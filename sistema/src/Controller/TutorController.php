<?php
declare(strict_types=1);

namespace VetVida\Controller;

use VetVida\Repository\TutorRepository;
use VetVida\Security\SessionManager;

final class TutorController extends BaseController {
    private TutorRepository $tutores;

    public function __construct() {
        $this->tutores = new TutorRepository();
    }

    public function list(): void {
        $search = trim((string)($_GET['q'] ?? ''));
        $list = $this->tutores->findAll($search !== '' ? $search : null);

        $this->render('tutores/listar', [
            'titulo'    => 'Tutores',
            'tutores'   => $list,
            'busca'     => $search,
            'sucesso'   => SessionManager::getFlash('sucesso'),
            'erro'      => SessionManager::getFlash('erro'),
            'csrfToken' => $this->getCsrfToken()
        ]);
    }

    public function createForm(): void {
        $this->render('tutores/criar', [
            'titulo'    => 'Novo tutor',
            'erro'      => null,
            'csrfToken' => $this->getCsrfToken()
        ]);
    }

    public function save(): void {
        if (!$this->validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->render('tutores/criar', [
                'titulo'    => 'Novo tutor',
                'erro'      => 'Sessão expirada. Tente enviar novamente.',
                'csrfToken' => $this->getCsrfToken()
            ]);
            return;
        }

        $nome     = trim((string)($_POST['nome'] ?? ''));
        $cpf      = trim((string)($_POST['cpf'] ?? ''));
        $telefone = trim((string)($_POST['telefone'] ?? ''));
        $email    = trim((string)($_POST['email'] ?? ''));

        if ($nome === '' || $cpf === '' || $telefone === '' || $email === '') {
            $this->render('tutores/criar', [
                'titulo'    => 'Novo tutor',
                'erro'      => 'Preencha todos os campos.',
                'csrfToken' => $this->getCsrfToken()
            ]);
            return;
        }

        if ($this->tutores->create($nome, $cpf, $telefone, $email)) {
            SessionManager::flash('sucesso', "Tutor {$nome} cadastrado.");
            $this->redirect('/tutores');
            return;
        }

        $this->render('tutores/criar', [
            'titulo'    => 'Novo tutor',
            'erro'      => 'Não foi possível salvar o tutor.',
            'csrfToken' => $this->getCsrfToken()
        ]);
    }

    public function editForm(): void {
        $id = (int)($_GET['id'] ?? 0);
        $tutor = $this->tutores->findById($id);

        if (!$tutor) {
            SessionManager::flash('erro', 'Tutor não encontrado.');
            $this->redirect('/tutores');
            return;
        }

        $this->render('tutores/editar', [
            'titulo'    => 'Editar tutor',
            'tutor'     => $tutor,
            'erro'      => null,
            'csrfToken' => $this->getCsrfToken()
        ]);
    }

    public function update(): void {
        if (!$this->validateCsrfToken($_POST['csrf_token'] ?? null)) {
            SessionManager::flash('erro', 'Sessão expirada. Tente novamente.');
            $this->redirect('/tutores');
            return;
        }

        $id       = (int)($_POST['id'] ?? 0);
        $nome     = trim((string)($_POST['nome'] ?? ''));
        $cpf      = trim((string)($_POST['cpf'] ?? ''));
        $telefone = trim((string)($_POST['telefone'] ?? ''));
        $email    = trim((string)($_POST['email'] ?? ''));

        if ($id <= 0 || $nome === '' || $cpf === '' || $telefone === '' || $email === '') {
            SessionManager::flash('erro', 'Preencha todos os campos.');
            $this->redirect('/tutores/editar?id=' . $id);
            return;
        }

        if ($this->tutores->update($id, $nome, $cpf, $telefone, $email)) {
            SessionManager::flash('sucesso', "Cadastro de {$nome} atualizado.");
            $this->redirect('/tutores');
            return;
        }

        SessionManager::flash('erro', 'Não foi possível atualizar o cadastro.');
        $this->redirect('/tutores/editar?id=' . $id);
    }

    public function remove(): void {
        if (!$this->validateCsrfToken($_POST['csrf_token'] ?? null)) {
            SessionManager::flash('erro', 'Sessão expirada. Tente novamente.');
            $this->redirect('/tutores');
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $tutor = $this->tutores->findById($id);
            if ($tutor) {
                $this->tutores->delete($id);
                SessionManager::flash('sucesso', "Tutor {$tutor['nome']} removido.");
            } else {
                SessionManager::flash('erro', 'Tutor não encontrado.');
            }
        }
        $this->redirect('/tutores');
    }
}
