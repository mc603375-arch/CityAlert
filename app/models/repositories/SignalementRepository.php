<?php

require_once ROOT_PATH . '/interfaces/RepositoryInterface.php';
require_once ROOT_PATH . '/config/Database.php';

class SignalementRepository implements RepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnexion();
    }

    // -------------------------------------------------------
    // READ — Tous les signalements avec infos auteur/catégorie
    // -------------------------------------------------------
    public function findAll(): array
    {
        $stmt = $this->db->prepare(
            "SELECT s.*,
                    u.nom AS user_nom, u.prenom AS user_prenom,
                    c.libelle AS categorie_libelle,
                    a.nom AS agent_nom, a.prenom AS agent_prenom
             FROM signalements s
             JOIN users u      ON s.user_id      = u.id
             JOIN categories c ON s.categorie_id = c.id
             LEFT JOIN users a ON s.agent_id     = a.id
             ORDER BY s.created_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // -------------------------------------------------------
    // READ — Un signalement par id
    // -------------------------------------------------------
    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT s.*,
                    u.nom AS user_nom, u.prenom AS user_prenom,
                    c.libelle AS categorie_libelle,
                    a.nom AS agent_nom, a.prenom AS agent_prenom
             FROM signalements s
             JOIN users u      ON s.user_id      = u.id
             JOIN categories c ON s.categorie_id = c.id
             LEFT JOIN users a ON s.agent_id     = a.id
             WHERE s.id = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // -------------------------------------------------------
    // READ — Signalements par statut
    // -------------------------------------------------------
    public function findByStatut(string $statut): array
    {
        $stmt = $this->db->prepare(
            "SELECT s.*,
                    u.nom AS user_nom, u.prenom AS user_prenom,
                    c.libelle AS categorie_libelle
             FROM signalements s
             JOIN users u      ON s.user_id      = u.id
             JOIN categories c ON s.categorie_id = c.id
             WHERE s.statut = :statut
             ORDER BY s.created_at DESC"
        );
        $stmt->execute([':statut' => $statut]);
        return $stmt->fetchAll();
    }

    // -------------------------------------------------------
    // READ — Signalements d'un citoyen
    // -------------------------------------------------------
    public function findByUserId(int $user_id): array
    {
        $stmt = $this->db->prepare(
            "SELECT s.*, c.libelle AS categorie_libelle
             FROM signalements s
             JOIN categories c ON s.categorie_id = c.id
             WHERE s.user_id = :user_id
             ORDER BY s.created_at DESC"
        );
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll();
    }

    // -------------------------------------------------------
    // READ — Statistiques pour le dashboard admin
    // -------------------------------------------------------
    public function getStatistiques(): array
    {
        $stmt = $this->db->prepare(
            "SELECT
                COUNT(*) AS total,
                SUM(statut = 'nouveau')  AS nouveau,
                SUM(statut = 'en_cours') AS en_cours,
                SUM(statut = 'resolu')   AS resolu,
                SUM(statut = 'rejete')   AS rejete
             FROM signalements"
        );
        $stmt->execute();
        return $stmt->fetch();
    }

    // -------------------------------------------------------
    // CREATE / UPDATE
    // -------------------------------------------------------
    public function save(array $data): bool
    {
        if (isset($data['id'])) {
            // UPDATE
            $stmt = $this->db->prepare(
                "UPDATE signalements
                 SET titre = :titre, description = :description,
                     adresse = :adresse, statut = :statut,
                     priorite = :priorite, agent_id = :agent_id,
                     updated_at = NOW()
                 WHERE id = :id"
            );
            return $stmt->execute([
                ':id'          => $data['id'],
                ':titre'       => $data['titre'],
                ':description' => $data['description'],
                ':adresse'     => $data['adresse'],
                ':statut'      => $data['statut'],
                ':priorite'    => $data['priorite'],
                ':agent_id'    => $data['agent_id'] ?? null
            ]);
        } else {
            // CREATE
            $stmt = $this->db->prepare(
                "INSERT INTO signalements
                 (titre, description, adresse, photo, priorite, user_id, categorie_id)
                 VALUES
                 (:titre, :description, :adresse, :photo, :priorite, :user_id, :categorie_id)"
            );
            return $stmt->execute([
                ':titre'        => $data['titre'],
                ':description'  => $data['description'],
                ':adresse'      => $data['adresse'],
                ':photo'        => $data['photo'] ?? null,
                ':priorite'     => $data['priorite'] ?? 'normale',
                ':user_id'      => $data['user_id'],
                ':categorie_id' => $data['categorie_id']
            ]);
        }
    }

    // -------------------------------------------------------
    // DELETE
    // -------------------------------------------------------
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM signalements WHERE id = :id"
        );
        return $stmt->execute([':id' => $id]);
    }

    // -------------------------------------------------------
    // Récupère le dernier id inséré
    // -------------------------------------------------------
    public function getLastInsertId(): int
    {
        return (int)$this->db->lastInsertId();
    }
}