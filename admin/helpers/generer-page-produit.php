<?php
/**
 * Helper pour générer une page HTML de produit
 */

/**
 * Générer le HTML d'une page produit
 */
function genererPageProduitHTML($p) {
    // Échapper les données
    $nom = htmlspecialchars($p['NOM_PRODUIT']);
    $soustitre = htmlspecialchars($p['SOUS_TITRE']);
    $descCourte = htmlspecialchars($p['DESCRIPTION_COURTE']);
    $descLongue = htmlspecialchars($p['DESCRIPTION_LONGUE']);
    $categorie = htmlspecialchars($p['CATEGORIE']);
    $id = htmlspecialchars($p['ID_PRODUIT']);

    // Prix dégressifs
    $prix010 = floatval($p['PRIX_0_10_M2']);
    $prix1150 = floatval($p['PRIX_11_50_M2']);
    $prix51100 = floatval($p['PRIX_51_100_M2']);
    $prix101300 = floatval($p['PRIX_101_300_M2']);
    $prix300plus = floatval($p['PRIX_300_PLUS_M2']);

    // Specs techniques
    $poids = htmlspecialchars($p['POIDS_M2']);
    $epaisseur = htmlspecialchars($p['EPAISSEUR']);
    $formatMax = htmlspecialchars($p['FORMAT_MAX_CM']);
    $usage = htmlspecialchars($p['USAGE']);
    $dureeVie = htmlspecialchars($p['DUREE_VIE']);
    $certification = htmlspecialchars($p['CERTIFICATION']);
    $finition = htmlspecialchars($p['FINITION']);
    $delai = intval($p['DELAI_STANDARD_JOURS']);

    $html = <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO -->
    <title>$nom - $soustitre | Imprixo</title>
    <meta name="description" content="$descCourte ✓ Prix dégressifs {$prix300plus}€→{$prix010}€/m² ✓ Livraison {$delai}j ✓ $certification">
    <link rel="canonical" href="https://imprixo.fr/produit/$id.html">

    <!-- Open Graph -->
    <meta property="og:title" content="$nom - Imprixo">
    <meta property="og:description" content="$descCourte">
    <meta property="og:type" content="product">
    <meta property="og:url" content="https://imprixo.fr/produit/$id.html">

    <!-- Schema.org Product -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org/",
        "@type": "Product",
        "name": "$nom",
        "description": "$descCourte",
        "brand": {
            "@type": "Brand",
            "name": "Imprixo"
        },
        "offers": {
            "@type": "AggregateOffer",
            "lowPrice": "$prix300plus",
            "highPrice": "$prix010",
            "priceCurrency": "EUR",
            "availability": "https://schema.org/InStock"
        },
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "4.7",
            "reviewCount": "183"
        }
    }
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/assets/css/product.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap');
        * { font-family: 'Roboto', sans-serif; }

        .price-sidebar { position: sticky; top: 120px; }
        .spec-badge {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-left: 4px solid #3b82f6;
        }
        .btn-primary {
            background: linear-gradient(135deg, #e63946 0%, #d62839 100%);
            transition: all 0.3s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(230, 57, 70, 0.3);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header chargé dynamiquement -->
    <script>
    fetch('/includes/header.html')
        .then(r => r.text())
        .then(html => document.getElementById('header-placeholder').innerHTML = html);
    </script>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Fil d'Ariane -->
        <nav class="text-sm mb-6">
            <a href="/" class="text-gray-600 hover:text-red-600">Accueil</a>
            <span class="mx-2 text-gray-400">›</span>
            <a href="/catalogue.html" class="text-gray-600 hover:text-red-600">Catalogue</a>
            <span class="mx-2 text-gray-400">›</span>
            <span class="text-gray-900 font-semibold">$nom</span>
        </nav>

        <!-- En-tête Produit -->
        <div class="bg-white rounded-xl shadow-lg p-6 md:p-8 mb-6">
            <h1 class="text-4xl font-black text-gray-900 mb-2">$nom</h1>
            <p class="text-xl text-gray-600 mb-4">$soustitre</p>

            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-2">
                    <span class="text-yellow-500 text-xl">★★★★★</span>
                    <span class="text-sm text-gray-600">4.7/5 (183 avis)</span>
                </div>
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded-full font-semibold text-sm">
                    ✓ En stock - Livraison {$delai}j
                </div>
                <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full font-semibold text-sm">
                    🔥 Prix dégressifs
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Image produit -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <img
                        src="/assets/products/$id.jpg"
                        alt="$nom"
                        class="w-full h-96 object-cover rounded-lg mb-4"
                        onerror="this.src='https://placehold.co/800x600/667eea/white?text=' + encodeURIComponent('$nom')"
                    >
                    <div class="grid grid-cols-4 gap-2">
                        <img src="/assets/products/$id-1.jpg" alt="Vue 1" class="w-full h-20 object-cover rounded cursor-pointer hover:opacity-75" onerror="this.style.display='none'">
                        <img src="/assets/products/$id-2.jpg" alt="Vue 2" class="w-full h-20 object-cover rounded cursor-pointer hover:opacity-75" onerror="this.style.display='none'">
                        <img src="/assets/products/$id-3.jpg" alt="Vue 3" class="w-full h-20 object-cover rounded cursor-pointer hover:opacity-75" onerror="this.style.display='none'">
                        <img src="/assets/products/$id-4.jpg" alt="Vue 4" class="w-full h-20 object-cover rounded cursor-pointer hover:opacity-75" onerror="this.style.display='none'">
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                    <h2 class="text-2xl font-black text-gray-900 mb-4">📝 Description</h2>
                    <p class="text-lg text-gray-700 mb-4 font-medium">$descCourte</p>
                    <p class="text-gray-600 leading-relaxed">$descLongue</p>
                </div>

                <!-- Caractéristiques -->
                <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                    <h2 class="text-2xl font-black text-gray-900 mb-6">⚙️ Caractéristiques techniques</h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="spec-badge rounded-lg p-4">
                            <div class="text-sm text-blue-700 font-medium">Poids</div>
                            <div class="text-lg font-black text-gray-900">$poids kg/m²</div>
                        </div>
                        <div class="spec-badge rounded-lg p-4">
                            <div class="text-sm text-blue-700 font-medium">Épaisseur</div>
                            <div class="text-lg font-black text-gray-900">$epaisseur</div>
                        </div>
                        <div class="spec-badge rounded-lg p-4">
                            <div class="text-sm text-blue-700 font-medium">Format maximum</div>
                            <div class="text-lg font-black text-gray-900">$formatMax cm</div>
                        </div>
                        <div class="spec-badge rounded-lg p-4">
                            <div class="text-sm text-blue-700 font-medium">Usage</div>
                            <div class="text-lg font-black text-gray-900">$usage</div>
                        </div>
                        <div class="spec-badge rounded-lg p-4">
                            <div class="text-sm text-blue-700 font-medium">Durée de vie</div>
                            <div class="text-lg font-black text-gray-900">$dureeVie</div>
                        </div>
                        <div class="spec-badge rounded-lg p-4">
                            <div class="text-sm text-blue-700 font-medium">Certification</div>
                            <div class="text-lg font-black text-gray-900">$certification</div>
                        </div>
                        <div class="spec-badge rounded-lg p-4">
                            <div class="text-sm text-blue-700 font-medium">Finition</div>
                            <div class="text-lg font-black text-gray-900">$finition</div>
                        </div>
                        <div class="spec-badge rounded-lg p-4">
                            <div class="text-sm text-blue-700 font-medium">Délai</div>
                            <div class="text-lg font-black text-gray-900">{$delai} jours</div>
                        </div>
                    </div>
                </div>

                <!-- Prix dégressifs -->
                <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                    <h2 class="text-2xl font-black text-gray-900 mb-6">💰 Prix dégressifs au m²</h2>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        <div class="border-2 border-gray-200 rounded-lg p-4 text-center hover:border-red-600 transition">
                            <div class="text-sm text-gray-600 mb-1">0-10 m²</div>
                            <div class="text-2xl font-black text-red-600">{$prix010}€</div>
                            <div class="text-xs text-gray-500">/m²</div>
                        </div>
                        <div class="border-2 border-gray-200 rounded-lg p-4 text-center hover:border-red-600 transition">
                            <div class="text-sm text-gray-600 mb-1">11-50 m²</div>
                            <div class="text-2xl font-black text-red-600">{$prix1150}€</div>
                            <div class="text-xs text-gray-500">/m²</div>
                        </div>
                        <div class="border-2 border-gray-200 rounded-lg p-4 text-center hover:border-red-600 transition">
                            <div class="text-sm text-gray-600 mb-1">51-100 m²</div>
                            <div class="text-2xl font-black text-red-600">{$prix51100}€</div>
                            <div class="text-xs text-gray-500">/m²</div>
                        </div>
                        <div class="border-2 border-gray-200 rounded-lg p-4 text-center hover:border-red-600 transition">
                            <div class="text-sm text-gray-600 mb-1">101-300 m²</div>
                            <div class="text-2xl font-black text-red-600">{$prix101300}€</div>
                            <div class="text-xs text-gray-500">/m²</div>
                        </div>
                        <div class="border-2 border-red-600 bg-red-50 rounded-lg p-4 text-center">
                            <div class="text-sm text-red-700 mb-1 font-semibold">300+ m²</div>
                            <div class="text-2xl font-black text-red-600">{$prix300plus}€</div>
                            <div class="text-xs text-red-700 font-semibold">Meilleur prix !</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Prix & Commande -->
            <div class="lg:col-span-1">
                <div class="price-sidebar bg-white rounded-xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-2">À partir de</div>
                    <div class="text-4xl font-black text-gray-900 mb-1">{$prix300plus}€</div>
                    <div class="text-lg text-gray-600 mb-6">/m²</div>

                    <div class="bg-gradient-to-r from-red-50 to-orange-50 border-2 border-red-200 rounded-lg p-4 mb-6">
                        <div class="text-sm font-semibold text-red-900 mb-2">🔥 Économisez jusqu'à</div>
                        <div class="text-3xl font-black text-red-600">-{round((($prix010-$prix300plus)/$prix010)*100)}%</div>
                        <div class="text-xs text-red-700 mt-1">sur les grandes quantités</div>
                    </div>

                    <button class="w-full btn-primary text-white py-4 rounded-lg font-black text-lg mb-3 shadow-lg">
                        🛒 CONFIGURER & COMMANDER
                    </button>

                    <button class="w-full border-2 border-gray-300 text-gray-700 py-3 rounded-lg font-bold hover:border-red-600 hover:text-red-600 transition mb-6">
                        📄 Demander un devis
                    </button>

                    <!-- Trust badges -->
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-2xl mb-1">🚚</div>
                            <div class="text-xs font-bold text-gray-700">Livraison {$delai}j</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-2xl mb-1">🔒</div>
                            <div class="text-xs font-bold text-gray-700">Paiement sécurisé</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-2xl mb-1">✓</div>
                            <div class="text-xs font-bold text-gray-700">Garantie qualité</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-2xl mb-1">⭐</div>
                            <div class="text-xs font-bold text-gray-700">4.7/5 avis</div>
                        </div>
                    </div>

                    <div class="border-t pt-4">
                        <div class="text-sm text-gray-600 mb-3">✓ Livraison gratuite dès 200€</div>
                        <div class="text-sm text-gray-600 mb-3">✓ Fichiers techniques fournis</div>
                        <div class="text-sm text-gray-600">✓ Support client 7j/7</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer chargé dynamiquement -->
    <script>
    fetch('/includes/footer.html')
        .then(r => r.text())
        .then(html => document.getElementById('footer-placeholder').innerHTML = html);
    </script>

    <script>
    // Configuration produit pour le panier
    window.productData = {
        id: '$id',
        nom: '$nom',
        prix_min: $prix300plus,
        prix_max: $prix010,
        delai: $delai
    };
    </script>
</body>
</html>
HTML;

    return $html;
}

/**
 * Générer et sauvegarder la page HTML d'un produit
 *
 * @param array $produit Données du produit
 * @param string $outputDir Dossier de sortie
 * @return bool Succès ou échec
 */
function genererEtSauvegarderPageProduit($produit, $outputDir = null) {
    if ($outputDir === null) {
        $outputDir = __DIR__ . '/../../produit/';
    }

    // Créer le dossier si nécessaire
    if (!is_dir($outputDir)) {
        mkdir($outputDir, 0755, true);
    }

    // Nettoyer l'ID pour le nom de fichier
    $fileId = preg_replace('/[^A-Za-z0-9\-_]/', '', $produit['ID_PRODUIT']);
    $fileName = $outputDir . $fileId . '.html';

    // Générer le HTML
    $html = genererPageProduitHTML($produit);

    // Sauvegarder
    return file_put_contents($fileName, $html) !== false;
}
