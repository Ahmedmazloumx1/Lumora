<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require dirname(__DIR__) . '/bootstrap.php';
$email = getenv('ADMIN_EMAIL') ?: '';
$password = getenv('ADMIN_PASSWORD') ?: '';
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 12) {
    fwrite(STDERR, "Set ADMIN_EMAIL and ADMIN_PASSWORD (at least 12 characters) in your environment.\n");
    exit(1);
}
$db = App\Core\Database::getConnection();
$stmt = $db->prepare('INSERT INTO users (role_id,name,email,password) VALUES (1,:name,:email,:password)');
$stmt->execute(['name' => 'Lumora Admin', 'email' => $email, 'password' => password_hash($password, PASSWORD_DEFAULT)]);
echo "Administrator created. Sign in at /login.\n";
