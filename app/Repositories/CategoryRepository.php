<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

class CategoryRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Retrieve all active categories
     */
    public function all(): array {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY display_order ASC");
        return $stmt->fetchAll();
    }

    /**
     * Find category by unique URL slug
     */
    public function findBySlug(string $slug): ?array {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE slug = :slug LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        $category = $stmt->fetch();
        return $category ? $category : null;
    }
}