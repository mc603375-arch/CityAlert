<?php
require_once ROOT_PATH . '/traits/Timestampable.php';

class Commentaire
{
    use Timestampable;

    private int    $id;
    private string $contenu;
    private int    $signalementId;
    private int    $userId;

    public function __construct(string $contenu, int $signalementId, int $userId)
    {
        $this->contenu       = $contenu;
        $this->signalementId = $signalementId;
        $this->userId        = $userId;
        $this->initTimestamps();
    }

    public function getId(): int            { return $this->id; }
    public function getContenu(): string    { return $this->contenu; }
    public function getSignalementId(): int { return $this->signalementId; }
    public function getUserId(): int        { return $this->userId; }

    public static function fromArray(array $data): self
    {
        $c = new self($data['contenu'], (int)$data['signalement_id'], (int)$data['user_id']);
        $c->id         = (int)$data['id'];
        $c->created_at = $data['created_at'] ?? date('Y-m-d H:i:s');
        $c->updated_at = $data['created_at'] ?? date('Y-m-d H:i:s');
        return $c;
    }
}