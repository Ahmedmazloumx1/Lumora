<?php
declare(strict_types=1);

namespace App\Services;

class UploadService {
    private const ALLOWED_MIME_TYPES = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp'
    ];
    private const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5 Megabytes limit

    /**
     * Validate and process secure image upload
     */
    public function uploadImage(array $file, string $targetDir): ?string {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        // 1. Enforce strict size check to prevent server exploitation
        if ($file['size'] > self::MAX_FILE_SIZE) {
            throw new \Exception("File exceeds maximum size limits of 5MB.");
        }

        // 2. Enforce strict MIME checking by inspecting raw file bytes
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!array_key_exists($mimeType, self::ALLOWED_MIME_TYPES)) {
            throw new \Exception("Invalid file format. Only JPG, PNG, and WebP are allowed.");
        }

        $extension = self::ALLOWED_MIME_TYPES[$mimeType];

        // 3. Prevent directory traversal and randomize filenames
        $fileName = bin2hex(random_bytes(16)) . '.' . $extension;
        $targetFile = ROOT_PATH . '/' . rtrim($targetDir, '/') . '/' . $fileName;

        if (!is_dir(ROOT_PATH . '/' . $targetDir)) {
            mkdir(ROOT_PATH . '/' . $targetDir, 0755, true);
        }

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return $fileName;
        }

        throw new \RuntimeException('The image could not be saved. Please try again.');
    }
}
