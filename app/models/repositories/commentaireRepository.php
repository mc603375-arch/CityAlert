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
    public function findAll(): array
    {
        return $this->db->query("SELECT * FROM commentaires ORDER BY created_at DESC")->fetchAll();
    }
    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM commentaires WHERE id=:id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    public function findBySignalement(int $sigId): array
    {
        $stmt = $this->db->prepare(
            "SELECT c.*, u.nom, u.prenom, u.role FROM commentaires c
             JOIN users u ON u.id = c.user_id
             WHERE c.signalement_id = :sid ORDER BY c.created_at ASC"
        );
        $stmt->execute([':sid' => $sigId]);
        return $stmt->fetchAll();
    }
    public function save(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO commentaires (contenu, signalement_id, user_id) VALUES (:contenu, :sig_id, :user_id)"
        );
        return $stmt->execute([
            ':contenu'  => $data['contenu'],
            ':sig_id'   => $data['signalement_id'],
            ':user_id'  => $data['user_id'],
        ]);
    }
    public function delete(int $id): bool
    {
        return $this->db->prepare("DELETE FROM commentaires WHERE id=:id")->execute([':id' => $id]);
    }
}
