<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SendUsersToUpdateKYC;

class SendKycUpdateEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kyc:send-update-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatches job to send KYC update emails to users with missing ID numbers';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->info('Dispatching KYC update email job...');

        // Dispatch the job
        SendUsersToUpdateKYC::dispatch();

        $this->info('KYC update email job dispatched successfully.');
    }
}
