<?php

require_once __DIR__ . '/Categorie.php';

class Dechets extends Categorie
{
    public function __construct()
    {
        parent::__construct('Dechets', 'normale', 3);
    }

    public function getPriorite(): string
    {
        return 'normale';
    }

    public function getDelaiTraitement(): int
    {
        return 3; // 3 jours
    }

    public function getDescription(): string
    {
        return "Problèmes liés à la collecte des déchets et propreté urbaine.";
    }
}