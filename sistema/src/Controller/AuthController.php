<?php
declare(strict_types=1);

namespace VetVida\Controller;

use VetVida\Repository\UsuarioRepository;
use VetVida\Security\SessionManager;

final class AuthController extends BaseController {
    private UsuarioRepository $usuarios;

    public function __construct() {
        $this->usuarios = new UsuarioRepository();
    }

    public function showLogin(): void {
        SessionManager::start();

        if (SessionManager::isAuthenticated()) {
            $this->redirect('/');
        }

        $notice = null;
        if (isset($_GET['expirada'])) {
            $notice = 'Sua sessão expirou por inatividade. Faça login novamente para continuar.';
        } elseif (isset($_GET['logout'])) {
            $notice = 'Sua sessão foi encerrada com sucesso.';
        }

        $this->render('auth/login', [
            'titulo'    => 'Entrar — SisVet',
            'erro'      => null,
            'aviso'     => $notice,
            'csrfToken' => $this->getCsrfToken()
        ]);
    }

    public function authenticate(): void {
        if (!$this->validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->render('auth/login', [
                'titulo'    => 'Entrar — SisVet',
                'erro'      => 'Sessão expirada. Tente enviar novamente.',
                'csrfToken' => $this->getCsrfToken()
            ]);
            return;
        }

        $email = trim((string)($_POST['email'] ?? ''));
        $senha = (string)($_POST['senha'] ?? '');

        if ($email === '' || $senha === '') {
            $this->render('auth/login', [
                'titulo'    => 'Entrar — SisVet',
                'erro'      => 'Informe e-mail e senha.',
                'csrfToken' => $this->getCsrfToken()
            ]);
            return;
        }

        $user = $this->usuarios->findByEmail($email);

        if ($user && password_verify($senha, $user['senha_hash'])) {
            SessionManager::login($user);
            $this->redirect('/');
            return;
        }

        $this->render('auth/login', [
            'titulo'    => 'Entrar — SisVet',
            'erro'      => 'E-mail ou senha incorretos.',
            'csrfToken' => $this->getCsrfToken()
        ]);
    }

    public function logout(): void {
        SessionManager::destroy();
        $this->redirect('/login?logout=1');
    }
}
