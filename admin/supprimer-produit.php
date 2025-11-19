<?php
/**
 * Supprimer un produit du catalogue
 */

require_once __DIR__ . '/auth.php';

verifierAdminConnecte();

$csvFile = __DIR__ . '/../CATALOGUE_COMPLET_VISUPRINT.csv';
$produitDir = __DIR__ . '/../produit/';

// Récupérer l'ID du produit
$idProduit = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($idProduit)) {
    header('Location: produits.php?error=' . urlencode('ID produit manquant'));
    exit;
}

// Vérifier que le produit existe
$produitExiste = false;
$nomProduit = '';

if (file_exists($csvFile)) {
    $file = fopen($csvFile, 'r');
    $headers = fgetcsv($file);

    while (($row = fgetcsv($file)) !== false) {
        if (count($row) === count($headers) && $row[0] === $idProduit) {
            $produitExiste = true;
            $produit = array_combine($headers, $row);
            $nomProduit = $produit['NOM_PRODUIT'];
            break;
        }
    }
    fclose($file);
}

if (!$produitExiste) {
    header('Location: produits.php?error=' . urlencode('Produit non trouvé'));
    exit;
}

// Supprimer le produit du CSV
$allProducts = [];
$file = fopen($csvFile, 'r');
$headers = fgetcsv($file);
$allProducts[] = $headers;

while (($row = fgetcsv($file)) !== false) {
    // Ne pas ajouter le produit à supprimer
    if ($row[0] !== $idProduit) {
        $allProducts[] = $row;
    }
}
fclose($file);

// Écrire le nouveau CSV
$file = fopen($csvFile, 'w');
foreach ($allProducts as $row) {
    fputcsv($file, $row);
}
fclose($file);

// Supprimer le fichier HTML
$fileId = preg_replace('/[^A-Za-z0-9\-_]/', '', $idProduit);
$htmlFile = $produitDir . $fileId . '.html';

if (file_exists($htmlFile)) {
    unlink($htmlFile);
}

// Redirection avec message de succès
header('Location: produits.php?success=' . urlencode('Produit "' . $nomProduit . '" supprimé avec succès !'));
exit;
