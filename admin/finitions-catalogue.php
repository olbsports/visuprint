<?php
/**
 * Gestion Catalogue Finitions - Imprixo Admin
 * Bibliothèque globale de toutes les finitions disponibles
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../api/config.php';

verifierAdminConnecte();
$admin = getAdminInfo();

$db = Database::getInstance();
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';

// Charger toutes les finitions du catalogue
$finitions = [];
try {
    $finitions = $db->fetchAll("SELECT * FROM finitions_catalogue ORDER BY categorie, ordre, nom");
} catch (Exception $e) {
    $error = "Erreur chargement : " . $e->getMessage();
}

// Grouper par catégorie
$finitionsParCategorie = [];
foreach ($finitions as $fin) {
    $cat = $fin['categorie'] ?? 'Autre';
    if (!isset($finitionsParCategorie[$cat])) {
        $finitionsParCategorie[$cat] = [];
    }
    $finitionsParCategorie[$cat][] = $fin;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue Finitions - Imprixo Admin</title>
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

        .nav a:hover, .nav a.active {
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

        .btn-success {
            background: #27ae60;
            color: white;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .btn-small {
            padding: 6px 12px;
            font-size: 12px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .info-box {
            background: #d1ecf1;
            border-left: 4px solid #0c5460;
            color: #0c5460;
            padding: 15px 20px;
            border-radius: 8px;
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

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
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

        .category-header {
            background: #667eea !important;
            color: white !important;
            font-weight: 600;
            font-size: 16px;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
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

        .actions-cell {
            display: flex;
            gap: 8px;
        }

        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
        }

        .icon {
            font-size: 20px;
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
            <li><a href="produits.php">🏷️ Produits</a></li>
            <li><a href="finitions-catalogue.php" class="active">🎨 Finitions</a></li>
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
            <h1 class="page-title">🎨 Catalogue des Finitions</h1>
            <a href="finition-ajouter.php" class="btn btn-primary">➕ Créer une finition</a>
        </div>

        <div class="info-box">
            <strong>ℹ️ Comment ça marche ?</strong><br>
            Ce catalogue contient TOUTES les finitions disponibles. Quand tu édites un produit, tu choisis librement lesquelles activer.
            Tu peux créer tes propres finitions personnalisées avec prix, conditions, etc.
        </div>

        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>Icône</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Prix défaut</th>
                        <th>Type prix</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($finitionsParCategorie as $categorie => $catFinitions): ?>
                        <tr class="category-header">
                            <td colspan="7"><?php echo htmlspecialchars($categorie); ?> (<?php echo count($catFinitions); ?> finitions)</td>
                        </tr>
                        <?php foreach ($catFinitions as $fin): ?>
                            <tr>
                                <td class="icon"><?php echo htmlspecialchars($fin['icone'] ?? ''); ?></td>
                                <td><strong><?php echo htmlspecialchars($fin['nom']); ?></strong></td>
                                <td><?php echo htmlspecialchars($fin['description'] ?? ''); ?></td>
                                <td>
                                    <?php if ($fin['prix_defaut'] > 0): ?>
                                        <strong style="color: #667eea;">+<?php echo number_format($fin['prix_defaut'], 2); ?> €</strong>
                                    <?php elseif ($fin['prix_defaut'] < 0): ?>
                                        <strong style="color: #27ae60;"><?php echo number_format($fin['prix_defaut'], 2); ?> €</strong>
                                    <?php else: ?>
                                        Inclus
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $types = [
                                        'fixe' => 'Fixe',
                                        'par_m2' => 'Par m²',
                                        'par_ml' => 'Par ml',
                                        'pourcentage' => '%'
                                    ];
                                    echo $types[$fin['type_prix_defaut']] ?? $fin['type_prix_defaut'];
                                    ?>
                                </td>
                                <td>
                                    <?php if ($fin['actif']): ?>
                                        <span class="badge badge-success">Actif</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Inactif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="actions-cell">
                                        <a href="finition-editer.php?id=<?php echo $fin['id']; ?>" class="btn btn-primary btn-small">✏️ Éditer</a>
                                        <a href="finition-supprimer.php?id=<?php echo $fin['id']; ?>" class="btn btn-danger btn-small" onclick="return confirm('Supprimer cette finition ?')">🗑️</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>

                    <?php if (empty($finitions)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #999;">
                                Aucune finition dans le catalogue. Créez-en une !
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="card" style="background: #f8f9fa;">
            <h3 style="margin-bottom: 15px;">💡 Conseils d'utilisation</h3>
            <ul style="line-height: 1.8; margin-left: 20px;">
                <li>Créez ici toutes les finitions possibles (même celles que tu n'utilises pas encore)</li>
                <li>Quand tu édites un produit, tu coches juste celles que tu veux activer</li>
                <li>Tu peux surcharger le prix par défaut pour chaque produit</li>
                <li>Les finitions "Tous" apparaissent sur tous les produits (ex: Livraison express)</li>
                <li>Les finitions catégorisées n'apparaissent que pour les produits de cette catégorie</li>
            </ul>
        </div>
    </div>
</body>
</html>
