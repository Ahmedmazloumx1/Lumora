<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Database;
use PDO;

class UserController extends Controller {
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
        $stmt = $this->db->query("SELECT * FROM users WHERE role_id = 2 AND deleted_at IS NULL ORDER BY created_at DESC");
        $users = $stmt->fetchAll();

        $this->render('admin/users', [
            'users' => $users,
            'success' => Session::get('flash_success'),
            'error'   => Session::get('flash_error')
        ]);
        Session::remove('flash_success');
        Session::remove('flash_error');
    }

    public function delete(): void {
        if (!$this->request->validateCsrf()) {
            Session::set('flash_error', 'CSRF verification failed.');
            header('Location: /admin/users');
            exit();
        }

        $input = $this->request->getBody();
        $id = isset($input['user_id']) ? (int)$input['user_id'] : null;

        if ($id) {
            $stmt = $this->db->prepare("UPDATE users SET deleted_at = NOW() WHERE id = :id");
            $stmt->execute(['id' => $id]);
            Session::set('flash_success', 'User profile soft-deleted successfully.');
        }

        header('Location: /admin/users');
        exit();
    }
}