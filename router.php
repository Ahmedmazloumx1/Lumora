<?php
// Router for `php -S 127.0.0.1:8000 router.php` only.
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = realpath(__DIR__ . rawurldecode($path ?: '/'));
$public = realpath(__DIR__ . '/public') . DIRECTORY_SEPARATOR;
if ($file !== false && str_starts_with($file, $public) && is_file($file)
    && preg_match('/\.(?:css|js|png|jpe?g|webp|svg|ico|woff2?|ttf|eot)$/i', $file)) {
    return false;
}
if (preg_match('#^/(?:app|config|database|archive|storage|scripts|tests|\.tools)(?:/|$)#i', $path ?: '')
    || (str_contains($path ?: '', '.') && $path !== '/index.php')) {
    http_response_code(404);
    echo 'Not found';
    return;
}
require __DIR__ . '/index.php';
