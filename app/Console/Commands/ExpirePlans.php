<?php

namespace App\Console\Commands;

use App\Jobs\ExpireUserPlans;
use Illuminate\Console\Command;

class ExpirePlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plans:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire user plans that have exceeded their duration';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting to expire user plans...');
        
        // Dispatch the job
        ExpireUserPlans::dispatch();
        
        $this->info('ExpireUserPlans job has been dispatched successfully!');
        
        return 0;
    }
}