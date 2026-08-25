<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
use App\Models\Mt4Dtails;
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

class DepositController extends Controller
{
    public function getmethod($id){
       $methodname =  Wdmethod::where('id', $id)->first();
        return response()->json($methodname->name);
    }

    //Return payment page
    public function newdeposit(Request $request){

        $settings = Settings::where('id', '1')->first();
        $methodname =  Wdmethod::where('name', $request->payment_method)->first();

        if ($methodname->name == "Zarex") {
            return $this->redirectToZarex($request);
        }

        if ($methodname->name == "Stripe") {
            $secretkey = $settings->s_s_k;
            $publickey = $settings->s_p_k;
            $zero = '00';
            $amt = $request->amount.$zero;

            \Stripe\Stripe::setApiKey($secretkey);
            $paymentIntent  = \Stripe\PaymentIntent::create([
                'amount' => $amt,
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'description' => 'Funding My Investment Account',
                'shipping' => [
                    'name' => Auth::user()->name,
                    'address' => [
                      'line1' => 'No Address',
                      'postal_code' => '000000',
                      'city' => 'No City',
                      'state' => 'CA',
                      'country' => 'US',
                    ],
                  ],
                'metadata' => ['integration_check' => 'accept_a_payment'],
            ]);

            $output = [
                'publishableKey' => $publickey,
                'clientSecret' => $paymentIntent->client_secret,
            ];

            //return $client_secret;
            $client_secret = $paymentIntent->client_secret;
        }




        //store payment info in session
        $request->session()->put('amount', $request['amount']);
        $request->session()->put('payment_mode', $methodname->name);
        $request->session()->put('intent', $client_secret);
        return redirect()->route('payment');
    }

    //payment route
    public function payment(Request $request){
        // if ($request->session()->exists('amount')) {
        //     return redirect()->route('deposits')->with('message', 'Please Select a payment method and amount');
        // }
        $methodname =  Wdmethod::where('name',$request->session()->get('payment_mode'))->first();

        // $set = Settings::where('id', '=', '1')->first();
        // dd($set);
        return view('user.payment')
        ->with(array(
            'amount'=>$request->session()->get('amount'),
            'payment_mode'=>$methodname,
            // 'pay_type' => $request->session()->get('pay_type'),
            // 'plan_id' => $request->session()->get('plan_id'),
            'intent' => $request->session()->get('intent'),
            'title' => 'Make Payment',
            'settings' => Settings::where('id', '=', '1')->first(),
        ));
    }

    public function savestripepayment(Request $request)
    {
        $user=User::where('id',Auth::user()->id)->first();
        //save and confirm the deposit
        $dp=new Deposit();
        $dp->amount= $request->amount;
        $dp->payment_mode= "Stripe";
        $dp->status= 'Processed';
        $dp->proof= "Stripe";
        $dp->plan= 0;
        $dp->user= $user->id;
        $dp->save();

        //add funds to user's account
        User::where('id',$user->id)
        ->update([
            'account_bal' => $user->account_bal + $request->amount,
        ]);

        //get settings
        $settings=Settings::where('id', '=', '1')->first();
        $earnings=$settings->referral_commission*$request->amount/100;

        if(!empty($user->ref_by)){
            //increment the user's referee total clients activated by 1
            Agent::where('agent',$user->ref_by)->increment('total_activated', 1);
            Agent::where('agent',$user->ref_by)->increment('earnings', $earnings);

            //add earnings to agent balance
            //get agent
            $agent=User::where('id',$user->ref_by)->first();
            User::where('id',$user->ref_by)
            ->update([
                'account_bal' => $agent->account_bal + $earnings,
                'ref_bonus' => $agent->ref_bonus + $earnings,
            ]);

            //credit commission to ancestors
            $deposit_amount = $request->amount;
            $array=User::all();
            $parent=$user->id;
            $this->getAncestors($array, $deposit_amount, $parent, 0, $user->id);

            Tp_Transaction::create([
                'user' => $user->ref_by,
                'from_user' => $user->id,
                'plan' => "Credit",
                'amount'=>$earnings,
                'type'=>"Ref_bonus",
            ]);
        }

        //send notification
        $settings=Settings::where('id', '=', '1')->first();
        //send email notification
        $objDemo = new \stdClass();
        $objDemo->message = "This is to inform you that you have successfully deposited $settings->currency$request->amount.";
        $objDemo->sender = $settings->site_name;
        $objDemo->date = \Carbon\Carbon::Now();
        $objDemo->subject ="Successful Deposit";

        Mail::bcc($user->email)->send(new NewNotification($objDemo));

        // Kill the session variables
        $request->session()->forget('payment_mode');
        $request->session()->forget('amount');
        $request->session()->forget('intent');

        return response()->json(['success'=>'Payment Completed, redirecting']);
    }

