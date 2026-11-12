<?php
declare(strict_types=1);

namespace VetVida\Security;

final class SessionManager {
    public static function start(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function requireAuth(): void {
        self::start();

        if (!self::isAuthenticated()) {
            header('Location: /login');
            exit;
        }

        if (self::isExpired()) {
            self::destroy();
            header('Location: /login?expirada=1');
            exit;
        }

        $_SESSION['ultimo_acesso'] = time();
    }

    public static function isAuthenticated(): bool {
        return !empty($_SESSION['usuario_id']);
    }

    public static function isExpired(): bool {
        $configFile = dirname(__DIR__, 2) . '/config/database.ini';
        $config = file_exists($configFile) ? parse_ini_file($configFile, true) : [];
        $timeout = (int)($config['security']['session_timeout'] ?? 900);
        $lastAccess = (int)($_SESSION['ultimo_acesso'] ?? time());
        return (time() - $lastAccess) > $timeout;
    }

    public static function login(array $user): void {
        self::start();
        session_regenerate_id(true);
        $_SESSION['usuario_id']     = (int)$user['id'];
        $_SESSION['usuario_nome']   = $user['nome'];
        $_SESSION['usuario_email']  = $user['email'];
        $_SESSION['usuario_perfil'] = $user['perfil'];
        $_SESSION['ultimo_acesso']  = time();
    }

    public static function destroy(): void {
        self::start();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
    }

    public static function flash(string $type, string $message): void {
        self::start();
        $_SESSION["flash_{$type}"] = $message;
    }

    public static function getFlash(string $type): ?string {
        self::start();
        $key = "flash_{$type}";
        if (isset($_SESSION[$key])) {
            $msg = (string)$_SESSION[$key];
            unset($_SESSION[$key]);
            return $msg;
        }
        return null;
    }
}
