<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NowPaymentsService;

class CryptoPaymentController extends Controller
{
    protected $nowPayments;

    public function __construct(NowPaymentsService $nowPayments)
    {
        $this->nowPayments = $nowPayments;
    }

    public function create(Request $request)
    {
    $user = auth()->user();

    $paymentData = [
        "price_amount" => $request->amount,
        "price_currency" => "usd",
        "pay_currency" => "usdttrc20",
        "ipn_callback_url" => route('crypto.callback'),
        "order_id" => uniqid('order_'),
        "order_description" => "Crypto deposit",
    ];

    $minData = $this->nowPayments->getMinimumAmount('usd', 'usdttrc20');
    $minAmount = $minData['min_amount'] ?? 1.00;

    if ($request->amount < $minAmount) {
        return redirect()->back()->with('error', "Minimum amount is \${$minAmount} USD for USDT payment.");
    }


    $response = $this->nowPayments->createPayment($paymentData);

    // dd($response);

    if (isset($response['invoice_url'])) {
        // Store payment details
        \App\Models\CryptoPayment::create([
            'user_id' => $user->id,
            'order_id' => $paymentData['order_id'],
            'amount' => $request->amount,
        ]);

        return redirect($response['invoice_url']);
    }

    return response()->json($response);
    }


    public function callback(Request $request)
    {
    // Reject any callback whose HMAC signature does not match our IPN secret.
    // Without this check, anyone could POST a forged "finished" payment and
    // credit an arbitrary balance.
    if (!$this->nowPayments->verifyIpnSignature($request->all(), $request->header('x-nowpayments-sig'))) {
        \Log::warning('NOWPayments callback rejected: invalid signature', [
            'order_id' => $request->order_id,
        ]);
        return response('Invalid signature', 400);
    }

    $paymentStatus = $request->payment_status;
    $orderId = $request->order_id;

    if (in_array($paymentStatus, ['confirmed', 'finished'])) {
        // Find the related payment record
        $payment = \App\Models\CryptoPayment::where('order_id', $orderId)->first();

        if ($payment && !$payment->is_completed) {
            \DB::transaction(function () use ($payment, $paymentStatus) {
                // Mark payment as completed
                $payment->is_completed = true;
                $payment->status = $paymentStatus;
                $payment->save();

                // Credit the user with the amount WE recorded when the invoice
                // was created (the USD price), never a value from the request.
                $user = $payment->user;
                $user->account_bal += $payment->amount;
                $user->save();
            });
        }
    }

    // Return 200 OK to acknowledge webhook
    return response('OK', 200);
    }

}
