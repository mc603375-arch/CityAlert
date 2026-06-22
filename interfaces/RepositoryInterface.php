<?php

interface RepositoryInterface
{
    // -------------------------------------------------------
    // Récupérer tous les enregistrements
    // -------------------------------------------------------
    public function findAll(): array;

    // -------------------------------------------------------
    // Récupérer un enregistrement par son id
    // -------------------------------------------------------
    public function findById(int $id): array|false;

    // -------------------------------------------------------
    // Sauvegarder (créer ou modifier)
    // -------------------------------------------------------
    public function save(array $data): bool;

    // -------------------------------------------------------
    // Supprimer un enregistrement
    // -------------------------------------------------------
    public function delete(int $id): bool;
}