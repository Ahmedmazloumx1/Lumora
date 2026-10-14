<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;

class OrderService {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Map shipping rates by localized Egyptian Governorate tiers [1.1.2]
     */
    public function getShippingCost(string $city): float {
        $city = strtolower(trim($city));
        
        $tier1 = ['cairo', 'giza'];
        $tier2 = [
            'alexandria', 'qalyubia', 'sharqia', 'dakahlia', 'gharbia', 
            'monufia', 'beheira', 'kafr el sheikh', 'damietta', 'port said', 
            'ismailia', 'suez'
        ];

        if (in_array($city, $tier1, true)) {
            return 50.00;
        }
        if (in_array($city, $tier2, true)) {
            return 70.00;
        }
        return 90.00; // Frontier & Upper Egypt Governorates
    }

    /**
     * Process checkout transaction and clear cart
     */
    public function createOrder(int $userId, array $shippingData, array $cartItems): string {
        $this->db->beginTransaction();

        try {
            // Serialize checkout for this cart and re-read prices from the database.
            $cartLock = $this->db->prepare('SELECT id FROM carts WHERE user_id = :user_id FOR UPDATE');
            $cartLock->execute(['user_id' => $userId]);
            $cartId = $cartLock->fetchColumn();
            if ($cartId === false) {
                throw new \DomainException('Your cart is empty.');
            }
            $cartItems = (new \App\Repositories\CartRepository())->getCartItems((int)$cartId);
            if (!$cartItems) {
                throw new \DomainException('Your cart is empty.');
            }
            usort($cartItems, static fn(array $a, array $b): int => $a['product_id'] <=> $b['product_id']);
            foreach ($cartItems as &$item) {
                $productLock = $this->db->prepare("SELECT price, stock_qty FROM products WHERE id = :id AND status = 'Active' AND deleted_at IS NULL FOR UPDATE");
                $productLock->execute(['id' => $item['product_id']]);
                $product = $productLock->fetch();
                if (!$product) {
                    throw new \DomainException('A product in your cart is unavailable.');
                }
                $table = 'products';
                $stockId = $item['product_id'];
                if ($item['variant_id'] !== null) {
                    $variantLock = $this->db->prepare('SELECT price, stock_qty FROM product_variants WHERE id = :id AND product_id = :product_id FOR UPDATE');
                    $variantLock->execute(['id' => $item['variant_id'], 'product_id' => $item['product_id']]);
                    $product = $variantLock->fetch();
                    if (!$product) {
                        throw new \DomainException('A selected variation is unavailable.');
                    }
                    $table = 'product_variants';
                    $stockId = $item['variant_id'];
                }
                $quantity = (int)$item['quantity'];
                if ($quantity < 1 || $quantity > (int)$product['stock_qty']) {
                    throw new \DomainException('A product in your cart has insufficient stock.');
                }
                $item['unit_price'] = $product['price'];
                $stockUpdate = $this->db->prepare("UPDATE {$table} SET stock_qty = stock_qty - :quantity WHERE id = :id");
                $stockUpdate->execute(['quantity' => $quantity, 'id' => $stockId]);
            }
            unset($item);
            $subtotal = 0.00;
            foreach ($cartItems as $item) {
                $subtotal += (float)$item['unit_price'] * (int)$item['quantity'];
            }

            $shippingAmount = $this->getShippingCost($shippingData['city']);
            $grandTotal = $subtotal + $shippingAmount;
            
            // Generate unique Order Number
            $orderNumber = 'LMR-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));

            // 1. Insert order record
            $stmt = $this->db->prepare("
                INSERT INTO orders (user_id, order_number, subtotal, shipping_amount, grand_total, payment_method, shipping_name, shipping_phone, shipping_city, shipping_address, payment_status, shipping_status) 
                VALUES (:user_id, :order_number, :subtotal, :shipping_amount, :grand_total, 'COD', :shipping_name, :shipping_phone, :shipping_city, :shipping_address, 'Pending', 'Pending')
            ");

            $stmt->execute([
                'user_id'          => $userId,
                'order_number'     => $orderNumber,
                'subtotal'         => $subtotal,
                'shipping_amount'  => $shippingAmount,
                'grand_total'      => $grandTotal,
                'shipping_name'    => $shippingData['name'],
                'shipping_phone'   => $shippingData['phone'],
                'shipping_city'    => $shippingData['city'],
                'shipping_address' => $shippingData['address']
            ]);

            $orderId = (int)$this->db->lastInsertId();

            // 2. Insert line items
            $itemStmt = $this->db->prepare("
                INSERT INTO order_items (order_id, product_id, variant_id, price, quantity) 
                VALUES (:order_id, :product_id, :variant_id, :price, :quantity)
            ");

            foreach ($cartItems as $item) {
                $itemStmt->execute([
                    'order_id'   => $orderId,
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'] ?: null,
                    'price'      => $item['unit_price'],
                    'quantity'   => $item['quantity']
                ]);
            }

            // 3. Clear user's cart
            $clearStmt = $this->db->prepare("
                DELETE ci FROM cart_items ci 
                JOIN carts c ON c.id = ci.cart_id 
                WHERE c.user_id = :user_id
            ");
            $clearStmt->execute(['user_id' => $userId]);

            $this->db->commit();
            return $orderNumber;

        } catch (\Throwable $e) {
            $this->db->rollBack();
            error_log("Checkout Order Creation Failed: " . $e->getMessage());
            throw new \Exception("Failed to finalize order transaction.");
        }
    }
}
