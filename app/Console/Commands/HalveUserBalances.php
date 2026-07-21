<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class HalveUserBalances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * php artisan balances:halve
     */
    protected $signature = 'balances:halve';

    /**
     * The console command description.
     */
    protected $description = 'Reduce all users\' account_bal by 50%';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::table('users')->update([
            'account_bal' => DB::raw('account_bal * 0.5'),
        ]);

        $this->info('All user balances have been reduced by 50%.');

        return Command::SUCCESS;
    }
}
