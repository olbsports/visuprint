<?php
$pageTitle = 'Tarifs - Calculateur de Prix | Imprixo Impression Grand Format';
$pageDescription = 'Tarifs transparents pour l\'impression grand format. Calculateur en ligne, prix dégressifs, devis instantané. Forex, Dibond, Bâches PVC à partir de 19€.';
include __DIR__ . '/includes/header.php';
?>

<!-- Header chargé dynamiquement -->
    <div id="header-placeholder"></div>

    <!-- Hero Section -->
    <section class="gradient-bg text-white py-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">
                Tarifs Transparents
            </h1>
            <p class="text-xl md:text-2xl opacity-90 max-w-3xl mx-auto">
                Prix compétitifs, qualité professionnelle garantie
            </p>
        </div>
    </section>

    <!-- Calculateur de Prix -->
    <section class="max-w-5xl mx-auto px-4">
        <div class="calculator-section">
            <div class="text-center mb-8">
                <h2 class="text-4xl font-bold mb-4">
                    <span class="gradient-text">Calculateur de Prix</span>
                </h2>
                <p class="text-gray-600 text-lg">
                    Obtenez un devis instantané pour votre projet
                </p>
            </div>

            <form id="price-calculator" onsubmit="calculatePrice(event)">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="form-label" for="product">
                            Type de produit *
                        </label>
                        <select id="product" class="form-select" required onchange="updateSizes()">
                            <option value="">Sélectionnez un produit</option>
                            <option value="forex" data-base-price="25">Forex 3mm</option>
                            <option value="dibond" data-base-price="35">Dibond 3mm</option>
                            <option value="bache" data-base-price="20">Bâche PVC</option>
                            <option value="kakemono" data-base-price="45">Kakémono</option>
                            <option value="adhesif" data-base-price="15">Adhésif</option>
                            <option value="poster" data-base-price="10">Poster papier</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="format">
                            Format *
                        </label>
                        <select id="format" class="form-select" required onchange="updatePrice()">
                            <option value="">Sélectionnez un format</option>
                            <option value="a4" data-multiplier="1">A4 (21x29.7cm)</option>
                            <option value="a3" data-multiplier="1.5">A3 (29.7x42cm)</option>
                            <option value="a2" data-multiplier="2">A2 (42x59.4cm)</option>
                            <option value="a1" data-multiplier="3">A1 (59.4x84.1cm)</option>
                            <option value="a0" data-multiplier="4.5">A0 (84.1x118.9cm)</option>
                            <option value="custom" data-multiplier="1">Format personnalisé</option>
                        </select>
                    </div>

                    <div class="form-group" id="custom-size-group" style="display: none;">
                        <label class="form-label" for="custom-width">
                            Largeur (cm) *
                        </label>
                        <input type="number" id="custom-width" class="form-input" min="10" max="500" placeholder="100">
                    </div>

                    <div class="form-group" id="custom-height-group" style="display: none;">
                        <label class="form-label" for="custom-height">
                            Hauteur (cm) *
                        </label>
                        <input type="number" id="custom-height" class="form-input" min="10" max="500" placeholder="150">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="quantity">
                            Quantité *
                        </label>
                        <select id="quantity" class="form-select" required onchange="updatePrice()">
                            <option value="1">1 exemplaire</option>
                            <option value="2">2 exemplaires (-5%)</option>
                            <option value="5">5 exemplaires (-10%)</option>
                            <option value="10">10 exemplaires (-15%)</option>
                            <option value="20">20 exemplaires (-20%)</option>
                            <option value="50">50 exemplaires (-25%)</option>
                            <option value="100">100 exemplaires (-30%)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="finishing">
                            Finition
                        </label>
                        <select id="finishing" class="form-select" onchange="updatePrice()">
                            <option value="none" data-price="0">Sans finition</option>
                            <option value="lamination" data-price="5">Pelliculage (+5€/m²)</option>
                            <option value="uv" data-price="8">Vernis UV (+8€/m²)</option>
                            <option value="mounting" data-price="10">Montage avec œillets (+10€)</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn-calculate mt-8">
                    💰 Calculer le prix
                </button>
            </form>

            <!-- Résultat du calcul -->
            <div id="result-box" class="result-box">
                <div class="text-xl opacity-90">Prix estimé</div>
                <div class="result-price" id="final-price">0€</div>
                <div class="text-lg opacity-90">
                    <span id="price-details"></span>
                </div>
                <div class="mt-6 flex gap-4 justify-center flex-wrap">
                    <a href="/catalogue.html" class="bg-white text-purple-600 px-8 py-3 rounded-lg font-bold hover:shadow-xl transition-all">
                        📦 Commander
                    </a>
                    <a href="/contact.html" class="bg-red-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-red-700 transition-all">
                        ✉️ Demander un devis détaillé
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Grille tarifaire -->
    <section class="max-w-7xl mx-auto px-4 py-16">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold mb-4">
                <span class="gradient-text">Grille Tarifaire</span>
            </h2>
            <p class="text-gray-600 text-lg">
                Nos prix indicatifs par produit (prix au m² TTC)
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            <!-- Supports Rigides -->
            <div>
                <h3 class="text-2xl font-bold mb-6 flex items-center gap-3">
                    <span class="text-3xl">🔲</span>
                    Supports Rigides
                </h3>
                <table class="table-pricing">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Prix/m²</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Forex 3mm</strong></td>
                            <td><span class="text-2xl font-bold text-purple-600">25€</span></td>
                        </tr>
                        <tr>
                            <td><strong>Forex 5mm</strong></td>
                            <td><span class="text-2xl font-bold text-purple-600">30€</span></td>
                        </tr>
                        <tr>
                            <td><strong>Dibond 3mm</strong></td>
                            <td><span class="text-2xl font-bold text-purple-600">35€</span></td>
                        </tr>
                        <tr>
                            <td><strong>Plexiglas</strong></td>
                            <td><span class="text-2xl font-bold text-purple-600">45€</span></td>
                        </tr>
                        <tr>
                            <td><strong>Carton Plume</strong></td>
                            <td><span class="text-2xl font-bold text-purple-600">20€</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Bâches & Textiles -->
            <div>
                <h3 class="text-2xl font-bold mb-6 flex items-center gap-3">
                    <span class="text-3xl">🎪</span>
                    Bâches & Textiles
                </h3>
                <table class="table-pricing">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Prix/m²</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Bâche PVC 440g</strong></td>
                            <td><span class="text-2xl font-bold text-purple-600">20€</span></td>
                        </tr>
                        <tr>
                            <td><strong>Bâche Mesh</strong></td>
                            <td><span class="text-2xl font-bold text-purple-600">25€</span></td>
                        </tr>
                        <tr>
                            <td><strong>Kakémono</strong></td>
                            <td><span class="text-2xl font-bold text-purple-600">45€</span></td>
                        </tr>
                        <tr>
                            <td><strong>Roll-up</strong></td>
                            <td><span class="text-2xl font-bold text-purple-600">89€</span> <span class="badge">Avec structure</span></td>
                        </tr>
                        <tr>
                            <td><strong>Toile Canvas</strong></td>
                            <td><span class="text-2xl font-bold text-purple-600">35€</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Adhésifs -->
            <div>
                <h3 class="text-2xl font-bold mb-6 flex items-center gap-3">
                    <span class="text-3xl">📌</span>
                    Adhésifs & Stickers
                </h3>
                <table class="table-pricing">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Prix/m²</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Adhésif Vinyle</strong></td>
                            <td><span class="text-2xl font-bold text-purple-600">15€</span></td>
                        </tr>
                        <tr>
                            <td><strong>Adhésif Microperforé</strong></td>
                            <td><span class="text-2xl font-bold text-purple-600">28€</span></td>
                        </tr>
                        <tr>
                            <td><strong>Adhésif Sol</strong></td>
                            <td><span class="text-2xl font-bold text-purple-600">32€</span></td>
                        </tr>
                        <tr>
                            <td><strong>Stickers découpés</strong></td>
                            <td><span class="text-2xl font-bold text-purple-600">18€</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Papiers -->
            <div>
                <h3 class="text-2xl font-bold mb-6 flex items-center gap-3">
                    <span class="text-3xl">📄</span>
                    Papiers & Affiches
                </h3>
                <table class="table-pricing">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Prix/m²</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Poster 150g</strong></td>
                            <td><span class="text-2xl font-bold text-purple-600">10€</span></td>
                        </tr>
                        <tr>
                            <td><strong>Poster 200g</strong></td>
                            <td><span class="text-2xl font-bold text-purple-600">12€</span></td>
                        </tr>
                        <tr>
                            <td><strong>Affiche blueback</strong></td>
                            <td><span class="text-2xl font-bold text-purple-600">15€</span></td>
                        </tr>
                        <tr>
                            <td><strong>Papier photo</strong></td>
                            <td><span class="text-2xl font-bold text-purple-600">18€</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Prix Dégressifs -->
    <section class="max-w-7xl mx-auto px-4 py-16">
        <div class="pricing-card">
            <div class="text-center mb-8">
                <h2 class="text-4xl font-bold mb-4">
                    <span class="gradient-text">Prix Dégressifs</span>
                </h2>
                <p class="text-gray-600 text-lg">
                    Plus vous commandez, plus vous économisez !
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 text-center">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-3xl font-bold text-gray-400 mb-2">1</div>
                    <div class="text-sm text-gray-600">Prix normal</div>
                </div>
                <div class="p-4 bg-purple-50 rounded-lg">
                    <div class="text-3xl font-bold text-purple-600 mb-2">2+</div>
                    <div class="text-sm font-bold text-purple-600">-5%</div>
                </div>
                <div class="p-4 bg-purple-100 rounded-lg">
                    <div class="text-3xl font-bold text-purple-700 mb-2">5+</div>
                    <div class="text-sm font-bold text-purple-700">-10%</div>
                </div>
                <div class="p-4 bg-purple-200 rounded-lg">
                    <div class="text-3xl font-bold text-purple-800 mb-2">10+</div>
                    <div class="text-sm font-bold text-purple-800">-15%</div>
                </div>
                <div class="p-4 bg-purple-300 rounded-lg">
                    <div class="text-3xl font-bold text-purple-900 mb-2">20+</div>
                    <div class="text-sm font-bold text-purple-900">-20%</div>
                </div>
                <div class="p-4 bg-red-400 rounded-lg">
                    <div class="text-3xl font-bold text-white mb-2">50+</div>
                    <div class="text-sm font-bold text-white">-25%</div>
                </div>
                <div class="p-4 gradient-bg rounded-lg">
                    <div class="text-3xl font-bold text-white mb-2">100+</div>
                    <div class="text-sm font-bold text-white">-30%</div>
                </div>
            </div>

            <div class="mt-8 p-6 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg">
                <h3 class="text-xl font-bold mb-4">📊 Exemple concret :</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                    <div>
                        <div class="text-gray-600 mb-2">1 Forex A1</div>
                        <div class="text-3xl font-bold text-gray-700">45€</div>
                    </div>
                    <div>
                        <div class="text-gray-600 mb-2">10 Forex A1 (-15%)</div>
                        <div class="text-3xl font-bold text-purple-600">38,25€</div>
                        <div class="text-sm text-green-600 font-bold">Économie: 67,50€</div>
                    </div>
                    <div>
                        <div class="text-gray-600 mb-2">50 Forex A1 (-25%)</div>
                        <div class="text-3xl font-bold text-red-600">33,75€</div>
                        <div class="text-sm text-green-600 font-bold">Économie: 562,50€</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Avantages -->
    <section class="max-w-7xl mx-auto px-4 py-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="pricing-card text-center">
                <div class="text-5xl mb-4">🎯</div>
                <h3 class="text-xl font-bold mb-3">Prix Transparents</h3>
                <ul class="feature-list text-left">
                    <li>Pas de frais cachés</li>
                    <li>Devis instantané</li>
                    <li>Prix dégressifs automatiques</li>
                </ul>
            </div>

            <div class="pricing-card text-center featured">
                <div class="text-5xl mb-4">⚡</div>
                <h3 class="text-xl font-bold mb-3">Livraison Rapide</h3>
                <ul class="feature-list text-left">
                    <li>Standard: 3-5 jours</li>
                    <li>Express: 24-48h</li>
                    <li>Gratuite dès 150€</li>
                </ul>
            </div>

            <div class="pricing-card text-center">
                <div class="text-5xl mb-4">✨</div>
                <h3 class="text-xl font-bold mb-3">Qualité Pro</h3>
                <ul class="feature-list text-left">
                    <li>Garantie satisfait ou remboursé</li>
                    <li>Matériaux premium</li>
                    <li>Support technique inclus</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="gradient-bg text-white py-16 mt-16">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold mb-6">
                Besoin d'un devis personnalisé ?
            </h2>
            <p class="text-xl mb-8 opacity-90">
                Notre équipe commerciale est à votre disposition pour étudier votre projet
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="/contact.html" class="bg-white text-purple-600 px-8 py-4 rounded-lg font-bold text-lg hover:shadow-xl transition-all">
                    ✉️ Demander un devis
                </a>
                <a href="tel:+33123456789" class="bg-red-600 text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-red-700 transition-all">
                    📞 01 23 45 67 89
                </a>
            </div>
        </div>
    </section>

    <!-- Footer chargé dynamiquement -->
    <div id="footer-placeholder"></div>

    <!-- Scripts -->
    <script>
        // Charger le header
        fetch('/includes/header.html')
            .then(r => r.text())
            .then(html => document.getElementById('header-placeholder').innerHTML = html)
            .catch(err => console.error('Erreur chargement header:', err));

        // Charger le footer
        fetch('/includes/footer.html')
            .then(r => r.text())
            .then(html => document.getElementById('footer-placeholder').innerHTML = html)
            .catch(err => console.error('Erreur chargement footer:', err));

        // Afficher/masquer les champs de format personnalisé
        function updateSizes() {
            const format = document.getElementById('format');
            const customWidth = document.getElementById('custom-size-group');
            const customHeight = document.getElementById('custom-height-group');

            if (format.value === 'custom') {
                customWidth.style.display = 'block';
                customHeight.style.display = 'block';
            } else {
                customWidth.style.display = 'none';
                customHeight.style.display = 'none';
            }
        }

        // Calcul du prix
        function calculatePrice(event) {
            event.preventDefault();

            const product = document.getElementById('product');
            const format = document.getElementById('format');
            const quantity = parseInt(document.getElementById('quantity').value);
            const finishing = document.getElementById('finishing');

            if (!product.value || !format.value) {
                alert('Veuillez sélectionner un produit et un format');
                return;
            }

            // Prix de base
            const basePrice = parseFloat(product.selectedOptions[0].dataset.basePrice);
            const formatMultiplier = parseFloat(format.selectedOptions[0].dataset.multiplier);

            // Calculer la surface (pour format personnalisé)
            let surface = formatMultiplier;
            if (format.value === 'custom') {
                const width = parseFloat(document.getElementById('custom-width').value) || 0;
                const height = parseFloat(document.getElementById('custom-height').value) || 0;
                if (width === 0 || height === 0) {
                    alert('Veuillez entrer les dimensions personnalisées');
                    return;
                }
                surface = (width * height) / 10000; // Convertir cm² en m²
            }

            // Prix unitaire
            let unitPrice = basePrice * surface;

            // Ajout finition
            const finishingPrice = parseFloat(finishing.selectedOptions[0].dataset.price) * surface;
            unitPrice += finishingPrice;

            // Prix total avant remise
            let totalPrice = unitPrice * quantity;

            // Remises dégressives
            let discount = 0;
            if (quantity >= 100) discount = 0.30;
            else if (quantity >= 50) discount = 0.25;
            else if (quantity >= 20) discount = 0.20;
            else if (quantity >= 10) discount = 0.15;
            else if (quantity >= 5) discount = 0.10;
            else if (quantity >= 2) discount = 0.05;

            const finalPrice = totalPrice * (1 - discount);

            // Afficher le résultat
            document.getElementById('final-price').textContent = finalPrice.toFixed(2) + '€';

            let details = quantity + ' exemplaire(s)';
            if (discount > 0) {
                details += ' • Remise -' + (discount * 100) + '%';
            }
            details += ' • ' + unitPrice.toFixed(2) + '€/unité';

            document.getElementById('price-details').textContent = details;
            document.getElementById('result-box').style.display = 'block';

            // Scroll vers le résultat
            document.getElementById('result-box').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        // Mise à jour du prix en temps réel (optionnel)
        function updatePrice() {
            // Cette fonction pourrait recalculer et afficher le prix en temps réel
            // Pour l'instant, elle ne fait rien (le calcul se fait au submit)
        }
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
