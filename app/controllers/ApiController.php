<?php
require_once ROOT_PATH . '/config/Database.php';

class ApiController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnexion();
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
    }

    // GET /api/signalements
    public function signalements(): void
    {
        $stmt = $this->db->query(
            "SELECT s.id, s.titre, s.description, s.adresse, s.statut, s.priorite,
                    s.lat, s.lng, s.created_at,
                    c.libelle AS categorie,
                    u.nom AS citoyen_nom, u.prenom AS citoyen_prenom
             FROM signalements s
             LEFT JOIN categories c ON c.id = s.categorie_id
             LEFT JOIN users u ON u.id = s.user_id
             ORDER BY s.created_at DESC
             LIMIT 200"
        );
        echo json_encode([
            'success' => true,
            'total'   => $stmt->rowCount(),
            'data'    => $stmt->fetchAll(),
        ]);
    }

    // GET /api/signalements/{id}
    public function signalement(): void
    {
        $id   = (int)($_GET['id'] ?? 0);
        $stmt = $this->db->prepare(
            "SELECT s.*, c.libelle AS categorie,
                    u.nom AS citoyen_nom, u.prenom AS citoyen_prenom
             FROM signalements s
             LEFT JOIN categories c ON c.id = s.categorie_id
             LEFT JOIN users u ON u.id = s.user_id
             WHERE s.id = :id"
        );
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        if (!$data) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Signalement introuvable']);
            return;
        }
        echo json_encode(['success' => true, 'data' => $data]);
    }

    // GET /api/stats
    public function stats(): void
    {
        $stats = [
            'total'      => $this->db->query("SELECT COUNT(*) FROM signalements")->fetchColumn(),
            'nouveau'    => $this->db->query("SELECT COUNT(*) FROM signalements WHERE statut='nouveau'")->fetchColumn(),
            'en_cours'   => $this->db->query("SELECT COUNT(*) FROM signalements WHERE statut='en_cours'")->fetchColumn(),
            'resolu'     => $this->db->query("SELECT COUNT(*) FROM signalements WHERE statut='resolu'")->fetchColumn(),
            'rejete'     => $this->db->query("SELECT COUNT(*) FROM signalements WHERE statut='rejete'")->fetchColumn(),
            'categories' => $this->db->query(
                "SELECT c.libelle, COUNT(s.id) AS total FROM categories c
                 LEFT JOIN signalements s ON s.categorie_id = c.id
                 GROUP BY c.id, c.libelle"
            )->fetchAll(),
        ];
        echo json_encode(['success' => true, 'data' => $stats]);
    }
}