<?php $pageTitle = "Gestion des agents — CityAlert"; ?>
<?php require_once VIEW_PATH . '/layout/header.php'; ?>
<?php require_once VIEW_PATH . '/layout/navbar.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>👮 Gestion des Agents</h1>
        <a href="<?= BASE_URL ?>/admin/dashboard" class="btn btn-outline btn-sm">← Dashboard</a>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($erreur)): ?>
        <div class="alert alert-danger">⚠️ <?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:1fr 380px;gap:1.5rem;align-items:start">

        <!-- Liste des agents dans form-card -->
        <div class="form-card" style="max-width:100%;padding:0;overflow:hidden">
            <div style="padding:20px 24px;border-bottom:1px solid var(--gray-100);display:flex;align-items:center;justify-content:space-between">
                <h3 style="font-family:var(--font-display);font-size:16px;font-weight:700;color:var(--gray-900)">
                    👮 Agents actifs (<?= count($agents) ?>)
                </h3>
            </div>
            <div style="overflow-x:auto">
                <table style="width:100%;border-collapse:collapse">
                    <thead>
                        <tr style="background:var(--gray-50)">
                            <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:.8px;border-bottom:1px solid var(--gray-100)">Agent</th>
                            <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:.8px;border-bottom:1px solid var(--gray-100)">Spécialité</th>
                            <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:.8px;border-bottom:1px solid var(--gray-100)">Total</th>
                            <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:.8px;border-bottom:1px solid var(--gray-100)">En cours</th>
                            <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:.8px;border-bottom:1px solid var(--gray-100)">Résolus</th>
                            <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:.8px;border-bottom:1px solid var(--gray-100)">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($agents)): ?>
                            <tr>
                                <td colspan="6" style="text-align:center;padding:40px;color:var(--gray-400);font-size:14px">
                                    Aucun agent pour le moment
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($agents as $agent): ?>
                                <?php
                                    $email = $agent['email'] ?? '';
                                    if (str_contains($email, 'voirie'))        { $spec = '🛣️ Voirie';               $color = '#1B4F8A'; $bg = '#EBF2FB'; }
                                    elseif (str_contains($email, 'eclairage')) { $spec = '💡 Éclairage';            $color = '#D97706'; $bg = '#FEF3C7'; }
                                    elseif (str_contains($email, 'dechets'))   { $spec = '🗑️ Déchets';              $color = '#059669'; $bg = '#DCFCE7'; }
                                    elseif (str_contains($email, 'eau'))       { $spec = '💧 Eau & Assainissement'; $color = '#0284C7'; $bg = '#E0F2FE'; }
                                    else                                       { $spec = '👮 Agent';                $color = '#6B7280'; $bg = '#F3F4F6'; }
                                ?>
                                <tr style="border-bottom:1px solid var(--gray-100)">
                                    <td style="padding:14px 20px">
                                        <div style="display:flex;align-items:center;gap:10px">
                                            <div style="width:40px;height:40px;border-radius:50%;background:<?= $color ?>;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:14px;flex-shrink:0">
                                                <?= strtoupper(substr($agent['prenom'], 0, 1) . substr($agent['nom'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <div style="font-weight:600;font-size:14px;color:var(--gray-900)">
                                                    <?= htmlspecialchars($agent['prenom'] . ' ' . $agent['nom']) ?>
                                                </div>
                                                <div style="font-size:11px;color:var(--gray-400)">
                                                    <?= htmlspecialchars($email) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding:14px 20px">
                                        <span style="background:<?= $bg ?>;color:<?= $color ?>;padding:5px 12px;border-radius:20px;font-size:12px;font-weight:600;white-space:nowrap">
                                            <?= $spec ?>
                                        </span>
                                    </td>
                                    <td style="padding:14px 20px">
                                        <span style="background:var(--primary-light);color:var(--primary);padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700">
                                            <?= $agent['nb_signalements'] ?>
                                        </span>
                                    </td>
                                    <td style="padding:14px 20px">
                                        <span style="background:#FEF3C7;color:#D97706;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700">
                                            <?= $agent['nb_en_cours'] ?? 0 ?>
                                        </span>
                                    </td>
                                    <td style="padding:14px 20px">
                                        <span style="background:#DCFCE7;color:#059669;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700">
                                            <?= $agent['nb_resolus'] ?? 0 ?>
                                        </span>
                                    </td>
                                    <td style="padding:14px 20px">
                                        <a href="<?= BASE_URL ?>/admin/agents/supprimer?id=<?= $agent['id'] ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Supprimer cet agent ?')">
                                           🗑️
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Formulaire créer agent -->
        <div class="form-card">
            <h3 style="font-family:var(--font-display);font-size:16px;font-weight:700;margin-bottom:20px;color:var(--gray-900)">
                ➕ Créer un nouvel agent
            </h3>

            <form method="POST" action="<?= BASE_URL ?>/admin/agents/creer">

                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="nom" placeholder="Nom de l'agent" required>
                </div>

                <div class="form-group">
                    <label>Prénom</label>
                    <input type="text" name="prenom" placeholder="Prénom de l'agent" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="ex: agent.voirie@cityalert.com" required>
                </div>

                <div class="form-group">
                    <label>Mot de passe</label>
                    <input type="password" name="password" placeholder="Minimum 6 caractères" required>
                </div>

                <div style="background:var(--primary-light);border-radius:8px;padding:14px;margin-bottom:16px;font-size:12px;color:var(--primary);line-height:1.8">
                    💡 <strong>Astuce :</strong> Utilisez l'email pour indiquer la spécialité :<br>
                    • <code>agent.voirie@...</code> → 🛣️ Voirie<br>
                    • <code>agent.eclairage@...</code> → 💡 Éclairage<br>
                    • <code>agent.dechets@...</code> → 🗑️ Déchets<br>
                    • <code>agent.eau@...</code> → 💧 Eau
                </div>

                <button type="submit" class="btn btn-primary btn-full">
                    ✅ Créer l'agent
                </button>

            </form>
        </div>
    </div>
</div>

<?php require_once VIEW_PATH . '/layout/footer.php'; ?>