<?php
/**
 * Générateur Autonome de Pages Produits HTML avec CONFIGURATEUR COMPLET
 * Site e-commerce Imprixo - OLB SPORTS OOD (Bulgarie)
 */

// Configuration
$csvFile = __DIR__ . '/CATALOGUE_COMPLET_VISUPRINT.csv';
$outputDir = __DIR__ . '/produit/';

if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

$stats = ['generated' => 0, 'errors' => 0];

if (!file_exists($csvFile)) {
    die("ERREUR: Le fichier CSV n'existe pas: $csvFile");
}

$file = fopen($csvFile, 'r');
$headers = fgetcsv($file);

echo "=== GÉNÉRATION PAGES PRODUITS AVEC CONFIGURATEUR ===\n\n";

while (($row = fgetcsv($file)) !== false) {
    if (count($row) !== count($headers)) continue;

    $produit = array_combine($headers, $row);
    if (empty($produit['ID_PRODUIT'])) continue;

    $fileId = preg_replace('/[^A-Za-z0-9\-_]/', '', $produit['ID_PRODUIT']);
    $fileName = $outputDir . $fileId . '.html';

    $html = genererPageProduitHTML($produit);

    if (file_put_contents($fileName, $html)) {
        echo "✓ {$produit['NOM_PRODUIT']} → $fileId.html\n";
        $stats['generated']++;
    } else {
        echo "✗ ERREUR: {$produit['NOM_PRODUIT']}\n";
        $stats['errors']++;
    }
}

fclose($file);

echo "\n=== RÉSUMÉ ===\n";
echo "✓ Pages générées: {$stats['generated']}\n";
echo "✗ Erreurs: {$stats['errors']}\n";

