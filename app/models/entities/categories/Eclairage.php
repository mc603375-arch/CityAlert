<?php

require_once __DIR__ . '/Categorie.php';

class Eclairage extends Categorie
{
    public function __construct()
    {
        parent::__construct('Eclairage', 'normale', 7);
    }

    public function getPriorite(): string
    {
        return 'normale';
    }

    public function getDelaiTraitement(): int
    {
        return 7; // 7 jours
    }

    public function getDescription(): string
    {
        return "Problèmes liés à l'éclairage public et signalisation lumineuse.";
    }
}