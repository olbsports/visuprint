<?php
/**
 * Script CLI pour régénérer toutes les pages produits
 */

require_once __DIR__ . '/admin/helpers/generer-page-produit.php';

$csvFile = __DIR__ . '/CATALOGUE_COMPLET_VISUPRINT.csv';
$outputDir = __DIR__ . '/produit/';

echo "🚀 Régénération des pages produits\n";
echo "=====================================\n\n";

if (!file_exists($csvFile)) {
    die("❌ Fichier CSV introuvable: $csvFile\n");
}

// Créer le dossier si nécessaire
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

$stats = ['generated' => 0, 'errors' => 0, 'skipped' => 0];

$file = fopen($csvFile, 'r');
$headers = fgetcsv($file);

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
        echo "✓ {$produit['NOM_PRODUIT']} → $fileId.html\n";
        $stats['generated']++;
    } else {
        echo "✗ Erreur: {$produit['NOM_PRODUIT']}\n";
        $stats['errors']++;
    }
}

fclose($file);

echo "\n=====================================\n";
echo "📊 RÉSULTATS\n";
echo "=====================================\n";
echo "✅ Pages générées : {$stats['generated']}\n";
echo "❌ Erreurs : {$stats['errors']}\n";
echo "⏭️  Ignorées : {$stats['skipped']}\n";
echo "\n✓ Régénération terminée !\n";
