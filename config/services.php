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

    'whatsapp' => [
        'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
        'access_token' => env('WHATSAPP_ACCESS_TOKEN'),
        'verify_token' => env('WHATSAPP_VERIFY_TOKEN'),
        'app_secret' => env('WHATSAPP_APP_SECRET'),
        'url' => 'https://graph.facebook.com/v18.0',
    ],

    'instagram' => [
        'access_token' => env('INSTAGRAM_ACCESS_TOKEN'),
        'verify_token' => env('INSTAGRAM_VERIFY_TOKEN'),
        'app_secret' => env('INSTAGRAM_APP_SECRET'),
        'url' => 'https://graph.facebook.com/v18.0',
    ],

    'anthropic' => [
        'key' => env('ANTHROPIC_API_KEY'),
        'url' => 'https://api.anthropic.com/v1/messages',
    ],

    // AI Provider Configuration
    'ai' => [
        'provider' => env('AI_PROVIDER', 'gemini'), // gemini, kimi, glm, or anthropic
    ],

    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
    ],

    'kimi' => [
        'api_key' => env('KIMI_API_KEY'),
    ],

    'glm' => [
        'api_key' => env('GLM_API_KEY'),
    ],

    'webhook_skip_verify' => env('WEBHOOK_SKIP_VERIFY', false),

];
