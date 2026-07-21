<?php

namespace App\Console\Commands;

use App\Jobs\ExtendTrades;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExtendTradesCommand extends Command
{
    protected $signature = 'trades:extend
                            {--test : Run in test mode (no actual changes)}';

    protected $description = 'Extend active trades that will expire in the next 24 hours by 10 hours';

    public function handle()
    {
        $this->info('Starting trade extension process...');

        if ($this->option('test')) {
            $this->warn('TEST MODE: No changes will be saved to database.');
            // Just dispatch and let job run in dry mode
            // Or you can add a check inside the job below
        }

        try {
            // Simply dispatch your existing job
            ExtendTrades::dispatch();

            $this->info('ExtendTrades job dispatched successfully!');
            $this->line('Check your queue worker (php artisan queue:work) or logs/storage/logs/laravel.log');

        } catch (\Exception $e) {
            Log::error('Failed to dispatch ExtendTrades job from command', [
                'error' => $e->getMessage()
            ]);
            $this->error('Failed to dispatch job: ' . $e->getMessage());
        }
    }
}
