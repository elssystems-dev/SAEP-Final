<?php
declare(strict_types=1);

namespace VetVida\Controller;

use VetVida\Repository\AgendamentoRepository;
use VetVida\Repository\TutorRepository;
use VetVida\Repository\PetRepository;
use VetVida\Security\SessionManager;

final class AgendamentoController extends BaseController {
    private AgendamentoRepository $agendamentos;
    private TutorRepository $tutores;
    private PetRepository $pets;

    public function __construct() {
        $this->agendamentos = new AgendamentoRepository();
        $this->tutores = new TutorRepository();
        $this->pets = new PetRepository();
    }

    public function list(): void {
        $agendamentosList = $this->agendamentos->findAllOrderedByDate();

        $this->render('agendamentos/listar', [
            'titulo'       => 'Agendamentos',
            'agendamentos' => $agendamentosList,
            'sucesso'      => SessionManager::getFlash('sucesso'),
            'erro'         => SessionManager::getFlash('erro')
        ]);
    }

    public function createForm(): void {
        $this->render('agendamentos/criar', [
            'titulo'    => 'Novo agendamento',
            'tutores'   => $this->tutores->findAll(),
            'pets'      => $this->pets->findAll(),
            'erro'      => null,
            'csrfToken' => $this->getCsrfToken()
        ]);
    }

    public function save(): void {
        if (!$this->validateCsrfToken($_POST['csrf_token'] ?? null)) {
            SessionManager::flash('erro', 'Sessão expirada. Tente novamente.');
            $this->redirect('/agendamentos');
            return;
        }

        $tutorId   = (int)($_POST['tutor_id'] ?? 0);
        $petId     = (int)($_POST['pet_id'] ?? 0);
        $data      = trim((string)($_POST['data_agendamento'] ?? ''));
        $hora      = trim((string)($_POST['hora_agendamento'] ?? ''));
        $servico   = trim((string)($_POST['servico'] ?? ''));
        $status    = trim((string)($_POST['status'] ?? 'AGENDADO'));
        $obs       = trim((string)($_POST['observacoes'] ?? ''));
        $usuarioId = (int)($_SESSION['usuario_id'] ?? 1);

        if ($tutorId <= 0 || $petId <= 0 || $data === '' || $hora === '' || $servico === '') {
            $this->render('agendamentos/criar', [
                'titulo'    => 'Novo agendamento',
                'tutores'   => $this->tutores->findAll(),
                'pets'      => $this->pets->findAll(),
                'erro'      => 'Preencha tutor, pet, data, horário e procedimento.',
                'csrfToken' => $this->getCsrfToken()
            ]);
            return;
        }

        if ($this->agendamentos->create($tutorId, $petId, $usuarioId, $data, $hora, $servico, $status, $obs)) {
            SessionManager::flash('sucesso', "Consulta agendada para " . date('d/m/Y', strtotime($data)) . " às {$hora}.");
            $this->redirect('/agendamentos');
            return;
        }

        $this->render('agendamentos/criar', [
            'titulo'    => 'Novo agendamento',
            'tutores'   => $this->tutores->findAll(),
            'pets'      => $this->pets->findAll(),
            'erro'      => 'Não foi possível salvar o agendamento.',
            'csrfToken' => $this->getCsrfToken()
        ]);
    }

    public function editDateForm(): void {
        $id = (int)($_GET['id'] ?? 0);
        $agendamento = $this->agendamentos->findById($id);

        if (!$agendamento) {
            SessionManager::flash('erro', 'Agendamento não encontrado.');
            $this->redirect('/agendamentos');
            return;
        }

        $this->render('agendamentos/editar_data', [
            'titulo'      => 'Alterar data da consulta',
            'agendamento' => $agendamento,
            'erro'        => null,
            'csrfToken'   => $this->getCsrfToken()
        ]);
    }

    public function updateDate(): void {
        if (!$this->validateCsrfToken($_POST['csrf_token'] ?? null)) {
            SessionManager::flash('erro', 'Sessão expirada. Tente novamente.');
            $this->redirect('/agendamentos');
            return;
        }

        $id       = (int)($_POST['id'] ?? 0);
        $novaData = trim((string)($_POST['data_agendamento'] ?? ''));
        $novaHora = trim((string)($_POST['hora_agendamento'] ?? ''));

        if ($id <= 0 || $novaData === '') {
            SessionManager::flash('erro', 'Informe a nova data.');
            $this->redirect('/agendamentos/editar-data?id=' . $id);
            return;
        }

        if ($this->agendamentos->updateDate($id, $novaData, $novaHora !== '' ? $novaHora : null)) {
            SessionManager::flash('sucesso', "Data alterada para " . date('d/m/Y', strtotime($novaData)) . ".");
            $this->redirect('/agendamentos');
            return;
        }

        SessionManager::flash('erro', 'Não foi possível atualizar a data.');
        $this->redirect('/agendamentos/editar-data?id=' . $id);
    }
}
