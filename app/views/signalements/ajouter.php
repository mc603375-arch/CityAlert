<?php $pageTitle = "Nouveau signalement — CityAlert"; ?>
<?php require_once VIEW_PATH . '/layout/header.php'; ?>
<?php require_once VIEW_PATH . '/layout/navbar.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>📝 Nouveau signalement</h1>
        <a href="<?= BASE_URL ?>/signalements" class="btn btn-outline">← Retour</a>
    </div>

    <?php if (!empty($erreur)): ?>
        <div class="alert alert-danger">⚠️ <?= $erreur ?></div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" action="<?= BASE_URL ?>/signalements/ajouter" enctype="multipart/form-data">
            <div class="form-group">
                <label for="titre">Titre *</label>
                <input type="text" id="titre" name="titre" placeholder="Ex: Nid-de-poule dangereux" required>
            </div>
            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description" rows="4" placeholder="Décrivez le problème en détail..." required></textarea>
            </div>
            <div class="form-group">
                <label for="adresse">Adresse *</label>
                <input type="text" id="adresse" name="adresse" placeholder="Ex: Rue 10, Médina, Dakar" required>
            </div>
            <div class="form-group">
                <label for="categorie_id">Catégorie *</label>
                <select id="categorie_id" name="categorie_id" required>
                    <option value="">Choisir une catégorie</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['libelle']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="photo">Photo (optionnel)</label>
                <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp">
                <small>Formats acceptés : JPG, PNG, WEBP — Max 2Mo</small>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Envoyer le signalement</button>
                <a href="<?= BASE_URL ?>/signalements" class="btn btn-outline">Annuler</a>
            </div>
        </form>
    </div>
</div>

<?php require_once VIEW_PATH . '/layout/footer.php'; ?>