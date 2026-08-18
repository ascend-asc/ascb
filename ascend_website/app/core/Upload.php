<?php

class Upload {
    private const IMAGE_MIMES = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];

    public static function image(array $file, $destinationDirectory, $prefix, $maxBytes = 5242880) {
        return self::store($file, $destinationDirectory, $prefix, self::IMAGE_MIMES, $maxBytes, true);
    }

    public static function pdf(array $file, $destinationDirectory, $prefix, $maxBytes = 10485760) {
        return self::store($file, $destinationDirectory, $prefix, ['application/pdf' => 'pdf'], $maxBytes, false);
    }

    private static function store(array $file, $destinationDirectory, $prefix, array $allowedMimes, $maxBytes, $mustBeImage) {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            throw new RuntimeException('The upload did not complete successfully.');
        }
        if (!isset($file['tmp_name'], $file['size']) || !is_uploaded_file($file['tmp_name'])) {
            throw new RuntimeException('Invalid upload source.');
        }
        if ((int) $file['size'] <= 0 || (int) $file['size'] > $maxBytes) {
            throw new RuntimeException('The uploaded file exceeds the allowed size.');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if (!isset($allowedMimes[$mime])) {
            throw new RuntimeException('The uploaded file type is not allowed.');
        }
        if ($mustBeImage && @getimagesize($file['tmp_name']) === false) {
            throw new RuntimeException('The uploaded file is not a valid image.');
        }
        if (!is_dir($destinationDirectory) && !mkdir($destinationDirectory, 0755, true)) {
            throw new RuntimeException('The upload directory is unavailable.');
        }

        $filename = preg_replace('/[^a-z0-9_-]/i', '', $prefix)
            . bin2hex(random_bytes(16)) . '.' . $allowedMimes[$mime];
        $destination = rtrim($destinationDirectory, '/\\') . DIRECTORY_SEPARATOR . $filename;
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new RuntimeException('Unable to store the uploaded file.');
        }
        chmod($destination, 0644);
        return $filename;
    }

    public static function deletePublicFile($relativePath, $publicRoot, $requiredPrefix = 'uploads/') {
        $relativePath = str_replace('\\', '/', (string) $relativePath);
        if (strpos($relativePath, $requiredPrefix) !== 0 || strpos($relativePath, '..') !== false) {
            return false;
        }

        $root = realpath($publicRoot);
        $target = realpath(rtrim($publicRoot, '/\\') . DIRECTORY_SEPARATOR . $relativePath);
        if ($root === false || $target === false || !is_file($target)) {
            return false;
        }
        if (strpos($target, rtrim($root, '/\\') . DIRECTORY_SEPARATOR) !== 0) {
            return false;
        }
        return unlink($target);
    }
}
