<?php
abstract class Categorie
{
    protected int    $id;
    protected string $libelle;
    protected string $priorite;
    protected int    $delaiJours;

    public function __construct(int $id, string $libelle, string $priorite, int $delaiJours)
    {
        $this->id         = $id;
        $this->libelle    = $libelle;
        $this->priorite   = $priorite;
        $this->delaiJours = $delaiJours;
    }

    public function getId(): int          { return $this->id; }
    public function getLibelle(): string  { return $this->libelle; }
    public function getPriorite(): string { return $this->priorite; }
    public function getDelaiJours(): int  { return $this->delaiJours; }

    abstract public function getComportementSpecifique(): string;
    abstract public function getMessageCreation(): string;

    public static function fromArray(array $d): self
    {
        $args = [(int)$d['id'], $d['libelle'], $d['priorite'], (int)$d['delai_jours']];
        $lib  = strtolower($d['libelle']);
        return match(true) {
            str_contains($lib, 'voirie')    => new Voirie(...$args),
            str_contains($lib, 'eclairage') => new Eclairage(...$args),
            str_contains($lib, 'dechet')    => new Dechets(...$args),
            default                         => new EauAssainissement(...$args),
        };
    }
}