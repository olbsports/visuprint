<?php
/**
 * Script pour initialiser les formats par défaut pour tous les produits
 */

require_once __DIR__ . '/api/config.php';

echo "🚀 Initialisation des formats par défaut\n";
echo "======================================\n\n";

try {
    $db = Database::getInstance();

    // Formats par défaut
    $formatsDefaut = [
        ['nom' => 'A0 (84×119 cm)', 'largeur' => 84, 'hauteur' => 119, 'ordre' => 0],
        ['nom' => 'A1 (59×84 cm)', 'largeur' => 59, 'hauteur' => 84, 'ordre' => 1],
        ['nom' => 'A2 (42×59 cm)', 'largeur' => 42, 'hauteur' => 59, 'ordre' => 2],
        ['nom' => 'A3 (30×42 cm)', 'largeur' => 30, 'hauteur' => 42, 'ordre' => 3],
        ['nom' => '100×100 cm', 'largeur' => 100, 'hauteur' => 100, 'ordre' => 4],
        ['nom' => '200×100 cm', 'largeur' => 200, 'hauteur' => 100, 'ordre' => 5],
        ['nom' => 'Roll-up 85×200 cm', 'largeur' => 85, 'hauteur' => 200, 'ordre' => 6],
        ['nom' => '300×200 cm', 'largeur' => 300, 'hauteur' => 200, 'ordre' => 7],
        ['nom' => 'Personnalisé', 'largeur' => 100, 'hauteur' => 100, 'ordre' => 8]
    ];

    // Charger tous les produits
    $produits = $db->fetchAll("SELECT id, code, nom FROM produits");

    $stats = ['produits' => 0, 'formats' => 0, 'skipped' => 0];

    foreach ($produits as $produit) {
        // Vérifier si le produit a déjà des formats
        $existants = $db->fetchAll(
            "SELECT COUNT(*) as count FROM produits_formats WHERE produit_id = ?",
            [$produit['id']]
        );

        if ($existants[0]['count'] > 0) {
            echo "⏭️  {$produit['nom']} - formats déjà définis\n";
            $stats['skipped']++;
            continue;
        }

        // Ajouter les formats par défaut
        foreach ($formatsDefaut as $format) {
            $db->query(
                "INSERT INTO produits_formats (produit_id, nom, largeur_cm, hauteur_cm, actif, ordre)
                 VALUES (?, ?, ?, ?, 1, ?)",
                [
                    $produit['id'],
                    $format['nom'],
                    $format['largeur'],
                    $format['hauteur'],
                    $format['ordre']
                ]
            );
            $stats['formats']++;
        }

        echo "✓ {$produit['nom']} - 9 formats ajoutés\n";
        $stats['produits']++;
    }

    echo "\n======================================\n";
    echo "📊 RÉSULTATS\n";
    echo "======================================\n";
    echo "✅ Produits initialisés : {$stats['produits']}\n";
    echo "📏 Formats créés : {$stats['formats']}\n";
    echo "⏭️  Produits ignorés : {$stats['skipped']}\n";
    echo "\n✓ Initialisation terminée !\n";

} catch (Exception $e) {
    die("❌ Erreur fatale : " . $e->getMessage() . "\n");
}
