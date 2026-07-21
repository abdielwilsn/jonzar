<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use App\Models\Settings;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Log;
use App\Models\BalanceLog;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;


    //    protected static function boot()
    //    {
    //        parent::boot();
    //
    //        static::updated(function ($user) {
    //            if ($user->isDirty('account_bal')) {
    //                $oldBalance = $user->getOriginal('account_bal');
    //                $newBalance = $user->account_bal;
    //
    //                Log::info('User account balance changed', [
    //                    'user_id' => $user->id,
    //                    'old_balance' => $oldBalance,
    //                    'new_balance' => $newBalance,
    //                    // 'changed_at' => now(),
    //                ]);
    //
    //                BalanceLog::create([
    //                    'user_id' => $user->id,
    //                    'old_balance' => $oldBalance,
    //                    'new_balance' => $newBalance,
    //                    // 'changed_at' => now(),
    //                ]);
    //            }
    //        });
    //    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */

    public function sendEmailVerificationNotification()
    {
        $settings = Settings::where('id', 1)->first();

        if ($settings->enable_verification == 'true') {
            $this->notify(new VerifyEmail());
        }

    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'l_name', 'email', 'phone','country','password', 'ref_by', 'roi', 'status', 'username', 'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];


    public function dp()
    {
        return $this->hasMany(Deposit::class, 'user');
    }

    public function wd()
    {
        return $this->hasMany(Withdrawal::class, 'user');
    }
    public function tuser()
    {
        return $this->belongsTo(Admin::class, 'assign_to');
    }
    public function dplan()
    {
        return $this->belongsTo(Plans::class, 'plan');
    }

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }

    // In User.php model

    /**
     * Get users referred by this user
     */
    public function refs()
    {
        return $this->hasMany(User::class, 'ref_by', 'id');
    }

    /**
     * Get the user who referred this user
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'ref_by', 'id');
    }

}
