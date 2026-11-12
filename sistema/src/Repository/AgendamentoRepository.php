<?php
declare(strict_types=1);

namespace VetVida\Repository;

use VetVida\Core\Database;
use PDO;

final class AgendamentoRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findAllOrderedByDate(): array {
        $sql = "SELECT a.id, a.data_agendamento, a.hora_agendamento, a.servico, a.status, a.observacoes, a.criado_em,
                       EXTRACT(YEAR FROM a.data_agendamento) AS ano_agendamento,
                       t.id AS tutor_id, t.nome AS tutor_nome, t.telefone AS tutor_telefone, t.email AS tutor_email,
                       p.id AS pet_id, p.nome AS pet_nome, p.especie AS pet_especie, p.raca AS pet_raca, p.data_nascimento AS pet_nascimento,
                       u.id AS usuario_id, u.nome AS usuario_nome
                FROM agendamentos a
                INNER JOIN tutores t ON t.id = a.tutor_id
                INNER JOIN pets p ON p.id = a.pet_id
                INNER JOIN usuarios u ON u.id = a.usuario_id
                ORDER BY a.data_agendamento ASC, a.hora_agendamento ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT a.id, a.tutor_id, a.pet_id, a.usuario_id, a.data_agendamento, a.hora_agendamento, a.servico, a.status, a.observacoes,
                    t.nome AS tutor_nome, t.telefone AS tutor_telefone, t.email AS tutor_email,
                    p.nome AS pet_nome, p.especie AS pet_especie, p.raca AS pet_raca
             FROM agendamentos a
             INNER JOIN tutores t ON t.id = a.tutor_id
             INNER JOIN pets p ON p.id = a.pet_id
             WHERE a.id = :id LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(
        int $tutorId,
        int $petId,
        int $usuarioId,
        string $data,
        string $hora,
        string $servico,
        string $status = 'AGENDADO',
        ?string $obs = null
    ): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO agendamentos (tutor_id, pet_id, usuario_id, data_agendamento, hora_agendamento, servico, status, observacoes) 
             VALUES (:tutor_id, :pet_id, :usuario_id, :data_agendamento, :hora_agendamento, :servico, :status, :observacoes)"
        );
        return $stmt->execute([
            ':tutor_id'          => $tutorId,
            ':pet_id'            => $petId,
            ':usuario_id'        => $usuarioId,
            ':data_agendamento'  => $data,
            ':hora_agendamento'  => $hora,
            ':servico'           => trim($servico),
            ':status'            => $status,
            ':observacoes'       => $obs !== null && trim($obs) !== '' ? trim($obs) : null
        ]);
    }

    public function updateDate(int $id, string $novaData, ?string $novaHora = null): bool {
        if ($novaHora !== null && trim($novaHora) !== '') {
            $stmt = $this->db->prepare("UPDATE agendamentos SET data_agendamento = :dt, hora_agendamento = :hr WHERE id = :id");
            return $stmt->execute([':dt' => $novaData, ':hr' => $novaHora, ':id' => $id]);
        }
        $stmt = $this->db->prepare("UPDATE agendamentos SET data_agendamento = :dt WHERE id = :id");
        return $stmt->execute([':dt' => $novaData, ':id' => $id]);
    }

    public function count(): int {
        return (int)$this->db->query("SELECT COUNT(id) FROM agendamentos")->fetchColumn();
    }
}