    //Save deposit requests
    public function savedeposit(Request $request){



        // dd($request['paymethd_method']);
        $this->validate($request, [
            'proof' => 'image|mimes:jpg,jpeg,png|max:10240',
        ]);

        $settings = Settings::where('id', '=', '1')->first();
        $strtxt = $this->RandomStringGenerator(6);





        if($request->hasFile('proof')) {
            $file = $request->file('proof');
            $originalName = $file->getClientOriginalName();

            // Extract extension using pathinfo
            $ext = strtolower($file->getClientOriginalExtension());
            $whitelist = ['pdf', 'doc', 'jpeg', 'jpg', 'png'];

            if (in_array($ext, $whitelist)) {

                // Remove extension from original name and clean it
                $basename = pathinfo($originalName, PATHINFO_FILENAME);
                $timestamp = time();
                $proofname = $strtxt . $basename . '_' . $timestamp . '.' . $ext;

                if ($settings->location == "Email") {
                    $data = [
                        'document' => $file
                    ];
//                    Mail::to($settings->contact_email)->send(new UserUpload($data));

                } elseif ($settings->location == "Local") {
                    $path = $file->storeAs('public/photos', $proofname);


                    $data = [
                        'document' => $file
                    ];

//                    Mail::to($settings->contact_email)->send(new UserUpload($data));

                } else {
                    $filePath = 'storage/' . $proofname;
                    Storage::disk('s3')->put($filePath, file_get_contents($file));
                }

            } else {
                return redirect()->back()->with('message', 'Unaccepted Image Uploaded');
            }
        }




        $dp=new Deposit();
        $dp->amount= $request['amount'];
        $dp->payment_mode= $request['paymethd_method'];
        $dp->status= 'Pending';
        $dp->proof = $proofname;
        $dp->user= Auth::user()->id;
        $dp->save();


        //get user
        $user=User::where('id',Auth::user()->id)->first();



        //send email notification

        $objDemo = new \stdClass();
        $objDemo->message = "This is to inform you of a successful Deposit of $settings->currency $request->amount, that just occured on your system. please login to process this deposit";
        $objDemo->sender = $settings->site_name;
        $objDemo->date = \Carbon\Carbon::Now();
        $objDemo->subject ="Successful Deposit";
        Mail::bcc($settings->contact_email)->send(new NewNotification($objDemo));

        $objDemou = new \stdClass();
        $objDemou->message = "This is to inform you of a successful deposit of $settings->currency $request->amount, that just occured on your account. please wait while we process this transaction, you will receive a notification of your transaction status.";
        $objDemou->sender = $settings->site_name;
        $objDemou->date = \Carbon\Carbon::Now();
        $objDemou->subject ="Successful Deposit";
        Mail::bcc($user->email)->send(new NewNotification($objDemou));


        // dd("lotad");

        // Kill the session variables
        $request->session()->forget('payment_mode');
        $request->session()->forget('amount');

        return redirect()->route('deposits')
        ->with('success', 'Account Fund Sucessful! Please wait for system to validate this transaction.');
    }

