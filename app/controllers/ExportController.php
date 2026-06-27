<?php
require_once ROOT_PATH . '/config/Database.php';

class ExportController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnexion();
    }

    public function csv(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== ROLE_ADMIN) {
            header('Location: ' . BASE_URL . '/login'); exit;
        }

        $stmt = $this->db->query(
            "SELECT s.id, s.titre, s.description, s.adresse, s.statut, s.priorite,
                    s.created_at, s.updated_at,
                    c.libelle AS categorie,
                    u.nom AS citoyen_nom, u.prenom AS citoyen_prenom, u.email AS citoyen_email
             FROM signalements s
             LEFT JOIN categories c ON c.id = s.categorie_id
             LEFT JOIN users u ON u.id = s.user_id
             ORDER BY s.created_at DESC"
        );
        $rows = $stmt->fetchAll();

        $filename = 'cityalert_signalements_' . date('Y-m-d') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');

        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8

        fputcsv($out, ['ID','Titre','Description','Adresse','Statut','Priorité','Catégorie','Citoyen','Email','Créé le','Mis à jour'], ';');

        foreach ($rows as $row) {
            fputcsv($out, [
                $row['id'],
                $row['titre'],
                $row['description'],
                $row['adresse'],
                $row['statut'],
                $row['priorite'],
                $row['categorie'],
                $row['citoyen_prenom'] . ' ' . $row['citoyen_nom'],
                $row['citoyen_email'],
                $row['created_at'],
                $row['updated_at'],
            ], ';');
        }
        fclose($out);
        exit;
    }
}