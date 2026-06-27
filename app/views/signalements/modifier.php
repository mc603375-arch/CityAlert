<?php $pageTitle = "Modifier — CityAlert"; ?>
<?php require_once VIEW_PATH . '/layout/header.php'; ?>
<?php require_once VIEW_PATH . '/layout/navbar.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>✏️ Modifier le signalement</h1>
        <a href="<?= BASE_URL ?>/signalements/detail?id=<?= $signalement['id'] ?>" class="btn btn-outline btn-sm">← Retour</a>
    </div>

    <?php if (!empty($erreur)): ?>
        <div class="alert alert-danger">⚠️ <?= $erreur ?></div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" action="<?= BASE_URL ?>/signalements/modifier?id=<?= $signalement['id'] ?>" enctype="multipart/form-data">

            <div class="form-row">
                <div class="form-group">
                    <label for="titre">Titre *</label>
                    <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($signalement['titre']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="categorie_id">Catégorie *</label>
                    <select id="categorie_id" name="categorie_id" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $signalement['categorie_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['libelle']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description" rows="4" required><?= htmlspecialchars($signalement['description']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="adresse">Adresse *</label>
                <input type="text" id="adresse" name="adresse" value="<?= htmlspecialchars($signalement['adresse']) ?>" required>
            </div>

            <!-- Photo actuelle -->
            <?php if (!empty($signalement['photo'])): ?>
                <div class="form-group">
                    <label>Photo actuelle</label>
                    <img src="<?= UPLOAD_URL . '/' . htmlspecialchars($signalement['photo']) ?>"
                         alt="Photo actuelle" class="img-preview"
                         style="max-width:240px;border-radius:var(--r-lg);display:block">
                </div>
            <?php endif; ?>

            <!-- Nouvelle photo -->
            <div class="form-group">
                <label>Changer la photo</label>
                <div class="photo-zone" id="photoZone" onclick="document.getElementById('photoFile').click()">
                    <span class="photo-zone-icon">📸</span>
                    <div class="photo-zone-title">Cliquez pour choisir une nouvelle photo</div>
                    <div class="photo-zone-sub">JPG, PNG, WEBP — Max 2 Mo</div>
                </div>
                <img id="photoPreview" src="" alt="" style="display:none;margin-top:.75rem;border-radius:var(--r-lg);max-height:220px;object-fit:cover;width:100%">
                <div class="photo-actions" id="photoActions" style="display:none">
                    <button type="button" class="btn btn-outline btn-sm" onclick="openCamera()">
                        📷 Prendre avec la caméra
                    </button>
                    <button type="button" class="btn btn-ghost btn-sm" onclick="clearPhoto()">Supprimer</button>
                </div>
                <div class="photo-actions" id="photoBtnsInit">
                    <button type="button" class="btn btn-outline btn-sm" onclick="openCamera()">📷 Prendre une photo</button>
                    <button type="button" class="btn btn-ghost btn-sm" onclick="document.getElementById('photoFile').click()">Galerie</button>
                </div>
                <input type="file" id="photoFile" name="photo" accept="image/*" style="display:none" onchange="previewFile(this)">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-warning btn-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
                    Mettre à jour
                </button>
                <a href="<?= BASE_URL ?>/signalements/detail?id=<?= $signalement['id'] ?>" class="btn btn-outline">Annuler</a>
            </div>
        </form>
    </div>
</div>

<!-- Modale Caméra -->
<div class="camera-modal" id="cameraModal">
    <div class="camera-box">
        <div class="camera-header">
            <span>📸 Prendre une photo</span>
            <button class="camera-close" onclick="closeCamera()">✕</button>
        </div>
        <video id="cameraStream" autoplay playsinline muted></video>
        <canvas id="cameraCanvas" style="display:none"></canvas>
        <div class="camera-controls">
            <button type="button" class="btn btn-outline btn-sm" onclick="switchCamera()" style="color:#fff;border-color:rgba(255,255,255,.2)">🔄</button>
            <button type="button" class="btn-shutter" onclick="capturePhoto()">📷</button>
            <button type="button" class="btn btn-outline btn-sm" onclick="closeCamera()" style="color:#fff;border-color:rgba(255,255,255,.2)">Annuler</button>
        </div>
    </div>
</div>

<script>
let stream, facingMode = 'environment';

function openCamera() {
    document.getElementById('cameraModal').classList.add('open');
    startCamera();
}
function startCamera() {
    if (stream) stream.getTracks().forEach(t => t.stop());
    navigator.mediaDevices.getUserMedia({ video: { facingMode } })
        .then(s => { stream = s; document.getElementById('cameraStream').srcObject = s; })
        .catch(() => { alert('Caméra inaccessible.'); closeCamera(); });
}
function switchCamera() { facingMode = facingMode === 'environment' ? 'user' : 'environment'; startCamera(); }
function capturePhoto() {
    const video = document.getElementById('cameraStream');
    const canvas = document.getElementById('cameraCanvas');
    canvas.width = video.videoWidth; canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    canvas.toBlob(blob => {
        const file = new File([blob], 'photo_' + Date.now() + '.jpg', { type: 'image/jpeg' });
        const dt = new DataTransfer(); dt.items.add(file);
        document.getElementById('photoFile').files = dt.files;
        showPreview(URL.createObjectURL(blob));
        closeCamera();
    }, 'image/jpeg', 0.88);
}
function closeCamera() {
    if (stream) stream.getTracks().forEach(t => t.stop());
    document.getElementById('cameraModal').classList.remove('open');
}
function previewFile(input) {
    if (input.files && input.files[0]) showPreview(URL.createObjectURL(input.files[0]));
}
function showPreview(url) {
    document.getElementById('photoZone').style.display = 'none';
    const p = document.getElementById('photoPreview');
    p.src = url; p.style.display = 'block';
    document.getElementById('photoActions').style.display = 'flex';
    document.getElementById('photoBtnsInit').style.display = 'none';
}
function clearPhoto() {
    document.getElementById('photoFile').value = '';
    document.getElementById('photoPreview').style.display = 'none';
    document.getElementById('photoZone').style.display = 'block';
    document.getElementById('photoActions').style.display = 'none';
    document.getElementById('photoBtnsInit').style.display = 'flex';
}
</script>

<?php require_once VIEW_PATH . '/layout/footer.php'; ?>