<?php

declare(strict_types=1);

return [
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
];
