<?php $pageTitle = "Inscription — CityAlert"; ?>
<?php require_once VIEW_PATH . '/layout/header.php'; ?>
<?php require_once VIEW_PATH . '/layout/navbar.php'; ?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-logo">
            <div class="auth-logo-icon">🚨</div>
            <h1>CityAlert</h1>
            <p>Créez votre compte citoyen</p>
        </div>

        <?php if (!empty($erreur)): ?>
            <div class="alert alert-danger">⚠️ <?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/register/traiter">
            <div class="form-row">
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" placeholder="Votre nom" required autocomplete="family-name">
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" placeholder="Votre prénom" required autocomplete="given-name">
                </div>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="votre@email.com" required autocomplete="email">
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Minimum 6 caractères" required>
            </div>
            <div class="form-group">
                <label for="confirm">Confirmer le mot de passe</label>
                <input type="password" id="confirm" name="confirm" placeholder="Répétez le mot de passe" required>
            </div>
            <button type="submit" class="btn btn-primary btn-full btn-lg" style="margin-top:.5rem">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/></svg>
                Créer mon compte
            </button>
        </form>

        <div class="auth-footer">
            Déjà un compte ? <a href="<?= BASE_URL ?>/login" style="font-weight:600;color:var(--blue)">Se connecter</a>
        </div>
    </div>
</div>

<?php require_once VIEW_PATH . '/layout/footer.php'; ?>