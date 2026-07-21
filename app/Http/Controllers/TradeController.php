<?php

namespace App\Http\Controllers;

use App\Models\TradingPair;
use App\Models\Trade;
use App\Models\User;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class TradeController extends Controller
{
public function index()
{
    $currencies = TradingPair::where('is_active', true)->get();
    
    // Get open positions (buy orders that haven't been sold)
    $openTrades = Trade::where('user_id', Auth::id())
        ->open()
        ->buys() // Only show buy positions as "open trades"
        ->with('user')
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($trade) {
            $currentPrice = $this->getCurrentPrice($trade->pair_base);
            $trade->current_price = $currentPrice ?? 0;
            
            // Use the model method to calculate unrealized P/L
            $trade->unrealized_pl = $trade->calculateUnrealizedPL($currentPrice);
            
            return $trade;
        });

    // Get recent trade history (last 10 closed trades)
    $recentTrades = Trade::where('user_id', Auth::id())
        ->closed()
        ->with('user')
        ->orderBy('closed_at', 'desc')
        ->limit(10)
        ->get();

    // Get pending orders
    $pendingOrders = Trade::where('user_id', Auth::id())
        ->pending()
        ->with('user')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('user.trade', compact('currencies', 'openTrades', 'recentTrades', 'pendingOrders'));
}

    

    public function getPairData(Request $request, $symbol)
    {
        $pair = TradingPair::where('base_currency', strtolower($symbol))
            ->where('is_active', true)
            ->first();

        if (!$pair) {
            Log::warning('Invalid trading pair requested: ' . $symbol);
            return response()->json([
                'success' => false,
                'error' => 'Invalid trading pair'
            ], 404);
        }

        $cacheKey = "price:{$pair->coingecko_id}";
        $cachedData = Cache::get($cacheKey);

        if ($cachedData) {
            return response()->json([
                'success' => true,
                'price' => $cachedData['price'],
                'change' => $cachedData['change']
            ]);
        }

        try {
            $response = Http::get('https://api.coingecko.com/api/v3/coins/markets', [
                'ids' => $pair->coingecko_id,
                'vs_currency' => 'usd',
                'price_change_percentage' => '24h'
            ]);

            // dd($response);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data[0]['current_price'])) {
                    $price = $data[0]['current_price'];
                    $change = number_format($data[0]['price_change_percentage_24h'] ?? 0, 2) . '% ($' . number_format($data[0]['price_change_24h'] ?? 0, 2) . ')';

                    Cache::put($cacheKey, [
                        'price' => $price,
                        'change' => $change
                    ], now()->addMinutes(2));

                    return response()->json([
                        'success' => true,
                        'price' => $price,
                        'change' => $change
                    ]);
                }
                Log::warning('No price data found for coingecko_id: ' . $pair->coingecko_id);
                return response()->json([
                    'success' => false,
                    'error' => 'Price data unavailable'
                ], 404);
            }

            Log::error('CoinGecko API failed: ' . $response->status());
            return response()->json([
                'success' => false,
                'error' => 'Price data unavailable'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Pair data fetch error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch pair data'
            ], 500);
        }
    }

   public function executeTrade(Request $request)
{

    // dd("hello");
    // $validated = $request->validate([
    //     'amount' => 'required|numeric|min:0.00000001',
    //     'price' => 'required_if:order_type,limit,stop|numeric|min:0',
    //     'order_type' => 'required|in:market,limit,stop',
    //     'stop_loss' => 'nullable|numeric|min:0',
    //     'take_profit' => 'nullable|numeric|min:0',
    //     'type' => 'required|in:buy,sell',
    //     'pair' => 'required|string'
    // ]);

    $user = Auth::user();
    $pair = TradingPair::where('base_currency', strtolower($request->pair))
        ->where('is_active', true)
        ->first();

        // dd($pair);

    if (!$pair) {
        return back()->with('error', 'Invalid trading pair');
    }

    // Get current market price
    $currentPrice = $this->getCurrentPrice($pair->base_currency);
    if (!$currentPrice) {
        return back()->with('error', 'Failed to fetch current market price');
    }

    // dd("here");

    // Determine execution price based on order type
    $executionPrice = $request->order_type === 'market' ? $currentPrice : $request->price;

    // Validate limit/stop order prices
    if ($request->order_type === 'limit') {
        if ($request->type === 'buy' && $executionPrice > $currentPrice) {
            return back()->with('error', 'Buy limit price must be below current market price');
        }
        if ($request->type === 'sell' && $executionPrice < $currentPrice) {
            return back()->with('error', 'Sell limit price must be above current market price');
        }
    }

    if ($request->order_type === 'stop') {
        if ($request->type === 'buy' && $executionPrice < $currentPrice) {
            return back()->with('error', 'Buy stop price must be above current market price');
        }
        if ($request->type === 'sell' && $executionPrice > $currentPrice) {
            return back()->with('error', 'Sell stop price must be below current market price');
        }
    }

    $totalValue = $request->amount * $executionPrice;
    $fee = $totalValue * 0.001; // 0.1% fee

    \DB::beginTransaction();
    try {
        if ($request->type === 'buy') {
            $totalCost = $totalValue + $fee;
            
            // Check if user has sufficient balance
            if ($totalCost > $user->account_bal) {
                \DB::rollBack();
                return back()->with('error', 'Insufficient balance. Required: $' . number_format($totalCost, 2));
            }

            // Deduct cost from user balance
            $user->account_bal -= $totalCost;
            
            // Create buy trade record
            $trade = Trade::create([
                'user_id' => $user->id,
                'pair_base' => $pair->base_currency,
                'quote_currency' => $pair->quote_currency,
                'type' => 'buy',
                'order_type' => $request->order_type,
                'entry_price' => $executionPrice,
                'volume' => $request->amount,
                'amount' => $totalValue,
                'fee' => $fee,
                'stop_loss' => $request->stop_loss,
                'take_profit' => $request->take_profit,
                'status' => $request->order_type === 'market' ? 'open' : 'pending',
                'executed_at' => $request->order_type === 'market' ? now() : null,
            ]);

            Log::info('Buy order created', [
                'user_id' => $user->id,
                'trade_id' => $trade->id,
                'pair' => $pair->base_currency . '/' . $pair->quote_currency,
                'amount' => $request->amount,
                'price' => $executionPrice,
                'total_cost' => $totalCost
            ]);

        } else { // SELL ORDER
            
            // Get user's open positions for this pair
            $openPositions = Trade::where('user_id', $user->id)
                ->where('pair_base', $pair->base_currency)
                ->where('type', 'buy')
                ->where('status', 'open')
                ->orderBy('created_at', 'asc') // FIFO (First In, First Out)
                ->get();

            $totalAvailableVol = $openPositions->sum('volume');

            if ($totalAvailableVol < $request->amount) {
                \DB::rollBack();
                return back()->with('error', 'Insufficient ' . strtoupper($pair->base_currency) . ' balance. Available: ' . number_format($totalAvailableVol, 8));
            }

            $remainingToSell = $request->amount;
            $totalProfitLoss = 0;
            $totalOriginalCost = 0;

            // Process positions FIFO
            foreach ($openPositions as $position) {
                if ($remainingToSell <= 0) break;

                $sellFromThisPosition = min($remainingToSell, $position->volume);
                
                // Calculate P/L for this portion
                $originalCostPerUnit = $position->entry_price;
                $profitLossPerUnit = $executionPrice - $originalCostPerUnit;
                $portionProfitLoss = $profitLossPerUnit * $sellFromThisPosition;
                $portionOriginalCost = $originalCostPerUnit * $sellFromThisPosition;
                
                $totalProfitLoss += $portionProfitLoss;
                $totalOriginalCost += $portionOriginalCost;

                // Update the original position
                if ($sellFromThisPosition >= $position->volume) {
                    // Completely close this position
                    $position->status = 'closed';
                    $position->closed_at = now();
                    $position->volume = 0;
                } else {
                    // Partially close this position
                    $position->volume -= $sellFromThisPosition;
                }
                
                $position->save();
                $remainingToSell -= $sellFromThisPosition;

                Log::info('Position updated during sell', [
                    'position_id' => $position->id,
                    'sold_amount' => $sellFromThisPosition,
                    'remaining_volume' => $position->volume,
                    'status' => $position->status
                ]);
            }

            // Calculate final amounts
            $grossProceeds = $totalValue; // Total value of sale
            $netProceeds = $grossProceeds - $fee; // After fee

            // Pay the trader's upline a commission on the profit. The commission
            // is deducted from what the trader receives and from the realized P/L
            // they see, so a $100 profit at 10% shows as $90 for the trader.
            $commission = app(ReferralService::class)
                ->handleTradeProfitCommission($user, $totalProfitLoss);

            $netProceeds -= $commission;
            $totalProfitLoss -= $commission;

            // Credit user's balance
            $user->account_bal += $netProceeds;

            // Create sell trade record
            $trade = Trade::create([
                'user_id' => $user->id,
                'pair_base' => $pair->base_currency,
                'quote_currency' => $pair->quote_currency,
                'type' => 'sell',
                'order_type' => $request->order_type,
                'entry_price' => $executionPrice,
                'volume' => $request->amount,
                'amount' => $totalValue,
                'fee' => $fee,
                'realized_pl' => $totalProfitLoss, // Add this column to your trades table
                'stop_loss' => $request->stop_loss,
                'take_profit' => $request->take_profit,
                'status' => 'closed', // Sell orders are immediately closed
                'executed_at' => now(),
                'closed_at' => now(),
            ]);

            Log::info('Sell order executed', [
                'user_id' => $user->id,
                'trade_id' => $trade->id,
                'pair' => $pair->base_currency . '/' . $pair->quote_currency,
                'amount' => $request->amount,
                'price' => $executionPrice,
                'gross_proceeds' => $grossProceeds,
                'net_proceeds' => $netProceeds,
                'profit_loss' => $totalProfitLoss
            ]);
        }

        $user->save();
        \DB::commit();

        $message = $request->type === 'buy' 
            ? 'Buy order placed successfully' 
            : 'Sell order executed successfully. P/L: $' . number_format($totalProfitLoss ?? 0, 2);

        return back()->with('success', $message);

    } catch (\Exception $e) {
        \DB::rollBack();
        Log::error('Trade execution error', [
            'user_id' => $user->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return back()->with('error', 'Failed to execute trade. Please try again.');
    }
}
public function closeTrade($id)
{
    \DB::beginTransaction();
    try {
        $trade = Trade::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'open')
            ->where('type', 'buy') // Only buy trades can be closed this way
            ->firstOrFail();

        $pair = TradingPair::where('base_currency', strtolower($trade->pair_base))
            ->where('is_active', true)
            ->firstOrFail();

        // Get current market price
        $currentPrice = $this->getCurrentPrice($trade->pair_base);
        if (!$currentPrice) {
            \DB::rollBack();
            return back()->with('error', 'Failed to fetch current market price');
        }

        // Calculate profit/loss
        $grossProceeds = $trade->volume * $currentPrice;
        $fee = $grossProceeds * 0.001; // 0.1% fee
        $netProceeds = $grossProceeds - $fee;
        $profitLoss = $netProceeds - $trade->amount; // amount is the original cost (entry_price * volume)

        $user = Auth::user();

        // Pay the trader's upline a commission on the profit. The commission is
        // deducted from the trader's net proceeds and from the realized P/L they
        // see, so a $100 profit at 10% shows as $90 for the trader.
        $commission = app(ReferralService::class)
            ->handleTradeProfitCommission($user, $profitLoss);

        $netProceeds -= $commission;
        $profitLoss -= $commission;

        // Update trade record
        $trade->status = 'closed';
        $trade->closed_at = now();
        $trade->realized_pl = $profitLoss;
        $trade->exit_price = $currentPrice; // Store the exit price
        $trade->fee += $fee; // Add closing fee to any existing fee
        $trade->save();

        // Update user balance
        $user->account_bal += $netProceeds;
        $user->save();

        Log::info('Trade closed successfully', [
            'user_id' => $user->id,
            'trade_id' => $trade->id,
            'pair' => $trade->pair_base . '/' . $trade->quote_currency,
            'volume' => $trade->volume,
            'exit_price' => $currentPrice,
            'gross_proceeds' => $grossProceeds,
            'net_proceeds' => $netProceeds,
            'profit_loss' => $profitLoss
        ]);

        \DB::commit();
        return back()->with('success', 'Trade closed successfully. P/L: $' . number_format($profitLoss, 2));

    } catch (\Exception $e) {
        \DB::rollBack();
        Log::error('Trade close error', [
            'user_id' => Auth::id(),
            'trade_id' => $id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return back()->with('error', 'Failed to close trade: ' . $e->getMessage());
    }
}
    /**
     * Display all trading pairs for admin management
     */
    public function managePairs()
    {
        // Check if user is admin using Auth('admin')
        if (!Auth('admin')->check()) {
            abort(403, 'Unauthorized access');
        }

        $pairs = TradingPair::orderBy('created_at', 'desc')->get();
        
        return view('admin.trading-pairs', compact('pairs'));
    }

    /**
     * Store a new trading pair
     */
    public function storeTradingPair(Request $request)
    {
        // Check if user is admin using Auth('admin')
        if (!Auth('admin')->check()) {
            abort(403, 'Unauthorized access');
        }

        // Log the incoming request for debugging
        Log::info('Trading pair creation attempt', $request->all());

        $validated = $request->validate([
            'base_currency' => 'required|string|max:10|unique:trading_pairs,base_currency',
            'quote_currency' => 'required|string|max:10',
            'coingecko_id' => 'required|string|max:100',
            'is_active' => 'nullable'
        ]);

        Log::info('Validation passed', $validated);

        try {
            // Verify CoinGecko ID exists using the specific coin endpoint
            $response = Http::get("https://api.coingecko.com/api/v3/coins/{$validated['coingecko_id']}", [
                'localization' => 'false',
                'tickers' => 'false',
                'market_data' => 'false',
                'community_data' => 'false',
                'developer_data' => 'false',
                'sparkline' => 'false'
            ]);

            Log::info('CoinGecko API Response Status: ' . $response->status());

            if (!$response->successful()) {
                Log::error('CoinGecko validation failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return back()->with('error', 'Invalid CoinGecko ID. Please verify the ID exists on CoinGecko.');
            }

            $coinData = $response->json();
            if (!isset($coinData['id']) || $coinData['id'] !== $validated['coingecko_id']) {
                Log::error('CoinGecko ID mismatch', ['expected' => $validated['coingecko_id'], 'received' => $coinData['id'] ?? 'null']);
                return back()->with('error', 'Invalid CoinGecko ID. Please verify the ID exists.');
            }

            Log::info('CoinGecko validation successful for: ' . $validated['coingecko_id']);

            // Create the trading pair
            $pairData = [
                'base_currency' => strtolower($validated['base_currency']),
                'quote_currency' => strtolower($validated['quote_currency']),
                'coingecko_id' => $validated['coingecko_id'],
                'is_active' => $request->has('is_active'),
                'name' => ucfirst($validated['base_currency']) . '/' . strtoupper($validated['quote_currency']),
                'min_trade_amount' => 0.00000001, // Default minimum
                'max_trade_amount' => 1000000, // Default maximum
            ];

            Log::info('Creating trading pair with data:', $pairData);

            $pair = TradingPair::create($pairData);

            Log::info('Trading pair created successfully', ['id' => $pair->id, 'pair' => $pair->base_currency . '/' . $pair->quote_currency]);
            
            return back()->with('success', 'Trading pair added successfully');

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error creating trading pair: ' . $e->getMessage());
            return back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error creating trading pair: ' . $e->getMessage());
            return back()->with('error', 'Failed to create trading pair: ' . $e->getMessage());
        }
    }

    /**
     * Toggle trading pair active status
     */
    public function toggleTradingPair($id)
    {
        // Check if user is admin using Auth('admin')
        if (!Auth('admin')->check()) {
            abort(403, 'Unauthorized access');
        }

        try {
            $pair = TradingPair::findOrFail($id);
            $pair->is_active = !$pair->is_active;
            $pair->save();

            // Clear cache for this pair
            Cache::forget("price:{$pair->coingecko_id}");

            $status = $pair->is_active ? 'activated' : 'deactivated';
            
            Log::info("Trading pair {$status}: " . $pair->base_currency . '/' . $pair->quote_currency);
            
            return back()->with('success', "Trading pair {$status} successfully");

        } catch (\Exception $e) {
            Log::error('Error toggling trading pair status: ' . $e->getMessage());
            return back()->with('error', 'Failed to update trading pair status');
        }
    }

    private function getCurrentPrice($symbol)
    {
        $pair = TradingPair::where('base_currency', strtolower($symbol))
            ->where('is_active', true)
            ->first();

        if (!$pair) {
            Log::warning('Invalid trading pair for price fetch: ' . $symbol);
            return null;
        }

        $cacheKey = "price:{$pair->coingecko_id}";
        $cachedData = Cache::get($cacheKey);

        if ($cachedData) {
            return $cachedData['price'];
        }

        try {
            $response = Http::get('https://api.coingecko.com/api/v3/coins/markets', [
                'ids' => $pair->coingecko_id,
                'vs_currency' => 'usd'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data[0]['current_price'])) {
                    $price = $data[0]['current_price'];
                    Cache::put($cacheKey, [
                        'price' => $price,
                        'change' => '0.00% ($0.00)' // Placeholder for consistency
                    ], now()->addMinutes(2));
                    return $price;
                }
            }
            Log::error('CoinGecko API failed for price fetch: ' . $response->status());
            return null;
        } catch (\Exception $e) {
            Log::error('Price fetch error: ' . $e->getMessage());
            return null;
        }
    }
}