<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tp_Transaction extends Model
{
    use HasFactory;
    protected $fillable=['user','from_user','plan','amount','type'];

    /**
     * The downline user whose deposit/trade generated this bonus (nullable).
     */
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user');
    }
}
