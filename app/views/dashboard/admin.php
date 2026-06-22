<?php require_once __DIR__ . '/../layout/header.php'; ?>
<?php require_once __DIR__ . '/../layout/navbar.php'; ?>

<div class="container mt-4">

    <h1>Tableau de bord administrateur</h1>

    <div class="row mt-4">

        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Utilisateurs</h5>
                    <h2><?= htmlspecialchars($totalUsers ?? 0) ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Signalements</h5>
                    <h2><?= htmlspecialchars($totalSignalements ?? 0) ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Nouveaux</h5>
                    <h2><?= htmlspecialchars($nouveaux ?? 0) ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Résolus</h5>
                    <h2><?= htmlspecialchars($resolus ?? 0) ?></h2>
                </div>
            </div>
        </div>

    </div>

    <hr>

    <p class="text-muted">
        Ce tableau de bord permet de suivre rapidement l’activité générale de CityAlert.
    </p>

</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>