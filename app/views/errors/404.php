<?php $pageTitle = "404 — Page introuvable"; ?>
<?php require_once VIEW_PATH . '/layout/header.php'; ?>
<div class="container error-page">
    <h1>🔍 404 — Page introuvable</h1>
    <p>La page que vous cherchez n'existe pas.</p>
    <a href="<?= BASE_URL ?>/login" class="btn btn-primary">Retour à l'accueil</a>
</div>
<?php require_once VIEW_PATH . '/layout/footer.php'; ?>