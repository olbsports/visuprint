<?php
/**
 * Script d'import CSV vers base de données
 * À exécuter UNE SEULE FOIS pour importer les produits
 */

require_once __DIR__ . '/../api/config.php';

$db = Database::getInstance();
$csvFile = __DIR__ . '/../CATALOGUE_COMPLET_VISUPRINT.csv';

if (!file_exists($csvFile)) {
    die("❌ Fichier CSV introuvable : $csvFile\n");
}

echo "🔄 Import des produits depuis CSV vers base de données...\n\n";

$file = fopen($csvFile, 'r');
$headers = fgetcsv($file);

$imported = 0;
$errors = 0;

while (($row = fgetcsv($file)) !== false) {
    if (count($row) !== count($headers)) continue;
    
    $produit = array_combine($headers, $row);
    
    if (empty($produit['ID_PRODUIT'])) continue;
    
    try {
        // Vérifier si le produit existe déjà
        $existing = $db->fetchOne(
            "SELECT id FROM produits WHERE id_produit = ?",
            [$produit['ID_PRODUIT']]
        );
        
        // Générer slug
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $produit['NOM_PRODUIT']));
        
        // Générer image URL selon catégorie
        $imageUrl = '';
        if (stripos($produit['CATEGORIE'], 'PVC') !== false || stripos($produit['CATEGORIE'], 'Forex') !== false) {
            $imageUrl = 'https://images.unsplash.com/photo-1626785774573-4b799315345d?w=600&h=400&fit=crop';
        } elseif (stripos($produit['CATEGORIE'], 'Aluminium') !== false || stripos($produit['CATEGORIE'], 'Dibond') !== false) {
            $imageUrl = 'https://images.unsplash.com/photo-1603912699214-92627f304eb6?w=600&h=400&fit=crop';
        } elseif (stripos($produit['CATEGORIE'], 'Bache') !== false || stripos($produit['CATEGORIE'], 'Bâche') !== false) {
            $imageUrl = 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&h=400&fit=crop';
        } elseif (stripos($produit['CATEGORIE'], 'Textile') !== false) {
            $imageUrl = 'https://images.unsplash.com/photo-1558769132-cb1aea9ccff1?w=600&h=400&fit=crop';
        }
        
        $sql = "";
        $params = [];
        
        if ($existing) {
            // UPDATE
            $sql = "UPDATE produits SET
                categorie = ?,
                nom_produit = ?,
                sous_titre = ?,
                description_courte = ?,
                description_longue = ?,
                poids_m2 = ?,
                epaisseur = ?,
                format_max_cm = ?,
                `usage` = ?,
                duree_vie = ?,
                certification = ?,
                finition_defaut = ?,
                impression_faces = ?,
                prix_0_10 = ?,
                prix_11_50 = ?,
                prix_51_100 = ?,
                prix_101_300 = ?,
                prix_300_plus = ?,
                commande_min_euro = ?,
                delai_standard_jours = ?,
                unite_vente = ?,
                image_url = ?,
                slug = ?,
                updated_at = NOW()
                WHERE id_produit = ?";
            
            $params = [
                $produit['CATEGORIE'],
                $produit['NOM_PRODUIT'],
                $produit['SOUS_TITRE'] ?? '',
                $produit['DESCRIPTION_COURTE'] ?? '',
                $produit['DESCRIPTION_LONGUE'] ?? '',
                !empty($produit['POIDS_M2']) ? floatval($produit['POIDS_M2']) : null,
                $produit['EPAISSEUR'] ?? '',
                $produit['FORMAT_MAX_CM'] ?? '',
                $produit['USAGE'] ?? '',
                $produit['DUREE_VIE'] ?? '',
                $produit['CERTIFICATION'] ?? '',
                $produit['FINITION'] ?? '',
                $produit['IMPRESSION_FACES'] ?? '',
                floatval($produit['PRIX_0_10_M2']),
                floatval($produit['PRIX_11_50_M2']),
                floatval($produit['PRIX_51_100_M2']),
                floatval($produit['PRIX_101_300_M2']),
                floatval($produit['PRIX_300_PLUS_M2']),
                !empty($produit['COMMANDE_MIN_EURO']) ? floatval($produit['COMMANDE_MIN_EURO']) : null,
                intval($produit['DELAI_STANDARD_JOURS']),
                $produit['UNITE_VENTE'] ?? 'm²',
                $imageUrl,
                $slug,
                $produit['ID_PRODUIT']
            ];
        } else {
            // INSERT
            $sql = "INSERT INTO produits (
                id_produit, categorie, nom_produit, sous_titre, description_courte, description_longue,
                poids_m2, epaisseur, format_max_cm, `usage`, duree_vie, certification,
                finition_defaut, impression_faces,
                prix_0_10, prix_11_50, prix_51_100, prix_101_300, prix_300_plus,
                commande_min_euro, delai_standard_jours, unite_vente, image_url, slug
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $params = [
                $produit['ID_PRODUIT'],
                $produit['CATEGORIE'],
                $produit['NOM_PRODUIT'],
                $produit['SOUS_TITRE'] ?? '',
                $produit['DESCRIPTION_COURTE'] ?? '',
                $produit['DESCRIPTION_LONGUE'] ?? '',
                !empty($produit['POIDS_M2']) ? floatval($produit['POIDS_M2']) : null,
                $produit['EPAISSEUR'] ?? '',
                $produit['FORMAT_MAX_CM'] ?? '',
                $produit['USAGE'] ?? '',
                $produit['DUREE_VIE'] ?? '',
                $produit['CERTIFICATION'] ?? '',
                $produit['FINITION'] ?? '',
                $produit['IMPRESSION_FACES'] ?? '',
                floatval($produit['PRIX_0_10_M2']),
                floatval($produit['PRIX_11_50_M2']),
                floatval($produit['PRIX_51_100_M2']),
                floatval($produit['PRIX_101_300_M2']),
                floatval($produit['PRIX_300_PLUS_M2']),
                !empty($produit['COMMANDE_MIN_EURO']) ? floatval($produit['COMMANDE_MIN_EURO']) : null,
                intval($produit['DELAI_STANDARD_JOURS']),
                $produit['UNITE_VENTE'] ?? 'm²',
                $imageUrl,
                $slug
            ];
        }
        
        $db->query($sql, $params);
        
        // Ajouter finitions par défaut selon catégorie
        if (!$existing) {
            $produitId = $db->lastInsertId();
            $finitions = [];
            
            if (stripos($produit['CATEGORIE'], 'PVC') !== false) {
                $finitions = [
                    ['Standard', 'Inclus', 0, 'fixe'],
                    ['Contrecollage', '+8€/m²', 8, 'par_m2'],
                    ['Découpe forme', '+15€', 15, 'fixe']
                ];
            } elseif (stripos($produit['CATEGORIE'], 'Aluminium') !== false) {
                $finitions = [
                    ['Standard', 'Inclus', 0, 'fixe'],
                    ['Perçage angles', '+8€', 8, 'fixe'],
                    ['Cadre aluminium', '+25€', 25, 'fixe']
                ];
            } elseif (stripos($produit['CATEGORIE'], 'Bache') !== false || stripos($produit['CATEGORIE'], 'Bâche') !== false) {
                $finitions = [
                    ['Standard', 'Inclus', 0, 'fixe'],
                    ['Œillets (tous les 50cm)', '+12€', 12, 'fixe'],
                    ['Ourlet soudé', '+8€/ml', 8, 'par_ml']
                ];
            } elseif (stripos($produit['CATEGORIE'], 'Textile') !== false) {
                $finitions = [
                    ['Standard', 'Inclus', 0, 'fixe'],
                    ['Baguettes haut/bas', '+18€', 18, 'fixe'],
                    ['Confection suspendue', '+25€', 25, 'fixe']
                ];
            }
            
            foreach ($finitions as $index => $fin) {
                $db->query(
                    "INSERT INTO produits_finitions (produit_id, nom, description, prix_supplement, type_prix, ordre)
                     VALUES (?, ?, ?, ?, ?, ?)",
                    [$produitId, $fin[0], $fin[1], $fin[2], $fin[3], $index]
                );
            }
        }
        
        $imported++;
        echo "✓ {$produit['ID_PRODUIT']} - {$produit['NOM_PRODUIT']}\n";
        
    } catch (Exception $e) {
        $errors++;
        echo "❌ {$produit['ID_PRODUIT']} - Erreur: " . $e->getMessage() . "\n";
    }
}

fclose($file);

echo "\n=== RÉSUMÉ ===\n";
echo "✓ Produits importés: $imported\n";
echo "❌ Erreurs: $errors\n";
