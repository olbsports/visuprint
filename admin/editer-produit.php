<?php
/**
 * Éditer un produit existant
 */

require_once __DIR__ . '/auth.php';

verifierAdminConnecte();
$admin = getAdminInfo();

$csvFile = __DIR__ . '/../CATALOGUE_COMPLET_VISUPRINT.csv';
$error = '';
$success = '';
$produit = null;

// Récupérer l'ID du produit
$idProduit = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($idProduit)) {
    header('Location: produits.php?error=' . urlencode('ID produit manquant'));
    exit;
}

// Charger les données du produit
if (file_exists($csvFile)) {
    $file = fopen($csvFile, 'r');
    $headers = fgetcsv($file);

    while (($row = fgetcsv($file)) !== false) {
        if (count($row) === count($headers) && $row[0] === $idProduit) {
            $produit = array_combine($headers, $row);
            break;
        }
    }
    fclose($file);
}

if (!$produit) {
    header('Location: produits.php?error=' . urlencode('Produit non trouvé'));
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation
    $requiredFields = ['ID_PRODUIT', 'CATEGORIE', 'NOM_PRODUIT', 'DESCRIPTION_COURTE'];
    $missingFields = [];

    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $missingFields[] = $field;
        }
    }

    if (!empty($missingFields)) {
        $error = "Champs obligatoires manquants : " . implode(', ', $missingFields);
    } else {
        // Lire tous les produits
        $allProducts = [];
        $file = fopen($csvFile, 'r');
        $headers = fgetcsv($file);
        $allProducts[] = $headers;

        while (($row = fgetcsv($file)) !== false) {
            if ($row[0] === $idProduit) {
                // Remplacer ce produit par les nouvelles données
                $row = [
                    $_POST['ID_PRODUIT'],
                    $_POST['CATEGORIE'],
                    $_POST['NOM_PRODUIT'],
                    $_POST['SOUS_TITRE'] ?? '',
                    $_POST['DESCRIPTION_COURTE'],
                    $_POST['DESCRIPTION_LONGUE'] ?? '',
                    $_POST['POIDS_M2'] ?? '',
                    $_POST['EPAISSEUR'] ?? '',
                    $_POST['FORMAT_MAX_CM'] ?? '',
                    $_POST['USAGE'] ?? '',
                    $_POST['DUREE_VIE'] ?? '',
                    $_POST['CERTIFICATION'] ?? '',
                    $_POST['FINITION'] ?? '',
                    $_POST['IMPRESSION_FACES'] ?? '',
                    $_POST['COUT_ACHAT_M2'] ?? '',
                    $_POST['PRIX_SIMPLE_FACE_M2'] ?? '',
                    $_POST['PRIX_DOUBLE_FACE_M2'] ?? '',
                    $_POST['PRIX_0_10_M2'] ?? '',
                    $_POST['PRIX_11_50_M2'] ?? '',
                    $_POST['PRIX_51_100_M2'] ?? '',
                    $_POST['PRIX_101_300_M2'] ?? '',
                    $_POST['PRIX_300_PLUS_M2'] ?? '',
                    $_POST['COMMANDE_MIN_EURO'] ?? '',
                    $_POST['DELAI_STANDARD_JOURS'] ?? '',
                    $_POST['UNITE_VENTE'] ?? 'm²'
                ];
            }
            $allProducts[] = $row;
        }
        fclose($file);

        // Écrire le nouveau CSV
        $file = fopen($csvFile, 'w');
        foreach ($allProducts as $row) {
            fputcsv($file, $row);
        }
        fclose($file);

        // Régénérer la page HTML du produit
        require_once __DIR__ . '/helpers/generer-page-produit.php';

        // Construire l'array du produit pour la génération HTML
        $produitData = [
            'ID_PRODUIT' => $_POST['ID_PRODUIT'],
            'CATEGORIE' => $_POST['CATEGORIE'],
            'NOM_PRODUIT' => $_POST['NOM_PRODUIT'],
            'SOUS_TITRE' => $_POST['SOUS_TITRE'] ?? '',
            'DESCRIPTION_COURTE' => $_POST['DESCRIPTION_COURTE'],
            'DESCRIPTION_LONGUE' => $_POST['DESCRIPTION_LONGUE'] ?? '',
            'POIDS_M2' => $_POST['POIDS_M2'] ?? '',
            'EPAISSEUR' => $_POST['EPAISSEUR'] ?? '',
            'FORMAT_MAX_CM' => $_POST['FORMAT_MAX_CM'] ?? '',
            'USAGE' => $_POST['USAGE'] ?? '',
            'DUREE_VIE' => $_POST['DUREE_VIE'] ?? '',
            'CERTIFICATION' => $_POST['CERTIFICATION'] ?? '',
            'FINITION' => $_POST['FINITION'] ?? '',
            'IMPRESSION_FACES' => $_POST['IMPRESSION_FACES'] ?? '',
            'COUT_ACHAT_M2' => $_POST['COUT_ACHAT_M2'] ?? '',
            'PRIX_SIMPLE_FACE_M2' => $_POST['PRIX_SIMPLE_FACE_M2'] ?? '',
            'PRIX_DOUBLE_FACE_M2' => $_POST['PRIX_DOUBLE_FACE_M2'] ?? '',
            'PRIX_0_10_M2' => $_POST['PRIX_0_10_M2'] ?? '',
            'PRIX_11_50_M2' => $_POST['PRIX_11_50_M2'] ?? '',
            'PRIX_51_100_M2' => $_POST['PRIX_51_100_M2'] ?? '',
            'PRIX_101_300_M2' => $_POST['PRIX_101_300_M2'] ?? '',
            'PRIX_300_PLUS_M2' => $_POST['PRIX_300_PLUS_M2'] ?? '',
            'COMMANDE_MIN_EURO' => $_POST['COMMANDE_MIN_EURO'] ?? '',
            'DELAI_STANDARD_JOURS' => $_POST['DELAI_STANDARD_JOURS'] ?? '',
            'UNITE_VENTE' => $_POST['UNITE_VENTE'] ?? 'm²'
        ];

        genererEtSauvegarderPageProduit($produitData);

        // Redirection
        header('Location: produits.php?success=' . urlencode('Produit modifié avec succès !'));
        exit;
    }
}

