<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CryptomusPayController extends Controller
{
    public function createInvoice(Request $request)
    {

        dd($request);
        $merchantId = '0c6ee885-d521-4b66-b41a-a9e5d531a44b';
        $apiKey = 'your_api_key_here';

        $url = 'https://api.cryptomus.com/v1/payment';

        $payload = [
            'amount' => '15',
            'currency' => 'USD',
            'order_id' => Str::random(10),
            'network' => 'tron',
            'url_return' => 'https://yourstore.com/return',
            'url_success' => 'https://yourstore.com/success',
            'url_callback' => 'https://yourstore.com/webhook',
            'is_payment_multiple' => false,
            'lifetime' => 3600,
        ];

        $payloadJson = json_encode($payload);

        $signature = md5(base64_encode($payloadJson) . $apiKey);

        try {
            $response = Http::withHeaders([
                'merchant' => $merchantId,
                'sign' => $signature,
                'Content-Type' => 'application/json',
            ])->post($url, $payload);

            if ($response->successful()) {
                $responseData = $response->json();
                return response()->json([
                    'success' => true,
                    'data' => $responseData['result'],
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => $response->json()['message'] ?? 'Request failed',
                    'details' => $response->json()['errors'] ?? null,
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'An error occurred',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function handleWebhook(Request $request)
    {
        $merchantId = '8b03432e-385b-4670-8d06-064591096795';
        $apiKey = 'your_api_key_here';

        $data = $request->all();
        $receivedSign = $request->header('sign');
        $payloadJson = json_encode($data);
        $expectedSign = md5(base64_encode($payloadJson) . $apiKey);

        if ($receivedSign === $expectedSign) {
            return response()->json(['status' => 'received']);
        }

        return response()->json(['error' => 'Invalid signature'], 401);
    }
}
