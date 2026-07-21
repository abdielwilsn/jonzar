<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserReferralSetting extends Model
{
    protected $fillable = [
        'user_id',
        'referral_commission',
        'referral_commission1',
        'referral_commission2',
        'referral_commission3',
        'referral_commission4',
        'referral_commission5',
        'signup_bonus',
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
