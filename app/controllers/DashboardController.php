<?php
require_once ROOT_PATH . '/config/Database.php';

class DashboardController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnexion();
    }

    private function requireAuth(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/login'); exit;
        }
    }

    public function citoyen(): void
    {
        $this->requireAuth();
        $userId = (int)$_SESSION['user']['id'];
        $stmt   = $this->db->prepare(
            "SELECT s.*, c.libelle AS categorie FROM signalements s
             LEFT JOIN categories c ON c.id = s.categorie_id
             WHERE s.user_id = :uid ORDER BY s.created_at DESC"
        );
        $stmt->execute([':uid' => $userId]);
        $signalements = $stmt->fetchAll();
        $success = $_SESSION['success'] ?? null;
        $erreur  = $_SESSION['erreur']  ?? null;
        unset($_SESSION['success'], $_SESSION['erreur']);
        require VIEW_PATH . '/dashboard/citoyen.php';
    }

    public function agent(): void
    {
        $this->requireAuth();
        if ($_SESSION['user']['role'] !== ROLE_AGENT) {
            header('Location: ' . BASE_URL . '/citoyen/dashboard'); exit;
        }
        $stmt = $this->db->prepare(
            "SELECT s.*, c.libelle AS categorie FROM signalements s
             LEFT JOIN categories c ON c.id = s.categorie_id
             WHERE s.agent_id = :aid OR s.statut = 'nouveau'
             ORDER BY s.created_at DESC"
        );
        $stmt->execute([':aid' => $_SESSION['user']['id']]);
        $signalements = $stmt->fetchAll();
        require VIEW_PATH . '/dashboard/agent.php';
    }

    public function admin(): void
    {
        $this->requireAuth();
        if ($_SESSION['user']['role'] !== ROLE_ADMIN) {
            header('Location: ' . BASE_URL . '/citoyen/dashboard'); exit;
        }
        $totalUsers        = $this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $totalSignalements = $this->db->query("SELECT COUNT(*) FROM signalements")->fetchColumn();
        $nouveaux          = $this->db->query("SELECT COUNT(*) FROM signalements WHERE statut='nouveau'")->fetchColumn();
        $enCours           = $this->db->query("SELECT COUNT(*) FROM signalements WHERE statut='en_cours'")->fetchColumn();
        $resolus           = $this->db->query("SELECT COUNT(*) FROM signalements WHERE statut='resolu'")->fetchColumn();
        $rejetes           = $this->db->query("SELECT COUNT(*) FROM signalements WHERE statut='rejete'")->fetchColumn();
        $parCategorie      = $this->db->query(
            "SELECT c.libelle, COUNT(s.id) AS total FROM categories c
             LEFT JOIN signalements s ON s.categorie_id = c.id
             GROUP BY c.id, c.libelle"
        )->fetchAll();
        require VIEW_PATH . '/dashboard/admin.php';
    }

    // ── Notification email ───────────────────────────────────
    public static function envoyerNotification(string $email, string $titre, string $ancienStatut, string $nouveauStatut): void
    {
        $sujet  = "[CityAlert] Votre signalement a été mis à jour";
        $statuts = ['nouveau' => 'Nouveau', 'en_cours' => 'En cours', 'resolu' => 'Résolu', 'rejete' => 'Rejeté'];
        $libAncien  = $statuts[$ancienStatut]  ?? $ancienStatut;
        $libNouveau = $statuts[$nouveauStatut] ?? $nouveauStatut;

        $message = "Bonjour,\n\n"
            . "Votre signalement \"$titre\" a changé de statut :\n\n"
            . "  Ancien statut : $libAncien\n"
            . "  Nouveau statut : $libNouveau\n\n"
            . "Consultez l'application CityAlert pour plus de détails.\n\n"
            . "L'équipe CityAlert";

        $headers = "From: noreply@cityalert.sn\r\nContent-Type: text/plain; charset=UTF-8";

        @mail($email, $sujet, $message, $headers);
    }
}