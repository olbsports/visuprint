<?php
/**
 * Générateur de Pages Produits HTML Statiques - Imprixo Admin
 * Génère des pages HTML optimisées SEO pour chaque produit
 */

require_once __DIR__ . '/auth.php';
verifierAdminConnecte();
$admin = getAdminInfo();

$pageTitle = 'Génération Pages Produits HTML';

// Configuration
$csvFile = __DIR__ . '/../CATALOGUE_COMPLET_VISUPRINT.csv';
$outputDir = __DIR__ . '/../produit/';

// Créer le dossier si nécessaire
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

// Statistiques
$stats = ['generated' => 0, 'errors' => 0, 'skipped' => 0];
$logs = [];

// Traiter si lancé
$processing = isset($_GET['run']) && $_GET['run'] === '1';

if ($processing) {
    if (!file_exists($csvFile)) {
        $logs[] = ['type' => 'error', 'message' => "Fichier CSV introuvable: $csvFile"];
    } else {
        $file = fopen($csvFile, 'r');
        $headers = fgetcsv($file);

        $logs[] = ['type' => 'info', 'message' => "Lecture du fichier: $csvFile"];

        while (($row = fgetcsv($file)) !== false) {
            if (count($row) !== count($headers)) {
                continue;
            }

            $produit = array_combine($headers, $row);

            if (empty($produit['ID_PRODUIT'])) {
                $stats['skipped']++;
                continue;
            }

            $fileId = preg_replace('/[^A-Za-z0-9\-_]/', '', $produit['ID_PRODUIT']);
            $fileName = $outputDir . $fileId . '.html';
            $html = genererPageProduitHTML($produit);

            if (file_put_contents($fileName, $html)) {
                $logs[] = ['type' => 'success', 'message' => "✓ {$produit['NOM_PRODUIT']} → $fileId.html"];
                $stats['generated']++;
            } else {
                $logs[] = ['type' => 'error', 'message' => "✗ Erreur: {$produit['NOM_PRODUIT']}"];
                $stats['errors']++;
            }
        }

        fclose($file);
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="top-bar">
    <div>
        <h1 class="page-title">🔨 Génération Pages Produits HTML</h1>
        <p class="page-subtitle">Créer des pages HTML statiques optimisées SEO</p>
    </div>
    <div class="top-bar-actions">
        <a href="/admin/parametres.php" class="btn btn-secondary">← Retour</a>
    </div>
</div>

<?php if (!$processing): ?>
    <!-- Avant lancement -->
    <div class="card">
        <h2 style="font-size: 20px; margin-bottom: 16px; color: var(--primary); font-weight: 700;">📋 Informations</h2>
        <p style="color: var(--text-secondary); margin-bottom: 16px;">
            Cet outil va générer des pages HTML statiques pour chaque produit du catalogue.
            Les pages seront créées dans le dossier <code style="background: var(--bg-hover); padding: 2px 8px; border-radius: 4px; color: var(--primary);">/produit/</code>
        </p>

        <div style="display: grid; gap: 12px; margin-bottom: 24px;">
            <div style="padding: 16px; background: var(--bg-hover); border-radius: var(--radius-md); border-left: 4px solid var(--info);">
                <div style="font-weight: 600; margin-bottom: 4px;">📂 Fichier source</div>
                <div style="font-family: monospace; font-size: 14px; color: var(--text-secondary);"><?php echo htmlspecialchars($csvFile); ?></div>
            </div>

            <div style="padding: 16px; background: var(--bg-hover); border-radius: var(--radius-md); border-left: 4px solid var(--success);">
                <div style="font-weight: 600; margin-bottom: 4px;">📁 Dossier de destination</div>
                <div style="font-family: monospace; font-size: 14px; color: var(--text-secondary);"><?php echo htmlspecialchars($outputDir); ?></div>
            </div>
        </div>

        <a href="?run=1" class="btn btn-primary" style="font-size: 16px;">
            🚀 Lancer la génération
        </a>
    </div>

    <div class="card" style="background: linear-gradient(135deg, #fee 0%, #fdd 100%); border-left: 4px solid var(--warning);">
        <h3 style="color: var(--warning); margin-bottom: 12px; font-size: 18px;">⚠️ Attention</h3>
        <ul style="color: var(--text-secondary); margin-left: 20px; line-height: 1.8;">
            <li>Les pages existantes seront écrasées</li>
            <li>Le processus peut prendre quelques secondes selon le nombre de produits</li>
            <li>Assurez-vous que le fichier CSV est à jour</li>
        </ul>
    </div>

<?php else: ?>
    <!-- Résultats -->
    <div class="card">
        <h2 style="font-size: 20px; margin-bottom: 20px; color: var(--primary); font-weight: 700;">📊 Résultats de la génération</h2>

        <!-- Stats -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
            <div style="padding: 20px; background: linear-gradient(135deg, var(--success) 0%, #059669 100%); color: white; border-radius: var(--radius-md);">
                <div style="font-size: 14px; opacity: 0.9; margin-bottom: 4px;">Pages générées</div>
                <div style="font-size: 36px; font-weight: 700;"><?php echo $stats['generated']; ?></div>
            </div>

            <div style="padding: 20px; background: linear-gradient(135deg, var(--danger) 0%, #c0392b 100%); color: white; border-radius: var(--radius-md);">
                <div style="font-size: 14px; opacity: 0.9; margin-bottom: 4px;">Erreurs</div>
                <div style="font-size: 36px; font-weight: 700;"><?php echo $stats['errors']; ?></div>
            </div>

            <div style="padding: 20px; background: linear-gradient(135deg, var(--secondary) 0%, #1a1b2e 100%); color: white; border-radius: var(--radius-md);">
                <div style="font-size: 14px; opacity: 0.9; margin-bottom: 4px;">Ignorées</div>
                <div style="font-size: 36px; font-weight: 700;"><?php echo $stats['skipped']; ?></div>
            </div>
        </div>

        <!-- Logs -->
        <h3 style="font-size: 16px; margin-bottom: 12px; font-weight: 600;">📝 Détails</h3>
        <div style="background: var(--bg-primary); padding: 20px; border-radius: var(--radius-md); max-height: 500px; overflow-y: auto; font-family: monospace; font-size: 13px;">
            <?php foreach ($logs as $log): ?>
                <?php
                $colors = [
                    'success' => 'var(--success)',
                    'error' => 'var(--danger)',
                    'info' => 'var(--info)'
                ];
                $color = $colors[$log['type']] ?? 'var(--text-secondary)';
                ?>
                <div style="color: <?php echo $color; ?>; margin-bottom: 4px;">
                    <?php echo htmlspecialchars($log['message']); ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="margin-top: 24px; display: flex; gap: 12px;">
            <a href="?" class="btn btn-secondary">🔄 Regénérer</a>
            <a href="/admin/produits.php" class="btn btn-primary">✓ Retour aux produits</a>
        </div>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>

<?php
/**
 * Générer le HTML d'une page produit
 */
function genererPageProduitHTML($p) {
    $nom = htmlspecialchars($p['NOM_PRODUIT']);
    $soustitre = htmlspecialchars($p['SOUS_TITRE']);
    $descCourte = htmlspecialchars($p['DESCRIPTION_COURTE']);
    $descLongue = htmlspecialchars($p['DESCRIPTION_LONGUE']);
    $categorie = htmlspecialchars($p['CATEGORIE']);
    $id = htmlspecialchars($p['ID_PRODUIT']);

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

    $reduction = round((($prix010-$prix300plus)/$prix010)*100);

    return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$nom - $soustitre | Imprixo</title>
    <meta name="description" content="$descCourte ✓ Prix dégressifs {$prix300plus}€→{$prix010}€/m² ✓ Livraison {$delai}j ✓ $certification">
    <link rel="canonical" href="https://imprixo.fr/produit/$id.html">

    <meta property="og:title" content="$nom - Imprixo">
    <meta property="og:description" content="$descCourte">
    <meta property="og:type" content="product">

    <script type="application/ld+json">
    {
        "@context": "https://schema.org/",
        "@type": "Product",
        "name": "$nom",
        "description": "$descCourte",
        "brand": {"@type": "Brand", "name": "Imprixo"},
        "offers": {
            "@type": "AggregateOffer",
            "lowPrice": "$prix300plus",
            "highPrice": "$prix010",
            "priceCurrency": "EUR",
            "availability": "https://schema.org/InStock"
        }
    }
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
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
    <div id="header-placeholder"></div>
    <script>
    fetch('/includes/header.html').then(r => r.text()).then(html => document.getElementById('header-placeholder').innerHTML = html);
    </script>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <nav class="text-sm mb-6">
            <a href="/" class="text-gray-600 hover:text-red-600">Accueil</a>
            <span class="mx-2">›</span>
            <a href="/catalogue.html" class="text-gray-600 hover:text-red-600">Catalogue</a>
            <span class="mx-2">›</span>
            <span class="font-semibold">$nom</span>
        </nav>

        <div class="bg-white rounded-xl shadow-lg p-6 md:p-8 mb-6">
            <h1 class="text-4xl font-black mb-2">$nom</h1>
            <p class="text-xl text-gray-600 mb-4">$soustitre</p>
            <div class="flex flex-wrap gap-4">
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded-full font-semibold text-sm">✓ En stock - Livraison {$delai}j</div>
                <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full font-semibold text-sm">🔥 Prix dégressifs</div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <img src="/assets/products/$id.jpg" alt="$nom" class="w-full h-96 object-cover rounded-lg" onerror="this.src='https://placehold.co/800x600/e63946/white?text=' + encodeURIComponent('$nom')">
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                    <h2 class="text-2xl font-black mb-4">📝 Description</h2>
                    <p class="text-lg font-medium mb-4">$descCourte</p>
                    <p class="text-gray-600">$descLongue</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                    <h2 class="text-2xl font-black mb-6">⚙️ Caractéristiques</h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
                            <div class="text-sm text-blue-700">Poids</div>
                            <div class="text-lg font-black">$poids kg/m²</div>
                        </div>
                        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
                            <div class="text-sm text-blue-700">Épaisseur</div>
                            <div class="text-lg font-black">$epaisseur</div>
                        </div>
                        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
                            <div class="text-sm text-blue-700">Format max</div>
                            <div class="text-lg font-black">$formatMax cm</div>
                        </div>
                        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
                            <div class="text-sm text-blue-700">Usage</div>
                            <div class="text-lg font-black">$usage</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                    <h2 class="text-2xl font-black mb-6">💰 Prix dégressifs au m²</h2>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        <div class="border-2 rounded-lg p-4 text-center">
                            <div class="text-sm text-gray-600">0-10 m²</div>
                            <div class="text-2xl font-black text-red-600">{$prix010}€</div>
                        </div>
                        <div class="border-2 rounded-lg p-4 text-center">
                            <div class="text-sm text-gray-600">11-50 m²</div>
                            <div class="text-2xl font-black text-red-600">{$prix1150}€</div>
                        </div>
                        <div class="border-2 rounded-lg p-4 text-center">
                            <div class="text-sm text-gray-600">51-100 m²</div>
                            <div class="text-2xl font-black text-red-600">{$prix51100}€</div>
                        </div>
                        <div class="border-2 rounded-lg p-4 text-center">
                            <div class="text-sm text-gray-600">101-300 m²</div>
                            <div class="text-2xl font-black text-red-600">{$prix101300}€</div>
                        </div>
                        <div class="border-2 border-red-600 bg-red-50 rounded-lg p-4 text-center">
                            <div class="text-sm text-red-700 font-semibold">300+ m²</div>
                            <div class="text-2xl font-black text-red-600">{$prix300plus}€</div>
                            <div class="text-xs text-red-700 font-semibold">Meilleur prix !</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="sticky top-32 bg-white rounded-xl shadow-lg p-6">
                    <div class="text-sm text-gray-600">À partir de</div>
                    <div class="text-4xl font-black">{$prix300plus}€</div>
                    <div class="text-lg text-gray-600 mb-6">/m²</div>

                    <div class="bg-gradient-to-r from-red-50 to-orange-50 border-2 border-red-200 rounded-lg p-4 mb-6">
                        <div class="text-sm font-semibold text-red-900 mb-2">🔥 Économisez jusqu'à</div>
                        <div class="text-3xl font-black text-red-600">-{$reduction}%</div>
                    </div>

                    <button class="w-full btn-primary text-white py-4 rounded-lg font-black text-lg mb-3 shadow-lg">
                        🛒 CONFIGURER & COMMANDER
                    </button>

                    <div class="grid grid-cols-2 gap-3 mb-6 mt-6">
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-2xl mb-1">🚚</div>
                            <div class="text-xs font-bold">Livraison {$delai}j</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-2xl mb-1">🔒</div>
                            <div class="text-xs font-bold">Paiement sécurisé</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="footer-placeholder"></div>
    <script>
    fetch('/includes/footer.html').then(r => r.text()).then(html => document.getElementById('footer-placeholder').innerHTML = html);
    window.productData = {id: '$id', nom: '$nom', prix_min: $prix300plus, prix_max: $prix010};
    </script>
</body>
</html>
HTML;
}
