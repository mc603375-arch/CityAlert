<?php $pageTitle = "Inscription — CityAlert"; ?>
<?php require_once VIEW_PATH . '/layout/header.php'; ?>
<?php require_once VIEW_PATH . '/layout/navbar.php'; ?>

<div class="auth-container">
    <div class="auth-card">

        <div class="auth-logo">
            <span class="logo-icon">🚨</span>
            <h1>CityAlert</h1>
            <p>Créez votre compte citoyen</p>
        </div>

        <?php if (!empty($erreur)): ?>
            <div class="alert alert-danger">
                ⚠️ <?= htmlspecialchars($erreur) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/register/traiter">

            <div class="form-row">
                <div class="form-group">
                    <label for="nom">👤 Nom</label>
                    <input type="text" id="nom" name="nom" placeholder="Votre nom" required>
                </div>

                <div class="form-group">
                    <label for="prenom">👤 Prénom</label>
                    <input type="text" id="prenom" name="prenom" placeholder="Votre prénom" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">📧 Email</label>
                <input type="email" id="email" name="email" placeholder="votre@email.com" required>
            </div>

            <div class="form-group">
                <label for="password">🔒 Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Min. 6 caractères" required>
            </div>

            <div class="form-group">
                <label for="confirm">🔒 Confirmer le mot de passe</label>
                <input type="password" id="confirm" name="confirm" placeholder="Répétez le mot de passe" required>
            </div>

            <button type="submit" class="btn btn-primary btn-full">
                Créer mon compte →
            </button>

        </form>

        <div class="auth-footer">
            <p>Déjà un compte ?
                <a href="<?= BASE_URL ?>/login">Se connecter</a>
            </p>
        </div>

    </div>
</div>

<?php require_once VIEW_PATH . '/layout/footer.php'; ?>