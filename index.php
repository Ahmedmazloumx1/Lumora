<?php
declare(strict_types=1);

try {
    require __DIR__ . '/bootstrap.php';
    \App\Core\Session::start();
// Initialize the routing table
$router = new \App\Core\Router();

// Register Active Front-End Routes
$router->get('', 'App\Controllers\HomeController@index');
$router->get('/', 'App\Controllers\HomeController@index');

// Secure Auth Routes
$router->get('/login', 'App\Controllers\AuthController@showLogin');
$router->post('/login', 'App\Controllers\AuthController@login');
$router->get('/register', 'App\Controllers\AuthController@showRegister');
$router->post('/register', 'App\Controllers\AuthController@register');
$router->post('/logout', 'App\Controllers\AuthController@logout');

// Public Catalog Routes
$router->get('/products', 'App\Controllers\ProductController@index');
$router->get('/product', 'App\Controllers\ProductController@show');

// Shopping Basket Routes
$router->get('/cart', 'App\Controllers\CartController@index');
$router->post('/cart/add', 'App\Controllers\CartController@add');
$router->post('/cart/update', 'App\Controllers\CartController@update');
$router->post('/cart/remove', 'App\Controllers\CartController@remove');

// Secure Checkout Routes
$router->get('/checkout', 'App\Controllers\CheckoutController@index');
$router->post('/checkout', 'App\Controllers\CheckoutController@placeOrder');
$router->get('/checkout/success', 'App\Controllers\CheckoutController@success');

// Bespoke Custom Orders Routes
$router->get('/custom-order', 'App\Controllers\CustomOrderController@index');
$router->post('/custom-order', 'App\Controllers\CustomOrderController@submit');
$router->get('/custom-order/status', 'App\Controllers\CustomOrderController@status');

// Admin Custom Order Reviews
$router->get('/admin/custom-orders', 'App\Controllers\Admin\CustomOrderController@index');
$router->post('/admin/custom-orders/update', 'App\Controllers\Admin\CustomOrderController@updateStatus');

// Advanced Shopping Experience Routes
$router->get('/api/search-suggestions', 'App\Controllers\ProductController@searchSuggestions');
$router->get('/wishlist', 'App\Controllers\WishlistController@index');
$router->post('/wishlist/toggle', 'App\Controllers\WishlistController@toggle');

// Executive Admin Dashboard Routes
$router->get('/admin', 'App\Controllers\Admin\DashboardController@index');
$router->get('/admin/products', 'App\Controllers\Admin\ProductController@index');
$router->post('/admin/products/bulk-edit', 'App\Controllers\Admin\ProductController@bulkEdit');
$router->post('/admin/products/duplicate', 'App\Controllers\Admin\ProductController@duplicate');
$router->post('/admin/products/delete', 'App\Controllers\Admin\ProductController@delete');

// Dynamic Web Content CMS Routes
$router->get('/about', 'App\Controllers\PageController@about');
$router->get('/faq', 'App\Controllers\PageController@faq');
$router->get('/policies', 'App\Controllers\PageController@policies');
$router->get('/admin/content', 'App\Controllers\Admin\ContentController@index');
$router->post('/admin/content/update', 'App\Controllers\Admin\ContentController@update');

// Consolidated Client Center Dashboard Routes
$router->get('/account', 'App\Controllers\AccountController@index');
$router->post('/account/profile', 'App\Controllers\AccountController@updateProfile');
$router->post('/account/password', 'App\Controllers\AccountController@updatePassword');

// Re-wired Customer, Order & Collections Management Routes [1.1.1]
$router->get('/admin/users', 'App\Controllers\Admin\UserController@index');
$router->post('/admin/users/delete', 'App\Controllers\Admin\UserController@delete');
$router->get('/admin/orders', 'App\Controllers\Admin\OrderController@index');
$router->post('/admin/orders/update', 'App\Controllers\Admin\OrderController@updateStatus');
$router->get('/admin/categories', 'App\Controllers\Admin\CategoryController@index');
$router->post('/admin/categories/add', 'App\Controllers\Admin\CategoryController@add');
$router->post('/admin/categories/delete', 'App\Controllers\Admin\CategoryController@delete');


    $router->dispatch();
} catch (\DomainException $error) {
    http_response_code(422);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => $error->getMessage()]);
} catch (\Throwable $error) {
    error_log((string)$error);
    http_response_code(500);
    if (isset($config) && $config['app']['environment'] === 'development') {
        echo '<h1>Server error</h1><pre>' . htmlspecialchars($error->getMessage(), ENT_QUOTES, 'UTF-8') . '</pre>';
    } else {
        require __DIR__ . '/app/Views/errors/500.php';
    }
}
