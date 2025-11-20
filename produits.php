<?php
$pageTitle = 'Catalogue Complet 50+ Supports Impression Grand Format | Prix Pro | Imprixo';
$pageDescription = 'Découvrez nos 50+ supports d\'impression grand format fabrication européenne : Forex, Dibond, Bâches PVC, Textiles. Prix dégressifs -40%. Livraison Europe 48-72h. Devis instantané gratuit.';
include __DIR__ . '/includes/header.php';
?>

<!-- ===== HERO SECTION ===== -->
<section class="hero-gradient text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center gap-2 bg-yellow-400 text-gray-900 px-4 py-2 rounded-full text-sm font-black mb-6">
            <i class="fas fa-tags"></i>
            <span>50+ SUPPORTS DISPONIBLES</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-black mb-6">
            Tous nos Supports d'Impression
        </h1>
        <p class="text-xl text-gray-200 mb-8 max-w-3xl mx-auto">
            <strong>Fabrication européenne</strong> • Qualité certifiée B1/M1 • Prix dégressifs jusqu'à -40%
        </p>

        <!-- Quick filters -->
        <div class="flex flex-wrap gap-3 justify-center">
            <button class="filter-btn px-6 py-3 bg-white/10 hover:bg-white/20 rounded-xl font-bold transition backdrop-blur border border-white/30 active" data-filter="all">
                <i class="fas fa-th"></i> Tous
            </button>
            <button class="filter-btn px-6 py-3 bg-white/10 hover:bg-white/20 rounded-xl font-bold transition backdrop-blur border border-white/30" data-filter="pvc">
                <i class="fas fa-square"></i> PVC
            </button>
            <button class="filter-btn px-6 py-3 bg-white/10 hover:bg-white/20 rounded-xl font-bold transition backdrop-blur border border-white/30" data-filter="alu">
                <i class="fas fa-shield-alt"></i> Aluminium
            </button>
            <button class="filter-btn px-6 py-3 bg-white/10 hover:bg-white/20 rounded-xl font-bold transition backdrop-blur border border-white/30" data-filter="bache">
                <i class="fas fa-wind"></i> Bâches
            </button>
            <button class="filter-btn px-6 py-3 bg-white/10 hover:bg-white/20 rounded-xl font-bold transition backdrop-blur border border-white/30" data-filter="textile">
                <i class="fas fa-compress-alt"></i> Textiles
            </button>
        </div>
    </div>
</section>

