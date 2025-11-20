<?php
$pageTitle = 'Supports Rigides PVC - Forex, PVC Expansé | Imprixo';
$pageDescription = 'Impression sur supports rigides PVC ✓ Forex 2mm à 10mm ✓ PVC expansé ✓ Prix à partir de 13€/m² ✓ Livraison 48h ✓ Idéal enseignes & panneaux';
include __DIR__ . '/../includes/header.php';
?>

<!-- HEADER -->
    <div id="header-placeholder"></div>
    <script>fetch('/includes/header.html').then(r=>r.text()).then(html=>document.getElementById('header-placeholder').innerHTML=html)</script>

    <!-- HERO CATÉGORIE -->
    <section class="hero-gradient text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <span class="badge badge-red mb-4">Catégorie</span>
                <h1 class="text-5xl font-black mb-6">
                    Supports Rigides PVC
                </h1>
                <p class="text-xl text-gray-300 mb-8">
                    Panneaux Forex et PVC expansé - Le support le plus populaire pour enseignes et panneaux. Léger, rigide et économique.
                </p>
                <div class="flex flex-wrap gap-4 mb-6">
                    <div class="bg-white/10 backdrop-blur px-6 py-3 rounded-lg">
                        <span class="text-sm text-gray-300">À partir de</span>
                        <div class="text-2xl font-black text-red-400">13€/m²</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur px-6 py-3 rounded-lg">
                        <span class="text-sm text-gray-300">Livraison</span>
                        <div class="text-2xl font-black">48-72h</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur px-6 py-3 rounded-lg">
                        <span class="text-sm text-gray-300">Durabilité</span>
                        <div class="text-2xl font-black">2-3 ans</div>
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
                    Tous les Supports PVC
                </h2>
                <p class="text-xl text-gray-600">
                    Choisissez l'épaisseur adaptée à votre projet
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Forex 3mm -->
                <a href="/produit/FX-3MM.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="badge badge-red mb-2">Best-Seller</span>
                                <h3 class="text-2xl font-black text-gray-900">Forex 3mm</h3>
                                <p class="text-sm text-gray-500">PVC Expansé Standard</p>
                            </div>
                            <span class="text-4xl">📄</span>
                        </div>
                        <p class="text-gray-600 mb-6">
                            Le support le plus populaire. Idéal pour enseignes intérieures et extérieures abritées.
                        </p>
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div><span class="text-gray-500">Usage:</span> <span class="font-bold">Int/Ext</span></div>
                                <div><span class="text-gray-500">Durée:</span> <span class="font-bold">2-3 ans</span></div>
                                <div><span class="text-gray-500">Certif:</span> <span class="font-bold">M1</span></div>
                                <div><span class="text-gray-500">Délai:</span> <span class="font-bold">3 jours</span></div>
                            </div>
                        </div>
                        <div class="flex items-end justify-between">
                            <div>
                                <div class="text-sm text-gray-500 mb-1">À partir de</div>
                                <div class="text-3xl font-black text-red-600">20€<span class="text-sm font-normal text-gray-500">/m²</span></div>
                            </div>
                            <button class="btn-primary text-white px-6 py-3 rounded-lg font-bold">
                                Configurer →
                            </button>
                        </div>
                    </div>
                </a>

                <!-- Forex 5mm -->
                <a href="/produit/FX-5MM.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="badge badge-green mb-2">Populaire</span>
                                <h3 class="text-2xl font-black text-gray-900">Forex 5mm</h3>
                                <p class="text-sm text-gray-500">PVC Expansé Renforcé</p>
                            </div>
                            <span class="text-4xl">📄</span>
                        </div>
                        <p class="text-gray-600 mb-6">
                            Plus rigide et résistant. Recommandé pour grands formats et usage intensif.
                        </p>
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div><span class="text-gray-500">Usage:</span> <span class="font-bold">Int/Ext</span></div>
                                <div><span class="text-gray-500">Durée:</span> <span class="font-bold">3-4 ans</span></div>
                                <div><span class="text-gray-500">Certif:</span> <span class="font-bold">M1</span></div>
                                <div><span class="text-gray-500">Délai:</span> <span class="font-bold">3 jours</span></div>
                            </div>
                        </div>
                        <div class="flex items-end justify-between">
                            <div>
                                <div class="text-sm text-gray-500 mb-1">À partir de</div>
                                <div class="text-3xl font-black text-red-600">25€<span class="text-sm font-normal text-gray-500">/m²</span></div>
                            </div>
                            <button class="btn-primary text-white px-6 py-3 rounded-lg font-bold">
                                Configurer →
                            </button>
                        </div>
                    </div>
                </a>

                <!-- Forex 10mm -->
                <a href="/produit/FX-10MM.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="badge badge-green mb-2">Premium</span>
                                <h3 class="text-2xl font-black text-gray-900">Forex 10mm</h3>
                                <p class="text-sm text-gray-500">PVC Expansé Ultra Rigide</p>
                            </div>
                            <span class="text-4xl">📄</span>
                        </div>
                        <p class="text-gray-600 mb-6">
                            Maximum de rigidité. Parfait pour enseignes grand format et usage professionnel.
                        </p>
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div><span class="text-gray-500">Usage:</span> <span class="font-bold">Int/Ext</span></div>
                                <div><span class="text-gray-500">Durée:</span> <span class="font-bold">4-5 ans</span></div>
                                <div><span class="text-gray-500">Certif:</span> <span class="font-bold">M1</span></div>
                                <div><span class="text-gray-500">Délai:</span> <span class="font-bold">3 jours</span></div>
                            </div>
                        </div>
                        <div class="flex items-end justify-between">
                            <div>
                                <div class="text-sm text-gray-500 mb-1">À partir de</div>
                                <div class="text-3xl font-black text-red-600">35€<span class="text-sm font-normal text-gray-500">/m²</span></div>
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

    <!-- AVANTAGES PVC -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-black text-gray-900 mb-4">
                    Pourquoi Choisir le PVC Expansé ?
                </h2>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center p-6">
                    <div class="text-5xl mb-4">💰</div>
                    <h3 class="text-xl font-bold mb-3">Économique</h3>
                    <p class="text-gray-600">Le meilleur rapport qualité/prix du marché</p>
                </div>
                <div class="text-center p-6">
                    <div class="text-5xl mb-4">🪶</div>
                    <h3 class="text-xl font-bold mb-3">Léger</h3>
                    <p class="text-gray-600">Facile à manipuler et installer</p>
                </div>
                <div class="text-center p-6">
                    <div class="text-5xl mb-4">🔧</div>
                    <h3 class="text-xl font-bold mb-3">Versatile</h3>
                    <p class="text-gray-600">Se découpe, se perce, se colle facilement</p>
                </div>
                <div class="text-center p-6">
                    <div class="text-5xl mb-4">🌦️</div>
                    <h3 class="text-xl font-bold mb-3">Résistant</h3>
                    <p class="text-gray-600">Idéal intérieur et extérieur abrité</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <div id="footer-placeholder"></div>
    <script>fetch('/includes/footer.html').then(r=>r.text()).then(html=>document.getElementById('footer-placeholder').innerHTML=html)</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
