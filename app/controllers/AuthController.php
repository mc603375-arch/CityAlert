<?php

require_once ROOT_PATH . '/app/models/repositories/UserRepository.php';

class AuthController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    // -------------------------------------------------------
    // PAGE LOGIN
    // -------------------------------------------------------
    public function login(): void
    {
        if (isset($_SESSION['user'])) {
            $this->redirectDashboard();
            return;
        }

        $erreur = $_SESSION['erreur'] ?? null;
        unset($_SESSION['erreur']);

        require_once VIEW_PATH . '/auth/login.php';
    }

    // -------------------------------------------------------
    // TRAITEMENT LOGIN
    // -------------------------------------------------------
    public function traiterLogin(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            $_SESSION['erreur'] = "Tous les champs sont obligatoires.";
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $userData = $this->userRepository->findByEmail($email);

        if (!$userData || !password_verify($password, $userData['password'])) {
            $_SESSION['erreur'] = "Email ou mot de passe incorrect.";
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $_SESSION['user'] = [
            'id'     => $userData['id'],
            'nom'    => $userData['nom'],
            'prenom' => $userData['prenom'],
            'email'  => $userData['email'],
            'role'   => $userData['role']
        ];

        $this->redirectDashboard();
    }

    // -------------------------------------------------------
    // PAGE REGISTER
    // -------------------------------------------------------
    public function register(): void
    {
        if (isset($_SESSION['user'])) {
            $this->redirectDashboard();
            return;
        }

        $erreur  = $_SESSION['erreur']  ?? null;
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['erreur'], $_SESSION['success']);

        require_once VIEW_PATH . '/auth/register.php';
    }

    // -------------------------------------------------------
    // TRAITEMENT REGISTER
    // -------------------------------------------------------
    public function traiterRegister(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/register');
            exit;
        }

        $nom      = trim($_POST['nom']      ?? '');
        $prenom   = trim($_POST['prenom']   ?? '');
        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirm  = trim($_POST['confirm']  ?? '');

        if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
            $_SESSION['erreur'] = "Tous les champs sont obligatoires.";
            header('Location: ' . BASE_URL . '/register');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['erreur'] = "Format d'email invalide.";
            header('Location: ' . BASE_URL . '/register');
            exit;
        }

        if (strlen($password) < 6) {
            $_SESSION['erreur'] = "Le mot de passe doit contenir au moins 6 caractères.";
            header('Location: ' . BASE_URL . '/register');
            exit;
        }

        if ($password !== $confirm) {
            $_SESSION['erreur'] = "Les mots de passe ne correspondent pas.";
            header('Location: ' . BASE_URL . '/register');
            exit;
        }

        if ($this->userRepository->emailExiste($email)) {
            $_SESSION['erreur'] = "Cet email est déjà utilisé.";
            header('Location: ' . BASE_URL . '/register');
            exit;
        }

        $success = $this->userRepository->save([
            'nom'      => $nom,
            'prenom'   => $prenom,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'role'     => ROLE_CITOYEN
        ]);

        if ($success) {
            $_SESSION['success'] = "Compte créé avec succès ! Connectez-vous.";
            header('Location: ' . BASE_URL . '/login');
        } else {
            $_SESSION['erreur'] = "Erreur lors de la création du compte.";
            header('Location: ' . BASE_URL . '/register');
        }
        exit;
    }

    // -------------------------------------------------------
    // DÉCONNEXION
    // -------------------------------------------------------
    public function logout(): void
    {
        session_destroy();
        header('Location: ' . BASE_URL . '/login');
        exit;
    }

    // -------------------------------------------------------
    // Redirige selon le rôle
    // -------------------------------------------------------
    private function redirectDashboard(): void
    {
        $role = $_SESSION['user']['role'] ?? ROLE_CITOYEN;

        match($role) {
            ROLE_ADMIN   => header('Location: ' . BASE_URL . '/admin/dashboard'),
            ROLE_AGENT   => header('Location: ' . BASE_URL . '/agent/dashboard'),
            default      => header('Location: ' . BASE_URL . '/citoyen/dashboard')
        };
        exit;
    }
}