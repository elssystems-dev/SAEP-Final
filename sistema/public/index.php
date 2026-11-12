<?php
declare(strict_types=1);

// Ponto unico de entrada da aplicacao (Front Controller)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . '/vendor/autoload.php';

use VetVida\Core\Router;
use VetVida\Security\SessionManager;
use VetVida\Controller\AuthController;
use VetVida\Controller\DashboardController;
use VetVida\Controller\TutorController;
use VetVida\Controller\PetController;
use VetVida\Controller\AgendamentoController;

$router = new Router();
$authGuard = [[SessionManager::class, 'requireAuth']];

// 1. Autenticacao e Logout
$router->add('GET', '/login', [AuthController::class, 'showLogin']);
$router->add('POST', '/login', [AuthController::class, 'authenticate']);
$router->add('POST', '/logout', [AuthController::class, 'logout'], $authGuard);

// 2. Modulo Principal / Dashboard
$router->add('GET', '/', [DashboardController::class, 'index'], $authGuard);

// 3. Gestao de Tutores (Busca, Cadastro, Edicao, Exclusao e Listagem)
$router->add('GET', '/tutores', [TutorController::class, 'list'], $authGuard);
$router->add('GET', '/tutores/novo', [TutorController::class, 'createForm'], $authGuard);
$router->add('POST', '/tutores/salvar', [TutorController::class, 'save'], $authGuard);
$router->add('GET', '/tutores/editar', [TutorController::class, 'editForm'], $authGuard);
$router->add('POST', '/tutores/atualizar', [TutorController::class, 'update'], $authGuard);
$router->add('POST', '/tutores/excluir', [TutorController::class, 'remove'], $authGuard);

// 4. Gestao de Pets (Listagem e Cadastro Vinculado Mandatoriamente ao Tutor)
$router->add('GET', '/pets', [PetController::class, 'list'], $authGuard);
$router->add('GET', '/pets/novo', [PetController::class, 'createForm'], $authGuard);
$router->add('POST', '/pets/salvar', [PetController::class, 'save'], $authGuard);

// 5. Gestao de Agendamentos (Listagem Ordenada por Data e Atualizacao de Data)
$router->add('GET', '/agendamentos', [AgendamentoController::class, 'list'], $authGuard);
$router->add('GET', '/agendamentos/novo', [AgendamentoController::class, 'createForm'], $authGuard);
$router->add('POST', '/agendamentos/salvar', [AgendamentoController::class, 'save'], $authGuard);
$router->add('GET', '/agendamentos/editar-data', [AgendamentoController::class, 'editDateForm'], $authGuard);
$router->add('POST', '/agendamentos/atualizar-data', [AgendamentoController::class, 'updateDate'], $authGuard);

// Despacha a requisicao HTTP atual
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
