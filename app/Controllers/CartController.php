<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Services\CartService;

class CartController extends Controller {
    private Request $request;
    private CartService $cartService;

    public function __construct() {
        $this->request = new Request();
        $this->cartService = new CartService();
    }

    /**
     * Render shopping cart view
     */
    public function index(): void {
        $userId = Session::get('user_id') ? (int)Session::get('user_id') : null;
        $sessionId = $userId === null ? session_id() : null;

        $cart = $this->cartService->getActiveCart($userId, $sessionId);
        $items = $this->cartService->getItems((int)$cart['id']);

        $this->render('cart/index', [
            'items' => $items
        ]);
    }

    /**
     * Add item route handler
     */
    public function add(): void {
        $input = $this->request->getBody();
        $productId = isset($input['product_id']) ? (int)$input['product_id'] : null;
        $variantId = !empty($input['variant_id']) ? (int)$input['variant_id'] : null;
        $quantity = isset($input['quantity']) ? (int)$input['quantity'] : 1;

        if (!$productId) {
            header('Location: /products');
            exit();
        }

        $userId = Session::get('user_id') ? (int)Session::get('user_id') : null;
        $sessionId = $userId === null ? session_id() : null;

        $this->cartService->addToCart($userId, $sessionId, $productId, $variantId, $quantity);

        header('Location: /cart');
        exit();
    }

    /**
     * AJAX Quantity Updater API (RESTful)
     */
    public function update(): void {
        if (!$this->request->validateCsrf()) {
            $this->json(['error' => 'CSRF verification failed.'], 403);
        }

        $input = $this->request->getBody();
        $itemId = isset($input['item_id']) ? (int)$input['item_id'] : null;
        $quantity = isset($input['quantity']) ? (int)$input['quantity'] : null;

        if ($itemId === null || $quantity === null) {
            $this->json(['error' => 'Missing parameter options.'], 400);
        }

        $this->cartService->updateItem($itemId, $quantity);

        if (str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'text/html')) {
            header('Location: /cart');
            exit();
        }
        $this->json(['success' => true]);
    }

    /**
     * Remove item route handler
     */
    public function remove(): void {
        $input = $this->request->getBody();
        $itemId = isset($input['item_id']) ? (int)$input['item_id'] : null;

        if ($itemId !== null) {
            $this->cartService->removeItem($itemId);
        }

        header('Location: /cart');
        exit();
    }
}
