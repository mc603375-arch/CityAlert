<?php

require_once __DIR__ . '/../../config/Database.php';

class DashboardController
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function index()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $role = $_SESSION['user']['role'];

        if ($role === 'citoyen') {
            $this->citoyen();
            return;
        }

        if ($role === 'agent') {
            $this->agent();
            return;
        }

        if ($role === 'admin') {
            $this->admin();
            return;
        }

        header('Location: index.php');
        exit;
    }

    public function citoyen()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $userId = (int) $_SESSION['user']['id'];

        $sql = "SELECT s.*, c.libelle AS categorie
                FROM signalements s
                LEFT JOIN categories c ON c.id = s.categorie_id
                WHERE s.user_id = :user_id
                ORDER BY s.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);

        $signalements = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../views/dashboard/citoyen.php';
    }

    public function agent()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'agent') {
            header('Location: index.php?controller=dashboard&action=index');
            exit;
        }

        $agentId = (int) $_SESSION['user']['id'];

        $sql = "SELECT s.*, c.libelle AS categorie
                FROM signalements s
                LEFT JOIN categories c ON c.id = s.categorie_id
                WHERE s.agent_id = :agent_id OR s.agent_id IS NULL
                ORDER BY s.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':agent_id' => $agentId]);

        $signalements = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../views/dashboard/agent.php';
    }

    public function admin()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?controller=dashboard&action=index');
            exit;
        }

        $totalUsers = $this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();

        $totalSignalements = $this->db->query("SELECT COUNT(*) FROM signalements")->fetchColumn();

        $nouveaux = $this->db
            ->query("SELECT COUNT(*) FROM signalements WHERE statut = 'nouveau'")
            ->fetchColumn();

        $resolus = $this->db
            ->query("SELECT COUNT(*) FROM signalements WHERE statut = 'resolu'")
            ->fetchColumn();

        require __DIR__ . '/../views/dashboard/admin.php';
    }
}