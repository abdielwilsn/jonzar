<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Settings;
use App\Models\BalanceLog;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        //
        $settings = Settings::where('id', 1)->first();

        if ($settings->enable_verification == 'false') {
            $user->email_verified_at = \Carbon\Carbon::now();
            $user->save();
        }

    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */


    public function updated(User $user)
    {
        // Check if account_bal has changed
        if ($user->isDirty('account_bal')) {
            $oldBalance = $user->getOriginal('account_bal');
            $newBalance = $user->account_bal;

            // Log the change
            Log::info('User account balance changed', [
                'user_id' => $user->id,
                'old_balance' => $oldBalance,
                'new_balance' => $newBalance,
            ]);

//            dd($oldBalance, $newBalance);

            BalanceLog::create([
                'user_id' => $user->id,
                'old_balance' => $oldBalance,
                'new_balance' => $newBalance,
            ]);
        }
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
