<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CryptoAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\SettingsCont;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;


class ExchangeController extends Controller
{
    public function assetview()
    {
        $settings = SettingsCont::where('id', '1')->first();
        if ($settings->use_crypto_feature == 'false') {
            return redirect()->back();
        }

        return view('user.asset', [
            'title' => 'Exchange currency',
            'cbalance' => CryptoAccount::where('user_id', Auth::user()->id)->first(),
        ]);
    }

    /**
     * Get market price for a given symbol and quote currency using CoinGecko API.
     * Symbol must be CoinGecko's coin id (e.g., 'bitcoin', 'ethereum').
     * Returns price as float or null on failure.
     */


     private function getMarketPrice(string $symbol, string $vs = 'usd'): ?float
     {
         $cacheKey = "price_{$symbol}_{$vs}";
         $cachedPrice = Cache::get($cacheKey);
         if ($cachedPrice !== null) {
             return $cachedPrice;
         }
     
         try {
             $response = Http::timeout(5)->get('https://api.coingecko.com/api/v3/simple/price', [
                 'ids' => $symbol,
                 'vs_currencies' => $vs,
             ]);
     
             if ($response->successful()) {
                 $data = $response->json();
     
                 if (empty($data)) {
                     Log::warning("Empty data returned from CoinGecko for {$symbol}/{$vs}");
                     return null;
                 }
     
                 if (isset($data[$symbol][$vs])) {
                     $price = (float) $data[$symbol][$vs];
                     Cache::put($cacheKey, $price, now()->addSeconds(60));
                     return $price;
                 } else {
                     Log::warning("Price not found in response for {$symbol}/{$vs}: " . json_encode($data));
                 }
             } else {
                 Log::error("CoinGecko API error: " . $response->status() . " - " . $response->body());
             }
         } catch (\Exception $e) {
             Log::error("Exception in getMarketPrice for {$symbol}/{$vs}: " . $e->getMessage());
         }
     
         return null;
     }
     
     

 

    /**
     * Calculate the converted amount between base and quote currencies.
     * Supports 'usd' and cryptos (expects CoinGecko ids).
     * Applies a fee percentage from settings.
     */
    private function calculatePrice(string $base, string $quote, float $amount): float
    {
        $settings = SettingsCont::where('id', '1')->first();
        $feePercent = $settings->fee ?? 0;
        $amountAfterFee = $amount - ($amount * $feePercent / 100);

        // Special handling for stablecoins or fiat equivalents you want to map:
        $stablecoins = ['usd', 'usdt'];

        try {
            // If both are stablecoins, 1:1 conversion
            if (in_array($base, $stablecoins) && in_array($quote, $stablecoins)) {
                return round($amountAfterFee, 8);
            }

            $coinMapping = [
                'btc' => 'bitcoin',
                'eth' => 'ethereum',
                'ltc' => 'litecoin',
                'usdt' => 'tether',
                'usd' => 'usd',
                'bnb' => 'binancecoin',
                'sol' => 'solana',
                'ada' => 'cardano',
                'xrp' => 'ripple',
                'doge' => 'dogecoin',
                // Add more based on your supported coins
            ];
            
            $baseId = $coinMapping[strtolower($base)] ?? null;
            $quoteId = $coinMapping[strtolower($quote)] ?? null;
            
            if (!$baseId || !$quoteId) {
                Log::error("Invalid base or quote symbol: base={$base}, quote={$quote}");
                return 0;
            }
            
            // Case 1: quote is USD or stablecoin
            if (in_array($quote, $stablecoins)) {
                $price = $this->getMarketPrice($baseId, 'usd');
                if ($price === null) {
                    Log::error("Price not found for base: {$baseId} in USD");
                    return 0;
                }
                return round($amountAfterFee * $price, 8);
            }

            // Case 2: base is USD or stablecoin
            if (in_array($base, $stablecoins)) {
                $price = $this->getMarketPrice($quoteId, 'usd');
                if ($price === null) {
                    Log::error("Price not found for quote: {$quoteId} in USD");
                    return 0;
                }
                return round($amountAfterFee / $price, 8);
            }

            // Case 3: crypto to crypto (neither is USD)
            $priceBase = $this->getMarketPrice($baseId, 'usd');
            $priceQuote = $this->getMarketPrice($quoteId, 'usd');

            if ($priceBase === null || $priceQuote === null) {
                Log::error("Price not found for crypto to crypto: base={$baseId}, quote={$quoteId}");
                return 0;
            }

            $rate = $priceBase / $priceQuote;
            return round($amountAfterFee * $rate, 8);

        } catch (\Exception $e) {
            Log::error("Exception in calculatePrice: " . $e->getMessage());
            return 0;
        }
    }

    public function getprice($base, $quote, $amount)
    {
        $price = $this->calculatePrice($base, $quote, $amount);
        return response()->json(['status' => 200, 'data' => $price]);
    }
    

    public function exchange(Request $request)
    {
        $cryptobalances = CryptoAccount::where('user_id', Auth::user()->id)->first();
        $acntbal = Auth::user()->account_bal;
        $src = $request->source;
        $cdest = $request->destination;
        $user = Auth::user();

        if (!$request->amount || $request->amount <= 0) {
            return response()->json(['status' => 201, 'message' => 'Invalid amount provided']);
        }

        // Calculate quantity if not provided or null
        if (!$request->quantity) {
            $calculatedQuantity = $this->calculatePrice($src, $cdest, $request->amount);

            if ($calculatedQuantity <= 0) {
                return response()->json(['status' => 201, 'message' => 'Error calculating exchange rate']);
            }
        } else {
            $calculatedQuantity = $request->quantity;
        }

        // Get source balance
        $source_balance = ($src == 'usd') ? $acntbal : $cryptobalances->{$src};

        // Check if user has sufficient balance in source account  
        if ($source_balance < $request->amount) {
            return response()->json(['status' => 201, 'message' => 'Insufficient funds in your source account']);
        }

        // Case 1: Converting FROM USD to crypto
        if ($src == 'usd') {
            User::where('id', $user->id)->update([
                'account_bal' => $acntbal - $request->amount,
            ]);

            DB::table('crypto_accounts')
                ->where('user_id', $user->id)
                ->update([
                    $cdest => $cryptobalances->{$cdest} + $calculatedQuantity,
                ]);

            return response()->json(['status' => 200, 'success' => 'Exchange Successful, Refreshing your Balances']);
        }

        // Case 2: Converting FROM crypto TO USD
        if ($cdest == 'usd') {
            DB::table('crypto_accounts')
                ->where('user_id', $user->id)
                ->update([
                    $src => $cryptobalances->{$src} - $request->amount,
                ]);

            User::where('id', $user->id)->update([
                'account_bal' => $acntbal + $calculatedQuantity,
            ]);

            return response()->json(['status' => 200, 'success' => 'Exchange Successful, Refreshing your Balances']);
        }

        // Case 3: Converting FROM crypto TO crypto (non-USD)
        if ($src != 'usd' && $cdest != 'usd') {
            DB::table('crypto_accounts')
                ->where('user_id', $user->id)
                ->update([
                    $src => $cryptobalances->{$src} - $request->amount,
                    $cdest => $cryptobalances->{$cdest} + $calculatedQuantity,
                ]);

            return response()->json(['status' => 200, 'success' => 'Exchange Successful, Refreshing your Balances']);
        }

        // Default fallback response
        return response()->json(['status' => 400, 'message' => 'Invalid exchange parameters']);
    }
}
