<?php
$pageTitle = 'Textiles Imprimables - Polyester, Backlite | Imprixo';
$pageDescription = 'Impression textile ✓ Polyester, Backlite, Polyglans ✓ Prix à partir de 6€/m² ✓ Livraison 48h ✓ Stands, kakémonos, murs d\'images';
include __DIR__ . '/../includes/header.php';
?>

<!-- HEADER -->
    <script>fetch('/includes/header.html').then(r=>r.text()).then(html=>document.getElementById('header-placeholder').innerHTML=html)</script>

    <!-- HERO CATÉGORIE -->
    <section class="hero-gradient text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <span class="badge badge-green mb-4">Catégorie Premium</span>
                <h1 class="text-5xl font-black mb-6">
                    Textiles Imprimables
                </h1>
                <p class="text-xl text-gray-300 mb-8">
                    Impressions textiles professionnelles - Idéal pour stands de salon, kakémonos, murs d'images et cloisons. Léger et élégant.
                </p>
                <div class="flex flex-wrap gap-4 mb-6">
                    <div class="bg-white/10 backdrop-blur px-6 py-3 rounded-lg">
                        <span class="text-sm text-gray-300">À partir de</span>
                        <div class="text-2xl font-black text-red-400">6€/m²</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur px-6 py-3 rounded-lg">
                        <span class="text-sm text-gray-300">Livraison</span>
                        <div class="text-2xl font-black">48-72h</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur px-6 py-3 rounded-lg">
                        <span class="text-sm text-gray-300">Usage</span>
                        <div class="text-2xl font-black">Int/Events</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PRODUITS -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-black text-gray-900 mb-4">
                    Tous les Textiles Imprimables
                </h2>
                <p class="text-xl text-gray-600">
                    Pour vos stands et événements professionnels
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Polyester 110g -->
                <a href="/produit/POLYESTER-110G.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="badge badge-red mb-2">Best-Seller</span>
                                <h3 class="text-2xl font-black text-gray-900">Polyester 110g</h3>
                                <p class="text-sm text-gray-500">Textile Standard</p>
                            </div>
                            <span class="text-4xl">🎨</span>
                        </div>
                        <p class="text-gray-600 mb-6">
                            Le textile le plus polyvalent. Idéal kakémonos, murs d'images et stands.
                        </p>
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div><span class="text-gray-500">Usage:</span> <span class="font-bold">Intérieur</span></div>
                                <div><span class="text-gray-500">Poids:</span> <span class="font-bold">110g/m²</span></div>
                                <div><span class="text-gray-500">Certif:</span> <span class="font-bold">B1/M1</span></div>
                                <div><span class="text-gray-500">Délai:</span> <span class="font-bold">3 jours</span></div>
                            </div>
                        </div>
                        <div class="flex items-end justify-between">
                            <div>
                                <div class="text-sm text-gray-500 mb-1">À partir de</div>
                                <div class="text-3xl font-black text-red-600">6€<span class="text-sm font-normal text-gray-500">/m²</span></div>
                            </div>
                            <button class="btn-primary text-white px-6 py-3 rounded-lg font-bold">
                                Configurer →
                            </button>
                        </div>
                    </div>
                </a>

                <!-- Backlite -->
                <a href="/produit/BACKLITE-200G.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="badge badge-green mb-2">Premium</span>
                                <h3 class="text-2xl font-black text-gray-900">Backlite 200g</h3>
                                <p class="text-sm text-gray-500">Textile Rétro-éclairé</p>
                            </div>
                            <span class="text-4xl">💡</span>
                        </div>
                        <p class="text-gray-600 mb-6">
                            Textile pour rétro-éclairage. Effet premium pour stands lumineux.
                        </p>
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div><span class="text-gray-500">Usage:</span> <span class="font-bold">Lumineux</span></div>
                                <div><span class="text-gray-500">Poids:</span> <span class="font-bold">200g/m²</span></div>
                                <div><span class="text-gray-500">Certif:</span> <span class="font-bold">B1/M1</span></div>
                                <div><span class="text-gray-500">Délai:</span> <span class="font-bold">3 jours</span></div>
                            </div>
                        </div>
                        <div class="flex items-end justify-between">
                            <div>
                                <div class="text-sm text-gray-500 mb-1">À partir de</div>
                                <div class="text-3xl font-black text-red-600">12€<span class="text-sm font-normal text-gray-500">/m²</span></div>
                            </div>
                            <button class="btn-primary text-white px-6 py-3 rounded-lg font-bold">
                                Configurer →
                            </button>
                        </div>
                    </div>
                </a>

                <!-- Polyglans -->
                <a href="/produit/POLYGLANS-190G.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="badge badge-green mb-2">Haut de Gamme</span>
                                <h3 class="text-2xl font-black text-gray-900">Polyglans 190g</h3>
                                <p class="text-sm text-gray-500">Textile Satiné Premium</p>
                            </div>
                            <span class="text-4xl">✨</span>
                        </div>
                        <p class="text-gray-600 mb-6">
                            Finition satinée élégante. Idéal pour cloisons et décoration haut de gamme.
                        </p>
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div><span class="text-gray-500">Usage:</span> <span class="font-bold">Intérieur</span></div>
                                <div><span class="text-gray-500">Poids:</span> <span class="font-bold">190g/m²</span></div>
                                <div><span class="text-gray-500">Certif:</span> <span class="font-bold">B1/M1</span></div>
                                <div><span class="text-gray-500">Délai:</span> <span class="font-bold">3 jours</span></div>
                            </div>
                        </div>
                        <div class="flex items-end justify-between">
                            <div>
                                <div class="text-sm text-gray-500 mb-1">À partir de</div>
                                <div class="text-3xl font-black text-red-600">10€<span class="text-sm font-normal text-gray-500">/m²</span></div>
                            </div>
                            <button class="btn-primary text-white px-6 py-3 rounded-lg font-bold">
                                Configurer →
                            </button>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- AVANTAGES TEXTILES -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-black text-gray-900 mb-4">
                    Pourquoi Choisir les Textiles ?
                </h2>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center p-6">
                    <div class="text-5xl mb-4">🪶</div>
                    <h3 class="text-xl font-bold mb-3">Ultra Léger</h3>
                    <p class="text-gray-600">Facile à transporter et installer</p>
                </div>
                <div class="text-center p-6">
                    <div class="text-5xl mb-4">✨</div>
                    <h3 class="text-xl font-bold mb-3">Élégant</h3>
                    <p class="text-gray-600">Rendu premium et professionnel</p>
                </div>
                <div class="text-center p-6">
                    <div class="text-5xl mb-4">🎪</div>
                    <h3 class="text-xl font-bold mb-3">Events</h3>
                    <p class="text-gray-600">Idéal salons et stands</p>
                </div>
                <div class="text-center p-6">
                    <div class="text-5xl mb-4">🔄</div>
                    <h3 class="text-xl font-bold mb-3">Réutilisable</h3>
                    <p class="text-gray-600">Lavable et durable</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <script>fetch('/includes/footer.html').then(r=>r.text()).then(html=>document.getElementById('footer-placeholder').innerHTML=html)</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
