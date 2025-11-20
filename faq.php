<?php
$pageTitle = 'FAQ - Questions Fréquentes | Imprixo Impression Grand Format';
$pageDescription = 'Retrouvez les réponses à toutes vos questions sur l\'impression grand format : délais, formats, tarifs, fichiers, livraison. Support Imprixo.';
include __DIR__ . '/includes/header.php';
?>

<!-- Header chargé dynamiquement -->

    <!-- Hero Section -->
    <section class="gradient-bg text-white py-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">
                Questions Fréquentes
            </h1>
            <p class="text-xl md:text-2xl opacity-90 max-w-3xl mx-auto mb-8">
                Trouvez rapidement les réponses à toutes vos questions
            </p>

            <!-- Search Box -->
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input
                    type="text"
                    id="search-faq"
                    class="search-input"
                    placeholder="Rechercher une question..."
                    onkeyup="searchFAQ()"
                >
            </div>
        </div>
    </section>

    <!-- Quick Links -->
    <section class="max-w-7xl mx-auto px-4 py-8 -mt-8">
        <div class="quick-links">
            <a href="#commandes" class="quick-link-card">
                <div class="quick-link-icon">📦</div>
                <div class="font-bold">Commandes</div>
            </a>
            <a href="#delais" class="quick-link-card">
                <div class="quick-link-icon">⏱️</div>
                <div class="font-bold">Délais & Livraison</div>
            </a>
            <a href="#fichiers" class="quick-link-card">
                <div class="quick-link-icon">📁</div>
                <div class="font-bold">Fichiers & Formats</div>
            </a>
            <a href="#paiement" class="quick-link-card">
                <div class="quick-link-icon">💳</div>
                <div class="font-bold">Paiement</div>
            </a>
        </div>
    </section>

    <!-- FAQ Content -->
    <section class="max-w-4xl mx-auto px-4 py-8">
        <!-- Commandes & Devis -->
        <div class="faq-category" id="commandes" data-category="commandes">
            <div class="category-icon">📦</div>
            <h2 class="text-2xl font-bold mb-6">
                <span class="gradient-text">Commandes & Devis</span>
            </h2>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <span>Comment passer une commande sur Imprixo ?</span>
                    <span class="faq-icon">▼</span>
                </div>
                <div class="faq-answer">
                    Pour passer commande, sélectionnez votre produit dans notre catalogue, personnalisez vos options (format, quantité, finition), téléchargez votre fichier et ajoutez au panier. Validez votre commande et procédez au paiement sécurisé. Vous recevrez immédiatement une confirmation par email.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <span>Comment obtenir un devis personnalisé ?</span>
                    <span class="faq-icon">▼</span>
                </div>
                <div class="faq-answer">
                    Vous pouvez utiliser notre calculateur en ligne sur la page <a href="/tarifs.html" class="text-blue-600 hover:underline">Tarifs</a>, ou nous contacter directement via le formulaire de contact ou par téléphone au 01 23 45 67 89. Nous vous répondons sous 24h avec un devis détaillé et gratuit.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <span>Puis-je modifier ou annuler ma commande ?</span>
                    <span class="faq-icon">▼</span>
                </div>
                <div class="faq-answer">
                    Les modifications sont possibles dans les 2 heures suivant la validation de votre commande. Au-delà, si la production a démarré, nous ne pourrons malheureusement plus modifier la commande. Contactez-nous rapidement au 01 23 45 67 89 pour toute demande.
                </div>
            </div>
        </div>

        <!-- Délais & Livraison -->
        <div class="faq-category" id="delais" data-category="delais">
            <div class="category-icon">⏱️</div>
            <h2 class="text-2xl font-bold mb-6">
                <span class="gradient-text">Délais & Livraison</span>
            </h2>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <span>Quels sont vos délais de livraison ?</span>
                    <span class="faq-icon">▼</span>
                </div>
                <div class="faq-answer">
                    Nos délais standard sont de <strong>3 à 5 jours ouvrés</strong> après validation de votre commande et de vos fichiers. Pour les commandes urgentes, nous proposons une option <strong>Express 24-48h</strong> avec supplément. Les délais peuvent varier selon le produit et la quantité.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <span>Comment suivre ma livraison ?</span>
                    <span class="faq-icon">▼</span>
                </div>
                <div class="faq-answer">
                    Dès l'expédition de votre commande, vous recevez un email avec le numéro de suivi de votre colis. Vous pouvez suivre votre livraison en temps réel sur le site du transporteur. Vous pouvez également consulter l'état de votre commande dans votre espace client.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <span>Livrez-vous partout en France ?</span>
                    <span class="faq-icon">▼</span>
                </div>
                <div class="faq-answer">
                    Oui, nous livrons partout en France métropolitaine, DOM-TOM et même en Europe. Les frais de livraison sont calculés automatiquement selon votre adresse et le poids de votre commande. Livraison gratuite en France métropolitaine pour les commandes de plus de 150€.
                </div>
            </div>
        </div>

        <!-- Fichiers & Formats -->
        <div class="faq-category" id="fichiers" data-category="fichiers">
            <div class="category-icon">📁</div>
            <h2 class="text-2xl font-bold mb-6">
                <span class="gradient-text">Fichiers & Formats</span>
            </h2>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <span>Quels formats de fichiers acceptez-vous ?</span>
                    <span class="faq-icon">▼</span>
                </div>
                <div class="faq-answer">
                    Nous acceptons les formats suivants : <strong>PDF, AI, EPS, PSD, TIFF, JPG, PNG</strong>. Le PDF haute définition est notre format préféré. Assurez-vous que vos fichiers sont en <strong>CMJN</strong>, avec une résolution minimale de <strong>150 DPI</strong> (300 DPI recommandé).
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <span>Quelles dimensions puis-je commander ?</span>
                    <span class="faq-icon">▼</span>
                </div>
                <div class="faq-answer">
                    Nous proposons tous les formats standards (A4, A3, A2, A1, A0) et des <strong>formats personnalisés</strong> jusqu'à <strong>3m x 2m</strong> pour les supports rigides et jusqu'à <strong>5m de largeur</strong> pour les bâches et textiles. Contactez-nous pour les dimensions spéciales.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <span>Comment préparer correctement mes fichiers ?</span>
                    <span class="faq-icon">▼</span>
                </div>
                <div class="faq-answer">
                    Pour un résultat optimal :<br>
                    • Utilisez le mode colorimétrique <strong>CMJN</strong><br>
                    • Résolution minimale <strong>150 DPI</strong> (300 DPI recommandé)<br>
                    • Ajoutez <strong>3mm de fonds perdus</strong> sur chaque côté<br>
                    • Convertissez les textes en tracés<br>
                    • Enregistrez en PDF haute qualité<br>
                    Téléchargez notre <a href="/guide-technique.pdf" class="text-blue-600 hover:underline">guide technique complet</a>.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <span>Proposez-vous un service de création graphique ?</span>
                    <span class="faq-icon">▼</span>
                </div>
                <div class="faq-answer">
                    Oui ! Notre équipe de graphistes peut créer ou adapter vos visuels. Nous proposons des services de retouche, mise en page, adaptation de format et création complète. Contactez-nous pour un devis personnalisé selon vos besoins.
                </div>
            </div>
        </div>

        <!-- Paiement & Facturation -->
        <div class="faq-category" id="paiement" data-category="paiement">
            <div class="category-icon">💳</div>
            <h2 class="text-2xl font-bold mb-6">
                <span class="gradient-text">Paiement & Facturation</span>
            </h2>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <span>Quels moyens de paiement acceptez-vous ?</span>
                    <span class="faq-icon">▼</span>
                </div>
                <div class="faq-answer">
                    Nous acceptons :<br>
                    • <strong>Carte bancaire</strong> (Visa, Mastercard, CB) - Paiement sécurisé 3D Secure<br>
                    • <strong>Virement bancaire</strong> (RIB envoyé par email)<br>
                    • <strong>PayPal</strong><br>
                    • <strong>Paiement à 30 jours</strong> sur facture (réservé aux professionnels après validation)
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <span>Le paiement en ligne est-il sécurisé ?</span>
                    <span class="faq-icon">▼</span>
                </div>
                <div class="faq-answer">
                    Absolument ! Notre site utilise le protocole <strong>SSL/TLS</strong> et tous les paiements par carte bancaire sont protégés par <strong>3D Secure</strong>. Nous ne conservons aucune donnée bancaire sur nos serveurs. Vos informations sont traitées directement par notre prestataire de paiement certifié PCI-DSS.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <span>Comment obtenir une facture ?</span>
                    <span class="faq-icon">▼</span>
                </div>
                <div class="faq-answer">
                    Votre facture est générée automatiquement et envoyée par email dès la validation de votre commande. Vous pouvez également la télécharger à tout moment depuis votre espace client dans la section "Mes commandes".
                </div>
            </div>
        </div>

        <!-- Qualité & SAV -->
        <div class="faq-category" id="qualite" data-category="qualite">
            <div class="category-icon">✨</div>
            <h2 class="text-2xl font-bold mb-6">
                <span class="gradient-text">Qualité & Service Après-Vente</span>
            </h2>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <span>Proposez-vous une garantie qualité ?</span>
                    <span class="faq-icon">▼</span>
                </div>
                <div class="faq-answer">
                    Oui, nous garantissons la qualité professionnelle de toutes nos impressions. Si vous n'êtes pas satisfait du résultat, nous nous engageons à réimprimer votre commande gratuitement ou à vous rembourser intégralement. Votre satisfaction est notre priorité !
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <span>Que faire si ma commande arrive endommagée ?</span>
                    <span class="faq-icon">▼</span>
                </div>
                <div class="faq-answer">
                    Contactez-nous immédiatement par email avec des photos du colis et des produits endommagés. Nous traiterons votre demande en priorité et organiserons le renvoi ou le remboursement selon votre préférence. Tous nos colis sont assurés.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <span>Puis-je demander un échantillon avant de commander ?</span>
                    <span class="faq-icon">▼</span>
                </div>
                <div class="faq-answer">
                    Pour les commandes importantes, nous pouvons vous envoyer des échantillons de matériaux ou réaliser un BAT (Bon À Tirer) pour validation. Contactez notre service commercial au 01 23 45 67 89 pour en discuter.
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="gradient-bg text-white py-16 mt-16">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold mb-6">
                Vous n'avez pas trouvé la réponse ?
            </h2>
            <p class="text-xl mb-8 opacity-90">
                Notre équipe est là pour vous aider !
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="/contact.html" class="bg-white text-purple-600 px-8 py-4 rounded-lg font-bold text-lg hover:shadow-xl transition-all">
                    ✉️ Nous contacter
                </a>
                <a href="tel:+33123456789" class="bg-red-600 text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-red-700 transition-all">
                    📞 01 23 45 67 89
                </a>
            </div>
        </div>
    </section>

    <!-- Footer chargé dynamiquement -->

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

        // Toggle FAQ Item
        function toggleFAQ(element) {
            const faqItem = element.closest('.faq-item');
            const isActive = faqItem.classList.contains('active');

            // Fermer tous les autres items de la même catégorie
            const category = faqItem.closest('.faq-category');
            category.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('active');
            });

            // Toggle l'item actuel
            if (!isActive) {
                faqItem.classList.add('active');
            }
        }

        // Recherche dans la FAQ
        function searchFAQ() {
            const searchTerm = document.getElementById('search-faq').value.toLowerCase();
            const faqItems = document.querySelectorAll('.faq-item');
            const categories = document.querySelectorAll('.faq-category');

            if (searchTerm === '') {
                // Tout afficher
                categories.forEach(cat => cat.style.display = 'block');
                faqItems.forEach(item => item.style.display = 'block');
                return;
            }

            // Chercher dans les questions et réponses
            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question span').textContent.toLowerCase();
                const answer = item.querySelector('.faq-answer').textContent.toLowerCase();

                if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                    item.style.display = 'block';
                    item.classList.add('active'); // Ouvrir les résultats
                } else {
                    item.style.display = 'none';
                }
            });

            // Masquer les catégories vides
            categories.forEach(cat => {
                const visibleItems = cat.querySelectorAll('.faq-item[style*="display: block"]');
                if (visibleItems.length === 0) {
                    cat.style.display = 'none';
                } else {
                    cat.style.display = 'block';
                }
            });
        }

        // Ouvrir automatiquement une question depuis l'ancre
        document.addEventListener('DOMContentLoaded', function() {
            const hash = window.location.hash;
            if (hash) {
                const section = document.querySelector(hash);
                if (section) {
                    setTimeout(() => {
                        section.scrollIntoView({ behavior: 'smooth' });
                    }, 500);
                }
            }
        });
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
