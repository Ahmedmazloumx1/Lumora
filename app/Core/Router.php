<?php
declare(strict_types=1);

namespace App\Core;

class Router {
    protected array $routes = [];
    protected Request $request;

    public function __construct() {
        $this->request = new Request();
    }

    public function get(string $path, string $callback): void {
        $this->routes['get'][$path] = $callback;
    }

    public function post(string $path, string $callback): void {
        $this->routes['post'][$path] = $callback;
    }

    /**
     * Dispatch routing requests to controllers
     */
    public function dispatch(): void {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $callback = $this->routes[$method][$path] ?? null;

        if ($callback !== null && $method === 'post' && !$this->request->validateCsrf()) {
            http_response_code(403);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => 'CSRF verification failed. Reload the form and try again.']);
            return;
        }

        $privatePaths = ['/account', '/checkout', '/custom-order', '/wishlist'];
        foreach ($privatePaths as $privatePath) {
            if (($path === $privatePath || str_starts_with($path, $privatePath . '/')) && !Session::get('user_id')) {
                header('Location: /login');
                return;
            }
        }
        if (($path === '/admin' || str_starts_with($path, '/admin/')) && (int)Session::get('user_role') !== 1) {
            header('Location: /login');
            return;
        }

        if ($callback === null) {
            http_response_code(404);
            $this->renderError(404);
            return;
        }

        // Handle standard Controller@action callback structure
        if (is_string($callback)) {
            $parts = explode('@', $callback);
            if (count($parts) === 2) {
                $controllerName = $parts[0];
                $actionName = $parts[1];

                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    if (method_exists($controller, $actionName)) {
                        call_user_func([$controller, $actionName]);
                        return;
                    }
                }
            }
        }

        http_response_code(500);
        $this->renderError(500);
    }

    protected function renderError(int $code): void {
        $viewFile = APP_PATH . "/Views/errors/{$code}.php";
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "<h2>Error {$code}</h2>";
            echo "<p>Requested resource not found.</p>";
        }
    }
}
