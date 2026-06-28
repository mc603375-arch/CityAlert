<?php $pageTitle = "Connexion — CityAlert"; ?>
<?php require_once VIEW_PATH . '/layout/header.php'; ?>

<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:var(--gray-50);padding:20px">
    <div style="width:100%;max-width:440px">

        <!-- CARD -->
        <div class="form-card" style="padding:40px">

            <!-- LOGO -->
            <div style="text-align:center;margin-bottom:32px">
                <img src="/CityAlert/public/assets/images/LOGO.jpeg"
                     alt="CityAlert"
                     style="width:80px;height:80px;object-fit:contain;margin-bottom:14px;display:block;margin-left:auto;margin-right:auto">
                <h1 style="font-family:var(--font-display);font-size:26px;font-weight:800;color:var(--primary);margin-bottom:6px">
                    City<span style="color:var(--accent)">Alert</span>
                </h1>
                <p style="color:var(--gray-500);font-size:14px">Connectez-vous à votre espace</p>
            </div>

            <!-- ALERTES -->
            <?php if (!empty($erreur)): ?>
                <div class="alert alert-danger">⚠️ <?= htmlspecialchars($erreur) ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <!-- FORMULAIRE -->
            <form method="POST" action="<?= BASE_URL ?>/login/traiter">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email"
                           placeholder="votre@email.com" required autocomplete="email">
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password"
                           placeholder="Votre mot de passe" required autocomplete="current-password">
                </div>
                <button type="submit" class="btn btn-primary btn-full btn-lg" style="margin-top:.5rem">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/></svg>
                    Se connecter
                </button>
            </form>

            <!-- FOOTER -->
            <div style="text-align:center;margin-top:24px;font-size:14px;color:var(--gray-500)">
                Pas encore de compte ?
                <a href="<?= BASE_URL ?>/register" style="font-weight:600;color:var(--primary)">
                    S'inscrire gratuitement
                </a>
            </div>

        </div>
    </div>
</div>

<?php require_once VIEW_PATH . '/layout/footer.php'; ?>