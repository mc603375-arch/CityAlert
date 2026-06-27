<?php $pageTitle = "Mon espace — CityAlert"; ?>
<?php require_once VIEW_PATH . '/layout/header.php'; ?>
<?php require_once VIEW_PATH . '/layout/navbar.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>
            Bonjour, <span class="hi"><?= htmlspecialchars($_SESSION['user']['prenom']) ?></span> 👋
        </h1>
        <a href="<?= BASE_URL ?>/signalements/ajouter" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Nouveau signalement
        </a>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <!-- Mini stats -->
    <?php
    $nbNouveau  = count(array_filter($signalements, fn($s) => $s['statut'] === 'nouveau'));
    $nbEnCours  = count(array_filter($signalements, fn($s) => $s['statut'] === 'en_cours'));
    $nbResolu   = count(array_filter($signalements, fn($s) => $s['statut'] === 'resolu'));
    ?>
    <div class="stats-grid" style="grid-template-columns:repeat(auto-fit,minmax(160px,1fr));margin-bottom:2rem">
        <div class="stat-card">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/></svg>
            </div>
            <div class="stat-value"><?= count($signalements) ?></div>
            <div class="stat-label">Total mes signalements</div>
        </div>
        <div class="stat-card stat-red">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            </div>
            <div class="stat-value"><?= $nbNouveau ?></div>
            <div class="stat-label">En attente</div>
        </div>
        <div class="stat-card stat-orange">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l5.654-4.654m5.546-4.647.166-.166a2.25 2.25 0 0 1 3.183 3.183l-.166.166m-5.349-5.349 3.166-3.166a2.25 2.25 0 0 1 3.183 3.183L16.766 9.57"/></svg>
            </div>
            <div class="stat-value"><?= $nbEnCours ?></div>
            <div class="stat-label">En cours de traitement</div>
        </div>
        <div class="stat-card stat-green">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            </div>
            <div class="stat-value"><?= $nbResolu ?></div>
            <div class="stat-label">Résolus</div>
        </div>
    </div>

    <!-- Liste des signalements -->
    <div class="card">
        <div class="card-header">
            <h3>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
                Mes signalements
            </h3>
        </div>

        <?php if (!empty($signalements)): ?>
            <div style="overflow-x:auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($signalements as $sig): ?>
                            <tr>
                                <td>
                                    <strong style="font-size:.85rem"><?= htmlspecialchars($sig['titre']) ?></strong>
                                    <div style="font-size:.73rem;color:var(--text-muted)">📍 <?= htmlspecialchars(substr($sig['adresse'] ?? '', 0, 35)) ?></div>
                                </td>
                                <td style="font-size:.82rem;color:var(--text-soft)"><?= htmlspecialchars($sig['categorie'] ?? '') ?></td>
                                <td>
                                    <span class="badge badge-<?= $sig['statut'] ?>">
                                        <span class="badge-dot"></span>
                                        <?= ucfirst(str_replace('_',' ',$sig['statut'])) ?>
                                    </span>
                                </td>
                                <td style="font-size:.78rem;color:var(--text-muted);white-space:nowrap">
                                    <?= date('d/m/Y', strtotime($sig['created_at'])) ?>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>/signalements/detail?id=<?= $sig['id'] ?>" class="btn btn-sm btn-primary">Voir</a>
                                    <?php if ($sig['statut'] === 'nouveau'): ?>
                                        <a href="<?= BASE_URL ?>/signalements/modifier?id=<?= $sig['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="card-body">
                <div style="text-align:center;padding:3rem 1rem">
                    <div style="font-size:3rem;margin-bottom:1rem">📭</div>
                    <p style="color:var(--text-muted);margin-bottom:1.25rem">Vous n'avez encore signalé aucun problème.</p>
                    <a href="<?= BASE_URL ?>/signalements/ajouter" class="btn btn-primary">
                        Faire mon premier signalement
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once VIEW_PATH . '/layout/footer.php'; ?>