<?php
trait Timestampable
{
    private string $created_at;
    private string $updated_at;

    public function getCreatedAt(): string { return $this->created_at; }
    public function getUpdatedAt(): string { return $this->updated_at; }

    public function setUpdatedAt(): void
    {
        $this->updated_at = date('Y-m-d H:i:s');
    }

    public function initTimestamps(): void
    {
        $now = date('Y-m-d H:i:s');
        $this->created_at = $now;
        $this->updated_at = $now;
    }

    public function formatDate(string $date): string
    {
        return date('d/m/Y à H:i', strtotime($date));
    }
}