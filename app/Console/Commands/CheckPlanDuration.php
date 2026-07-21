<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User_plans;
use Carbon\Carbon;

class CheckPlanDuration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plans:check-duration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate user plans if the duration has passed';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get current date and time
        $now = Carbon::now();

        // Fetch all active plans where the expiration date has passed
        $expiredPlans = User_plans::where('active', 'yes')
            ->where('expire_date', '<', $now)
            ->get();

        // Process each expired plan
        foreach ($expiredPlans as $plan) {
            $plan->update(['active' => 'no']);

            // Log the deactivation
            $this->info("Deactivated plan ID: {$plan->id} for user ID: {$plan->user}");
        }

        // Summary message
        if ($expiredPlans->isEmpty()) {
            $this->info('No plans found to deactivate.');
        } else {
            $this->info('Expired plans have been deactivated successfully.');
        }

        return Command::SUCCESS;
    }
}
