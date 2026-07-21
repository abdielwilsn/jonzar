<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReferralsController extends Controller
{
    public function getReferrals(User $user)
    {
        $referred_users = User::where('ref_by', $user->id)->paginate(10);

        return view('admin.Users.referrals',[
            'referred_users' => $referred_users,
            'user' => $user
        ]);
    }
}
