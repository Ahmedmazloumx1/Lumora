<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Database;
use PDO;

class AccountController extends Controller {
    private Request $request;
    private PDO $db;

    public function __construct() {
        $this->request = new Request();
        $this->db = Database::getConnection();

        // Enforce user authentication
        if (!Session::get('user_id')) {
            header('Location: /login');
            exit();
        }
    }

    /**
     * Render central client dashboard panel
     */
    public function index(): void {
        $userId = (int)Session::get('user_id');

        // Fetch user profile variables
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch();

        // Fetch full order history
        $orderStmt = $this->db->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC");
        $orderStmt->execute(['user_id' => $userId]);
        $orders = $orderStmt->fetchAll();

        // Fetch itemized product listings for each order [1.1.1]
        foreach ($orders as $index => $order) {
            $itemStmt = $this->db->prepare("
                SELECT oi.*, p.name as product_name, p.slug,
                       (SELECT image_path FROM product_gallery pg WHERE pg.product_id = p.id ORDER BY pg.display_order ASC LIMIT 1) as main_image
                FROM order_items oi
                JOIN products p ON p.id = oi.product_id
                WHERE oi.order_id = :order_id
            ");
            $itemStmt->execute(['order_id' => $order['id']]);
            $orders[$index]['items'] = $itemStmt->fetchAll();
        }

        $this->render('account/index', [
            'user' => $user,
            'orders' => $orders,
            'success' => Session::get('flash_success'),
            'error' => Session::get('flash_error')
        ]);
        Session::remove('flash_success');
        Session::remove('flash_error');
    }

    /**
     * Handle default shipping profile updates
     */
    public function updateProfile(): void {
        if (!$this->request->validateCsrf()) {
            Session::set('flash_error', 'CSRF verification failed.');
            header('Location: /account');
            exit();
        }

        $userId = (int)Session::get('user_id');
        $input = $this->request->getBody();

        // Server-side Egyptian phone validation [1.1.2]
        if (!preg_match("/^01[0125][0-9]{8}$/", $input['contact'] ?? '')) {
            Session::set('flash_error', 'Invalid phone number format. Please enter an active 11-digit Egyptian mobile number.');
            header('Location: /account');
            exit();
        }

        $stmt = $this->db->prepare("
            UPDATE users 
            SET name = :name, contact = :contact, city = :city, address = :address 
            WHERE id = :id
        ");

        $stmt->execute([
            'name'    => $input['name'] ?? '',
            'contact' => $input['contact'] ?? '',
            'city'    => $input['city'] ?? '',
            'address' => $input['address'] ?? '',
            'id'      => $userId
        ]);

        Session::set('user_name', $input['name']); // Update session header greeting
        Session::set('flash_success', 'Shipping profile information updated successfully.');
        header('Location: /account');
        exit();
    }

    /**
     * Handle secure password rotation using Bcrypt hashing [1.1.1]
     */
    public function updatePassword(): void {
        if (!$this->request->validateCsrf()) {
            Session::set('flash_error', 'CSRF token validation failed.');
            header('Location: /account');
            exit();
        }

        $userId = (int)Session::get('user_id');
        $input = $this->request->getBody();

        $oldPassword = $input['current_password'] ?? '';
        $newPassword = $input['new_password'] ?? '';
        $confirmPassword = $input['confirm_password'] ?? '';

        if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
            Session::set('flash_error', 'All password fields are required.');
            header('Location: /account');
            exit();
        }

        if ($newPassword !== $confirmPassword) {
            Session::set('flash_error', 'New password and confirmation fields do not match.');
            header('Location: /account');
            exit();
        }

        if (strlen($newPassword) < 8) {
            Session::set('flash_error', 'New password must be at least 8 characters long.');
            header('Location: /account');
            exit();
        }

        // Retrieve current hash from database
        $stmt = $this->db->prepare("SELECT password FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $userId]);
        $currentHash = $stmt->fetchColumn();

        if (!password_verify($oldPassword, $currentHash)) {
            Session::set('flash_error', 'Current password entered is incorrect.');
            header('Location: /account');
            exit();
        }

        // Generate strong secure password hash [1.1.1]
        $newHash = password_hash($newPassword, PASSWORD_BCRYPT);

        $updateStmt = $this->db->prepare("UPDATE users SET password = :password WHERE id = :id");
        $updateStmt->execute(['password' => $newHash, 'id' => $userId]);

        Session::set('flash_success', 'Password updated successfully.');
        header('Location: /account');
        exit();
    }
}