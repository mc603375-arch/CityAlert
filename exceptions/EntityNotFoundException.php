<?php

class EntityNotFoundException extends RuntimeException
{
    // -------------------------------------------------------
    // Exception lancée quand un enregistrement n'est pas trouvé
    // -------------------------------------------------------
    public function __construct(string $entity, int $id)
    {
        $message = "❌ {$entity} avec l'id {$id} introuvable.";
        parent::__construct($message, 404);
    }
}