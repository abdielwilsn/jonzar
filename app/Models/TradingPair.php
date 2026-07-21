<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TradingPair extends Model
{
    use HasFactory;

    protected $fillable = [
        'coingecko_id',
        'base_symbol',
        'base_name',
        'quote_symbol',
        'base_icon_url',
        'current_price',
        'price_change_24h',
        'market_cap',
        'volume_24h',
        'price_last_updated',
        'min_investment',
        'max_investment',
        'min_return_percentage',
        'max_return_percentage',
        'investment_duration',
        'max_investment_duration',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'current_price' => 'decimal:8',
        'price_change_24h' => 'decimal:4',
        'market_cap' => 'decimal:2',
        'min_investment' => 'decimal:2',
        'max_investment' => 'decimal:2',
        'min_return_percentage' => 'decimal:4',
        'max_return_percentage' => 'decimal:4',
        'is_active' => 'boolean',
        'price_last_updated' => 'datetime'
    ];

    /**
     * Scope for active trading pairs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered trading pairs
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('base_symbol');
    }

    /**
     * Fetch coin data from CoinGecko API and populate the model
     */
    public function fetchCoinGeckoData()
    {
        try {
            $response = Http::timeout(10)->get("https://api.coingecko.com/api/v3/coins/{$this->coingecko_id}", [
                'localization' => false,
                'tickers' => false,
                'market_data' => true,
                'community_data' => false,
                'developer_data' => false,
                'sparkline' => false
            ]);

            if ($response->successful()) {
                $data = $response->json();

                $this->update([
                    'base_symbol' => strtoupper($data['symbol'] ?? $this->base_symbol),
                    'base_name' => $data['name'] ?? $this->base_name,
                    'base_icon_url' => $data['image']['large'] ?? $this->base_icon_url,
                    'current_price' => $data['market_data']['current_price']['usd'] ?? 0,
                    'price_change_24h' => $data['market_data']['price_change_percentage_24h'] ?? 0,
                    'market_cap' => $data['market_data']['market_cap']['usd'] ?? null,
                    'volume_24h' => $data['market_data']['total_volume']['usd'] ?? null,
                    'price_last_updated' => now()
                ]);

                return true;
            }
        } catch (\Exception $e) {
            Log::error("Failed to fetch CoinGecko data for {$this->coingecko_id}: " . $e->getMessage());
        }

        return false;
    }

    /**
     * Update price data for all active trading pairs
     */
    public static function updateAllPrices()
    {
        $pairs = self::active()->get();
        $coingeckoIds = $pairs->pluck('coingecko_id')->implode(',');

        if (empty($coingeckoIds)) {
            return;
        }

        try {
            $response = Http::timeout(15)->get('https://api.coingecko.com/api/v3/simple/price', [
                'ids' => $coingeckoIds,
                'vs_currencies' => 'usd',
                'include_24hr_change' => 'true',
                'include_market_cap' => 'true',
                'include_24hr_vol' => 'true'
            ]);

            if ($response->successful()) {
                $priceData = $response->json();

                foreach ($pairs as $pair) {
                    if (isset($priceData[$pair->coingecko_id])) {
                        $data = $priceData[$pair->coingecko_id];

                        $pair->update([
                            'current_price' => $data['usd'] ?? $pair->current_price,
                            'price_change_24h' => $data['usd_24h_change'] ?? $pair->price_change_24h,
                            'market_cap' => $data['usd_market_cap'] ?? $pair->market_cap,
                            'volume_24h' => $data['usd_24h_vol'] ?? $pair->volume_24h,
                            'price_last_updated' => now()
                        ]);
                    }
                }

                Log::info("Updated prices for " . $pairs->count() . " trading pairs");
                return true;
            }
        } catch (\Exception $e) {
            Log::error("Failed to update trading pair prices: " . $e->getMessage());
        }

        return false;
    }

    /**
     * Check if price data is stale (older than 5 minutes)
     */
    public function isPriceStale()
    {
        return !$this->price_last_updated ||
               $this->price_last_updated->diffInMinutes(now()) > 5;
    }

    /**
     * Get formatted price string
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->current_price, $this->current_price < 1 ? 6 : 2);
    }

    /**
     * Get price change color class
     */
    public function getPriceChangeColorAttribute()
    {
        return $this->price_change_24h >= 0 ? 'text-success' : 'text-danger';
    }

    /**
     * Get trading pair display name
     */
    public function getPairNameAttribute()
    {
        return "{$this->base_symbol}/{$this->quote_symbol}";
    }

    /**
     * Calculate potential return range for a given investment
     */
    public function calculateReturns($investmentAmount)
    {
        $minReturn = $investmentAmount * ($this->min_return_percentage / 100);
        $maxReturn = $investmentAmount * ($this->max_return_percentage / 100);

        return [
            'min_return' => $minReturn,
            'max_return' => $maxReturn,
            'min_total' => $investmentAmount + $minReturn,
            'max_total' => $investmentAmount + $maxReturn
        ];
    }

    /**
     * Validate investment amount
     */
    public function isValidInvestmentAmount($amount)
    {
        return $amount >= $this->min_investment && $amount <= $this->max_investment;
    }

    /**
     * Get investment validation errors
     */
    public function getInvestmentValidationErrors($amount)
    {
        $errors = [];

        if ($amount < $this->min_investment) {
            $errors[] = "Minimum investment amount is " . number_format($this->min_investment, 2);
        }

        if ($amount > $this->max_investment) {
            $errors[] = "Maximum investment amount is " . number_format($this->max_investment, 2);
        }

        if (!$this->is_active) {
            $errors[] = "This trading pair is currently inactive";
        }

        return $errors;
    }

    /**
     * Get relationship with user investments (if you have an investments table)
     */
    public function investments()
    {
        return $this->hasMany(Investment::class, 'trading_pair_id');
    }

    /**
     * Get active investments count
     */
    public function getActiveInvestmentsCountAttribute()
    {
        return $this->investments()->where('status', 'active')->count();
    }

    /**
     * Get total invested amount
     */
    public function getTotalInvestedAttribute()
    {
        return $this->investments()->where('status', 'active')->sum('amount');
    }
}
