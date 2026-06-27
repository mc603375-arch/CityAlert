<?php $pageTitle = "Espace agent — CityAlert"; ?>
<?php require_once VIEW_PATH . '/layout/header.php'; ?>
<?php require_once VIEW_PATH . '/layout/navbar.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>🛠 Bonjour, <span class="hi"><?= htmlspecialchars($_SESSION['user']['prenom']) ?></span></h1>
    </div>

    <?php
    $nbNouveau = count(array_filter($signalements, fn($s) => $s['statut'] === 'nouveau'));
    $nbEnCours = count(array_filter($signalements, fn($s) => $s['statut'] === 'en_cours'));
    ?>

    <div class="stats-grid" style="grid-template-columns:repeat(auto-fit,minmax(160px,1fr));margin-bottom:2rem">
        <div class="stat-card">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12"/></svg>
            </div>
            <div class="stat-value"><?= count($signalements) ?></div>
            <div class="stat-label">Signalements visibles</div>
        </div>
        <div class="stat-card stat-red">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
            </div>
            <div class="stat-value"><?= $nbNouveau ?></div>
            <div class="stat-label">À traiter</div>
        </div>
        <div class="stat-card stat-orange">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877"/></svg>
            </div>
            <div class="stat-value"><?= $nbEnCours ?></div>
            <div class="stat-label">En cours</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l5.654-4.654m5.546-4.647.166-.166a2.25 2.25 0 0 1 3.183 3.183l-.166.166m-5.349-5.349 3.166-3.166a2.25 2.25 0 0 1 3.183 3.183L16.766 9.57"/></svg>
                Signalements à traiter
            </h3>
        </div>

        <?php if (!empty($signalements)): ?>
            <div style="overflow-x:auto">
                <table class="table">
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
                        <?php foreach ($signalements as $sig): ?>
                            <tr>
                                <td>
                                    <strong style="font-size:.85rem"><?= htmlspecialchars($sig['titre']) ?></strong>
                                </td>
                                <td style="font-size:.8rem;color:var(--text-soft)">
                                    <?= htmlspecialchars(substr($sig['adresse'] ?? '', 0, 30)) ?>
                                </td>
                                <td style="font-size:.8rem"><?= htmlspecialchars($sig['categorie'] ?? '') ?></td>
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
                                <td style="font-size:.78rem;color:var(--text-muted);white-space:nowrap">
                                    <?= date('d/m/Y', strtotime($sig['created_at'])) ?>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>/signalements/detail?id=<?= $sig['id'] ?>" class="btn btn-sm btn-primary">
                                        Traiter
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="card-body" style="text-align:center;padding:3rem">
                <div style="font-size:3rem;margin-bottom:1rem">✅</div>
                <p style="color:var(--text-muted)">Aucun signalement à traiter pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once VIEW_PATH . '/layout/footer.php'; ?>