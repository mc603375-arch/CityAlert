<?php require_once __DIR__ . '/../layout/header.php'; ?>
<?php require_once __DIR__ . '/../layout/navbar.php'; ?>

<div class="container mt-4">

    <h1>Liste des signalements</h1>

    <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'citoyen'): ?>
        <a href="index.php?controller=signalement&action=ajouter" class="btn btn-success mb-3">
            Ajouter un signalement
        </a>
    <?php endif; ?>

    <?php if (!empty($signalements)): ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Adresse</th>
                    <th>Statut</th>
                    <th>Priorité</th>
                    <th>Catégorie</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($signalements as $signalement): ?>
                    <tr>
                        <td><?= htmlspecialchars($signalement['titre'] ?? '') ?></td>
                        <td><?= htmlspecialchars($signalement['adresse'] ?? '') ?></td>
                        <td><?= htmlspecialchars($signalement['statut'] ?? '') ?></td>
                        <td><?= htmlspecialchars($signalement['priorite'] ?? '') ?></td>
                        <td><?= htmlspecialchars($signalement['categorie'] ?? '') ?></td>
                        <td><?= htmlspecialchars($signalement['created_at'] ?? '') ?></td>
                        <td>
                            <a href="index.php?controller=signalement&action=detail&id=<?= htmlspecialchars($signalement['id']) ?>"
                               class="btn btn-primary btn-sm">
                                Voir
                            </a>

                            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'citoyen'): ?>
                                <a href="index.php?controller=signalement&action=modifier&id=<?= htmlspecialchars($signalement['id']) ?>"
                                   class="btn btn-warning btn-sm">
                                    Modifier
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php else: ?>
        <div class="alert alert-info">
            Aucun signalement trouvé.
        </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>