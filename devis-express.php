<?php
$pageTitle = 'Devis Gratuit Impression Grand Format en 30 secondes | Imprixo';
$pageDescription = '💨 Devis express gratuit impression grand format ! Réponse immédiate · Sans engagement · Prix dégressifs · Livraison 48h · +50 supports disponibles';
include __DIR__ . '/includes/header.php';
?>

<div class="container">

        <div class="header">
            <h1>💨 Devis Express Gratuit</h1>
            <p class="subtitle">Obtenez votre prix en 30 secondes · Sans engagement · Réponse immédiate</p>

            <div class="benefits">
                <div class="benefit">
                    <span>✓</span>
                    <span>100% Gratuit</span>
                </div>
                <div class="benefit">
                    <span>✓</span>
                    <span>Sans engagement</span>
                </div>
                <div class="benefit">
                    <span>✓</span>
                    <span>Réponse immédiate</span>
                </div>
                <div class="benefit">
                    <span>✓</span>
                    <span>Prix dégressifs automatiques</span>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="progress-bar">
            <div class="steps">
                <div class="progress-line" id="progressLine" style="width: 0%;"></div>

                <div class="step active" data-step="1">
                    <div class="step-circle">1</div>
                    <div class="step-label">Support</div>
                </div>

                <div class="step" data-step="2">
                    <div class="step-circle">2</div>
                    <div class="step-label">Dimensions</div>
                </div>

                <div class="step" data-step="3">
                    <div class="step-circle">3</div>
                    <div class="step-label">Options</div>
                </div>

                <div class="step" data-step="4">
                    <div class="step-circle">4</div>
                    <div class="step-label">Contact</div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="form-card">
            <form id="devisForm">

                <!-- Step 1: Support -->
                <div class="form-section active" data-section="1">
                    <h2 class="section-title">Quel support souhaitez-vous ?</h2>
                    <p class="section-desc">Sélectionnez le matériau qui correspond à votre besoin</p>

                    <div class="radio-cards">
                        <div class="radio-card">
                            <input type="radio" name="support" id="support-dibond" value="dibond" required>
                            <label for="support-dibond" class="radio-card-label">
                                <div class="radio-card-icon">📐</div>
                                <div class="radio-card-title">Dibond 3mm</div>
                                <div class="radio-card-desc">Alu composite premium</div>
                            </label>
                        </div>

                        <div class="radio-card">
                            <input type="radio" name="support" id="support-forex" value="forex">
                            <label for="support-forex" class="radio-card-label">
                                <div class="radio-card-icon">📋</div>
                                <div class="radio-card-title">Forex 3-10mm</div>
                                <div class="radio-card-desc">PVC expansé rigide</div>
                            </label>
                        </div>

                        <div class="radio-card">
                            <input type="radio" name="support" id="support-bache" value="bache">
                            <label for="support-bache" class="radio-card-label">
                                <div class="radio-card-icon">🎪</div>
                                <div class="radio-card-title">Bâche PVC</div>
                                <div class="radio-card-desc">Souple résistant</div>
                            </label>
                        </div>

                        <div class="radio-card">
                            <input type="radio" name="support" id="support-autre" value="autre">
                            <label for="support-autre" class="radio-card-label">
                                <div class="radio-card-icon">📦</div>
                                <div class="radio-card-title">Autre support</div>
                                <div class="radio-card-desc">Textile, vinyle...</div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Dimensions -->
                <div class="form-section" data-section="2">
                    <h2 class="section-title">Quelles dimensions ?</h2>
                    <p class="section-desc">Indiquez les dimensions souhaitées pour votre impression</p>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Largeur (cm)</label>
                            <input type="number" name="largeur" id="largeur" placeholder="Ex: 120" min="10" max="500" required>
                            <small>Min 10cm · Max 500cm</small>
                        </div>

                        <div class="form-group">
                            <label>Hauteur (cm)</label>
                            <input type="number" name="hauteur" id="hauteur" placeholder="Ex: 80" min="10" max="500" required>
                            <small>Min 10cm · Max 500cm</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Quantité</label>
                        <input type="number" name="quantite" id="quantite" placeholder="Nombre d'exemplaires" min="1" value="1" required>
                        <small>💡 Prix dégressifs automatiques à partir de 5m²</small>
                    </div>

                    <div id="surfaceDisplay" style="background: var(--bg-hover); padding: 15px; border-radius: var(--radius-sm); text-align: center; margin-top: 20px;">
                        <strong>Surface totale :</strong> <span id="surfaceValue">0</span> m²
                    </div>
                </div>

                <!-- Step 3: Options -->
                <div class="form-section" data-section="3">
                    <h2 class="section-title">Options & finitions</h2>
                    <p class="section-desc">Personnalisez votre commande selon vos besoins</p>

                    <div class="form-group">
                        <label>Impression</label>
                        <select name="impression" id="impression" required>
                            <option value="">Sélectionner...</option>
                            <option value="simple">Simple face</option>
                            <option value="double">Double face</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Finition (optionnel)</label>
                        <select name="finition" id="finition">
                            <option value="">Aucune finition</option>
                            <option value="pelliculage-mat">Pelliculage mat</option>
                            <option value="pelliculage-brillant">Pelliculage brillant</option>
                            <option value="vernis">Vernis sélectif</option>
                            <option value="oeillets">Œillets (bâches)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Délai souhaité</label>
                        <select name="delai" id="delai" required>
                            <option value="standard">Standard (48-72h)</option>
                            <option value="express">Express (24h) +30%</option>
                            <option value="urgent">Urgent (12h) +50%</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Commentaires / précisions (optionnel)</label>
                        <textarea name="commentaires" id="commentaires" placeholder="Décrivez votre projet, posez vos questions..."></textarea>
                    </div>
                </div>

                <!-- Step 4: Contact -->
                <div class="form-section" data-section="4">
                    <h2 class="section-title">Vos coordonnées</h2>
                    <p class="section-desc">Pour recevoir votre devis personnalisé par email</p>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Prénom</label>
                            <input type="text" name="prenom" id="prenom" placeholder="Votre prénom" required>
                        </div>

                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" name="nom" id="nom" placeholder="Votre nom" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="email" placeholder="votre@email.com" required>
                        <small>Votre devis sera envoyé à cette adresse</small>
                    </div>

                    <div class="form-group">
                        <label>Téléphone (optionnel)</label>
                        <input type="tel" name="telephone" id="telephone" placeholder="06 12 34 56 78">
                        <small>Pour un suivi personnalisé de votre projet</small>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="newsletter" id="newsletter">
                            Recevoir nos offres promotionnelles
                        </label>
                    </div>

                    <!-- Price Preview -->
                    <div class="price-preview" id="pricePreview">
                        <div class="price-preview-label">Estimation de votre commande</div>
                        <div class="price-preview-value" id="estimatedPrice">245,00€</div>
                        <div class="price-preview-unit">HT · Livraison incluse</div>
                        <div class="price-preview-degressive">
                            💡 Prix dégressifs appliqués automatiquement
                        </div>
                    </div>
                </div>

                <!-- Success -->
                <div class="form-section" data-section="5">
                    <div class="success-message">
                        <div class="success-icon">✅</div>
                        <h2 class="success-title">Devis envoyé avec succès !</h2>
                        <p class="success-text">
                            Nous avons bien reçu votre demande de devis.<br>
                            Vous allez recevoir une confirmation par email dans quelques instants.
                        </p>
                        <div class="success-ref">
                            Référence : <strong id="devisRef">DV-2024-001234</strong>
                        </div>
                        <p style="color: var(--text-muted); margin-bottom: 30px;">
                            Notre équipe reviendra vers vous sous 2 heures ouvrées<br>
                            avec un devis détaillé et personnalisé.
                        </p>
                        <a href="/" class="btn btn-primary">Retour à l'accueil</a>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="form-actions" id="formActions">
                    <button type="button" class="btn btn-secondary" id="btnPrev" style="display: none;">
                        ← Précédent
                    </button>
                    <button type="button" class="btn btn-primary" id="btnNext">
                        Suivant →
                    </button>
                </div>

            </form>
        </div>

    </div>

    <script>
        // Form navigation
        let currentStep = 1;
        const totalSteps = 4;

        const form = document.getElementById('devisForm');
        const btnNext = document.getElementById('btnNext');
        const btnPrev = document.getElementById('btnPrev');
        const progressLine = document.getElementById('progressLine');

        function updateStep() {
            // Hide all sections
            document.querySelectorAll('.form-section').forEach(section => {
                section.classList.remove('active');
            });

            // Show current section
            document.querySelector(`[data-section="${currentStep}"]`).classList.add('active');

            // Update steps UI
            document.querySelectorAll('.step').forEach((step, index) => {
                const stepNum = index + 1;
                step.classList.remove('active', 'completed');

                if (stepNum < currentStep) {
                    step.classList.add('completed');
                } else if (stepNum === currentStep) {
                    step.classList.add('active');
                }
            });

            // Update progress line
            const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;
            progressLine.style.width = progress + '%';

            // Update buttons
            btnPrev.style.display = currentStep > 1 ? 'block' : 'none';

            if (currentStep === totalSteps) {
                btnNext.textContent = '📧 Envoyer ma demande';
            } else {
                btnNext.textContent = 'Suivant →';
            }

            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        btnNext.addEventListener('click', () => {
            // Validate current section
            const currentSection = document.querySelector(`[data-section="${currentStep}"]`);
            const inputs = currentSection.querySelectorAll('input[required], select[required], textarea[required]');

            let isValid = true;
            inputs.forEach(input => {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    isValid = false;
                }
            });

            if (!isValid) return;

            if (currentStep < totalSteps) {
                currentStep++;
                updateStep();
            } else {
                // Submit form
                submitForm();
            }
        });

        btnPrev.addEventListener('click', () => {
            if (currentStep > 1) {
                currentStep--;
                updateStep();
            }
        });

        function submitForm() {
            // Simulate form submission
            const formData = new FormData(form);

            // Generate reference
            const ref = 'DV-' + new Date().getFullYear() + '-' + String(Math.floor(Math.random() * 999999)).padStart(6, '0');
            document.getElementById('devisRef').textContent = ref;

            // Show success
            currentStep = 5;
            document.getElementById('formActions').style.display = 'none';
            updateStep();

            // In real app, send to API
            console.log('Form data:', Object.fromEntries(formData));

            // Send to backend (example)
            // fetch('/api/devis', {
            //     method: 'POST',
            //     body: formData
            // });
        }

        // Calculate surface
        const largeur = document.getElementById('largeur');
        const hauteur = document.getElementById('hauteur');
        const quantite = document.getElementById('quantite');
        const surfaceValue = document.getElementById('surfaceValue');

        function calculateSurface() {
            const l = parseFloat(largeur.value) || 0;
            const h = parseFloat(hauteur.value) || 0;
            const q = parseInt(quantite.value) || 1;

            const surface = (l / 100) * (h / 100) * q;
            surfaceValue.textContent = surface.toFixed(2);

            // Update price estimate
            updatePriceEstimate(surface);
        }

        function updatePriceEstimate(surface) {
            // Simple price calculation (example)
            let pricePerM2 = 25; // Base price

            // Degressive pricing
            if (surface >= 50) pricePerM2 = 18;
            else if (surface >= 20) pricePerM2 = 20;
            else if (surface >= 10) pricePerM2 = 22;

            const total = surface * pricePerM2;
            document.getElementById('estimatedPrice').textContent = total.toFixed(2) + '€';
        }

        largeur.addEventListener('input', calculateSurface);
        hauteur.addEventListener('input', calculateSurface);
        quantite.addEventListener('input', calculateSurface);

        // Init
        updateStep();
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
