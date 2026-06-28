<?php $pageTitle = "Inscription — CityAlert"; ?>
<?php require_once VIEW_PATH . '/layout/header.php'; ?>

<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:var(--gray-50);padding:20px">
    <div style="width:100%;max-width:500px">

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
                <p style="color:var(--gray-500);font-size:14px">Créez votre compte citoyen</p>
            </div>

            <!-- ALERTES -->
            <?php if (!empty($erreur)): ?>
                <div class="alert alert-danger">⚠️ <?= htmlspecialchars($erreur) ?></div>
            <?php endif; ?>

            <!-- FORMULAIRE -->
            <form method="POST" action="<?= BASE_URL ?>/register/traiter">

                <div class="form-row">
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom"
                               placeholder="Votre nom" required autocomplete="family-name">
                    </div>
                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" id="prenom" name="prenom"
                               placeholder="Votre prénom" required autocomplete="given-name">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email"
                           placeholder="votre@email.com" required autocomplete="email">
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password"
                           placeholder="Minimum 6 caractères" required>
                </div>

                <div class="form-group">
                    <label for="confirm">Confirmer le mot de passe</label>
                    <input type="password" id="confirm" name="confirm"
                           placeholder="Répétez le mot de passe" required>
                </div>

                <button type="submit" class="btn btn-primary btn-full btn-lg" style="margin-top:.5rem">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/></svg>
                    Créer mon compte
                </button>

            </form>

            <!-- FOOTER -->
            <div style="text-align:center;margin-top:24px;font-size:14px;color:var(--gray-500)">
                Déjà un compte ?
                <a href="<?= BASE_URL ?>/login" style="font-weight:600;color:var(--primary)">
                    Se connecter
                </a>
            </div>

        </div>
    </div>
</div>

<?php require_once VIEW_PATH . '/layout/footer.php'; ?>