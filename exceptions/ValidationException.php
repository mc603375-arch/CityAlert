<?php
class ValidationException extends RuntimeException
{
    private array $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
        parent::__construct("❌ Erreur de validation.", 422);
    }

    public function getErrors(): array { return $this->errors; }
}