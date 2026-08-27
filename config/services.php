<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'nowpayments' => [
    'api_key' => env('NOWPAYMENTS_API_KEY'),
    'base_url' => env('NOWPAYMENTS_API_BASE', 'https://api.nowpayments.io/v1'),
    'ipn_secret' => env('NOWPAYMENTS_IPN_SECRET'),
    ],

    // Zaraex Single Sign-On (one-time code handoff, exchanged server-to-server
    // for the user's profile via the wallet API key below — see
    // ZarexSsoController). ZAREXTRADE_SSO_SECRET/LEEWAY are no longer used;
    // this used to verify a JWT passed directly in the URL, which review
    // flagged as exposing the login credential via browser history/logs.
    'zarex' => [
        // Zaraex wallet API (server-to-server), Bearer-token authenticated
        // with a key shared out-of-band by the Zaraex team. Used for the SSO
        // code exchange and for resolving a user's Zaraex deposit address so
        // withdrawals can be sent there as a real on-chain crypto transfer.
        'api_base_url' => env('ZAREXTRADE_API_BASE_URL'),
        'api_key' => env('ZAREXTRADE_API_KEY'),

        // Zaraex-hosted page that mints an SSO token for a visitor who is
        // already logged into Zaraex (or sends them to log in first) and
        // redirects back to /auth/zarex. Powers "Continue with Zaraex" on
        // our own login page. URL unconfirmed with the Zaraex team.
        'authorize_url' => env('ZAREXTRADE_AUTHORIZE_URL'),
    ],


    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URI'),
    ],


    'etherscan' => [
        'api_key' => env('ETHERSCAN_API_KEY'),
    ],

    'wallet' => [
        'address' => env('MAIN_WALLET_ADDRESS'),
    ],


];
