<?php

class ValidationException extends RuntimeException
{
    // -------------------------------------------------------
    // Stocke les erreurs de validation
    // -------------------------------------------------------
    private array $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
        parent::__construct("❌ Erreur de validation.", 422);
    }

    // -------------------------------------------------------
    // Retourne toutes les erreurs
    // -------------------------------------------------------
    public function getErrors(): array
    {
        return $this->errors;
    }
}