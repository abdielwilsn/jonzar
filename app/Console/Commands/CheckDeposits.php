<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CheckDepositsService;

class CheckDeposits extends Command
{
    protected $signature = 'deposits:check';
    protected $description = 'Check blockchain for new BEP20 deposits';

    public function handle(CheckDepositsService $service)
    {
        $service->checkDeposits();
        $this->info('Deposits checked and updated successfully.');
    }
}
