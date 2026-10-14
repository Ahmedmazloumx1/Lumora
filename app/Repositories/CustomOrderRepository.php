<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

class CustomOrderRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findByUserId(int $userId): array {
        $stmt = $this->db->prepare("SELECT * FROM custom_orders WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function create(array $data): bool {
        $stmt = $this->db->prepare("
            INSERT INTO custom_orders (user_id, brand, model, style, size, custom_text, image, status) 
            VALUES (:user_id, :brand, :model, :style, :size, :custom_text, :image, 'Pending')
        ");
        return $stmt->execute([
            'user_id'     => $data['user_id'],
            'brand'       => $data['brand'],
            'model'       => $data['model'],
            'style'       => $data['style'],
            'size'        => $data['size'],
            'custom_text' => $data['custom_text'] ?: null,
            'image'       => $data['image'] ?: null
        ]);
    }

    public function all(): array {
        $stmt = $this->db->query("
            SELECT co.*, u.name as customer_name, u.email, u.contact 
            FROM custom_orders co 
            JOIN users u ON u.id = co.user_id 
            ORDER BY co.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function updateStatus(int $id, string $status): bool {
        $stmt = $this->db->prepare("UPDATE custom_orders SET status = :status WHERE id = :id");
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }
}