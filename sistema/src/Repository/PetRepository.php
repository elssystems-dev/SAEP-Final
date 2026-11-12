<?php
declare(strict_types=1);

namespace VetVida\Repository;

use VetVida\Core\Database;
use PDO;

final class PetRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findAll(): array {
        $sql = "SELECT p.id, p.nome, p.especie, p.raca, p.data_nascimento, p.cadastrado_em,
                       t.id AS tutor_id, t.nome AS tutor_nome, t.telefone AS tutor_telefone, t.email AS tutor_email
                FROM pets p
                INNER JOIN tutores t ON t.id = p.tutor_id
                ORDER BY p.nome ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT p.id, p.tutor_id, p.nome, p.especie, p.raca, p.data_nascimento, p.cadastrado_em,
                    t.nome AS tutor_nome
             FROM pets p
             INNER JOIN tutores t ON t.id = p.tutor_id
             WHERE p.id = :id LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(int $tutorId, string $nome, string $especie, string $raca, string $dataNascimento): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO pets (tutor_id, nome, especie, raca, data_nascimento) 
             VALUES (:tutor_id, :nome, :especie, :raca, :data_nascimento)"
        );
        return $stmt->execute([
            ':tutor_id'        => $tutorId,
            ':nome'            => trim($nome),
            ':especie'         => trim($especie),
            ':raca'            => trim($raca),
            ':data_nascimento' => $dataNascimento
        ]);
    }

    public function count(): int {
        return (int)$this->db->query("SELECT COUNT(id) FROM pets")->fetchColumn();
    }
}
