<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Database;
use PDO;

class ProductController extends Controller {
    private Request $request;
    private PDO $db;

    public function __construct() {
        $this->request = new Request();
        $this->db = Database::getConnection();

        // Enforce RBAC
        $roleId = Session::get('user_role') ? (int)Session::get('user_role') : null;
        if ($roleId !== 1) {
            header('Location: /login');
            exit();
        }
    }

    /**
     * Render product inventory
     */
    public function index(): void {
        $stmt = $this->db->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON c.id = p.category_id WHERE p.deleted_at IS NULL ORDER BY p.created_at DESC");
        $products = $stmt->fetchAll();

        $this->render('admin/products/index', [
            'products' => $products,
            'error' => Session::get('flash_error'),
            'success' => Session::get('flash_success')
        ]);
        Session::remove('flash_error');
        Session::remove('flash_success');
    }

    /**
     * Safe parameterized bulk pricing updates (EGP)
     */
    public function bulkEdit(): void {
        if (!$this->request->validateCsrf()) {
            Session::set('flash_error', 'CSRF verification failed.');
            header('Location: /admin/products');
            exit();
        }

        $input = $this->request->getBody();
        $type = $input['type'] ?? 'flat'; // 'flat' or 'percent'
        $action = $input['action'] ?? 'add'; // 'add' or 'subtract'
        $amount = (float)($input['amount'] ?? 0);

        if ($amount <= 0) {
            Session::set('flash_error', 'Invalid adjustment amount.');
            header('Location: /admin/products');
            exit();
        }

        $operator = ($action === 'add') ? '+' : '-';

        if ($type === 'percent') {
            // Apply percentage calculations safely
            $multiplier = ($action === 'add') ? (1 + ($amount / 100)) : (1 - ($amount / 100));
            $stmt = $this->db->prepare("UPDATE products SET price = GREATEST(0, price * :multiplier) WHERE deleted_at IS NULL");
            $stmt->execute(['multiplier' => $multiplier]);
        } else {
            // Apply flat rate calculations safely
            $stmt = $this->db->prepare("UPDATE products SET price = GREATEST(0, price {$operator} :amount) WHERE deleted_at IS NULL");
            $stmt->execute(['amount' => $amount]);
        }

        Session::set('flash_success', 'Global catalog pricing updated successfully.');
        header('Location: /admin/products');
        exit();
    }

    /**
     * Duplicate existing product record cleanly
     */
    public function duplicate(): void {
        if (!$this->request->validateCsrf()) {
            Session::set('flash_error', 'CSRF verification failed.');
            header('Location: /admin/products');
            exit();
        }

        $input = $this->request->getBody();
        $productId = isset($input['product_id']) ? (int)$input['product_id'] : null;

        if (!$productId) {
            Session::set('flash_error', 'Missing product identifier.');
            header('Location: /admin/products');
            exit();
        }

        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $productId]);
        $product = $stmt->fetch();

        if (!$product) {
            Session::set('flash_error', 'Product record not found.');
            header('Location: /admin/products');
            exit();
        }

        // Duplicate row variables cleanly
        $newName = $product['name'] . ' (Copy)';
        $newSlug = $product['slug'] . '-' . uniqid();

        $dupStmt = $this->db->prepare("
            INSERT INTO products (category_id, name, slug, short_description, description, price, sale_price, sku, stock_qty, status) 
            VALUES (:category_id, :name, :slug, :short_description, :description, :price, :sale_price, :sku, :stock_qty, 'Draft')
        ");

        $dupStmt->execute([
            'category_id'       => $product['category_id'],
            'name'              => $newName,
            'slug'              => $newSlug,
            'short_description' => $product['short_description'],
            'description'       => $product['description'],
            'price'             => $product['price'],
            'sale_price'        => $product['sale_price'],
            'sku'               => $product['sku'] ? $product['sku'] . '-' . bin2hex(random_bytes(4)) : null,
            'stock_qty'         => $product['stock_qty']
        ]);

        Session::set('flash_success', "Product duplicated successfully as Draft: '{$newName}'.");
        header('Location: /admin/products');
        exit();
    }

    /**
     * Non-destructive soft delete to protect historical order records [1.1.1]
     */
    public function delete(): void {
        if (!$this->request->validateCsrf()) {
            Session::set('flash_error', 'CSRF verification failed.');
            header('Location: /admin/products');
            exit();
        }

        $input = $this->request->getBody();
        $productId = isset($input['product_id']) ? (int)$input['product_id'] : null;

        if ($productId) {
            $stmt = $this->db->prepare("UPDATE products SET deleted_at = NOW() WHERE id = :id");
            $stmt->execute(['id' => $productId]);
            Session::set('flash_success', 'Product record moved to soft deleted trash.');
        }

        header('Location: /admin/products');
        exit();
    }
}
