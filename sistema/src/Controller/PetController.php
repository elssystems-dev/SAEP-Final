<?php
declare(strict_types=1);

namespace VetVida\Controller;

use VetVida\Repository\PetRepository;
use VetVida\Repository\TutorRepository;
use VetVida\Security\SessionManager;

final class PetController extends BaseController {
    private PetRepository $pets;
    private TutorRepository $tutores;

    public function __construct() {
        $this->pets = new PetRepository();
        $this->tutores = new TutorRepository();
    }

    public function list(): void {
        $petsList = $this->pets->findAll();

        $this->render('pets/listar', [
            'titulo'  => 'Pets',
            'pets'    => $petsList,
            'sucesso' => SessionManager::getFlash('sucesso'),
            'erro'    => SessionManager::getFlash('erro')
        ]);
    }

    public function createForm(): void {
        $tutoresList = $this->tutores->findAll();

        $this->render('pets/criar', [
            'titulo'    => 'Novo pet',
            'tutores'   => $tutoresList,
            'erro'      => null,
            'csrfToken' => $this->getCsrfToken()
        ]);
    }

    public function save(): void {
        if (!$this->validateCsrfToken($_POST['csrf_token'] ?? null)) {
            SessionManager::flash('erro', 'Sessão expirada. Tente novamente.');
            $this->redirect('/pets');
            return;
        }

        $tutorId        = (int)($_POST['tutor_id'] ?? 0);
        $nome           = trim((string)($_POST['nome'] ?? ''));
        $especie        = trim((string)($_POST['especie'] ?? ''));
        $raca           = trim((string)($_POST['raca'] ?? ''));
        $dataNascimento = trim((string)($_POST['data_nascimento'] ?? ''));

        if ($tutorId <= 0 || $nome === '' || $especie === '' || $raca === '' || $dataNascimento === '') {
            $this->render('pets/criar', [
                'titulo'    => 'Novo pet',
                'tutores'   => $this->tutores->findAll(),
                'erro'      => 'Selecione um tutor e preencha os dados do pet.',
                'csrfToken' => $this->getCsrfToken()
            ]);
            return;
        }

        if ($this->pets->create($tutorId, $nome, $especie, $raca, $dataNascimento)) {
            SessionManager::flash('sucesso', "Pet {$nome} cadastrado.");
            $this->redirect('/pets');
            return;
        }

        $this->render('pets/criar', [
            'titulo'    => 'Novo pet',
            'tutores'   => $this->tutores->findAll(),
            'erro'      => 'Não foi possível salvar o pet.',
            'csrfToken' => $this->getCsrfToken()
        ]);
    }
}
