<?php
declare(strict_types=1);

namespace App\Core;

class Session {
    /**
     * Start session with strict cookie security parameters [1.1.2]
     */
    public static function start(): void {
        if (session_status() === PHP_SESSION_NONE) {
            // Configure session cookie variables securely [1.1.2]
            $secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
            
            $session_options = [
                'cookie_lifetime' => 0,
                'cookie_path'     => '/',
                'cookie_domain'   => '',
                'cookie_secure'   => $secure, // Transmit cookies via HTTPS only when active [1.1.2]
                'cookie_httponly' => true,    // Prevent client-side XSS javascript extraction [1.1.2]
                'cookie_samesite' => 'Lax'     // Mitigate Cross-Site Request Forgery (CSRF) vectors [1.1.2]
            ];

            ini_set('session.use_strict_mode', '1');
            session_start($session_options);
        }

        // Generate dynamic CSRF security token if missing
        if (!self::get('csrf_token')) {
            self::set('csrf_token', bin2hex(random_bytes(32)));
        }
    }

    public static function set(string $key, mixed $value): void {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key): mixed {
        return $_SESSION[$key] ?? null;
    }

    public static function remove(string $key): void {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function destroy(): void {
        session_unset();
        session_destroy();
    }
}
