<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Database;
use PDO;

class DashboardController extends Controller {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();

        // Enforce strict Role-Based Access Control (RBAC) [1.1.1]
        $roleId = Session::get('user_role') ? (int)Session::get('user_role') : null;
        if ($roleId !== 1) { // 1 = Admin role
            header('Location: /login');
            exit();
        }
    }

    /**
     * Compile real-time business performance analytics reports
     */
    public function index(): void {
        // Retrieve core counts
        $productCount = $this->db->query("SELECT COUNT(id) FROM products WHERE deleted_at IS NULL")->fetchColumn();
        $userCount = $this->db->query("SELECT COUNT(id) FROM users WHERE role_id = 2 AND deleted_at IS NULL")->fetchColumn();
        $orderCount = $this->db->query("SELECT COUNT(id) FROM orders WHERE shipping_status != 'Cancelled'")->fetchColumn();

        // 1. Dynamic Gross Revenue (EGP)
        $revenue = $this->db->query("SELECT SUM(grand_total) FROM orders WHERE payment_status = 'Paid'")->fetchColumn() ?: 0.00;

        // 2. Average Order Value (AOV)
        $aov = ($orderCount > 0) ? ($revenue / $orderCount) : 0.00;

        // 3. Active unconfirmed carts currently in-funnel
        $activeCarts = $this->db->query("SELECT COUNT(DISTINCT user_id) FROM carts WHERE updated_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)")->fetchColumn() ?: 0;

        // Sourcing top 5 selling items for Chart.js visualization
        $chartLabels = [];
        $chartData = [];
        $stmt = $this->db->query("
            SELECT p.name, SUM(oi.quantity) as sales_qty 
            FROM order_items oi 
            JOIN products p ON p.id = oi.product_id 
            GROUP BY oi.product_id, p.name
            ORDER BY sales_qty DESC 
            LIMIT 5
        ");
        while ($row = $stmt->fetch()) {
            $chartLabels[] = $row['name'];
            $chartData[] = (int)$row['sales_qty'];
        }

        $this->render('admin/dashboard', [
            'product_count' => $productCount,
            'user_count'    => $userCount,
            'order_count'   => $orderCount,
            'revenue'       => (float)$revenue,
            'aov'           => (float)$aov,
            'active_carts'  => (int)$activeCarts,
            'chart_labels'  => $chartLabels,
            'chart_data'    => $chartData
        ]);
    }
}
