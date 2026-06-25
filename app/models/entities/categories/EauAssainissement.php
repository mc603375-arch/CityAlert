<?php
require_once __DIR__ . '/Categorie.php';

class EauAssainissement extends Categorie
{
    public function getComportementSpecifique(): string
    {
        return "Traitement {->libelle} — délai : {->delaiJours} jours, priorité : {->priorite}.";
    }

    public function getMessageCreation(): string
    {
        return "✅ Signalement enregistré dans la catégorie {->libelle}. Délai d'intervention : {->delaiJours} jours.";
    }
}