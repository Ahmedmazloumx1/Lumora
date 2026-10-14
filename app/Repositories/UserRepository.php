<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

class UserRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Find active user record by email
     */
    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email AND deleted_at IS NULL LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        return $user ? $user : null;
    }

    /**
     * Create new user record and return insert ID
     */
    public function create(array $data): int {
        // Match default role as Customer (role_id = 2)
        $stmt = $this->db->prepare("
            INSERT INTO users (role_id, name, email, password, contact, city, address, status) 
            VALUES (:role_id, :name, :email, :password, :contact, :city, :address, 'Active')
        ");

        $stmt->execute([
            'role_id'  => $data['role_id'] ?? 2,
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'],
            'contact'  => $data['contact'] ?? null,
            'city'     => $data['city'] ?? null,
            'address'  => $data['address'] ?? null
        ]);

        return (int)$this->db->lastInsertId();
    }
}
