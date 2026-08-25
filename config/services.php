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

    // Zaraex Single Sign-On (JWT handoff). The shared secret verifies the
    // HS256 token Zaraex issues when a user opens Zarextrade from their app.
    'zarex' => [
        'sso_secret' => env('ZAREXTRADE_SSO_SECRET'),
        'sso_leeway' => (int) env('ZAREXTRADE_SSO_LEEWAY', 30),

        // Zaraex wallet API (server-to-server). We debit the user's Zaraex
        // crypto balance to fund their Zarextrade account. Bearer-token
        // authenticated with a key shared out-of-band by the Zaraex team.
        'api_base_url' => env('ZAREXTRADE_API_BASE_URL'),
        'api_key' => env('ZAREXTRADE_API_KEY'),
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
