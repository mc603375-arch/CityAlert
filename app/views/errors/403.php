<?php $pageTitle = "403 — Accès refusé"; ?>
<?php require_once VIEW_PATH . '/layout/header.php'; ?>
<?php require_once VIEW_PATH . '/layout/navbar.php'; ?>
<div class="container error-page">
    <div style="font-size:4rem;margin-bottom:1rem">🚫</div>
    <h1>Accès refusé</h1>
    <p>Vous n'avez pas les droits pour accéder à cette page.</p>
    <a href="<?= BASE_URL ?>/login" class="btn btn-primary btn-lg">Retour à l'accueil</a>
</div>
<?php require_once VIEW_PATH . '/layout/footer.php'; ?>