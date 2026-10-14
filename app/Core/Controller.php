<?php
declare(strict_types=1);

namespace App\Core;

class Controller {
    /**
     * Render the View file with extraction payload
     */
    public function render(string $view, array $data = []): void {
        // Extract array keys as local variables
        extract($data);

        $viewFile = APP_PATH . "/Views/{$view}.php";
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            // Check for lowercase fallback compatibility on Linux [1.1.1]
            $lowercaseViewFile = APP_PATH . "/views/" . strtolower($view) . ".php";
            if (file_exists($lowercaseViewFile)) {
                include $lowercaseViewFile;
            } else {
                throw new \Exception("View template '{$view}' not found on disk.");
            }
        }
    }

    /**
     * Standardize API responses using JSON
     */
    public function json(array $data, int $statusCode = 200): void {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);
        echo json_encode($data);
        exit();
    }
}