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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google_maps' => [
        'api_key' => env('GOOGLE_MAPS_API_KEY'),
    ],

    'mappls' => [
        'access_token' => env('MAPPLS_ACCESS_TOKEN'),
    ],

    'otp' => [
        'user_id'     => env('SMS_PROVIDER_USER_ID', env('OTP_USER_ID')),
        'password'    => env('SMS_PROVIDER_PASSWORD', env('OTP_PASSWORD')),
        'sender_id'   => env('SMS_PROVIDER_SENDER_ID', env('OTP_SENDER_ID', 'NULAC')),
        'template_id' => env('SMS_PROVIDER_OTP_TEMPLATE_ID', env('OTP_TEMPLATE_ID')),
    ],

];
