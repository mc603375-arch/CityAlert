<?php
require_once __DIR__ . '/Categorie.php';
class EauAssainissement extends Categorie
{
    public function getComportementSpecifique(): string
    {
        return "Traitement " . $this->libelle . " — délai : " . $this->delaiJours . " jours, priorité : " . $this->priorite . ".";
    }
    public function getMessageCreation(): string
    {
        return "✅ Signalement enregistré dans la catégorie " . $this->libelle . ". Délai d'intervention : " . $this->delaiJours . " jours.";
    }
}
