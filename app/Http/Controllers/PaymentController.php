<?php

namespace App\Http\Controllers;

use App\Services\NOWPaymentsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentController extends Controller
{
    protected $nowPayments;

    public function __construct(NOWPaymentsService $nowPayments)
    {
        $this->nowPayments = $nowPayments;
    }

    /**
     * Show payment form
     */
    public function showPaymentForm(Request $request)
    {
        $currencies = $this->nowPayments->getAvailableCurrencies();
        return view('payment.form', compact('currencies'));
    }

    /**
     * Create payment and redirect to NOWPayments
     */
    public function createPayment(Request $request)
    {

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

    
        $orderId = 'ORDER_' . Str::random(10) . '_' . time();
    
        // Force USDT as the base and pay currency
        $currency = 'usdt'; // invoice currency
        $payCurrency = 'USDTERC20'; // payment currency (optional, same as currency here)
    
        $invoice = $this->nowPayments->createInvoice([
            'amount' => $request->amount,
            'currency' => $currency,
            'pay_currency' => $payCurrency,
            'order_id' => $orderId,
            'description' => $request->description,
            'ipn_callback_url' => route('payment.webhook'),
        ]);
    
        // dd($invoice);

        if ($invoice && isset($invoice['invoice_url'])) {
            DB::table('payments')->insert([
                'order_id' => $orderId,
                'invoice_id' => $invoice['id'],
                'amount' => $request->amount,
                'currency' => $currency,
                'pay_currency' => $payCurrency,
                'status' => 'pending',
                'invoice_url' => $invoice['invoice_url'],
                'description' => $request->description,
                'metadata' => json_encode([
                    'created_from_ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'invoice_data' => $invoice
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // dd($invoice['invoice_url']);
    
            return redirect($invoice['invoice_url']);
        }
    
        return back()->with('error', 'Failed to create payment. Please try again.');
    }
    
    /**
     * Handle successful payment return
     */
    public function paymentSuccess(Request $request)
    {
        $orderId = $request->get('order_id');
        $invoiceId = $request->get('invoice_id');

        // Get payment from database
        $payment = DB::table('payments')
                    ->where('order_id', $orderId)
                    ->orWhere('invoice_id', $invoiceId)
                    ->first();

        if ($payment) {
            // Get latest status from NOWPayments API
            $invoiceStatus = $this->nowPayments->getInvoiceStatus($payment->invoice_id ?? $invoiceId);
            
            if ($invoiceStatus) {
                // Update payment status
                $existingMetadata = json_decode($payment->metadata ?? '[]', true);
                
                DB::table('payments')
                  ->where('id', $payment->id)
                  ->update([
                      'status' => 'finished',
                      'paid_at' => now(),
                      'metadata' => json_encode(array_merge($existingMetadata, $invoiceStatus)),
                      'updated_at' => now(),
                  ]);
            }
        }

        return view('payment.success', [
            'message' => 'Payment completed successfully!',
            'payment' => $payment,
            'details' => $request->all()
        ]);
    }

    /**
     * Handle cancelled payment return
     */
    public function paymentCancel(Request $request)
    {
        $orderId = $request->get('order_id');
        
        if ($orderId) {
            // Update payment status to failed
            DB::table('payments')
              ->where('order_id', $orderId)
              ->update([
                  'status' => 'failed',
                  'updated_at' => now(),
              ]);
        }

        return view('payment.cancel', [
            'message' => 'Payment was cancelled.',
            'details' => $request->all()
        ]);
    }

    /**
     * Handle partial payment
     */
    public function paymentPartial(Request $request, $orderId)
    {
        // Update payment status to partially paid
        DB::table('payments')
          ->where('order_id', $orderId)
          ->update([
              'status' => 'partially_paid',
              'updated_at' => now(),
          ]);

        return view('payment.partial', [
            'message' => 'Payment partially completed.',
            'order_id' => $orderId,
            'details' => $request->all()
        ]);
    }

    /**
     * Handle IPN webhook from NOWPayments
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->all();
        $signature = $request->header('x-nowpayments-sig');
        $secret = env('NOWPAYMENTS_WEBHOOK_SECRET');
    
        // Verify signature
        $sortedPayload = json_encode($payload, JSON_UNESCENDED_SLASHES);
        $calculatedSignature = hash_hmac('sha512', $sortedPayload, $secret);
        if (!hash_equals($calculatedSignature, $signature)) {
            Log::error('Invalid NOWPayments webhook signature', ['payload' => $payload]);
            return response('Invalid signature', 403);
        }
    
        Log::info('NOWPayments Webhook Received', $payload);
    
        if (isset($payload['order_id']) && isset($payload['payment_status'])) {
            $existingPayment = DB::table('payments')
                ->where('order_id', $payload['order_id'])
                ->first();
    
            if ($existingPayment) {
                $updateData = [
                    'status' => $payload['payment_status'],
                    'updated_at' => now(),
                ];
    
                if (isset($payload['payment_id'])) {
                    $updateData['payment_id'] = $payload['payment_id'];
                }
    
                if (isset($payload['pay_amount'])) {
                    $updateData['pay_amount'] = $payload['pay_amount'];
                }
    
                if ($payload['payment_status'] === 'finished') {
                    $updateData['paid_at'] = now();
                }
    
                $existingMetadata = json_decode($existingPayment->metadata ?? '[]', true);
                $updateData['metadata'] = json_encode(array_merge($existingMetadata, [
                    'webhook_data' => $payload,
                    'webhook_received_at' => now()->toISOString(),
                ]));
    
                DB::table('payments')
                    ->where('order_id', $payload['order_id'])
                    ->update($updateData);
            }
        }
    
        return response('OK', 200);
    }

    /**
     * Get payment status API endpoint
     */
    public function getPaymentStatus($orderId)
    {
        $payment = DB::table('payments')
                    ->where('order_id', $orderId)
                    ->first();

        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        return response()->json([
            'order_id' => $payment->order_id,
            'invoice_id' => $payment->invoice_id,
            'status' => $payment->status,
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'pay_currency' => $payment->pay_currency,
            'pay_amount' => $payment->pay_amount,
            'description' => $payment->description,
            'paid_at' => $payment->paid_at,
            'created_at' => $payment->created_at,
        ]);
    }

    /**
     * List all payments (admin dashboard)
     */
    public function listPayments(Request $request)
    {
        $status = $request->get('status');
        $perPage = $request->get('per_page', 15);
        $page = $request->get('page', 1);

        // Build query
        $query = DB::table('payments')->orderBy('created_at', 'desc');
        
        if ($status) {
            $query->where('status', $status);
        }

        // Get total count
        $total = $query->count();

        // Get paginated results
        $payments = $query->offset(($page - 1) * $perPage)
                         ->limit($perPage)
                         ->get();

        // Calculate pagination info
        $totalPages = ceil($total / $perPage);
        $hasMore = $page < $totalPages;

        return view('admin.payments', compact('payments', 'total', 'page', 'totalPages', 'hasMore'));
    }

    /**
     * Get payment statistics
     */
    public function getPaymentStats()
    {
        $stats = DB::table('payments')
                   ->select([
                       'status',
                       'currency',
                       DB::raw('COUNT(*) as count'),
                       DB::raw('SUM(amount) as total_amount')
                   ])
                   ->groupBy('status', 'currency')
                   ->get();

        $totalPayments = DB::table('payments')->count();
        $completedPayments = DB::table('payments')
                              ->whereIn('status', ['finished', 'confirmed'])
                              ->count();
        
        $totalRevenue = DB::table('payments')
                         ->whereIn('status', ['finished', 'confirmed'])
                         ->sum('amount');

        return response()->json([
            'total_payments' => $totalPayments,
            'completed_payments' => $completedPayments,
            'total_revenue' => $totalRevenue,
            'completion_rate' => $totalPayments > 0 ? ($completedPayments / $totalPayments) * 100 : 0,
            'breakdown' => $stats
        ]);
    }

    /**
     * Search payments
     */
    public function searchPayments(Request $request)
    {
        $query = $request->get('q');
        $status = $request->get('status');
        
        $payments = DB::table('payments')
                     ->where(function($q) use ($query) {
                         if ($query) {
                             $q->where('order_id', 'LIKE', "%{$query}%")
                               ->orWhere('invoice_id', 'LIKE', "%{$query}%")
                               ->orWhere('payment_id', 'LIKE', "%{$query}%")
                               ->orWhere('description', 'LIKE', "%{$query}%");
                         }
                     })
                     ->when($status, function($q) use ($status) {
                         return $q->where('status', $status);
                     })
                     ->orderBy('created_at', 'desc')
                     ->limit(50)
                     ->get();

        return response()->json($payments);
    }

    /**
     * Retry failed payment
     */
    public function retryPayment($orderId)
    {
        $payment = DB::table('payments')
                    ->where('order_id', $orderId)
                    ->where('status', 'failed')
                    ->first();

        if (!$payment) {
            return response()->json(['error' => 'Payment not found or not failed'], 404);
        }

        // Create new invoice
        $invoice = $this->nowPayments->createInvoice([
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'pay_currency' => $payment->pay_currency,
            'order_id' => $orderId,
            'description' => $payment->description,
            'ipn_callback_url' => route('payment.webhook'),
        ]);

        if ($invoice && isset($invoice['invoice_url'])) {
            // Update payment with new invoice details
            DB::table('payments')
              ->where('order_id', $orderId)
              ->update([
                  'invoice_id' => $invoice['id'],
                  'invoice_url' => $invoice['invoice_url'],
                  'status' => 'pending',
                  'updated_at' => now(),
              ]);

            return response()->json([
                'success' => true,
                'invoice_url' => $invoice['invoice_url']
            ]);
        }

        return response()->json(['error' => 'Failed to create new invoice'], 500);
    }


}