<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRepository;
use App\Core\Session;

class AuthService {
    private UserRepository $userRepo;
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCK_TIME_SECONDS = 300; // 5 minute cooldown lock

    public function __construct() {
        $this->userRepo = new UserRepository();
    }

    /**
     * Validate credentials and login user with brute force checks [1.1.1]
     */
    public function authenticate(string $email, string $password): array {
        if ($this->isLockedOut($email)) {
            return ['success' => false, 'error' => 'Too many login failures. Please try again in 5 minutes.'];
        }

        $user = $this->userRepo->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $this->incrementAttempts($email);
            return ['success' => false, 'error' => 'Incorrect email address or password.'];
        }

        if ($user['status'] === 'Suspended') {
            return ['success' => false, 'error' => 'Your account is suspended. Contact administration.'];
        }

        // Authentication Successful: Reset login attempts
        $this->resetAttempts($email);

        return ['success' => true, 'user' => $user];
    }

    /**
     * Hash user password securely and register account
     */
    public function register(array $data): array {
        if (!is_string($data['email'] ?? null) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)
            || !is_string($data['name'] ?? null) || trim($data['name']) === ''
            || !is_string($data['password'] ?? null) || strlen($data['password']) < 8
            || !preg_match('/^01[0125][0-9]{8}$/', $data['contact'] ?? '')) {
            return ['success' => false, 'error' => 'Enter a name, valid email, Egyptian mobile number, and password of at least 8 characters.'];
        }
        if ($this->userRepo->findByEmail($data['email'])) {
            return ['success' => false, 'error' => 'This email is already registered in our database.'];
        }

        // Hash using strong Bcrypt algorithm
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['role_id'] = 2; // Default Customer role

        $userId = $this->userRepo->create($data);

        return [
            'success' => true,
            'user' => [
                'id' => $userId,
                'name' => $data['name'],
                'email' => $data['email'],
                'role_id' => 2
            ]
        ];
    }

    private function isLockedOut(string $email): bool {
        $attempts = Session::get("login_attempts_{$email}") ?? 0;
        $lockTime = Session::get("login_lock_{$email}") ?? 0;

        if ($attempts >= self::MAX_LOGIN_ATTEMPTS) {
            if (time() < $lockTime) {
                return true;
            }
            // Cooldown lock expired: Reset attempts
            $this->resetAttempts($email);
        }
        return false;
    }

    private function incrementAttempts(string $email): void {
        $attempts = (Session::get("login_attempts_{$email}") ?? 0) + 1;
        Session::set("login_attempts_{$email}", $attempts);

        if ($attempts >= self::MAX_LOGIN_ATTEMPTS) {
            Session::set("login_lock_{$email}", time() + self::LOCK_TIME_SECONDS);
        }
    }

    private function resetAttempts(string $email): void {
        Session::remove("login_attempts_{$email}");
        Session::remove("login_lock_{$email}");
    }
}
