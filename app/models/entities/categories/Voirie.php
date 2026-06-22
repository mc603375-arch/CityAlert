<?php

require_once __DIR__ . '/Categorie.php';

class Voirie extends Categorie
{
    public function __construct()
    {
        parent::__construct('Voirie', 'haute', 5);
    }

    public function getPriorite(): string
    {
        return 'haute';
    }

    public function getDelaiTraitement(): int
    {
        return 5; // 5 jours
    }

    public function getDescription(): string
    {
        return "Problèmes liés aux routes, trottoirs et infrastructure routière.";
    }
}