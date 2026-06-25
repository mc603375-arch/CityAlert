<?php
require_once ROOT_PATH . '/traits/Timestampable.php';

class Signalement
{
    use Timestampable;

    public const STATUTS = ['nouveau', 'en_cours', 'resolu', 'rejete'];

    private int     $id;
    private string  $titre;
    private string  $description;
    private string  $adresse;
    private ?string $photo;
    private string  $statut;
    private string  $priorite;
    private int     $userId;
    private int     $categorieId;
    private ?int    $agentId;

    public function __construct(
        string $titre, string $description, string $adresse,
        int $userId, int $categorieId,
        string $priorite = 'normale', string $statut = 'nouveau',
        ?string $photo = null, ?int $agentId = null
    ) {
        $this->titre       = $titre;
        $this->description = $description;
        $this->adresse     = $adresse;
        $this->userId      = $userId;
        $this->categorieId = $categorieId;
        $this->priorite    = $priorite;
        $this->statut      = $statut;
        $this->photo       = $photo;
        $this->agentId     = $agentId;
        $this->initTimestamps();
    }

    public function getId(): int             { return $this->id; }
    public function getTitre(): string       { return $this->titre; }
    public function getDescription(): string { return $this->description; }
    public function getAdresse(): string     { return $this->adresse; }
    public function getPhoto(): ?string      { return $this->photo; }
    public function getStatut(): string      { return $this->statut; }
    public function getPriorite(): string    { return $this->priorite; }
    public function getUserId(): int         { return $this->userId; }
    public function getCategorieId(): int    { return $this->categorieId; }
    public function getAgentId(): ?int       { return $this->agentId; }

    public function setId(int $id): void             { $this->id = $id; }
    public function setTitre(string $t): void        { $this->titre = $t; }
    public function setDescription(string $d): void  { $this->description = $d; }
    public function setAdresse(string $a): void      { $this->adresse = $a; }
    public function setPhoto(?string $p): void       { $this->photo = $p; }
    public function setAgentId(?int $id): void       { $this->agentId = $id; }

    public function setStatut(string $statut): void
    {
        if (!in_array($statut, self::STATUTS, true))
            throw new InvalidArgumentException("Statut invalide : $statut");
        $this->statut = $statut;
        $this->setUpdatedAt();
    }

    public function isEditable(): bool { return $this->statut === 'nouveau'; }

    public function getBadgeStatut(): string
    {
        return match($this->statut) {
            'nouveau'  => '<span class="badge badge-nouveau">Nouveau</span>',
            'en_cours' => '<span class="badge badge-en-cours">En cours</span>',
            'resolu'   => '<span class="badge badge-resolu">Résolu</span>',
            'rejete'   => '<span class="badge badge-rejete">Rejeté</span>',
            default    => '<span class="badge">Inconnu</span>',
        };
    }

    public static function fromArray(array $d): self
    {
        $s = new self(
            $d['titre'], $d['description'], $d['adresse'],
            (int)$d['user_id'], (int)$d['categorie_id'],
            $d['priorite'] ?? 'normale', $d['statut'] ?? 'nouveau',
            $d['photo'] ?? null,
            isset($d['agent_id']) ? (int)$d['agent_id'] : null,
        );
        $s->id         = (int)$d['id'];
        $s->created_at = $d['created_at'] ?? date('Y-m-d H:i:s');
        $s->updated_at = $d['updated_at'] ?? date('Y-m-d H:i:s');
        return $s;
    }
}