<?php

require_once ROOT_PATH . '/traits/Timestampable.php';

class Signalement
{
    use Timestampable;

    // -------------------------------------------------------
    // ATTRIBUTS PRIVÉS
    // -------------------------------------------------------
    private int     $id;
    private string  $titre;
    private string  $description;
    private string  $adresse;
    private ?string $photo;
    private string  $statut;
    private string  $priorite;
    private int     $user_id;
    private int     $categorie_id;
    private ?int    $agent_id;

    // -------------------------------------------------------
    // CONSTRUCTEUR
    // -------------------------------------------------------
    public function __construct(
        string  $titre,
        string  $description,
        string  $adresse,
        int     $user_id,
        int     $categorie_id,
        string  $priorite  = 'normale',
        ?string $photo     = null,
        ?int    $agent_id  = null
    ) {
        $this->titre        = $titre;
        $this->description  = $description;
        $this->adresse      = $adresse;
        $this->user_id      = $user_id;
        $this->categorie_id = $categorie_id;
        $this->priorite     = $priorite;
        $this->photo        = $photo;
        $this->agent_id     = $agent_id;
        $this->statut       = STATUT_NOUVEAU;
        $this->initTimestamps();
    }

    // -------------------------------------------------------
    // GETTERS
    // -------------------------------------------------------
    public function getId(): int           { return $this->id; }
    public function getTitre(): string     { return $this->titre; }
    public function getDescription(): string { return $this->description; }
    public function getAdresse(): string   { return $this->adresse; }
    public function getPhoto(): ?string    { return $this->photo; }
    public function getStatut(): string    { return $this->statut; }
    public function getPriorite(): string  { return $this->priorite; }
    public function getUserId(): int       { return $this->user_id; }
    public function getCategorieId(): int  { return $this->categorie_id; }
    public function getAgentId(): ?int     { return $this->agent_id; }

    // -------------------------------------------------------
    // SETTERS
    // -------------------------------------------------------
    public function setId(int $id): void        { $this->id = $id; }
    public function setPhoto(?string $p): void  { $this->photo = $p; }
    public function setAgentId(?int $id): void  { $this->agent_id = $id; }

    // -------------------------------------------------------
    // Changer le statut du signalement
    // -------------------------------------------------------
    public function changerStatut(string $nouveauStatut): void
    {
        $statuts = [
            STATUT_NOUVEAU,
            STATUT_EN_COURS,
            STATUT_RESOLU,
            STATUT_REJETE
        ];

        if (!in_array($nouveauStatut, $statuts)) {
            throw new ValidationException(["statut" => "Statut invalide"]);
        }

        $this->statut = $nouveauStatut;
        $this->setUpdatedAt();
    }

    // -------------------------------------------------------
    // Retourne la couleur selon le statut (pour l'affichage)
    // -------------------------------------------------------
    public function getCouleurStatut(): string
    {
        return match($this->statut) {
            STATUT_NOUVEAU   => 'badge-nouveau',
            STATUT_EN_COURS  => 'badge-en-cours',
            STATUT_RESOLU    => 'badge-resolu',
            STATUT_REJETE    => 'badge-rejete',
            default          => 'badge-default'
        };
    }

    // -------------------------------------------------------
    // Crée un Signalement depuis un tableau (retour BDD)
    // -------------------------------------------------------
    public static function fromArray(array $data): self
    {
        $s = new self(
            $data['titre'],
            $data['description'],
            $data['adresse'],
            $data['user_id'],
            $data['categorie_id'],
            $data['priorite']   ?? 'normale',
            $data['photo']      ?? null,
            $data['agent_id']   ?? null
        );
        $s->id         = $data['id'];
        $s->statut     = $data['statut'];
        $s->created_at = $data['created_at'] ?? date('Y-m-d H:i:s');
        $s->updated_at = $data['updated_at'] ?? date('Y-m-d H:i:s');
        return $s;
    }
}