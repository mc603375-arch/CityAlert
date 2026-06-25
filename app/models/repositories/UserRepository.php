<?php
require_once ROOT_PATH . '/interfaces/RepositoryInterface.php';
require_once ROOT_PATH . '/config/Database.php';
require_once ROOT_PATH . '/exceptions/EntityNotFoundException.php';

class UserRepository implements RepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnexion();
    }

    public function findAll(): array
    {
        return $this->db->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        if (!$data) throw new EntityNotFoundException('User', $id);
        return $data;
    }

    public function findByEmail(string $email): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function emailExiste(string $email): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function save(array $data): bool
    {
        if (empty($data['id'])) {
            $stmt = $this->db->prepare(
                "INSERT INTO users (nom, prenom, email, password, role) VALUES (:nom, :prenom, :email, :password, :role)"
            );
            return $stmt->execute([
                ':nom'      => $data['nom'],
                ':prenom'   => $data['prenom'],
                ':email'    => $data['email'],
                ':password' => $data['password'],
                ':role'     => $data['role'] ?? ROLE_CITOYEN,
            ]);
        }
        $stmt = $this->db->prepare(
            "UPDATE users SET nom=:nom, prenom=:prenom, email=:email, role=:role WHERE id=:id"
        );
        return $stmt->execute([
            ':nom'    => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email'  => $data['email'],
            ':role'   => $data['role'],
            ':id'     => $data['id'],
        ]);
    }

    public function delete(int $id): bool
    {
        return $this->db->prepare("DELETE FROM users WHERE id=:id")->execute([':id' => $id]);
    }
}