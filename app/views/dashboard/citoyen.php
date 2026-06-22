<?php require_once __DIR__ . '/../layout/header.php'; ?>
<?php require_once __DIR__ . '/../layout/navbar.php'; ?>

<div class="container mt-4">

    <h1>Mon espace citoyen</h1>

    <a href="index.php?controller=signalement&action=ajouter" class="btn btn-success mb-3">
        Nouveau signalement
    </a>

    <h3>Mes signalements</h3>

    <?php if (!empty($signalements)): ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Adresse</th>
                    <th>Catégorie</th>
                    <th>Statut</th>
                    <th>Priorité</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($signalements as $signalement): ?>
                    <tr>
                        <td><?= htmlspecialchars($signalement['titre'] ?? '') ?></td>
                        <td><?= htmlspecialchars($signalement['adresse'] ?? '') ?></td>
                        <td><?= htmlspecialchars($signalement['categorie'] ?? '') ?></td>
                        <td><?= htmlspecialchars($signalement['statut'] ?? '') ?></td>
                        <td><?= htmlspecialchars($signalement['priorite'] ?? '') ?></td>
                        <td><?= htmlspecialchars($signalement['created_at'] ?? '') ?></td>
                        <td>
                            <a href="index.php?controller=signalement&action=detail&id=<?= htmlspecialchars($signalement['id'] ?? '') ?>"
                               class="btn btn-primary btn-sm">
                                Voir
                            </a>

                            <a href="index.php?controller=signalement&action=modifier&id=<?= htmlspecialchars($signalement['id'] ?? '') ?>"
                               class="btn btn-warning btn-sm">
                                Modifier
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">
            Vous n’avez encore créé aucun signalement.
        </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>