<?php $pageTitle = "Connexion — CityAlert"; ?>
<?php require_once VIEW_PATH . '/layout/header.php'; ?>
<?php require_once VIEW_PATH . '/layout/navbar.php'; ?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-logo">
            <span class="logo-icon">🚨</span>
            <h1>CityAlert</h1>
            <p>Connectez-vous à votre espace</p>
        </div>

        <?php if (!empty($erreur)): ?>
            <div class="alert alert-danger">⚠️ <?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/login/traiter">
            <div class="form-group">
                <label for="email">📧 Email</label>
                <input type="email" id="email" name="email" placeholder="votre@email.com" required>
            </div>
            <div class="form-group">
                <label for="password">🔒 Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>
            </div>
            <button type="submit" class="btn btn-primary btn-full">Se connecter →</button>
        </form>

        <div class="auth-footer">
            <p>Pas encore de compte ? <a href="<?= BASE_URL ?>/register">S'inscrire</a></p>
        </div>
    </div>
</div>

<?php require_once VIEW_PATH . '/layout/footer.php'; ?>