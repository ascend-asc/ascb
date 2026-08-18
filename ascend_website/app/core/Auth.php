<?php
class Auth {
    private static function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function isLoggedIn() {
        self::startSession();
        return isset($_SESSION['admin_user_id']);
    }

    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: ' . URLROOT . '/admin/login.php');
            exit();
        }
        header('Cache-Control: no-store, private');
    }

    public static function login($user) {
        self::startSession();
        session_regenerate_id(true);
        unset($_SESSION['csrf_token'], $_SESSION['login_failures'], $_SESSION['login_locked_until']);
        $_SESSION['admin_user_id'] = $user->id;
        $_SESSION['admin_email'] = $user->email;
        $_SESSION['admin_full_name'] = $user->full_name;
        $_SESSION['admin_role'] = $user->role;
    }

    public static function logout() {
        self::startSession();
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', [
                'expires' => time() - 42000,
                'path' => $params['path'],
                'domain' => $params['domain'],
                'secure' => $params['secure'],
                'httponly' => $params['httponly'],
                'samesite' => $params['samesite'] ?? 'Lax',
            ]);
        }

        session_destroy();
        header('Location: ' . URLROOT . '/admin/login.php');
        exit();
    }

    public static function currentUserId() {
        self::startSession();
        return isset($_SESSION['admin_user_id']) ? (int) $_SESSION['admin_user_id'] : null;
    }

    public static function hasRole($roles) {
        self::startSession();
        $roles = (array) $roles;
        return isset($_SESSION['admin_role']) && in_array($_SESSION['admin_role'], $roles, true);
    }

    public static function requireRole($roles) {
        self::requireLogin();
        if (!self::hasRole($roles)) {
            http_response_code(403);
            exit('Forbidden');
        }
    }

    public static function updateCurrentUser($fullName, $email, $role = null) {
        self::startSession();
        $_SESSION['admin_full_name'] = $fullName;
        $_SESSION['admin_email'] = $email;
        if ($role !== null) {
            $_SESSION['admin_role'] = $role;
        }
    }
}
