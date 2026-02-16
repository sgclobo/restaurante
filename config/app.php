<?php

return [
    'app_name' => 'Hau Nia Restaurant',
    'env' => 'development', // production or development
    'debug' => true,
    'url' => 'http://localhost/restaurante', // Base URL
    'timezone' => 'Asia/Makassar', // Example timezone
    'logo' => 'assets/images/logo.png',
    'favicon' => 'assets/images/favicon.png',

    // Database Configuration
    // 'driver' => 'sqlite', // mysql or sqlite
'database' => [
    'driver' => 'mysql', // UDPATE THIS from 'sqlite' to 'mysql'
    'sqlite_path' => __DIR__ . '/../database/database.sqlite',
    'host' => 'localhost',
    'name' => 'restaurante_db', // Ensure this matches what you created
    'user' => 'root',        // Default XAMPP user
    'pass' => '',            // Default XAMPP password (usually empty)
    'charset' => 'utf8mb4',
],

    // Security
    'security' => [
        'encryption_key' => 'YOUR_GENERATED_KEY_HERE', // Should be generated on install
        'session_lifetime' => 86400, // 24 hours
    ],
];
