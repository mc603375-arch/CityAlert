<?php require_once __DIR__ . '/../layout/header.php'; ?>
<?php require_once __DIR__ . '/../layout/navbar.php'; ?>

<div class="container mt-4">

    <h1><?= htmlspecialchars($signalement['titre'] ?? 'Détail du signalement') ?></h1>

    <div class="card mb-4">
        <div class="card-body">

            <p>
                <strong>Description :</strong><br>
                <?= nl2br(htmlspecialchars($signalement['description'] ?? '')) ?>
            </p>

            <p>
                <strong>Adresse :</strong>
                <?= htmlspecialchars($signalement['adresse'] ?? '') ?>
            </p>

            <p>
                <strong>Statut :</strong>
                <?= htmlspecialchars($signalement['statut'] ?? '') ?>
            </p>

            <p>
                <strong>Priorité :</strong>
                <?= htmlspecialchars($signalement['priorite'] ?? '') ?>
            </p>

            <p>
                <strong>Catégorie :</strong>
                <?= htmlspecialchars($signalement['categorie'] ?? '') ?>
            </p>

            <?php if (!empty($signalement['photo'])): ?>
                <div class="mt-3">
                    <img src="public/uploads/<?= htmlspecialchars($signalement['photo']) ?>"
                         alt="Photo du signalement"
                         class="img-fluid rounded"
                         style="max-width: 500px;">
                </div>
            <?php endif; ?>

        </div>
    </div>

    <h3>Commentaires</h3>

    <?php if (!empty($commentaires)): ?>
        <?php foreach ($commentaires as $commentaire): ?>
            <div class="card mb-2">
                <div class="card-body">
                    <strong>
                        <?= htmlspecialchars(($commentaire['prenom'] ?? '') . ' ' . ($commentaire['nom'] ?? '')) ?>
                    </strong>

                    <small class="text-muted">
                        <?= htmlspecialchars($commentaire['created_at'] ?? '') ?>
                    </small>

                    <p class="mb-0">
                        <?= nl2br(htmlspecialchars($commentaire['contenu'] ?? '')) ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun commentaire pour le moment.</p>
    <?php endif; ?>

    <?php if (isset($_SESSION['user'])): ?>
        <form action="index.php?controller=commentaire&action=ajouter" method="POST" class="mt-4">
            <input type="hidden" name="signalement_id" value="<?= htmlspecialchars($signalement['id'] ?? '') ?>">

            <div class="mb-3">
                <label for="contenu" class="form-label">Ajouter un commentaire</label>
                <textarea name="contenu" id="contenu" class="form-control" rows="3" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Publier</button>
        </form>
    <?php endif; ?>

    <a href="index.php?controller=signalement&action=liste" class="btn btn-secondary mt-3">
        Retour à la liste
    </a>

</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>