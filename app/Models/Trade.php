<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trade extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pair_base',
        'quote_currency',
        'type',
        'order_type',
        'entry_price',
        'amount',
        'volume',
        'fee',
        'stop_loss',
        'take_profit',
        'realized_pl',
        'status',
        'exit_price',
        'profit_loss',
        'executed_at',
        'closed_at',
    ];

    protected $casts = [
        'entry_price' => 'decimal:8',
        'amount' => 'decimal:8',
        'volume' => 'decimal:8',
        'fee' => 'decimal:8',
        'stop_loss' => 'decimal:8',
        'take_profit' => 'decimal:8',
        'realized_pl' => 'decimal:8',
        'exit_price' => 'decimal:8',
        'profit_loss' => 'decimal:8',
        'executed_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the trade
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the trading pair for this trade
     */
    public function tradingPair(): BelongsTo
    {
        return $this->belongsTo(TradingPair::class, 'pair_base', 'base_currency');
    }

    /**
     * Get the full pair name (e.g., BTC/USDT)
     */
    public function getFullPairAttribute(): string
    {
        return strtoupper($this->pair_base) . '/' . strtoupper($this->quote_currency);
    }

    /**
     * Calculate unrealized P/L for open positions
     */
    public function calculateUnrealizedPL(float $currentPrice): float
    {
        if ($this->status !== 'open' || $this->type !== 'buy') {
            return 0;
        }

        return ($currentPrice - $this->entry_price) * $this->volume;
    }

    /**
     * Check if this trade should be automatically closed based on stop loss/take profit
     */
    public function shouldAutoClose(float $currentPrice): ?string
    {
        if ($this->status !== 'open' || $this->type !== 'buy') {
            return null;
        }

        // Check stop loss
        if ($this->stop_loss && $currentPrice <= $this->stop_loss) {
            return 'stop_loss';
        }

        // Check take profit
        if ($this->take_profit && $currentPrice >= $this->take_profit) {
            return 'take_profit';
        }

        return null;
    }

    /**
     * Scope for open trades
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope for closed trades
     */
    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    /**
     * Scope for pending trades
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for buy trades
     */
    public function scopeBuys($query)
    {
        return $query->where('type', 'buy');
    }

    /**
     * Scope for sell trades
     */
    public function scopeSells($query)
    {
        return $query->where('type', 'sell');
    }

    /**
     * Scope for a specific trading pair
     */
    public function scopeForPair($query, string $baseCurrency)
    {
        return $query->where('pair_base', strtolower($baseCurrency));
    }

    /**
     * Get the total cost including fees
     */
    public function getTotalCostAttribute(): float
    {
        return $this->amount + $this->fee;
    }

    /**
     * Get the net proceeds for sell orders (amount - fees)
     */
    public function getNetProceedsAttribute(): float
    {
        return $this->amount - $this->fee;
    }
}