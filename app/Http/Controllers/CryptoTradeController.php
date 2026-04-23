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
        $general = Helper::config();
        $curl = curl_init();
        $apiCurrency = $this->resolveApiCurrencySymbol($request->currency);
        $url = "https://min-api.cryptocompare.com/data/v2/histominute?fsym={$apiCurrency}&tsym=USD&limit=40&api_key=" . $general->crypto_api;

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        $curlError = curl_error($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($response === false) {
            Log::error('Trade latestTicker request failed', [
                'currency' => $request->currency,
                'url' => $url,
                'curl_error' => $curlError,
                'http_code' => $statusCode,
            ]);
            curl_close($curl);

            return response()->json([]);
        }

        $result = json_decode($response);

        if (!isset($result->Data->Data) || !is_array($result->Data->Data)) {
            Log::warning('Trade latestTicker returned unexpected payload', [
                'currency' => $request->currency,
                'url' => $url,
                'http_code' => $statusCode,
                'response' => $response,
            ]);
            curl_close($curl);

            return response()->json([]);
        }

        $hvoc = $result->Data->Data;

        $chartData = [];

        foreach ($hvoc as $key => $value) {
            $chartData[$key] = [
                'x' => $value->time,
                'y' => [$value->open, $value->high, $value->low, $value->close]
            ];
        }

        curl_close($curl);

        return response()->json($chartData);
    }

    public function currentPrice(Request $request)
    {
        $general = Helper::config();
        $apiCurrency = $this->resolveApiCurrencySymbol($request->currency);
        $url = "https://min-api.cryptocompare.com/data/price?fsym={$apiCurrency}&tsyms=USD&api_key=" . $general->crypto_api;

        try {
            $data = json_decode(file_get_contents($url), true);
            $result = is_array($data) ? reset($data) : null;

            if (!is_numeric($result)) {
                Log::warning('Trade currentPrice returned invalid data', [
                    'currency' => $request->currency,
                    'url' => $url,
                    'response' => $data,
                ]);
            }

            return response()->json($result);
        } catch (\Throwable $exception) {
            Log::error('Trade currentPrice request failed', [
                'currency' => $request->currency,
                'url' => $url,
                'message' => $exception->getMessage(),
            ]);

            return response()->json(null, 500);
        }
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

        $allowedTradeTypes = implode(',', Trade::allowedTradeTypes());

        $request->validate([
            "trade_cur" => "required",
            "trade_price" => "required",
            "trade_amount" => "required|numeric|gt:0",
            "type" => "required|in:" . $allowedTradeTypes,
            "duration" => "required|in:0.5,1,1.5,2" // restrict values
        ]);

        $user = auth()->user();

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

        $ref = Str::random(16);
        $tradePayload = [
            'ref' => $ref,
            'user_id' => auth()->id(),
            'currency' => $request->trade_cur,
            'current_price' => $request->trade_price,
            'trade_amount' => $request->trade_amount,
            'trade_type' => Trade::normalizeTradeType($request->type),
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

            $currentPrice = $this->fetchCurrentPrice($trade->currency, $config->crypto_api);

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

    private function fetchCurrentPrice(string $currency, string $apiKey): ?float
    {
        $apiCurrency = $this->resolveApiCurrencySymbol($currency);
        $url = "https://min-api.cryptocompare.com/data/price?fsym={$apiCurrency}&tsyms=USD&api_key={$apiKey}";

        try {
            $data = json_decode(file_get_contents($url), true);
            $price = is_array($data) ? reset($data) : null;

            if (! is_numeric($price)) {
                Log::warning('Trade fetchCurrentPrice returned invalid payload', [
                    'currency' => $currency,
                    'url' => $url,
                    'response' => $data,
                ]);
                return null;
            }

            return (float) $price;
        } catch (\Throwable $exception) {
            Log::error('Trade fetchCurrentPrice failed', [
                'currency' => $currency,
                'url' => $url,
                'message' => $exception->getMessage(),
            ]);

            return null;
        }
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
}
