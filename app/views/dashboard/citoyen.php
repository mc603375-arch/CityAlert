<?php $pageTitle = "Mon espace — CityAlert"; ?>
<?php require_once VIEW_PATH . '/layout/header.php'; ?>
<?php require_once VIEW_PATH . '/layout/navbar.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>👤 Bonjour, <?= htmlspecialchars($_SESSION['user']['prenom']) ?> !</h1>
        <a href="<?= BASE_URL ?>/signalements/ajouter" class="btn btn-primary">+ Nouveau signalement</a>
    </div>

    <h2>Mes signalements (<?= count($signalements) ?>)</h2>

    <?php if (!empty($signalements)): ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr><th>Titre</th><th>Catégorie</th><th>Statut</th><th>Date</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($signalements as $sig): ?>
                        <tr>
                            <td><?= htmlspecialchars($sig['titre']) ?></td>
                            <td><?= htmlspecialchars($sig['categorie'] ?? '') ?></td>
                            <td><span class="badge badge-<?= $sig['statut'] ?>"><?= ucfirst(str_replace('_',' ',$sig['statut'])) ?></span></td>
                            <td><?= date('d/m/Y', strtotime($sig['created_at'])) ?></td>
                            <td><a href="<?= BASE_URL ?>/signalements/detail?id=<?= $sig['id'] ?>" class="btn btn-sm btn-primary">Voir</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Vous n'avez encore créé aucun signalement. <a href="<?= BASE_URL ?>/signalements/ajouter">Créer le premier</a></div>
    <?php endif; ?>
</div>

<?php require_once VIEW_PATH . '/layout/footer.php'; ?>