<?php $pageTitle = "Nouveau signalement — CityAlert"; ?>
<?php require_once VIEW_PATH . '/layout/header.php'; ?>
<?php require_once VIEW_PATH . '/layout/navbar.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>📝 Nouveau signalement</h1>
        <a href="<?= BASE_URL ?>/signalements" class="btn btn-outline">← Retour</a>
    </div>

    <?php if (!empty($erreur)): ?>
        <div class="alert alert-danger">⚠️ <?= $erreur ?></div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" action="<?= BASE_URL ?>/signalements/ajouter" enctype="multipart/form-data" id="sigForm">

            <div class="form-row">
                <div class="form-group">
                    <label for="titre">Titre *</label>
                    <input type="text" id="titre" name="titre" placeholder="Ex: Nid-de-poule dangereux" required>
                </div>
                <div class="form-group">
                    <label for="categorie_id">Catégorie *</label>
                    <select id="categorie_id" name="categorie_id" required>
                        <option value="">Choisir une catégorie</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['libelle']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description" rows="4"
                    placeholder="Décrivez le problème : nature, gravité, depuis quand..." required></textarea>
            </div>

            <div class="form-group">
                <label for="adresse">Adresse *</label>
                <input type="text" id="adresse" name="adresse"
                    placeholder="Ex: Rue 10, Médina, Dakar" required
                    oninput="geocodeAdresse(this.value)">
                <small>Saisissez l'adresse pour placer le marqueur sur la carte</small>
            </div>

            <!-- Carte Leaflet -->
            <div class="form-group">
                <label>Localisation sur la carte</label>
                <div id="map-pick" style="height:260px;border-radius:12px;overflow:hidden;border:1.5px solid var(--border)"></div>
                <input type="hidden" name="lat" id="lat">
                <input type="hidden" name="lng" id="lng">
                <small>Cliquez sur la carte pour ajuster la position exacte</small>
            </div>

            <!-- Zone photo -->
            <div class="form-group">
                <label>Photo du problème</label>

                <div class="photo-zone" id="photoZone" onclick="document.getElementById('photoFile').click()">
                    <span class="photo-zone-icon">📸</span>
                    <div class="photo-zone-title">Ajouter une photo</div>
                    <div class="photo-zone-sub">Cliquez pour choisir ou glissez une image ici</div>
                </div>

                <img id="photoPreview" class="photo-preview-img" src="" alt="" style="display:none;margin-top:.75rem;border-radius:12px;max-height:280px;object-fit:cover;width:100%">

                <div class="photo-actions" id="photoActions" style="display:none">
                    <button type="button" class="btn btn-outline btn-sm" onclick="openCamera()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z"/></svg>
                        Reprendre avec la caméra
                    </button>
                    <button type="button" class="btn btn-outline btn-sm" onclick="clearPhoto()">
                        Supprimer la photo
                    </button>
                </div>

                <!-- Boutons capture initiaux -->
                <div class="photo-actions" id="photoBtnsInit">
                    <button type="button" class="btn btn-outline btn-sm" onclick="openCamera()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/></svg>
                        Prendre une photo
                    </button>
                    <button type="button" class="btn btn-ghost btn-sm" onclick="document.getElementById('photoFile').click()">
                        Choisir dans la galerie
                    </button>
                </div>

                <input type="file" id="photoFile" name="photo" accept="image/*" style="display:none" onchange="previewFile(this)">
                <small>JPG, PNG, WEBP — Max 2 Mo</small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/></svg>
                    Envoyer le signalement
                </button>
                <a href="<?= BASE_URL ?>/signalements" class="btn btn-outline">Annuler</a>
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
            <button type="button" class="btn btn-outline btn-sm" onclick="switchCamera()" title="Changer de caméra" style="color:#fff;border-color:rgba(255,255,255,.2)">
                🔄
            </button>
            <button type="button" class="btn-shutter" onclick="capturePhoto()" title="Prendre la photo">📷</button>
            <button type="button" class="btn btn-outline btn-sm" onclick="closeCamera()" style="color:#fff;border-color:rgba(255,255,255,.2)">
                Annuler
            </button>
        </div>
    </div>
