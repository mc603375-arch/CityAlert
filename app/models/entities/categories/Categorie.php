<?php

abstract class Categorie
{
    // -------------------------------------------------------
    // ATTRIBUTS PROTÉGÉS
    // protected = accessible dans les classes filles
    // -------------------------------------------------------
    protected int    $id;
    protected string $libelle;
    protected string $priorite;
    protected int    $delai_jours;

    // -------------------------------------------------------
    // CONSTRUCTEUR
    // -------------------------------------------------------
    public function __construct(
        string $libelle,
        string $priorite,
        int    $delai_jours
    ) {
        $this->libelle     = $libelle;
        $this->priorite    = $priorite;
        $this->delai_jours = $delai_jours;
    }

    // -------------------------------------------------------
    // GETTERS
    // -------------------------------------------------------
    public function getId(): int          { return $this->id; }
    public function getLibelle(): string  { return $this->libelle; }
    public function getDelaiJours(): int  { return $this->delai_jours; }

    public function setId(int $id): void  { $this->id = $id; }

    // -------------------------------------------------------
    // MÉTHODES ABSTRAITES
    // Chaque sous-classe DOIT les implémenter
    // -------------------------------------------------------
    abstract public function getPriorite(): string;
    abstract public function getDelaiTraitement(): int;
    abstract public function getDescription(): string;

    // -------------------------------------------------------
    // Crée la bonne sous-classe selon le libellé
    // -------------------------------------------------------
    public static function fromArray(array $data): self
    {
        return match(strtolower($data['libelle'])) {
            'voirie'               => new Voirie(),
            'eclairage'            => new Eclairage(),
            'dechets'              => new Dechets(),
            'eau et assainissement'=> new EauAssainissement(),
            default                => new Voirie()
        };
    }
}