<?php

require_once ROOT_PATH . '/interfaces/RepositoryInterface.php';
require_once ROOT_PATH . '/config/Database.php';
require_once ROOT_PATH . '/app/models/entities/User.php';

class UserRepository implements RepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnexion();
    }

    // -------------------------------------------------------
    // READ — Tous les utilisateurs
    // -------------------------------------------------------
    public function findAll(): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users ORDER BY nom ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // -------------------------------------------------------
    // READ — Un utilisateur par id
    // -------------------------------------------------------
    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE id = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // -------------------------------------------------------
    // READ — Un utilisateur par email
    // -------------------------------------------------------
    public function findByEmail(string $email): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE email = :email"
        );
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    // -------------------------------------------------------
    // READ — Tous les utilisateurs par rôle
    // -------------------------------------------------------
    public function findByRole(string $role): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE role = :role ORDER BY nom ASC"
        );
        $stmt->execute([':role' => $role]);
        return $stmt->fetchAll();
    }

    // -------------------------------------------------------
    // CREATE / UPDATE — Sauvegarder un utilisateur
    // -------------------------------------------------------
    public function save(array $data): bool
    {
        if (isset($data['id'])) {
            // UPDATE
            $stmt = $this->db->prepare(
                "UPDATE users
                 SET nom = :nom, prenom = :prenom,
                     email = :email, role = :role,
                     updated_at = NOW()
                 WHERE id = :id"
            );
            return $stmt->execute([
                ':id'     => $data['id'],
                ':nom'    => $data['nom'],
                ':prenom' => $data['prenom'],
                ':email'  => $data['email'],
                ':role'   => $data['role']
            ]);
        } else {
            // CREATE
            $stmt = $this->db->prepare(
                "INSERT INTO users (nom, prenom, email, password, role)
                 VALUES (:nom, :prenom, :email, :password, :role)"
            );
            return $stmt->execute([
                ':nom'      => $data['nom'],
                ':prenom'   => $data['prenom'],
                ':email'    => $data['email'],
                ':password' => $data['password'],
                ':role'     => $data['role'] ?? ROLE_CITOYEN
            ]);
        }
    }

    // -------------------------------------------------------
    // DELETE — Supprimer un utilisateur
    // -------------------------------------------------------
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM users WHERE id = :id"
        );
        return $stmt->execute([':id' => $id]);
    }

    // -------------------------------------------------------
    // Vérifie si un email existe déjà
    // -------------------------------------------------------
    public function emailExiste(string $email): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM users WHERE email = :email"
        );
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn() > 0;
    }

    // -------------------------------------------------------
    // Récupère le dernier id inséré
    // -------------------------------------------------------
    public function getLastInsertId(): int
    {
        return (int)$this->db->lastInsertId();
    }
}