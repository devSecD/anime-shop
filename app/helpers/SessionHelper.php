<?php
namespace App\Helpers;

class SessionHelper
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            // Seguridad antes del session_start
            ini_set('session.use_strict_mode', 1);
            ini_set('session.cookie_httponly', 1);
            ini_set('session.cookie_secure', self::isHttps() ? 1 : 0);

            session_set_cookie_params([
                'httponly' => true, 
                'secure' => self::isHttps(), 
                'samesite' => 'Strict'
            ]);

            session_start();
        }
    }

    public static function regenerate(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    public static function delete(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            session_unset();
            session_destroy();
        }
    }

    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function getUser(): array|null
    {
        return $_SESSION['user'] ?? null;
    }

    public static function isHttps(): bool
    {
        return ( 
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS' === 'off']) || 
            $_SERVER['SERVER_PORT'] === 443
        );
    }
}