    /**
     * Fund the Zarextrade account by debiting the user's Zaraex wallet
     * balance server-to-server (POST /wallet/debit on the Zaraex API).
     * Only USDT/USDC are offered since they're ~1:1 with USD, so no
     * exchange-rate lookup is needed to know how much to credit.
     */
    private function redirectToZarex(Request $request)
    {
        $coin = strtoupper((string) $request->input('coin'));

        if (!in_array($coin, ['USDT', 'USDC'], true)) {
            return redirect()->route('deposits')->with('message', 'Please select USDT or USDC to deposit via Zarex.');
        }

        $amount = (float) $request->amount;
        $user = Auth::user();

        if (empty($user->zarex_user_id)) {
            return redirect()->route('deposits')->with('message', 'Your account is not linked to Zarex.');
        }

        $baseUrl = config('services.zarex.api_base_url');
        $apiKey = config('services.zarex.api_key');

        if (empty($baseUrl) || empty($apiKey)) {
            Log::error('Zarex wallet integration is not configured (ZAREXTRADE_API_BASE_URL / ZAREXTRADE_API_KEY).');
            return redirect()->route('deposits')->with('message', 'Zarex deposits are temporarily unavailable.');
        }

        $http = Http::baseUrl($baseUrl)->withToken($apiKey)->timeout(15);

        $balanceResponse = $http->get('/wallet/balance', [
            'user_id' => $user->zarex_user_id,
            'currency' => $coin,
        ]);

        if (!$balanceResponse->successful()) {
            Log::warning('Zarex wallet/balance check failed: ' . $balanceResponse->body());
            return redirect()->route('deposits')->with('message', 'Could not reach Zarex to check your balance. Please try again.');
        }

        if ((float) $balanceResponse->json('available', 0) < $amount) {
            return redirect()->route('deposits')->with('message', "Insufficient $coin balance on Zarex for this deposit.");
        }

        $reference = (string) Str::uuid();

        $debitResponse = $http->post('/wallet/debit', [
            'user_id' => $user->zarex_user_id,
            'amount' => $amount,
            'currency' => $coin,
            'reference' => $reference,
            'idempotency_key' => $reference,
        ]);

        if ($debitResponse->status() === 400) {
            return redirect()->route('deposits')->with('message', $debitResponse->json('error', 'Zarex declined this deposit.'));
        }

        if (!$debitResponse->successful()) {
            Log::error('Zarex wallet/debit failed: ' . $debitResponse->body());
            return redirect()->route('deposits')->with('message', 'Zarex deposit failed. Please try again.');
        }

        $txnId = 'zarex_' . $debitResponse->json('transaction_id', $reference);

        // Idempotency: guard against a duplicate submit resulting in a second credit.
        if (Deposit::where('txn_id', $txnId)->exists()) {
            return redirect()->route('deposits')->with('success', 'Payment already processed.');
        }

        $dp = new Deposit();
        $dp->amount = $amount;
        $dp->txn_id = $txnId;
        $dp->payment_mode = 'Zarex';
        $dp->status = 'Processed';
        $dp->proof = $coin;
        $dp->plan = 0;
        $dp->user = $user->id;
        $dp->save();

        User::where('id', $user->id)
            ->update([
                'account_bal' => $user->account_bal + $amount,
            ]);

        $settings = Settings::where('id', '=', '1')->first();
        $earnings = $settings->referral_commission * $amount / 100;

        if (!empty($user->ref_by)) {
            Agent::where('agent', $user->ref_by)->increment('total_activated', 1);
            Agent::where('agent', $user->ref_by)->increment('earnings', $earnings);

            $agent = User::where('id', $user->ref_by)->first();
            User::where('id', $user->ref_by)
                ->update([
                    'account_bal' => $agent->account_bal + $earnings,
                    'ref_bonus' => $agent->ref_bonus + $earnings,
                ]);

            $array = User::all();
            $this->getAncestors($array, $amount, $user->id, 0, $user->id);

            Tp_Transaction::create([
                'user' => $user->ref_by,
                'from_user' => $user->id,
                'plan' => 'Credit',
                'amount' => $earnings,
                'type' => 'Ref_bonus',
            ]);
        }

        $objDemo = new \stdClass();
        $objDemo->message = "This is to inform you that you have successfully deposited $settings->currency$amount.";
        $objDemo->sender = $settings->site_name;
        $objDemo->date = \Carbon\Carbon::Now();
        $objDemo->subject = "Successful Deposit";

        Mail::bcc($user->email)->send(new NewNotification($objDemo));

        return redirect()->route('deposits')->with('success', 'Payment Completed');
    }

