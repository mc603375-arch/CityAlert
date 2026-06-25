<?php
class EntityNotFoundException extends RuntimeException
{
    public function __construct(string $entity, int $id)
    {
        parent::__construct("❌ {$entity} avec l'id {$id} introuvable.", 404);
    }
}