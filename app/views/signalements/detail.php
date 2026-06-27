<?php $pageTitle = htmlspecialchars($signalement['titre']) . " — CityAlert"; ?>
<?php require_once VIEW_PATH . '/layout/header.php'; ?>
<?php require_once VIEW_PATH . '/layout/navbar.php'; ?>

<div class="container">
    <div class="page-header">
        <h1 style="font-size:1.3rem"><?= htmlspecialchars($signalement['titre']) ?></h1>
        <a href="<?= BASE_URL ?>/signalements" class="btn btn-outline btn-sm">← Retour</a>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($erreur)): ?>
        <div class="alert alert-danger">⚠️ <?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:1fr 340px;gap:1.5rem">

        <!-- Colonne principale -->
        <div>
            <div class="card">
                <div class="card-body">
                    <div class="detail-meta">
                        <span class="badge badge-<?= $signalement['statut'] ?>">
                            <span class="badge-dot"></span>
                            <?= ucfirst(str_replace('_',' ',$signalement['statut'])) ?>
                        </span>
                        <span class="badge badge-priorite-<?= $signalement['priorite'] ?>">
                            <?= ucfirst($signalement['priorite']) ?>
                        </span>
                        <span class="text-muted">
                            📅 <?= date('d/m/Y à H:i', strtotime($signalement['created_at'])) ?>
                        </span>
                    </div>

                    <div style="display:grid;gap:.75rem;margin-bottom:1.25rem">
                        <p>
                            <span style="font-size:.75rem;font-weight:700;text-transform:uppercase;color:var(--text-muted);letter-spacing:.06em">Adresse</span><br>
                            <span style="font-size:.9rem">📍 <?= htmlspecialchars($signalement['adresse']) ?></span>
                        </p>
                        <p>
                            <span style="font-size:.75rem;font-weight:700;text-transform:uppercase;color:var(--text-muted);letter-spacing:.06em">Catégorie</span><br>
                            <span style="font-size:.9rem">🗂 <?= htmlspecialchars($signalement['categorie_libelle'] ?? '') ?></span>
                        </p>
                        <p>
                            <span style="font-size:.75rem;font-weight:700;text-transform:uppercase;color:var(--text-muted);letter-spacing:.06em">Signalé par</span><br>
                            <span style="font-size:.9rem">👤 <?= htmlspecialchars($signalement['citoyen_prenom'] . ' ' . $signalement['citoyen_nom']) ?></span>
                        </p>
                    </div>

                    <div style="border-top:1px solid var(--border-soft);padding-top:1rem">
                        <p style="font-size:.75rem;font-weight:700;text-transform:uppercase;color:var(--text-muted);letter-spacing:.06em;margin-bottom:.5rem">Description</p>
                        <p style="font-size:.9rem;line-height:1.7;color:var(--text)"><?= nl2br(htmlspecialchars($signalement['description'])) ?></p>
                    </div>

                    <?php if (!empty($signalement['photo'])): ?>
                        <div style="margin-top:1.25rem">
                            <p style="font-size:.75rem;font-weight:700;text-transform:uppercase;color:var(--text-muted);letter-spacing:.06em;margin-bottom:.6rem">Photo</p>
                            <img src="<?= UPLOAD_URL . '/' . htmlspecialchars($signalement['photo']) ?>"
                                 alt="Photo du signalement" class="img-detail"
                                 style="max-height:320px;object-fit:cover;width:100%">
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($signalement['lat']) && !empty($signalement['lng'])): ?>
                        <div style="margin-top:1.25rem">
                            <p style="font-size:.75rem;font-weight:700;text-transform:uppercase;color:var(--text-muted);letter-spacing:.06em;margin-bottom:.6rem">Localisation</p>
                            <div id="map-detail" class="map-mini"></div>
                        </div>
                    <?php endif; ?>

                    <div style="margin-top:1.25rem;padding-top:1rem;border-top:1px solid var(--border-soft)">
                        <p class="text-muted" style="font-size:.8rem">
                            ℹ️ <?= htmlspecialchars($categorie->getComportementSpecifique()) ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Commentaires -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/></svg>
                        Commentaires (<?= count($commentaires) ?>)
                    </h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($commentaires)): ?>
                        <?php foreach ($commentaires as $com): ?>
                            <div class="comment-item">
                                <div class="comment-header">
                                    <strong><?= htmlspecialchars($com['prenom'] . ' ' . $com['nom']) ?></strong>
                                    <span class="badge badge-role"><?= ucfirst($com['role']) ?></span>
                                    <small class="text-muted"><?= date('d/m/Y H:i', strtotime($com['created_at'])) ?></small>
                                </div>
                                <p style="font-size:.88rem;color:var(--text)"><?= nl2br(htmlspecialchars($com['contenu'])) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted" style="text-align:center;padding:1rem 0">Aucun commentaire pour l'instant.</p>
                    <?php endif; ?>

                    <form method="POST" action="<?= BASE_URL ?>/commentaires/ajouter" class="comment-form">
                        <input type="hidden" name="signalement_id" value="<?= $signalement['id'] ?>">
                        <div class="form-group">
                            <label for="contenu">Ajouter un commentaire</label>
                            <textarea id="contenu" name="contenu" rows="3" placeholder="Votre message..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:13px;height:13px"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/></svg>
                            Publier
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div>
            <!-- Changer statut (agent/admin) -->
            <?php if ($_SESSION['user']['role'] !== ROLE_CITOYEN && !in_array($signalement['statut'], ['resolu','rejete'])): ?>
                <div class="card">
                    <div class="card-header">
                        <h3>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                            Changer le statut
                        </h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?= BASE_URL ?>/signalements/statut">
                            <input type="hidden" name="id" value="<?= $signalement['id'] ?>">
                            <div class="form-group">
                                <label for="nouveau_statut">Nouveau statut</label>
                                <select id="nouveau_statut" name="nouveau_statut" required>
                                    <option value="en_cours" <?= $signalement['statut']==='en_cours'?'selected':'' ?>>En cours</option>
                                    <option value="resolu">Résolu</option>
                                    <option value="rejete">Rejeté</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="commentaire">Remarque</label>
                                <textarea id="commentaire" name="commentaire" rows="3" placeholder="Commentaire de traitement..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-full">Valider le changement</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Actions citoyen -->
            <?php if ($_SESSION['user']['role'] === ROLE_CITOYEN && $signalement['statut'] === 'nouveau' && (int)$signalement['user_id'] === (int)$_SESSION['user']['id']): ?>
                <div class="card">
                    <div class="card-header"><h3>⚙️ Actions</h3></div>
                    <div class="card-body" style="display:flex;flex-direction:column;gap:.6rem">
                        <a href="<?= BASE_URL ?>/signalements/modifier?id=<?= $signalement['id'] ?>" class="btn btn-warning btn-full">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
                            Modifier
                        </a>
                        <a href="<?= BASE_URL ?>/signalements/supprimer?id=<?= $signalement['id'] ?>" class="btn btn-danger btn-full"
                           onclick="return confirm('Supprimer définitivement ce signalement ?')">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916"/></svg>
                            Supprimer
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Historique -->
            <?php if (!empty($historique)): ?>
                <div class="card mt-3">
                    <div class="card-header">
                        <h3>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            Historique
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php foreach ($historique as $h): ?>
                            <div class="historique-item">
                                <div style="display:flex;align-items:center;gap:.4rem;flex-wrap:wrap">
                                    <span class="badge badge-<?= $h['ancien_statut'] ?>" style="font-size:.65rem">
                                        <?= ucfirst(str_replace('_',' ',$h['ancien_statut'])) ?>
                                    </span>
                                    <span style="color:var(--text-muted);font-size:.75rem">→</span>
                                    <span class="badge badge-<?= $h['nouveau_statut'] ?>" style="font-size:.65rem">
                                        <?= ucfirst(str_replace('_',' ',$h['nouveau_statut'])) ?>
                                    </span>
                                </div>
                                <div style="font-size:.72rem;color:var(--text-muted);margin-top:.3rem">
                                    Par <?= htmlspecialchars($h['agent_prenom'] . ' ' . $h['agent_nom']) ?>
                                    — <?= date('d/m/Y H:i', strtotime($h['created_at'])) ?>
                                </div>
                                <?php if (!empty($h['commentaire'])): ?>
                                    <p class="historique-com"><?= htmlspecialchars($h['commentaire']) ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (!empty($signalement['lat']) && !empty($signalement['lng'])): ?>
<script>
window.addEventListener('load', () => {
    const map = L.map('map-detail').setView([<?= $signalement['lat'] ?>, <?= $signalement['lng'] ?>], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    L.marker([<?= $signalement['lat'] ?>, <?= $signalement['lng'] ?>])
     .addTo(map)
     .bindPopup('<?= addslashes(htmlspecialchars($signalement['titre'])) ?>')
     .openPopup();
});
</script>
<?php endif; ?>

<?php require_once VIEW_PATH . '/layout/footer.php'; ?>