<!-- ===== MAIN CONTENT WITH SIDEBAR ===== -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">

            <!-- CATALOGUE (Left) -->
            <div class="flex-1">

                <!-- ===== SUPPORTS RIGIDES PVC ===== -->
                <div class="mb-12 scroll-mt-24" id="pvc" data-category="pvc">
                    <div class="bg-white rounded-2xl p-8 shadow-md mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-square text-3xl text-red-600"></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-black text-gray-900">Supports Rigides PVC</h2>
                                <p class="text-gray-600">Panneaux légers et économiques pour intérieur et extérieur abrité</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Forex 2mm -->
                        <div class="product-card bg-white rounded-2xl border-2 border-gray-200 p-6 hover:border-red-500 hover:shadow-xl transition-all">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full mb-2">ULTRA LÉGER</span>
                                    <h3 class="text-2xl font-black text-gray-900 mb-2">Forex 2mm</h3>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-500 line-through">28€/m²</div>
                                    <div class="text-2xl font-black text-red-600">dès 20€/m²</div>
                                </div>
                            </div>

                            <p class="text-gray-700 mb-4 leading-relaxed">
                                <strong>PVC expansé ultra-léger</strong> parfait pour l'affichage temporaire intérieur. Idéal pour PLV, signalétique événementielle, panneaux d'exposition.
                            </p>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Usage :</strong> Intérieur, extérieur abrité court terme (3-6 mois)</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Poids :</strong> 400g/m² • Très facile à manipuler et transporter</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Format max :</strong> 205×305 cm en une seule pièce</span>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <a href="/contact.php" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-bold text-center transition">
                                    Commander
                                </a>
                                <button class="px-4 py-3 border-2 border-gray-300 hover:border-red-600 rounded-xl transition">
                                    <i class="fas fa-info-circle text-gray-600"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Forex 3mm -->
                        <div class="product-card bg-white rounded-2xl border-2 border-red-500 p-6 hover:shadow-xl transition-all relative">
                            <div class="absolute -top-3 -right-3">
                                <span class="inline-flex items-center gap-1 px-4 py-2 bg-red-600 text-white text-xs font-black rounded-full shadow-lg">
                                    <i class="fas fa-star"></i> BEST-SELLER
                                </span>
                            </div>

                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <span class="inline-block px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full mb-2">POLYVALENT</span>
                                    <h3 class="text-2xl font-black text-gray-900 mb-2">Forex 3mm</h3>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-500 line-through">35€/m²</div>
                                    <div class="text-2xl font-black text-red-600">dès 20€/m²</div>
                                </div>
                            </div>

                            <p class="text-gray-700 mb-4 leading-relaxed">
                                <strong>Le support le plus populaire !</strong> Équilibre parfait entre légèreté, rigidité et prix. Notre choix N°1 pour enseignes, panneaux immobiliers, PLV.
                            </p>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Usage :</strong> Intérieur et extérieur abrité (2-3 ans)</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Poids :</strong> 600g/m² • Bon compromis rigidité/poids</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Certification :</strong> B1/M1 ignifugé disponible</span>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <a href="/contact.php" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-bold text-center transition">
                                    Commander
                                </a>
                                <button class="px-4 py-3 border-2 border-gray-300 hover:border-red-600 rounded-xl transition">
                                    <i class="fas fa-info-circle text-gray-600"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Forex 5mm -->
                        <div class="product-card bg-white rounded-2xl border-2 border-gray-200 p-6 hover:border-red-500 hover:shadow-xl transition-all">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <span class="inline-block px-3 py-1 bg-purple-100 text-purple-700 text-xs font-bold rounded-full mb-2">RIGIDE</span>
                                    <h3 class="text-2xl font-black text-gray-900 mb-2">Forex 5mm</h3>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-black text-red-600">dès 30€/m²</div>
                                </div>
                            </div>

                            <p class="text-gray-700 mb-4 leading-relaxed">
                                <strong>Rigidité supérieure</strong> pour grands formats et fixations lourdes. Parfait pour enseignes durables, panneaux directionnels, affichage permanent.
                            </p>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Usage :</strong> Intérieur et extérieur abrité longue durée</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Poids :</strong> 1000g/m² • Ne plie pas, excellente tenue</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Idéal pour :</strong> Grands formats jusqu'à 3m de large</span>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <a href="/contact.php" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-bold text-center transition">
                                    Commander
                                </a>
                                <button class="px-4 py-3 border-2 border-gray-300 hover:border-red-600 rounded-xl transition">
                                    <i class="fas fa-info-circle text-gray-600"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Forex 10mm -->
                        <div class="product-card bg-white rounded-2xl border-2 border-gray-200 p-6 hover:border-red-500 hover:shadow-xl transition-all">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <span class="inline-block px-3 py-1 bg-gray-700 text-white text-xs font-bold rounded-full mb-2">STRUCTURE PRO</span>
                                    <h3 class="text-2xl font-black text-gray-900 mb-2">Forex 10mm</h3>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-black text-red-600">dès 50€/m²</div>
                                </div>
                            </div>

                            <p class="text-gray-700 mb-4 leading-relaxed">
                                <strong>Maximum de rigidité</strong> pour structures, cloisons légères, présentoirs. Ne se déforme jamais même en très grand format.
                            </p>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Usage :</strong> Structure, cloison, présentoir permanent</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Poids :</strong> 2000g/m² • Indéformable</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Applications pro :</strong> Stands, mobilier, agencement</span>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <a href="/contact.php" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-bold text-center transition">
                                    Commander
                                </a>
                                <button class="px-4 py-3 border-2 border-gray-300 hover:border-red-600 rounded-xl transition">
                                    <i class="fas fa-info-circle text-gray-600"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== SUPPORTS ALUMINIUM PREMIUM ===== -->
                <div class="mb-12 scroll-mt-24" id="alu" data-category="alu">
                    <div class="bg-white rounded-2xl p-8 shadow-md mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-shield-alt text-3xl text-purple-600"></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-black text-gray-900">Supports Aluminium Premium</h2>
                                <p class="text-gray-600">Composite aluminium ultra-résistant pour enseignes durables 10+ ans</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Dibond 3mm -->
                        <div class="product-card bg-white rounded-2xl border-2 border-purple-500 p-6 hover:shadow-xl transition-all relative">
                            <div class="absolute -top-3 -right-3">
                                <span class="inline-flex items-center gap-1 px-4 py-2 bg-purple-600 text-white text-xs font-black rounded-full shadow-lg">
                                    <i class="fas fa-crown"></i> PREMIUM
                                </span>
                            </div>

                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <span class="inline-block px-3 py-1 bg-purple-100 text-purple-700 text-xs font-bold rounded-full mb-2">LONGUE DURÉE</span>
                                    <h3 class="text-2xl font-black text-gray-900 mb-2">Dibond Alu 3mm</h3>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-500 line-through">65€/m²</div>
                                    <div class="text-2xl font-black text-red-600">dès 40€/m²</div>
                                </div>
                            </div>

                            <p class="text-gray-700 mb-4 leading-relaxed">
                                <strong>Composite aluminium haute qualité.</strong> 2 feuilles alu + âme polyéthylène. Résiste 10+ ans en extérieur. Le choix pro pour enseignes permanentes.
                            </p>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Durabilité :</strong> 10-15 ans extérieur, anti-UV total</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Poids :</strong> 3800g/m² • Ultra rigide et léger</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Applications :</strong> Enseignes, façades, signalétique</span>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <a href="/contact.php" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-bold text-center transition">
                                    Commander
                                </a>
                                <button class="px-4 py-3 border-2 border-gray-300 hover:border-red-600 rounded-xl transition">
                                    <i class="fas fa-info-circle text-gray-600"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Dibond Brossé -->
                        <div class="product-card bg-white rounded-2xl border-2 border-gray-200 p-6 hover:border-red-500 hover:shadow-xl transition-all">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold rounded-full mb-2">PRESTIGE</span>
                                    <h3 class="text-2xl font-black text-gray-900 mb-2">Dibond 3mm Brossé</h3>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-black text-red-600">dès 45€/m²</div>
                                </div>
                            </div>

                            <p class="text-gray-700 mb-4 leading-relaxed">
                                <strong>Finition brossée luxueuse.</strong> Effet alu brossé du plus bel effet. Idéal pour enseignes haut de gamme, plaques professionnelles.
                            </p>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Esthétique :</strong> Aspect métallique premium, élégant</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Durabilité :</strong> 10-15 ans extérieur, inaltérable</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Usage :</strong> Boutiques, bureaux, enseignes luxe</span>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <a href="/contact.php" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-bold text-center transition">
                                    Commander
                                </a>
                                <button class="px-4 py-3 border-2 border-gray-300 hover:border-red-600 rounded-xl transition">
                                    <i class="fas fa-info-circle text-gray-600"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== BÂCHES PVC ===== -->
                <div class="mb-12 scroll-mt-24" id="bache" data-category="bache">
                    <div class="bg-white rounded-2xl p-8 shadow-md mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-wind text-3xl text-blue-600"></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-black text-gray-900">Bâches & Supports Souples</h2>
                                <p class="text-gray-600">Grand format économique pour extérieur, résistant et durable</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Frontlit 500g -->
                        <div class="product-card bg-white rounded-2xl border-2 border-blue-500 p-6 hover:shadow-xl transition-all relative">
                            <div class="absolute -top-3 -right-3">
                                <span class="inline-flex items-center gap-1 px-4 py-2 bg-blue-600 text-white text-xs font-black rounded-full shadow-lg">
                                    <i class="fas fa-fire"></i> TOP VENTES
                                </span>
                            </div>

                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full mb-2">ÉCONOMIQUE</span>
                                    <h3 class="text-2xl font-black text-gray-900 mb-2">Frontlit 500g</h3>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-black text-red-600">dès 5€/m²</div>
                                </div>
                            </div>

                            <p class="text-gray-700 mb-4 leading-relaxed">
                                <strong>Bâche PVC standard professionnelle.</strong> Le meilleur rapport qualité/prix pour banderoles, bâches publicitaires, kakémonos extérieurs.
                            </p>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Usage :</strong> Extérieur permanent, résiste pluie et vent</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Durée :</strong> 3-5 ans extérieur, traitement anti-UV</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Finitions :</strong> Œillets, ourlets, découpe possible</span>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <a href="/contact.php" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-bold text-center transition">
                                    Commander
                                </a>
                                <button class="px-4 py-3 border-2 border-gray-300 hover:border-red-600 rounded-xl transition">
                                    <i class="fas fa-info-circle text-gray-600"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Mesh 330g -->
                        <div class="product-card bg-white rounded-2xl border-2 border-gray-200 p-6 hover:border-red-500 hover:shadow-xl transition-all">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <span class="inline-block px-3 py-1 bg-cyan-100 text-cyan-700 text-xs font-bold rounded-full mb-2">ANTI-VENT</span>
                                    <h3 class="text-2xl font-black text-gray-900 mb-2">Mesh 330g</h3>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-black text-red-600">dès 5€/m²</div>
                                </div>
                            </div>

                            <p class="text-gray-700 mb-4 leading-relaxed">
                                <strong>Bâche microperforée anti-vent.</strong> Laisse passer l'air (70% d'ouverture), parfait pour façades, échafaudages, clôtures de chantier.
                            </p>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Avantage :</strong> Réduit la prise au vent de 70%</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Usage :</strong> Façades, échafaudages, grandes surfaces</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Sécurité :</strong> Conforme normes BTP et affichage</span>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <a href="/contact.php" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-bold text-center transition">
                                    Commander
                                </a>
                                <button class="px-4 py-3 border-2 border-gray-300 hover:border-red-600 rounded-xl transition">
                                    <i class="fas fa-info-circle text-gray-600"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== TEXTILES ===== -->
                <div class="mb-12 scroll-mt-24" id="textile" data-category="textile">
                    <div class="bg-white rounded-2xl p-8 shadow-md mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 bg-pink-100 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-compress-alt text-3xl text-pink-600"></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-black text-gray-900">Textiles Imprimables</h2>
                                <p class="text-gray-600">Polyester premium pour stands, événements et rétro-éclairage</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Polyglans 115g -->
                        <div class="product-card bg-white rounded-2xl border-2 border-gray-200 p-6 hover:border-red-500 hover:shadow-xl transition-all">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <span class="inline-block px-3 py-1 bg-pink-100 text-pink-700 text-xs font-bold rounded-full mb-2">STANDARD PRO</span>
                                    <h3 class="text-2xl font-black text-gray-900 mb-2">Polyglans 115g</h3>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-black text-red-600">dès 6€/m²</div>
                                </div>
                            </div>

                            <p class="text-gray-700 mb-4 leading-relaxed">
                                <strong>Textile polyester standard.</strong> Idéal kakémonos, roll-ups, tentures murales. Rendu mat élégant, facile à installer et transporter.
                            </p>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Usage :</strong> Intérieur, stands, événementiel</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Avantages :</strong> Léger, sans plis, transportable</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Finition :</strong> Fourreaux, œillets, velcro possible</span>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <a href="/contact.php" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-bold text-center transition">
                                    Commander
                                </a>
                                <button class="px-4 py-3 border-2 border-gray-300 hover:border-red-600 rounded-xl transition">
                                    <i class="fas fa-info-circle text-gray-600"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Stretch 240g -->
                        <div class="product-card bg-white rounded-2xl border-2 border-gray-200 p-6 hover:border-red-500 hover:shadow-xl transition-all">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold rounded-full mb-2">RÉTRO-ÉCLAIRÉ</span>
                                    <h3 class="text-2xl font-black text-gray-900 mb-2">Stretch 240g B1</h3>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-black text-red-600">dès 14€/m²</div>
                                </div>
                            </div>

                            <p class="text-gray-700 mb-4 leading-relaxed">
                                <strong>Textile rétro-éclairé premium.</strong> Parfait pour caissons lumineux, stands rétro-éclairés. Diffusion lumineuse homogène, certifié ignifugé B1.
                            </p>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Usage :</strong> Caissons lumineux, PLV rétro-éclairée</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Certification :</strong> B1/M1 ignifugé, normes ERP</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                    <span><strong>Effet :</strong> Diffusion lumineuse parfaite</span>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <a href="/contact.php" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-bold text-center transition">
                                    Commander
                                </a>
                                <button class="px-4 py-3 border-2 border-gray-300 hover:border-red-600 rounded-xl transition">
                                    <i class="fas fa-info-circle text-gray-600"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ===== CONFIGURATEUR SIDEBAR (Right) - TOUJOURS VISIBLE ===== -->
            <div class="lg:w-96">
                <div class="sticky top-24">
                    <div class="bg-white rounded-2xl shadow-2xl p-8 border-2 border-red-500">
                        <div class="text-center mb-6">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
                                <i class="fas fa-calculator text-3xl text-red-600"></i>
                            </div>
                            <h3 class="text-2xl font-black text-gray-900 mb-2">Devis Instantané</h3>
                            <p class="text-gray-600">Configurez et estimez votre prix</p>
                        </div>

                        <form class="space-y-4" id="configurator-form">
                            <!-- Support -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    <i class="fas fa-box-open text-red-600"></i> Type de support
                                </label>
                                <select class="w-full border-2 border-gray-200 rounded-lg p-3 font-semibold text-gray-900 focus:border-red-500 focus:outline-none" id="support-select">
                                    <option value="20">Forex 3mm (20€/m²)</option>
                                    <option value="30">Forex 5mm (30€/m²)</option>
                                    <option value="40">Dibond 3mm (40€/m²)</option>
                                    <option value="5">Bâche PVC 500g (5€/m²)</option>
                                    <option value="6">Textile Polyester (6€/m²)</option>
                                    <option value="14">Textile Rétro-éclairé (14€/m²)</option>
                                </select>
                            </div>

                            <!-- Dimensions personnalisées - TOUJOURS VISIBLES -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    <i class="fas fa-ruler-combined text-red-600"></i> Dimensions personnalisées
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs text-gray-600 mb-1">Largeur (cm)</label>
                                        <input type="number" value="100" min="10" max="500" class="w-full border-2 border-gray-200 rounded-lg p-3 font-bold text-gray-900 focus:border-red-500 focus:outline-none" id="width-input">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-600 mb-1">Hauteur (cm)</label>
                                        <input type="number" value="150" min="10" max="500" class="w-full border-2 border-gray-200 rounded-lg p-3 font-bold text-gray-900 focus:border-red-500 focus:outline-none" id="height-input">
                                    </div>
                                </div>
                            </div>

                            <!-- Quantité -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    <i class="fas fa-hashtag text-red-600"></i> Quantité
                                </label>
                                <input type="number" value="1" min="1" max="1000" class="w-full border-2 border-gray-200 rounded-lg p-3 font-bold text-gray-900 focus:border-red-500 focus:outline-none" id="quantity-input">
                                <p class="text-xs text-gray-500 mt-1">Prix dégressifs dès 2m² !</p>
                            </div>

                            <!-- Prix estimé -->
                            <div class="bg-gradient-to-r from-red-50 to-pink-50 border-2 border-red-500 rounded-xl p-6 text-center">
                                <div class="text-sm text-gray-600 mb-1">Prix estimé</div>
                                <div class="text-4xl font-black text-red-600 mb-1" id="estimated-price">30,00€</div>
                                <div class="text-sm text-gray-500">HT • <span id="surface-display">1.50m²</span></div>
                            </div>

                            <!-- CTA -->
                            <button type="button" onclick="window.location.href='/contact.php'" class="w-full bg-red-600 hover:bg-red-700 text-white px-6 py-4 rounded-xl font-bold text-lg transition shadow-lg">
                                <i class="fas fa-envelope"></i> Demander un Devis
                            </button>
                        </form>

                        <!-- Réassurance -->
                        <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <i class="fas fa-shield-alt text-green-600"></i>
                                <span><strong>Paiement sécurisé</strong></span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <i class="fas fa-truck text-blue-600"></i>
                                <span><strong>Livraison Europe 48-72h</strong></span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <i class="fas fa-check-circle text-green-600"></i>
                                <span><strong>Qualité garantie</strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- Besoin d'aide -->
                    <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl p-6 mt-6 text-white text-center">
                        <i class="fas fa-headset text-4xl mb-3"></i>
                        <h4 class="font-black text-lg mb-2">Besoin de conseils ?</h4>
                        <p class="text-sm mb-4 text-blue-100">Notre équipe vous répond en moins d'1h</p>
                        <a href="tel:0123456789" class="inline-flex items-center gap-2 bg-white text-blue-600 px-6 py-3 rounded-lg font-bold hover:bg-blue-50 transition">
                            <i class="fas fa-phone"></i> 01 23 45 67 89
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ===== CTA FINAL ===== -->
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-4xl font-black text-gray-900 mb-6">Vous ne trouvez pas votre support ?</h2>
        <p class="text-xl text-gray-600 mb-10">
            Nous proposons plus de 50 supports différents. Contactez-nous pour un conseil personnalisé !
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="/contact.php" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-10 py-5 rounded-xl font-bold text-lg shadow-xl transition">
                <i class="fas fa-envelope"></i> Contactez-nous
            </a>
            <a href="tel:0123456789" class="inline-flex items-center gap-2 bg-white border-2 border-gray-300 hover:border-red-600 text-gray-900 px-10 py-5 rounded-xl font-bold text-lg transition">
                <i class="fas fa-phone"></i> 01 23 45 67 89
            </a>
        </div>
    </div>
