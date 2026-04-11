<?php

return [
    'limits' => [
        'campaigns_per_day' => (int) env('INFIMAL_CAMPAIGNS_PER_DAY', 10),
        'emails_per_day' => (int) env('INFIMAL_EMAILS_PER_DAY', 5000),
        'subscribers_per_user' => (int) env('INFIMAL_SUBSCRIBERS_PER_USER', 50000),
    ],
    'queue' => [
        'connection' => env('QUEUE_CONNECTION', 'database'),
        'user_email_queue' => env('INFIMAL_USER_EMAIL_QUEUE', 'user_email_jobs'),
        'admin_email_queue' => env('INFIMAL_ADMIN_EMAIL_QUEUE', 'admin_email_jobs'),
    ],
    'smtp_validation_probe_to' => env('INFIMAL_SMTP_PROBE_TO'),

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

    'workers' => [
        'user_email_jobs' => [
            'max_processes' => (int) env('INFIMAL_USER_WORKERS', 8),
            'balance' => 'auto',
        ],
        'admin_email_jobs' => [
            'max_processes' => (int) env('INFIMAL_ADMIN_WORKERS', 20),
            'balance' => 'auto',
        ],
    ],
];

