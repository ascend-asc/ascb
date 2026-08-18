<?php

class RateLimiter {
    private $directory;

    public function __construct($namespace = 'ascb') {
        $this->directory = rtrim(sys_get_temp_dir(), '/\\')
            . DIRECTORY_SEPARATOR . preg_replace('/[^a-z0-9_-]/i', '', $namespace) . '-rate-limits';
        if (!is_dir($this->directory)) {
            @mkdir($this->directory, 0700, true);
        }
    }

    public function tooManyAttempts($key, $maximumAttempts, $windowSeconds) {
        return count($this->readRecent($key, $windowSeconds)) >= $maximumAttempts;
    }

    public function hit($key, $windowSeconds) {
        $attempts = $this->readRecent($key, $windowSeconds);
        $attempts[] = time();
        $this->write($key, $attempts);
    }

    public function clear($key) {
        $path = $this->path($key);
        if (is_file($path)) {
            @unlink($path);
        }
    }

    private function readRecent($key, $windowSeconds) {
        $path = $this->path($key);
        if (!is_file($path)) {
            return [];
        }
        $handle = @fopen($path, 'rb');
        if (!$handle) {
            return [];
        }
        flock($handle, LOCK_SH);
        $contents = stream_get_contents($handle);
        flock($handle, LOCK_UN);
        fclose($handle);

        $attempts = json_decode($contents ?: '[]', true);
        if (!is_array($attempts)) {
            return [];
        }
        $cutoff = time() - $windowSeconds;
        return array_values(array_filter($attempts, static function ($timestamp) use ($cutoff) {
            return is_int($timestamp) && $timestamp >= $cutoff;
        }));
    }

    private function write($key, array $attempts) {
        if (!is_dir($this->directory) || !is_writable($this->directory)) {
            return;
        }
        $path = $this->path($key);
        $handle = @fopen($path, 'c+b');
        if (!$handle) {
            return;
        }
        flock($handle, LOCK_EX);
        ftruncate($handle, 0);
        fwrite($handle, json_encode($attempts));
        fflush($handle);
        flock($handle, LOCK_UN);
        fclose($handle);
        @chmod($path, 0600);
    }

    private function path($key) {
        return $this->directory . DIRECTORY_SEPARATOR . hash('sha256', (string) $key) . '.json';
    }
}
