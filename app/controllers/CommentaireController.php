<?php

require_once __DIR__ . '/../../config/Database.php';

class CommentaireController
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function ajouter()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=signalement&action=liste');
            exit;
        }

        $contenu = trim($_POST['contenu'] ?? '');
        $signalementId = (int) ($_POST['signalement_id'] ?? 0);
        $userId = (int) $_SESSION['user']['id'];

        if ($contenu === '' || $signalementId <= 0) {
            header('Location: index.php?controller=signalement&action=detail&id=' . $signalementId);
            exit;
        }

        $sql = "INSERT INTO commentaires (contenu, signalement_id, user_id)
                VALUES (:contenu, :signalement_id, :user_id)";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':contenu' => $contenu,
            ':signalement_id' => $signalementId,
            ':user_id' => $userId
        ]);

        header('Location: index.php?controller=signalement&action=detail&id=' . $signalementId);
        exit;
    }
}