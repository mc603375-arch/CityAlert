<?php
require_once ROOT_PATH . '/app/models/repositories/UserRepository.php';

class AuthController
{
    private UserRepository $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
    }

    public function login(): void
    {
        if (isset($_SESSION['user'])) { $this->redirectDashboard(); return; }
        $erreur  = $_SESSION['erreur']  ?? null;
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['erreur'], $_SESSION['success']);
        require VIEW_PATH . '/auth/login.php';
    }

    public function traiterLogin(): void
    {
        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            $_SESSION['erreur'] = "Tous les champs sont obligatoires.";
            header('Location: ' . BASE_URL . '/login'); exit;
        }

        $userData = $this->userRepo->findByEmail($email);

        if (!$userData || !password_verify($password, $userData['password'])) {
            $_SESSION['erreur'] = "Email ou mot de passe incorrect.";
            header('Location: ' . BASE_URL . '/login'); exit;
        }

        $_SESSION['user'] = [
            'id'     => $userData['id'],
            'nom'    => $userData['nom'],
            'prenom' => $userData['prenom'],
            'email'  => $userData['email'],
            'role'   => $userData['role'],
        ];
        $this->redirectDashboard();
    }

    public function register(): void
    {
        if (isset($_SESSION['user'])) { $this->redirectDashboard(); return; }
        $erreur  = $_SESSION['erreur']  ?? null;
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['erreur'], $_SESSION['success']);
        require VIEW_PATH . '/auth/register.php';
    }

    public function traiterRegister(): void
    {
        $nom      = trim($_POST['nom']      ?? '');
        $prenom   = trim($_POST['prenom']   ?? '');
        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirm  = trim($_POST['confirm']  ?? '');

        if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
            $_SESSION['erreur'] = "Tous les champs sont obligatoires.";
            header('Location: ' . BASE_URL . '/register'); exit;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['erreur'] = "Format d'email invalide.";
            header('Location: ' . BASE_URL . '/register'); exit;
        }
        if (strlen($password) < 6) {
            $_SESSION['erreur'] = "Le mot de passe doit contenir au moins 6 caractères.";
            header('Location: ' . BASE_URL . '/register'); exit;
        }
        if ($password !== $confirm) {
            $_SESSION['erreur'] = "Les mots de passe ne correspondent pas.";
            header('Location: ' . BASE_URL . '/register'); exit;
        }
        if ($this->userRepo->emailExiste($email)) {
            $_SESSION['erreur'] = "Cet email est déjà utilisé.";
            header('Location: ' . BASE_URL . '/register'); exit;
        }

        $ok = $this->userRepo->save([
            'nom'      => htmlspecialchars($nom),
            'prenom'   => htmlspecialchars($prenom),
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'role'     => ROLE_CITOYEN,
        ]);

        if ($ok) {
            $_SESSION['success'] = "Compte créé avec succès ! Connectez-vous.";
            header('Location: ' . BASE_URL . '/login');
        } else {
            $_SESSION['erreur'] = "Erreur lors de la création du compte.";
            header('Location: ' . BASE_URL . '/register');
        }
        exit;
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: ' . BASE_URL . '/login'); exit;
    }

    private function redirectDashboard(): void
    {
        match($_SESSION['user']['role']) {
            ROLE_ADMIN  => header('Location: ' . BASE_URL . '/admin/dashboard'),
            ROLE_AGENT  => header('Location: ' . BASE_URL . '/agent/dashboard'),
            default     => header('Location: ' . BASE_URL . '/citoyen/dashboard'),
        };
        exit;
    }
}