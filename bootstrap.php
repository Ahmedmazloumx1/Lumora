<?php
declare(strict_types=1);

define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');

spl_autoload_register(static function (string $class): void {
    if (str_starts_with($class, 'App\\')) {
        $file = APP_PATH . '/' . str_replace('\\', '/', substr($class, 4)) . '.php';
        if (is_file($file)) {
            require $file;
        }
    }
});

$config = require ROOT_PATH . '/config/config.php';
error_reporting(E_ALL);
ini_set('display_errors', $config['app']['environment'] === 'development' ? '1' : '0');
ini_set('log_errors', '1');
foreach (['logs', 'sessions'] as $directory) {
    $path = ROOT_PATH . '/storage/' . $directory;
    if (!is_dir($path) && !mkdir($path, 0755, true) && !is_dir($path)) {
        throw new RuntimeException('Cannot create application storage directory.');
    }
}
ini_set('error_log', ROOT_PATH . '/storage/logs/error.log');
if (PHP_SAPI !== 'cli') {
    session_save_path(ROOT_PATH . '/storage/sessions');
}
