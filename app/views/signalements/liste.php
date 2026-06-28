<?php $pageTitle = "Signalements — CityAlert"; ?>
<?php require_once VIEW_PATH . '/layout/header.php'; ?>
<?php require_once VIEW_PATH . '/layout/navbar.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>📋 Signalements</h1>
        <?php if ($_SESSION['user']['role'] === ROLE_CITOYEN): ?>
            <a href="<?= BASE_URL ?>/signalements/ajouter" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Nouveau signalement
            </a>
        <?php endif; ?>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($erreur)): ?>
        <div class="alert alert-danger">⚠️ <?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <!-- Filtres -->
    <form method="GET" action="<?= BASE_URL ?>/signalements" class="filter-form">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:16px;height:16px;color:var(--text-muted);flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z"/></svg>
        <select name="statut">
            <option value="">Tous les statuts</option>
            <?php foreach (['nouveau'=>'Nouveau','en_cours'=>'En cours','resolu'=>'Résolu','rejete'=>'Rejeté'] as $val => $lib): ?>
                <option value="<?= $val ?>" <?= ($filtres['statut'] ?? '') === $val ? 'selected' : '' ?>><?= $lib ?></option>
            <?php endforeach; ?>
        </select>
        <select name="categorie_id">
            <option value="">Toutes catégories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($filtres['categorie_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['libelle']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
        <a href="<?= BASE_URL ?>/signalements" class="btn btn-outline btn-sm">Réinitialiser</a>
    </form>

    <?php if (!empty($signalements)): ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Statut</th>
                        <th>Priorité</th>
                        <th>Agent assigné</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($signalements as $sig): ?>
                        <tr>
                            <td style="color:var(--text-muted);font-size:.75rem">#<?= $sig['id'] ?></td>
                            <td>
                                <strong style="font-size:.85rem"><?= htmlspecialchars($sig['titre']) ?></strong>
                                <div style="font-size:.75rem;color:var(--text-muted);margin-top:.15rem">
                                    📍 <?= htmlspecialchars(substr($sig['adresse'] ?? '', 0, 40)) ?>
                                </div>
                            </td>
                            <td>
                                <span style="font-size:.8rem;color:var(--text-soft)">
                                    <?= htmlspecialchars($sig['categorie_libelle'] ?? '') ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-<?= $sig['statut'] ?>">
                                    <span class="badge-dot"></span>
                                    <?= ucfirst(str_replace('_',' ',$sig['statut'])) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-priorite-<?= $sig['priorite'] ?>">
                                    <?= ucfirst($sig['priorite']) ?>
                                </span>
                            </td>

                            <!-- Agent assigné -->
                            <td style="font-size:.78rem">
                                <?php if (!empty($sig['agent_nom'])): ?>
                                    <div style="display:flex;align-items:center;gap:6px">
                                        <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--accent));display:flex;align-items:center;justify-content:center;color:white;font-size:10px;font-weight:700;flex-shrink:0">
                                            <?= strtoupper(substr($sig['agent_prenom'] ?? '', 0, 1) . substr($sig['agent_nom'] ?? '', 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div style="font-weight:600;font-size:.8rem;color:var(--text)">
                                                <?= htmlspecialchars($sig['agent_prenom'] . ' ' . $sig['agent_nom']) ?>
                                            </div>
                                            <div style="font-size:.7rem;color:var(--text-muted)">
                                                <?php
                                                    $agentEmail = $sig['agent_email'] ?? '';
                                                    if (str_contains($agentEmail, 'voirie'))        echo '🛣️ Voirie';
                                                    elseif (str_contains($agentEmail, 'eclairage')) echo '💡 Éclairage';
                                                    elseif (str_contains($agentEmail, 'dechets'))   echo '🗑️ Déchets';
                                                    elseif (str_contains($agentEmail, 'eau'))       echo '💧 Eau & Assainissement';
                                                    else echo 'Agent';
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <span style="color:var(--text-muted);font-size:.75rem;font-style:italic">
                                        ⏳ Non assigné
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td style="font-size:.78rem;color:var(--text-muted);white-space:nowrap">
                                <?= date('d/m/Y', strtotime($sig['created_at'])) ?>
                            </td>
                            <td>
                                <div style="display:flex;gap:.4rem;flex-wrap:wrap">
                                    <a href="<?= BASE_URL ?>/signalements/detail?id=<?= $sig['id'] ?>" class="btn btn-sm btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:12px;height:12px"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                        Voir
                                    </a>
                                    <?php if ($sig['statut'] === 'nouveau' && (int)$sig['user_id'] === (int)$_SESSION['user']['id']): ?>
                                        <a href="<?= BASE_URL ?>/signalements/modifier?id=<?= $sig['id'] ?>" class="btn btn-sm btn-warning">✏️</a>
                                        <a href="<?= BASE_URL ?>/signalements/supprimer?id=<?= $sig['id'] ?>" class="btn btn-sm btn-danger"
                                           onclick="return confirm('Supprimer ce signalement ?')">🗑️</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>&statut=<?= urlencode($filtres['statut'] ?? '') ?>&categorie_id=<?= urlencode($filtres['categorie_id'] ?? '') ?>"
                       class="btn btn-sm <?= $i === $page ? 'btn-primary' : 'btn-outline' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="alert alert-info">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:18px;height:18px;flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/></svg>
            Aucun signalement trouvé.
            <?php if ($_SESSION['user']['role'] === ROLE_CITOYEN): ?>
                <a href="<?= BASE_URL ?>/signalements/ajouter" style="font-weight:600">Créer le premier →</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once VIEW_PATH . '/layout/footer.php'; ?>