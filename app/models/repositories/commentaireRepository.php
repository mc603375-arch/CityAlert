<?php

require_once ROOT_PATH . '/interfaces/RepositoryInterface.php';
require_once ROOT_PATH . '/config/Database.php';

class CommentaireRepository implements RepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnexion();
    }

    // -------------------------------------------------------
    // READ — Tous les commentaires d'un signalement
    // -------------------------------------------------------
    public function findAll(): array
    {
        $stmt = $this->db->prepare(
            "SELECT c.*, u.nom AS user_nom, u.prenom AS user_prenom, u.role AS user_role
             FROM commentaires c
             JOIN users u ON c.user_id = u.id
             ORDER BY c.created_at ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // -------------------------------------------------------
    // READ — Un commentaire par id
    // -------------------------------------------------------
    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT c.*, u.nom AS user_nom, u.prenom AS user_prenom
             FROM commentaires c
             JOIN users u ON c.user_id = u.id
             WHERE c.id = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // -------------------------------------------------------
    // READ — Commentaires d'un signalement
    // -------------------------------------------------------
    public function findBySignalement(int $signalement_id): array
    {
        $stmt = $this->db->prepare(
            "SELECT c.*, u.nom AS user_nom, u.prenom AS user_prenom, u.role AS user_role
             FROM commentaires c
             JOIN users u ON c.user_id = u.id
             WHERE c.signalement_id = :signalement_id
             ORDER BY c.created_at ASC"
        );
        $stmt->execute([':signalement_id' => $signalement_id]);
        return $stmt->fetchAll();
    }

    // -------------------------------------------------------
    // CREATE
    // -------------------------------------------------------
    public function save(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO commentaires (contenu, signalement_id, user_id)
             VALUES (:contenu, :signalement_id, :user_id)"
        );
        return $stmt->execute([
            ':contenu'        => $data['contenu'],
            ':signalement_id' => $data['signalement_id'],
            ':user_id'        => $data['user_id']
        ]);
    }

    // -------------------------------------------------------
    // DELETE
    // -------------------------------------------------------
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM commentaires WHERE id = :id"
        );
        return $stmt->execute([':id' => $id]);
    }
}