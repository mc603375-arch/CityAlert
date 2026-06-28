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

    // -------------------------------------------------------
    // Dashboard Admin
    // -------------------------------------------------------
    public function utilisateurs(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== ROLE_ADMIN) {
            header('Location: ' . BASE_URL . '/login'); exit;
        }

        $users             = (new UserRepository())->findAll();
        $totalUsers        = $this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $totalSignalements = $this->db->query("SELECT COUNT(*) FROM signalements")->fetchColumn();
        $nouveaux          = $this->db->query("SELECT COUNT(*) FROM signalements WHERE statut='nouveau'")->fetchColumn();
        $resolus           = $this->db->query("SELECT COUNT(*) FROM signalements WHERE statut='resolu'")->fetchColumn();
        $parCategorie      = $this->db->query(
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
    // Gestion des citoyens et utilisateurs
    // -------------------------------------------------------
    public function citoyens(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== ROLE_ADMIN) {
            header('Location: ' . BASE_URL . '/login'); exit;
        }

        $filtre = $_GET['role'] ?? '';

        if ($filtre === 'citoyen') {
            $users = $this->db->query(
                "SELECT * FROM users WHERE role = 'citoyen' ORDER BY nom"
            )->fetchAll();
        } elseif ($filtre === 'agent') {
            $users = $this->db->query(
                "SELECT * FROM users WHERE role = 'agent' ORDER BY nom"
            )->fetchAll();
        } elseif ($filtre === 'admin') {
            $users = $this->db->query(
                "SELECT * FROM users WHERE role = 'admin' ORDER BY nom"
            )->fetchAll();
        } else {
            $users = $this->db->query(
                "SELECT * FROM users ORDER BY role, nom"
            )->fetchAll();
        }

        $totalCitoyens = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'citoyen'")->fetchColumn();
        $totalAgents   = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'agent'")->fetchColumn();
        $totalAdmins   = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();

        $success = $_SESSION['success'] ?? null;
        $erreur  = $_SESSION['erreur']  ?? null;
        unset($_SESSION['success'], $_SESSION['erreur']);

        require VIEW_PATH . '/admin/citoyens.php';
    }

    // -------------------------------------------------------
    // Liste des agents
    // -------------------------------------------------------
    public function agents(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== ROLE_ADMIN) {
            header('Location: ' . BASE_URL . '/login'); exit;
        }

        $agents = $this->db->query(
            "SELECT u.*,
                    COUNT(s.id) AS nb_signalements,
                    SUM(s.statut = 'resolu')   AS nb_resolus,
                    SUM(s.statut = 'en_cours') AS nb_en_cours
             FROM users u
             LEFT JOIN signalements s ON s.agent_id = u.id
             WHERE u.role = 'agent'
             GROUP BY u.id
             ORDER BY u.nom"
        )->fetchAll();

        $success = $_SESSION['success'] ?? null;
        $erreur  = $_SESSION['erreur']  ?? null;
        unset($_SESSION['success'], $_SESSION['erreur']);

        require VIEW_PATH . '/admin/agents.php';
    }

    // -------------------------------------------------------
    // Créer un agent
    // -------------------------------------------------------
    public function creerAgent(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== ROLE_ADMIN) {
            header('Location: ' . BASE_URL . '/login'); exit;
        }

        $nom      = trim($_POST['nom']      ?? '');
        $prenom   = trim($_POST['prenom']   ?? '');
        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
            $_SESSION['erreur'] = "Tous les champs sont obligatoires.";
            header('Location: ' . BASE_URL . '/admin/agents'); exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['erreur'] = "Format d'email invalide.";
            header('Location: ' . BASE_URL . '/admin/agents'); exit;
        }

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        if ($stmt->fetchColumn() > 0) {
            $_SESSION['erreur'] = "Cet email est déjà utilisé.";
            header('Location: ' . BASE_URL . '/admin/agents'); exit;
        }

        $stmt = $this->db->prepare(
            "INSERT INTO users (nom, prenom, email, password, role)
             VALUES (:nom, :prenom, :email, :password, 'agent')"
        );
        $ok = $stmt->execute([
            ':nom'      => htmlspecialchars($nom),
            ':prenom'   => htmlspecialchars($prenom),
            ':email'    => $email,
            ':password' => password_hash($password, PASSWORD_BCRYPT),
        ]);

        $_SESSION[$ok ? 'success' : 'erreur'] = $ok
            ? "Agent créé avec succès !"
            : "Erreur lors de la création.";

        header('Location: ' . BASE_URL . '/admin/agents');
        exit;
    }

    // -------------------------------------------------------
    // Supprimer un agent
    // -------------------------------------------------------
    public function supprimerAgent(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== ROLE_ADMIN) {
            header('Location: ' . BASE_URL . '/login'); exit;
        }

        $id = (int)($_GET['id'] ?? 0);

        if ($id === 0) {
            header('Location: ' . BASE_URL . '/admin/agents'); exit;
        }

        $stmt = $this->db->prepare(
            "UPDATE signalements SET agent_id = NULL WHERE agent_id = :id"
        );
        $stmt->execute([':id' => $id]);

        $stmt = $this->db->prepare(
            "DELETE FROM users WHERE id = :id AND role = 'agent'"
        );
        $ok = $stmt->execute([':id' => $id]);

        $_SESSION[$ok ? 'success' : 'erreur'] = $ok
            ? "Agent supprimé."
            : "Erreur lors de la suppression.";

        header('Location: ' . BASE_URL . '/admin/agents');
        exit;
    }

    // -------------------------------------------------------
    // Changer le rôle d'un utilisateur
    // -------------------------------------------------------
    public function changerRole(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== ROLE_ADMIN) {
            header('Location: ' . BASE_URL . '/login'); exit;
        }

        $id   = (int)($_POST['id']  ?? 0);
        $role = trim($_POST['role'] ?? '');

        if (!in_array($role, ['citoyen', 'agent', 'admin'], true)) {
            $_SESSION['erreur'] = "Rôle invalide.";
            header('Location: ' . BASE_URL . '/admin/citoyens'); exit;
        }

        $stmt = $this->db->prepare("UPDATE users SET role = :role WHERE id = :id");
        $ok   = $stmt->execute([':role' => $role, ':id' => $id]);

        $_SESSION[$ok ? 'success' : 'erreur'] = $ok
            ? "Rôle mis à jour."
            : "Erreur lors de la mise à jour.";

        header('Location: ' . BASE_URL . '/admin/citoyens');
        exit;
    }
}