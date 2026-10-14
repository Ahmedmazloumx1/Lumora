<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

class ProductRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Retrieve catalog products with filters
     */
    public function all(array $filters = []): array {
        $sql = "SELECT p.*, c.name as category_name,
                (SELECT image_path FROM product_gallery pg WHERE pg.product_id = p.id ORDER BY pg.display_order, pg.id LIMIT 1) as main_image
                FROM products p 
                JOIN categories c ON c.id = p.category_id 
                WHERE p.status = 'Active' AND p.deleted_at IS NULL";

        $params = [];

        if (!empty($filters['category'])) {
            $sql .= " AND c.slug = :category";
            $params['category'] = $filters['category'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (p.name LIKE :search_name OR p.description LIKE :search_desc)";
            $params['search_name'] = "%{$filters['search']}%";
            $params['search_desc'] = "%{$filters['search']}%";
        }

        $sql .= " ORDER BY p.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Find product with full variants and gallery details
     */
    public function findBySlug(string $slug): ?array {
        $stmt = $this->db->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON c.id = p.category_id WHERE p.slug = :slug AND p.status = 'Active' AND p.deleted_at IS NULL LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        $product = $stmt->fetch();

        if (!$product) {
            return null;
        }

        // Fetch gallery images
        $galleryStmt = $this->db->prepare("SELECT image_path FROM product_gallery WHERE product_id = :product_id ORDER BY display_order ASC");
        $galleryStmt->execute(['product_id' => $product['id']]);
        $product['gallery'] = $galleryStmt->fetchAll();
        $product['main_image'] = $product['gallery'][0]['image_path'] ?? null;

        // Fetch variants and their option attributes
        $variantsStmt = $this->db->prepare("
            SELECT pv.id as variant_id, pv.sku, pv.price, pv.stock_qty, 
                   group_concat(pav.value separator ' / ') as attribute_values
            FROM product_variants pv
            LEFT JOIN product_variant_options pvo ON pvo.variant_id = pv.id
            LEFT JOIN product_attribute_values pav ON pav.id = pvo.attribute_value_id
            WHERE pv.product_id = :product_id
            GROUP BY pv.id, pv.sku, pv.price, pv.stock_qty
        ");
        $variantsStmt->execute(['product_id' => $product['id']]);
        $product['variants'] = $variantsStmt->fetchAll();

        return $product;
    }
    // Add these functions inside app/Repositories/ProductRepository.php

/**
 * Retrieve related products in the same category, excluding the active one [1.1.1]
 */
public function getRelatedProducts(int $productId, int $categoryId, int $limit = 4): array {
    $stmt = $this->db->prepare("
        SELECT p.*,
               (SELECT image_path FROM product_gallery pg WHERE pg.product_id = p.id ORDER BY pg.display_order ASC LIMIT 1) as main_image
        FROM products p
        WHERE p.category_id = :category_id 
          AND p.id != :product_id 
          AND p.status = 'Active' 
          AND p.deleted_at IS NULL
        ORDER BY RAND()
        LIMIT :limit
    ");
    
    $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
    $stmt->bindValue(':product_id', $productId, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Query AJAX Search Autocomplete Suggestions
 */
public function searchSuggestions(string $query, int $limit = 5): array {
    $stmt = $this->db->prepare("
        SELECT name, slug, price 
        FROM products 
        WHERE status = 'Active' 
          AND deleted_at IS NULL 
          AND name LIKE :query 
        LIMIT :limit
    ");
    $stmt->bindValue(':query', "%{$query}%", PDO::PARAM_STR);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}
}
