<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

class CartRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Find active cart by user ID
     */
    public function findByUserId(int $userId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM carts WHERE user_id = :user_id LIMIT 1");
        $stmt->execute(['user_id' => $userId]);
        $cart = $stmt->fetch();
        return $cart ? $cart : null;
    }

    /**
     * Find active guest cart by session ID
     */
    public function findBySessionId(string $sessionId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM carts WHERE session_id = :session_id LIMIT 1");
        $stmt->execute(['session_id' => $sessionId]);
        $cart = $stmt->fetch();
        return $cart ? $cart : null;
    }

    /**
     * Create a new cart entry
     */
    public function create(?int $userId, ?string $sessionId): int {
        $stmt = $this->db->prepare("INSERT INTO carts (user_id, session_id) VALUES (:user_id, :session_id)");
        $stmt->execute([
            'user_id' => $userId,
            'session_id' => $sessionId
        ]);
        return (int)$this->db->lastInsertId();
    }

    /**
     * Retrieve complete cart items joined with variants and main images
     */
    public function getCartItems(int $cartId): array {
        $stmt = $this->db->prepare("
            SELECT ci.id as item_id, ci.product_id, ci.variant_id, ci.quantity, 
                   p.name, p.slug, 
                   COALESCE(pv.price, p.price) as unit_price, 
                   COALESCE(pv.sku, p.sku) as sku,
                   (SELECT image_path FROM product_gallery pg WHERE pg.product_id = p.id ORDER BY pg.display_order ASC LIMIT 1) as main_image,
                   (SELECT GROUP_CONCAT(pav.value ORDER BY pav.id SEPARATOR ' / ')
                    FROM product_variant_options pvo
                    JOIN product_attribute_values pav ON pav.id = pvo.attribute_value_id
                    WHERE pvo.variant_id = ci.variant_id) as variant_details
            FROM cart_items ci
            JOIN products p ON p.id = ci.product_id
            LEFT JOIN product_variants pv ON pv.id = ci.variant_id
            WHERE ci.cart_id = :cart_id
        ");
        $stmt->execute(['cart_id' => $cartId]);
        return $stmt->fetchAll();
    }

    /**
     * Check if item/variant already exists inside a cart
     */
    public function findItemInCart(int $cartId, int $productId, ?int $variantId): ?array {
        $sql = "SELECT * FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id";
        $params = ['cart_id' => $cartId, 'product_id' => $productId];

        if ($variantId !== null) {
            $sql .= " AND variant_id = :variant_id";
            $params['variant_id'] = $variantId;
        } else {
            $sql .= " AND variant_id IS NULL";
        }
        $sql .= " LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $item = $stmt->fetch();
        return $item ? $item : null;
    }

    public function insertItem(int $cartId, int $productId, ?int $variantId, int $quantity): void {
        $stmt = $this->db->prepare("INSERT INTO cart_items (cart_id, product_id, variant_id, quantity) VALUES (:cart_id, :product_id, :variant_id, :quantity)");
        $stmt->execute([
            'cart_id' => $cartId,
            'product_id' => $productId,
            'variant_id' => $variantId,
            'quantity' => $quantity
        ]);
    }

    public function updateItemQuantity(int $itemId, int $quantity): void {
        $stmt = $this->db->prepare("UPDATE cart_items SET quantity = :quantity WHERE id = :id");
        $stmt->execute(['quantity' => $quantity, 'id' => $itemId]);
    }

    public function belongsToCart(int $itemId, int $cartId): bool {
        $stmt = $this->db->prepare('SELECT id FROM cart_items WHERE id = :id AND cart_id = :cart_id');
        $stmt->execute(['id' => $itemId, 'cart_id' => $cartId]);
        return (bool)$stmt->fetchColumn();
    }

    public function validateProduct(int $productId, ?int $variantId, int $quantity): void {
        $stmt = $this->db->prepare("SELECT stock_qty FROM products WHERE id = :id AND status = 'Active' AND deleted_at IS NULL");
        $stmt->execute(['id' => $productId]);
        $stock = $stmt->fetchColumn();
        if ($stock === false) {
            throw new \DomainException('Product is unavailable.');
        }
        if ($variantId !== null) {
            $stmt = $this->db->prepare('SELECT stock_qty FROM product_variants WHERE id = :id AND product_id = :product_id');
            $stmt->execute(['id' => $variantId, 'product_id' => $productId]);
            $stock = $stmt->fetchColumn();
            if ($stock === false) {
                throw new \DomainException('This variant does not belong to the selected product.');
            }
        }
        if ($quantity < 1 || $quantity > 999 || $quantity > (int)$stock) {
            throw new \DomainException('Requested quantity is unavailable.');
        }
    }

    public function removeItem(int $itemId): void {
        $stmt = $this->db->prepare("DELETE FROM cart_items WHERE id = :id");
        $stmt->execute(['id' => $itemId]);
    }

    public function deleteCart(int $cartId): void {
        $stmt = $this->db->prepare("DELETE FROM carts WHERE id = :id");
        $stmt->execute(['id' => $cartId]);
    }
}