function genererPageProduitHTML($p) {
    $nom = htmlspecialchars($p['NOM_PRODUIT']);
    $soustitre = htmlspecialchars($p['SOUS_TITRE']);
    $descCourte = htmlspecialchars($p['DESCRIPTION_COURTE']);
    $descLongue = htmlspecialchars($p['DESCRIPTION_LONGUE']);
    $id = htmlspecialchars($p['ID_PRODUIT']);
    $categorie = htmlspecialchars($p['CATEGORIE']);

    $prix010 = floatval($p['PRIX_0_10_M2']);
    $prix1150 = floatval($p['PRIX_11_50_M2']);
    $prix51100 = floatval($p['PRIX_51_100_M2']);
    $prix101300 = floatval($p['PRIX_101_300_M2']);
    $prix300plus = floatval($p['PRIX_300_PLUS_M2']);

    $poids = htmlspecialchars($p['POIDS_M2']);
    $epaisseur = htmlspecialchars($p['EPAISSEUR']);
    $formatMax = htmlspecialchars($p['FORMAT_MAX_CM']);
    $usage = htmlspecialchars($p['USAGE']);
    $dureeVie = htmlspecialchars($p['DUREE_VIE']);
    $certification = htmlspecialchars($p['CERTIFICATION']);
    $finition = htmlspecialchars($p['FINITION']);
    $delai = intval($p['DELAI_STANDARD_JOURS']);

    // Formats standards selon catégorie
    $formatsStandards = [
        'A0 (84×119 cm)' => ['w' => 84, 'h' => 119],
        'A1 (59×84 cm)' => ['w' => 59, 'h' => 84],
        'A2 (42×59 cm)' => ['w' => 42, 'h' => 59],
        'A3 (30×42 cm)' => ['w' => 30, 'h' => 42],
        '100×100 cm' => ['w' => 100, 'h' => 100],
        '200×100 cm' => ['w' => 200, 'h' => 100],
        '300×200 cm' => ['w' => 300, 'h' => 200],
        'Personnalisé' => ['w' => 100, 'h' => 100]
    ];

    return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$nom - Impression Europe | Imprixo</title>
    <meta name="description" content="$descCourte ✓ Prix {$prix300plus}€-{$prix010}€/m² ✓ Livraison Europe {$delai}j ✓ $certification ✓ Fabrication UE">
    <link rel="canonical" href="https://imprixo.fr/produit/$id.html">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap');
        *{font-family:'Roboto',sans-serif}
        .btn-primary{background:linear-gradient(135deg,#e63946 0%,#d62839 100%);transition:all 0.3s}
        .btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(230,57,70,0.3)}
        .config-option{border:2px solid #e5e7eb;transition:all 0.2s;cursor:pointer}
        .config-option:hover{border-color:#e63946;background:#fff5f5}
        .config-option.selected{border-color:#e63946;background:#fff1f2;font-weight:700}
        .price-badge{animation:pulse 2s infinite}
        @keyframes pulse{0%,100%{opacity:1}50%{opacity:0.8}}
    </style>
</head>
<body class="bg-gray-50">
    <div id="header-placeholder"></div>
    <script>fetch('/includes/header.html').then(r=>r.text()).then(html=>document.getElementById('header-placeholder').innerHTML=html)</script>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <nav class="text-sm mb-6">
            <a href="/index.html" class="text-gray-600 hover:text-red-600">Accueil</a>
            <span class="mx-2 text-gray-400">›</span>
            <a href="/catalogue.html" class="text-gray-600 hover:text-red-600">Catalogue</a>
            <span class="mx-2 text-gray-400">›</span>
            <span class="text-gray-900 font-semibold">$nom</span>
        </nav>

        <div class="bg-white rounded-xl shadow-lg p-6 md:p-8 mb-6">
            <h1 class="text-4xl font-black text-gray-900 mb-2">$nom</h1>
            <p class="text-xl text-gray-600 mb-4">$soustitre</p>
            <div class="flex flex-wrap items-center gap-4">
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded-full font-semibold text-sm">✓ Fabrication UE</div>
                <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full font-semibold text-sm">🇪🇺 Livraison Europe {$delai}j</div>
                <div class="bg-red-100 text-red-800 px-4 py-2 rounded-full font-semibold text-sm">🔥 Prix dégressifs</div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- CONFIGURATEUR PRINCIPAL -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg p-6 md:p-8 mb-6">
                    <h2 class="text-2xl font-black text-gray-900 mb-6 flex items-center gap-3">
                        <span class="text-3xl">⚙️</span> Configurez votre produit
                    </h2>

                    <!-- Format -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-3">📐 Format</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3" id="format-selector">
HTML;

    foreach ($formatsStandards as $label => $dims) {
        $isPersonnalise = $label === 'Personnalisé' ? 'true' : 'false';
        $html .= <<<HTML
                            <div class="config-option rounded-lg p-3 text-center" data-format="$label" data-w="{$dims['w']}" data-h="{$dims['h']}" data-custom="$isPersonnalise">
                                <div class="font-semibold text-sm">$label</div>
                            </div>
HTML;
    }

    $html .= <<<HTML
                        </div>
                    </div>

                    <!-- Dimensions personnalisées -->
                    <div class="grid grid-cols-2 gap-4 mb-6" id="custom-dimensions" style="display:none">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Largeur (cm)</label>
                            <input type="number" id="largeur" value="100" min="10" max="500" step="1" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none font-bold text-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Hauteur (cm)</label>
                            <input type="number" id="hauteur" value="100" min="10" max="500" step="1" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none font-bold text-lg">
                        </div>
                    </div>

                    <!-- Quantité -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">📦 Quantité</label>
                        <input type="number" id="quantite" value="1" min="1" max="1000" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none font-bold text-lg">
                        <p class="text-xs text-gray-500 mt-1">Prix dégressifs selon quantité</p>
                    </div>

                    <!-- Options -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-3">🎨 Finition</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="config-option rounded-lg p-4 selected" data-option="standard" data-prix="0">
                                <div class="font-bold">Standard</div>
                                <div class="text-sm text-gray-600">Inclus</div>
                            </div>
                            <div class="config-option rounded-lg p-4" data-option="oeillets" data-prix="5">
                                <div class="font-bold">Avec œillets</div>
                                <div class="text-sm text-gray-600">+5€</div>
                            </div>
                            <div class="config-option rounded-lg p-4" data-option="support" data-prix="12">
                                <div class="font-bold">Sur support</div>
                                <div class="text-sm text-gray-600">+12€/m²</div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload fichier -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-3">📁 Votre fichier (optionnel)</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-red-600 transition cursor-pointer" id="upload-zone">
                            <div class="text-4xl mb-3">📤</div>
                            <div class="font-bold text-gray-900 mb-2">Glissez votre fichier ici</div>
                            <div class="text-sm text-gray-600 mb-3">ou cliquez pour parcourir</div>
                            <div class="text-xs text-gray-500">PDF, AI, EPS, PNG, JPG • Max 100 Mo • 300 DPI recommandé</div>
                            <input type="file" id="file-input" class="hidden" accept=".pdf,.ai,.eps,.png,.jpg,.jpeg">
                        </div>
                        <p class="text-sm text-gray-600 mt-2">💡 Vous pouvez aussi commander maintenant et envoyer votre fichier plus tard</p>
                    </div>

                    <!-- Résumé config -->
                    <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6">
                        <h3 class="font-bold text-blue-900 mb-3">📋 Résumé de votre configuration</h3>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div><span class="text-gray-600">Format:</span> <span class="font-bold" id="resume-format">-</span></div>
                            <div><span class="text-gray-600">Dimensions:</span> <span class="font-bold" id="resume-dims">-</span></div>
                            <div><span class="text-gray-600">Surface:</span> <span class="font-bold" id="resume-surface">-</span></div>
                            <div><span class="text-gray-600">Quantité:</span> <span class="font-bold" id="resume-qte">-</span></div>
                            <div><span class="text-gray-600">Finition:</span> <span class="font-bold" id="resume-finition">Standard</span></div>
                            <div><span class="text-gray-600">Délai:</span> <span class="font-bold text-green-700">{$delai} jours ouvrés</span></div>
                        </div>
                    </div>
                </div>

                <!-- Description produit -->
                <div class="bg-white rounded-xl shadow-lg p-6 md:p-8 mb-6">
                    <h2 class="text-2xl font-black text-gray-900 mb-4">📝 Description</h2>
                    <p class="text-lg text-gray-700 mb-4 font-medium">$descCourte</p>
                    <p class="text-gray-600 leading-relaxed">$descLongue</p>

                    <div class="mt-6 bg-green-50 border-l-4 border-green-600 p-4 rounded">
                        <div class="font-bold text-green-900 mb-2">🇪🇺 Fabrication européenne de qualité</div>
                        <p class="text-sm text-green-800">Produit fabriqué en Europe par <strong>OLB SPORTS OOD</strong> (Bulgarie). Livraison dans toute l'Europe en {$delai} jours ouvrés.</p>
                    </div>
                </div>

                <!-- Caractéristiques -->
                <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                    <h2 class="text-2xl font-black text-gray-900 mb-6">⚙️ Caractéristiques techniques</h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="bg-blue-50 border-l-4 border-blue-600 rounded-lg p-4">
                            <div class="text-sm text-blue-700 font-medium">Poids</div>
                            <div class="text-lg font-black text-gray-900">$poids kg/m²</div>
                        </div>
                        <div class="bg-blue-50 border-l-4 border-blue-600 rounded-lg p-4">
                            <div class="text-sm text-blue-700 font-medium">Épaisseur</div>
                            <div class="text-lg font-black text-gray-900">$epaisseur</div>
                        </div>
                        <div class="bg-blue-50 border-l-4 border-blue-600 rounded-lg p-4">
                            <div class="text-sm text-blue-700 font-medium">Format maximum</div>
                            <div class="text-lg font-black text-gray-900">$formatMax cm</div>
                        </div>
                        <div class="bg-blue-50 border-l-4 border-blue-600 rounded-lg p-4">
                            <div class="text-sm text-blue-700 font-medium">Usage</div>
                            <div class="text-lg font-black text-gray-900">$usage</div>
                        </div>
                        <div class="bg-blue-50 border-l-4 border-blue-600 rounded-lg p-4">
                            <div class="text-sm text-blue-700 font-medium">Durée de vie</div>
                            <div class="text-lg font-black text-gray-900">$dureeVie</div>
                        </div>
                        <div class="bg-blue-50 border-l-4 border-blue-600 rounded-lg p-4">
                            <div class="text-sm text-blue-700 font-medium">Certification</div>
                            <div class="text-lg font-black text-gray-900">$certification</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR PRIX & COMMANDE -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 bg-white rounded-xl shadow-lg p-6">
                    <div class="text-sm text-gray-600 mb-2">Prix TTC à partir de</div>
                    <div class="text-5xl font-black text-gray-900 mb-1 price-badge" id="prix-unitaire">{$prix300plus}€</div>
                    <div class="text-lg text-gray-600 mb-6">/m²</div>

                    <div class="bg-gradient-to-r from-red-50 to-orange-50 border-2 border-red-200 rounded-lg p-4 mb-6">
                        <div class="text-sm font-semibold text-red-900 mb-2">💰 PRIX TOTAL TTC</div>
                        <div class="text-3xl font-black text-red-600" id="prix-total">-</div>
                        <div class="text-xs text-red-700 mt-2">TVA incluse • Livraison Europe</div>
                    </div>

                    <button class="w-full btn-primary text-white py-4 rounded-lg font-black text-lg mb-3 shadow-lg" id="btn-add-cart">
                        🛒 AJOUTER AU PANIER
                    </button>

                    <button class="w-full border-2 border-gray-300 text-gray-700 py-3 rounded-lg font-bold hover:border-red-600 hover:text-red-600 transition mb-6">
                        📄 Demander un devis
                    </button>

                    <!-- Trust badges -->
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-2xl mb-1">🇪🇺</div>
                            <div class="text-xs font-bold text-gray-700">Fabrication UE</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-2xl mb-1">🚚</div>
                            <div class="text-xs font-bold text-gray-700">Livraison {$delai}j</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-2xl mb-1">✓</div>
                            <div class="text-xs font-bold text-gray-700">Qualité garantie</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-2xl mb-1">🔒</div>
                            <div class="text-xs font-bold text-gray-700">Paiement sécurisé</div>
                        </div>
                    </div>

                    <div class="border-t pt-4 text-sm text-gray-600 space-y-2">
                        <div>✓ Livraison gratuite dès 200€</div>
                        <div>✓ Fichiers techniques fournis</div>
                        <div>✓ Support client 7j/7</div>
                        <div>✓ Paiement sécurisé (CB, PayPal)</div>
                    </div>

                    <!-- Prix dégressifs -->
                    <div class="mt-6 pt-6 border-t">
                        <h3 class="font-bold text-gray-900 mb-3">💰 Prix dégressifs au m²</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between"><span>0-10 m²</span><span class="font-bold">{$prix010}€/m²</span></div>
                            <div class="flex justify-between"><span>11-50 m²</span><span class="font-bold text-green-700">{$prix1150}€/m²</span></div>
                            <div class="flex justify-between"><span>51-100 m²</span><span class="font-bold text-green-700">{$prix51100}€/m²</span></div>
                            <div class="flex justify-between"><span>101-300 m²</span><span class="font-bold text-green-700">{$prix101300}€/m²</span></div>
                            <div class="flex justify-between bg-red-50 p-2 rounded"><span class="font-bold">300+ m²</span><span class="font-black text-red-600">{$prix300plus}€/m²</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="footer-placeholder"></div>
    <script>fetch('/includes/footer.html').then(r=>r.text()).then(html=>document.getElementById('footer-placeholder').innerHTML=html)</script>

    <script>
    // CONFIGURATEUR PRODUIT COMPLET
    const PRODUIT = {
        id: '$id',
        nom: '$nom',
        prix: {
            '0-10': $prix010,
            '11-50': $prix1150,
            '51-100': $prix51100,
            '101-300': $prix101300,
            '300+': $prix300plus
        },
        delai: $delai
    };

    let config = {
        largeur: 100,
        hauteur: 100,
        quantite: 1,
        finition: 'standard',
        prixFinition: 0,
        format: 'Personnalisé',
        fichier: null
    };

    // Sélection format
    document.querySelectorAll('[data-format]').forEach(el => {
        el.addEventListener('click', function() {
            document.querySelectorAll('[data-format]').forEach(e => e.classList.remove('selected'));
            this.classList.add('selected');

            config.format = this.dataset.format;
            config.largeur = parseFloat(this.dataset.w);
            config.hauteur = parseFloat(this.dataset.h);

            const isCustom = this.dataset.custom === 'true';
            document.getElementById('custom-dimensions').style.display = isCustom ? 'grid' : 'none';

            if (!isCustom) {
                document.getElementById('largeur').value = config.largeur;
                document.getElementById('hauteur').value = config.hauteur;
            }

            calculerPrix();
        });
    });

    // Premier format sélectionné par défaut
    document.querySelector('[data-format]').click();

    // Dimensions personnalisées
    ['largeur', 'hauteur'].forEach(dim => {
        document.getElementById(dim).addEventListener('input', function() {
            config[dim] = parseFloat(this.value) || 0;
            config.format = 'Personnalisé';
            calculerPrix();
        });
    });

    // Quantité
    document.getElementById('quantite').addEventListener('input', function() {
        config.quantite = parseInt(this.value) || 1;
        calculerPrix();
    });

    // Options finition
    document.querySelectorAll('[data-option]').forEach(el => {
        el.addEventListener('click', function() {
            document.querySelectorAll('[data-option]').forEach(e => e.classList.remove('selected'));
            this.classList.add('selected');
            config.finition = this.dataset.option;
            config.prixFinition = parseFloat(this.dataset.prix);
            document.getElementById('resume-finition').textContent = this.querySelector('.font-bold').textContent;
            calculerPrix();
        });
    });

    // Upload fichier
    const uploadZone = document.getElementById('upload-zone');
    const fileInput = document.getElementById('file-input');

    uploadZone.addEventListener('click', () => fileInput.click());

    uploadZone.addEventListener('dragover', e => {
        e.preventDefault();
        uploadZone.classList.add('border-red-600');
    });

    uploadZone.addEventListener('dragleave', () => {
        uploadZone.classList.remove('border-red-600');
    });

    uploadZone.addEventListener('drop', e => {
        e.preventDefault();
        uploadZone.classList.remove('border-red-600');
        if (e.dataTransfer.files.length) {
            handleFile(e.dataTransfer.files[0]);
        }
    });

    fileInput.addEventListener('change', function() {
        if (this.files.length) {
            handleFile(this.files[0]);
        }
    });

    function handleFile(file) {
        config.fichier = file;
        uploadZone.innerHTML = `
            <div class="text-4xl mb-3">✓</div>
            <div class="font-bold text-green-700 mb-2">\${file.name}</div>
            <div class="text-sm text-gray-600">\${(file.size / 1024 / 1024).toFixed(2)} Mo</div>
        `;
    }

    // Calcul prix
    function calculerPrix() {
        const surface = (config.largeur * config.hauteur) / 10000;
        const surfaceTotale = surface * config.quantite;

        // Prix dégressif
        let prixM2;
        if (surfaceTotale > 300) prixM2 = PRODUIT.prix['300+'];
        else if (surfaceTotale > 100) prixM2 = PRODUIT.prix['101-300'];
        else if (surfaceTotale > 50) prixM2 = PRODUIT.prix['51-100'];
        else if (surfaceTotale > 10) prixM2 = PRODUIT.prix['11-50'];
        else prixM2 = PRODUIT.prix['0-10'];

        const prixImpression = prixM2 * surface * config.quantite;
        const prixOptions = config.prixFinition * (config.prixFinition > 20 ? 1 : surface) * config.quantite;
        const prixTotal = prixImpression + prixOptions;

        // Affichage
        document.getElementById('prix-unitaire').textContent = prixM2.toFixed(2) + '€';
        document.getElementById('prix-total').textContent = prixTotal.toFixed(2) + ' €';

        // Résumé
        document.getElementById('resume-format').textContent = config.format;
        document.getElementById('resume-dims').textContent = `\${config.largeur}×\${config.hauteur} cm`;
        document.getElementById('resume-surface').textContent = surface.toFixed(2) + ' m²';
        document.getElementById('resume-qte').textContent = config.quantite;
    }

    // Ajout au panier
    document.getElementById('btn-add-cart').addEventListener('click', function() {
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');

        cart.push({
            id: PRODUIT.id,
            nom: PRODUIT.nom,
            config: {...config},
            prix: parseFloat(document.getElementById('prix-total').textContent),
            date: new Date().toISOString()
        });

        localStorage.setItem('cart', JSON.stringify(cart));

        this.textContent = '✓ AJOUTÉ AU PANIER';
        this.style.background = '#10b981';

        setTimeout(() => {
            window.location.href = '/panier.html';
        }, 1000);
    });

    // Calcul initial
    calculerPrix();
    </script>
</body>
</html>
HTML;

    return $html;
}
?>
