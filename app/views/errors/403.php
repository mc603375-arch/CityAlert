<?php $pageTitle = "403 — Accès refusé"; ?>
<?php require_once VIEW_PATH . '/layout/header.php'; ?>
<div class="container error-page">
    <h1>🚫 403 — Accès refusé</h1>
    <p>Vous n'avez pas les droits pour accéder à cette page.</p>
    <a href="<?= BASE_URL ?>/login" class="btn btn-primary">Retour à l'accueil</a>
</div>
<?php require_once VIEW_PATH . '/layout/footer.php'; ?>