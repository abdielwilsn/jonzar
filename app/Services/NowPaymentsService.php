<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NowPaymentsService
{
    protected $baseUrl;
    protected $apiKey;
    protected $ipnSecret;

    public function __construct()
    {
        $this->baseUrl = config('services.nowpayments.base_url');
        $this->apiKey = config('services.nowpayments.api_key');
        $this->ipnSecret = config('services.nowpayments.ipn_secret');
    }

    /**
     * Verify a NOWPayments IPN callback signature.
     *
     * NOWPayments signs the raw request body with HMAC-SHA512 using the IPN
     * secret, over the JSON payload with keys sorted alphabetically, and sends
     * the digest in the "x-nowpayments-sig" header. We recompute it and compare
     * in constant time. Returns false if no secret is configured so that an
     * unconfigured callback can never credit balances.
     *
     * @param  array   $payload           The decoded request body ($request->all()).
     * @param  string|null  $providedSignature  The x-nowpayments-sig header value.
     */
    public function verifyIpnSignature(array $payload, ?string $providedSignature): bool
    {
        if (empty($this->ipnSecret) || empty($providedSignature)) {
            return false;
        }

        $sorted = $payload;
        $this->ksortRecursive($sorted);

        $expected = hash_hmac(
            'sha512',
            json_encode($sorted, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            $this->ipnSecret
        );

        return hash_equals($expected, $providedSignature);
    }

    private function ksortRecursive(array &$array): void
    {
        ksort($array);
        foreach ($array as &$value) {
            if (is_array($value)) {
                $this->ksortRecursive($value);
            }
        }
    }

    public function createPayment(array $data)
    {
        $response = Http::withHeaders([
            'x-api-key' => $this->apiKey
        ])->post("{$this->baseUrl}/invoice", $data);

        return $response->json();
    }

    public function getMinimumAmount($from = 'usd', $to = 'usdttrc20')
{
    $response = Http::withHeaders([
        'x-api-key' => $this->apiKey
    ])->get("{$this->baseUrl}/min-amount", [
        'currency_from' => $from,
        'currency_to' => $to,
        'fiat_equivalent' => 'usd',
        'is_fixed_rate' => 'false',
        'is_fee_paid_by_user' => 'false',
    ]);

    return $response->json();
}


    public function getPaymentStatus($paymentId)
    {
        $response = Http::withHeaders([
            'x-api-key' => $this->apiKey
        ])->get("{$this->baseUrl}/payment/$paymentId");

        return $response->json();
    }
}
