<?php

namespace App\Http\Controllers;

use App\Helpers\Helper\Helper;
use App\Models\Admin;
use App\Models\Trade;
use App\Models\Transaction;
use App\Notifications\TradeCreatedNotification;
use Illuminate\Http\Request;
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

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://min-api.cryptocompare.com/data/v2/histominute?fsym={$request->currency}&tsym=USD&limit=40&api_key=" . $general->crypto_api,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        $result = json_decode($response);


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

        $currency = $request->currency;

        $data = json_decode(file_get_contents("https://min-api.cryptocompare.com/data/price?fsym={$request->currency}&tsyms=USD&api_key=" . $general->crypto_api), true);


        $result = reset($data);

        return response()->json($result);
    }

    public function trades()
    {
        $data['trades'] = Trade::where('user_id', auth()->id())->paginate(Helper::pagination());

        $data['title'] = 'Trades List';

        return view(Helper::theme() . 'user.trade_list')->with($data);
    }


    public function openTrade(Request $request)
    {
        $request->validate([
            "trade_cur" => "required",
            "trade_price" => "required",
            "trade_amount" => "required|numeric|gt:0",
            "type" => "required|in:buy,sell",
            "duration" => "required|in:0.5,1,1.5,2" // restrict values
        ]);

        $user = auth()->user();

        if ($user->trades->count() >= Helper::config()->trade_limit) {
            return redirect()->back()->with('error', 'Per Day Trading Limit expired');
        }

        if ($user->payments->count() <= 0) {
            return redirect()->back()->with('error', 'You need to subscribe a plan to trade');
        }

        if ($request->trade_amount < Helper::config()->min_trade_balance) {
            return redirect()->back()->with('error', 'Minimum trade amount is ' . Helper::formatter(Helper::config()->min_trade_balance));
        }

        if ($user->balance < $request->trade_amount) {
            return redirect()->back()->with('error', 'Insufficient balance for this trade amount');
        }

        // ✅ Convert minutes → seconds
        $durationInSeconds = $request->duration * 60;

        $ref = Str::random(16);

        $trade = Trade::create([
            'ref' => $ref,
            'user_id' => auth()->id(),
            'currency' => $request->trade_cur,
            'current_price' => $request->trade_price,
            'trade_amount' => $request->trade_amount,
            'trade_type' => $request->type,
            'duration' => $durationInSeconds, // store seconds
            'trade_stop_at' => now()->addSeconds($durationInSeconds), // correct
            'trade_opens_at' => now()
        ]);

        $admin = Admin::where('type', 'super')->first();

        if ($admin) {
            $admin->notify(new TradeCreatedNotification($trade));
        }

        return redirect()->back()->with('success', 'Trade Open Successfully');
    }

    public function tradeClose()
    {
        $trades = Trade::where('user_id', auth()->id())->where('status', 0)->get();
        $this->settleTrades($trades);
    }


    public function tradingInterest()
    {
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
                continue;
            }

            $stake = (float) ($trade->trade_amount ?? 0);
            $profitPercent = (int) ($trade->user->trade_profit_percent ?? 1);
            $profit = $stake * ($profitPercent / 100.0);
            $marketResult = $this->determineMarketResult($trade->trade_type, (float) $trade->current_price, $currentPrice);
            $winRate = (int) ($trade->user->trade_win_rate ?? 50);
            $finalResult = rand(1, 100) <= $winRate;

            if ($finalResult) {
                $charge = ($config->trade_charge / 100) * $profit;
                $userAmount = $profit - $charge;
                $type = '+';

                $trade->profit_type = $type;
                $trade->profit_amount = $profit;
                $trade->loss_amount = 0;
                $trade->charge = $charge;
                $trade->status = 1;

                $trade->user->balance += $userAmount;
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
                'details' => 'Trade Return',
                'charge' => $charge,
                'type' => $type,
                'user_id' => $trade->user->id
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
        $data = json_decode(file_get_contents("https://min-api.cryptocompare.com/data/price?fsym={$currency}&tsyms=USD&api_key={$apiKey}"), true);
        $price = reset($data);

        if (! is_numeric($price)) {
            return null;
        }

        return (float) $price;
    }

    private function determineMarketResult(string $tradeType, float $openPrice, float $closePrice): ?bool
    {
        if ($closePrice == $openPrice) {
            return null;
        }

        if ($tradeType === 'buy') {
            return $closePrice > $openPrice;
        }

        return $closePrice < $openPrice;
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

    private function applyBiasResult(?bool $marketResult, int $tradeWinRate): ?bool
    {
        if ($marketResult === null) {
            return null;
        }

        $winRate = max(0, min(100, $tradeWinRate));
        $shouldWin = random_int(1, 100) <= $winRate;

        return $shouldWin ? $marketResult : ! $marketResult;
    }
}
