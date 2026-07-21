<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Mail\KycUpdateRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendUsersToUpdateKYC implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = User::whereNull('id_number')->get();



        foreach ($users as $user) {

            Log::info("Sending KYC update email to user: {$user->email}");
            // Send email to user to update KYC
            Mail::to($user->email)->send(new KycUpdateRequest($user));

            $user->account_verify = Null;
            $user->ref_bonus = 0.00;

            $user->save();


        }
    }
}
