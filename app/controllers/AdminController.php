<?php
require_once ROOT_PATH . '/app/models/repositories/UserRepository.php';
require_once ROOT_PATH . '/config/Database.php';

class AdminController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnexion();
    }

    public function utilisateurs(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== ROLE_ADMIN) {
            header('Location: ' . BASE_URL . '/login'); exit;
        }

        $users            = (new UserRepository())->findAll();
        $totalUsers       = $this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $totalSignalements= $this->db->query("SELECT COUNT(*) FROM signalements")->fetchColumn();
        $nouveaux         = $this->db->query("SELECT COUNT(*) FROM signalements WHERE statut='nouveau'")->fetchColumn();
        $resolus          = $this->db->query("SELECT COUNT(*) FROM signalements WHERE statut='resolu'")->fetchColumn();
        $parCategorie     = $this->db->query(
            "SELECT c.libelle, COUNT(s.id) AS total FROM categories c
             LEFT JOIN signalements s ON s.categorie_id = c.id GROUP BY c.id, c.libelle"
        )->fetchAll();

        $success = $_SESSION['success'] ?? null;
        $erreur  = $_SESSION['erreur']  ?? null;
        unset($_SESSION['success'], $_SESSION['erreur']);

        require VIEW_PATH . '/dashboard/admin.php';
    }
}