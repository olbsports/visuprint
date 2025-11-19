<?php
/**
 * Ajouter / Éditer une finition du catalogue
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../api/config.php';

verifierAdminConnecte();
$admin = getAdminInfo();

$db = Database::getInstance();
$error = '';
$success = '';
$finition = null;
$isEdit = false;

// Mode édition ?
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $isEdit = true;
    try {
        $finition = $db->fetchOne("SELECT * FROM finitions_catalogue WHERE id = ?", [$id]);
        if (!$finition) {
            header('Location: finitions-catalogue.php?error=' . urlencode('Finition non trouvée'));
            exit;
        }
    } catch (Exception $e) {
        header('Location: finitions-catalogue.php?error=' . urlencode($e->getMessage()));
        exit;
    }
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $categorie = trim($_POST['categorie'] ?? '');
    $prix_defaut = floatval($_POST['prix_defaut'] ?? 0);
    $type_prix_defaut = $_POST['type_prix_defaut'] ?? 'fixe';
    $icone = trim($_POST['icone'] ?? '');
    $actif = isset($_POST['actif']) ? 1 : 0;
    $ordre = intval($_POST['ordre'] ?? 0);

    if (empty($nom)) {
        $error = "Le nom est obligatoire";
    } else {
        try {
            if ($isEdit) {
                // Mise à jour
                $db->query(
                    "UPDATE finitions_catalogue
                     SET nom = ?, description = ?, categorie = ?, prix_defaut = ?,
                         type_prix_defaut = ?, icone = ?, actif = ?, ordre = ?, updated_at = NOW()
                     WHERE id = ?",
                    [$nom, $description, $categorie, $prix_defaut, $type_prix_defaut, $icone, $actif, $ordre, $id]
                );
                $success = "Finition modifiée avec succès !";
            } else {
                // Création
                $db->query(
                    "INSERT INTO finitions_catalogue (nom, description, categorie, prix_defaut, type_prix_defaut, icone, actif, ordre)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                    [$nom, $description, $categorie, $prix_defaut, $type_prix_defaut, $icone, $actif, $ordre]
                );
                header('Location: finitions-catalogue.php?success=' . urlencode('Finition créée avec succès !'));
                exit;
            }

            // Recharger les données
            $finition = $db->fetchOne("SELECT * FROM finitions_catalogue WHERE id = ?", [$id]);

        } catch (Exception $e) {
            $error = "Erreur : " . $e->getMessage();
        }
    }
}

// Valeurs par défaut pour nouveau
if (!$finition) {
    $finition = [
        'nom' => '',
        'description' => '',
        'categorie' => '',
        'prix_defaut' => 0,
        'type_prix_defaut' => 'fixe',
        'icone' => '',
        'actif' => 1,
        'ordre' => 0
    ];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEdit ? 'Éditer' : 'Créer'; ?> une finition - Imprixo Admin</title>
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

        .container {
            max-width: 900px;
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

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
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

        .form-group .required {
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

        .form-group small {
            display: block;
            margin-top: 5px;
            color: #666;
            font-size: 12px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
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

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎨 Imprixo - Administration</h1>
    </div>

    <div class="container">
        <?php if ($error): ?>
            <div class="error-message">✗ <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success-message">✓ <?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <div class="page-header">
            <h1 class="page-title"><?php echo $isEdit ? '✏️ Éditer' : '➕ Créer'; ?> une finition</h1>
            <a href="finitions-catalogue.php" class="btn btn-secondary">← Retour</a>
        </div>

        <div class="card">
            <form method="POST" action="">
                <div class="form-group">
                    <label>Nom de la finition <span class="required">*</span></label>
                    <input type="text" name="nom" required value="<?php echo htmlspecialchars($finition['nom']); ?>" placeholder="Ex: Œillets tous les 50cm">
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" placeholder="Description détaillée de la finition"><?php echo htmlspecialchars($finition['description'] ?? ''); ?></textarea>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Catégorie</label>
                        <select name="categorie">
                            <option value="">-- Aucune catégorie --</option>
                            <option value="PVC" <?php echo $finition['categorie'] === 'PVC' ? 'selected' : ''; ?>>PVC / Forex</option>
                            <option value="Aluminium" <?php echo $finition['categorie'] === 'Aluminium' ? 'selected' : ''; ?>>Aluminium</option>
                            <option value="Bâche" <?php echo $finition['categorie'] === 'Bâche' ? 'selected' : ''; ?>>Bâche</option>
                            <option value="Textile" <?php echo $finition['categorie'] === 'Textile' ? 'selected' : ''; ?>>Textile</option>
                            <option value="Tous" <?php echo $finition['categorie'] === 'Tous' ? 'selected' : ''; ?>>Tous les produits</option>
                        </select>
                        <small>Laissez vide pour rendre disponible manuellement, ou choisissez "Tous" pour tous les produits</small>
                    </div>

                    <div class="form-group">
                        <label>Icône</label>
                        <input type="text" name="icone" value="<?php echo htmlspecialchars($finition['icone'] ?? ''); ?>" placeholder="Ex: 🎨 ou ⭕">
                        <small>Emoji ou texte court pour identifier visuellement</small>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Prix par défaut (€)</label>
                        <input type="number" step="0.01" name="prix_defaut" value="<?php echo htmlspecialchars($finition['prix_defaut']); ?>">
                        <small>Peut être modifié produit par produit. Utilisez négatif pour réduction</small>
                    </div>

                    <div class="form-group">
                        <label>Type de prix</label>
                        <select name="type_prix_defaut">
                            <option value="fixe" <?php echo $finition['type_prix_defaut'] === 'fixe' ? 'selected' : ''; ?>>Fixe (ex: +15€)</option>
                            <option value="par_m2" <?php echo $finition['type_prix_defaut'] === 'par_m2' ? 'selected' : ''; ?>>Par m² (ex: +8€/m²)</option>
                            <option value="par_ml" <?php echo $finition['type_prix_defaut'] === 'par_ml' ? 'selected' : ''; ?>>Par mètre linéaire</option>
                            <option value="pourcentage" <?php echo $finition['type_prix_defaut'] === 'pourcentage' ? 'selected' : ''; ?>>Pourcentage (ex: +10%)</option>
                        </select>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Ordre d'affichage</label>
                        <input type="number" name="ordre" value="<?php echo htmlspecialchars($finition['ordre']); ?>">
                        <small>Les finitions sont triées par ordre croissant</small>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-group">
                            <input type="checkbox" name="actif" value="1" <?php echo $finition['actif'] ? 'checked' : ''; ?>>
                            <span>Finition active</span>
                        </label>
                        <small>Si désactivée, elle n'apparaîtra plus dans les choix</small>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">💾 <?php echo $isEdit ? 'Enregistrer les modifications' : 'Créer la finition'; ?></button>
                    <a href="finitions-catalogue.php" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>

        <div class="card" style="background: #f8f9fa;">
            <h3 style="margin-bottom: 15px;">💡 À savoir</h3>
            <ul style="line-height: 1.8; margin-left: 20px;">
                <li><strong>Catégorie "Tous"</strong> : La finition apparaîtra automatiquement pour tous les produits</li>
                <li><strong>Catégorie spécifique</strong> : Apparaît seulement pour les produits de cette catégorie</li>
                <li><strong>Aucune catégorie</strong> : Tu devras l'activer manuellement produit par produit</li>
                <li><strong>Prix négatif</strong> : Pour faire des réductions (ex: -10€ si le client fournit son fichier)</li>
                <li><strong>Ordre</strong> : Les finitions avec ordre plus petit apparaissent en premier</li>
            </ul>
        </div>
    </div>
</body>
</html>
