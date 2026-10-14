<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

class WishlistRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getWishlistItems(int $userId): array {
        $stmt = $this->db->prepare("
            SELECT w.id as wish_id, p.id as product_id, p.name, p.slug, p.price,
                   (SELECT image_path FROM product_gallery pg WHERE pg.product_id = p.id ORDER BY pg.display_order ASC LIMIT 1) as main_image
            FROM wishlists w
            JOIN products p ON p.id = w.product_id
            WHERE w.user_id = :user_id AND p.status = 'Active' AND p.deleted_at IS NULL
            ORDER BY w.created_at DESC
        ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function isWishlisted(int $userId, int $productId): bool {
        $stmt = $this->db->prepare("SELECT id FROM wishlists WHERE user_id = :user_id AND product_id = :product_id LIMIT 1");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
        return (bool)$stmt->fetch();
    }

    public function toggle(int $userId, int $productId): bool {
        $product = $this->db->prepare("SELECT id FROM products WHERE id = :id AND status = 'Active' AND deleted_at IS NULL");
        $product->execute(['id' => $productId]);
        if (!$product->fetchColumn()) {
            throw new \DomainException('Product is unavailable.');
        }
        if ($this->isWishlisted($userId, $productId)) {
            $stmt = $this->db->prepare("DELETE FROM wishlists WHERE user_id = :user_id AND product_id = :product_id");
            $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
            return false; // Removed
        } else {
            $stmt = $this->db->prepare("INSERT INTO wishlists (user_id, product_id) VALUES (:user_id, :product_id)");
            $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
            return true; // Added
        }
    }
}
