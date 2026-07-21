<?php

namespace App\Jobs;

use App\Models\User_plans;
use App\Models\Plans;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ExpireUserPlans implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle()
    {
        try {
            $activePlans = User_plans::where('active', 'yes')
                ->whereNotNull('activated_at')
                ->whereNotNull('inv_duration')
                ->get();

            $expiredCount = 0;

            foreach ($activePlans as $plan) {
                $activatedAt = Carbon::parse($plan->activated_at);
                $duration = $this->parseDuration($plan->inv_duration);

                if ($duration) {
                    $expirationDate = $activatedAt->copy()->add($duration['interval'], $duration['value']);

                    // dd("yes");

                    if (Carbon::now()->greaterThan($expirationDate)) {
                        $plan->update([
                            'active' => 'expired',
                            'expire_date' => $expirationDate
                        ]);

                        $expiredCount++;


                        Log::info("User plan {$plan->id} expired for user {$plan->user}");

                        $planDetails = Plans::find($plan->plan);
                        if (!$planDetails) {
                            Log::warning("Plan ID {$plan->plan} not found for user plan {$plan->plan}");
                            continue;
                        }

                        $expectedReturn = (float) $planDetails->expected_return;

                        $user = User::find($plan->user);
                        if (!$user) {
                            Log::warning("User ID {$plan->user} not found for user plan {$plan->plan}");
                            continue;
                        }

           

                        $bal = $user->account_bal;

                        $returns = rand($planDetails->minr, $planDetails->maxr);

                        // $bal += $returns;


                        $user->account_bal += $returns;


                        $user->save();

                        $user->account_bal += $plan->amount;

                        $user->save();



                    }
                }
            }

            Log::info("Expired {$expiredCount} user plans.");
        } catch (\Exception $e) {
            Log::error('Error in ExpireUserPlans Job: ' . $e->getMessage());
        }
    }

    private function parseDuration($duration)
    {
        $duration = strtolower(trim($duration));

        $patterns = [
            '/^(\d+)\s*hours?$/' => ['interval' => 'hour'],
            '/^(\d+)\s*hrs?$/' => ['interval' => 'hour'],
            '/^(\d+)\s*h$/' => ['interval' => 'hour'],
            '/^(\d+)\s*days?$/' => ['interval' => 'day'],
            '/^(\d+)\s*d$/' => ['interval' => 'day'],
            '/^(\d+)\s*weeks?$/' => ['interval' => 'week'],
            '/^(\d+)\s*w$/' => ['interval' => 'week'],
            '/^(\d+)\s*months?$/' => ['interval' => 'month'],
            '/^(\d+)\s*m$/' => ['interval' => 'month'],
            '/^(\d+)\s*years?$/' => ['interval' => 'year'],
            '/^(\d+)\s*y$/' => ['interval' => 'year'],
        ];

        $wordPatterns = [
            'one hour' => ['interval' => 'hour', 'value' => 1],
            'two hours' => ['interval' => 'hour', 'value' => 2],
            'three hours' => ['interval' => 'hour', 'value' => 3],
            'four hours' => ['interval' => 'hour', 'value' => 4],
            'five hours' => ['interval' => 'hour', 'value' => 5],
            'six hours' => ['interval' => 'hour', 'value' => 6],
            'twelve hours' => ['interval' => 'hour', 'value' => 12],
            'one day' => ['interval' => 'day', 'value' => 1],
            'two days' => ['interval' => 'day', 'value' => 2],
            'three days' => ['interval' => 'day', 'value' => 3],
            'four days' => ['interval' => 'day', 'value' => 4],
            'five days' => ['interval' => 'day', 'value' => 5],
            'one week' => ['interval' => 'week', 'value' => 1],
            'two weeks' => ['interval' => 'week', 'value' => 2],
            'one month' => ['interval' => 'month', 'value' => 1],
            'two months' => ['interval' => 'month', 'value' => 2],
            'three months' => ['interval' => 'month', 'value' => 3],
            'six months' => ['interval' => 'month', 'value' => 6],
            'one year' => ['interval' => 'year', 'value' => 1],
            'two years' => ['interval' => 'year', 'value' => 2],
        ];

        if (isset($wordPatterns[$duration])) {
            return $wordPatterns[$duration];
        }

        foreach ($patterns as $pattern => $config) {
            if (preg_match($pattern, $duration, $matches)) {
                return [
                    'interval' => $config['interval'],
                    'value' => (int) $matches[1]
                ];
            }
        }

        Log::warning("Unrecognized duration format: {$duration}");
        return null;
    }
}
