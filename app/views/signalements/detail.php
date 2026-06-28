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
                            <span style="font-size:.9rem">👤 <?= htmlspecialchars(($signalement['citoyen_prenom'] ?? '') . ' ' . ($signalement['citoyen_nom'] ?? '')) ?></span>
                        </p>

                        <?php if (!empty($signalement['agent_nom'])): ?>
                        <p>
                            <span style="font-size:.75rem;font-weight:700;text-transform:uppercase;color:var(--text-muted);letter-spacing:.06em">Agent assigné</span><br>
                            <div style="display:flex;align-items:center;gap:8px;margin-top:4px">
                                <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--accent));display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:12px">
                                    <?= strtoupper(substr($signalement['agent_prenom'] ?? '', 0, 1) . substr($signalement['agent_nom'] ?? '', 0, 1)) ?>
                                </div>
                                <div>
                                    <div style="font-size:.9rem;font-weight:600">
                                        🔧 <?= htmlspecialchars($signalement['agent_prenom'] . ' ' . $signalement['agent_nom']) ?>
                                    </div>
                                    <div style="font-size:.75rem;color:var(--text-muted)">
                                        <?php
                                            $agentEmail = $signalement['agent_email'] ?? '';
                                            if (str_contains($agentEmail, 'voirie'))        echo '🛣️ Agent Voirie';
                                            elseif (str_contains($agentEmail, 'eclairage')) echo '💡 Agent Éclairage';
                                            elseif (str_contains($agentEmail, 'dechets'))   echo '🗑️ Agent Déchets';
                                            elseif (str_contains($agentEmail, 'eau'))       echo '💧 Agent Eau & Assainissement';
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </p>
                        <?php endif; ?>
                    </div>

                    <div style="border-top:1px solid var(--border-soft);padding-top:1rem">
                        <p style="font-size:.75rem;font-weight:700;text-transform:uppercase;color:var(--text-muted);letter-spacing:.06em;margin-bottom:.5rem">Description</p>
                        <p style="font-size:.9rem;line-height:1.7;color:var(--text)"><?= nl2br(htmlspecialchars($signalement['description'])) ?></p>
                    </div>

                    <?php if (!empty($signalement['photo'])): ?>
                        <div style="margin-top:1.25rem">
                            <p style="font-size:.75rem;font-weight:700;text-transform:uppercase;color:var(--text-muted);letter-spacing:.06em;margin-bottom:.6rem">Photo</p>
                            <img src="<?= UPLOAD_URL . '/' . htmlspecialchars($signalement['photo']) ?>"
                                 alt="Photo du signalement"
                                 style="max-height:320px;object-fit:cover;width:100%;border-radius:12px">
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($signalement['lat']) && !empty($signalement['lng'])): ?>
                        <div style="margin-top:1.25rem">
                            <p style="font-size:.75rem;font-weight:700;text-transform:uppercase;color:var(--text-muted);letter-spacing:.06em;margin-bottom:.6rem">Localisation</p>
                            <div id="map-detail" style="height:220px;border-radius:12px;overflow:hidden;border:1.5px solid var(--border)"></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Commentaires -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3>💬 Commentaires (<?= count($commentaires) ?>)</h3>
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

                    <form method="POST" action="<?= BASE_URL ?>/commentaires/ajouter" style="margin-top:1rem">
                        <input type="hidden" name="signalement_id" value="<?= $signalement['id'] ?>">
                        <div class="form-group">
                            <label for="contenu">Ajouter un commentaire</label>
                            <textarea id="contenu" name="contenu" rows="3"
                                placeholder="Votre message..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Publier</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div style="display:flex;flex-direction:column;gap:1rem">

            <!-- ASSIGNER UN AGENT (admin seulement) -->
            <?php if ($_SESSION['user']['role'] === ROLE_ADMIN): ?>
                <div class="card">
                    <div class="card-header">
                        <h3>👤 Assigner un agent</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($signalement['agent_nom'])): ?>
                            <div style="background:var(--success-light);border-radius:8px;padding:10px 14px;margin-bottom:14px;font-size:.85rem;color:var(--success);font-weight:600">
                                ✅ Actuellement : <?= htmlspecialchars($signalement['agent_prenom'] . ' ' . $signalement['agent_nom']) ?>
                            </div>
                        <?php endif; ?>
                        <form method="POST" action="<?= BASE_URL ?>/signalements/assigner">
                            <input type="hidden" name="id" value="<?= $signalement['id'] ?>">
                            <div class="form-group">
                                <label for="agent_id">Choisir un agent</label>
                                <select id="agent_id" name="agent_id" required>
                                    <option value="">-- Sélectionner --</option>
                                    <?php foreach ($agents ?? [] as $agent): ?>
                                        <option value="<?= $agent['id'] ?>"
                                            <?= (int)($signalement['agent_id'] ?? 0) === (int)$agent['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($agent['prenom'] . ' ' . $agent['nom']) ?>
                                            <?php
                                                $email = $agent['email'] ?? '';
                                                if (str_contains($email, 'voirie'))        echo '— 🛣️ Voirie';
                                                elseif (str_contains($email, 'eclairage')) echo '— 💡 Éclairage';
                                                elseif (str_contains($email, 'dechets'))   echo '— 🗑️ Déchets';
                                                elseif (str_contains($email, 'eau'))       echo '— 💧 Eau & Assainis.';
                                            ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-full">
                                ✅ Assigner l'agent
                            </button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <!-- CHANGER STATUT (agent/admin) -->
            <?php if ($_SESSION['user']['role'] !== ROLE_CITOYEN && !in_array($signalement['statut'], ['resolu','rejete'])): ?>
                <div class="card">
                    <div class="card-header">
                        <h3>🔄 Changer le statut</h3>
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
                                <textarea id="commentaire" name="commentaire" rows="3"
                                    placeholder="Commentaire de traitement..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-full">
                                Valider le changement
                            </button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <!-- ACTIONS CITOYEN -->
            <?php if ($_SESSION['user']['role'] === ROLE_CITOYEN
                   && $signalement['statut'] === 'nouveau'
                   && (int)$signalement['user_id'] === (int)$_SESSION['user']['id']): ?>
                <div class="card">
                    <div class="card-header"><h3>⚙️ Actions</h3></div>
                    <div class="card-body" style="display:flex;flex-direction:column;gap:.6rem">
                        <a href="<?= BASE_URL ?>/signalements/modifier?id=<?= $signalement['id'] ?>"
                           class="btn btn-warning btn-full">✏️ Modifier</a>
                        <a href="<?= BASE_URL ?>/signalements/supprimer?id=<?= $signalement['id'] ?>"
                           class="btn btn-danger btn-full"
                           onclick="return confirm('Supprimer définitivement ce signalement ?')">
                           🗑️ Supprimer
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- HISTORIQUE -->
            <?php if (!empty($historique)): ?>
                <div class="card">
                    <div class="card-header">
                        <h3>🕐 Historique</h3>
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
                                    Par <?= htmlspecialchars(($h['agent_prenom'] ?? '') . ' ' . ($h['agent_nom'] ?? '')) ?>
                                    — <?= date('d/m/Y H:i', strtotime($h['created_at'])) ?>
                                </div>
                                <?php if (!empty($h['commentaire'])): ?>
                                    <p style="font-size:.8rem;color:var(--text);margin-top:.3rem;padding:.5rem;background:var(--surface-2);border-radius:6px">
                                        <?= htmlspecialchars($h['commentaire']) ?>
                                    </p>
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