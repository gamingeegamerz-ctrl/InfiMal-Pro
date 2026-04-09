<?php

return [
    'limits' => [
        'campaigns_per_day' => (int) env('INFIMAL_CAMPAIGNS_PER_DAY', 10),
        'emails_per_day' => (int) env('INFIMAL_EMAILS_PER_DAY', 5000),
        'subscribers_per_user' => (int) env('INFIMAL_SUBSCRIBERS_PER_USER', 50000),
    ],
    'queue' => [
        'connection' => env('QUEUE_CONNECTION', 'database'),
        'email_queue' => env('INFIMAL_EMAIL_QUEUE', 'emails'),
    ],
    'deliverability' => [
        'spf' => env('MAIL_SPF_RECORD', ''),
        'dkim_selector' => env('MAIL_DKIM_SELECTOR', ''),
        'dkim_domain' => env('MAIL_DKIM_DOMAIN', ''),
        'dkim_private_key' => env('MAIL_DKIM_PRIVATE_KEY', ''),
        'dmarc' => env('MAIL_DMARC_RECORD', ''),
    ],
    'otp' => [
        'from_address' => env('OTP_FROM_ADDRESS', 'noreply@yourdomain.com'),
        'from_name' => env('OTP_FROM_NAME', env('APP_NAME', 'InfiMal')) ,
    ],
    'alerts' => [
        'ops_email' => env('OPS_ALERT_EMAIL'),
        'enabled' => (bool) env('OPS_ALERTS_ENABLED', true),
    ],
];