</section>

<!-- ===== SCHEMA.ORG - SEO/LLM OPTIMIZATION ===== -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "name": "Supports d'impression grand format",
    "description": "Catalogue complet de supports d'impression professionnels fabrication européenne",
    "numberOfItems": 12,
    "itemListElement": [
        {
            "@type": "Product",
            "position": 1,
            "name": "Forex 3mm",
            "description": "PVC expansé 3mm polyvalent pour enseignes et panneaux",
            "brand": {"@type": "Brand", "name": "Imprixo"},
            "offers": {
                "@type": "Offer",
                "price": "20",
                "priceCurrency": "EUR",
                "availability": "https://schema.org/InStock",
                "priceValidUntil": "2025-12-31"
            }
        },
        {
            "@type": "Product",
            "position": 2,
            "name": "Dibond Aluminium 3mm",
            "description": "Composite aluminium premium pour enseignes durables 10+ ans",
            "brand": {"@type": "Brand", "name": "Imprixo"},
            "offers": {
                "@type": "Offer",
                "price": "40",
                "priceCurrency": "EUR",
                "availability": "https://schema.org/InStock",
                "priceValidUntil": "2025-12-31"
            }
        },
        {
            "@type": "Product",
            "position": 3,
            "name": "Bâche PVC Frontlit 500g",
            "description": "Bâche PVC économique pour extérieur, durée 3-5 ans",
            "brand": {"@type": "Brand", "name": "Imprixo"},
            "offers": {
                "@type": "Offer",
                "price": "5",
                "priceCurrency": "EUR",
                "availability": "https://schema.org/InStock",
                "priceValidUntil": "2025-12-31"
            }
        },
        {
            "@type": "Product",
            "position": 4,
            "name": "Textile Polyester 115g",
            "description": "Textile standard pour stands et événementiel",
            "brand": {"@type": "Brand", "name": "Imprixo"},
            "offers": {
                "@type": "Offer",
                "price": "6",
                "priceCurrency": "EUR",
                "availability": "https://schema.org/InStock",
                "priceValidUntil": "2025-12-31"
            }
        }
    ]
}
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "name": "Accueil",
            "item": "https://imprixo.fr/"
        },
        {
            "@type": "ListItem",
            "position": 2,
            "name": "Produits",
            "item": "https://imprixo.fr/produits.php"
        }
    ]
}
</script>

