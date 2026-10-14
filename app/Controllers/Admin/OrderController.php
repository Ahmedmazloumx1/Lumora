<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Database;
use PDO;

class OrderController extends Controller {
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

    public function index(): void {
        $stmt = $this->db->query("
            SELECT o.*, u.email as customer_email 
            FROM orders o 
            LEFT JOIN users u ON u.id = o.user_id 
            ORDER BY o.created_at DESC
        ");
        $orders = $stmt->fetchAll();

        foreach ($orders as $index => $order) {
            $itemStmt = $this->db->prepare("
                SELECT oi.*, p.name as product_name, p.slug 
                FROM order_items oi 
                JOIN products p ON p.id = oi.product_id 
                WHERE oi.order_id = :order_id
            ");
            $itemStmt->execute(['order_id' => $order['id']]);
            $orders[$index]['items'] = $itemStmt->fetchAll();
        }

        $this->render('admin/orders', [
            'orders' => $orders,
            'success' => Session::get('flash_success'),
            'error'   => Session::get('flash_error')
        ]);
        Session::remove('flash_success');
        Session::remove('flash_error');
    }

    public function updateStatus(): void {
        if (!$this->request->validateCsrf()) {
            Session::set('flash_error', 'CSRF verification failed.');
            header('Location: /admin/orders');
            exit();
        }

        $input = $this->request->getBody();
        $orderId = isset($input['order_id']) ? (int)$input['order_id'] : null;
        $shippingStatus = $input['shipping_status'] ?? null;
        $paymentStatus = $input['payment_status'] ?? null;

        if ($orderId !== null) {
            if (in_array($shippingStatus, ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'], true)) {
                $stmt = $this->db->prepare("UPDATE orders SET shipping_status = :status WHERE id = :id");
                $stmt->execute(['status' => $shippingStatus, 'id' => $orderId]);
            }
            if (in_array($paymentStatus, ['Pending', 'Paid', 'Failed', 'Refunded'], true)) {
                $stmt = $this->db->prepare("UPDATE orders SET payment_status = :status WHERE id = :id");
                $stmt->execute(['status' => $paymentStatus, 'id' => $orderId]);
            }
            Session::set('flash_success', 'Order status parameters updated successfully.');
        }

        header('Location: /admin/orders');
        exit();
    }
}
