<?php $pageTitle = "Dashboard — CityAlert"; ?>
<?php require_once VIEW_PATH . '/layout/header.php'; ?>
<?php require_once VIEW_PATH . '/layout/navbar.php'; ?>

<div class="container">
 <div class="page-header">
     <h1>Dashboard <span class="hi">Admin</span></h1>
     <div style="display:flex;gap:.75rem;flex-wrap:wrap">
         <a href="<?= BASE_URL ?>/admin/agents" class="btn btn-outline btn-sm">
             👮 Gérer les agents
         </a>
         <a href="<?= BASE_URL ?>/admin/citoyens" class="btn btn-outline btn-sm">
             👥 Gérer les citoyens
         </a>
         <a href="<?= BASE_URL ?>/export/csv" class="btn btn-outline btn-sm">
             ⬇️ Export CSV
         </a>
         <a href="<?= BASE_URL ?>/signalements" class="btn btn-primary btn-sm">
             Voir tous les signalements
         </a>
     </div>
 </div>

    <!-- Stats cards cliquables -->
    <div class="stats-grid">

        <a href="<?= BASE_URL ?>/admin/utilisateurs" class="stat-card" style="text-decoration:none;cursor:pointer">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
            </div>
            <div class="stat-value"><?= $totalUsers ?? 0 ?></div>
            <div class="stat-label">Utilisateurs inscrits</div>
        </a>

        <a href="<?= BASE_URL ?>/signalements" class="stat-card stat-orange" style="text-decoration:none;cursor:pointer">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
            </div>
            <div class="stat-value"><?= $totalSignalements ?? 0 ?></div>
            <div class="stat-label">Signalements total</div>
        </a>

        <a href="<?= BASE_URL ?>/signalements?statut=nouveau" class="stat-card stat-red" style="text-decoration:none;cursor:pointer">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            </div>
            <div class="stat-value"><?= $nouveaux ?? 0 ?></div>
            <div class="stat-label">En attente de traitement</div>
        </a>

        <a href="<?= BASE_URL ?>/signalements?statut=resolu" class="stat-card stat-green" style="text-decoration:none;cursor:pointer">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            </div>
            <div class="stat-value"><?= $resolus ?? 0 ?></div>
            <div class="stat-label">Signalements résolus</div>
        </a>

    </div>

    <!-- Graphiques -->
    <div class="charts-grid">
        <div class="chart-wrap">
            <div class="chart-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:15px;height:15px;color:var(--blue)"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
                Signalements par statut
            </div>
            <canvas id="chartStatut" height="200"></canvas>
        </div>
        <div class="chart-wrap">
            <div class="chart-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:15px;height:15px;color:var(--orange)"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z"/></svg>
                Par catégorie
            </div>
            <canvas id="chartCat" height="200"></canvas>
        </div>
    </div>

    <!-- Carte -->
    <div class="card mt-4">
        <div class="card-header">
            <h3>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                Carte des signalements
            </h3>
        </div>
        <div class="card-body" style="padding:0">
            <div id="map" style="height:420px;border-radius:0 0 var(--r-lg) var(--r-lg)"></div>
        </div>
    </div>

    <!-- Table catégories -->
    <?php if (!empty($parCategorie)): ?>
    <div class="card mt-4">
        <div class="card-header">
            <h3>📊 Détail par catégorie</h3>
        </div>
        <div class="card-body" style="padding:0">
            <table class="table">
                <thead>
                    <tr>
                        <th>Catégorie</th>
                        <th>Signalements</th>
                        <th>Part</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalSig = array_sum(array_column($parCategorie, 'total')) ?: 1;
                    foreach ($parCategorie as $row):
                        $pct = round($row['total'] / $totalSig * 100);
                    ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($row['libelle']) ?></strong></td>
                        <td><?= $row['total'] ?></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:.75rem">
                                <div style="flex:1;height:6px;background:var(--surface-3);border-radius:3px;overflow:hidden">
                                    <div style="width:<?= $pct ?>%;height:100%;background:var(--blue);border-radius:3px"></div>
                                </div>
                                <span style="font-size:.75rem;color:var(--text-muted);min-width:30px"><?= $pct ?>%</span>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    // Graphique statuts
    const ctxS = document.getElementById('chartStatut').getContext('2d');
    new Chart(ctxS, {
        type: 'bar',
        data: {
            labels: ['Nouveau', 'En cours', 'Résolu', 'Rejeté'],
            datasets: [{
                label: 'Signalements',
                data: [<?= $nouveaux ?? 0 ?>, <?= $enCours ?? 0 ?>, <?= $resolus ?? 0 ?>, <?= $rejetes ?? 0 ?>],
                backgroundColor: ['#dbeafe','#fdf6b2','#d1fae5','#fde8e8'],
                borderColor:     ['#1e40af','#78350f','#065f46','#9b1c1c'],
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,.04)' }, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
            }
        }
    });

    // Graphique catégories
    const ctxC = document.getElementById('chartCat').getContext('2d');
    new Chart(ctxC, {
        type: 'doughnut',
        data: {
            labels: [<?php foreach($parCategorie as $r) echo '"' . addslashes($r['libelle']) . '",'; ?>],
            datasets: [{
                data: [<?php foreach($parCategorie as $r) echo $r['total'] . ','; ?>],
                backgroundColor: ['#1a56db','#e85d04','#057a55','#6c2bd9'],
                borderWidth: 0,
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 16 } }
            }
        }
    });

    // Carte Leaflet admin
    const map = L.map('map').setView([14.6928, -17.4467], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    fetch('<?= BASE_URL ?>/api/signalements')
        .then(r => r.json())
        .then(data => {
            if (!data.data) return;
            data.data.forEach(sig => {
                if (!sig.lat || !sig.lng) return;
                const colors = {
                    nouveau:  '#1a56db',
                    en_cours: '#e85d04',
                    resolu:   '#057a55',
                    rejete:   '#c81e1e'
                };
                const color = colors[sig.statut] || '#64748b';
                const icon  = L.divIcon({
                    className: '',
                    html: `<div style="width:14px;height:14px;background:${color};border:2px solid #fff;border-radius:50%;box-shadow:0 2px 6px rgba(0,0,0,.3)"></div>`,
                    iconSize: [14,14], iconAnchor: [7,7]
                });
                L.marker([sig.lat, sig.lng], {icon})
                    .addTo(map)
                    .bindPopup(`<strong>${sig.titre}</strong><br><span style="font-size:.8rem;color:#64748b">${sig.statut}</span>`);
            });
        }).catch(() => {});
});
</script>

<?php require_once VIEW_PATH . '/layout/footer.php'; ?>