<?php require_once __DIR__ . '/../layout/header.php'; ?>
<?php require_once __DIR__ . '/../layout/navbar.php'; ?>

<div class="container mt-4">

    <h1>Modifier le signalement</h1>

    <form action="index.php?controller=signalement&action=update&id=<?= htmlspecialchars($signalement['id'] ?? '') ?>"
          method="POST"
          enctype="multipart/form-data">

        <div class="mb-3">
            <label for="titre" class="form-label">Titre</label>
            <input type="text"
                   name="titre"
                   id="titre"
                   class="form-control"
                   value="<?= htmlspecialchars($signalement['titre'] ?? '') ?>"
                   required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description"
                      id="description"
                      class="form-control"
                      rows="5"
                      required><?= htmlspecialchars($signalement['description'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
            <label for="adresse" class="form-label">Adresse</label>
            <input type="text"
                   name="adresse"
                   id="adresse"
                   class="form-control"
                   value="<?= htmlspecialchars($signalement['adresse'] ?? '') ?>"
                   required>
        </div>

        <div class="mb-3">
            <label for="categorie_id" class="form-label">Catégorie</label>
            <select name="categorie_id" id="categorie_id" class="form-select" required>
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $categorie): ?>
                        <option value="<?= htmlspecialchars($categorie['id']) ?>"
                            <?= isset($signalement['categorie_id']) && $signalement['categorie_id'] == $categorie['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($categorie['libelle']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="priorite" class="form-label">Priorité</label>
            <select name="priorite" id="priorite" class="form-select" required>
                <?php
                $priorites = ['basse', 'normale', 'haute', 'urgente'];
                foreach ($priorites as $priorite):
                ?>
                    <option value="<?= $priorite ?>"
                        <?= isset($signalement['priorite']) && $signalement['priorite'] === $priorite ? 'selected' : '' ?>>
                        <?= ucfirst($priorite) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if (!empty($signalement['photo'])): ?>
            <div class="mb-3">
                <p><strong>Photo actuelle :</strong></p>
                <img src="public/uploads/<?= htmlspecialchars($signalement['photo']) ?>"
                     alt="Photo actuelle"
                     class="img-fluid rounded"
                     style="max-width: 300px;">
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <label for="photo" class="form-label">Changer la photo</label>
            <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-warning">Mettre à jour</button>

        <a href="index.php?controller=signalement&action=liste" class="btn btn-secondary">
            Annuler
        </a>

    </form>

</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>