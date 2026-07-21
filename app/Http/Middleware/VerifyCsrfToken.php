<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        // External NOWPayments IPN webhook. Cannot carry a CSRF token; its
        // authenticity is instead verified via HMAC signature in the
        // controller (see CryptoPaymentController::callback). Do NOT add
        // 'crypto-pay' here — that is user-initiated and must keep CSRF.
        'crypto-callback',
    ];
}
