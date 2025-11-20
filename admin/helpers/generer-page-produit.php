<?php
/**
 * Helper pour générer une page HTML de produit - VERSION MODERNE
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

    // Calculer économie
    $economie = round((($prix010-$prix300plus)/$prix010)*100);

    // Specs techniques
    $poids = htmlspecialchars($p['POIDS_M2']);
    $epaisseur = htmlspecialchars($p['EPAISSEUR']);
    $formatMax = htmlspecialchars($p['FORMAT_MAX_CM']);
    $usage = htmlspecialchars($p['USAGE']);
    $dureeVie = htmlspecialchars($p['DUREE_VIE']);
    $certification = htmlspecialchars($p['CERTIFICATION']);
    $finition = htmlspecialchars($p['FINITION']);
    $delai = intval($p['DELAI_STANDARD_JOURS']);

    // Déterminer icône selon catégorie
    $iconCategorie = 'fa-box';
    $colorCategorie = 'red';
    if (stripos($categorie, 'PVC') !== false || stripos($categorie, 'Forex') !== false) {
        $iconCategorie = 'fa-square';
        $colorCategorie = 'red';
    } elseif (stripos($categorie, 'Alu') !== false || stripos($categorie, 'Dibond') !== false) {
        $iconCategorie = 'fa-shield-alt';
        $colorCategorie = 'purple';
    } elseif (stripos($categorie, 'Bâche') !== false) {
        $iconCategorie = 'fa-wind';
        $colorCategorie = 'blue';
    } elseif (stripos($categorie, 'Textile') !== false) {
        $iconCategorie = 'fa-compress-alt';
        $colorCategorie = 'pink';
    }

    $html = <<<HTML
<?php
\$pageTitle = '$nom - $soustitre | Imprixo';
\$pageDescription = '$descCourte • Prix dégressifs dès {$prix300plus}€/m² • Fabrication européenne • Livraison Europe 48-72h • $certification';
include __DIR__ . '/../includes/header.php';
?>

<!-- ===== FIL D'ARIANE ===== -->
<section class="bg-gray-50 border-b border-gray-200 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-sm">
            <a href="/" class="text-gray-600 hover:text-red-600 transition">
                <i class="fas fa-home"></i> Accueil
            </a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <a href="/produits.php" class="text-gray-600 hover:text-red-600 transition">
                Produits
            </a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <span class="text-gray-900 font-bold">$nom</span>
        </nav>
    </div>
</section>

<!-- ===== HERO PRODUIT ===== -->
<section class="bg-white py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-start gap-6">
            <div class="w-16 h-16 bg-{$colorCategorie}-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                <i class="fas $iconCategorie text-3xl text-{$colorCategorie}-600"></i>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                    <span class="inline-block px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">
                        <i class="fas fa-check-circle"></i> EN STOCK
                    </span>
                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">
                        <i class="fas fa-truck"></i> LIVRAISON {$delai}J
                    </span>
                    <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold rounded-full">
                        <i class="fas fa-tags"></i> PRIX DÉGRESSIFS -{$economie}%
                    </span>
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-3">$nom</h1>
                <p class="text-xl text-gray-600 mb-4">$soustitre</p>

                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="text-sm font-bold text-gray-600">4.8/5</span>
                        <span class="text-sm text-gray-500">(247 avis)</span>
                    </div>
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-box-open text-green-600"></i>
                        <strong>Catégorie :</strong> $categorie
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== CONTENU PRINCIPAL ===== -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">

            <!-- COLONNE GAUCHE - CONTENU -->
            <div class="flex-1 space-y-6">

                <!-- IMAGE PRODUIT -->
                <div class="bg-white rounded-2xl shadow-md p-8">
                    <div class="aspect-video bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center mb-4">
                        <div class="text-center">
                            <i class="fas $iconCategorie text-6xl text-gray-400 mb-4"></i>
                            <div class="text-2xl font-black text-gray-600">$nom</div>
                            <div class="text-gray-500">$epaisseur</div>
                        </div>
                    </div>
                    <div class="grid grid-cols-4 gap-2">
                        <div class="aspect-square bg-gray-100 rounded-lg"></div>
                        <div class="aspect-square bg-gray-100 rounded-lg"></div>
                        <div class="aspect-square bg-gray-100 rounded-lg"></div>
                        <div class="aspect-square bg-gray-100 rounded-lg"></div>
                    </div>
                </div>

                <!-- DESCRIPTION -->
                <div class="bg-white rounded-2xl shadow-md p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-align-left text-2xl text-blue-600"></i>
                        </div>
                        <h2 class="text-3xl font-black text-gray-900">Description du produit</h2>
                    </div>

                    <div class="mb-6">
                        <p class="text-xl text-gray-800 font-bold mb-4 leading-relaxed">$descCourte</p>
                        <p class="text-gray-700 leading-relaxed">$descLongue</p>
                    </div>

                    <!-- USPs -->
                    <div class="grid md:grid-cols-2 gap-4 mt-6">
                        <div class="flex items-start gap-3 p-4 bg-green-50 rounded-xl border-l-4 border-green-500">
                            <i class="fas fa-check-circle text-2xl text-green-600 mt-1"></i>
                            <div>
                                <div class="font-bold text-gray-900">Fabrication européenne</div>
                                <div class="text-sm text-gray-600">Qualité certifiée et traçable</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-4 bg-blue-50 rounded-xl border-l-4 border-blue-500">
                            <i class="fas fa-truck text-2xl text-blue-600 mt-1"></i>
                            <div>
                                <div class="font-bold text-gray-900">Livraison express</div>
                                <div class="text-sm text-gray-600">Partout en Europe sous 48-72h</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-4 bg-purple-50 rounded-xl border-l-4 border-purple-500">
                            <i class="fas fa-shield-alt text-2xl text-purple-600 mt-1"></i>
                            <div>
                                <div class="font-bold text-gray-900">Garantie qualité</div>
                                <div class="text-sm text-gray-600">$certification</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-4 bg-yellow-50 rounded-xl border-l-4 border-yellow-500">
                            <i class="fas fa-medal text-2xl text-yellow-600 mt-1"></i>
                            <div>
                                <div class="font-bold text-gray-900">Prix compétitifs</div>
                                <div class="text-sm text-gray-600">Remises dégressives automatiques</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARACTÉRISTIQUES TECHNIQUES -->
                <div class="bg-white rounded-2xl shadow-md p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-cogs text-2xl text-purple-600"></i>
                        </div>
                        <h2 class="text-3xl font-black text-gray-900">Caractéristiques techniques</h2>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="bg-gradient-to-br from-gray-50 to-white border-2 border-gray-200 rounded-xl p-5">
                            <div class="flex items-center gap-3 mb-2">
                                <i class="fas fa-weight text-xl text-gray-600"></i>
                                <div class="text-sm font-bold text-gray-600">POIDS</div>
                            </div>
                            <div class="text-2xl font-black text-gray-900">$poids kg/m²</div>
                        </div>

                        <div class="bg-gradient-to-br from-gray-50 to-white border-2 border-gray-200 rounded-xl p-5">
                            <div class="flex items-center gap-3 mb-2">
                                <i class="fas fa-ruler-vertical text-xl text-gray-600"></i>
                                <div class="text-sm font-bold text-gray-600">ÉPAISSEUR</div>
                            </div>
                            <div class="text-2xl font-black text-gray-900">$epaisseur</div>
                        </div>

                        <div class="bg-gradient-to-br from-gray-50 to-white border-2 border-gray-200 rounded-xl p-5">
                            <div class="flex items-center gap-3 mb-2">
                                <i class="fas fa-expand text-xl text-gray-600"></i>
                                <div class="text-sm font-bold text-gray-600">FORMAT MAXIMUM</div>
                            </div>
                            <div class="text-2xl font-black text-gray-900">$formatMax cm</div>
                        </div>

                        <div class="bg-gradient-to-br from-gray-50 to-white border-2 border-gray-200 rounded-xl p-5">
                            <div class="flex items-center gap-3 mb-2">
                                <i class="fas fa-home text-xl text-gray-600"></i>
                                <div class="text-sm font-bold text-gray-600">USAGE</div>
                            </div>
                            <div class="text-lg font-black text-gray-900">$usage</div>
                        </div>

                        <div class="bg-gradient-to-br from-gray-50 to-white border-2 border-gray-200 rounded-xl p-5">
                            <div class="flex items-center gap-3 mb-2">
                                <i class="fas fa-calendar-check text-xl text-gray-600"></i>
                                <div class="text-sm font-bold text-gray-600">DURÉE DE VIE</div>
                            </div>
                            <div class="text-2xl font-black text-gray-900">$dureeVie</div>
                        </div>

                        <div class="bg-gradient-to-br from-gray-50 to-white border-2 border-gray-200 rounded-xl p-5">
                            <div class="flex items-center gap-3 mb-2">
                                <i class="fas fa-certificate text-xl text-gray-600"></i>
                                <div class="text-sm font-bold text-gray-600">CERTIFICATION</div>
                            </div>
                            <div class="text-lg font-black text-gray-900">$certification</div>
                        </div>

                        <div class="bg-gradient-to-br from-gray-50 to-white border-2 border-gray-200 rounded-xl p-5">
                            <div class="flex items-center gap-3 mb-2">
                                <i class="fas fa-paint-brush text-xl text-gray-600"></i>
                                <div class="text-sm font-bold text-gray-600">FINITION</div>
                            </div>
                            <div class="text-lg font-black text-gray-900">$finition</div>
                        </div>

                        <div class="bg-gradient-to-br from-gray-50 to-white border-2 border-gray-200 rounded-xl p-5">
                            <div class="flex items-center gap-3 mb-2">
                                <i class="fas fa-clock text-xl text-gray-600"></i>
                                <div class="text-sm font-bold text-gray-600">DÉLAI STANDARD</div>
                            </div>
                            <div class="text-2xl font-black text-gray-900">{$delai} jours</div>
                        </div>
                    </div>
                </div>

                <!-- GRILLE TARIFAIRE -->
                <div class="bg-white rounded-2xl shadow-md p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-euro-sign text-2xl text-green-600"></i>
                        </div>
                        <h2 class="text-3xl font-black text-gray-900">Grille tarifaire dégressives</h2>
                    </div>

                    <p class="text-gray-600 mb-6">Plus vous commandez, plus vous économisez ! Nos prix dégressifs s'appliquent automatiquement selon la quantité.</p>

                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        <div class="border-2 border-gray-300 rounded-xl p-4 text-center hover:border-red-500 transition hover:shadow-lg">
                            <div class="text-xs font-bold text-gray-500 mb-2">0 - 10 m²</div>
                            <div class="text-3xl font-black text-red-600 mb-1">{$prix010}€</div>
                            <div class="text-sm text-gray-600">/m²</div>
                        </div>

                        <div class="border-2 border-gray-300 rounded-xl p-4 text-center hover:border-red-500 transition hover:shadow-lg">
                            <div class="text-xs font-bold text-gray-500 mb-2">11 - 50 m²</div>
                            <div class="text-3xl font-black text-red-600 mb-1">{$prix1150}€</div>
                            <div class="text-sm text-gray-600">/m²</div>
                            <div class="text-xs font-bold text-green-600 mt-1">
                                -" . round((($prix010-$prix1150)/$prix010)*100) . "%
                            </div>
                        </div>

                        <div class="border-2 border-gray-300 rounded-xl p-4 text-center hover:border-red-500 transition hover:shadow-lg">
                            <div class="text-xs font-bold text-gray-500 mb-2">51 - 100 m²</div>
                            <div class="text-3xl font-black text-red-600 mb-1">{$prix51100}€</div>
                            <div class="text-sm text-gray-600">/m²</div>
                            <div class="text-xs font-bold text-green-600 mt-1">
                                -" . round((($prix010-$prix51100)/$prix010)*100) . "%
                            </div>
                        </div>

                        <div class="border-2 border-gray-300 rounded-xl p-4 text-center hover:border-red-500 transition hover:shadow-lg">
                            <div class="text-xs font-bold text-gray-500 mb-2">101 - 300 m²</div>
                            <div class="text-3xl font-black text-red-600 mb-1">{$prix101300}€</div>
                            <div class="text-sm text-gray-600">/m²</div>
                            <div class="text-xs font-bold text-green-600 mt-1">
                                -" . round((($prix010-$prix101300)/$prix010)*100) . "%
                            </div>
                        </div>

                        <div class="border-2 border-red-500 bg-red-50 rounded-xl p-4 text-center shadow-lg">
                            <div class="text-xs font-bold text-red-700 mb-2">
                                <i class="fas fa-star"></i> 300+ m²
                            </div>
                            <div class="text-3xl font-black text-red-600 mb-1">{$prix300plus}€</div>
                            <div class="text-sm text-red-700 font-bold">/m²</div>
                            <div class="text-xs font-bold text-green-700 mt-1">
                                <i class="fas fa-bolt"></i> MEILLEUR PRIX -{$economie}%
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- SIDEBAR DROITE - COMMANDE -->
            <div class="lg:w-96">
                <div class="sticky top-24 space-y-6">

                    <!-- PRIX & COMMANDE -->
                    <div class="bg-white rounded-2xl shadow-xl p-8 border-2 border-red-500">
                        <div class="text-center mb-6">
                            <div class="text-sm text-gray-600 mb-2">À partir de</div>
                            <div class="text-5xl font-black text-red-600 mb-2">{$prix300plus}€</div>
                            <div class="text-xl text-gray-600 mb-4">/m² HT</div>

                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 text-green-700 rounded-full font-bold text-sm">
                                <i class="fas fa-bolt"></i>
                                <span>Économisez jusqu'à {$economie}%</span>
                            </div>
                        </div>

                        <!-- CONFIGURATEUR -->
                        <form class="space-y-4 mb-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    <i class="fas fa-ruler-combined text-red-600"></i> Dimensions (cm)
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <input type="number" placeholder="Largeur" value="100" class="w-full border-2 border-gray-200 rounded-lg p-3 font-bold focus:border-red-500 focus:outline-none">
                                    </div>
                                    <div>
                                        <input type="number" placeholder="Hauteur" value="150" class="w-full border-2 border-gray-200 rounded-lg p-3 font-bold focus:border-red-500 focus:outline-none">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    <i class="fas fa-hashtag text-red-600"></i> Quantité
                                </label>
                                <input type="number" value="1" min="1" class="w-full border-2 border-gray-200 rounded-lg p-3 font-bold text-gray-900 focus:border-red-500 focus:outline-none">
                            </div>

                            <div class="bg-gradient-to-r from-red-50 to-pink-50 border-2 border-red-500 rounded-xl p-5 text-center">
                                <div class="text-sm text-gray-600 mb-1">Prix estimé</div>
                                <div class="text-4xl font-black text-red-600 mb-1">30,00€</div>
                                <div class="text-sm text-gray-500">HT • 1.5 m²</div>
                            </div>
                        </form>

                        <button class="w-full bg-red-600 hover:bg-red-700 text-white px-6 py-5 rounded-xl font-black text-lg transition shadow-lg hover:scale-105 mb-3">
                            <i class="fas fa-shopping-cart"></i> COMMANDER MAINTENANT
                        </button>

                        <a href="/contact.php" class="block w-full border-2 border-gray-300 hover:border-red-600 text-gray-700 hover:text-red-600 px-6 py-4 rounded-xl font-bold text-center transition">
                            <i class="fas fa-envelope"></i> Demander un Devis Gratuit
                        </a>
                    </div>

                    <!-- RÉASSURANCE -->
                    <div class="bg-white rounded-2xl shadow-md p-6 space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-truck text-xl text-green-600"></i>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">Livraison express</div>
                                <div class="text-sm text-gray-600">Europe sous 48-72h</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-shield-alt text-xl text-blue-600"></i>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">Paiement sécurisé</div>
                                <div class="text-sm text-gray-600">Transaction 100% protégée</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check-circle text-xl text-purple-600"></i>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">Qualité garantie</div>
                                <div class="text-sm text-gray-600">Satisfaction ou remboursé</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-headset text-xl text-yellow-600"></i>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">Support expert</div>
                                <div class="text-sm text-gray-600">Disponible 6j/7</div>
                            </div>
                        </div>
                    </div>

                    <!-- CONTACT -->
                    <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl p-6 text-white text-center">
                        <i class="fas fa-phone-alt text-4xl mb-3"></i>
                        <div class="font-black text-lg mb-2">Besoin de conseils ?</div>
                        <p class="text-sm text-blue-100 mb-4">Notre équipe répond en moins d'1h</p>
                        <a href="tel:0123456789" class="inline-flex items-center gap-2 bg-white text-blue-600 px-6 py-3 rounded-lg font-bold hover:bg-blue-50 transition">
                            <i class="fas fa-phone"></i> 01 23 45 67 89
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- ===== SCHEMA.ORG - SEO/LLM ===== -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "$nom",
    "description": "$descCourte",
    "brand": {
        "@type": "Brand",
        "name": "Imprixo"
    },
    "manufacturer": {
        "@type": "Organization",
        "name": "OLB SPORTS ODD",
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "BG"
        }
    },
    "offers": {
        "@type": "AggregateOffer",
        "lowPrice": "$prix300plus",
        "highPrice": "$prix010",
        "priceCurrency": "EUR",
        "availability": "https://schema.org/InStock",
        "priceValidUntil": "2025-12-31",
        "seller": {
            "@type": "Organization",
            "name": "Imprixo"
        }
    },
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "4.8",
        "reviewCount": "247",
        "bestRating": "5",
        "worstRating": "1"
    },
    "category": "$categorie",
    "additionalProperty": [
        {
            "@type": "PropertyValue",
            "name": "Poids",
            "value": "$poids kg/m²"
        },
        {
            "@type": "PropertyValue",
            "name": "Épaisseur",
            "value": "$epaisseur"
        },
        {
            "@type": "PropertyValue",
            "name": "Usage",
            "value": "$usage"
        },
        {
            "@type": "PropertyValue",
            "name": "Durée de vie",
            "value": "$dureeVie"
        },
        {
            "@type": "PropertyValue",
            "name": "Certification",
            "value": "$certification"
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
        },
        {
            "@type": "ListItem",
            "position": 3,
            "name": "$nom",
            "item": "https://imprixo.fr/produit/$id.html"
        }
    ]
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
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
    $fileName = $outputDir . $fileId . '.php';

    // Générer le HTML
    $html = genererPageProduitHTML($produit);

    // Sauvegarder
    return file_put_contents($fileName, $html) !== false;
}
