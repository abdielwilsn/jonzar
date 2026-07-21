<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BalanceLog extends Model
{
    protected $fillable = ['user_id', 'old_balance', 'new_balance'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
