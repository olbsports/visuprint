<?php
/**
 * Paramètres / Réglages - Imprixo Admin
 */

require_once __DIR__ . '/auth.php';

verifierAdminConnecte();
$admin = getAdminInfo();
$db = Database::getInstance();

$success = '';
$error = '';

// Récupérer ou initialiser les paramètres
$params = [
    'site_nom' => 'Imprixo',
    'site_email' => 'contact@imprixo.fr',
    'site_telephone' => '01 23 45 67 89',
    'site_adresse' => '',
    'tva_taux' => 20,
    'livraison_gratuite_seuil' => 200,
    'delai_livraison_standard' => 3,
    'fonds_perdu_cm' => 0.3,
    'zone_securite_cm' => 0.3,
    'min_commande_ht' => 25,
    'stripe_public_key' => '',
    'stripe_secret_key' => '',
    'email_expediteur' => 'noreply@imprixo.fr',
    'email_notifications' => 'admin@imprixo.fr'
];

// Charger depuis fichier JSON
$paramsFile = __DIR__ . '/../config/parametres.json';
if (file_exists($paramsFile)) {
    $savedParams = json_decode(file_get_contents($paramsFile), true);
    if ($savedParams) {
        $params = array_merge($params, $savedParams);
    }
}

// Sauvegarder les paramètres
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_params') {
    $newParams = [
        'site_nom' => cleanInput($_POST['site_nom']),
        'site_email' => cleanInput($_POST['site_email']),
        'site_telephone' => cleanInput($_POST['site_telephone']),
        'site_adresse' => cleanInput($_POST['site_adresse']),
        'tva_taux' => (float)$_POST['tva_taux'],
        'livraison_gratuite_seuil' => (float)$_POST['livraison_gratuite_seuil'],
        'delai_livraison_standard' => (int)$_POST['delai_livraison_standard'],
        'fonds_perdu_cm' => (float)$_POST['fonds_perdu_cm'],
        'zone_securite_cm' => (float)$_POST['zone_securite_cm'],
        'min_commande_ht' => (float)$_POST['min_commande_ht'],
        'stripe_public_key' => cleanInput($_POST['stripe_public_key']),
        'stripe_secret_key' => cleanInput($_POST['stripe_secret_key']),
        'email_expediteur' => cleanInput($_POST['email_expediteur']),
        'email_notifications' => cleanInput($_POST['email_notifications'])
    ];

    // Créer le dossier config s'il n'existe pas
    $configDir = __DIR__ . '/../config';
    if (!is_dir($configDir)) {
        mkdir($configDir, 0755, true);
    }

    if (file_put_contents($paramsFile, json_encode($newParams, JSON_PRETTY_PRINT))) {
        $params = $newParams;
        $success = 'Paramètres sauvegardés avec succès';
        logAdminAction($admin['id'], 'update_params', 'Mise à jour des paramètres');
    } else {
        $error = 'Erreur lors de la sauvegarde';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres - Imprixo Admin</title>
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

        .section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #ecf0f1;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
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
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
            font-family: inherit;
        }

        .form-group .help-text {
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 5px;
        }

        .btn {
            padding: 14px 28px;
            border-radius: 8px;
            border: none;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
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
            <li><a href="clients.php">👥 Clients</a></li>
            <li><a href="parametres.php" class="active">⚙️ Paramètres</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1 style="font-size: 32px; font-weight: 900; margin-bottom: 30px;">⚙️ Paramètres du site</h1>

        <?php if ($success): ?>
            <div class="success-message">✓ <?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error-message">✗ <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="action" value="save_params">

            <!-- Informations générales -->
            <div class="section">
                <h2 class="section-title">🏢 Informations générales</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nom du site</label>
                        <input type="text" name="site_nom" value="<?php echo htmlspecialchars($params['site_nom']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Email de contact</label>
                        <input type="email" name="site_email" value="<?php echo htmlspecialchars($params['site_email']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="tel" name="site_telephone" value="<?php echo htmlspecialchars($params['site_telephone']); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Adresse complète</label>
                    <textarea name="site_adresse"><?php echo htmlspecialchars($params['site_adresse']); ?></textarea>
                </div>
            </div>

            <!-- Paramètres commerciaux -->
            <div class="section">
                <h2 class="section-title">💰 Paramètres commerciaux</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Taux TVA (%)</label>
                        <input type="number" name="tva_taux" value="<?php echo $params['tva_taux']; ?>" step="0.01" required>
                        <div class="help-text">Taux de TVA appliqué (généralement 20%)</div>
                    </div>

                    <div class="form-group">
                        <label>Seuil livraison gratuite (€)</label>
                        <input type="number" name="livraison_gratuite_seuil" value="<?php echo $params['livraison_gratuite_seuil']; ?>" step="0.01" required>
                        <div class="help-text">Commande minimum pour livraison gratuite</div>
                    </div>

                    <div class="form-group">
                        <label>Délai livraison standard (jours)</label>
                        <input type="number" name="delai_livraison_standard" value="<?php echo $params['delai_livraison_standard']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Commande minimum HT (€)</label>
                        <input type="number" name="min_commande_ht" value="<?php echo $params['min_commande_ht']; ?>" step="0.01" required>
                        <div class="help-text">Montant minimum de commande</div>
                    </div>
                </div>
            </div>

            <!-- Paramètres techniques impression -->
            <div class="section">
                <h2 class="section-title">🎨 Paramètres techniques impression</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Fonds perdus (cm)</label>
                        <input type="number" name="fonds_perdu_cm" value="<?php echo $params['fonds_perdu_cm']; ?>" step="0.1" required>
                        <div class="help-text">Standard: 0.3 cm (3mm) de chaque côté</div>
                    </div>

                    <div class="form-group">
                        <label>Zone de sécurité (cm)</label>
                        <input type="number" name="zone_securite_cm" value="<?php echo $params['zone_securite_cm']; ?>" step="0.1" required>
                        <div class="help-text">Standard: 0.3 cm (3mm) depuis le bord</div>
                    </div>
                </div>
            </div>

            <!-- Paiement Stripe -->
            <div class="section">
                <h2 class="section-title">💳 Paiement Stripe</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Clé publique Stripe</label>
                        <input type="text" name="stripe_public_key" value="<?php echo htmlspecialchars($params['stripe_public_key']); ?>">
                        <div class="help-text">Commence par pk_...</div>
                    </div>

                    <div class="form-group">
                        <label>Clé secrète Stripe</label>
                        <input type="password" name="stripe_secret_key" value="<?php echo htmlspecialchars($params['stripe_secret_key']); ?>">
                        <div class="help-text">Commence par sk_...</div>
                    </div>
                </div>
            </div>

            <!-- Emails -->
            <div class="section">
                <h2 class="section-title">📧 Configuration email</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Email expéditeur</label>
                        <input type="email" name="email_expediteur" value="<?php echo htmlspecialchars($params['email_expediteur']); ?>" required>
                        <div class="help-text">Email utilisé pour l'envoi des notifications clients</div>
                    </div>

                    <div class="form-group">
                        <label>Email notifications admin</label>
                        <input type="email" name="email_notifications" value="<?php echo htmlspecialchars($params['email_notifications']); ?>" required>
                        <div class="help-text">Email recevant les notifications de commandes</div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">💾 Enregistrer les paramètres</button>
        </form>
    </div>
</body>
</html>
