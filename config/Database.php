<?php

require_once __DIR__ . '/config.php';

class Database
{
    // -------------------------------------------------------
    // Instance unique (pattern Singleton)
    // -------------------------------------------------------
    private static ?Database $instance = null;
    private PDO $connexion;

    // -------------------------------------------------------
    // Constructeur privé — connexion PDO
    // -------------------------------------------------------
    private function __construct()
    {
        try {
            $dsn = "mysql:host=" . DB_HOST .
                   ";dbname="    . DB_NAME .
                   ";charset="   . DB_CHARSET;

            $this->connexion = new PDO($dsn, DB_USER, DB_PASS);

            $this->connexion->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
            $this->connexion->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );
            $this->connexion->setAttribute(
                PDO::ATTR_EMULATE_PREPARES,
                false
            );

        } catch (PDOException $e) {
            die("❌ Erreur de connexion : " . $e->getMessage());
        }
    }

    // -------------------------------------------------------
    // Récupère l'instance unique
    // -------------------------------------------------------
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // -------------------------------------------------------
    // Retourne la connexion PDO
    // -------------------------------------------------------
    public function getConnexion(): PDO
    {
        return $this->connexion;
    }

    // -------------------------------------------------------
    // Empêche la copie de l'instance
    // -------------------------------------------------------
    private function __clone() {}
}