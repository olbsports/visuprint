<?php
$pageTitle = 'Tous nos Produits - 54 Supports d\'Impression | Imprixo';
$pageDescription = 'Découvrez nos 54 supports d\'impression ✓ Forex, Dibond, Bâches, Textiles ✓ Prix dégressifs ✓ Livraison 48h ✓ Qualité pro';
include __DIR__ . '/includes/header.php';
?>

<!-- HEADER -->
    <script>fetch('/includes/header.html').then(r=>r.text()).then(html=>document.getElementById('header-placeholder').innerHTML=html)</script>

    <!-- HERO -->
    <section class="hero-gradient text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="badge badge-red mb-4">Catalogue Complet</span>
            <h1 class="text-5xl font-black mb-6">
                Tous nos Supports d'Impression
            </h1>
            <p class="text-xl text-gray-300 mb-8 max-w-3xl mx-auto">
                Plus de 50 supports professionnels pour tous vos besoins d'impression grand format
            </p>
        </div>
    </section>

    <!-- FILTRES -->
    <section class="bg-white py-8 sticky top-[120px] z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap gap-3 justify-center">
                <button class="filter-btn active" data-filter="all">Tous les produits</button>
                <button class="filter-btn" data-filter="pvc">Supports PVC</button>
                <button class="filter-btn" data-filter="alu">Aluminium</button>
                <button class="filter-btn" data-filter="bache">Bâches</button>
                <button class="filter-btn" data-filter="textile">Textiles</button>
            </div>
        </div>
    </section>

    <!-- PRODUITS -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- SUPPORTS RIGIDES PVC -->
            <div class="mb-16" data-category="pvc">
                <h2 class="text-3xl font-black text-gray-900 mb-8 flex items-center">
                    <span class="text-4xl mr-3"><i class="fas fa-file"></i></span> Supports Rigides PVC
                </h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <a href="/produit/FX-2MM.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden p-6 hover:border-red-600">
                        <h3 class="text-xl font-black mb-2">Forex 2mm</h3>
                        <p class="text-sm text-gray-600 mb-4">Ultra léger</p>
                        <div class="text-2xl font-black text-red-600">20€/m²</div>
                    </a>
                    <a href="/produit/FX-3MM.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden p-6 hover:border-red-600">
                        <span class="badge badge-red mb-2">Best-Seller</span>
                        <h3 class="text-xl font-black mb-2">Forex 3mm</h3>
                        <p class="text-sm text-gray-600 mb-4">Standard polyvalent</p>
                        <div class="text-2xl font-black text-red-600">20€/m²</div>
                    </a>
                    <a href="/produit/FX-5MM.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden p-6 hover:border-red-600">
                        <h3 class="text-xl font-black mb-2">Forex 5mm</h3>
                        <p class="text-sm text-gray-600 mb-4">Rigidité supérieure</p>
                        <div class="text-2xl font-black text-red-600">30€/m²</div>
                    </a>
                    <a href="/produit/FX-8MM.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden p-6 hover:border-red-600">
                        <h3 class="text-xl font-black mb-2">Forex 8mm</h3>
                        <p class="text-sm text-gray-600 mb-4">Haute rigidité</p>
                        <div class="text-2xl font-black text-red-600">40€/m²</div>
                    </a>
                    <a href="/produit/FX-10MM.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden p-6 hover:border-red-600">
                        <h3 class="text-xl font-black mb-2">Forex 10mm</h3>
                        <p class="text-sm text-gray-600 mb-4">Structure</p>
                        <div class="text-2xl font-black text-red-600">50€/m²</div>
                    </a>
                    <a href="/produit/CP-8MM.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden p-6 hover:border-red-600">
                        <h3 class="text-xl font-black mb-2">Channel Plate 8mm</h3>
                        <p class="text-sm text-gray-600 mb-4">Carton économique</p>
                        <div class="text-2xl font-black text-red-600">13€/m²</div>
                    </a>
                    <a href="/produit/HIPS-3MM.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden p-6 hover:border-red-600">
                        <h3 class="text-xl font-black mb-2">HIPS 3mm</h3>
                        <p class="text-sm text-gray-600 mb-4">Polystyrène choc</p>
                        <div class="text-2xl font-black text-red-600">26€/m²</div>
                    </a>
                    <a href="/produit/PALB-3MM.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden p-6 hover:border-red-600">
                        <h3 class="text-xl font-black mb-2">Palboard 3mm</h3>
                        <p class="text-sm text-gray-600 mb-4">PVC alvéolaire</p>
                        <div class="text-2xl font-black text-red-600">36€/m²</div>
                    </a>
                    <a href="/produit/ACRYL-3MM.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden p-6 hover:border-red-600">
                        <h3 class="text-xl font-black mb-2">Acrylique 3mm</h3>
                        <p class="text-sm text-gray-600 mb-4">Transparent</p>
                        <div class="text-2xl font-black text-red-600">40€/m²</div>
                    </a>
                </div>
            </div>

            <!-- SUPPORTS ALUMINIUM -->
            <div class="mb-16" data-category="alu">
                <h2 class="text-3xl font-black text-gray-900 mb-8 flex items-center">
                    <span class="text-4xl mr-3"><i class="fas fa-gem"></i></span> Supports Aluminium Premium
                </h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <a href="/produit/DB-2MM.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden p-6 hover:border-red-600">
                        <h3 class="text-xl font-black mb-2">Dibond Alu 2mm</h3>
                        <p class="text-sm text-gray-600 mb-4">Léger</p>
                        <div class="text-2xl font-black text-red-600">40€/m²</div>
                    </a>
                    <a href="/produit/DB-3MM.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden p-6 hover:border-red-600">
                        <span class="badge badge-green mb-2">Premium</span>
                        <h3 class="text-xl font-black mb-2">Dibond Alu 3mm</h3>
                        <p class="text-sm text-gray-600 mb-4">Standard pro</p>
                        <div class="text-2xl font-black text-red-600">40€/m²</div>
                    </a>
                    <a href="/produit/DB-3MM-BRUSH.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden p-6 hover:border-red-600">
                        <h3 class="text-xl font-black mb-2">Dibond Alu 3mm Brossé</h3>
                        <p class="text-sm text-gray-600 mb-4">Prestige</p>
                        <div class="text-2xl font-black text-red-600">45€/m²</div>
                    </a>
                </div>
            </div>

            <!-- BÂCHES -->
            <div class="mb-16" data-category="bache">
                <h2 class="text-3xl font-black text-gray-900 mb-8 flex items-center">
                    <span class="text-4xl mr-3"><i class="fas fa-flag"></i></span> Bâches & Supports Souples
                </h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <a href="/produit/FRONTLIT-COATED-500G-B1.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden p-6 hover:border-red-600">
                        <span class="badge badge-red mb-2">Best-Seller</span>
                        <h3 class="text-xl font-black mb-2">Frontlit 500g</h3>
                        <p class="text-sm text-gray-600 mb-4">Bâche PVC</p>
                        <div class="text-2xl font-black text-red-600">5€/m²</div>
                    </a>
                    <a href="/produit/MESH-330-B1.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden p-6 hover:border-red-600">
                        <h3 class="text-xl font-black mb-2">Mesh 330g</h3>
                        <p class="text-sm text-gray-600 mb-4">Anti-vent microperforé</p>
                        <div class="text-2xl font-black text-red-600">5€/m²</div>
                    </a>
                    <a href="/produit/BLOCKOUT-650-B1.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden p-6 hover:border-red-600">
                        <h3 class="text-xl font-black mb-2">Blockout 650g</h3>
                        <p class="text-sm text-gray-600 mb-4">Opaque renforcé</p>
                        <div class="text-2xl font-black text-red-600">6€/m²</div>
                    </a>
                    <a href="/produit/PVC-BACKLITE-510.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden p-6 hover:border-red-600">
                        <h3 class="text-xl font-black mb-2">PVC Backlite 510g</h3>
                        <p class="text-sm text-gray-600 mb-4">Rétro-éclairé</p>
                        <div class="text-2xl font-black text-red-600">8€/m²</div>
                    </a>
                    <a href="/produit/POLYTENT-220.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden p-6 hover:border-red-600">
                        <h3 class="text-xl font-black mb-2">Polytent 220g</h3>
                        <p class="text-sm text-gray-600 mb-4">Bâche légère</p>
                        <div class="text-2xl font-black text-red-600">4€/m²</div>
                    </a>
                    <a href="/produit/POLYTENT-SUN-285.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden p-6 hover:border-red-600">
                        <h3 class="text-xl font-black mb-2">Polytent Sun 285g</h3>
                        <p class="text-sm text-gray-600 mb-4">Anti-UV</p>
                        <div class="text-2xl font-black text-red-600">5€/m²</div>
                    </a>
                </div>
            </div>

            <!-- TEXTILES -->
            <div class="mb-16" data-category="textile">
                <h2 class="text-3xl font-black text-gray-900 mb-8 flex items-center">
                    <span class="text-4xl mr-3"><i class="fas fa-tshirt"></i></span> Textiles Imprimables
                </h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <a href="/produit/POLYGLANS-115G.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden p-6 hover:border-red-600">
                        <span class="badge badge-red mb-2">Populaire</span>
                        <h3 class="text-xl font-black mb-2">Polyglans 115g</h3>
                        <p class="text-sm text-gray-600 mb-4">Textile standard</p>
                        <div class="text-2xl font-black text-red-600">6€/m²</div>
                    </a>
                    <a href="/produit/POLYGLANS-115G-B1.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden p-6 hover:border-red-600">
                        <h3 class="text-xl font-black mb-2">Polyglans 115g B1</h3>
                        <p class="text-sm text-gray-600 mb-4">Ignifugé</p>
                        <div class="text-2xl font-black text-red-600">8€/m²</div>
                    </a>
                    <a href="/produit/AIR-POLYGLANS-110G.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden p-6 hover:border-red-600">
                        <h3 class="text-xl font-black mb-2">Air Polyglans 110g</h3>
                        <p class="text-sm text-gray-600 mb-4">Air léger</p>
                        <div class="text-2xl font-black text-red-600">8€/m²</div>
                    </a>
                    <a href="/produit/SATIN-140G-B1.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden p-6 hover:border-red-600">
                        <h3 class="text-xl font-black mb-2">Satin 140g B1</h3>
                        <p class="text-sm text-gray-600 mb-4">Finition satinée</p>
                        <div class="text-2xl font-black text-red-600">9€/m²</div>
                    </a>
                    <a href="/produit/DECOR-205G-B1.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden p-6 hover:border-red-600">
                        <h3 class="text-xl font-black mb-2">Decor 205g B1</h3>
                        <p class="text-sm text-gray-600 mb-4">Premium déco</p>
                        <div class="text-2xl font-black text-red-600">9€/m²</div>
                    </a>
                    <a href="/produit/STRETCH-240G-B1.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden p-6 hover:border-red-600">
                        <h3 class="text-xl font-black mb-2">Stretch 240g B1</h3>
                        <p class="text-sm text-gray-600 mb-4">Rétro-éclairé</p>
                        <div class="text-2xl font-black text-red-600">14€/m²</div>
                    </a>
                    <a href="/produit/BLOCKOUT-ROLL-UP-440.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden p-6 hover:border-red-600">
                        <h3 class="text-xl font-black mb-2">Roll-Up 440g</h3>
                        <p class="text-sm text-gray-600 mb-4">Pour roll-up</p>
                        <div class="text-2xl font-black text-red-600">6€/m²</div>
                    </a>
                    <a href="/produit/ROLL-UP-FILM-205.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden p-6 hover:border-red-600">
                        <h3 class="text-xl font-black mb-2">Roll-Up Film 205g</h3>
                        <p class="text-sm text-gray-600 mb-4">Film roll-up</p>
                        <div class="text-2xl font-black text-red-600">5€/m²</div>
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- CTA -->
    <section class="py-20 hero-gradient text-white">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-4xl font-black mb-6">Besoin d'Aide pour Choisir ?</h2>
            <p class="text-xl mb-10 text-gray-300">
                Notre équipe vous conseille gratuitement sur le support idéal pour votre projet
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="/contact.html" class="btn-primary text-white px-10 py-4 rounded-lg font-bold text-lg shadow-2xl inline-flex items-center gap-2">
                    <i class="fas fa-envelope"></i> Contactez-nous
                </a>
                <a href="tel:0123456789" class="bg-white text-gray-900 px-10 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition shadow-xl inline-flex items-center gap-2">
                    <i class="fas fa-phone"></i> 01 23 45 67 89
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <script>fetch('/includes/footer.html').then(r=>r.text()).then(html=>document.getElementById('footer-placeholder').innerHTML=html)</script>

    <!-- FILTRES JS -->
    <script>
        const filterBtns = document.querySelectorAll('.filter-btn');
        const categories = document.querySelectorAll('[data-category]');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                const filter = btn.dataset.filter;

                categories.forEach(cat => {
                    if (filter === 'all' || cat.dataset.category === filter) {
                        cat.style.display = 'block';
                    } else {
                        cat.style.display = 'none';
                    }
                });
            });
        });
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
