<?php
declare(strict_types=1);

namespace VetVida\Repository;

use VetVida\Core\Database;
use VetVida\Security\CryptoService;
use PDO;

final class TutorRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findAll(?string $search = null): array {
        if (!empty($search)) {
            $stmt = $this->db->prepare(
                "SELECT id, nome, cpf_criptografado, telefone, email, criado_em 
                 FROM tutores 
                 WHERE nome ILIKE :term OR email ILIKE :term OR telefone ILIKE :term 
                 ORDER BY nome ASC"
            );
            $stmt->execute([':term' => '%' . trim($search) . '%']);
            $rows = $stmt->fetchAll();
        } else {
            $rows = $this->db->query("SELECT id, nome, cpf_criptografado, telefone, email, criado_em FROM tutores ORDER BY nome ASC")->fetchAll();
        }

        foreach ($rows as &$r) {
            $r['cpf'] = CryptoService::decrypt($r['cpf_criptografado']);
        }
        return $rows;
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT id, nome, cpf_criptografado, telefone, email, criado_em FROM tutores WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        if ($row) {
            $row['cpf'] = CryptoService::decrypt($row['cpf_criptografado']);
        }
        return $row ?: null;
    }

    public function create(string $nome, string $cpf, string $telefone, string $email): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO tutores (nome, cpf_criptografado, telefone, email) 
             VALUES (:nome, :cpf, :telefone, :email)"
        );
        return $stmt->execute([
            ':nome'     => trim($nome),
            ':cpf'      => CryptoService::encrypt(trim($cpf)),
            ':telefone' => trim($telefone),
            ':email'    => trim($email)
        ]);
    }

    public function update(int $id, string $nome, string $cpf, string $telefone, string $email): bool {
        $stmt = $this->db->prepare(
            "UPDATE tutores 
             SET nome = :nome, cpf_criptografado = :cpf, telefone = :telefone, email = :email 
             WHERE id = :id"
        );
        return $stmt->execute([
            ':nome'     => trim($nome),
            ':cpf'      => CryptoService::encrypt(trim($cpf)),
            ':telefone' => trim($telefone),
            ':email'    => trim($email),
            ':id'       => $id
        ]);
    }

    public function delete(int $id): bool {
        Database::beginTransaction();
        try {
            $stmtAg = $this->db->prepare("DELETE FROM agendamentos WHERE tutor_id = :id");
            $stmtAg->execute([':id' => $id]);

            $stmtPet = $this->db->prepare("DELETE FROM pets WHERE tutor_id = :id");
            $stmtPet->execute([':id' => $id]);

            $stmtTut = $this->db->prepare("DELETE FROM tutores WHERE id = :id");
            $ok = $stmtTut->execute([':id' => $id]);

            Database::commit();
            return $ok;
        } catch (\Throwable $e) {
            Database::rollBack();
            throw $e;
        }
    }

    public function count(): int {
        return (int)$this->db->query("SELECT COUNT(id) FROM tutores")->fetchColumn();
    }
}
