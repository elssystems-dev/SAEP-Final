<?php
declare(strict_types=1);

namespace VetVida\Controller;

use VetVida\Security\SessionManager;
use RuntimeException;

abstract class BaseController {
    protected function render(string $viewPath, array $data = []): void {
        SessionManager::start();
        extract($data, EXTR_SKIP);

        $viewFile = dirname(__DIR__) . "/Views/{$viewPath}.php";
        if (!file_exists($viewFile)) {
            throw new RuntimeException("Arquivo de view nao encontrado: {$viewFile}");
        }

        require_once dirname(__DIR__) . "/Views/layout/header.php";
        require_once $viewFile;
        require_once dirname(__DIR__) . "/Views/layout/footer.php";
    }

    protected function redirect(string $path): void {
        header("Location: {$path}");
        exit;
    }

    protected function getCsrfToken(): string {
        SessionManager::start();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return (string)$_SESSION['csrf_token'];
    }

    protected function validateCsrfToken(?string $token): bool {
        SessionManager::start();
        $sessionToken = $_SESSION['csrf_token'] ?? '';
        return !empty($token) && hash_equals((string)$sessionToken, (string)$token);
    }
}
