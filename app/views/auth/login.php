<?php $pageTitle = "Connexion — CityAlert"; ?>
<?php require_once VIEW_PATH . '/layout/header.php'; ?>
<?php require_once VIEW_PATH . '/layout/navbar.php'; ?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-logo">
            <div class="auth-logo-icon">🚨</div>
            <h1>CityAlert</h1>
            <p>Connectez-vous à votre espace citoyen</p>
        </div>

        <?php if (!empty($erreur)): ?>
            <div class="alert alert-danger">⚠️ <?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/login/traiter">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="votre@email.com" required autocomplete="email">
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Votre mot de passe" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn-primary btn-full btn-lg" style="margin-top:.5rem">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/></svg>
                Se connecter
            </button>
        </form>

        <div class="auth-footer">
            Pas encore de compte ? <a href="<?= BASE_URL ?>/register" style="font-weight:600;color:var(--blue)">S'inscrire gratuitement</a>
        </div>
    </div>
</div>

<?php require_once VIEW_PATH . '/layout/footer.php'; ?>