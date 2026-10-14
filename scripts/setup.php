<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require dirname(__DIR__) . '/bootstrap.php';
$db = $config['db'];
if (!preg_match('/^[A-Za-z0-9_]+$/', $db['database'])) {
    throw new RuntimeException('DB_NAME must contain only letters, digits, and underscores.');
}
$connection = new PDO("mysql:host={$db['host']};port={$db['port']};charset=utf8mb4", $db['username'], $db['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$connection->exec("CREATE DATABASE IF NOT EXISTS `{$db['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$connection->exec("USE `{$db['database']}`");
$existing = $connection->query("SHOW TABLES LIKE 'users'")->fetchColumn();
if ($existing && !$connection->query("SHOW COLUMNS FROM users LIKE 'role_id'")->fetch()) {
    throw new RuntimeException('Legacy database detected. Use a NEW database; see README.md.');
}
$connection->exec(file_get_contents(ROOT_PATH . '/database/schema.sql'));
if (in_array('--demo', $argv, true)) {
    $connection->exec("INSERT IGNORE INTO products (category_id,name,slug,description,short_description,price,sku,stock_qty,status) VALUES (1,'Lumora Sample Wall Art','lumora-sample-wall-art','Sample product for local verification. Replace with your own catalog.','Sample acrylic wall art',500,'LMR-DEMO-001',50,'Active')");
    $connection->exec("INSERT INTO product_gallery (product_id,image_path) SELECT p.id,'placeholder.svg' FROM products p WHERE p.slug='lumora-sample-wall-art' AND NOT EXISTS (SELECT 1 FROM product_gallery g WHERE g.product_id=p.id)");
}
echo "Database schema ready.\n";
