<?php

require_once ROOT_PATH . '/interfaces/NotifiableInterface.php';
require_once ROOT_PATH . '/traits/Timestampable.php';

class User implements NotifiableInterface
{
    // -------------------------------------------------------
    // Trait Timestampable (created_at, updated_at)
    // -------------------------------------------------------
    use Timestampable;

    // -------------------------------------------------------
    // ATTRIBUTS PRIVÉS
    // -------------------------------------------------------
    private int    $id;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $password;
    private string $role;

    // -------------------------------------------------------
    // CONSTRUCTEUR
    // -------------------------------------------------------
    public function __construct(
        string $nom,
        string $prenom,
        string $email,
        string $password,
        string $role = ROLE_CITOYEN
    ) {
        $this->nom      = $nom;
        $this->prenom   = $prenom;
        $this->email    = $email;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        $this->role     = $role;
        $this->initTimestamps();
    }

    // -------------------------------------------------------
    // GETTERS
    // -------------------------------------------------------
    public function getId(): int       { return $this->id; }
    public function getNom(): string   { return $this->nom; }
    public function getPrenom(): string { return $this->prenom; }
    public function getEmail(): string { return $this->email; }
    public function getPassword(): string { return $this->password; }
    public function getRole(): string  { return $this->role; }

    public function getNomComplet(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    // -------------------------------------------------------
    // SETTERS
    // -------------------------------------------------------
    public function setId(int $id): void          { $this->id = $id; }
    public function setNom(string $nom): void      { $this->nom = $nom; }
    public function setPrenom(string $prenom): void { $this->prenom = $prenom; }
    public function setEmail(string $email): void  { $this->email = $email; }
    public function setRole(string $role): void    { $this->role = $role; }

    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        $this->setUpdatedAt();
    }

    // -------------------------------------------------------
    // Vérifie si le mot de passe est correct
    // -------------------------------------------------------
    public function verifierPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    // -------------------------------------------------------
    // Vérifie le rôle de l'utilisateur
    // -------------------------------------------------------
    public function estAdmin(): bool    { return $this->role === ROLE_ADMIN; }
    public function estAgent(): bool    { return $this->role === ROLE_AGENT; }
    public function estCitoyen(): bool  { return $this->role === ROLE_CITOYEN; }

    // -------------------------------------------------------
    // INTERFACE NotifiableInterface
    // -------------------------------------------------------
    public function notifier(string $message): void
    {
        // Stocke la notification en session
        $_SESSION['notification'] = $message;
    }

    // -------------------------------------------------------
    // Crée un User depuis un tableau (retour BDD)
    // -------------------------------------------------------
    public static function fromArray(array $data): self
    {
        $user           = new self(
            $data['nom'],
            $data['prenom'],
            $data['email'],
            $data['password'],
            $data['role']
        );
        $user->id         = $data['id'];
        $user->password   = $data['password']; // déjà hashé en BDD
        $user->created_at = $data['created_at'] ?? date('Y-m-d H:i:s');
        $user->updated_at = $data['updated_at'] ?? date('Y-m-d H:i:s');
        return $user;
    }
}