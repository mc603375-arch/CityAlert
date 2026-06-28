<?php

require_once ROOT_PATH . '/config/Database.php';

class DashboardController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnexion();
    }

    // -------------------------------------------------------
    // Dashboard Citoyen
    // -------------------------------------------------------
    public function citoyen(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/login'); exit;
        }
        if ($_SESSION['user']['role'] !== ROLE_CITOYEN) {
            header('Location: ' . BASE_URL . '/login'); exit;
        }

        $userId = (int)$_SESSION['user']['id'];

        $stmt = $this->db->prepare(
            "SELECT s.*, c.libelle AS categorie
             FROM signalements s
             LEFT JOIN categories c ON c.id = s.categorie_id
             WHERE s.user_id = :user_id
             ORDER BY s.created_at DESC"
        );
        $stmt->execute([':user_id' => $userId]);
        $signalements = $stmt->fetchAll();

        // Stats du citoyen
        $total   = count($signalements);
        $resolus = count(array_filter($signalements, fn($s) => $s['statut'] === 'resolu'));
        $enCours = count(array_filter($signalements, fn($s) => $s['statut'] === 'en_cours'));

        $success = $_SESSION['success'] ?? null;
        $erreur  = $_SESSION['erreur']  ?? null;
        unset($_SESSION['success'], $_SESSION['erreur']);

        require VIEW_PATH . '/dashboard/citoyen.php';
    }

    // -------------------------------------------------------
    // Dashboard Agent — voit SEULEMENT ses signalements assignés
    // -------------------------------------------------------
    public function agent(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/login'); exit;
        }
        if ($_SESSION['user']['role'] !== ROLE_AGENT) {
            header('Location: ' . BASE_URL . '/login'); exit;
        }

        $agentId = (int)$_SESSION['user']['id'];

        // Seulement les signalements assignés à CET agent
        $stmt = $this->db->prepare(
            "SELECT s.*, c.libelle AS categorie,
                    u.nom AS citoyen_nom, u.prenom AS citoyen_prenom
             FROM signalements s
             LEFT JOIN categories c ON c.id = s.categorie_id
             LEFT JOIN users u ON u.id = s.user_id
             WHERE s.agent_id = :agent_id
             ORDER BY s.created_at DESC"
        );
        $stmt->execute([':agent_id' => $agentId]);
        $signalements = $stmt->fetchAll();

        $success = $_SESSION['success'] ?? null;
        $erreur  = $_SESSION['erreur']  ?? null;
        unset($_SESSION['success'], $_SESSION['erreur']);

        require VIEW_PATH . '/dashboard/agent.php';
    }

    // -------------------------------------------------------
    // Dashboard Admin
    // -------------------------------------------------------
    public function admin(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/login'); exit;
        }
        if ($_SESSION['user']['role'] !== ROLE_ADMIN) {
            header('Location: ' . BASE_URL . '/login'); exit;
        }

        $totalUsers        = $this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $totalSignalements = $this->db->query("SELECT COUNT(*) FROM signalements")->fetchColumn();
        $nouveaux          = $this->db->query("SELECT COUNT(*) FROM signalements WHERE statut='nouveau'")->fetchColumn();
        $enCours           = $this->db->query("SELECT COUNT(*) FROM signalements WHERE statut='en_cours'")->fetchColumn();
        $resolus           = $this->db->query("SELECT COUNT(*) FROM signalements WHERE statut='resolu'")->fetchColumn();
        $rejetes           = $this->db->query("SELECT COUNT(*) FROM signalements WHERE statut='rejete'")->fetchColumn();

        $parCategorie = $this->db->query(
            "SELECT c.libelle, COUNT(s.id) AS total
             FROM categories c
             LEFT JOIN signalements s ON s.categorie_id = c.id
             GROUP BY c.id, c.libelle"
        )->fetchAll();

        $success = $_SESSION['success'] ?? null;
        $erreur  = $_SESSION['erreur']  ?? null;
        unset($_SESSION['success'], $_SESSION['erreur']);

        require VIEW_PATH . '/dashboard/admin.php';
    }

    // -------------------------------------------------------
    // Notification email (statique)
    // -------------------------------------------------------
    public static function envoyerNotification(
        string $email,
        string $titre,
        string $ancienStatut,
        string $nouveauStatut
    ): void {
        $sujet  = "CityAlert — Mise à jour de votre signalement";
        $corps  = "Bonjour,\n\n";
        $corps .= "Votre signalement \"$titre\" a changé de statut :\n";
        $corps .= "• Ancien statut : $ancienStatut\n";
        $corps .= "• Nouveau statut : $nouveauStatut\n\n";
        $corps .= "Merci d'utiliser CityAlert.\n";

        // mail() fonctionne si un serveur SMTP est configuré
        @mail($email, $sujet, $corps, "From: noreply@cityalert.com");
    }
}