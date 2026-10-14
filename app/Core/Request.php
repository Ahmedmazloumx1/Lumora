<?php
declare(strict_types=1);

namespace App\Core;

class Request {
    /**
     * Fetch active HTTP method verb
     */
    public function getMethod(): string {
        return strtolower($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    /**
     * Get path URI requested (Cleans internal index.php rewrites)
     */
    public function getPath(): string {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        
        // Clean leading/trailing slashes and index.php from route matching
        $path = preg_replace('#^/index\.php(?:/|$)#', '/', $path);
        $path = rtrim($path, '/');
        if (empty($path)) {
            $path = '/';
        }
        
        return $path;
    }

    /**
     * Extract sanitized input parameters safely
     */
    public function getBody(): array {
        // Preserve passwords and FAQ arrays. Escape text when rendering HTML.
        $body = $this->getMethod() === 'post' ? $_POST : $_GET;
        foreach ($body as $key => $value) {
            if (is_array($value)) {
                if (!in_array($key, ['faq_questions', 'faq_answers'], true)
                    || count(array_filter($value, 'is_string')) !== count($value)) {
                    throw new \DomainException('Invalid input format.');
                }
            }
        }
        return $body;
    }

    /**
     * Verify authenticity of inbound CSRF tokens
     */
    public function validateCsrf(): bool {
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        $expected = Session::get('csrf_token');
        return is_string($expected) && $expected !== '' && is_string($token)
            && hash_equals($expected, $token);
    }
}
