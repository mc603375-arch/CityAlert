<?php $pageTitle = "Espace agent — CityAlert"; ?>
<?php require_once VIEW_PATH . '/layout/header.php'; ?>
<?php require_once VIEW_PATH . '/layout/navbar.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>🛠 Espace agent — <?= htmlspecialchars($_SESSION['user']['prenom']) ?></h1>
    </div>

    <h2>Signalements à traiter (<?= count($signalements) ?>)</h2>

    <?php if (!empty($signalements)): ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr><th>Titre</th><th>Adresse</th><th>Catégorie</th><th>Statut</th><th>Priorité</th><th>Date</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($signalements as $sig): ?>
                        <tr>
                            <td><?= htmlspecialchars($sig['titre']) ?></td>
                            <td><?= htmlspecialchars($sig['adresse']) ?></td>
                            <td><?= htmlspecialchars($sig['categorie'] ?? '') ?></td>
                            <td><span class="badge badge-<?= $sig['statut'] ?>"><?= ucfirst(str_replace('_',' ',$sig['statut'])) ?></span></td>
                            <td><span class="badge badge-priorite-<?= $sig['priorite'] ?>"><?= ucfirst($sig['priorite']) ?></span></td>
                            <td><?= date('d/m/Y', strtotime($sig['created_at'])) ?></td>
                            <td><a href="<?= BASE_URL ?>/signalements/detail?id=<?= $sig['id'] ?>" class="btn btn-sm btn-primary">Traiter</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Aucun signalement à traiter.</div>
    <?php endif; ?>
</div>

<?php require_once VIEW_PATH . '/layout/footer.php'; ?>