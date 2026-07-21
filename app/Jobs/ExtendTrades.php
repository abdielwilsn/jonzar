<?php

namespace App\Jobs;

use App\Models\Investment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ExtendTrades implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $newExpirationDate = now()->addHours(72);

        $activeTrades = Investment::where('status', 'active')->get();

        if ($activeTrades->isEmpty()) {
            Log::info('ExtendTrades: No active trades to extend.');
            return;
        }

        $updatedCount = 0;

        foreach ($activeTrades as $trade) {
            $trade->end_date = $newExpirationDate;
            $trade->save();

            $updatedCount++;

            Log::info('Trade expiration set to 72 hours from now', [
                'trade_id' => $trade->id,
                'user_id'  => $trade->user_id,
                'new_end_date' => $newExpirationDate->toDateTimeString(),
            ]);
        }

        Log::info("ExtendTrades completed: {$updatedCount} active trade(s) expiration set to 72 hours from now.");
    }
}
