<?php
/**
 * Script de conversion automatique:
 * Convertit toutes les pages HTML en PHP avec includes header/footer
 */

$baseDir = __DIR__;
$pagesConverted = 0;
$errors = [];

// Trouver toutes les pages HTML
$htmlFiles = array_merge(
    glob($baseDir . '/*.html'),
    glob($baseDir . '/categorie/*.html'),
    glob($baseDir . '/compte/*.html'),
    glob($baseDir . '/application/*.html'),
    glob($baseDir . '/guides/*.html')
);

foreach ($htmlFiles as $htmlFile) {
    try {
        $content = file_get_contents($htmlFile);
        if ($content === false) continue;
        
        // Extraire le title et meta description
        preg_match('/<title>(.*?)<\/title>/si', $content, $titleMatch);
        preg_match('/<meta\s+name="description"\s+content="([^"]*)"/si', $content, $descMatch);
        
        $pageTitle = $titleMatch[1] ?? 'Imprixo';
        $pageDesc = $descMatch[1] ?? '';
        
        // Extraire uniquement le contenu du body
        preg_match('/<body[^>]*>(.*?)<\/body>/si', $content, $bodyMatch);
        $bodyContent = $bodyMatch[1] ?? $content;
        
        // Nettoyer le contenu (supprimer header/footer si présents)
        $bodyContent = preg_replace('/<header.*?<\/header>/si', '', $bodyContent);
        $bodyContent = preg_replace('/<footer.*?<\/footer>/si', '', $bodyContent);
        $bodyContent = preg_replace('/<div class="topbar".*?<\/div>/si', '', $bodyContent);
        $bodyContent = preg_replace('/<nav.*?<\/nav>/si', '', $bodyContent);
        
        // Créer le nouveau fichier PHP
        $phpFile = str_replace('.html', '.php', $htmlFile);
        
        $newContent = "<?php\n";
        $newContent .= "\$pageTitle = " . var_export($pageTitle, true) . ";\n";
        $newContent .= "\$pageDescription = " . var_export($pageDesc, true) . ";\n";
        $newContent .= "include __DIR__ . '/includes/header.php';\n";
        $newContent .= "?>\n\n";
        $newContent .= trim($bodyContent);
        $newContent .= "\n\n<?php include __DIR__ . '/includes/footer.php'; ?>\n";
        
        file_put_contents($phpFile, $newContent);
        
        // Supprimer l'ancien HTML
        unlink($htmlFile);
        
        $pagesConverted++;
        echo "✓ Converti: " . basename($htmlFile) . " → " . basename($phpFile) . "\n";
        
    } catch (Exception $e) {
        $errors[] = basename($htmlFile) . ": " . $e->getMessage();
    }
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ Pages converties: $pagesConverted\n";
if (!empty($errors)) {
    echo "❌ Erreurs: " . count($errors) . "\n";
    foreach ($errors as $error) {
        echo "  - $error\n";
    }
}
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
