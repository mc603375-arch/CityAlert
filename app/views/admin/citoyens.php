<?php $pageTitle = "Utilisateurs — CityAlert"; ?>
<?php require_once VIEW_PATH . '/layout/header.php'; ?>
<?php require_once VIEW_PATH . '/layout/navbar.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>👥 Gestion des Utilisateurs</h1>
        <a href="<?= BASE_URL ?>/admin/dashboard" class="btn btn-outline btn-sm">← Dashboard</a>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <!-- Stats cliquables -->
    <div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:24px">
        <a href="<?= BASE_URL ?>/admin/citoyens" class="stat-card" style="text-decoration:none;cursor:pointer">
            <div class="stat-icon">👥</div>
            <div class="stat-value"><?= $totalCitoyens + $totalAgents + $totalAdmins ?></div>
            <div class="stat-label">Tous les utilisateurs</div>
        </a>
        <a href="<?= BASE_URL ?>/admin/citoyens?role=citoyen" class="stat-card stat-orange" style="text-decoration:none;cursor:pointer">
            <div class="stat-icon">🏙️</div>
            <div class="stat-value"><?= $totalCitoyens ?></div>
            <div class="stat-label">Citoyens</div>
        </a>
        <a href="<?= BASE_URL ?>/admin/citoyens?role=agent" class="stat-card stat-green" style="text-decoration:none;cursor:pointer">
            <div class="stat-icon">👮</div>
            <div class="stat-value"><?= $totalAgents ?></div>
            <div class="stat-label">Agents</div>
        </a>
    </div>

    <!-- Filtres -->
    <div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap">
        <a href="<?= BASE_URL ?>/admin/citoyens"
           class="filter-btn <?= empty($_GET['role']) ? 'active' : '' ?>">Tous</a>
        <a href="<?= BASE_URL ?>/admin/citoyens?role=citoyen"
           class="filter-btn <?= ($_GET['role'] ?? '') === 'citoyen' ? 'active' : '' ?>">🏙️ Citoyens</a>
        <a href="<?= BASE_URL ?>/admin/citoyens?role=agent"
           class="filter-btn <?= ($_GET['role'] ?? '') === 'agent' ? 'active' : '' ?>">👮 Agents</a>
        <a href="<?= BASE_URL ?>/admin/citoyens?role=admin"
           class="filter-btn <?= ($_GET['role'] ?? '') === 'admin' ? 'active' : '' ?>">🔑 Admins</a>
    </div>

    <!-- Tableau dans form-card -->
    <div class="form-card" style="max-width:100%;padding:0;overflow:hidden">
        <div style="padding:20px 24px;border-bottom:1px solid var(--gray-100);display:flex;align-items:center;justify-content:space-between">
            <h3 style="font-family:var(--font-display);font-size:16px;font-weight:700;color:var(--gray-900)">
                Utilisateurs (<?= count($users) ?>)
            </h3>
        </div>
        <div style="overflow-x:auto">
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="background:var(--gray-50)">
                        <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:.8px;border-bottom:1px solid var(--gray-100)">Utilisateur</th>
                        <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:.8px;border-bottom:1px solid var(--gray-100)">Email</th>
                        <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:.8px;border-bottom:1px solid var(--gray-100)">Rôle</th>
                        <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:.8px;border-bottom:1px solid var(--gray-100)">Inscrit le</th>
                        <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:.8px;border-bottom:1px solid var(--gray-100)">Changer rôle</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr style="border-bottom:1px solid var(--gray-100)">
                            <td style="padding:14px 20px">
                                <div style="display:flex;align-items:center;gap:10px">
                                    <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--accent));display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:13px;flex-shrink:0">
                                        <?= strtoupper(substr($u['prenom'], 0, 1) . substr($u['nom'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div style="font-weight:600;font-size:14px;color:var(--gray-900)">
                                            <?= htmlspecialchars($u['prenom'] . ' ' . $u['nom']) ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding:14px 20px;font-size:13px;color:var(--gray-500)">
                                <?= htmlspecialchars($u['email']) ?>
                            </td>
                            <td style="padding:14px 20px">
                                <?php if ($u['role'] === 'admin'): ?>
                                    <span style="background:#EBF2FB;color:#1B4F8A;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600">🔑 Admin</span>
                                <?php elseif ($u['role'] === 'agent'): ?>
                                    <span style="background:#FEF3C7;color:#D97706;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600">👮 Agent</span>
                                <?php else: ?>
                                    <span style="background:#F3F4F6;color:#6B7280;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600">🏙️ Citoyen</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding:14px 20px;font-size:13px;color:var(--gray-500)">
                                <?= date('d/m/Y', strtotime($u['created_at'])) ?>
                            </td>
                            <td style="padding:14px 20px">
                                <?php if ($u['id'] !== (int)$_SESSION['user']['id']): ?>
                                    <form method="POST" action="<?= BASE_URL ?>/admin/changer-role"
                                          style="display:inline-flex;gap:6px;align-items:center">
                                        <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                        <select name="role"
                                                style="font-size:12px;padding:5px 10px;border:1.5px solid var(--gray-200);border-radius:6px;outline:none;cursor:pointer">
                                            <option value="citoyen" <?= $u['role']==='citoyen'?'selected':'' ?>>🏙️ Citoyen</option>
                                            <option value="agent"   <?= $u['role']==='agent'  ?'selected':'' ?>>👮 Agent</option>
                                            <option value="admin"   <?= $u['role']==='admin'  ?'selected':'' ?>>🔑 Admin</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary">✓</button>
                                    </form>
                                <?php else: ?>
                                    <span style="font-size:12px;color:var(--gray-400);font-style:italic">C'est vous</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once VIEW_PATH . '/layout/footer.php'; ?>