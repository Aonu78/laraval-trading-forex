<?php

namespace App\Http\Controllers;

use App\Helpers\Helper\Helper;
use App\Models\Admin;
use App\Models\Trade;
use App\Models\Transaction;
use App\Notifications\TradeCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CryptoTradeController extends Controller
{

    public function index(Request $request)
    {
        $data['title'] = 'Trade';

        $data['trades'] = Trade::when($request->trx, function ($item) use ($request) {
            $item->where('ref', $request->trx);
        })->when($request->date, function ($item) use ($request) {
            $item->whereDate('trade_opens_at', $request->date);
        })->where('user_id', auth()->id())->orderBy('id', 'desc')->paginate(Helper::pagination());
        
        $data['activeTrade'] = Trade::where('user_id', auth()->id())
            ->where('status', 0) // running
            ->latest()
            ->first();
        return view(Helper::theme() . 'user.trading')->with($data);
    }

    public function latestTicker(Request $request)
    {
        $symbol = $this->resolveBinanceSymbol($request->currency);

        if ($symbol === null) {
            return response()->json(['message' => 'Unsupported live market symbol.'], 422);
        }

        $chartData = $this->fetchBinanceCandles($symbol);

        if ($chartData === null) {
            return response()->json(['message' => 'Live market candles are unavailable.'], 502);
        }

        return response()->json($chartData);
    }

    public function currentPrice(Request $request)
    {
        $price = $this->fetchCurrentPrice((string) $request->currency);

        if ($price === null) {
            return response()->json(['message' => 'Live market price is unavailable.'], 502);
        }

        return response()->json($price);
    }

    public function trades()
    {
        $data['trades'] = Trade::where('user_id', auth()->id())->paginate(Helper::pagination());

        $data['title'] = 'Trades List';

        return view(Helper::theme() . 'user.trade_list')->with($data);
    }


    public function openTrade(Request $request)
    {
        Log::info('Trade open requested', [
            'user_id' => auth()->id(),
            'payload' => $request->only(['trade_cur', 'trade_price', 'trade_amount', 'type', 'duration']),
        ]);

        $user = auth()->user();

        if ($user->is_trade_blocked) {
            Log::info('Trade open silently blocked by admin', [
                'user_id' => $user->id,
            ]);

            return redirect()->back();
        }

        $allowedTradeTypes = implode(',', Trade::allowedTradeTypes());

        $request->validate([
            "trade_cur" => "required",
            "trade_price" => "nullable|numeric",
            "trade_amount" => "required|numeric|gt:0",
            "type" => "required|in:" . $allowedTradeTypes,
            "duration" => "required|in:0.5,1,1.5,2" // restrict values
        ]);

        if ($user->trades->count() >= Helper::config()->trade_limit) {
            Log::warning('Trade open blocked by daily limit', [
                'user_id' => $user->id,
                'trade_count' => $user->trades->count(),
                'trade_limit' => Helper::config()->trade_limit,
            ]);
            return redirect()->back()->with('error', 'Per Day Trading Limit expired');
        }

        if ($request->trade_amount < Helper::config()->min_trade_balance) {
            Log::warning('Trade open blocked by minimum trade balance', [
                'user_id' => $user->id,
                'trade_amount' => $request->trade_amount,
                'minimum_trade_balance' => Helper::config()->min_trade_balance,
            ]);
            return redirect()->back()->with('error', 'Minimum trade amount is ' . Helper::formatter(Helper::config()->min_trade_balance));
        }

        if ($user->balance < $request->trade_amount) {
            Log::warning('Trade open blocked by insufficient balance', [
                'user_id' => $user->id,
                'user_balance' => $user->balance,
                'trade_amount' => $request->trade_amount,
            ]);
            return redirect()->back()->with('error', 'Insufficient balance for this trade amount');
        }

        // ✅ Convert minutes → seconds
        $durationInSeconds = $request->duration * 60;
        $isFirstTrade = ! Trade::where('user_id', $user->id)->exists();
        $stakeAmount = (float) $request->trade_amount;
        $openPrice = $this->fetchCurrentPrice((string) $request->trade_cur);

        if ($openPrice === null) {
            Log::warning('Trade open blocked because live price is unavailable', [
                'user_id' => $user->id,
                'currency' => $request->trade_cur,
            ]);

            return redirect()->back()->with('error', 'Live market price is unavailable for this pair');
        }

        $ref = Str::random(16);
        $tradePayload = [
            'ref' => $ref,
            'user_id' => auth()->id(),
            'currency' => $request->trade_cur,
            'current_price' => $openPrice,
            'trade_amount' => $request->trade_amount,
            'trade_type' => Trade::normalizeTradeType($request->type),
            'result_mode' => $isFirstTrade ? Trade::RESULT_MODE_FORCE_WIN : Trade::RESULT_MODE_DEFAULT,
            'force_profit_amount' => $isFirstTrade ? ($stakeAmount * 0.5) : null,
            'duration' => $durationInSeconds,
            'trade_stop_at' => now()->addSeconds($durationInSeconds),
            'trade_opens_at' => now()
        ];

        try {
            $trade = Trade::create($tradePayload);
            Log::info('Trade created successfully', [
                'user_id' => $user->id,
                'trade_id' => $trade->id,
                'ref' => $trade->ref,
                'payload' => $tradePayload,
            ]);
        } catch (\Throwable $exception) {
            Log::error('Trade creation failed', [
                'user_id' => $user->id,
                'payload' => $tradePayload,
                'message' => $exception->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Trade open failed. Check server log.');
        }

        try {
            $admin = Admin::where('type', 'super')->first();

            if ($admin) {
                $admin->notify(new TradeCreatedNotification($trade));
            }
        } catch (\Throwable $exception) {
            Log::error('Trade notification failed', [
                'user_id' => $user->id,
                'trade_id' => $trade->id,
                'message' => $exception->getMessage(),
            ]);
        }

        return redirect()->back()->with('success', 'Trade Open Successfully');
    }

    public function tradeClose()
    {
        Log::info('Manual trade close requested', [
            'user_id' => auth()->id(),
        ]);

        $trades = Trade::where('user_id', auth()->id())->where('status', 0)->get();
        $this->settleTrades($trades);
    }


    public function tradingInterest()
    {
        Log::info('Trading interest settlement started', [
            'open_trades_count' => Trade::where('status', 0)->count(),
        ]);

        $trades = Trade::where('status', 0)->get();
        $this->settleTrades($trades);
    }

    private function settleTrades($trades): void
    {
        $config = Helper::config();

        foreach ($trades as $trade) {
            if (! $trade->trade_stop_at->lte(now())) {
                continue;
            }

            $currentPrice = $this->fetchCurrentPrice($trade->currency);

            if ($currentPrice === null) {
                Log::warning('Trade settlement skipped because current price is unavailable', [
                    'trade_id' => $trade->id,
                    'ref' => $trade->ref,
                    'currency' => $trade->currency,
                ]);
                continue;
            }

            $stake = (float) ($trade->trade_amount ?? 0);
            $profit = $this->resolveTradeProfit($trade, $stake);
            $marketResult = $this->determineMarketResult(
                $trade->trade_type,
                (float) $trade->current_price,
                (float) $currentPrice
            );
            $finalResult = $this->resolveTradeResult($trade, $marketResult);

            if ($finalResult === null) {
                $this->closeNeutralTrade($trade);

                Log::info('Trade settled as neutral', [
                    'trade_id' => $trade->id,
                    'ref' => $trade->ref,
                    'user_id' => $trade->user->id,
                    'current_price' => $currentPrice,
                ]);

                continue;
            }

            if ($finalResult) {
                $charge = ($config->trade_charge / 100) * $profit;
                $userAmount = $profit - $charge;
                $type = '+';

                $trade->profit_type = $type;
                $trade->profit_amount = $profit;
                $trade->loss_amount = 0;
                $trade->charge = $charge;
                $trade->status = 1;

                if ($trade->user->is_account_freeze) {
                    $trade->user->freeze_balance += $userAmount;
                } else {
                    $trade->user->balance += $userAmount;
                }
                $trade->user->save();
            } else {
                $charge = 0;
                $type = '-';

                $trade->profit_type = $type;
                $trade->profit_amount = 0;
                $trade->loss_amount = $stake;
                $trade->charge = 0;
                $trade->status = 1;

                $trade->user->balance -= $stake;
                $trade->user->save();
            }

            $trade->save();

            Transaction::create([
                'trx' => $trade->ref,
                'amount' => $finalResult ? $profit : $stake,
                'details' => $finalResult && $trade->user->is_account_freeze
                    ? 'Trade Return To Freeze Balance'
                    : 'Trade Return',
                'charge' => $charge,
                'type' => $type,
                'user_id' => $trade->user->id
            ]);

            Log::info('Trade settled successfully', [
                'trade_id' => $trade->id,
                'ref' => $trade->ref,
                'user_id' => $trade->user->id,
                'result' => $type,
                'profit' => $trade->profit_amount,
                'loss' => $trade->loss_amount,
                'charge' => $charge,
                'current_price' => $currentPrice,
            ]);
        }
    }

    private function closeNeutralTrade(Trade $trade): void
    {
        $trade->profit_type = '=';
        $trade->profit_amount = 0;
        $trade->loss_amount = 0;
        $trade->charge = 0;
        $trade->status = 1;
        $trade->save();

        Transaction::create([
            'trx' => $trade->ref,
            'amount' => 0,
            'details' => 'Trade Return',
            'charge' => 0,
            'type' => '+',
            'user_id' => $trade->user->id
        ]);
    }

    private function fetchCurrentPrice(string $currency, ?string $apiKey = null): ?float
    {
        $symbol = $this->resolveBinanceSymbol($currency);

        if ($symbol === null) {
            return null;
        }

        return $this->fetchBinancePrice($symbol);
    }

    private function fetchBinancePrice(string $symbol): ?float
    {
        foreach ($this->binanceRestEndpoints() as $endpoint) {
            try {
                $response = Http::timeout(5)
                    ->get($endpoint . '/api/v3/ticker/price', [
                    'symbol' => $symbol,
                ]);

                if (! $response->successful()) {
                    Log::warning('Binance price request failed', [
                        'endpoint' => $endpoint,
                        'symbol' => $symbol,
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);

                    continue;
                }

                $price = (float) $response->json('price');

                if ($price > 0) {
                    return $this->roundMarketPrice($price);
                }
            } catch (\Throwable $exception) {
                Log::warning('Binance price request exception', [
                    'endpoint' => $endpoint,
                    'symbol' => $symbol,
                    'message' => $exception->getMessage(),
                ]);
            }
        }

        return null;
    }

    private function fetchBinanceCandles(string $symbol, string $interval = '1m', int $limit = 80): ?array
    {
        foreach ($this->binanceRestEndpoints() as $endpoint) {
            try {
                $response = Http::timeout(8)
                    ->get($endpoint . '/api/v3/klines', [
                    'symbol' => $symbol,
                    'interval' => $interval,
                    'limit' => max(1, min(300, $limit)),
                ]);

                if (! $response->successful()) {
                    Log::warning('Binance candles request failed', [
                        'endpoint' => $endpoint,
                        'symbol' => $symbol,
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);

                    continue;
                }

                return collect($response->json())->map(function ($candle) {
                    $time = (int) floor(((int) $candle[0]) / 1000);
                    $open = $this->roundMarketPrice((float) $candle[1]);
                    $high = $this->roundMarketPrice((float) $candle[2]);
                    $low = $this->roundMarketPrice((float) $candle[3]);
                    $close = $this->roundMarketPrice((float) $candle[4]);

                    return [
                        'time' => $time,
                        'open' => $open,
                        'high' => $high,
                        'low' => $low,
                        'close' => $close,
                        'x' => $time,
                        'y' => [$open, $high, $low, $close],
                    ];
                })->values()->all();
            } catch (\Throwable $exception) {
                Log::warning('Binance candles request exception', [
                    'endpoint' => $endpoint,
                    'symbol' => $symbol,
                    'message' => $exception->getMessage(),
                ]);
            }
        }

        return null;
    }

    private function binanceRestEndpoints(): array
    {
        return [
            'https://data-api.binance.vision',
            'https://api.binance.com',
        ];
    }

    private function determineMarketResult(string $tradeType, float $openPrice, float $closePrice): ?bool
    {
        if ($closePrice == $openPrice) {
            return null;
        }

        return Trade::tradeDirectionFromType($tradeType) === 'up'
            ? $closePrice > $openPrice
            : $closePrice < $openPrice;
    }

    private function calculateTradeAmount(Trade $trade, float $closePrice): float
    {
        $stake = (float) ($trade->trade_amount ?? 0);
        $openPrice = (float) $trade->current_price;

        if ($stake <= 0 || $openPrice <= 0) {
            return abs($closePrice - $openPrice);
        }

        $movePercent = abs($closePrice - $openPrice) / $openPrice;

        return $stake * $movePercent;
    }

    private function resolveTradeResult(Trade $trade, ?bool $marketResult): ?bool
    {
        if ($trade->result_mode === Trade::RESULT_MODE_FORCE_WIN) {
            return true;
        }

        if ($trade->result_mode === Trade::RESULT_MODE_FORCE_LOSS) {
            return false;
        }

        $winRate = (int) ($trade->user->trade_win_rate ?? 50);

        return $this->applyBiasResult($marketResult, $winRate);
    }

    private function resolveTradeProfit(Trade $trade, float $stake): float
    {
        if (
            $trade->result_mode === Trade::RESULT_MODE_FORCE_WIN
            && $trade->force_profit_amount !== null
        ) {
            return (float) $trade->force_profit_amount;
        }

        $profitPercent = (int) ($trade->user->trade_profit_percent ?? 1);

        return $stake * ($profitPercent / 100.0);
    }

    private function applyBiasResult(?bool $marketResult, int $tradeWinRate): ?bool
    {
        if ($marketResult === null) {
            return null;
        }

        $winRate = max(0, min(100, $tradeWinRate));
        $shouldWin = random_int(1, 100) <= $winRate;

        return $shouldWin ? $marketResult : ! $marketResult;
    }

    private function resolveBinanceSymbol(?string $currency): ?string
    {
        $normalized = strtoupper(trim((string) $currency));
        $normalized = str_replace(['/', '-'], '_', $normalized);

        $aliases = [
            'BTC' => 'BTC_USDT',
            'ETH' => 'ETH_USDT',
            'BNB' => 'BNB_USDT',
            'DOGE' => 'DOGE_USDT',
            'LTC' => 'LTC_USDT',
            'DASH' => 'DASH_USDT',
            'ETC' => 'ETC_USDT',
            'BCH' => 'BCH_USDT',
        ];

        $normalized = $aliases[$normalized] ?? $normalized;

        if (! str_contains($normalized, '_')) {
            $normalized .= '_USDT';
        }

        [$base, $quote] = array_pad(explode('_', $normalized, 2), 2, null);

        if (! $base || ! $quote || $base === $quote) {
            return null;
        }

        $symbol = $base . $quote;

        return preg_match('/^[A-Z0-9]+$/', $symbol) ? $symbol : null;
    }

    private function roundMarketPrice(float $price): float
    {
        if ($price >= 1000) {
            return round($price, 2);
        }

        if ($price >= 1) {
            return round($price, 4);
        }

        return round($price, 8);
    }
}
