<?php

namespace App\Console\Commands;

use App\Jobs\ReverseWithdrawals;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ReversePendingWithdrawals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'withdrawals:reverse
                            {--dry-run : Show what would be reversed without actually doing it}
                            {--queue= : Specify the queue name to process}
                            {--limit=100 : Limit number of withdrawals to process}
                            {--user-id= : Reverse withdrawals for specific user only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reverse all pending withdrawals and restore user balances';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Starting Reverse Pending Withdrawals Command');
        $this->newLine();

        // Check if dry run
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('⚠️  DRY RUN MODE: No changes will be made to database');
            $this->newLine();
        }

        // Get pending withdrawals count
        $query = \App\Models\Withdrawal::where('status', 'pending');

        // Apply user filter if specified
        if ($userId = $this->option('user-id')) {
            $query->where('user_id', $userId);
            $this->info("Filtering by user ID: {$userId}");
        }

        $pendingCount = $query->count();

        if ($pendingCount === 0) {
            $this->info('✅ No pending withdrawals found.');
            return self::SUCCESS;
        }

        $this->info("📊 Found {$pendingCount} pending withdrawal(s) to reverse");
        $this->newLine();

        // Show preview table
        $this->showPreview($query->limit($this->option('limit'))->get());

        if ($dryRun) {
            $this->warn('✅ Dry run completed. No changes were made.');
            return self::SUCCESS;
        }

        // Confirm execution
        if (!$this->confirm('🚀 Do you want to proceed with reversing these withdrawals?', false)) {
            $this->warn('❌ Operation cancelled by user.');
            return self::SUCCESS;
        }

        $this->newLine();
        $this->info('🚀 Dispatching ReverseWithdrawals job...');

        // Dispatch the job
        $queueName = $this->option('queue') ?: 'default';
        $limit = (int) $this->option('limit');

        ReverseWithdrawals::dispatch()
            ->onQueue($queueName)
            ->chain([
                function () use ($pendingCount, $limit) {
                    \Log::info('ReverseWithdrawals job dispatched successfully', [
                        'pending_count' => $pendingCount,
                        'limit' => $limit
                    ]);
                }
            ]);

        $this->info("✅ ReverseWithdrawals job dispatched to queue: {$queueName}");
        $this->info("📋 Limit applied: {$limit} withdrawals");

        if ($userId) {
            $this->info("👤 Filtered by user ID: {$userId}");
        }

        $this->newLine();
        $this->info('📋 Check queue worker logs or use the following commands:');
        $this->line('   php artisan queue:work');
        $this->line('   php artisan queue:monitor');
        $this->newLine();

        return self::SUCCESS;
    }

    /**
     * Show preview of withdrawals to be reversed
     */
    protected function showPreview($withdrawals)
    {
        $this->table(
            ['ID', 'User ID', 'Username', 'Amount', 'To Deduct', 'Payment Mode', 'Created'],
            $withdrawals->map(function ($withdrawal) {
                return [
                    $withdrawal->id,
                    $withdrawal->user_id,
                    $withdrawal->uname ?? 'N/A',
                    number_format($withdrawal->amount ?? 0, 2),
                    $withdrawal->payment_mode ?? 'N/A',
                    $withdrawal->created_at?->format('Y-m-d H:i:s') ?? 'N/A',
                ];
            })->toArray()
        );

        $totalAmount = $withdrawals->sum('amount');
        $this->newLine();
        $this->info("💰 Total amount to be restored: " . number_format($totalAmount, 2));
    }
}