    // for front end content management
    function RandomStringGenerator($n)
    {
        $generated_string = "";
        $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $len = strlen($domain);
        for ($i = 0; $i < $n; $i++)
        {
            $index = rand(0, $len - 1);
            $generated_string = $generated_string . $domain[$index];
        }
        // Return the random generated string
        return $generated_string;
    }

    //Get uplines
    function getAncestors($array, $deposit_amount, $parent = 0, $level = 0, $origin = null) {
        $referedMembers = '';
        $parent=User::where('id',$parent)->first();

        foreach ($array as $entry) {
            if ($entry->id == $parent->ref_by) {
                //get settings
                $settings=Settings::where('id', '=', '1')->first();

                if($level == 1){
                    $earnings=$settings->referral_commission1*$deposit_amount/100;
                    //add earnings to ancestor balance
                    User::where('id',$entry->id)
                    ->update([
                        'account_bal' => $entry->account_bal + $earnings,
                        'ref_bonus' => $entry->ref_bonus + $earnings,
                    ]);

                    //create history
                    Tp_Transaction::create([
                        'user' => $entry->id,
                        'from_user' => $origin,
                        'plan' => "Credit",
                        'amount'=>$earnings,
                        'type'=>"Ref_bonus",
                    ]);

                }elseif($level == 2){
                    $earnings=$settings->referral_commission2*$deposit_amount/100;
                    //add earnings to ancestor balance
                    User::where('id',$entry->id)
                    ->update([
                        'account_bal' => $entry->account_bal + $earnings,
                        'ref_bonus' => $entry->ref_bonus + $earnings,
                    ]);

                    //create history
                    Tp_Transaction::create([
                        'user' => $entry->id,
                        'from_user' => $origin,
                        'plan' => "Credit",
                        'amount'=>$earnings,
                        'type'=>"Ref_bonus",
                    ]);

                }elseif($level == 3){
                    $earnings=$settings->referral_commission3*$deposit_amount/100;
                    //add earnings to ancestor balance
                    User::where('id',$entry->id)
                    ->update([
                        'account_bal' => $entry->account_bal + $earnings,
                        'ref_bonus' => $entry->ref_bonus + $earnings,
                    ]);

                    //create history
                    Tp_Transaction::create([
                        'user' => $entry->id,
                        'from_user' => $origin,
                        'plan' => "Credit",
                        'amount'=>$earnings,
                        'type'=>"Ref_bonus",
                    ]);

                }elseif($level == 4){
                    $earnings=$settings->referral_commission4*$deposit_amount/100;
                    //add earnings to ancestor balance
                    User::where('id',$entry->id)
                    ->update([
                        'account_bal' => $entry->account_bal + $earnings,
                        'ref_bonus' => $entry->ref_bonus + $earnings,
                    ]);

                    //create history
                    Tp_Transaction::create([
                        'user' => $entry->id,
                        'from_user' => $origin,
                        'plan' => "Credit",
                        'amount'=>$earnings,
                        'type'=>"Ref_bonus",
                    ]);

                }elseif($level == 5){
                    $earnings=$settings->referral_commission5*$deposit_amount/100;
                    //add earnings to ancestor balance
                    User::where('id',$entry->id)
                    ->update([
                        'account_bal' => $entry->account_bal + $earnings,
                        'ref_bonus' => $entry->ref_bonus + $earnings,
                    ]);

                    //create history
                    Tp_Transaction::create([
                        'user' => $entry->id,
                        'from_user' => $origin,
                        'plan' => "Credit",
                        'amount'=>$earnings,
                        'type'=>"Ref_bonus",
                    ]);

                }

                if($level == 6){
                    break;
                }

                //$referedMembers .= '- ' . $entry->name . '- Level: '. $level. '- Commission: '.$earnings.'<br/>';
                $referedMembers .= $this->getAncestors($array, $deposit_amount, $entry->id, $level+1, $origin);

            }
        }
        return $referedMembers;
    }

}
