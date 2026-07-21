<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    protected $fillable = [
        'user_id',
        'trading_pair_id',
        'amount',
        'status',
        'start_date',
        'profit',
        'end_date'
    ];

    protected $casts = [
        'amount' => 'float',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tradingPair()
    {
        return $this->belongsTo(TradingPair::class);
    }
}