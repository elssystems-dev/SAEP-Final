<?php
declare(strict_types=1);

namespace VetVida\Repository;

use VetVida\Core\Database;
use PDO;

final class UsuarioRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT id, nome, email, senha_hash, perfil, criado_em FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => trim($email)]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT id, nome, email, perfil, criado_em FROM usuarios WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function count(): int {
        return (int)$this->db->query("SELECT COUNT(id) FROM usuarios")->fetchColumn();
    }
}
