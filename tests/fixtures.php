<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require dirname(__DIR__) . '/bootstrap.php';
if (!preg_match('/^lumora_test_[a-f0-9]{12}$/', $config['db']['database'])) {
    throw new RuntimeException('Tests require a disposable lumora_test_<12 hex digits> database.');
}
$db = App\Core\Database::getConnection();
switch ($argv[1] ?? '') {
    case 'seed':
        $db->exec("INSERT INTO products (category_id,name,slug,price,stock_qty,status) VALUES (1,'Second Artwork','second-artwork',800,20,'Active')");
        $second = (int)$db->lastInsertId();
        $first = (int)$db->query("SELECT id FROM products WHERE slug='lumora-sample-wall-art'")->fetchColumn();
        $db->exec("INSERT INTO product_variants (product_id,sku,price,stock_qty) VALUES ($first,'TEST-VARIANT',650,10)");
        $variant = (int)$db->lastInsertId();
        $db->exec("INSERT INTO product_attribute_values (value) VALUES ('Large')");
        $attribute = (int)$db->lastInsertId();
        $db->exec("INSERT INTO product_variant_options VALUES ($variant,$attribute)");
        echo json_encode(compact('first', 'second', 'variant'));
        break;
    case 'verify-order':
        $order = $db->query('SELECT * FROM orders ORDER BY id DESC LIMIT 1')->fetch();
        $items = $db->query('SELECT * FROM order_items ORDER BY id DESC LIMIT 1')->fetch();
        $stock = $db->query("SELECT stock_qty FROM products WHERE slug='lumora-sample-wall-art'")->fetchColumn();
        $cartCount = $db->query('SELECT COUNT(*) FROM cart_items ci JOIN carts c ON c.id=ci.cart_id WHERE c.user_id=' . (int)$order['user_id'])->fetchColumn();
        if ((float)$order['grand_total'] !== 1050.0 || (int)$items['quantity'] !== 2 || (int)$stock !== 48 || (int)$cartCount !== 0) {
            throw new RuntimeException('Order totals, stock, or cart clearing are incorrect.');
        }
        echo 'Order total, stock decrement, and cart clearing verified.';
        break;
    case 'exhaust-stock':
        $db->exec("UPDATE products SET stock_qty=0 WHERE slug='lumora-sample-wall-art'");
        break;
    case 'verify-rollback':
        if ((int)$db->query('SELECT COUNT(*) FROM orders')->fetchColumn() !== 1) {
            throw new RuntimeException('Failed checkout created an order.');
        }
        echo 'Failed checkout rolled back.';
        break;
    case 'order-id':
        echo $db->query('SELECT id FROM orders LIMIT 1')->fetchColumn();
        break;
    case 'custom-id':
        echo $db->query('SELECT id FROM custom_orders LIMIT 1')->fetchColumn();
        break;
    case 'category-id':
        echo $db->query("SELECT id FROM categories WHERE slug='test-collection'")->fetchColumn();
        break;
    case 'duplicate-id':
        echo $db->query("SELECT id FROM products WHERE status='Draft' ORDER BY id DESC LIMIT 1")->fetchColumn();
        break;
    case 'customer-id':
        echo $db->query("SELECT id FROM users WHERE email='customer@example.test'")->fetchColumn();
        break;
    case 'verify-statuses':
        if ($db->query('SELECT shipping_status FROM orders LIMIT 1')->fetchColumn() !== 'Shipped'
            || $db->query('SELECT payment_status FROM orders LIMIT 1')->fetchColumn() !== 'Paid'
            || $db->query('SELECT status FROM custom_orders LIMIT 1')->fetchColumn() !== 'Accepted') {
            throw new RuntimeException('Admin status updates failed.');
        }
        echo 'Statuses verified.';
        break;
    case 'cleanup':
        // Only this explicitly named, disposable test database can be removed.
        $db->exec('DROP DATABASE `' . $config['db']['database'] . '`');
        echo 'Disposable database removed.';
        break;
    default:
        throw new RuntimeException('Unknown fixture action.');
}
