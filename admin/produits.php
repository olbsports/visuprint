<?php
/**
 * Gestion Produits CRUD Complet - Imprixo Admin
 */

require_once __DIR__ . '/auth.php';

verifierAdminConnecte();
$admin = getAdminInfo();
$db = Database::getInstance();

$success = '';
$error = '';
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'list';
$produit_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// ============================================
// TRAITEMENT DES ACTIONS
// ============================================

// Supprimer un produit
if (isset($_POST['action']) && $_POST['action'] === 'delete_produit' && isset($_POST['produit_id'])) {
    $id = (int)$_POST['produit_id'];
    $db->query("DELETE FROM produits WHERE id = ?", [$id]);
    $success = 'Produit supprimé avec succès';
    logAdminAction($admin['id'], 'delete_produit', "Suppression produit ID: $id");
}

// Créer ou modifier un produit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_produit') {
    $data = [
        'code' => strtoupper(trim($_POST['code'])),
        'nom' => trim($_POST['nom']),
        'categorie' => trim($_POST['categorie']),
        'sous_titre' => trim($_POST['sous_titre'] ?? ''),
        'description_courte' => trim($_POST['description_courte'] ?? ''),
        'description_longue' => trim($_POST['description_longue'] ?? ''),
        'poids_m2' => (float)($_POST['poids_m2'] ?? 0),
        'epaisseur' => trim($_POST['epaisseur'] ?? ''),
        'format_max_cm' => trim($_POST['format_max_cm'] ?? ''),
        'usage' => trim($_POST['usage'] ?? ''),
        'duree_vie' => trim($_POST['duree_vie'] ?? ''),
        'certification' => trim($_POST['certification'] ?? ''),
        'finition' => trim($_POST['finition'] ?? ''),
        'impression_faces' => trim($_POST['impression_faces'] ?? 'Simple'),
        'prix_0_10' => (float)$_POST['prix_0_10'],
        'prix_11_50' => (float)$_POST['prix_11_50'],
        'prix_51_100' => (float)$_POST['prix_51_100'],
        'prix_101_300' => (float)$_POST['prix_101_300'],
        'prix_300_plus' => (float)$_POST['prix_300_plus'],
        'prix_simple_face' => (float)($_POST['prix_simple_face'] ?? $_POST['prix_300_plus']),
        'prix_double_face' => (float)($_POST['prix_double_face'] ?? $_POST['prix_300_plus'] * 1.5),
        'commande_min_euro' => (float)($_POST['commande_min_euro'] ?? 25),
        'delai_standard_jours' => (int)($_POST['delai_standard_jours'] ?? 3),
        'stock_disponible' => isset($_POST['stock_disponible']) ? 1 : 0,
        'unite_vente' => trim($_POST['unite_vente'] ?? 'm²')
    ];

    if ($produit_id > 0) {
        // Modification
        $sql = "UPDATE produits SET
                code = ?, nom = ?, categorie = ?, sous_titre = ?, description_courte = ?,
                description_longue = ?, poids_m2 = ?, epaisseur = ?, format_max_cm = ?,
                usage = ?, duree_vie = ?, certification = ?, finition = ?, impression_faces = ?,
                prix_0_10 = ?, prix_11_50 = ?, prix_51_100 = ?, prix_101_300 = ?, prix_300_plus = ?,
                prix_simple_face = ?, prix_double_face = ?, commande_min_euro = ?,
                delai_standard_jours = ?, stock_disponible = ?, unite_vente = ?
                WHERE id = ?";

        $params = array_values($data);
        $params[] = $produit_id;

        $db->query($sql, $params);
        $success = 'Produit modifié avec succès';
        logAdminAction($admin['id'], 'update_produit', "Modification produit: {$data['nom']}");
    } else {
        // Création
        $sql = "INSERT INTO produits (
                code, nom, categorie, sous_titre, description_courte, description_longue,
                poids_m2, epaisseur, format_max_cm, usage, duree_vie, certification,
                finition, impression_faces, prix_0_10, prix_11_50, prix_51_100,
                prix_101_300, prix_300_plus, prix_simple_face, prix_double_face,
                commande_min_euro, delai_standard_jours, stock_disponible, unite_vente
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $db->query($sql, array_values($data));
        $success = 'Produit créé avec succès';
        logAdminAction($admin['id'], 'create_produit', "Création produit: {$data['nom']}");
    }

    $mode = 'list';
}

// Récupérer le produit à éditer
$produit = null;
if ($mode === 'edit' && $produit_id > 0) {
    $produit = $db->fetchOne("SELECT * FROM produits WHERE id = ?", [$produit_id]);
    if (!$produit) {
        $error = 'Produit introuvable';
        $mode = 'list';
    }
}

