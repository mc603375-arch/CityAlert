<?php
require_once ROOT_PATH . '/app/models/repositories/CommentaireRepository.php';

class CommentaireController
{
    public function ajouter(): void
    {
        if (!isset($_SESSION['user'])) { header('Location: ' . BASE_URL . '/login'); exit; }

        $contenu       = trim($_POST['contenu']        ?? '');
        $signalementId = (int)($_POST['signalement_id'] ?? 0);

        if (empty($contenu) || $signalementId <= 0) {
            header('Location: ' . BASE_URL . '/signalements/detail?id=' . $signalementId); exit;
        }

        (new CommentaireRepository())->save([
            'contenu'        => htmlspecialchars($contenu),
            'signalement_id' => $signalementId,
            'user_id'        => $_SESSION['user']['id'],
        ]);

        header('Location: ' . BASE_URL . '/signalements/detail?id=' . $signalementId); exit;
    }
}