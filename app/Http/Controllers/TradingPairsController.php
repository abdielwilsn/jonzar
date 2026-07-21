<?php

namespace App\Http\Controllers;

use App\Models\TradingPair;
use App\Models\Settings;
use App\Models\Investment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TradingPairsController extends Controller
{
    public function index()
    {
        $tradingPairs = TradingPair::ordered()->get();
        $settings = Settings::first();
        $this->updateStalePrices($tradingPairs);
        return view('admin.Plans.plans', compact('tradingPairs', 'settings'));
    }

    public function recentTrades()
    {
        $investments = Investment::with(['user', 'tradingPair'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        if ($investments->isEmpty()) {
            // Optional: flash a message or log
            session()->flash('info', 'You have no recent trades.');
        }
        // dd($investments);
        $settings = Settings::first() ?? new Settings(['currency' => 'USD']);
        return view('user.recent-trades', compact('investments', 'settings'));
    }

    public function store(Request $request)
    {
        try {
            $coinData = $this->fetchCoinGeckoData($request->coingecko_id);
            if (!$coinData) {
                return back()->with('error', 'Failed to fetch coin data from CoinGecko. Please verify the CoinGecko ID.');
            }

            $tradingPair = TradingPair::create([
                'coingecko_id' => $request->coingecko_id,
                'base_symbol' => strtoupper($coinData['symbol']),
                'base_name' => $coinData['name'],
                'quote_symbol' => $request->quote_symbol,
                'base_icon_url' => $coinData['image']['large'] ?? null,
                'current_price' => $coinData['market_data']['current_price']['usd'] ?? 0,
                'price_change_24h' => $coinData['market_data']['price_change_percentage_24h'] ?? 0,
                'market_cap' => $coinData['market_data']['market_cap']['usd'] ?? null,
                'volume_24h' => $coinData['market_data']['total_volume']['usd'] ?? null,
                'price_last_updated' => now(),
                'min_investment' => $request->min_investment,
                'max_investment' => $request->max_investment,
                'min_return_percentage' => $request->min_return_percentage,
                'max_return_percentage' => $request->max_return_percentage,
                'investment_duration' => $request->investment_duration,
                'max_investment_duration' => $request->max_investment_duration,

                'is_active' => $request->boolean('is_active', true)
            ]);

            return back()->with('success', 'Trading pair added successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating trading pair: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while creating the trading pair.');
        }
    }

    public function edit(TradingPair $tradingPair)
    {
        return response()->json($tradingPair);
    }

    public function create()
    {
        return view('admin.Plans.add-new-trading-pair');
    }

    public function update(Request $request, TradingPair $tradingPair)
    {
        $validator = Validator::make($request->all(), [
            'coingecko_id' => 'required|string|unique:trading_pairs,coingecko_id,' . $tradingPair->id,
            'quote_symbol' => 'required|string|in:USDT,USD,BTC,ETH',
            'min_investment' => 'required|numeric|min:0',
            'max_investment' => 'required|numeric|gt:min_investment',
            'min_return_percentage' => 'required|numeric|min:0',
            'max_return_percentage' => 'required|numeric|gt:min_return_percentage',
            'investment_duration' => 'required|integer|min:1',
            'max_investment_duration' => 'required|integer|min:1',

            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            if ($request->coingecko_id !== $tradingPair->coingecko_id) {
                $coinData = $this->fetchCoinGeckoData($request->coingecko_id);
                if (!$coinData) {
                    return back()->with('error', 'Failed to fetch coin data from CoinGecko. Please verify the CoinGecko ID.');
                }

                $tradingPair->update([
                    'coingecko_id' => $request->coingecko_id,
                    'base_symbol' => strtoupper($coinData['symbol']),
                    'base_name' => $coinData['name'],
                    'base_icon_url' => $coinData['image']['large'] ?? $tradingPair->base_icon_url,
                    'current_price' => $coinData['market_data']['current_price']['usd'] ?? $tradingPair->current_price,
                    'price_change_24h' => $coinData['market_data']['price_change_percentage_24h'] ?? $tradingPair->price_change_24h,
                    'market_cap' => $coinData['market_data']['market_cap']['usd'] ?? $tradingPair->market_cap,
                    'volume_24h' => $coinData['market_data']['total_volume']['usd'] ?? $tradingPair->volume_24h,
                    'price_last_updated' => now(),
                ]);
            }

            $tradingPair->update([
                'quote_symbol' => $request->quote_symbol,
                'min_investment' => $request->min_investment,
                'max_investment' => $request->max_investment,
                'min_return_percentage' => $request->min_return_percentage,
                'max_return_percentage' => $request->max_return_percentage,
                'investment_duration' => $request->investment_duration,
                'max_investment_duration' => $request->max_investment_duration,

                'is_active' => $request->boolean('is_active', true)
            ]);

            return back()->with('success', 'Trading pair updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating trading pair: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while updating the trading pair.');
        }
    }

    public function toggleStatus(TradingPair $tradingPair)
    {
        try {
            $tradingPair->update([
                'is_active' => !$tradingPair->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Trading pair status updated successfully',
                'is_active' => $tradingPair->is_active
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling trading pair status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the status'
            ], 500);
        }
    }

    public function destroy(TradingPair $tradingPair)
    {
        try {
            $tradingPair->delete();
            return response()->json([
                'success' => true,
                'message' => 'Trading pair deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting trading pair: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the trading pair'
            ], 500);
        }
    }

    public function refreshPrices()
    {
        try {
            TradingPair::updateAllPrices();
            $tradingPairs = TradingPair::active()->ordered()->get();
            return response()->json($tradingPairs->map(function ($pair) {
                return [
                    'id' => $pair->id,
                    'current_price' => $pair->current_price,
                    'price_change_24h' => $pair->price_change_24h
                ];
            }));
        } catch (\Exception $e) {
            Log::error('Error refreshing prices: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to refresh prices'
            ], 500);
        }
    }

    public function getPublicTradingPairs()
    {
        $tradingPairs = TradingPair::active()->ordered()->get();
        $this->updateStalePrices($tradingPairs);
        return response()->json($tradingPairs->map(function ($pair) {
            return [
                'id' => $pair->id,
                'pair_name' => $pair->pair_name,
                'base_symbol' => $pair->base_symbol,
                'base_name' => $pair->base_name,
                'quote_symbol' => $pair->quote_symbol,
                'base_icon_url' => $pair->base_icon_url,
                'current_price' => $pair->formatted_price,
                'price_change_24h' => $pair->price_change_24h,
                'price_change_color' => $pair->price_change_color,
                'min_investment' => $pair->min_investment,
                'max_investment' => $pair->max_investment,
                'min_return_percentage' => $pair->min_return_percentage,
                'max_return_percentage' => $pair->max_return_percentage,
                'investment_duration' => $pair->investment_duration
            ];
        }));
    }

    private function fetchCoinGeckoData($coingeckoId)
    {
        try {
            $response = Http::timeout(10)->get("https://api.coingecko.com/api/v3/coins/{$coingeckoId}", [
                'localization' => false,
                'tickers' => false,
                'market_data' => true,
                'community_data' => false,
                'developer_data' => false,
                'sparkline' => false
            ]);
            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error("Failed to fetch CoinGecko data for {$coingeckoId}: " . $e->getMessage());
        }
        return null;
    }

    private function updateStalePrices($tradingPairs)
    {
        $stalePairs = $tradingPairs->filter(function ($pair) {
            return $pair->isPriceStale();
        });
        if ($stalePairs->count() > 0) {
            TradingPair::updateAllPrices();
        }
    }

    public function updateSortOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pairs' => 'required|array',
            'pairs.*.id' => 'required|exists:trading_pairs,id',
            'pairs.*.sort_order' => 'required|integer|min:0'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data provided'
            ], 400);
        }
        try {
            foreach ($request->pairs as $pairData) {
                TradingPair::where('id', $pairData['id'])
                    ->update(['sort_order' => $pairData['sort_order']]);
            }
            return response()->json([
                'success' => true,
                'message' => 'Sort order updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating sort order: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating sort order'
            ], 500);
        }
    }

    public function userIndex()
    {
        // dd("hell");
        $tradingPairs = TradingPair::active()->ordered()->get();
        // dd($tradingPairs);
        $settings = Settings::first();
        $this->updateStalePrices($tradingPairs);
        return view('user.mplans', compact('tradingPairs', 'settings'));
    }

    public function showPair(TradingPair $tradingPair)
    {
        $settings = Settings::first();
        return view('user.invest_pair', compact('tradingPair', 'settings'));
    }

    public function invest(TradingPair $tradingPair)
    {
        $settings = Settings::first();
        return view('user.invest-trading-pair', compact('tradingPair', 'settings'));
    }

    public function chartFeed(TradingPair $tradingPair)
    {
        $intervalSeconds = 1;
        $points = 60;
        $now = microtime(true);
        $currentStep = (int) floor($now / $intervalSeconds);
        $secondsIntoStep = $now - ($currentStep * $intervalSeconds);
        $progress = max(0.0, min(1.0, $secondsIntoStep / $intervalSeconds));
        $startStep = $currentStep - ($points - 1);
        $seed = abs(crc32((string) $tradingPair->id));

        $line = [];
        $candles = [];

        for ($step = $startStep; $step <= $currentStep; $step++) {
            $isCurrentCandle = $step === $currentStep;

            // Open and close are pure functions of the absolute second, so every
            // completed candle keeps the exact same shape on every poll (no pulsing
            // as the 60s window slides). A candle's close == the next candle's open,
            // so the series is continuous with no gaps.
            $open = $this->pairPriceAt($seed, $step);
            $targetClose = $this->pairPriceAt($seed, $step + 1);

            if ($isCurrentCandle) {
                // Live candle: walk the close steadily from the open toward the next
                // second's price as the current second elapses. No mid-candle reversals.
                $close = max(1, $open + (($targetClose - $open) * $progress));
            } else {
                $close = $targetClose;
            }

            $upperWick = $this->pairWickAt($seed, $step);
            $lowerWick = $this->pairWickAt($seed + 31, $step);

            if ($isCurrentCandle) {
                // Wicks reach their full extent only as the second completes.
                $wickGrowth = 0.35 + (0.65 * $progress);
                $upperWick *= $wickGrowth;
                $lowerWick *= $wickGrowth;
            }

            $high = max($open, $close) + $upperWick;
            $low = max(1, min($open, $close) - $lowerWick);

            $line[] = [
                't' => $step * $intervalSeconds,
                'v' => round($close, 4),
            ];

            $candles[] = [
                't' => $step * $intervalSeconds,
                'o' => round($open, 4),
                'h' => round($high, 4),
                'l' => round($low, 4),
                'c' => round($close, 4),
            ];
        }

        $first = $line[0]['v'] ?? 0;
        $last = $line[count($line) - 1]['v'] ?? 0;
        $trend = $last >= $first ? 'up' : 'down';

        return response()->json([
            'success' => true,
            'pair' => strtoupper($tradingPair->base_symbol) . '/' . strtoupper($tradingPair->quote_symbol),
            'trend' => $trend,
            'line' => $line,
            'candles' => $candles,
            'interval' => $intervalSeconds,
            'candle_progress' => round($progress, 4),
            'generated_at' => now()->timestamp,
        ]);
    }

    /**
     * Deterministic price level at the boundary of a given second.
     *
     * Because it depends only on the absolute step (timestamp) — never on the
     * sliding window — every historical candle keeps the same open/close across
     * polls, and one candle's close equals the next candle's open (continuous).
     */
    private function pairPriceAt(int $seed, int $step): float
    {
        $base = 100 + ($seed % 17);
        $phase = ($seed % 360) * (M_PI / 180);

        $value = $base
            + sin(($step * 0.045) + $phase) * 9.0          // slow trend swell
            + sin(($step * 0.150) + $phase) * 5.0          // primary wave
            + sin(($step * 0.370) + ($phase * 0.5)) * 2.2  // medium chop
            + sin(($step * 0.910) + ($phase * 1.3)) * 1.0; // fine chop

        return max(1, $value);
    }

    /**
     * Deterministic wick reach for a given second, anchored to the absolute step
     * so historical wicks never change between polls.
     */
    private function pairWickAt(int $seed, int $step, float $scale = 1.0): float
    {
        $roll = $this->syntheticRoll($seed + 700, $step);

        return (0.4 + ($roll * 1.6)) * $scale;
    }

    private function syntheticRoll(int $seed, int $step): float
    {
        $hash = (float) sprintf('%u', crc32($seed . ':' . $step));

        return $hash / 4294967295;
    }

    public function storeInvestment(Request $request, TradingPair $tradingPair)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'amount' => [
                'required',
                'numeric',
                'min:' . $tradingPair->min_investment,
                'max:' . $tradingPair->max_investment,
                function ($attribute, $value, $fail) use ($user) {
                    if ($value > $user->account_bal) {
                        $fail('Insufficient balance for this trade.');
                    }
                }
            ],
            'duration' => [
                'required',
                'integer',
                'min:' . ($tradingPair->min_investment_duration ?? 1),
                'max:' . $tradingPair->max_investment_duration,
            ]
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $alreadyUsed = Investment::where('user_id', $user->id)
            ->where('trading_pair_id', $tradingPair->id)
            ->where('status', 'active')
            ->exists();

        if ($alreadyUsed) {
            return back()->withErrors(['message' => 'Already traded in this today, wait till tomorrow']);
        }

        try {
            \DB::beginTransaction();

            // Deduct amount from user balance
            $user->decrement('account_bal', $request->amount);

            // Get duration from request (user selected)
            $duration = (int) $request->duration;

            // Create investment
            Investment::create([
                'user_id' => auth()->id(),
                'trading_pair_id' => $tradingPair->id,
                'amount' => $request->amount,
                'duration' => $duration, // Store the selected duration
                'status' => 'active',
                'start_date' => now(),
                'end_date' => now()->addDays($duration), // Use user-selected duration
            ]);

            \DB::commit();

            return redirect()->route('user.recent-trades')->with('success', 'Trade placed successfully!');
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Error creating investment: ' . $e->getMessage());
            return back()->with('error', 'Failed to place investment.');
        }
    }
    /**
     * Platform-wide trade earnings: total profit made across all users and a
     * per-user breakdown ordered by top earners. "Trades" here are the
     * investments closed by CloseExpiredInvestmentsJob, whose `profit` is the
     * net amount the user keeps (after any referral commission).
     */
    public function tradeEarnings()
    {
        $settings = Settings::first() ?? new Settings(['currency' => 'USD']);

        // One row per user: their total net profit and number of closed trades.
        $earners = Investment::query()
            ->selectRaw('user_id, SUM(profit) as total_profit, COUNT(*) as trades_count')
            ->whereNotNull('profit')
            ->groupBy('user_id')
            ->orderByDesc('total_profit')
            ->with('user')
            ->get();

        $platformTotal = $earners->sum('total_profit');
        $earnersCount  = $earners->count();

        return view('admin.trade-earnings', compact('earners', 'platformTotal', 'earnersCount', 'settings'));
    }

    public function viewUserTrades(User $user)
    {
        $investments = Investment::with(['tradingPair'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $settings = Settings::first() ?? new Settings(['currency' => 'USD']);

        return view('admin.user-trades', compact('investments', 'user', 'settings'));
    }

    public function deleteUserTrade(Investment $investment)
    {
        try {
            \DB::beginTransaction();

            // Optionally refund the user's balance
            $user = User::find($investment->user_id);
            $user->increment('account_bal', $investment->amount);

            $investment->delete();

            \DB::commit();

            return redirect()->back()->with('success', 'Trade deleted successfully!');
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Error deleting user trade: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete trade.');
        }
    }


}
