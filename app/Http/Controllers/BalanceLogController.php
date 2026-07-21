<?php

namespace App\Http\Controllers;

use App\Models\BalanceLog;
use App\Models\User;
use Illuminate\Http\Request;

class BalanceLogController extends Controller
{
    public function index(Request $request)
    {
        // Build the query for balance logs
        $query = BalanceLog::query()->with('user'); // Eager load the user relationship

        // Filter by user name
        if ($request->filled('name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('name') . '%');
            });
        }

        // Filter by user email
        if ($request->filled('email')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('email', 'like', '%' . $request->input('email') . '%');
            });
        }

        // Filter by date
        // if ($request->filled('date')) {
        //     $query->whereDate('changed_at', $request->input('date'));
        // }

        // Get paginated results
        $logs = $query->orderBy('updated_at', 'desc')->paginate(10);

        // Pass data to the view
        return view('balance_logs.index', compact('logs'));
    }
}
