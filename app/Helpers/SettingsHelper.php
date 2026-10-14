<?php
declare(strict_types=1);

namespace App\Helpers;

use App\Core\Database;
use PDO;

class SettingsHelper {
    private static array $cache = [];
    private static bool $loaded = false;

    /**
     * Retrieve setting value by key with safe fallback defaults
     */
    public static function get(string $key, ?string $default = null): ?string {
        if (!self::$loaded) {
            self::$loaded = true;
            self::loadAll();
        }
        return self::$cache[$key] ?? $default;
    }

    /**
     * Cache all database configurations in memory to prevent duplicate query scans
     */
    private static function loadAll(): void {
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT `key`, `value` FROM settings");
            while ($row = $stmt->fetch()) {
                self::$cache[$row['key']] = $row['value'];
            }
        } catch (\Exception $e) {
            // Fallback during initial schema setup
            self::$cache = [];
        }
    }
}
