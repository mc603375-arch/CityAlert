<?php

trait Timestampable
{
    // -------------------------------------------------------
    // ATTRIBUTS
    // -------------------------------------------------------
    private string $created_at;
    private string $updated_at;

    // -------------------------------------------------------
    // GETTERS
    // -------------------------------------------------------
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    // -------------------------------------------------------
    // Met à jour la date de modification
    // -------------------------------------------------------
    public function setUpdatedAt(): void
    {
        $this->updated_at = date('Y-m-d H:i:s');
    }

    // -------------------------------------------------------
    // Initialise les dates à la création
    // -------------------------------------------------------
    public function initTimestamps(): void
    {
        $now = date('Y-m-d H:i:s');
        $this->created_at = $now;
        $this->updated_at = $now;
    }

    // -------------------------------------------------------
    // Formate la date pour l'affichage
    // -------------------------------------------------------
    public function formatDate(string $date): string
    {
        return date('d/m/Y à H:i', strtotime($date));
    }
}