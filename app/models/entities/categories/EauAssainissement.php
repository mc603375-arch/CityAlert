<?php

require_once __DIR__ . '/Categorie.php';

class EauAssainissement extends Categorie
{
    public function __construct()
    {
        parent::__construct('Eau et assainissement', 'urgente', 2);
    }

    public function getPriorite(): string
    {
        return 'urgente';
    }

    public function getDelaiTraitement(): int
    {
        return 2; // 2 jours
    }

    public function getDescription(): string
    {
        return "Problèmes liés à l'eau potable, fuites et assainissement.";
    }
}