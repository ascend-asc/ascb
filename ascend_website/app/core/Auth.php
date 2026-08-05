<?php
class Auth {
    public static function isLoggedIn() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['admin_user_id']);
    }

    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: ' . URLROOT . '/admin/login.php');
            exit();
        }
    }

    public static function login($user) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_regenerate_id(true);
        $_SESSION['admin_user_id'] = $user->id;
        $_SESSION['admin_email'] = $user->email;
        $_SESSION['admin_full_name'] = $user->full_name;
        $_SESSION['admin_role'] = $user->role;
    }

    public static function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header('Location: ' . URLROOT . '/admin/login.php');
        exit();
    }
}
