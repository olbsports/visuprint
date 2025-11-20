<?php
$pageTitle = 'Configurateur en Ligne - Devis Instantané | Imprixo';
$pageDescription = 'Configurez et commandez votre impression grand format en ligne ✓ Prix instantané ✓ Tous supports ✓ Devis gratuit ✓ Livraison 48h';
include __DIR__ . '/includes/header.php';
?>

<!-- HEADER -->
    <script>fetch('/includes/header.html').then(r=>r.text()).then(html=>document.getElementById('header-placeholder').innerHTML=html)</script>

    <!-- HERO -->
    <section class="hero-gradient text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="badge badge-red mb-4">Devis Instantané</span>
            <h1 class="text-5xl font-black mb-6">
                Configurateur en Ligne
            </h1>
            <p class="text-xl text-gray-300 mb-8">
                Configurez votre impression et obtenez votre prix en temps réel
            </p>
        </div>
    </section>

    <!-- CONFIGURATEUR -->
    <section class="py-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-8">

                <!-- CONFIGURATION -->
                <div class="lg:col-span-2">

                    <!-- ÉTAPE 1 : SUPPORT -->
                    <div class="config-step active">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-black">1. Choisissez votre support</h2>
                            <span class="badge badge-red">Requis</span>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="format-option selected">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <div class="font-bold text-lg">Forex 3mm</div>
                                        <div class="text-sm text-gray-600">PVC Expansé Standard</div>
                                    </div>
                                    <span class="text-3xl">📄</span>
                                </div>
                                <div class="text-red-600 font-black">À partir de 20€/m²</div>
                            </div>

                            <div class="format-option">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <div class="font-bold text-lg">Dibond Alu 3mm</div>
                                        <div class="text-sm text-gray-600">Composite Aluminium</div>
                                    </div>
                                    <span class="text-3xl">✨</span>
                                </div>
                                <div class="text-red-600 font-black">À partir de 40€/m²</div>
                            </div>

                            <div class="format-option">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <div class="font-bold text-lg">Bâche PVC 500g</div>
                                        <div class="text-sm text-gray-600">Frontlit B1</div>
                                    </div>
                                    <span class="text-3xl">🎪</span>
                                </div>
                                <div class="text-red-600 font-black">À partir de 6€/m²</div>
                            </div>

                            <div class="format-option">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <div class="font-bold text-lg">Polyester 110g</div>
                                        <div class="text-sm text-gray-600">Textile Standard</div>
                                    </div>
                                    <span class="text-3xl">🎨</span>
                                </div>
                                <div class="text-red-600 font-black">À partir de 6€/m²</div>
                            </div>
                        </div>

                        <div class="mt-6 text-center">
                            <a href="/produits.html" class="text-red-600 font-bold hover:underline">
                                Voir tous les supports disponibles →
                            </a>
                        </div>
                    </div>

                    <!-- ÉTAPE 2 : FORMAT -->
                    <div class="config-step">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-black">2. Définissez le format</h2>
                            <span class="badge badge-red">Requis</span>
                        </div>

                        <div class="grid grid-cols-3 gap-3 mb-6">
                            <button class="format-option text-center py-4 selected">
                                <div class="font-bold">100×150 cm</div>
                                <div class="text-sm text-gray-600">Standard</div>
                            </button>
                            <button class="format-option text-center py-4">
                                <div class="font-bold">80×120 cm</div>
                                <div class="text-sm text-gray-600">Compact</div>
                            </button>
                            <button class="format-option text-center py-4">
                                <div class="font-bold">120×180 cm</div>
                                <div class="text-sm text-gray-600">Large</div>
                            </button>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="font-bold mb-4">Format personnalisé</div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold mb-2">Largeur (cm)</label>
                                    <input type="number" class="w-full border-2 border-gray-300 rounded-lg p-3 font-bold" placeholder="100" value="100">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold mb-2">Hauteur (cm)</label>
                                    <input type="number" class="w-full border-2 border-gray-300 rounded-lg p-3 font-bold" placeholder="150" value="150">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ÉTAPE 3 : QUANTITÉ -->
                    <div class="config-step">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-black">3. Quantité</h2>
                            <span class="badge badge-red">Requis</span>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-6">
                            <label class="block text-sm font-bold mb-2">Nombre d'exemplaires</label>
                            <input type="number" class="w-full border-2 border-gray-300 rounded-lg p-3 font-bold text-2xl" value="1" min="1">
                            <div class="text-sm text-gray-600 mt-2">
                                💡 Prix dégressifs à partir de 10m²
                            </div>
                        </div>
                    </div>

                    <!-- ÉTAPE 4 : OPTIONS -->
                    <div class="config-step">
                        <h2 class="text-2xl font-black mb-6">4. Options (facultatif)</h2>

                        <div class="space-y-4">
                            <label class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                                <input type="checkbox" class="w-5 h-5">
                                <div class="flex-1">
                                    <div class="font-bold">Découpe à la forme</div>
                                    <div class="text-sm text-gray-600">+5€ par panneau</div>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                                <input type="checkbox" class="w-5 h-5">
                                <div class="flex-1">
                                    <div class="font-bold">Œillets (bâches uniquement)</div>
                                    <div class="text-sm text-gray-600">Inclus tous les 50cm</div>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                                <input type="checkbox" class="w-5 h-5">
                                <div class="flex-1">
                                    <div class="font-bold">Livraison express 24h</div>
                                    <div class="text-sm text-gray-600">+30€</div>
                                </div>
                            </label>
                        </div>
                    </div>

                </div>

                <!-- RÉCAPITULATIF -->
                <div class="lg:col-span-1">
                    <div class="sticky top-32 bg-white border-2 border-gray-200 rounded-2xl p-6">
                        <h3 class="text-xl font-black mb-6">Récapitulatif</h3>

                        <div class="space-y-3 mb-6 pb-6 border-b">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Support</span>
                                <span class="font-bold">Forex 3mm</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Format</span>
                                <span class="font-bold">100×150 cm</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Surface</span>
                                <span class="font-bold">1.5 m²</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Quantité</span>
                                <span class="font-bold">1 ex.</span>
                            </div>
                        </div>

                        <div class="bg-red-50 border-2 border-red-600 rounded-lg p-4 mb-6">
                            <div class="text-sm text-gray-600 mb-1">Prix total HT</div>
                            <div class="text-4xl font-black text-red-600">30,00€</div>
                            <div class="text-sm text-gray-500 mt-1">soit 20€/m²</div>
                        </div>

                        <button class="w-full btn-primary text-white py-4 rounded-lg font-bold text-lg mb-3">
                            🛒 Ajouter au Panier
                        </button>

                        <a href="/contact.html" class="block w-full text-center border-2 border-gray-300 py-4 rounded-lg font-bold hover:border-red-600 hover:bg-red-50 hover:text-red-600 transition">
                            📧 Demander un Devis
                        </a>

                        <div class="mt-6 pt-6 border-t text-center text-sm text-gray-600">
                            ✓ Livraison 48-72h<br>
                            ✓ Prix dégressifs<br>
                            ✓ Sans engagement
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <script>fetch('/includes/footer.html').then(r=>r.text()).then(html=>document.getElementById('footer-placeholder').innerHTML=html)</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
