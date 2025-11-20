<?php
$pageTitle = 'Promotions Impression Grand Format -40% | Imprixo';
$pageDescription = '🔥 Promotions exceptionnelles impression grand format ! Jusqu\'à -40% sur Forex, Dibond, Bâches ✓ Offres limitées ✓ Livraison 48h ✓ Stock disponible';
include __DIR__ . '/includes/header.php';
?>

<div class="container">

        <!-- Filters -->
        <div class="filters">
            <div class="filter-group">
                <label>Catégorie</label>
                <select id="filter-category">
                    <option value="">Toutes les catégories</option>
                    <option value="pvc">Supports PVC rigides</option>
                    <option value="alu">Supports aluminium</option>
                    <option value="baches">Bâches souples</option>
                    <option value="textiles">Textiles</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Réduction</label>
                <select id="filter-discount">
                    <option value="">Toutes les réductions</option>
                    <option value="10">-10% et plus</option>
                    <option value="20">-20% et plus</option>
                    <option value="30">-30% et plus</option>
                    <option value="40">-40%</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Trier par</label>
                <select id="filter-sort">
                    <option value="discount">Réduction (décroissant)</option>
                    <option value="price-asc">Prix (croissant)</option>
                    <option value="price-desc">Prix (décroissant)</option>
                    <option value="popular">Les plus populaires</option>
                </select>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="products-grid">

            <!-- Product 1: Dibond 3mm -->
            <div class="product-card" data-category="alu" data-discount="40">
                <div class="promo-badge">-40%</div>
                <div class="product-image">📐</div>
                <div class="product-content">
                    <div class="product-category">Supports Aluminium</div>
                    <h3 class="product-title">Dibond 3mm</h3>
                    <p class="product-desc">Panneau composite aluminium premium pour enseignes extérieures durables</p>

                    <div class="timer-badge">
                        ⏰ Plus que 18 heures
                    </div>

                    <div class="price-section">
                        <span class="price-old">42,00€</span>
                        <span class="price-new">25,20€</span>
                        <span class="price-unit">/m²</span>
                        <span class="savings">Économisez 16,80€</span>
                    </div>

                    <ul class="product-features">
                        <li><span class="feature-icon">✓</span> Durée de vie 5-7 ans extérieur</li>
                        <li><span class="feature-icon">✓</span> Rigidité exceptionnelle</li>
                        <li><span class="feature-icon">✓</span> Livraison 48h</li>
                    </ul>

                    <a href="/produit/DIBOND-3MM.php" class="btn btn-primary">
                        Profiter de l'offre →
                    </a>
                </div>
            </div>

            <!-- Product 2: Forex 10mm -->
            <div class="product-card" data-category="pvc" data-discount="30">
                <div class="promo-badge">-30%</div>
                <div class="product-image">📋</div>
                <div class="product-content">
                    <div class="product-category">Supports PVC</div>
                    <h3 class="product-title">Forex 10mm</h3>
                    <p class="product-desc">PVC expansé ultra-rigide pour panneaux grand format</p>

                    <div class="timer-badge">
                        ⏰ Stock limité
                    </div>

                    <div class="price-section">
                        <span class="price-old">28,00€</span>
                        <span class="price-new">19,60€</span>
                        <span class="price-unit">/m²</span>
                        <span class="savings">Économisez 8,40€</span>
                    </div>

                    <ul class="product-features">
                        <li><span class="feature-icon">✓</span> Épaisseur 10mm ultra-rigide</li>
                        <li><span class="feature-icon">✓</span> Intérieur & court terme extérieur</li>
                        <li><span class="feature-icon">✓</span> Découpe facile</li>
                    </ul>

                    <a href="/produit/FOREX-10MM.php" class="btn btn-primary">
                        Profiter de l'offre →
                    </a>
                </div>
            </div>

            <!-- Product 3: Bâche M1 -->
            <div class="product-card" data-category="baches" data-discount="35">
                <div class="promo-badge">-35%</div>
                <div class="product-image">🎪</div>
                <div class="product-content">
                    <div class="product-category">Bâches souples</div>
                    <h3 class="product-title">Bâche M1 anti-feu 650g</h3>
                    <p class="product-desc">Bâche PVC M1 certifiée pour événements et ERP</p>

                    <div class="timer-badge">
                        ⏰ Offre flash
                    </div>

                    <div class="price-section">
                        <span class="price-old">32,00€</span>
                        <span class="price-new">20,80€</span>
                        <span class="price-unit">/m²</span>
                        <span class="savings">Économisez 11,20€</span>
                    </div>

                    <ul class="product-features">
                        <li><span class="feature-icon">✓</span> Certification M1 anti-feu</li>
                        <li><span class="feature-icon">✓</span> 650g/m² ultra-résistant</li>
                        <li><span class="feature-icon">✓</span> Œillets inclus</li>
                    </ul>

                    <a href="/produit/BLOCKOUT-650-B1.php" class="btn btn-primary">
                        Profiter de l'offre →
                    </a>
                </div>
            </div>

            <!-- Product 4: Kakémono -->
            <div class="product-card" data-category="textiles" data-discount="25">
                <div class="promo-badge">-25%</div>
                <div class="product-image">🎌</div>
                <div class="product-content">
                    <div class="product-category">Textiles</div>
                    <h3 class="product-title">Kakémono textile</h3>
                    <p class="product-desc">Support vertical enrouleur pour salons et événements</p>

                    <div class="timer-badge">
                        ⏰ Dernières pièces
                    </div>

                    <div class="price-section">
                        <span class="price-old">89,00€</span>
                        <span class="price-new">66,75€</span>
                        <span class="price-unit">/unité</span>
                        <span class="savings">Économisez 22,25€</span>
                    </div>

                    <ul class="product-features">
                        <li><span class="feature-icon">✓</span> Enrouleur alu inclus</li>
                        <li><span class="feature-icon">✓</span> Montage 30 secondes</li>
                        <li><span class="feature-icon">✓</span> Housse de transport</li>
                    </ul>

                    <a href="/configurateur.html" class="btn btn-primary">
                        Profiter de l'offre →
                    </a>
                </div>
            </div>

            <!-- Product 5: Forex 3mm -->
            <div class="product-card" data-category="pvc" data-discount="25">
                <div class="promo-badge">-25%</div>
                <div class="product-image">📄</div>
                <div class="product-content">
                    <div class="product-category">Supports PVC</div>
                    <h3 class="product-title">Forex 3mm</h3>
                    <p class="product-desc">PVC expansé léger pour PLV et affichage intérieur</p>

                    <div class="price-section">
                        <span class="price-old">16,00€</span>
                        <span class="price-new">12,00€</span>
                        <span class="price-unit">/m²</span>
                        <span class="savings">Économisez 4,00€</span>
                    </div>

                    <ul class="product-features">
                        <li><span class="feature-icon">✓</span> Ultra-léger 500g/m²</li>
                        <li><span class="feature-icon">✓</span> Découpe précise</li>
                        <li><span class="feature-icon">✓</span> Rapport qualité/prix imbattable</li>
                    </ul>

                    <a href="/produit/FOREX-3MM.php" class="btn btn-primary">
                        Profiter de l'offre →
                    </a>
                </div>
            </div>

            <!-- Product 6: Bâche mesh -->
            <div class="product-card" data-category="baches" data-discount="30">
                <div class="promo-badge">-30%</div>
                <div class="product-image">🕸️</div>
                <div class="product-content">
                    <div class="product-category">Bâches souples</div>
                    <h3 class="product-title">Bâche Mesh micro-perforée</h3>
                    <p class="product-desc">Bâche ajourée pour façades et zones ventées</p>

                    <div class="price-section">
                        <span class="price-old">25,00€</span>
                        <span class="price-new">17,50€</span>
                        <span class="price-unit">/m²</span>
                        <span class="savings">Économisez 7,50€</span>
                    </div>

                    <ul class="product-features">
                        <li><span class="feature-icon">✓</span> Laisse passer le vent</li>
                        <li><span class="feature-icon">✓</span> Idéal façades chantier</li>
                        <li><span class="feature-icon">✓</span> Haute résistance</li>
                    </ul>

                    <a href="/produit/MESH-330-B1-DOUBLE-SIDED.php" class="btn btn-primary">
                        Profiter de l'offre →
                    </a>
                </div>
            </div>

        </div>

    </div>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="faq-container">
            <h2 class="faq-title">Questions fréquentes promotions</h2>

            <div class="faq-item">
                <h3 class="faq-question">Combien de temps durent les promotions ?</h3>
                <p class="faq-answer">
                    Nos promotions sont valables pour une durée limitée, généralement entre 3 et 7 jours.
                    Le compte à rebours en haut de page indique le temps restant en temps réel.
                    Certaines offres flash peuvent durer seulement 24-48h, alors ne tardez pas !
                </p>
            </div>

            <div class="faq-item">
                <h3 class="faq-question">Les promotions sont-elles cumulables avec d'autres offres ?</h3>
                <p class="faq-answer">
                    Les promotions affichées sur cette page ne sont généralement pas cumulables avec d'autres codes promo ou réductions.
                    Cependant, les prix dégressifs en fonction de la quantité commandée restent toujours actifs et s'appliquent sur le prix promo.
                </p>
            </div>

            <div class="faq-item">
                <h3 class="faq-question">Y a-t-il une quantité minimum pour profiter des promos ?</h3>
                <p class="faq-answer">
                    Non ! Les promotions s'appliquent dès la première commande, sans minimum d'achat.
                    Vous pouvez commander 1m² ou 100m² et bénéficier de la même réduction affichée.
                    Les prix dégressifs viennent s'ajouter pour les grosses commandes.
                </p>
            </div>

            <div class="faq-item">
                <h3 class="faq-question">Le stock est-il garanti pour toute la durée de la promo ?</h3>
                <p class="faq-answer">
                    Nous faisons notre maximum pour maintenir le stock, mais sur certains produits très demandés,
                    les ruptures peuvent survenir avant la fin de la promotion. Les mentions "Stock limité" ou
                    "Dernières pièces" indiquent les produits à forte demande. Nous recommandons de commander rapidement.
                </p>
            </div>

            <div class="faq-item">
                <h3 class="faq-question">Les délais de livraison sont-ils maintenus pendant les promos ?</h3>
                <p class="faq-answer">
                    Oui ! Même pendant les périodes promotionnelles, nous maintenons nos délais de livraison standards :
                    48-72h pour la plupart des supports. Les commandes passées avant 14h sont généralement expédiées le jour même.
                    Le délai exact est indiqué sur chaque fiche produit.
                </p>
            </div>

            <div class="faq-item">
                <h3 class="faq-question">Puis-je faire un devis sur un produit en promotion ?</h3>
                <p class="faq-answer">
                    Absolument ! Utilisez notre outil de devis express pour obtenir un chiffrage précis incluant la promotion.
                    Le prix promo sera automatiquement appliqué dans votre devis tant que l'offre est active.
                    Les devis réalisés pendant la promo restent valables 15 jours.
                </p>
            </div>

        </div>
    </section>

    <!-- Schema.org FAQ -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [
            {
                "@type": "Question",
                "name": "Combien de temps durent les promotions ?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Nos promotions sont valables pour une durée limitée, généralement entre 3 et 7 jours. Le compte à rebours en haut de page indique le temps restant en temps réel. Certaines offres flash peuvent durer seulement 24-48h."
                }
            },
            {
                "@type": "Question",
                "name": "Les promotions sont-elles cumulables avec d'autres offres ?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Les promotions affichées sur cette page ne sont généralement pas cumulables avec d'autres codes promo ou réductions. Cependant, les prix dégressifs en fonction de la quantité commandée restent toujours actifs."
                }
            },
            {
                "@type": "Question",
                "name": "Y a-t-il une quantité minimum pour profiter des promos ?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Non ! Les promotions s'appliquent dès la première commande, sans minimum d'achat. Vous pouvez commander 1m² ou 100m² et bénéficier de la même réduction affichée."
                }
            }
        ]
    }
    </script>

    <script>
        // Countdown Timer
        function startCountdown() {
            const endDate = new Date();
            endDate.setDate(endDate.getDate() + 3);
            endDate.setHours(endDate.getHours() + 14);

            function updateCountdown() {
                const now = new Date().getTime();
                const distance = endDate - now;

                if (distance < 0) {
                    document.getElementById('countdown').innerHTML = '<div class="countdown-item"><span class="countdown-value">TERMINÉ</span></div>';
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById('days').textContent = String(days).padStart(2, '0');
                document.getElementById('hours').textContent = String(hours).padStart(2, '0');
                document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
                document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
            }

            updateCountdown();
            setInterval(updateCountdown, 1000);
        }

        // Filters
        const filterCategory = document.getElementById('filter-category');
        const filterDiscount = document.getElementById('filter-discount');
        const filterSort = document.getElementById('filter-sort');
        const productCards = document.querySelectorAll('.product-card');

        function applyFilters() {
            const category = filterCategory.value;
            const discount = parseInt(filterDiscount.value) || 0;

            let visibleCards = Array.from(productCards).filter(card => {
                const cardCategory = card.dataset.category;
                const cardDiscount = parseInt(card.dataset.discount);

                const categoryMatch = !category || cardCategory === category;
                const discountMatch = cardDiscount >= discount;

                if (categoryMatch && discountMatch) {
                    card.style.display = 'block';
                    return true;
                } else {
                    card.style.display = 'none';
                    return false;
                }
            });

            // Sort
            const sort = filterSort.value;
            if (sort === 'discount') {
                visibleCards.sort((a, b) => parseInt(b.dataset.discount) - parseInt(a.dataset.discount));
            } else if (sort === 'price-asc') {
                visibleCards.sort((a, b) => {
                    const priceA = parseFloat(a.querySelector('.price-new').textContent);
                    const priceB = parseFloat(b.querySelector('.price-new').textContent);
                    return priceA - priceB;
                });
            } else if (sort === 'price-desc') {
                visibleCards.sort((a, b) => {
                    const priceA = parseFloat(a.querySelector('.price-new').textContent);
                    const priceB = parseFloat(b.querySelector('.price-new').textContent);
                    return priceB - priceA;
                });
            }

            const grid = document.querySelector('.products-grid');
            visibleCards.forEach(card => grid.appendChild(card));
        }

        filterCategory.addEventListener('change', applyFilters);
        filterDiscount.addEventListener('change', applyFilters);
        filterSort.addEventListener('change', applyFilters);

        // Init
        startCountdown();
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
