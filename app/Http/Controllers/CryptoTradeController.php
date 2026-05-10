<?php

namespace App\Http\Controllers;

use App\Helpers\Helper\Helper;
use App\Models\Admin;
use App\Models\Trade;
use App\Models\Transaction;
use App\Notifications\TradeCreatedNotification;
use Illuminate\Http\Request;
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
        $chartData = [];

        foreach ($this->buildSyntheticCandles($request->currency) as $key => $value) {
            $chartData[$key] = [
                'time' => $value['time'],
                'open' => $value['open'],
                'high' => $value['high'],
                'low' => $value['low'],
                'close' => $value['close'],
                'x' => $value['time'],
                'y' => [$value['open'], $value['high'], $value['low'], $value['close']]
            ];
        }

        return response()->json($chartData);
    }

    public function currentPrice(Request $request)
    {
        return response()->json($this->syntheticSpotPrice($request->currency));
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
            "trade_price" => "required",
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

        $ref = Str::random(16);
        $tradePayload = [
            'ref' => $ref,
            'user_id' => auth()->id(),
            'currency' => $request->trade_cur,
            'current_price' => $request->trade_price,
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
        return $this->syntheticSpotPrice($currency);
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

    private function resolveApiCurrencySymbol(?string $currency): string
    {
        $normalized = strtoupper((string) $currency);

        if (str_contains($normalized, '_')) {
            return explode('_', $normalized)[0];
        }

        if (str_contains($normalized, '/')) {
            return explode('/', $normalized)[0];
        }

        return $normalized;
    }

    private function buildSyntheticCandles(?string $currency, int $limit = 80, int $intervalSeconds = 60): array
    {
        $limit = max(1, min(300, $limit));
        $currentBucket = intdiv(now()->timestamp, $intervalSeconds) * $intervalSeconds;
        $startBucket = $currentBucket - (($limit - 1) * $intervalSeconds);
        $candles = [];

        for ($time = $startBucket; $time <= $currentBucket; $time += $intervalSeconds) {
            $open = $this->syntheticSpotPriceAt($currency, $time - $intervalSeconds);
            $close = $this->syntheticSpotPriceAt($currency, $time);
            $mid = $this->syntheticSpotPriceAt($currency, $time - (int) ($intervalSeconds / 2));
            $padding = $this->syntheticCandlePadding($currency, $time, $open, $close);

            $candles[] = [
                'time' => $time,
                'open' => $open,
                'high' => $this->roundMarketPrice(max($open, $close, $mid) + $padding),
                'low' => $this->roundMarketPrice(max(0.00000001, min($open, $close, $mid) - $padding)),
                'close' => $close,
            ];
        }

        return $candles;
    }

    private function syntheticSpotPrice(?string $currency): float
    {
        return $this->syntheticSpotPriceAt($currency, now()->timestamp);
    }

    private function syntheticSpotPriceAt(?string $currency, int $timestamp): float
    {
        $symbol = $this->resolveApiCurrencySymbol($currency);
        $base = $this->syntheticBasePrice($symbol);
        $seed = $this->symbolSeed($symbol);

        $drift = sin(($timestamp / 86400) + ($seed / 250)) * 0.012;
        $longWave = sin(($timestamp / 3600) + ($seed / 1000)) * 0.018;
        $mediumWave = sin(($timestamp / 240) + $seed) * 0.008;
        $shortWave = cos(($timestamp / 35) + ($seed / 17)) * 0.002;
        $price = $base * (1 + $drift + $longWave + $mediumWave + $shortWave);

        return $this->roundMarketPrice(max(0.00000001, $price));
    }

    private function syntheticCandlePadding(?string $currency, int $timestamp, float $open, float $close): float
    {
        $symbol = $this->resolveApiCurrencySymbol($currency);
        $base = $this->syntheticBasePrice($symbol);
        $seed = $this->symbolSeed($symbol);
        $movement = abs($close - $open);
        $minimumRange = $base * 0.0012;
        $rangeWave = 0.6 + abs(sin(($timestamp / 57) + $seed));

        return max($movement * 0.35, $minimumRange) * $rangeWave;
    }

    private function syntheticBasePrice(string $symbol): float
    {
        $prices = [
            'BTC' => 65000.0,
            'ETH' => 3200.0,
            'BNB' => 600.0,
            'DOGE' => 0.15,
            'LTC' => 85.0,
            'XAUT' => 2350.0,
            'BTS' => 0.01,
        ];

        return $prices[$symbol] ?? (10 + ($this->symbolSeed($symbol) % 1000));
    }

    private function symbolSeed(string $symbol): int
    {
        return (int) sprintf('%u', crc32($symbol));
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
