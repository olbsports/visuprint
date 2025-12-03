<?php
/**
 * Script pour régénérer toutes les pages produits DEPUIS LA BASE DE DONNÉES
 * (au lieu du CSV)
 */

require_once __DIR__ . '/admin/helpers/generer-page-produit.php';
require_once __DIR__ . '/api/config.php';

echo "🚀 Régénération des pages produits depuis la BDD\n";
echo "=============================================\n\n";

try {
    $db = Database::getInstance();

    // Charger tous les produits depuis la BDD
    $produits = $db->fetchAll("SELECT * FROM produits WHERE actif = 1 ORDER BY nom");

    if (empty($produits)) {
        die("❌ Aucun produit trouvé dans la base de données\n");
    }

    $stats = ['generated' => 0, 'errors' => 0];

    foreach ($produits as $produit) {
        try {
            $success = regenererPageProduitDepuisBDD($produit['code']);

            if ($success) {
                echo "✓ {$produit['nom']} → {$produit['code']}.html\n";
                $stats['generated']++;
            } else {
                echo "✗ Erreur: {$produit['nom']}\n";
                $stats['errors']++;
            }
        } catch (Exception $e) {
            echo "✗ Erreur {$produit['nom']}: {$e->getMessage()}\n";
            $stats['errors']++;
        }
    }

    echo "\n=============================================\n";
    echo "📊 RÉSULTATS\n";
    echo "=============================================\n";
    echo "✅ Pages générées : {$stats['generated']}\n";
    echo "❌ Erreurs : {$stats['errors']}\n";
    echo "\n✓ Régénération terminée !\n";

} catch (Exception $e) {
    die("❌ Erreur fatale : " . $e->getMessage() . "\n");
}
