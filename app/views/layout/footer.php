<footer class="footer">
    <div class="footer-content">
        <p>🚨 <strong style="color:rgba(255,255,255,.7)">CityAlert</strong> — Plateforme de signalement citoyen</p>
        <p style="margin-top:.35rem">
            <a href="<?= BASE_URL ?>/api/signalements">API JSON</a> &nbsp;·&nbsp;
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === ROLE_ADMIN): ?>
                <a href="<?= BASE_URL ?>/export/csv">Export CSV</a> &nbsp;·&nbsp;
            <?php endif; ?>
            &copy; <?= date('Y') ?> Tous droits réservés
        </p>
    </div>
</footer>
</body>
</html>