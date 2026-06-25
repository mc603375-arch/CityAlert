<?php $pageTitle = "Signalements — CityAlert"; ?>
<?php require_once VIEW_PATH . '/layout/header.php'; ?>
<?php require_once VIEW_PATH . '/layout/navbar.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>📋 Liste des signalements</h1>
        <?php if ($_SESSION['user']['role'] === ROLE_CITOYEN): ?>
            <a href="<?= BASE_URL ?>/signalements/ajouter" class="btn btn-primary">+ Nouveau signalement</a>
        <?php endif; ?>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($erreur)): ?>
        <div class="alert alert-danger">⚠️ <?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <form method="GET" action="<?= BASE_URL ?>/signalements" class="filter-form">
        <select name="statut">
            <option value="">Tous les statuts</option>
            <?php foreach (['nouveau','en_cours','resolu','rejete'] as $s): ?>
                <option value="<?= $s ?>" <?= ($filtres['statut'] ?? '') === $s ? 'selected' : '' ?>>
                    <?= ucfirst(str_replace('_', ' ', $s)) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <select name="categorie_id">
            <option value="">Toutes les catégories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($filtres['categorie_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['libelle']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-secondary">Filtrer</button>
        <a href="<?= BASE_URL ?>/signalements" class="btn btn-outline">Réinitialiser</a>
    </form>

    <?php if (!empty($signalements)): ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Statut</th>
                        <th>Priorité</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($signalements as $sig): ?>
                        <tr>
                            <td><?= htmlspecialchars($sig['titre']) ?></td>
                            <td><?= htmlspecialchars($sig['categorie_libelle'] ?? '') ?></td>
                            <td><span class="badge badge-<?= $sig['statut'] ?>"><?= ucfirst(str_replace('_', ' ', $sig['statut'])) ?></span></td>
                            <td><span class="badge badge-priorite-<?= $sig['priorite'] ?>"><?= ucfirst($sig['priorite']) ?></span></td>
                            <td><?= date('d/m/Y', strtotime($sig['created_at'])) ?></td>
                            <td>
                                <a href="<?= BASE_URL ?>/signalements/detail?id=<?= $sig['id'] ?>" class="btn btn-sm btn-primary">Voir</a>
                                <?php if ($sig['statut'] === 'nouveau' && (int)$sig['user_id'] === (int)$_SESSION['user']['id']): ?>
                                    <a href="<?= BASE_URL ?>/signalements/modifier?id=<?= $sig['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                                    <a href="<?= BASE_URL ?>/signalements/supprimer?id=<?= $sig['id'] ?>" class="btn btn-sm btn-danger"
                                       onclick="return confirm('Supprimer ce signalement ?')">Supprimer</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>&statut=<?= $filtres['statut'] ?>&categorie_id=<?= $filtres['categorie_id'] ?>"
                       class="btn btn-sm <?= $i === $page ? 'btn-primary' : 'btn-outline' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-info">Aucun signalement trouvé.</div>
    <?php endif; ?>
</div>

<?php require_once VIEW_PATH . '/layout/footer.php'; ?>