<!-- ===== JAVASCRIPT - CONFIGURATEUR ===== -->
<script>
// Calculateur de prix dynamique
const supportSelect = document.getElementById('support-select');
const widthInput = document.getElementById('width-input');
const heightInput = document.getElementById('height-input');
const quantityInput = document.getElementById('quantity-input');
const estimatedPrice = document.getElementById('estimated-price');
const surfaceDisplay = document.getElementById('surface-display');

function calculatePrice() {
    const pricePerM2 = parseFloat(supportSelect.value);
    const width = parseFloat(widthInput.value) || 100;
    const height = parseFloat(heightInput.value) || 150;
    const quantity = parseInt(quantityInput.value) || 1;

    // Calculer surface en m²
    const surface = (width * height) / 10000;
    const totalSurface = surface * quantity;

    // Prix de base
    let price = totalSurface * pricePerM2;

    // Remises dégressives
    if (totalSurface >= 10) {
        price *= 0.6; // -40%
    } else if (totalSurface >= 5) {
        price *= 0.7; // -30%
    } else if (totalSurface >= 2) {
        price *= 0.8; // -20%
    }

    // Affichage
    estimatedPrice.textContent = price.toFixed(2) + '€';
    surfaceDisplay.textContent = totalSurface.toFixed(2) + 'm²';
}

// Events
supportSelect.addEventListener('change', calculatePrice);
widthInput.addEventListener('input', calculatePrice);
heightInput.addEventListener('input', calculatePrice);
quantityInput.addEventListener('input', calculatePrice);

// Calcul initial
calculatePrice();

// Filtres
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

        // Smooth scroll to first visible category
        const firstVisible = document.querySelector(`[data-category="${filter === 'all' ? 'pvc' : filter}"]`);
        if (firstVisible) {
            firstVisible.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

// Style active pour filtres
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(b => {
            b.classList.remove('bg-white', 'text-gray-900', 'shadow-lg');
            b.classList.add('bg-white/10');
        });
        this.classList.remove('bg-white/10');
        this.classList.add('bg-white', 'text-gray-900', 'shadow-lg');
    });
});
</script>

<style>
.filter-btn.active {
    background: white !important;
    color: #111827 !important;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
