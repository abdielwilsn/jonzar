<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Settings;
use App\Models\Plans;
use App\Models\Agent;
use App\Models\User_plans;
use App\Models\Admin;
use App\Models\Faq;
use App\Models\Images;
use App\Models\Testimony;
use App\Models\Content;
use App\Models\Asset;
use App\Models\Mt4Details;
use App\Models\Deposit;
use App\Models\Notification;
use App\Models\Wdmethod;
use App\Models\Withdrawal;
use App\Models\Cp_transaction;
use App\Models\Tp_Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Mail\NewNotification;
use App\Mail\UserUpload;
use App\Mail\KycUpload;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\CPTrait;

class ViewsController extends Controller
{
    public function dashboard(Request $request)
    {

        $settings = Settings::where('id', '1')->first();

        //Check if the user is referred by someone after a successful registration
        $settings = Settings::where('id', '1')->first();

        //check for users without ref link and update them with it
        $usf = User::all();
        foreach ($usf as $usf) {
            //if the ref_link column is empty
            if ($usf->ref_link == '') {
                User::where('id', $usf->id)
                ->update([
                    'ref_link' => $settings->site_address.'/ref/'.$usf->username,
                    'ref_bonus' => '0',
                    'bonus_released' => '0',
                ]);
            }
            //give reg bonus if new
            if ($usf->created_at->diffInDays() < 2 && $usf->signup_bonus != "received") {
                User::where('id', $usf->id)
                ->update([
                    'bonus' => $usf->bonus + $settings->signup_bonus,
                    'account_bal' => $usf->account_bal + $settings->signup_bonus,
                    'signup_bonus' => "received",
                ]);

                if ($settings->signup_bonus > 0 and !empty($settings->signup_bonus)) {
                    //create history
                    Tp_Transaction::create([
                        'user' => Auth::user()->id,
                        'plan' => "SignUp Bonus",
                        'amount' => $settings->signup_bonus,
                        'type' => "Bonus",
                    ]);
                }

            }
        }

        //get referral earnings

        $dref = Agent::where('agent', Auth::user()->id)->first();
        if ($dref) {
            $ref_earnings = "0.00";
        } else {
            $ref_earnings = "$dref->earnings";
        }

        //sum total deposited
        $total_deposited = DB::table('deposits')->select(DB::raw("SUM(amount) as count"))->where('user', Auth::user()->id)->
        where('status', 'Processed')->get();
        $withdrawals = DB::table('withdrawals')
            ->select(DB::raw("SUM(amount) as count"))
            ->where('user_id', Auth::user()->id)
            ->where('status', 'processed')
            ->get();


        if ($settings->payment_mode == 'Bank transfer') {
            $condition = empty(Auth::user()->account_no) or empty(Auth::user()->account_name) or empty(Auth::user()->bank_name) or empty(Auth::user()->phone);
        } elseif ($settings->payment_mode == 'BTC') {
            $condition = empty(Auth::user()->btc_address) or empty(Auth::user()->phone);
        } elseif ($settings->payment_mode == 'ETH') {
            $condition = empty(Auth::user()->eth_address) or empty(Auth::user()->phone);
        } else {
            $condition = empty(Auth::user()->id);
        }

        //Get bonus from users table
        $total_bonus = User::where('id', Auth::user()->id)->first();

        //count the number of plans users have purchased
        $user_plan = User_plans::where('user', Auth::user()->id)->get();

        $user_plan_active = User_plans::where([
                    ['user', '=', Auth::user()->id],
                    ['active', '=', 'yes']
                ])->get();

        //log user out if not approved
        if (Auth::user()->status != "active") {
            $request->session()->flush();
            $request->session()->put('reged', 'yes');
            return redirect()->route('dashboard');
        }//Also log user out if web dashboard is not enabled and user is not admin
        // dd(Auth::user());
        return view('user.dashboard', [
            'title' => 'User panel',
            'ref_earnings' => $ref_earnings,
            'deposited' => $total_deposited,
            'withdrawals' => $withdrawals,
            'total_bonus' => $total_bonus,
            'user_plan' => $user_plan,
            'user_plan_active' => $user_plan_active,
            'upplan' => Plans::where('id', Auth::user()->promo_plan)->first(),
            'uplan' => Plans::where('id', Auth::user()->plan)->first(),

        ]);
    }

    //Profile route
    public function profile()
    {
        $userinfo = User::where('id', Auth::user()->id)->first();
        return view('user.profile')->with(array(
            'userinfo' => $userinfo,
            'title' => 'Profile',

        ));
    }

    //return add withdrawal account form view
    public function accountdetails()
    {
        return view('user.updateacct')->with(array(
          'title' => 'Update account details',

        ));
    }

    //Notification page view
    public function notification()
    {
        return view('user.notification', [
            'Notif' => Notification::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(12),
            'title' => 'Notification',

        ]);
    }

    //support route
    public function support()
    {
        return view('user.support')
        ->with(array(
            'title' => 'Support',

        ));
    }

