<?php
require_once ROOT_PATH . '/interfaces/NotifiableInterface.php';
require_once ROOT_PATH . '/traits/Timestampable.php';

class User implements NotifiableInterface
{
    use Timestampable;

    private int    $id;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $password;
    private string $role;

    public function __construct(string $nom, string $prenom, string $email, string $password, string $role = ROLE_CITOYEN)
    {
        $this->nom      = $nom;
        $this->prenom   = $prenom;
        $this->email    = $email;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        $this->role     = $role;
        $this->initTimestamps();
    }

    public function getId(): int          { return $this->id; }
    public function getNom(): string      { return $this->nom; }
    public function getPrenom(): string   { return $this->prenom; }
    public function getEmail(): string    { return $this->email; }
    public function getPassword(): string { return $this->password; }
    public function getRole(): string     { return $this->role; }
    public function getNomComplet(): string { return $this->prenom . ' ' . $this->nom; }

    public function setId(int $id): void          { $this->id = $id; }
    public function setNom(string $n): void        { $this->nom = $n; }
    public function setPrenom(string $p): void     { $this->prenom = $p; }
    public function setEmail(string $e): void      { $this->email = $e; }
    public function setRole(string $r): void       { $this->role = $r; }
    public function setPassword(string $p): void   { $this->password = password_hash($p, PASSWORD_BCRYPT); $this->setUpdatedAt(); }

    public function verifierPassword(string $password): bool { return password_verify($password, $this->password); }
    public function estAdmin(): bool   { return $this->role === ROLE_ADMIN; }
    public function estAgent(): bool   { return $this->role === ROLE_AGENT; }
    public function estCitoyen(): bool { return $this->role === ROLE_CITOYEN; }

    public function notifier(string $message): void { $_SESSION['notification'] = $message; }

    public static function fromArray(array $data): self
    {
        $u = new self($data['nom'], $data['prenom'], $data['email'], $data['password'], $data['role']);
        $u->id         = (int)$data['id'];
        $u->password   = $data['password'];
        $u->created_at = $data['created_at'] ?? date('Y-m-d H:i:s');
        $u->updated_at = $data['updated_at'] ?? date('Y-m-d H:i:s');
        return $u;
    }
}