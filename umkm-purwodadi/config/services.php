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

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'midtrans' => [
        'server_key' => env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-DEMO123456789'),
        'client_key' => env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-DEMO123456789'),
        'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
        'is_sanitized' => true,
        'is_3ds' => true,
    ],

    'qris' => [
        'static_code' => env('QRIS_STATIC_CODE', ''),
        'merchant_name' => env('QRIS_MERCHANT_NAME', 'UMKM PURWODADI'),
        'merchant_city' => env('QRIS_MERCHANT_CITY', 'LAMPUNG TENGAH'),
    ],

    'buatqris' => [
        'api_key' => env('BUATQRIS_API_KEY', ''),
        'api_url' => env('BUATQRIS_API_URL', 'https://app.buatqris.site/api'),
    ],

];
