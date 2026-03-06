<?php

declare(strict_types=1);

return [
    'app' => [
        'timezone' => env('APP_TIMEZONE', 'America/Sao_Paulo'),
    ],
    'database' => [
        // 'driver'   => 'sqlite',
        // 'database' => base_path('database/database.sqlite'),

        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'port' => 3306,
        'dbname' => 'lockbox',
        'user' => 'root',
        'password' => 'mysql',
        'charset' => 'utf8mb4'
    ],
    'security' => [
        'first_key'  => env('ENCRYPT_FIRST_KEY', base64_encode('jeremias')),
        'second_key' => env('ENCRYPT_SECOND_KEY', base64_decode('jeremias123')),
    ],
    'mail' => [
        'from_email' => env('MAIL_FROM_EMAIL', 'organize@test.com'),
        'from_name' => env('MAIL_FROM_NAME', 'Organize'),
        'smtp_host' => env('MAIL_SMTP_HOST', 'sandbox.smtp.mailtrap.io'),
        'smtp_port' => env('MAIL_SMTP_PORT', '2525'),
        'smtp_user' => env('MAIL_SMTP_USER', '09d66f4e973cf4'),
        'smtp_pass' => env('MAIL_SMTP_PASS', '496b9c4d821f96'),
        'smtp_secure' => env('MAIL_SMTP_SECURE', 'tls'),
        'smtp_auth' => env('MAIL_SMTP_AUTH', '1'),
        'smtp_timeout' => env('MAIL_SMTP_TIMEOUT', '15'),
    ]
];
