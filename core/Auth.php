<?php
// core/Auth.php

class Auth {
    public static function check() {
        return isset($_SESSION['user_id']);
    }

    public static function user() {
        if (self::check()) {
            return [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'],
                'role' => $_SESSION['role']
            ];
        }
        return null;
    }

    public static function id() {
        return $_SESSION['user_id'] ?? null;
    }

    public static function isAdmin() {
        return self::check() && $_SESSION['role'] === 'admin';
    }

    public static function requireLogin() {
        if (!self::check()) {
            header("Location: /index.php?page=login");
            die();
        }
    }

    public static function requireAdmin() {
        self::requireLogin();
        if (!self::isAdmin()) {
            http_response_code(403);
            http_response_code(403);
            require_once __DIR__ . '/../views/errors/403.php';
            exit;
        }
    }
}
