<?php
/**
 * Éditer un produit existant
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../api/config.php';

verifierAdminConnecte();
$admin = getAdminInfo();

$db = Database::getInstance();
$error = '';
$success = '';
$produit = null;
$finitions = [];

// Récupérer l'ID du produit
$idProduit = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($idProduit)) {
    header('Location: produits.php?error=' . urlencode('ID produit manquant'));
    exit;
}

// Charger les données du produit depuis la base de données
try {
    $produit = $db->fetchOne(
        "SELECT p.*,
                pr.id as promo_id, pr.type as promo_type, pr.valeur as promo_valeur,
                pr.prix_special as promo_prix, pr.titre as promo_titre, pr.badge_texte as promo_badge,
                pr.date_debut as promo_date_debut, pr.date_fin as promo_date_fin,
                pr.afficher_countdown as promo_countdown, pr.actif as promo_actif
         FROM produits p
         LEFT JOIN promotions pr ON pr.produit_id = p.id AND pr.actif = 1
         WHERE p.id_produit = ?",
        [$idProduit]
    );

    if (!$produit) {
        header('Location: produits.php?error=' . urlencode('Produit non trouvé'));
        exit;
    }

    // Charger les finitions
    $finitions = $db->fetchAll(
        "SELECT * FROM produits_finitions WHERE produit_id = ? ORDER BY ordre",
        [$produit['id']]
    );

} catch (Exception $e) {
    header('Location: produits.php?error=' . urlencode('Erreur chargement: ' . $e->getMessage()));
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation
    $requiredFields = ['CATEGORIE', 'NOM_PRODUIT', 'DESCRIPTION_COURTE'];
    $missingFields = [];

    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $missingFields[] = $field;
        }
    }

    if (!empty($missingFields)) {
        $error = "Champs obligatoires manquants : " . implode(', ', $missingFields);
    } else {
        try {
            // Mettre à jour le produit
            $updates = [];
            $params = [];

            $allowedFields = [
                'categorie' => 'CATEGORIE',
                'nom_produit' => 'NOM_PRODUIT',
                'sous_titre' => 'SOUS_TITRE',
                'description_courte' => 'DESCRIPTION_COURTE',
                'description_longue' => 'DESCRIPTION_LONGUE',
                'poids_m2' => 'POIDS_M2',
                'epaisseur' => 'EPAISSEUR',
                'format_max_cm' => 'FORMAT_MAX_CM',
                'usage' => 'USAGE',
                'duree_vie' => 'DUREE_VIE',
                'certification' => 'CERTIFICATION',
                'finition_defaut' => 'FINITION',
                'impression_faces' => 'IMPRESSION_FACES',
                'prix_0_10' => 'PRIX_0_10_M2',
                'prix_11_50' => 'PRIX_11_50_M2',
                'prix_51_100' => 'PRIX_51_100_M2',
                'prix_101_300' => 'PRIX_101_300_M2',
                'prix_300_plus' => 'PRIX_300_PLUS_M2',
                'commande_min_euro' => 'COMMANDE_MIN_EURO',
                'delai_standard_jours' => 'DELAI_STANDARD_JOURS',
                'unite_vente' => 'UNITE_VENTE',
                'image_url' => 'IMAGE_URL'
            ];

            foreach ($allowedFields as $dbField => $postField) {
                if (isset($_POST[$postField])) {
                    $updates[] = "$dbField = ?";
                    $params[] = $_POST[$postField];
                }
            }

            if (count($updates) > 0) {
                $params[] = $produit['id'];
                $db->query(
                    "UPDATE produits SET " . implode(', ', $updates) . ", updated_at = NOW() WHERE id = ?",
                    $params
                );
            }

            // Mettre à jour finitions si présentes
            if (isset($_POST['finitions']) && is_array($_POST['finitions'])) {
                // Supprimer anciennes finitions
                $db->query("DELETE FROM produits_finitions WHERE produit_id = ?", [$produit['id']]);

                // Ajouter nouvelles finitions
                foreach ($_POST['finitions'] as $index => $fin) {
                    if (!empty($fin['nom'])) {
                        $db->query(
                            "INSERT INTO produits_finitions (produit_id, nom, description, prix_supplement, type_prix, ordre)
                             VALUES (?, ?, ?, ?, ?, ?)",
                            [
                                $produit['id'],
                                $fin['nom'],
                                $fin['description'] ?? '',
                                $fin['prix_supplement'] ?? 0,
                                $fin['type_prix'] ?? 'fixe',
                                $index
                            ]
                        );
                    }
                }
            }

            // Gestion promotion
            if (isset($_POST['promo_actif'])) {
                if ($_POST['promo_actif'] == '1') {
                    // Créer ou mettre à jour promo
                    $existing = $db->fetchOne("SELECT id FROM promotions WHERE produit_id = ?", [$produit['id']]);

                    if ($existing) {
                        $db->query(
                            "UPDATE promotions SET
                                type = ?, valeur = ?, prix_special = ?, titre = ?, badge_texte = ?,
                                date_debut = ?, date_fin = ?, afficher_countdown = ?, actif = 1
                             WHERE id = ?",
                            [
                                $_POST['promo_type'] ?? 'pourcentage',
                                $_POST['promo_valeur'] ?? 0,
                                $_POST['promo_prix_special'] ?? null,
                                $_POST['promo_titre'] ?? '',
                                $_POST['promo_badge'] ?? 'PROMO',
                                $_POST['promo_date_debut'] ?? null,
                                $_POST['promo_date_fin'] ?? null,
                                isset($_POST['promo_countdown']) ? 1 : 0,
                                $existing['id']
                            ]
                        );
                    } else {
                        $db->query(
                            "INSERT INTO promotions (produit_id, type, valeur, prix_special, titre, badge_texte, date_debut, date_fin, afficher_countdown, actif)
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)",
                            [
                                $produit['id'],
                                $_POST['promo_type'] ?? 'pourcentage',
                                $_POST['promo_valeur'] ?? 0,
                                $_POST['promo_prix_special'] ?? null,
                                $_POST['promo_titre'] ?? '',
                                $_POST['promo_badge'] ?? 'PROMO',
                                $_POST['promo_date_debut'] ?? null,
                                $_POST['promo_date_fin'] ?? null,
                                isset($_POST['promo_countdown']) ? 1 : 0
                            ]
                        );
                    }
                } else {
                    // Désactiver promo
                    $db->query("UPDATE promotions SET actif = 0 WHERE produit_id = ?", [$produit['id']]);
                }
            }

            // Log admin action
            logAdminAction($admin['id'] ?? 0, 'modification_produit', "Produit {$produit['id_produit']} modifié");

            // Redirection
            header('Location: produits.php?success=' . urlencode('Produit modifié avec succès !'));
            exit;

        } catch (Exception $e) {
            $error = "Erreur lors de la mise à jour : " . $e->getMessage();
        }
    }
}

// Charger les catégories existantes depuis la base de données
$categories = [];
try {
    $categoriesData = $db->fetchAll("SELECT DISTINCT categorie FROM produits ORDER BY categorie");
    $categories = array_column($categoriesData, 'categorie');
} catch (Exception $e) {
    // Erreur silencieuse, on continuera avec une liste vide
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
            <h1 class="page-title">✏️ Éditer le produit: <?php echo htmlspecialchars($produit['nom_produit']); ?></h1>
            <a href="produits.php" class="btn btn-secondary">← Retour à la liste</a>
        </div>

        <div class="alert-info">
            ℹ️ <strong>ID Produit:</strong> <code><?php echo htmlspecialchars($produit['id_produit']); ?></code> - La modification du produit met automatiquement à jour la base de données.
        </div>

        <div class="card">
            <form method="POST" action="">
                <div class="section-title">📋 Informations de base</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>ID Produit <span class="required">*</span></label>
                        <input type="text" name="ID_PRODUIT" required value="<?php echo htmlspecialchars($produit['id_produit']); ?>" readonly style="background: #f5f5f5;">
                        <small>L'ID ne peut pas être modifié</small>
                    </div>

                    <div class="form-group">
                        <label>Catégorie <span class="required">*</span></label>
                        <select name="CATEGORIE" required>
                            <option value="">Sélectionner une catégorie</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $produit['categorie'] === $cat ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Nom du produit <span class="required">*</span></label>
                        <input type="text" name="NOM_PRODUIT" required value="<?php echo htmlspecialchars($produit['nom_produit']); ?>">
                    </div>

                    <div class="form-group">
                        <label>Sous-titre</label>
                        <input type="text" name="SOUS_TITRE" value="<?php echo htmlspecialchars($produit['sous_titre'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Description courte <span class="required">*</span></label>
                    <textarea name="DESCRIPTION_COURTE" required><?php echo htmlspecialchars($produit['description_courte']); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Description longue</label>
                    <textarea name="DESCRIPTION_LONGUE" style="min-height: 150px;"><?php echo htmlspecialchars($produit['description_longue'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Image URL</label>
                    <input type="url" name="IMAGE_URL" value="<?php echo htmlspecialchars($produit['image_url'] ?? ''); ?>" placeholder="https://...">
                    <small>URL de l'image du produit (Unsplash, CDN, etc.)</small>
                </div>

                <div class="section-title">⚙️ Caractéristiques techniques</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Poids (kg/m²)</label>
                        <input type="text" name="POIDS_M2" value="<?php echo htmlspecialchars($produit['poids_m2'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Épaisseur</label>
                        <input type="text" name="EPAISSEUR" value="<?php echo htmlspecialchars($produit['epaisseur'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Format maximum (cm)</label>
                        <input type="text" name="FORMAT_MAX_CM" value="<?php echo htmlspecialchars($produit['format_max_cm'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Usage</label>
                        <input type="text" name="USAGE" value="<?php echo htmlspecialchars($produit['usage'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Durée de vie</label>
                        <input type="text" name="DUREE_VIE" value="<?php echo htmlspecialchars($produit['duree_vie'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Certification</label>
                        <input type="text" name="CERTIFICATION" value="<?php echo htmlspecialchars($produit['certification'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Finition</label>
                        <input type="text" name="FINITION" value="<?php echo htmlspecialchars($produit['finition_defaut'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Impression faces</label>
                        <select name="IMPRESSION_FACES">
                            <option value="">Sélectionner</option>
                            <option value="Simple ou double" <?php echo (isset($produit['impression_faces']) && $produit['impression_faces'] === 'Simple ou double') ? 'selected' : ''; ?>>Simple ou double</option>
                            <option value="Simple face" <?php echo (isset($produit['impression_faces']) && $produit['impression_faces'] === 'Simple face') ? 'selected' : ''; ?>>Simple face</option>
                            <option value="Double face" <?php echo (isset($produit['impression_faces']) && $produit['impression_faces'] === 'Double face') ? 'selected' : ''; ?>>Double face</option>
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
                        <input type="number" step="0.01" name="PRIX_0_10_M2" value="<?php echo htmlspecialchars($produit['prix_0_10'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Prix 11-50 m² (€/m²)</label>
                        <input type="number" step="0.01" name="PRIX_11_50_M2" value="<?php echo htmlspecialchars($produit['prix_11_50'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Prix 51-100 m² (€/m²)</label>
                        <input type="number" step="0.01" name="PRIX_51_100_M2" value="<?php echo htmlspecialchars($produit['prix_51_100'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Prix 101-300 m² (€/m²)</label>
                        <input type="number" step="0.01" name="PRIX_101_300_M2" value="<?php echo htmlspecialchars($produit['prix_101_300'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Prix 300+ m² (€/m²)</label>
                        <input type="number" step="0.01" name="PRIX_300_PLUS_M2" value="<?php echo htmlspecialchars($produit['prix_300_plus'] ?? ''); ?>">
                    </div>
                </div>

                <div class="section-title">📦 Logistique</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Commande minimum (€)</label>
                        <input type="number" step="0.01" name="COMMANDE_MIN_EURO" value="<?php echo htmlspecialchars($produit['commande_min_euro'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Délai standard (jours)</label>
                        <input type="number" name="DELAI_STANDARD_JOURS" value="<?php echo htmlspecialchars($produit['delai_standard_jours'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Unité de vente</label>
                        <select name="UNITE_VENTE">
                            <option value="m²" <?php echo (isset($produit['unite_vente']) && $produit['unite_vente'] === 'm²') ? 'selected' : ''; ?>>m²</option>
                            <option value="ml" <?php echo (isset($produit['unite_vente']) && $produit['unite_vente'] === 'ml') ? 'selected' : ''; ?>>ml (mètre linéaire)</option>
                            <option value="unité" <?php echo (isset($produit['unite_vente']) && $produit['unite_vente'] === 'unité') ? 'selected' : ''; ?>>unité</option>
                        </select>
                    </div>
                </div>

                <div class="section-title">🎨 Finitions et options</div>
                <div id="finitions-container">
                    <?php foreach ($finitions as $index => $fin): ?>
                        <div class="form-grid" style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 10px;">
                            <input type="hidden" name="finitions[<?php echo $index; ?>][id]" value="<?php echo $fin['id']; ?>">
                            <div class="form-group">
                                <label>Nom</label>
                                <input type="text" name="finitions[<?php echo $index; ?>][nom]" value="<?php echo htmlspecialchars($fin['nom']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <input type="text" name="finitions[<?php echo $index; ?>][description]" value="<?php echo htmlspecialchars($fin['description'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label>Prix supplément</label>
                                <input type="number" step="0.01" name="finitions[<?php echo $index; ?>][prix_supplement]" value="<?php echo htmlspecialchars($fin['prix_supplement']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Type prix</label>
                                <select name="finitions[<?php echo $index; ?>][type_prix]">
                                    <option value="fixe" <?php echo $fin['type_prix'] === 'fixe' ? 'selected' : ''; ?>>Fixe</option>
                                    <option value="par_m2" <?php echo $fin['type_prix'] === 'par_m2' ? 'selected' : ''; ?>>Par m²</option>
                                    <option value="par_ml" <?php echo $fin['type_prix'] === 'par_ml' ? 'selected' : ''; ?>>Par ml</option>
                                </select>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" onclick="addFinition()" class="btn btn-secondary" style="margin-bottom: 20px;">➕ Ajouter une finition</button>

                <div class="section-title">🎁 Promotion</div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="promo_actif" value="1" <?php echo ($produit['promo_actif'] ?? 0) ? 'checked' : ''; ?>>
                        Activer une promotion sur ce produit
                    </label>
                </div>

                <div id="promo-fields" style="display: <?php echo ($produit['promo_actif'] ?? 0) ? 'block' : 'none'; ?>;">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Type de promotion</label>
                            <select name="promo_type">
                                <option value="pourcentage" <?php echo ($produit['promo_type'] ?? '') === 'pourcentage' ? 'selected' : ''; ?>>Pourcentage</option>
                                <option value="fixe" <?php echo ($produit['promo_type'] ?? '') === 'fixe' ? 'selected' : ''; ?>>Réduction fixe</option>
                                <option value="prix_special" <?php echo ($produit['promo_type'] ?? '') === 'prix_special' ? 'selected' : ''; ?>>Prix spécial</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Valeur (% ou €)</label>
                            <input type="number" step="0.01" name="promo_valeur" value="<?php echo htmlspecialchars($produit['promo_valeur'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>Prix spécial (si applicable)</label>
                            <input type="number" step="0.01" name="promo_prix_special" value="<?php echo htmlspecialchars($produit['promo_prix'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Titre promo</label>
                            <input type="text" name="promo_titre" value="<?php echo htmlspecialchars($produit['promo_titre'] ?? ''); ?>" placeholder="Ex: SOLDES D'ÉTÉ">
                        </div>
                        <div class="form-group">
                            <label>Badge</label>
                            <input type="text" name="promo_badge" value="<?php echo htmlspecialchars($produit['promo_badge'] ?? 'PROMO'); ?>" placeholder="PROMO">
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Date début</label>
                            <input type="datetime-local" name="promo_date_debut" value="<?php echo isset($produit['promo_date_debut']) ? date('Y-m-d\TH:i', strtotime($produit['promo_date_debut'])) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Date fin</label>
                            <input type="datetime-local" name="promo_date_fin" value="<?php echo isset($produit['promo_date_fin']) ? date('Y-m-d\TH:i', strtotime($produit['promo_date_fin'])) : ''; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="promo_countdown" value="1" <?php echo ($produit['promo_countdown'] ?? 0) ? 'checked' : ''; ?>>
                            Afficher un compte à rebours
                        </label>
                    </div>
                </div>

                <script>
                let finitionIndex = <?php echo count($finitions); ?>;

                function addFinition() {
                    const container = document.getElementById('finitions-container');
                    const div = document.createElement('div');
                    div.className = 'form-grid';
                    div.style.cssText = 'background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 10px;';
                    div.innerHTML = `
                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" name="finitions[${finitionIndex}][nom]" placeholder="Ex: Œillets">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" name="finitions[${finitionIndex}][description]" placeholder="Ex: Tous les 50cm">
                        </div>
                        <div class="form-group">
                            <label>Prix supplément</label>
                            <input type="number" step="0.01" name="finitions[${finitionIndex}][prix_supplement]" value="0">
                        </div>
                        <div class="form-group">
                            <label>Type prix</label>
                            <select name="finitions[${finitionIndex}][type_prix]">
                                <option value="fixe">Fixe</option>
                                <option value="par_m2">Par m²</option>
                                <option value="par_ml">Par ml</option>
                            </select>
                        </div>
                    `;
                    container.appendChild(div);
                    finitionIndex++;
                }

                // Toggle promo fields
                document.querySelector('input[name="promo_actif"]').addEventListener('change', function(e) {
                    document.getElementById('promo-fields').style.display = e.target.checked ? 'block' : 'none';
                });
                </script>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">💾 Enregistrer les modifications</button>
                    <a href="produits.php" class="btn btn-secondary">✖ Annuler</a>
                    <a href="supprimer-produit.php?id=<?php echo urlencode($produit['id_produit']); ?>" class="btn btn-danger" onclick="return confirm('Supprimer définitivement ce produit ?')" style="margin-left: auto;">🗑️ Supprimer le produit</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