</div>

<script>
// ── Carte Leaflet ─────────────────────────────────────────
let map, marker;
window.addEventListener('load', () => {
    map = L.map('map-pick').setView([14.6928, -17.4467], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    map.on('click', (e) => {
        placeMarker(e.latlng.lat, e.latlng.lng);
    });

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(pos => {
            map.setView([pos.coords.latitude, pos.coords.longitude], 15);
        });
    }
});

function placeMarker(lat, lng) {
    if (marker) map.removeLayer(marker);
    marker = L.marker([lat, lng]).addTo(map);
    document.getElementById('lat').value = lat;
    document.getElementById('lng').value = lng;
}

let geocodeTimer;
function geocodeAdresse(val) {
    clearTimeout(geocodeTimer);
    if (val.length < 5) return;
    geocodeTimer = setTimeout(() => {
        fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(val)}&format=json&limit=1`)
            .then(r => r.json()).then(d => {
                if (d.length > 0) {
                    const lat = parseFloat(d[0].lat), lng = parseFloat(d[0].lon);
                    map.setView([lat, lng], 16);
                    placeMarker(lat, lng);
                }
            }).catch(() => {});
    }, 700);
}

// ── Photo Capture ─────────────────────────────────────────
let stream, facingMode = 'environment';

function openCamera() {
    const modal = document.getElementById('cameraModal');
    modal.classList.add('open');
    startCamera();
}

function startCamera() {
    if (stream) stream.getTracks().forEach(t => t.stop());
    navigator.mediaDevices.getUserMedia({
        video: { facingMode, width: { ideal: 1280 }, height: { ideal: 720 } }
    }).then(s => {
        stream = s;
        document.getElementById('cameraStream').srcObject = s;
    }).catch(() => {
        alert('Impossible d\'accéder à la caméra. Vérifiez les autorisations.');
        closeCamera();
    });
}

function switchCamera() {
    facingMode = facingMode === 'environment' ? 'user' : 'environment';
    startCamera();
}

function capturePhoto() {
    const video  = document.getElementById('cameraStream');
    const canvas = document.getElementById('cameraCanvas');
    canvas.width  = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);

    canvas.toBlob(blob => {
        const file = new File([blob], 'photo_' + Date.now() + '.jpg', { type: 'image/jpeg' });
        const dt   = new DataTransfer();
        dt.items.add(file);
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
    if (input.files && input.files[0]) {
        showPreview(URL.createObjectURL(input.files[0]));
    }
}

function showPreview(url) {
    const zone    = document.getElementById('photoZone');
    const preview = document.getElementById('photoPreview');
    const actions = document.getElementById('photoActions');
    const initBtns= document.getElementById('photoBtnsInit');
    zone.style.display    = 'none';
    preview.src           = url;
    preview.style.display = 'block';
    actions.style.display = 'flex';
    initBtns.style.display= 'none';
}

function clearPhoto() {
    document.getElementById('photoFile').value = '';
    document.getElementById('photoPreview').style.display = 'none';
    document.getElementById('photoZone').style.display    = 'block';
    document.getElementById('photoActions').style.display  = 'none';
    document.getElementById('photoBtnsInit').style.display = 'flex';
}

// Drag & drop
const zone = document.getElementById('photoZone');
zone.addEventListener('dragover', e => { e.preventDefault(); zone.style.borderColor = 'var(--blue)'; });
zone.addEventListener('dragleave', () => { zone.style.borderColor = ''; });
zone.addEventListener('drop', e => {
    e.preventDefault(); zone.style.borderColor = '';
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const dt = new DataTransfer(); dt.items.add(file);
        document.getElementById('photoFile').files = dt.files;
        showPreview(URL.createObjectURL(file));
    }
});
</script>

<?php require_once VIEW_PATH . '/layout/footer.php'; ?>