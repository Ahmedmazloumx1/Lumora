<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Services\AuthService;

class AuthController extends Controller {
    private Request $request;
    private AuthService $authService;

    public function __construct() {
        $this->request = new Request();
        $this->authService = new AuthService();
    }

    public function showLogin(): void {
        if (Session::get('user_id')) {
            header('Location: /');
            exit();
        }
        $this->render('auth/login', [
            'error' => Session::get('flash_error'),
            'success' => Session::get('flash_success')
        ]);
        Session::remove('flash_error');
        Session::remove('flash_success');
    }

    public function login(): void {
        if (!$this->request->validateCsrf()) {
            Session::set('flash_error', 'Invalid CSRF security token. Form verification failed.');
            header('Location: /login');
            exit();
        }

        $input = $this->request->getBody();
        $result = $this->authService->authenticate($input['email'] ?? '', $input['password'] ?? '');

        if (!$result['success']) {
            Session::set('flash_error', $result['error']);
            header('Location: /login');
            exit();
        }

        // Preserve the guest cart key before rotating the authenticated session.
        $guestSessionId = session_id();
        session_regenerate_id(true);
        Session::set('csrf_token', bin2hex(random_bytes(32)));
        // Set secure login sessions
        Session::set('user_id', $result['user']['id']);
        Session::set('user_name', $result['user']['name']);
        Session::set('user_email', $result['user']['email']);
        Session::set('user_role', $result['user']['role_id']);

         // SYNC CART SYSTEM: Merge guest choices into database [1.1.1]
    $cartService = new \App\Services\CartService();
    $cartService->syncGuestCartToUser($guestSessionId, (int)$result['user']['id']);
        
        header('Location: /');
        exit();
    }

    public function showRegister(): void {
        if (Session::get('user_id')) {
            header('Location: /');
            exit();
        }
        $this->render('auth/register', ['error' => Session::get('flash_error')]);
        Session::remove('flash_error');
    }

    public function register(): void {
        if (!$this->request->validateCsrf()) {
            Session::set('flash_error', 'Invalid security token verification.');
            header('Location: /register');
            exit();
        }

        $input = $this->request->getBody();

        // Server-Side Input Validation
        if (strlen($input['password'] ?? '') < 8) {
            Session::set('flash_error', 'Password must be at least 8 characters long.');
            header('Location: /register');
            exit();
        }

        $result = $this->authService->register($input);

        if (!$result['success']) {
            Session::set('flash_error', $result['error']);
            header('Location: /register');
            exit();
        }

        $guestSessionId = session_id();
        session_regenerate_id(true);
        Session::set('csrf_token', bin2hex(random_bytes(32)));
        // Auto-login registered account immediately
        Session::set('user_id', $result['user']['id']);
        Session::set('user_name', $result['user']['name']);
        Session::set('user_email', $result['user']['email']);
        Session::set('user_role', $result['user']['role_id']);

         // SYNC CART SYSTEM: Merge guest choices into database [1.1.1]
    $cartService = new \App\Services\CartService();
    $cartService->syncGuestCartToUser($guestSessionId, (int)$result['user']['id']);
        
        Session::set('flash_success', 'Welcome! Your account has been registered successfully.');
        header('Location: /');
        exit();
    }

    public function logout(): void {
        Session::destroy();
        header('Location: /');
        exit();
    }
}
