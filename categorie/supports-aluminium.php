<?php
$pageTitle = 'Supports Aluminium - Dibond, Composite Alu | Imprixo';
$pageDescription = 'Impression sur Dibond ✓ Composite aluminium 3mm ✓ Durabilité 10+ ans ✓ Prix à partir de 40€/m² ✓ Livraison 48h ✓ Enseignes premium';
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
                    Supports Aluminium
                </h1>
                <p class="text-xl text-gray-300 mb-8">
                    Panneaux Dibond et composite aluminium - Qualité premium pour enseignes durables. Résistance extrême UV et intempéries.
                </p>
                <div class="flex flex-wrap gap-4 mb-6">
                    <div class="bg-white/10 backdrop-blur px-6 py-3 rounded-lg">
                        <span class="text-sm text-gray-300">À partir de</span>
                        <div class="text-2xl font-black text-red-400">40€/m²</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur px-6 py-3 rounded-lg">
                        <span class="text-sm text-gray-300">Livraison</span>
                        <div class="text-2xl font-black">48-72h</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur px-6 py-3 rounded-lg">
                        <span class="text-sm text-gray-300">Durabilité</span>
                        <div class="text-2xl font-black">10+ ans</div>
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
                    Tous les Supports Aluminium
                </h2>
                <p class="text-xl text-gray-600">
                    L'excellence pour vos enseignes extérieures
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Dibond 3mm -->
                <a href="/produit/DB-3MM.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="badge badge-green mb-2">Premium</span>
                                <h3 class="text-2xl font-black text-gray-900">Dibond Alu 3mm</h3>
                                <p class="text-sm text-gray-500">Composite Aluminium</p>
                            </div>
                            <span class="text-4xl">✨</span>
                        </div>
                        <p class="text-gray-600 mb-6">
                            Le standard premium. 2 feuilles alu + âme polyéthylène. Durabilité exceptionnelle.
                        </p>
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div><span class="text-gray-500">Usage:</span> <span class="font-bold">Extérieur</span></div>
                                <div><span class="text-gray-500">Durée:</span> <span class="font-bold">10+ ans</span></div>
                                <div><span class="text-gray-500">Certif:</span> <span class="font-bold">M1</span></div>
                                <div><span class="text-gray-500">Délai:</span> <span class="font-bold">3 jours</span></div>
                            </div>
                        </div>
                        <div class="flex items-end justify-between">
                            <div>
                                <div class="text-sm text-gray-500 mb-1">À partir de</div>
                                <div class="text-3xl font-black text-red-600">40€<span class="text-sm font-normal text-gray-500">/m²</span></div>
                            </div>
                            <button class="btn-primary text-white px-6 py-3 rounded-lg font-bold">
                                Configurer →
                            </button>
                        </div>
                    </div>
                </a>

                <!-- Alu Blanc -->
                <a href="/produit/ALU-BLANC-0-5MM.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="badge badge-green mb-2">Polyvalent</span>
                                <h3 class="text-2xl font-black text-gray-900">Alu Blanc 0,5mm</h3>
                                <p class="text-sm text-gray-500">Aluminium Laqué Blanc</p>
                            </div>
                            <span class="text-4xl">✨</span>
                        </div>
                        <p class="text-gray-600 mb-6">
                            Feuille aluminium laquée. Idéal plaques professionnelles et enseignes légères.
                        </p>
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div><span class="text-gray-500">Usage:</span> <span class="font-bold">Int/Ext</span></div>
                                <div><span class="text-gray-500">Durée:</span> <span class="font-bold">5-7 ans</span></div>
                                <div><span class="text-gray-500">Certif:</span> <span class="font-bold">M1</span></div>
                                <div><span class="text-gray-500">Délai:</span> <span class="font-bold">3 jours</span></div>
                            </div>
                        </div>
                        <div class="flex items-end justify-between">
                            <div>
                                <div class="text-sm text-gray-500 mb-1">À partir de</div>
                                <div class="text-3xl font-black text-red-600">30€<span class="text-sm font-normal text-gray-500">/m²</span></div>
                            </div>
                            <button class="btn-primary text-white px-6 py-3 rounded-lg font-bold">
                                Configurer →
                            </button>
                        </div>
                    </div>
                </a>

                <!-- Alu Brossé -->
                <a href="/produit/ALU-BROSSE.html" class="product-card bg-white rounded-2xl border-2 border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="badge badge-red mb-2">Haut de Gamme</span>
                                <h3 class="text-2xl font-black text-gray-900">Alu Brossé</h3>
                                <p class="text-sm text-gray-500">Aluminium Effet Brossé</p>
                            </div>
                            <span class="text-4xl">✨</span>
                        </div>
                        <p class="text-gray-600 mb-6">
                            Finition élégante brossée. Parfait pour plaques professionnelles haut de gamme.
                        </p>
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div><span class="text-gray-500">Usage:</span> <span class="font-bold">Int/Ext</span></div>
                                <div><span class="text-gray-500">Durée:</span> <span class="font-bold">10+ ans</span></div>
                                <div><span class="text-gray-500">Certif:</span> <span class="font-bold">M1</span></div>
                                <div><span class="text-gray-500">Délai:</span> <span class="font-bold">4 jours</span></div>
                            </div>
                        </div>
                        <div class="flex items-end justify-between">
                            <div>
                                <div class="text-sm text-gray-500 mb-1">À partir de</div>
                                <div class="text-3xl font-black text-red-600">50€<span class="text-sm font-normal text-gray-500">/m²</span></div>
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

    <!-- AVANTAGES ALUMINIUM -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-black text-gray-900 mb-4">
                    Pourquoi Choisir l'Aluminium ?
                </h2>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center p-6">
                    <div class="text-5xl mb-4">🏆</div>
                    <h3 class="text-xl font-bold mb-3">Premium</h3>
                    <p class="text-gray-600">Qualité exceptionnelle et finitions parfaites</p>
                </div>
                <div class="text-center p-6">
                    <div class="text-5xl mb-4">⏳</div>
                    <h3 class="text-xl font-bold mb-3">Durable</h3>
                    <p class="text-gray-600">10+ ans en extérieur sans altération</p>
                </div>
                <div class="text-center p-6">
                    <div class="text-5xl mb-4">🌞</div>
                    <h3 class="text-xl font-bold mb-3">Anti-UV</h3>
                    <p class="text-gray-600">Résistance totale aux rayons UV</p>
                </div>
                <div class="text-center p-6">
                    <div class="text-5xl mb-4">💎</div>
                    <h3 class="text-xl font-bold mb-3">Élégant</h3>
                    <p class="text-gray-600">Aspect professionnel et haut de gamme</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <script>fetch('/includes/footer.html').then(r=>r.text()).then(html=>document.getElementById('footer-placeholder').innerHTML=html)</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
