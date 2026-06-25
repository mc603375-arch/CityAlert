<?php $pageTitle = "Modifier — CityAlert"; ?>
<?php require_once VIEW_PATH . '/layout/header.php'; ?>
<?php require_once VIEW_PATH . '/layout/navbar.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>✏️ Modifier le signalement</h1>
        <a href="<?= BASE_URL ?>/signalements/detail?id=<?= $signalement['id'] ?>" class="btn btn-outline">← Retour</a>
    </div>

    <?php if (!empty($erreur)): ?>
        <div class="alert alert-danger">⚠️ <?= $erreur ?></div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" action="<?= BASE_URL ?>/signalements/modifier?id=<?= $signalement['id'] ?>" enctype="multipart/form-data">
            <div class="form-group">
                <label for="titre">Titre *</label>
                <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($signalement['titre']) ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description" rows="4" required><?= htmlspecialchars($signalement['description']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="adresse">Adresse *</label>
                <input type="text" id="adresse" name="adresse" value="<?= htmlspecialchars($signalement['adresse']) ?>" required>
            </div>
            <div class="form-group">
                <label for="categorie_id">Catégorie *</label>
                <select id="categorie_id" name="categorie_id" required>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $signalement['categorie_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['libelle']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if (!empty($signalement['photo'])): ?>
                <div class="form-group">
                    <label>Photo actuelle</label>
                    <img src="<?= UPLOAD_URL . '/' . htmlspecialchars($signalement['photo']) ?>" alt="Photo" class="img-preview">
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label for="photo">Changer la photo</label>
                <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp">
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-warning">Mettre à jour</button>
                <a href="<?= BASE_URL ?>/signalements/detail?id=<?= $signalement['id'] ?>" class="btn btn-outline">Annuler</a>
            </div>
        </form>
    </div>
</div>

<?php require_once VIEW_PATH . '/layout/footer.php'; ?>