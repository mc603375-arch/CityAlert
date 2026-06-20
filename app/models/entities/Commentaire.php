<?php

class Commentaire
{
    // -------------------------------------------------------
    // ATTRIBUTS PRIVÉS
    // -------------------------------------------------------
    private int    $id;
    private string $contenu;
    private int    $signalement_id;
    private int    $user_id;
    private string $created_at;

    // -------------------------------------------------------
    // CONSTRUCTEUR
    // -------------------------------------------------------
    public function __construct(
        string $contenu,
        int    $signalement_id,
        int    $user_id
    ) {
        $this->contenu        = $contenu;
        $this->signalement_id = $signalement_id;
        $this->user_id        = $user_id;
        $this->created_at     = date('Y-m-d H:i:s');
    }

    // -------------------------------------------------------
    // GETTERS
    // -------------------------------------------------------
    public function getId(): int              { return $this->id; }
    public function getContenu(): string      { return $this->contenu; }
    public function getSignalementId(): int   { return $this->signalement_id; }
    public function getUserId(): int          { return $this->user_id; }
    public function getCreatedAt(): string    { return $this->created_at; }

    // -------------------------------------------------------
    // Crée un Commentaire depuis un tableau (retour BDD)
    // -------------------------------------------------------
    public static function fromArray(array $data): self
    {
        $c = new self(
            $data['contenu'],
            $data['signalement_id'],
            $data['user_id']
        );
        $c->id         = $data['id'];
        $c->created_at = $data['created_at'];
        return $c;
    }
}