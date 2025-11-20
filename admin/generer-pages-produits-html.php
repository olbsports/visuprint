<?php
/**
 * Générateur de Pages Produits HTML Statiques - Imprixo Admin
 * Génère des pages HTML optimisées SEO pour chaque produit
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/helpers/generer-page-produit.php';
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