<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User_plans;
use Illuminate\Support\Facades\Auth;

class CheckUserPlan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user has an active plan
        $activePlan = User_plans::where('user', $user->id)
            ->where('active', 'yes')
            ->first();

        if (!$activePlan) {
            // Return an error response if the user does not have an active plan
            return response()->json([
                'error' => 'Access denied. Your plan is not active.',
            ], 403);
        }

        // Allow the request to proceed if the plan is active
        return $next($request);
    }
}
