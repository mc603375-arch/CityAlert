<?php
class Autoloader
{
    public static function register(): void
    {
        spl_autoload_register([self::class, 'load']);
    }

    public static function load(string $classe): void
    {
        $root = realpath(dirname(__DIR__));
        $map  = [
            'Database'                => $root . '/config/Database.php',
            'RepositoryInterface'     => $root . '/interfaces/RepositoryInterface.php',
            'NotifiableInterface'     => $root . '/interfaces/NotifiableInterface.php',
            'Timestampable'           => $root . '/traits/Timestampable.php',
            'EntityNotFoundException' => $root . '/exceptions/EntityNotFoundException.php',
            'ValidationException'     => $root . '/exceptions/ValidationException.php',
            'User'                    => $root . '/app/models/entities/User.php',
            'Signalement'             => $root . '/app/models/entities/Signalement.php',
            'Commentaire'             => $root . '/app/models/entities/Commentaire.php',
            'Categorie'               => $root . '/app/models/entities/categories/Categorie.php',
            'Voirie'                  => $root . '/app/models/entities/categories/Voirie.php',
            'Eclairage'               => $root . '/app/models/entities/categories/Eclairage.php',
            'Dechets'                 => $root . '/app/models/entities/categories/Dechets.php',
            'EauAssainissement'       => $root . '/app/models/entities/categories/EauAssainissement.php',
            'UserRepository'          => $root . '/app/models/repositories/UserRepository.php',
            'SignalementRepository'   => $root . '/app/models/repositories/SignalementRepository.php',
            'CommentaireRepository'   => $root . '/app/models/repositories/CommentaireRepository.php',
            'AuthController'          => $root . '/app/controllers/AuthController.php',
            'SignalementController'   => $root . '/app/controllers/SignalementController.php',
            'CommentaireController'   => $root . '/app/controllers/CommentaireController.php',
            'DashboardController'     => $root . '/app/controllers/DashboardController.php',
            'AdminController'         => $root . '/app/controllers/AdminController.php',
        ];
        if (isset($map[$classe]) && file_exists($map[$classe])) {
            require_once $map[$classe];
        }
    }
}
