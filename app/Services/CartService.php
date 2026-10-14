<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\CartRepository;
use App\Core\Session;

class CartService {
    private CartRepository $cartRepo;

    public function __construct() {
        $this->cartRepo = new CartRepository();
    }

    /**
     * Fetch or generate active cart
     */
    public function getActiveCart(?int $userId, ?string $sessionId): array {
        $cart = null;
        if ($userId !== null) {
            $cart = $this->cartRepo->findByUserId($userId);
        } elseif ($sessionId !== null) {
            $cart = $this->cartRepo->findBySessionId($sessionId);
        }

        if (!$cart) {
            $cartId = $this->cartRepo->create($userId, $sessionId);
            return ['id' => $cartId, 'user_id' => $userId, 'session_id' => $sessionId];
        }

        return $cart;
    }

    /**
     * Add product/variant to active cart safely
     */
    public function addToCart(?int $userId, ?string $sessionId, int $productId, ?int $variantId, int $quantity = 1): void {
        $this->cartRepo->validateProduct($productId, $variantId, $quantity);
        $cart = $this->getActiveCart($userId, $sessionId);
        $existingItem = $this->cartRepo->findItemInCart((int)$cart['id'], $productId, $variantId);

        if ($existingItem) {
            $newQuantity = (int)$existingItem['quantity'] + $quantity;
            $this->cartRepo->validateProduct($productId, $variantId, $newQuantity);
            $this->cartRepo->updateItemQuantity((int)$existingItem['id'], $newQuantity);
        } else {
            $this->cartRepo->insertItem((int)$cart['id'], $productId, $variantId, $quantity);
        }
    }

    /**
     * Synchronize guest session cart to user account on login [1.1.1]
     */
    public function syncGuestCartToUser(string $sessionId, int $userId): void {
        $guestCart = $this->cartRepo->findBySessionId($sessionId);
        if (!$guestCart) {
            return; // No active guest cart found
        }

        $userCart = $this->getActiveCart($userId, null);
        $guestItems = $this->cartRepo->getCartItems((int)$guestCart['id']);

        foreach ($guestItems as $item) {
            $variantId = $item['variant_id'] ? (int)$item['variant_id'] : null;
            $existingUserItem = $this->cartRepo->findItemInCart((int)$userCart['id'], (int)$item['product_id'], $variantId);

            if ($existingUserItem) {
                // Merge quantities
                $newQty = (int)$existingUserItem['quantity'] + (int)$item['quantity'];
                $this->cartRepo->updateItemQuantity((int)$existingUserItem['id'], $newQty);
            } else {
                // Transfer item
                $this->cartRepo->insertItem((int)$userCart['id'], (int)$item['product_id'], $variantId, (int)$item['quantity']);
            }
        }

        // Delete guest cart safely
        $this->cartRepo->deleteCart((int)$guestCart['id']);
    }

    public function getItems(int $cartId): array {
        return $this->cartRepo->getCartItems($cartId);
    }

    public function updateItem(int $itemId, int $quantity): void {
        $cartId = $this->assertOwnership($itemId);
        if ($quantity > 0) {
            foreach ($this->getItems($cartId) as $item) {
                if ((int)$item['item_id'] === $itemId) {
                    $this->cartRepo->validateProduct((int)$item['product_id'], $item['variant_id'] !== null ? (int)$item['variant_id'] : null, $quantity);
                    break;
                }
            }
        }
        if ($quantity <= 0) {
            $this->cartRepo->removeItem($itemId);
        } else {
            $this->cartRepo->updateItemQuantity($itemId, $quantity);
        }
    }

    public function removeItem(int $itemId): void {
        $this->assertOwnership($itemId);
        $this->cartRepo->removeItem($itemId);
    }

    private function assertOwnership(int $itemId): int {
        $userId = Session::get('user_id') ? (int)Session::get('user_id') : null;
        $cart = $this->getActiveCart($userId, $userId === null ? session_id() : null);
        if (!$this->cartRepo->belongsToCart($itemId, (int)$cart['id'])) {
            throw new \DomainException('Cart item not found.');
        }
        return (int)$cart['id'];
    }
}
