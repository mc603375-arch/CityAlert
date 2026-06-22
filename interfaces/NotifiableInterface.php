<?php

interface NotifiableInterface
{
    // -------------------------------------------------------
    // Envoyer une notification à l'utilisateur
    // -------------------------------------------------------
    public function notifier(string $message): void;

    // -------------------------------------------------------
    // Récupérer l'email pour la notification
    // -------------------------------------------------------
    public function getEmail(): string;
}