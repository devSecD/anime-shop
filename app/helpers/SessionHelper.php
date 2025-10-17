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

            // configura las cookies de la sesion
            session_set_cookie_params([
                'httponly' => true, 
                'secure' => self::isHttps(), 
                'samesite' => 'Strict'
            ]);

            session_start();
        }
    }

    /**
     * Regenera el identificador actual de sesión para prevenir ataques de fijación de sesión.
     *
     * Este método verifica que la sesión esté activa antes de ejecutar `session_regenerate_id(true)`,
     * lo cual crea un nuevo ID de sesión y elimina el anterior de forma segura.
     * 
     * Regenerar el ID de sesión es una práctica recomendada después de eventos sensibles
     * como el inicio de sesión o el cambio de privilegios de usuario, ya que impide que
     * un atacante reutilice un ID de sesión previamente capturado.
     *
     * @return void No devuelve ningún valor.
     */
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

    /**
     * Destruye la sesión actual de manera segura.
     *
     * Este método verifica que la sesión esté activa antes de proceder a eliminarla.
     * Realiza los siguientes pasos:
     * 1. Vacía el arreglo `$_SESSION`.
     * 2. Llama a `session_unset()` para liberar todas las variables de sesión.
     * 3. Llama a `session_destroy()` para finalizar la sesión en el servidor.
     *
     * Es útil para implementar un cierre de sesión seguro, asegurando que
     * ningún dato de la sesión anterior quede accesible.
     *
     * @return void No devuelve ningún valor.
     */
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

    /**
     * Determina si la conexión actual se realiza mediante HTTPS.
     *
     * Este método verifica múltiples indicadores para detectar correctamente
     * si la conexión es segura, incluyendo servidores detrás de proxies o
     * balanceadores de carga que usan encabezados como `X-Forwarded-Proto`.
     *
     * Orden de verificación:
     * 1. Variable `$_SERVER['HTTPS']` (activa y distinta de 'off').
     * 2. Puerto del servidor (`$_SERVER['SERVER_PORT'] === 443`).
     * 3. Encabezado `X-Forwarded-Proto` enviado por proxies.
     *
     * @return bool Retorna true si la conexión es HTTPS, false en caso contrario.
     */
    public static function isHttps(): bool
    {
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            return true;
        }

        // el puerto 443 se usa para el https
        if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) {
            return true;
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') {
            return true;
        }

        return false;
    }

    public static function setUserSession(array $userData): void
    {
        $_SESSION['user'] = $userData;
    }

    public static function hasRole(string $role): bool
    {
        return isset($_SESSION['user']['roles']) && in_array($role, $_SESSION['user']['roles']);
    }

}