// Récupérer tous les produits pour la liste
if ($mode === 'list') {
    $produits = $db->fetchAll("SELECT * FROM produits ORDER BY categorie, nom");

    // Stats
    $stats = [
        'total' => $db->fetchOne("SELECT COUNT(*) as count FROM produits")['count'],
        'stock' => $db->fetchOne("SELECT COUNT(*) as count FROM produits WHERE stock_disponible = TRUE")['count'],
        'categories' => $db->fetchOne("SELECT COUNT(DISTINCT categorie) as count FROM produits")['count']
    ];
}

// Récupérer les catégories uniques
$categories = $db->fetchAll("SELECT DISTINCT categorie FROM produits ORDER BY categorie");

// Fonction helper pour nettoyer l'input
function cleanInput($str) {
    return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
}
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

        .btn-success {
            background: #27ae60;
            color: white;
        }

        .btn-success:hover {
            background: #229954;
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

        .btn-small {
            padding: 6px 12px;
            font-size: 12px;
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

        .category-row {
            background: #667eea !important;
            color: white !important;
            font-weight: 600;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success {
            background: #27ae60;
            color: white;
        }

        .badge-danger {
            background: #e74c3c;
            color: white;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
            color: #555;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border 0.3s;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-group .help-text {
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 5px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .checkbox-group input[type="checkbox"] {
            width: auto;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            margin: 30px 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #ecf0f1;
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

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .price-grid {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            color: #667eea;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #ecf0f1;
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

        <?php if ($mode === 'list'): ?>
            <!-- MODE LISTE -->
            <div class="page-header">
                <h1 class="page-title">🏷️ Gestion des produits</h1>
                <a href="?mode=create" class="btn btn-primary">➕ Nouveau produit</a>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">Total produits</div>
                    <div class="stat-value"><?php echo $stats['total']; ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">En stock</div>
                    <div class="stat-value" style="color: #27ae60;"><?php echo $stats['stock']; ?></div>
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
                            <th>Catégorie</th>
                            <th>Prix 300+ m²</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $currentCategory = '';
                        foreach ($produits as $p):
                            if ($currentCategory !== $p['categorie']):
                                $currentCategory = $p['categorie'];
                        ?>
                                <tr class="category-row">
                                    <td colspan="6"><?php echo htmlspecialchars($currentCategory); ?></td>
                                </tr>
                        <?php endif; ?>
                        <tr>
                            <td><code><?php echo htmlspecialchars($p['code']); ?></code></td>
                            <td>
                                <strong><?php echo htmlspecialchars($p['nom']); ?></strong>
                                <?php if ($p['sous_titre']): ?>
                                    <br><small style="color: #666;"><?php echo htmlspecialchars($p['sous_titre']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($p['categorie']); ?></td>
                            <td><strong style="color: #667eea;"><?php echo number_format($p['prix_300_plus'], 2, ',', ' '); ?> €/<?php echo $p['unite_vente']; ?></strong></td>
                            <td>
                                <?php if ($p['stock_disponible']): ?>
                                    <span class="badge badge-success">En stock</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Rupture</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="?mode=edit&id=<?php echo $p['id']; ?>" class="btn btn-primary btn-small">✏️ Éditer</a>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Confirmer la suppression ?');">
                                        <input type="hidden" name="action" value="delete_produit">
                                        <input type="hidden" name="produit_id" value="<?php echo $p['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-small">🗑️ Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($mode === 'create' || $mode === 'edit'): ?>
            <!-- MODE CRÉATION / ÉDITION -->
            <div class="page-header">
                <h1 class="page-title">
                    <?php echo $mode === 'create' ? '➕ Nouveau produit' : '✏️ Modifier le produit'; ?>
                </h1>
                <a href="?mode=list" class="btn btn-secondary">← Retour à la liste</a>
            </div>

            <div class="card">
                <form method="POST">
                    <input type="hidden" name="action" value="save_produit">

                    <!-- Informations générales -->
                    <h3 class="section-title">📋 Informations générales</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Code produit *</label>
                            <input type="text" name="code" value="<?php echo $produit['code'] ?? ''; ?>" required>
                            <div class="help-text">Identifiant unique (ex: FX-3MM)</div>
                        </div>

                        <div class="form-group">
                            <label>Nom du produit *</label>
                            <input type="text" name="nom" value="<?php echo $produit['nom'] ?? ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Catégorie *</label>
                            <input type="text" name="categorie" list="categories-list" value="<?php echo $produit['categorie'] ?? ''; ?>" required>
                            <datalist id="categories-list">
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat['categorie']); ?>">
                                <?php endforeach; ?>
                            </datalist>
                            <div class="help-text">Choisir ou créer une nouvelle catégorie</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Sous-titre</label>
                        <input type="text" name="sous_titre" value="<?php echo $produit['sous_titre'] ?? ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Description courte</label>
                        <textarea name="description_courte"><?php echo $produit['description_courte'] ?? ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Description longue</label>
                        <textarea name="description_longue" rows="6"><?php echo $produit['description_longue'] ?? ''; ?></textarea>
                    </div>

                    <!-- Caractéristiques techniques -->
                    <h3 class="section-title">🔧 Caractéristiques techniques</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Poids (kg/m²)</label>
                            <input type="number" name="poids_m2" step="0.01" value="<?php echo $produit['poids_m2'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label>Épaisseur</label>
                            <input type="text" name="epaisseur" value="<?php echo $produit['epaisseur'] ?? ''; ?>">
                            <div class="help-text">Ex: 3mm, 5mm</div>
                        </div>

                        <div class="form-group">
                            <label>Format maximum</label>
                            <input type="text" name="format_max_cm" value="<?php echo $produit['format_max_cm'] ?? ''; ?>">
                            <div class="help-text">Ex: 200x300</div>
                        </div>

                        <div class="form-group">
                            <label>Usage</label>
                            <input type="text" name="usage" value="<?php echo $produit['usage'] ?? ''; ?>">
                            <div class="help-text">Ex: Intérieur/Extérieur</div>
                        </div>

                        <div class="form-group">
                            <label>Durée de vie</label>
                            <input type="text" name="duree_vie" value="<?php echo $produit['duree_vie'] ?? ''; ?>">
                            <div class="help-text">Ex: 3-5 ans</div>
                        </div>

                        <div class="form-group">
                            <label>Certification</label>
                            <input type="text" name="certification" value="<?php echo $produit['certification'] ?? ''; ?>">
                            <div class="help-text">Ex: M1, B1</div>
                        </div>

                        <div class="form-group">
                            <label>Finition</label>
                            <input type="text" name="finition" value="<?php echo $produit['finition'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label>Impression faces</label>
                            <select name="impression_faces">
                                <option value="Simple" <?php echo ($produit['impression_faces'] ?? '') === 'Simple' ? 'selected' : ''; ?>>Simple face</option>
                                <option value="Double" <?php echo ($produit['impression_faces'] ?? '') === 'Double' ? 'selected' : ''; ?>>Double face</option>
                                <option value="Simple ou double" <?php echo ($produit['impression_faces'] ?? '') === 'Simple ou double' ? 'selected' : ''; ?>>Simple ou double</option>
                            </select>
                        </div>
                    </div>

                    <!-- Grille tarifaire -->
                    <h3 class="section-title">💰 Tarification par volume (€/m²)</h3>
                    <div class="price-grid">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Prix 0-10 m² *</label>
                                <input type="number" name="prix_0_10" step="0.01" value="<?php echo $produit['prix_0_10'] ?? ''; ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Prix 11-50 m² *</label>
                                <input type="number" name="prix_11_50" step="0.01" value="<?php echo $produit['prix_11_50'] ?? ''; ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Prix 51-100 m² *</label>
                                <input type="number" name="prix_51_100" step="0.01" value="<?php echo $produit['prix_51_100'] ?? ''; ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Prix 101-300 m² *</label>
                                <input type="number" name="prix_101_300" step="0.01" value="<?php echo $produit['prix_101_300'] ?? ''; ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Prix 300+ m² *</label>
                                <input type="number" name="prix_300_plus" step="0.01" value="<?php echo $produit['prix_300_plus'] ?? ''; ?>" required>
                            </div>
                        </div>
                    </div>

                    <h3 class="section-title">🎨 Prix impression</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Prix simple face (€/m²)</label>
                            <input type="number" name="prix_simple_face" step="0.01" value="<?php echo $produit['prix_simple_face'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label>Prix double face (€/m²)</label>
                            <input type="number" name="prix_double_face" step="0.01" value="<?php echo $produit['prix_double_face'] ?? ''; ?>">
                        </div>
                    </div>

                    <!-- Paramètres commerciaux -->
                    <h3 class="section-title">⚙️ Paramètres commerciaux</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Commande minimum (€) *</label>
                            <input type="number" name="commande_min_euro" step="0.01" value="<?php echo $produit['commande_min_euro'] ?? 25; ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Délai standard (jours) *</label>
                            <input type="number" name="delai_standard_jours" value="<?php echo $produit['delai_standard_jours'] ?? 3; ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Unité de vente</label>
                            <select name="unite_vente">
                                <option value="m²" <?php echo ($produit['unite_vente'] ?? 'm²') === 'm²' ? 'selected' : ''; ?>>m²</option>
                                <option value="unité" <?php echo ($produit['unite_vente'] ?? '') === 'unité' ? 'selected' : ''; ?>>Unité</option>
                                <option value="ml" <?php echo ($produit['unite_vente'] ?? '') === 'ml' ? 'selected' : ''; ?>>Mètre linéaire</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <div class="checkbox-group">
                                <input type="checkbox" name="stock_disponible" id="stock_disponible" <?php echo ($produit['stock_disponible'] ?? 1) ? 'checked' : ''; ?>>
                                <label for="stock_disponible" style="margin: 0;">Produit en stock</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-success">💾 Enregistrer le produit</button>
                        <a href="?mode=list" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
