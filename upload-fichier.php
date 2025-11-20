<?php
$pageTitle = 'Upload Fichier Impression - Imprixo';
$pageDescription = '';
include __DIR__ . '/includes/header.php';
?>

<div class="container">
        <div class="upload-card">
            <h1>📤 Upload Fichier d'Impression</h1>
            <p class="subtitle">Uploadez vos fichiers pour impression professionnelle</p>

            <!-- Zone de drop -->
            <div class="upload-zone" id="uploadZone">
                <div class="upload-icon">📁</div>
                <div class="upload-text">Glissez-déposez votre fichier ici</div>
                <div class="upload-hint">ou cliquez pour parcourir</div>
                <input type="file" id="fileInput" accept=".jpg,.jpeg,.png,.pdf,.tif,.tiff,.psd,.ai,.eps,.svg,.zip">
            </div>

            <!-- Barre de progression -->
            <div class="progress-bar" id="progressBar">
                <div class="progress-bar-bg">
                    <div class="progress-bar-fill" id="progressFill"></div>
                </div>
                <div class="progress-text" id="progressText">Upload en cours...</div>
            </div>

            <!-- Informations fichier -->
            <div class="file-info" id="fileInfo">
                <h3 style="margin-bottom: 15px;">📋 Informations du fichier</h3>
                <div class="file-info-row">
                    <span class="file-info-label">Nom</span>
                    <span class="file-info-value" id="fileName">-</span>
                </div>
                <div class="file-info-row">
                    <span class="file-info-label">Taille</span>
                    <span class="file-info-value" id="fileSize">-</span>
                </div>
                <div class="file-info-row">
                    <span class="file-info-label">Type</span>
                    <span class="file-info-value" id="fileType">-</span>
                </div>
                <div class="file-info-row" id="dimensionsRow" style="display: none;">
                    <span class="file-info-label">Dimensions</span>
                    <span class="file-info-value" id="fileDimensions">-</span>
                </div>
                <div class="file-info-row" id="resolutionRow" style="display: none;">
                    <span class="file-info-label">Résolution</span>
                    <span class="file-info-value" id="fileResolution">-</span>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="buttons" id="buttons">
                <button class="btn btn-primary" id="uploadBtn">📤 Uploader</button>
                <button class="btn btn-secondary" id="cancelBtn">Annuler</button>
            </div>

            <!-- Alertes -->
            <div class="alert alert-success" id="successAlert">
                ✅ <strong>Fichier uploadé avec succès !</strong>
                <div style="margin-top: 10px; font-size: 14px;" id="successMessage"></div>
            </div>

            <div class="alert alert-error" id="errorAlert">
                ❌ <strong>Erreur :</strong>
                <div style="margin-top: 10px;" id="errorMessage"></div>
            </div>

            <div class="alert alert-warning" id="warningAlert">
                ⚠️ <strong>Avertissement :</strong>
                <div style="margin-top: 10px;" id="warningMessage"></div>
            </div>

            <!-- Spécifications -->
            <div class="file-specs">
                <h3>📝 Spécifications Recommandées</h3>
                <ul>
                    <li>Formats acceptés : JPG, PNG, PDF, TIFF, PSD, AI, EPS, SVG</li>
                    <li>Taille maximale : 100 MB par fichier</li>
                    <li>Résolution recommandée : 300 DPI minimum</li>
                    <li>Espace couleur : CMYK pour impression (RVB accepté)</li>
                    <li>Fond perdu : +3mm de chaque côté recommandé</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        const uploadZone = document.getElementById('uploadZone');
        const fileInput = document.getElementById('fileInput');
        const fileInfo = document.getElementById('fileInfo');
        const buttons = document.getElementById('buttons');
        const progressBar = document.getElementById('progressBar');
        const progressFill = document.getElementById('progressFill');
        const progressText = document.getElementById('progressText');
        const uploadBtn = document.getElementById('uploadBtn');
        const cancelBtn = document.getElementById('cancelBtn');

        let selectedFile = null;

        // Click sur la zone d'upload
        uploadZone.addEventListener('click', () => {
            fileInput.click();
        });

        // Sélection fichier
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFile(e.target.files[0]);
            }
        });

        // Drag & Drop
        uploadZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadZone.classList.add('dragover');
        });

        uploadZone.addEventListener('dragleave', () => {
            uploadZone.classList.remove('dragover');
        });

        uploadZone.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadZone.classList.remove('dragover');

            if (e.dataTransfer.files.length > 0) {
                handleFile(e.dataTransfer.files[0]);
            }
        });

        // Gérer fichier sélectionné
        function handleFile(file) {
            selectedFile = file;

            // Afficher les infos
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileSize').textContent = formatFileSize(file.size);
            document.getElementById('fileType').textContent = file.type || 'Inconnu';

            fileInfo.classList.add('visible');
            buttons.classList.add('visible');

            // Masquer les alertes
            hideAllAlerts();
        }

        // Upload fichier
        uploadBtn.addEventListener('click', async () => {
            if (!selectedFile) return;

            const formData = new FormData();
            formData.append('fichier', selectedFile);

            // Afficher progression
            progressBar.classList.add('visible');
            progressFill.style.width = '0%';
            progressText.textContent = 'Upload en cours...';
            buttons.classList.remove('visible');
            hideAllAlerts();

            try {
                const xhr = new XMLHttpRequest();

                // Progression
                xhr.upload.addEventListener('progress', (e) => {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        progressFill.style.width = percent + '%';
                        progressText.textContent = `Upload en cours... ${percent}%`;
                    }
                });

                // Terminé
                xhr.addEventListener('load', () => {
                    progressBar.classList.remove('visible');

                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        showSuccess(response);
                    } else {
                        const error = JSON.parse(xhr.responseText);
                        showError(error.error || 'Erreur inconnue');
                    }
                });

                // Erreur
                xhr.addEventListener('error', () => {
                    progressBar.classList.remove('visible');
                    showError('Erreur réseau lors de l\'upload');
                });

                xhr.open('POST', '/api/upload-fichier.php');
                xhr.send(formData);

            } catch (error) {
                progressBar.classList.remove('visible');
                showError(error.message);
            }
        });

        // Annuler
        cancelBtn.addEventListener('click', () => {
            reset();
        });

        // Afficher succès
        function showSuccess(response) {
            const fichier = response.fichier;

            let message = `
                <strong>Fichier :</strong> ${fichier.nom_original}<br>
                <strong>Taille :</strong> ${fichier.taille_mb} MB<br>
                <strong>Statut :</strong> ${fichier.statut}
            `;

            if (fichier.dimensions_mm) {
                message += `<br><strong>Dimensions :</strong> ${fichier.dimensions_mm} mm`;
                document.getElementById('dimensionsRow').style.display = 'flex';
                document.getElementById('fileDimensions').textContent = fichier.dimensions_mm + ' mm';
            }

            if (fichier.resolution_dpi) {
                message += `<br><strong>Résolution :</strong> ${fichier.resolution_dpi} DPI`;
                document.getElementById('resolutionRow').style.display = 'flex';
                document.getElementById('fileResolution').textContent = fichier.resolution_dpi + ' DPI';
            }

            document.getElementById('successMessage').innerHTML = message;
            document.getElementById('successAlert').classList.add('visible');

            // Avertissement si résolution faible
            if (fichier.avertissement) {
                document.getElementById('warningMessage').textContent = fichier.avertissement;
                document.getElementById('warningAlert').classList.add('visible');
            }
        }

        // Afficher erreur
        function showError(message) {
            document.getElementById('errorMessage').textContent = message;
            document.getElementById('errorAlert').classList.add('visible');
            buttons.classList.add('visible');
        }

        // Masquer toutes les alertes
        function hideAllAlerts() {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.classList.remove('visible');
            });
        }

        // Reset
        function reset() {
            selectedFile = null;
            fileInput.value = '';
            fileInfo.classList.remove('visible');
            buttons.classList.remove('visible');
            progressBar.classList.remove('visible');
            hideAllAlerts();
        }

        // Formater taille fichier
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
