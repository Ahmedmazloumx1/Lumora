<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Repositories\WishlistRepository;

class WishlistController extends Controller {
    private Request $request;
    private WishlistRepository $wishlistRepo;

    public function __construct() {
        $this->request = new Request();
        $this->wishlistRepo = new WishlistRepository();
        
        // Force authentication before wishlist access
        if (!Session::get('user_id')) {
            header('Location: /login');
            exit();
        }
    }

    public function index(): void {
        $userId = (int)Session::get('user_id');
        $items = $this->wishlistRepo->getWishlistItems($userId);

        $this->render('catalog/wishlist', [
            'items' => $items
        ]);
    }

    /**
     * AJAX Toggle API (RESTful) [1.1.2]
     */
    public function toggle(): void {
        if (!$this->request->validateCsrf()) {
            $this->json(['error' => 'CSRF verification failed.'], 403);
        }

        $input = $this->request->getBody();
        $productId = isset($input['product_id']) ? (int)$input['product_id'] : null;

        if ($productId === null) {
            $this->json(['error' => 'Missing product identifier.'], 400);
        }

        $userId = (int)Session::get('user_id');
        $status = $this->wishlistRepo->toggle($userId, $productId);

        $this->json([
            'success' => true,
            'is_wishlisted' => $status
        ]);
    }
}