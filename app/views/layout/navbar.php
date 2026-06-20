<nav class="navbar">
    <div class="navbar-brand">
        <a href="<?= BASE_URL ?>">
            🚨 CityAlert
        </a>
    </div>

    <ul class="navbar-menu">
        <?php if (isset($_SESSION['user'])): ?>

            <?php if ($_SESSION['user']['role'] === ROLE_CITOYEN): ?>
                <li><a href="<?= BASE_URL ?>/citoyen/dashboard">Mon espace</a></li>
                <li><a href="<?= BASE_URL ?>/signalements/ajouter">+ Signaler</a></li>

            <?php elseif ($_SESSION['user']['role'] === ROLE_AGENT): ?>
                <li><a href="<?= BASE_URL ?>/agent/dashboard">Mon espace</a></li>
                <li><a href="<?= BASE_URL ?>/signalements">Signalements</a></li>

            <?php elseif ($_SESSION['user']['role'] === ROLE_ADMIN): ?>
                <li><a href="<?= BASE_URL ?>/admin/dashboard">Dashboard</a></li>
                <li><a href="<?= BASE_URL ?>/admin/utilisateurs">Utilisateurs</a></li>
                <li><a href="<?= BASE_URL ?>/signalements">Signalements</a></li>
            <?php endif; ?>

            <li class="navbar-user">
                👤 <?= htmlspecialchars($_SESSION['user']['prenom']) ?>
                <a href="<?= BASE_URL ?>/logout" class="btn-logout">Déconnexion</a>
            </li>

        <?php else: ?>
            <li><a href="<?= BASE_URL ?>/login">Connexion</a></li>
            <li><a href="<?= BASE_URL ?>/register">S'inscrire</a></li>
        <?php endif; ?>
    </ul>
</nav>