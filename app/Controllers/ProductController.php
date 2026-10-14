<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\WishlistRepository;

class ProductController extends Controller {
    private Request $request;
    private ProductRepository $productRepo;
    private CategoryRepository $categoryRepo;
    private WishlistRepository $wishlistRepo;

    public function __construct() {
        $this->request = new Request();
        $this->productRepo = new ProductRepository();
        $this->categoryRepo = new CategoryRepository();
        $this->wishlistRepo = new WishlistRepository();
    }

    /**
     * Render store catalog with search/category filters and user wishlists
     */
    public function index(): void {
        $filters = $this->request->getBody();
        $products = $this->productRepo->all($filters);
        $categories = $this->categoryRepo->all();

        // Safe evaluation of authenticated user wishlists [1.1.2]
        $wishlistItems = [];
        $userId = Session::get('user_id');
        if ($userId !== null) {
            try {
                $saved = $this->wishlistRepo->getWishlistItems((int)$userId);
                foreach ($saved as $item) {
                    $wishlistItems[] = (int)$item['product_id'];
                }
            } catch (\Exception $e) {
                $wishlistItems = [];
            }
        }

        $this->render('catalog/index', [
            'products' => $products,
            'categories' => $categories,
            'active_category' => $filters['category'] ?? null,
            'search_keyword' => $filters['search'] ?? '', // Map search keywords safely to prevent null warnings
            'wishlist_items' => $wishlistItems // Map wishlist arrays safely to prevent in_array type crashes
        ]);
    }

    /**
     * Render single product detail with variations and related products [1.1.1]
     */
    public function show(): void {
        $params = $this->request->getBody();
        $slug = $params['slug'] ?? '';

        if (empty($slug)) {
            header('Location: /products');
            exit();
        }

        $product = $this->productRepo->findBySlug($slug);

        if (!$product) {
            http_response_code(404);
            $this->render('errors/404');
            return;
        }

        // Get related products dynamically [1.1.1]
        $relatedProducts = $this->productRepo->getRelatedProducts((int)$product['id'], (int)$product['category_id'], 4);

        $this->render('catalog/show', [
            'product' => $product,
            'related_products' => $relatedProducts
        ]);
    }

    /**
     * RESTful AJAX Search Autocomplete Suggestions API [1.1.1]
     */
    public function searchSuggestions(): void {
        $params = $this->request->getBody();
        $query = $params['query'] ?? '';

        if (empty($query) || strlen($query) < 2) {
            $this->json([]);
        }

        $suggestions = $this->productRepo->searchSuggestions($query, 5);
        $this->json($suggestions);
    }
}