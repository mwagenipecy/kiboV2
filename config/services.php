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

    'openai' => [
        'key' => env('OPENAI_API_KEY'),
        'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
        'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
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

    'twilio' => [
        'sid' => env('TWILIO_ACCOUNT_SID'),
        'token' => env('TWILIO_AUTH_TOKEN'),
        'whatsapp_from' => env('TWILIO_WHATSAPP_FROM', 'whatsapp:+255794777772'),
        // WhatsApp Content Template SIDs (optional – use for consistent UI)
        'language_selection_template_sid' => env('TWILIO_LANGUAGE_SELECTION_TEMPLATE_SID', null),
        'main_menu_template_sid' => env('TWILIO_MAIN_MENU_TEMPLATE_SID', null),
        'menu_template_sid' => env('TWILIO_MENU_TEMPLATE_SID', null),
    ],

    'selcom_sms' => [
        'base_url' => env('SELCOM_SMS_BASE_URL', 'https://gw.selcommobile.com:8443'),
        'username' => env('SELCOM_SMS_USERNAME', 'savannahills'),
        'password' => env('SELCOM_SMS_PASSWORD', 'savannahills'),
    ],

    'universal_payment_link' => [
        'base_url' => env('PAYMENT_LINK_BASE_URL', 'http://197.250.35.61:8085'),
        'api_key' => env('PAYMENT_LINK_API_KEY'),
        'api_secret' => env('PAYMENT_LINK_API_SECRET'),
        'generate_universal_path' => '/api/payment-links/generate-universal',
    ],
    'mailgun' => [
    'domain' => env('MAILGUN_DOMAIN'),
    'secret' => env('MAILGUN_SECRET'),
    'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
],

    'kibomailer' => [
        'base_url' => env('KIBO_MAILER_BASE_URL'),
        'api_key' => env('KIBO_MAILER_API_KEY'),
        'api_secret' => env('KIBO_MAILER_API_SECRET'),
    ],


];
