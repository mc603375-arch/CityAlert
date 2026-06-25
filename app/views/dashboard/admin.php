<?php $pageTitle = "Dashboard admin — CityAlert"; ?>
<?php require_once VIEW_PATH . '/layout/header.php'; ?>
<?php require_once VIEW_PATH . '/layout/navbar.php'; ?>


<div class="container">
    <div class="page-header">
        <h1>⚙️ Dashboard administrateur</h1>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-value"><?= $totalUsers ?? 0 ?></div>
            <div class="stat-label">Utilisateurs</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📋</div>
            <div class="stat-value"><?= $totalSignalements ?? 0 ?></div>
            <div class="stat-label">Signalements</div>
        </div>
        <div class="stat-card stat-warning">
            <div class="stat-icon">🆕</div>
            <div class="stat-value"><?= $nouveaux ?? 0 ?></div>
            <div class="stat-label">Nouveaux</div>
        </div>
        <div class="stat-card stat-success">
            <div class="stat-icon">✅</div>
            <div class="stat-value"><?= $resolus ?? 0 ?></div>
            <div class="stat-label">Résolus</div>
        </div>
    </div>

    <?php if (!empty($parCategorie)): ?>
        <div class="card mt-4">
            <div class="card-header"><h3>📊 Signalements par catégorie</h3></div>
            <div class="card-body">
                <table class="table">
                    <thead><tr><th>Catégorie</th><th>Total</th></tr></thead>
                    <tbody>
                        <?php foreach ($parCategorie as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['libelle']) ?></td>
                                <td><?= $row['total'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <div class="mt-4">
        <a href="<?= BASE_URL ?>/signalements" class="btn btn-primary">Voir tous les signalements</a>
        <a href="<?= BASE_URL ?>/admin/utilisateurs" class="btn btn-secondary">Gérer les utilisateurs</a>
    </div>
</div>

<?php require_once VIEW_PATH . '/layout/footer.php'; ?>