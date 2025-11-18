<?php
/**
 * Gestion Produits - Imprixo Admin
 * Affichage et gestion des produits depuis CSV
 */

require_once __DIR__ . '/auth.php';

verifierAdminConnecte();
$admin = getAdminInfo();

$success = '';
$error = '';

// Charger les produits depuis CSV
$produits = [];
$csvFile = __DIR__ . '/../CATALOGUE_COMPLET_VISUPRINT.csv';

if (file_exists($csvFile)) {
    $file = fopen($csvFile, 'r');
    $headers = fgetcsv($file); // Lire l'en-tête

    while (($row = fgetcsv($file)) !== false) {
        if (count($row) === count($headers)) {
            $produit = array_combine($headers, $row);
            if (!empty($produit['ID_PRODUIT'])) {
                $produits[] = $produit;
            }
        }
    }
    fclose($file);
}

// Stats
$stats = [
    'total' => count($produits),
    'categories' => count(array_unique(array_column($produits, 'CATEGORIE')))
];

// Grouper par catégorie
$categories = [];
foreach ($produits as $produit) {
    $cat = $produit['CATEGORIE'];
    if (!isset($categories[$cat])) {
        $categories[$cat] = [];
    }
    $categories[$cat][] = $produit;
}
ksort($categories);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Produits - Imprixo Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f5f7fa;
            color: #2c3e50;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .nav {
            background: white;
            padding: 0 40px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .nav ul {
            list-style: none;
            display: flex;
            gap: 30px;
        }

        .nav a {
            display: block;
            padding: 15px 0;
            color: #666;
            text-decoration: none;
            font-weight: 500;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }

        .nav a:hover,
        .nav a.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 32px;
            font-weight: 900;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .stat-label {
            font-size: 13px;
            color: #666;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #667eea;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table thead {
            background: #f8f9fa;
        }

        .table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #666;
            font-size: 13px;
            border-bottom: 2px solid #e0e0e0;
        }

        .table td {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        .category-row {
            background: #667eea !important;
            color: white !important;
            font-weight: 600;
        }

        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            color: #667eea;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-logout {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 8px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-logout:hover {
            background: rgba(255,255,255,0.3);
        }

        .alert-info {
            background: #d1ecf1;
            border-left: 4px solid #0c5460;
            color: #0c5460;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .btn-small {
            padding: 6px 12px;
            font-size: 12px;
        }

        .btn-success {
            background: #27ae60;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎨 Imprixo - Administration</h1>
        <div class="user-info">
            👤 <?php echo htmlspecialchars($admin['prenom'] ?? $admin['username']); ?>
            <a href="logout.php" class="btn-logout">Déconnexion</a>
        </div>
    </div>

    <nav class="nav">
        <ul>
            <li><a href="index.php">📊 Dashboard</a></li>
            <li><a href="commandes.php">📦 Commandes</a></li>
            <li><a href="produits.php" class="active">🏷️ Produits</a></li>
            <li><a href="clients.php">👥 Clients</a></li>
            <li><a href="parametres.php">⚙️ Paramètres</a></li>
        </ul>
    </nav>

    <div class="container">
        <?php if ($success): ?>
            <div class="success-message">✓ <?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <div class="page-header">
            <h1 class="page-title">🏷️ Gestion des produits</h1>
            <a href="javascript:regenererPages()" class="btn btn-success">🔄 Régénérer les pages produits</a>
        </div>

        <div class="alert-info">
            ℹ️ <strong>Information:</strong> Les produits sont stockés dans le fichier CSV <code>CATALOGUE_COMPLET_VISUPRINT.csv</code>.
            Pour modifier un produit, éditez le CSV puis régénérez les pages avec le bouton ci-dessus.
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total produits</div>
                <div class="stat-value"><?php echo $stats['total']; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Catégories</div>
                <div class="stat-value" style="color: #764ba2;"><?php echo $stats['categories']; ?></div>
            </div>
        </div>

        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Produit</th>
                        <th>Prix 300+ m²</th>
                        <th>Délai</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat_nom => $cat_produits): ?>
                        <tr class="category-row">
                            <td colspan="5"><?php echo htmlspecialchars($cat_nom); ?> (<?php echo count($cat_produits); ?> produits)</td>
                        </tr>
                        <?php foreach ($cat_produits as $p): ?>
                        <tr>
                            <td><code><?php echo htmlspecialchars($p['ID_PRODUIT']); ?></code></td>
                            <td>
                                <strong><?php echo htmlspecialchars($p['NOM_PRODUIT']); ?></strong>
                                <?php if (!empty($p['SOUS_TITRE'])): ?>
                                    <br><small style="color: #666;"><?php echo htmlspecialchars($p['SOUS_TITRE']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong style="color: #667eea;">
                                    <?php echo number_format((float)$p['PRIX_300_PLUS_M2'], 2, ',', ' '); ?> €/m²
                                </strong>
                            </td>
                            <td><?php echo htmlspecialchars($p['DELAI_STANDARD_JOURS']); ?> jours</td>
                            <td>
                                <a href="/produit/<?php echo $p['ID_PRODUIT']; ?>.php" target="_blank" class="btn btn-primary btn-small">
                                    👁️ Voir la page
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function regenererPages() {
            if (confirm('Voulez-vous régénérer toutes les pages produits ?\n\nCela va recréer les 54 pages PHP à partir du fichier CSV.')) {
                alert('Pour régénérer les pages produits, exécutez cette commande sur le serveur:\n\npython3 generer_pages_produits_v2.py\n\nOu utilisez le terminal Claude Code.');
            }
        }
    </script>
</body>
</html>