    //Trading history route
    public function tradinghistory()
    {
        return view('user.thistory')
        ->with(array(
          't_history' => Tp_Transaction::where('user', Auth::user()->id)
          ->where('type', 'ROI')
          ->orderBy('id', 'desc')
          ->get(),
          'title' => 'Trading History',

        ));
    }

    //Account transactions history route
    public function accounthistory()
    {
        return view('user.transactions')
        ->with(array(
          't_history' => Tp_Transaction::where('user', Auth::user()->id)
          ->where('type', '<>', 'ROI')
          ->orderBy('id', 'desc')
          ->get(),

          'withdrawals' => Withdrawal::where('user_id', Auth::user()->id)->orderBy('id', 'desc')
          ->get(),
          'deposits' => Deposit::where('user', Auth::user()->id)->orderBy('id', 'desc')
          ->get(),
          'title' => 'Account Transactions History',

        ));
    }

    //Return deposit route
    public function deposits()
    {
        $paymethod = Wdmethod::where('type', 'deposit')->orWhere('type', 'both')->where('status', 'enabled')->orderByDesc('id')->get();

        // dd($paymethod);
        return view('user.deposits')
        ->with(array(
            'title' => 'Fund your account',
            'dmethods' => $paymethod,
            'deposits' => Deposit::where(['user' => Auth::user()->id])
            ->orderBy('id', 'desc')
            ->get(),

        ));
    }

    //Return withdrawals route
    public function withdrawals()
    {
        // $withdrawals = User::where('id', Auth::user()->id)->get();

        // dd($withdrawals);
        // $withdrawals = Wdmethod::where('type', 'withdrawal')->orWhere('type', 'both')->where('status', 'enabled')->orderByDesc('id')->get();
        // dd($withdrawals);
        return view('user.withdrawals')
         ->with(array(
            'title' => 'Withdraw Your funds',
            'wmethods' => $withdrawals,
         ));
    }

    //Subscription Trading
    public function subtrade(Request $request)
    {
        $settings = Settings::where('id', 1)->first();

        if ($settings->subscription_service != 'on') {
            return redirect()->back();
        }
        return view('user.subtrade')
        ->with(array(
            'title' => 'Subscription Trade',
            'subscriptions' => Mt4Details::where('client_id', auth::user()->id)->orderBy('id', 'desc')->get(),

        ));
    }


    //Main Plans route
    public function mplans()
    {
        return view('user.mplans')
        ->with(array(
            'title' => 'Main Plans',
            'plans' => Plans::where('type', 'main')->get(),
            'settings' => Settings::where('id', '1')->first(),
        ));
    }

    //My Plans route
    public function myplans()
    {
        $plans = User_plans::where('user', Auth::user()->id)->get();
        if (count($plans) < 1) {
            return redirect()->back()->with('message', 'You do not have a package at the moment');
        }
        return view('user.myplans')
        ->with(array(
            'title' => 'Your packages',
            'plans' => User_plans::where('user', Auth::user()->id)->get(),
            'cplan' => User_plans::where('id', Auth::user()->user_plan)->first(),
            'settings' => Settings::where('id', '1')->first(),
        ));
    }

    public function twofa()
    {
        return view('profile.show', [
            'title' => 'Advance Security Settings',
        ]);
    }

    // Referral Page
    public function referuser()
    {
        $user = Auth::user();
        $refs = User::where('ref_by', $user->id)
            ->select('name', 'email', 'created_at', 'ref_bonus', 'account_bal')
            ->orderBy('created_at', 'desc')
            ->get();

        $referralLink = url('/ref/' . $user->username);

        return view('user.referuser', [
            'title'        => 'Refer & Earn',
            'refs'         => $refs,
            'referralLink' => $referralLink,
        ]);
    }

    // Referral Earnings Page — breaks down what the user has earned from referrals
    public function referralEarnings()
    {
        $user = Auth::user();

        // All bonus credits earned by this user, newest first.
        $bonuses = Tp_Transaction::where('user', $user->id)
            ->whereIn('type', ['Ref_bonus', 'Trade_commission'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Resolve the names of the downline users who generated each bonus.
        $names = User::whereIn('id', $bonuses->pluck('from_user')->filter()->unique())
            ->pluck('name', 'id');

        $decorate = function ($txn) use ($names) {
            $txn->source_name = $names[$txn->from_user] ?? null;
            return $txn;
        };

        $referralBonuses = $bonuses->where('type', 'Ref_bonus')->map($decorate)->values();
        $tradeBonuses    = $bonuses->where('type', 'Trade_commission')->map($decorate)->values();

        return view('user.referral-earnings', [
            'title'           => 'Referral Earnings',
            'referralBonuses' => $referralBonuses,
            'tradeBonuses'    => $tradeBonuses,
            'referralTotal'   => $referralBonuses->sum(fn ($t) => (float) $t->amount),
            'tradeTotal'      => $tradeBonuses->sum(fn ($t) => (float) $t->amount),
        ]);
    }

    public function verifyaccount()
    {
        return view('user.verify', [
          'title' => 'Verify your Account',
        ]);
    }

























}
