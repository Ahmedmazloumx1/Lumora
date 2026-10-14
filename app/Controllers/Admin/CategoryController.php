<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Database;
use PDO;

class CategoryController extends Controller {
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
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY display_order ASC");
        $categories = $stmt->fetchAll();

        $this->render('admin/categories', [
            'categories' => $categories,
            'success' => Session::get('flash_success'),
            'error'   => Session::get('flash_error')
        ]);
        Session::remove('flash_success');
        Session::remove('flash_error');
    }

    public function add(): void {
        if (!$this->request->validateCsrf()) {
            Session::set('flash_error', 'CSRF verification failed.');
            header('Location: /admin/categories');
            exit();
        }

        $input = $this->request->getBody();
        $name = $input['category_name'] ?? '';

        if (empty($name)) {
            Session::set('flash_error', 'Category name is required.');
            header('Location: /admin/categories');
            exit();
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        if ($slug === '') {
            $slug = 'category-' . bin2hex(random_bytes(4));
        }
        $existing = $this->db->prepare('SELECT id FROM categories WHERE slug = :slug');
        $existing->execute(['slug' => $slug]);
        if ($existing->fetchColumn()) {
            Session::set('flash_error', 'A category with this name already exists.');
            header('Location: /admin/categories');
            exit();
        }

        $stmt = $this->db->prepare("INSERT INTO categories (name, slug) VALUES (:name, :slug)");
        $stmt->execute(['name' => $name, 'slug' => $slug]);

        Session::set('flash_success', 'New product category registered.');
        header('Location: /admin/categories');
        exit();
    }

    public function delete(): void {
        if (!$this->request->validateCsrf()) {
            Session::set('flash_error', 'CSRF verification failed.');
            header('Location: /admin/categories');
            exit();
        }

        $input = $this->request->getBody();
        $id = isset($input['category_id']) ? (int)$input['category_id'] : null;

        if ($id && $id !== 1) {
            // Re-assign items back to Wall Art (ID=1) before deletion
            $this->db->prepare("UPDATE products SET category_id = 1 WHERE category_id = :id")->execute(['id' => $id]);
            $this->db->prepare("DELETE FROM categories WHERE id = :id")->execute(['id' => $id]);
            Session::set('flash_success', 'Category successfully deleted from catalog.');
        }

        header('Location: /admin/categories');
        exit();
    }
}
