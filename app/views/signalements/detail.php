<?php $pageTitle = htmlspecialchars($signalement['titre']) . " — CityAlert"; ?>
<?php require_once VIEW_PATH . '/layout/header.php'; ?>
<?php require_once VIEW_PATH . '/layout/navbar.php'; ?>

<div class="container">
    <div class="page-header">
        <h1><?= htmlspecialchars($signalement['titre']) ?></h1>
        <a href="<?= BASE_URL ?>/signalements" class="btn btn-outline">← Retour</a>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($erreur)): ?>
        <div class="alert alert-danger">⚠️ <?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <div class="detail-grid">
        <div class="detail-main">
            <div class="card">
                <div class="card-body">
                    <div class="detail-meta">
                        <span class="badge badge-<?= $signalement['statut'] ?>"><?= ucfirst(str_replace('_',' ',$signalement['statut'])) ?></span>
                        <span class="badge badge-priorite-<?= $signalement['priorite'] ?>"><?= ucfirst($signalement['priorite']) ?></span>
                        <span class="text-muted">📅 <?= date('d/m/Y à H:i', strtotime($signalement['created_at'])) ?></span>
                    </div>
                    <p><strong>📍 Adresse :</strong> <?= htmlspecialchars($signalement['adresse']) ?></p>
                    <p><strong>🗂 Catégorie :</strong> <?= htmlspecialchars($signalement['categorie_libelle'] ?? '') ?></p>
                    <p><strong>👤 Signalé par :</strong> <?= htmlspecialchars($signalement['citoyen_prenom'] . ' ' . $signalement['citoyen_nom']) ?></p>
                    <p><strong>📝 Description :</strong></p>
                    <p><?= nl2br(htmlspecialchars($signalement['description'])) ?></p>
                    <?php if (!empty($signalement['photo'])): ?>
                        <img src="<?= UPLOAD_URL . '/' . htmlspecialchars($signalement['photo']) ?>" alt="Photo" class="img-detail">
                    <?php endif; ?>
                    <p class="text-muted">ℹ️ <?= $categorie->getComportementSpecifique() ?></p>
                </div>
            </div>

            <?php if ($_SESSION['user']['role'] !== ROLE_CITOYEN && $signalement['statut'] !== 'resolu' && $signalement['statut'] !== 'rejete'): ?>
                <div class="card mt-3">
                    <div class="card-header"><h3>🔄 Changer le statut</h3></div>
                    <div class="card-body">
                        <form method="POST" action="<?= BASE_URL ?>/signalements/statut">
                            <input type="hidden" name="id" value="<?= $signalement['id'] ?>">
                            <div class="form-group">
                                <label for="nouveau_statut">Nouveau statut</label>
                                <select id="nouveau_statut" name="nouveau_statut" required>
                                    <option value="en_cours">En cours</option>
                                    <option value="resolu">Résolu</option>
                                    <option value="rejete">Rejeté</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="commentaire">Commentaire</label>
                                <textarea id="commentaire" name="commentaire" rows="2" placeholder="Remarque de traitement..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Valider</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($historique)): ?>
                <div class="card mt-3">
                    <div class="card-header"><h3>📜 Historique</h3></div>
                    <div class="card-body">
                        <?php foreach ($historique as $h): ?>
                            <div class="historique-item">
                                <span class="badge badge-<?= $h['ancien_statut'] ?>"><?= ucfirst(str_replace('_',' ',$h['ancien_statut'])) ?></span>
                                → <span class="badge badge-<?= $h['nouveau_statut'] ?>"><?= ucfirst(str_replace('_',' ',$h['nouveau_statut'])) ?></span>
                                <small class="text-muted">par <?= htmlspecialchars($h['agent_prenom'] . ' ' . $h['agent_nom']) ?>
                                — <?= date('d/m/Y H:i', strtotime($h['created_at'])) ?></small>
                                <?php if (!empty($h['commentaire'])): ?>
                                    <p class="historique-com"><?= htmlspecialchars($h['commentaire']) ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card mt-3">
                <div class="card-header"><h3>💬 Commentaires (<?= count($commentaires) ?>)</h3></div>
                <div class="card-body">
                    <?php if (!empty($commentaires)): ?>
                        <?php foreach ($commentaires as $com): ?>
                            <div class="comment-item">
                                <div class="comment-header">
                                    <strong><?= htmlspecialchars($com['prenom'] . ' ' . $com['nom']) ?></strong>
                                    <span class="badge badge-role"><?= ucfirst($com['role']) ?></span>
                                    <small class="text-muted"><?= date('d/m/Y H:i', strtotime($com['created_at'])) ?></small>
                                </div>
                                <p><?= nl2br(htmlspecialchars($com['contenu'])) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">Aucun commentaire pour l'instant.</p>
                    <?php endif; ?>

                    <form method="POST" action="<?= BASE_URL ?>/commentaires/ajouter" class="comment-form">
                        <input type="hidden" name="signalement_id" value="<?= $signalement['id'] ?>">
                        <div class="form-group">
                            <label for="contenu">Ajouter un commentaire</label>
                            <textarea id="contenu" name="contenu" rows="3" placeholder="Votre commentaire..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Publier</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEW_PATH . '/layout/footer.php'; ?>