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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel'              => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_REDIRECT'),
    ],

    'facebook' => [
        'client_id'     => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect'      => env('FACEBOOK_REDIRECT'),
    ],

    'apple' => [
        'client_id' => env('APPLE_CLIENT_ID'),     // Service ID
        'team_id' => env('APPLE_TEAM_ID'),         // Team ID
        'key_id' => env('APPLE_KEY_ID'),           // Key ID
        'private_key' => env('APPLE_PRIVATE_KEY'), // путь к .p8 файлу или сам ключ
        'redirect' => env('APPLE_REDIRECT_URI'),   // callback URL
        'client_secret' => '', // важно: нужен, но будет заменён динамически
    ],

    'novaposhta' => [
        'api_key' => env('NOVA_POSHTA_API_KEY'),
    ],
];