// Charger les catégories existantes
$categories = [];
if (file_exists($csvFile)) {
    $file = fopen($csvFile, 'r');
    $headers = fgetcsv($file);
    while (($row = fgetcsv($file)) !== false) {
        if (!empty($row[1])) {
            $categories[] = $row[1];
        }
    }
    fclose($file);
    $categories = array_unique($categories);
    sort($categories);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éditer un produit - Imprixo Admin</title>
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
            max-width: 1200px;
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

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
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
            color: #2c3e50;
        }

        .form-group label .required {
            color: #e74c3c;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s;
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

        .form-group small {
            display: block;
            margin-top: 5px;
            color: #666;
            font-size: 12px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #667eea;
            margin: 30px 0 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
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

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
        }

        .alert-info {
            background: #d1ecf1;
            border-left: 4px solid #0c5460;
            color: #0c5460;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
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
        <?php if ($error): ?>
            <div class="error-message">✗ <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success-message">✓ <?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <div class="page-header">
            <h1 class="page-title">✏️ Éditer le produit: <?php echo htmlspecialchars($produit['NOM_PRODUIT']); ?></h1>
            <a href="produits.php" class="btn btn-secondary">← Retour à la liste</a>
        </div>

        <div class="alert-info">
            ℹ️ <strong>ID Produit:</strong> <code><?php echo htmlspecialchars($produit['ID_PRODUIT']); ?></code> - La modification du produit met automatiquement à jour le CSV et régénère la page HTML.
        </div>

        <div class="card">
            <form method="POST" action="">
                <div class="section-title">📋 Informations de base</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>ID Produit <span class="required">*</span></label>
                        <input type="text" name="ID_PRODUIT" required value="<?php echo htmlspecialchars($produit['ID_PRODUIT']); ?>" readonly style="background: #f5f5f5;">
                        <small>L'ID ne peut pas être modifié</small>
                    </div>

                    <div class="form-group">
                        <label>Catégorie <span class="required">*</span></label>
                        <select name="CATEGORIE" required>
                            <option value="">Sélectionner une catégorie</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $produit['CATEGORIE'] === $cat ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Nom du produit <span class="required">*</span></label>
                        <input type="text" name="NOM_PRODUIT" required value="<?php echo htmlspecialchars($produit['NOM_PRODUIT']); ?>">
                    </div>

                    <div class="form-group">
                        <label>Sous-titre</label>
                        <input type="text" name="SOUS_TITRE" value="<?php echo htmlspecialchars($produit['SOUS_TITRE'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Description courte <span class="required">*</span></label>
                    <textarea name="DESCRIPTION_COURTE" required><?php echo htmlspecialchars($produit['DESCRIPTION_COURTE']); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Description longue</label>
                    <textarea name="DESCRIPTION_LONGUE" style="min-height: 150px;"><?php echo htmlspecialchars($produit['DESCRIPTION_LONGUE'] ?? ''); ?></textarea>
                </div>

                <div class="section-title">⚙️ Caractéristiques techniques</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Poids (kg/m²)</label>
                        <input type="text" name="POIDS_M2" value="<?php echo htmlspecialchars($produit['POIDS_M2'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Épaisseur</label>
                        <input type="text" name="EPAISSEUR" value="<?php echo htmlspecialchars($produit['EPAISSEUR'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Format maximum (cm)</label>
                        <input type="text" name="FORMAT_MAX_CM" value="<?php echo htmlspecialchars($produit['FORMAT_MAX_CM'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Usage</label>
                        <input type="text" name="USAGE" value="<?php echo htmlspecialchars($produit['USAGE'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Durée de vie</label>
                        <input type="text" name="DUREE_VIE" value="<?php echo htmlspecialchars($produit['DUREE_VIE'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Certification</label>
                        <input type="text" name="CERTIFICATION" value="<?php echo htmlspecialchars($produit['CERTIFICATION'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Finition</label>
                        <input type="text" name="FINITION" value="<?php echo htmlspecialchars($produit['FINITION'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Impression faces</label>
                        <select name="IMPRESSION_FACES">
                            <option value="">Sélectionner</option>
                            <option value="Simple ou double" <?php echo (isset($produit['IMPRESSION_FACES']) && $produit['IMPRESSION_FACES'] === 'Simple ou double') ? 'selected' : ''; ?>>Simple ou double</option>
                            <option value="Simple face" <?php echo (isset($produit['IMPRESSION_FACES']) && $produit['IMPRESSION_FACES'] === 'Simple face') ? 'selected' : ''; ?>>Simple face</option>
                            <option value="Double face" <?php echo (isset($produit['IMPRESSION_FACES']) && $produit['IMPRESSION_FACES'] === 'Double face') ? 'selected' : ''; ?>>Double face</option>
                        </select>
                    </div>
                </div>

                <div class="section-title">💰 Prix et tarification</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Coût d'achat (€/m²)</label>
                        <input type="number" step="0.01" name="COUT_ACHAT_M2" value="<?php echo htmlspecialchars($produit['COUT_ACHAT_M2'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Prix simple face (€/m²)</label>
                        <input type="number" step="0.01" name="PRIX_SIMPLE_FACE_M2" value="<?php echo htmlspecialchars($produit['PRIX_SIMPLE_FACE_M2'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Prix double face (€/m²)</label>
                        <input type="number" step="0.01" name="PRIX_DOUBLE_FACE_M2" value="<?php echo htmlspecialchars($produit['PRIX_DOUBLE_FACE_M2'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Prix 0-10 m² (€/m²)</label>
                        <input type="number" step="0.01" name="PRIX_0_10_M2" value="<?php echo htmlspecialchars($produit['PRIX_0_10_M2'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Prix 11-50 m² (€/m²)</label>
                        <input type="number" step="0.01" name="PRIX_11_50_M2" value="<?php echo htmlspecialchars($produit['PRIX_11_50_M2'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Prix 51-100 m² (€/m²)</label>
                        <input type="number" step="0.01" name="PRIX_51_100_M2" value="<?php echo htmlspecialchars($produit['PRIX_51_100_M2'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Prix 101-300 m² (€/m²)</label>
                        <input type="number" step="0.01" name="PRIX_101_300_M2" value="<?php echo htmlspecialchars($produit['PRIX_101_300_M2'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Prix 300+ m² (€/m²)</label>
                        <input type="number" step="0.01" name="PRIX_300_PLUS_M2" value="<?php echo htmlspecialchars($produit['PRIX_300_PLUS_M2'] ?? ''); ?>">
                    </div>
                </div>

                <div class="section-title">📦 Logistique</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Commande minimum (€)</label>
                        <input type="number" step="0.01" name="COMMANDE_MIN_EURO" value="<?php echo htmlspecialchars($produit['COMMANDE_MIN_EURO'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Délai standard (jours)</label>
                        <input type="number" name="DELAI_STANDARD_JOURS" value="<?php echo htmlspecialchars($produit['DELAI_STANDARD_JOURS'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Unité de vente</label>
                        <select name="UNITE_VENTE">
                            <option value="m²" <?php echo (isset($produit['UNITE_VENTE']) && $produit['UNITE_VENTE'] === 'm²') ? 'selected' : ''; ?>>m²</option>
                            <option value="ml" <?php echo (isset($produit['UNITE_VENTE']) && $produit['UNITE_VENTE'] === 'ml') ? 'selected' : ''; ?>>ml (mètre linéaire)</option>
                            <option value="unité" <?php echo (isset($produit['UNITE_VENTE']) && $produit['UNITE_VENTE'] === 'unité') ? 'selected' : ''; ?>>unité</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">💾 Enregistrer les modifications</button>
                    <a href="produits.php" class="btn btn-secondary">✖ Annuler</a>
                    <a href="supprimer-produit.php?id=<?php echo urlencode($produit['ID_PRODUIT']); ?>" class="btn btn-danger" onclick="return confirm('Supprimer définitivement ce produit ?')" style="margin-left: auto;">🗑️ Supprimer le produit</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
