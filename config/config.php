<?php
declare(strict_types=1);

// Environment variables take precedence over the optional, private local file.
$local = is_file(__DIR__ . '/local.php') ? require __DIR__ . '/local.php' : [];
$value = static function (string $name, mixed $default) use ($local): mixed {
    $environment = getenv($name);
    return $environment !== false ? $environment : ($local[$name] ?? $default);
};

return [
    'db' => [
        'host' => $value('DB_HOST', '127.0.0.1'),
        'port' => (int)$value('DB_PORT', 3306),
        'database' => $value('DB_NAME', 'lumora'),
        'username' => $value('DB_USER', 'root'),
        'password' => $value('DB_PASSWORD', ''),
        'charset' => 'utf8mb4',
    ],
    'app' => [
        'name' => 'Lumora',
        'url' => $value('APP_URL', 'http://127.0.0.1:8000'),
        'environment' => $value('APP_ENV', 'production'),
        'sales_target' => 100000.00,
    ],
];
