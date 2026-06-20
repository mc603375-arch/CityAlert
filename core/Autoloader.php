<?php

class Autoloader
{
    // -------------------------------------------------------
    // Enregistre l'autoloader
    // À appeler une seule fois dans index.php
    // -------------------------------------------------------
    public static function register(): void
    {
        spl_autoload_register(function (string $className) {

            // Convertit les \ en / pour les namespaces
            $className = str_replace('\\', '/', $className);

            // Liste des dossiers où chercher les classes
            $directories = [
                ROOT_PATH . '/app/models/entities/',
                ROOT_PATH . '/app/models/entities/categories/',
                ROOT_PATH . '/app/models/repositories/',
                ROOT_PATH . '/app/controllers/',
                ROOT_PATH . '/config/',
                ROOT_PATH . '/core/',
                ROOT_PATH . '/interfaces/',
                ROOT_PATH . '/traits/',
                ROOT_PATH . '/exceptions/',
            ];

            // Cherche le fichier dans chaque dossier
            foreach ($directories as $directory) {
                $file = $directory . $className . '.php';
                if (file_exists($file)) {
                    require_once $file;
                    return;
                }
            }
        });
    }
}