<?php
require_once ROOT_PATH . '/interfaces/RepositoryInterface.php';
require_once ROOT_PATH . '/config/Database.php';
require_once ROOT_PATH . '/app/models/entities/categories/Categorie.php';
require_once ROOT_PATH . '/app/models/entities/categories/Voirie.php';
require_once ROOT_PATH . '/app/models/entities/categories/Eclairage.php';
require_once ROOT_PATH . '/app/models/entities/categories/Dechets.php';
require_once ROOT_PATH . '/app/models/entities/categories/EauAssainissement.php';
require_once ROOT_PATH . '/exceptions/EntityNotFoundException.php';

class SignalementRepository implements RepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnexion();
    }

    public function findAll(): array
    {
        return $this->db->query(
            "SELECT s.*, c.libelle AS categorie_libelle
             FROM signalements s LEFT JOIN categories c ON c.id = s.categorie_id
             ORDER BY s.created_at DESC"
        )->fetchAll();
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT s.*, c.libelle AS categorie_libelle,
                    u.nom AS citoyen_nom, u.prenom AS citoyen_prenom
             FROM signalements s
             LEFT JOIN categories c ON c.id = s.categorie_id
             LEFT JOIN users u ON u.id = s.user_id
             WHERE s.id = :id"
        );
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        if (!$data) throw new EntityNotFoundException('Signalement', $id);
        return $data;
    }

    public function findByUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT s.*, c.libelle AS categorie_libelle
             FROM signalements s LEFT JOIN categories c ON c.id = s.categorie_id
             WHERE s.user_id = :uid ORDER BY s.created_at DESC"
        );
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll();
    }

    public function findByStatut(string $statut): array
    {
        $stmt = $this->db->prepare(
            "SELECT s.*, c.libelle AS categorie_libelle
             FROM signalements s LEFT JOIN categories c ON c.id = s.categorie_id
             WHERE s.statut = :statut ORDER BY s.created_at DESC"
        );
        $stmt->execute([':statut' => $statut]);
        return $stmt->fetchAll();
    }

    public function paginer(int $page, int $limite, array $filtres = []): array
    {
        $offset = ($page - 1) * $limite;
        $where  = [];
        $params = [];
        if (!empty($filtres['statut']))       { $where[] = 's.statut = :statut';    $params[':statut'] = $filtres['statut']; }
        if (!empty($filtres['categorie_id'])) { $where[] = 's.categorie_id = :cid'; $params[':cid']    = $filtres['categorie_id']; }
        if (!empty($filtres['user_id']))      { $where[] = 's.user_id = :uid';       $params[':uid']    = $filtres['user_id']; }

        $sql = "SELECT s.*, c.libelle AS categorie_libelle
                FROM signalements s LEFT JOIN categories c ON c.id = s.categorie_id";
        if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
        $sql .= ' ORDER BY s.created_at DESC LIMIT :limite OFFSET :offset';

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->bindValue(':limite',  $limite,  PDO::PARAM_INT);
        $stmt->bindValue(':offset',  $offset,  PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function compter(array $filtres = []): int
    {
        $where  = [];
        $params = [];
        if (!empty($filtres['statut']))       { $where[] = 'statut = :statut';      $params[':statut'] = $filtres['statut']; }
        if (!empty($filtres['categorie_id'])) { $where[] = 'categorie_id = :cid';   $params[':cid']    = $filtres['categorie_id']; }
        if (!empty($filtres['user_id']))      { $where[] = 'user_id = :uid';         $params[':uid']    = $filtres['user_id']; }
        $sql = 'SELECT COUNT(*) FROM signalements';
        if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function save(array $data): bool
    {
        if (empty($data['id'])) {
            $stmt = $this->db->prepare(
                "INSERT INTO signalements (titre, description, adresse, photo, statut, priorite, user_id, categorie_id, agent_id, lat, lng)
                 VALUES (:titre, :description, :adresse, :photo, :statut, :priorite, :user_id, :categorie_id, :agent_id, :lat, :lng)"
            );
            return $stmt->execute([
                ':titre'        => $data['titre'],
                ':description'  => $data['description'],
                ':adresse'      => $data['adresse'],
                ':photo'        => $data['photo']        ?? null,
                ':statut'       => $data['statut']       ?? 'nouveau',
                ':priorite'     => $data['priorite']     ?? 'normale',
                ':user_id'      => $data['user_id'],
                ':categorie_id' => $data['categorie_id'],
                ':agent_id'     => $data['agent_id']     ?? null,
                ':lat'          => $data['lat']          ?? null,
                ':lng'          => $data['lng']          ?? null,
            ]);
        }

        $stmt = $this->db->prepare(
            "UPDATE signalements
             SET titre=:titre, description=:description, adresse=:adresse,
                 photo=:photo, statut=:statut, priorite=:priorite,
                 categorie_id=:categorie_id, agent_id=:agent_id, lat=:lat, lng=:lng
             WHERE id=:id"
        );
        return $stmt->execute([
            ':titre'        => $data['titre'],
            ':description'  => $data['description'],
            ':adresse'      => $data['adresse'],
            ':photo'        => $data['photo']        ?? null,
            ':statut'       => $data['statut']       ?? 'nouveau',
            ':priorite'     => $data['priorite']     ?? 'normale',
            ':categorie_id' => $data['categorie_id'],
            ':agent_id'     => $data['agent_id']     ?? null,
            ':lat'          => $data['lat']          ?? null,
            ':lng'          => $data['lng']          ?? null,
            ':id'           => $data['id'],
        ]);
    }

    public function changerStatut(int $id, string $nouveauStatut, string $commentaire, int $agentId): bool
    {
        $ancien = $this->findById($id)['statut'];
        $this->db->prepare("UPDATE signalements SET statut=:s, agent_id=:a WHERE id=:id")
                 ->execute([':s' => $nouveauStatut, ':a' => $agentId, ':id' => $id]);
        return $this->db->prepare(
            "INSERT INTO historique_statuts (signalement_id, ancien_statut, nouveau_statut, commentaire, agent_id)
             VALUES (:sig, :ancien, :nouveau, :com, :agent)"
        )->execute([':sig' => $id, ':ancien' => $ancien, ':nouveau' => $nouveauStatut, ':com' => $commentaire, ':agent' => $agentId]);
    }

    public function getHistorique(int $sigId): array
    {
        $stmt = $this->db->prepare(
            "SELECT h.*, u.nom AS agent_nom, u.prenom AS agent_prenom
             FROM historique_statuts h LEFT JOIN users u ON u.id = h.agent_id
             WHERE h.signalement_id = :sid ORDER BY h.created_at ASC"
        );
        $stmt->execute([':sid' => $sigId]);
        return $stmt->fetchAll();
    }

    public function delete(int $id): bool
    {
        return $this->db->prepare("DELETE FROM signalements WHERE id=:id")->execute([':id' => $id]);
    }
}