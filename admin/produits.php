<?php
/**
 * Gestion Produits - Imprixo Admin
 * Affichage et gestion des produits depuis base de données
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../api/config.php';

verifierAdminConnecte();
$admin = getAdminInfo();

$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';

// Charger les produits depuis la base de données
$db = Database::getInstance();
$produits = [];

try {
    // Construire la requête avec filtres
    $where = ['actif = 1'];
    $params = [];

    $recherche = isset($_GET['q']) ? trim($_GET['q']) : '';
    $filtreCategorie = isset($_GET['cat']) ? $_GET['cat'] : '';

    if ($recherche) {
        $where[] = "(id_produit LIKE ? OR nom_produit LIKE ? OR sous_titre LIKE ? OR description_courte LIKE ?)";
        $search = '%' . $recherche . '%';
        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
    }

    if ($filtreCategorie) {
        $where[] = "categorie = ?";
        $params[] = $filtreCategorie;
    }

    $whereClause = 'WHERE ' . implode(' AND ', $where);

    // Récupérer produits avec promotions actives
    $produits = $db->fetchAll(
        "SELECT * FROM v_produits_avec_promos $whereClause ORDER BY categorie, nom_produit",
        $params
    );

    // Convertir en format compatible avec l'affichage (majuscules pour compatibilité)
    foreach ($produits as &$p) {
        $p['ID_PRODUIT'] = $p['id_produit'];
        $p['CATEGORIE'] = $p['categorie'];
        $p['NOM_PRODUIT'] = $p['nom_produit'];
        $p['SOUS_TITRE'] = $p['sous_titre'];
        $p['PRIX_300_PLUS_M2'] = $p['prix_300_plus'];
        $p['DELAI_STANDARD_JOURS'] = $p['delai_standard_jours'];
    }

    // Liste de toutes les catégories pour le filtre
    $listeCategoriesUniques = $db->fetchAll(
        "SELECT DISTINCT categorie FROM produits WHERE actif = 1 ORDER BY categorie"
    );
    $listeCategoriesUniques = array_column($listeCategoriesUniques, 'categorie');

} catch (Exception $e) {
    $error = "Erreur chargement produits: " . $e->getMessage();
    $produits = [];
    $listeCategoriesUniques = [];
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

        .btn-success:hover {
            background: #229954;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(39, 174, 96, 0.4);
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        .search-box {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .search-input {
            width: 100%;
            max-width: 400px;
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
        }

        .filter-select {
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
        }

        .filter-select:focus {
            outline: none;
            border-color: #667eea;
        }

        .actions-cell {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }

        .header-actions {
            display: flex;
            gap: 10px;
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

        <?php if ($error): ?>
            <div class="error-message">✗ <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="page-header">
            <h1 class="page-title">🏷️ Gestion des produits</h1>
            <div class="header-actions">
                <a href="nouveau-produit.php" class="btn btn-primary">➕ Ajouter un produit</a>
                <a href="generer-pages-produits-html.php" class="btn btn-success" onclick="return confirm('Régénérer toutes les pages HTML des produits ?')">🔄 Régénérer toutes les pages</a>
            </div>
        </div>

        <!-- Recherche et filtres -->
        <div class="search-box">
            <form method="GET" action="" style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                <input
                    type="text"
                    name="q"
                    class="search-input"
                    placeholder="🔍 Rechercher un produit (ID, nom, description...)"
                    value="<?php echo htmlspecialchars($recherche); ?>"
                >
                <select name="cat" class="filter-select" onchange="this.form.submit()">
                    <option value="">📁 Toutes les catégories</option>
                    <?php foreach ($listeCategoriesUniques as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $filtreCategorie === $cat ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary btn-small">Rechercher</button>
                <?php if ($recherche || $filtreCategorie): ?>
                    <a href="produits.php" class="btn btn-secondary btn-small">✖ Effacer les filtres</a>
                <?php endif; ?>
            </form>
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
                                <div class="actions-cell">
                                    <a href="/produit/<?php echo $p['ID_PRODUIT']; ?>.html" target="_blank" class="btn btn-secondary btn-small" title="Voir la page HTML">
                                        👁️
                                    </a>
                                    <a href="editer-produit.php?id=<?php echo urlencode($p['ID_PRODUIT']); ?>" class="btn btn-primary btn-small" title="Éditer">
                                        ✏️
                                    </a>
                                    <a href="supprimer-produit.php?id=<?php echo urlencode($p['ID_PRODUIT']); ?>" class="btn btn-danger btn-small" onclick="return confirm('Supprimer le produit <?php echo htmlspecialchars($p['NOM_PRODUIT']); ?> ?')" title="Supprimer">
                                        🗑️
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
