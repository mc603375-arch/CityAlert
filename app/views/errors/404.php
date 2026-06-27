<?php $pageTitle = "404 — Page introuvable"; ?>
<?php require_once VIEW_PATH . '/layout/header.php'; ?>
<?php require_once VIEW_PATH . '/layout/navbar.php'; ?>
<div class="container error-page">
    <div style="font-size:4rem;margin-bottom:1rem">🔍</div>
    <h1>Page introuvable</h1>
    <p>La page que vous cherchez n'existe pas ou a été déplacée.</p>
    <a href="<?= BASE_URL ?>/login" class="btn btn-primary btn-lg">Retour à l'accueil</a>
</div>
<?php require_once VIEW_PATH . '/layout/footer.php'; ?>