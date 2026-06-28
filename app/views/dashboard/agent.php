<?php $pageTitle = "Espace agent — CityAlert"; ?>
<?php require_once VIEW_PATH . '/layout/header.php'; ?>
<?php require_once VIEW_PATH . '/layout/navbar.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>🛠 Bonjour, <span class="hi"><?= htmlspecialchars($_SESSION['user']['prenom']) ?></span></h1>
    </div>

    <?php
    $nbEnCours = count(array_filter($signalements, fn($s) => $s['statut'] === 'en_cours'));
    $nbResolu  = count(array_filter($signalements, fn($s) => $s['statut'] === 'resolu'));
    ?>

    <!-- Stats cliquables -->
    <div class="stats-grid" style="grid-template-columns:repeat(auto-fit,minmax(160px,1fr));margin-bottom:2rem">

        <a href="<?= BASE_URL ?>/signalements" class="stat-card" style="text-decoration:none;cursor:pointer">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12"/></svg>
            </div>
            <div class="stat-value"><?= count($signalements) ?></div>
            <div class="stat-label">Mes signalements</div>
        </a>

        <a href="<?= BASE_URL ?>/signalements?statut=en_cours" class="stat-card stat-orange" style="text-decoration:none;cursor:pointer">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
            </div>
            <div class="stat-value"><?= $nbEnCours ?></div>
            <div class="stat-label">À traiter</div>
        </a>

        <a href="<?= BASE_URL ?>/signalements?statut=resolu" class="stat-card stat-green" style="text-decoration:none;cursor:pointer">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            </div>
            <div class="stat-value"><?= $nbResolu ?></div>
            <div class="stat-label">Résolus</div>
        </a>

    </div>

    <!-- Table signalements -->
    <div class="card">
        <div class="card-header">
            <h3>🔧 Signalements assignés</h3>
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
                            <th>Citoyen</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($signalements as $sig): ?>
                            <tr>
                                <td><strong style="font-size:.85rem"><?= htmlspecialchars($sig['titre']) ?></strong></td>
                                <td style="font-size:.8rem;color:var(--text-soft)"><?= htmlspecialchars(substr($sig['adresse'] ?? '', 0, 30)) ?></td>
                                <td style="font-size:.8rem"><?= htmlspecialchars($sig['categorie_libelle'] ?? $sig['categorie'] ?? '') ?></td>
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
                                <td style="font-size:.8rem">
                                    👤 <?= htmlspecialchars(($sig['citoyen_prenom'] ?? '') . ' ' . ($sig['citoyen_nom'] ?? '')) ?>
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
                <p style="color:var(--text-muted)">Aucun signalement assigné pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once VIEW_PATH . '/layout/footer.php'; ?>