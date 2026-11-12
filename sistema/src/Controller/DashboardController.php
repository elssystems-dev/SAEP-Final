<?php
declare(strict_types=1);

namespace VetVida\Controller;

use VetVida\Repository\TutorRepository;
use VetVida\Repository\PetRepository;
use VetVida\Repository\AgendamentoRepository;

final class DashboardController extends BaseController {
    private TutorRepository $tutores;
    private PetRepository $pets;
    private AgendamentoRepository $agendamentos;

    public function __construct() {
        $this->tutores = new TutorRepository();
        $this->pets = new PetRepository();
        $this->agendamentos = new AgendamentoRepository();
    }

    public function index(): void {
        $usuarioNome = $_SESSION['usuario_nome'] ?? 'Usuário';
        $usuarioPerfil = $_SESSION['usuario_perfil'] ?? '';

        $totalTutores = $this->tutores->count();
        $totalPets = $this->pets->count();
        $totalAgendamentos = $this->agendamentos->count();

        $todos = $this->agendamentos->findAllOrderedByDate();
        $proximos = array_slice($todos, 0, 5);

        $this->render('dashboard/index', [
            'titulo'               => 'Painel — SisVet',
            'usuarioNome'          => $usuarioNome,
            'usuarioPerfil'        => $usuarioPerfil,
            'totalTutores'         => $totalTutores,
            'totalPets'            => $totalPets,
            'totalAgendamentos'    => $totalAgendamentos,
            'proximosAgendamentos' => $proximos
        ]);
    }
}
