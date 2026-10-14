<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Services\CartService;
use App\Services\OrderService;
use App\Repositories\UserRepository;

class CheckoutController extends Controller {
    private Request $request;
    private CartService $cartService;
    private OrderService $orderService;
    private UserRepository $userRepo;

    public function __construct() {
        $this->request = new Request();
        $this->cartService = new CartService();
        $this->orderService = new OrderService();
        $this->userRepo = new UserRepository();
    }

    /**
     * Render secure checkout dashboard
     */
    public function index(): void {
        $userId = Session::get('user_id') ? (int)Session::get('user_id') : null;

        // Force login before checkout
        if (!$userId) {
            Session::set('flash_error', 'Please log in to finalize your checkout.');
            header('Location: /login');
            exit();
        }

        $cart = $this->cartService->getActiveCart($userId, null);
        $items = $this->cartService->getItems((int)$cart['id']);

        if (count($items) === 0) {
            header('Location: /products');
            exit();
        }

        $userProfile = $this->userRepo->findByEmail(Session::get('user_email'));

        $this->render('checkout/index', [
            'items' => $items,
            'user' => $userProfile,
            'error' => Session::get('flash_error')
        ]);
        Session::remove('flash_error');
    }

    /**
     * Handle order submission form
     */
    public function placeOrder(): void {
        $userId = Session::get('user_id') ? (int)Session::get('user_id') : null;

        if (!$userId) {
            header('Location: /login');
            exit();
        }

        if (!$this->request->validateCsrf()) {
            Session::set('flash_error', 'Verification of secure security token failed.');
            header('Location: /checkout');
            exit();
        }

        $input = $this->request->getBody();

        // Server-Side Egyptian Phone validation [1.1.2]
        if (!preg_match("/^01[0125][0-9]{8}$/", $input['shipping_phone'] ?? '')) {
            Session::set('flash_error', 'Please enter a valid Egyptian mobile number (11 digits starting with 010, 011, 012, or 015).');
            header('Location: /checkout');
            exit();
        }

        $cart = $this->cartService->getActiveCart($userId, null);
        $items = $this->cartService->getItems((int)$cart['id']);

        if (count($items) === 0) {
            header('Location: /products');
            exit();
        }

        $shippingData = [
            'name'    => $input['shipping_name'] ?? '',
            'phone'   => $input['shipping_phone'] ?? '',
            'city'    => $input['shipping_city'] ?? '',
            'address' => $input['shipping_address'] ?? ''
        ];

        if (trim($shippingData['name']) === '' || trim($shippingData['city']) === '' || trim($shippingData['address']) === '') {
            Session::set('flash_error', 'Complete all shipping address fields.');
            header('Location: /checkout');
            exit();
        }

        try {
            $orderNumber = $this->orderService->createOrder($userId, $shippingData, $items);
            Session::set('last_order_number', $orderNumber);
            header('Location: /checkout/success');
            exit();
        } catch (\Exception $e) {
            Session::set('flash_error', 'Checkout processing failed. Please try again.');
            header('Location: /checkout');
            exit();
        }
    }

    /**
     * Render success thank you view
     */
    public function success(): void {
        $orderNumber = Session::get('last_order_number');

        if (!$orderNumber) {
            header('Location: /products');
            exit();
        }

        $this->render('checkout/success', [
            'order_number' => $orderNumber
        ]);
        Session::remove('last_order_number');
    }
}
