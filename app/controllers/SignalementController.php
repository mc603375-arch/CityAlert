<?php
require_once ROOT_PATH . '/app/models/repositories/SignalementRepository.php';
require_once ROOT_PATH . '/app/models/repositories/CommentaireRepository.php';
require_once ROOT_PATH . '/app/controllers/DashboardController.php';

class SignalementController
{
    private SignalementRepository $repo;

    public function __construct()
    {
        $this->repo = new SignalementRepository();
    }

    private function requireAuth(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/login'); exit;
        }
    }

    public function liste(): void
    {
        $this->requireAuth();
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $limite  = 10;
        $filtres = [
            'statut'       => $_GET['statut']       ?? '',
            'categorie_id' => $_GET['categorie_id'] ?? '',
        ];
        if ($_SESSION['user']['role'] === ROLE_CITOYEN) {
            $filtres['user_id'] = $_SESSION['user']['id'];
        }
        $signalements = $this->repo->paginer($page, $limite, $filtres);
        $total        = $this->repo->compter($filtres);
        $totalPages   = (int)ceil($total / $limite);
        $categories   = Database::getInstance()->getConnexion()
                            ->query("SELECT * FROM categories ORDER BY libelle")->fetchAll();
        $erreur  = $_SESSION['erreur']  ?? null;
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['erreur'], $_SESSION['success']);
        require VIEW_PATH . '/signalements/liste.php';
    }

    public function detail(): void
    {
        $this->requireAuth();
        $id           = (int)($_GET['id'] ?? 0);
        $signalement  = $this->repo->findById($id);
        $historique   = $this->repo->getHistorique($id);
        $commentaires = (new CommentaireRepository())->findBySignalement($id);
        $db           = Database::getInstance()->getConnexion();

        $stmtCat = $db->prepare("SELECT * FROM categories WHERE id=:id");
        $stmtCat->execute([':id' => $signalement['categorie_id']]);
        $categorie = Categorie::fromArray($stmtCat->fetch());

        // Récupère les agents pour l'assignation (admin seulement)
        $agents = $db->query(
            "SELECT id, nom, prenom FROM users WHERE role = 'agent' ORDER BY nom"
        )->fetchAll();

        $erreur  = $_SESSION['erreur']  ?? null;
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['erreur'], $_SESSION['success']);

        require VIEW_PATH . '/signalements/detail.php';
    }

    public function ajouter(): void
    {
        $this->requireAuth();
        if ($_SESSION['user']['role'] !== ROLE_CITOYEN) {
            header('Location: ' . BASE_URL . '/signalements'); exit;
        }
        $categories = Database::getInstance()->getConnexion()
                         ->query("SELECT * FROM categories ORDER BY libelle")->fetchAll();
        $erreur = $_SESSION['erreur'] ?? null;
        unset($_SESSION['erreur']);
        require VIEW_PATH . '/signalements/ajouter.php';
    }

    public function traiterAjout(): void
    {
        $this->requireAuth();
        $erreurs = $this->valider($_POST);
        if (!empty($erreurs)) {
            $_SESSION['erreur'] = implode('<br>', $erreurs);
            header('Location: ' . BASE_URL . '/signalements/ajouter'); exit;
        }

        $db      = Database::getInstance()->getConnexion();
        $stmtCat = $db->prepare("SELECT * FROM categories WHERE id=:id");
        $stmtCat->execute([':id' => (int)$_POST['categorie_id']]);
        $catData   = $stmtCat->fetch();
        $categorie = Categorie::fromArray($catData);
        $photo     = $this->gererUpload();

        $ok = $this->repo->save([
            'titre'        => htmlspecialchars(trim($_POST['titre'])),
            'description'  => htmlspecialchars(trim($_POST['description'])),
            'adresse'      => htmlspecialchars(trim($_POST['adresse'])),
            'photo'        => $photo,
            'statut'       => 'nouveau',
            'priorite'     => $categorie->getPriorite(),
            'user_id'      => $_SESSION['user']['id'],
            'categorie_id' => (int)$_POST['categorie_id'],
            'agent_id'     => null,
            'lat'          => !empty($_POST['lat']) ? (float)$_POST['lat'] : null,
            'lng'          => !empty($_POST['lng']) ? (float)$_POST['lng'] : null,
        ]);

        $_SESSION[$ok ? 'success' : 'erreur'] = $ok
            ? "Signalement créé avec succès !"
            : "Erreur lors de la création.";
        header('Location: ' . BASE_URL . ($ok ? '/signalements' : '/signalements/ajouter'));
        exit;
    }

    public function modifier(): void
    {
        $this->requireAuth();
        $id          = (int)($_GET['id'] ?? 0);
        $signalement = $this->repo->findById($id);
        $this->verifierPropriete($signalement);
        $categories  = Database::getInstance()->getConnexion()
                           ->query("SELECT * FROM categories ORDER BY libelle")->fetchAll();
        $erreur = $_SESSION['erreur'] ?? null;
        unset($_SESSION['erreur']);
        require VIEW_PATH . '/signalements/modifier.php';
    }

    public function traiterModifier(): void
    {
        $this->requireAuth();
        $id          = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
        $signalement = $this->repo->findById($id);
        $this->verifierPropriete($signalement);

        $erreurs = $this->valider($_POST);
        if (!empty($erreurs)) {
            $_SESSION['erreur'] = implode('<br>', $erreurs);
            header('Location: ' . BASE_URL . '/signalements/modifier?id=' . $id); exit;
        }

        $photo = !empty($_FILES['photo']['name'])
            ? $this->gererUpload()
            : ($signalement['photo'] ?? null);

        $ok = $this->repo->save([
            'id'           => $id,
            'titre'        => htmlspecialchars(trim($_POST['titre'])),
            'description'  => htmlspecialchars(trim($_POST['description'])),
            'adresse'      => htmlspecialchars(trim($_POST['adresse'])),
            'photo'        => $photo,
            'statut'       => $signalement['statut'],
            'priorite'     => $signalement['priorite'],
            'user_id'      => $signalement['user_id'],
            'categorie_id' => (int)$_POST['categorie_id'],
            'agent_id'     => $signalement['agent_id'] ?? null,
            'lat'          => $signalement['lat'],
            'lng'          => $signalement['lng'],
        ]);

        $_SESSION[$ok ? 'success' : 'erreur'] = $ok
            ? "Signalement mis à jour."
            : "Erreur lors de la mise à jour.";
        header('Location: ' . BASE_URL . '/signalements/detail?id=' . $id);
        exit;
    }

    public function supprimer(): void
    {
        $this->requireAuth();
        $id          = (int)($_GET['id'] ?? 0);
        $signalement = $this->repo->findById($id);
        $this->verifierPropriete($signalement);
        if ($signalement['statut'] !== 'nouveau') {
            $_SESSION['erreur'] = "Ce signalement ne peut plus être supprimé.";
            header('Location: ' . BASE_URL . '/signalements/detail?id=' . $id); exit;
        }
        $this->repo->delete($id);
        $_SESSION['success'] = "Signalement supprimé.";
        header('Location: ' . BASE_URL . '/signalements'); exit;
    }

    public function changerStatut(): void
    {
        $this->requireAuth();
        if ($_SESSION['user']['role'] === ROLE_CITOYEN) {
            header('Location: ' . BASE_URL . '/signalements'); exit;
        }

        $id     = (int)($_POST['id']            ?? 0);
        $statut = trim($_POST['nouveau_statut'] ?? '');
        $com    = trim($_POST['commentaire']    ?? '');

        if (!in_array($statut, ['en_cours', 'resolu', 'rejete'], true)) {
            $_SESSION['erreur'] = "Statut invalide.";
            header('Location: ' . BASE_URL . '/signalements/detail?id=' . $id); exit;
        }

        $signalement  = $this->repo->findById($id);
        $ancienStatut = $signalement['statut'];

        $this->repo->changerStatut($id, $statut, $com, (int)$_SESSION['user']['id']);

        // Notification email au citoyen
        $db   = Database::getInstance()->getConnexion();
        $stmt = $db->prepare(
            "SELECT u.email FROM users u
             JOIN signalements s ON s.user_id = u.id
             WHERE s.id = :id"
        );
        $stmt->execute([':id' => $id]);
        $email = $stmt->fetchColumn();
        if ($email) {
            DashboardController::envoyerNotification(
                $email,
                $signalement['titre'],
                $ancienStatut,
                $statut
            );
        }

        $_SESSION['success'] = "Statut mis à jour.";
        header('Location: ' . BASE_URL . '/signalements/detail?id=' . $id);
        exit;
    }

    // -------------------------------------------------------
    // ASSIGNER UN AGENT (admin seulement)
    // -------------------------------------------------------
    public function assigner(): void
    {
        $this->requireAuth();
        if ($_SESSION['user']['role'] !== ROLE_ADMIN) {
            header('Location: ' . BASE_URL . '/signalements'); exit;
        }

        $id      = (int)($_POST['id']       ?? 0);
        $agentId = (int)($_POST['agent_id'] ?? 0);

        if ($id === 0 || $agentId === 0) {
            $_SESSION['erreur'] = "Données invalides.";
            header('Location: ' . BASE_URL . '/signalements/detail?id=' . $id); exit;
        }

        $db   = Database::getInstance()->getConnexion();
        $stmt = $db->prepare(
            "UPDATE signalements SET agent_id = :agent_id, statut = 'en_cours' WHERE id = :id"
        );
        $ok = $stmt->execute([':agent_id' => $agentId, ':id' => $id]);

        $_SESSION[$ok ? 'success' : 'erreur'] = $ok
            ? "Agent assigné et signalement passé en cours."
            : "Erreur lors de l'assignation.";

        header('Location: ' . BASE_URL . '/signalements/detail?id=' . $id);
        exit;
    }

    // -------------------------------------------------------
    // MÉTHODES PRIVÉES
    // -------------------------------------------------------
    private function valider(array $d): array
    {
        $e = [];
        if (empty(trim($d['titre']       ?? ''))) $e[] = "Le titre est obligatoire.";
        if (empty(trim($d['description'] ?? ''))) $e[] = "La description est obligatoire.";
        if (empty(trim($d['adresse']     ?? ''))) $e[] = "L'adresse est obligatoire.";
        if (empty($d['categorie_id']))             $e[] = "La catégorie est obligatoire.";
        return $e;
    }

    private function verifierPropriete(array $s): void
    {
        $user = $_SESSION['user'];
        if ($user['role'] !== ROLE_ADMIN && (int)$s['user_id'] !== (int)$user['id']) {
            http_response_code(403);
            require VIEW_PATH . '/errors/403.php'; exit;
        }
    }

    private function gererUpload(): ?string
    {
        if (empty($_FILES['photo']['name'])) return null;
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        $mime    = mime_content_type($_FILES['photo']['tmp_name']);
        if (!in_array($mime, $allowed, true)) {
            $_SESSION['erreur'] = "Format non autorisé (jpg, png, webp).";
            return null;
        }
        if ($_FILES['photo']['size'] > MAX_FILE_SIZE) {
            $_SESSION['erreur'] = "Photo trop lourde (max 2 Mo).";
            return null;
        }

        $ext  = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $name = 'sig_' . uniqid('', true) . '.' . strtolower($ext);
        $dest = UPLOAD_PATH . '/' . $name;

        if (!is_dir(UPLOAD_PATH)) {
            mkdir(UPLOAD_PATH, 0755, true);
        }

        move_uploaded_file($_FILES['photo']['tmp_name'], $dest);

        if (function_exists('imagecreatefromjpeg') &&
            in_array($mime, ['image/jpeg','image/png','image/webp'])) {
            $this->redimensionner($dest, $mime, 1200);
        }

        return $name;
    }

    private function redimensionner(string $path, string $mime, int $maxW): void
    {
        [$w, $h] = getimagesize($path);
        if ($w <= $maxW) return;

        $ratio = $maxW / $w;
        $newH  = (int)($h * $ratio);

        $src = match($mime) {
            'image/png'  => imagecreatefrompng($path),
            'image/webp' => imagecreatefromwebp($path),
            default      => imagecreatefromjpeg($path),
        };
        $dst = imagecreatetruecolor($maxW, $newH);

        if ($mime === 'image/png') {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $maxW, $newH, $w, $h);

        match($mime) {
            'image/png'  => imagepng($dst, $path, 8),
            'image/webp' => imagewebp($dst, $path, 85),
            default      => imagejpeg($dst, $path, 85),
        };

        imagedestroy($src);
        imagedestroy($dst);
    }
}