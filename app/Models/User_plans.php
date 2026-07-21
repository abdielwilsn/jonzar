<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_plans extends Model
{
    use HasFactory;

    protected $dates = ['created_at', 'updated_at', 'activated_at', 'last_growth'];

    protected $fillable = ['active'];
 
    public function dplan(){
        return $this->belongsTo(Plans::class, 'plan', 'id');
    }

    public function duser(){
    	return $this->belongsTo(User::class, 'user', 'id');
    }

     /**
     * Check if the plan is expired
     */
    public function isExpired()
    {
        if ($this->active !== 'active') {
            return true;
        }

        if (!$this->activated_at || !$this->inv_duration) {
            return false;
        }

        $activatedAt = Carbon::parse($this->activated_at);
        $duration = $this->parseDuration($this->inv_duration);
        
        if ($duration) {
            $expirationDate = $activatedAt->add($duration['interval'], $duration['value']);
            return Carbon::now()->greaterThan($expirationDate);
        }

        return false;
    }

    /**
     * Get the expiration date of the plan
     */
    public function getExpirationDate()
    {
        if (!$this->activated_at || !$this->inv_duration) {
            return null;
        }

        $activatedAt = Carbon::parse($this->activated_at);
        $duration = $this->parseDuration($this->inv_duration);
        
        if ($duration) {
            return $activatedAt->add($duration['interval'], $duration['value']);
        }

        return null;
    }

    /**
     * Parse duration string (same logic as in the job)
     */
    private function parseDuration($duration)
    {
        $duration = strtolower(trim($duration));
        
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

        // Numeric patterns
        $patterns = [
            '/^(\d+)\s*hours?$/' => ['interval' => 'hour', 'multiplier' => 1],
            '/^(\d+)\s*days?$/' => ['interval' => 'day', 'multiplier' => 1],
            '/^(\d+)\s*weeks?$/' => ['interval' => 'week', 'multiplier' => 1],
            '/^(\d+)\s*months?$/' => ['interval' => 'month', 'multiplier' => 1],
            '/^(\d+)\s*years?$/' => ['interval' => 'year', 'multiplier' => 1],
        ];

        foreach ($patterns as $pattern => $config) {
            if (preg_match($pattern, $duration, $matches)) {
                return [
                    'interval' => $config['interval'],
                    'value' => (int)$matches[1] * $config['multiplier']
                ];
            }
        }

        return null;
    }

}
