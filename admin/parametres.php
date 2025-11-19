<?php
/**
 * Paramètres & Réglages - Imprixo Admin
 */

require_once __DIR__ . '/auth.php';

verifierAdminConnecte();
$admin = getAdminInfo();
$db = Database::getInstance();

$pageTitle = 'Paramètres & Réglages';

$success = '';
$error = '';

// Paramètres par défaut
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
    'email_notifications' => 'admin@imprixo.fr',
    'maintenance_mode' => 0,
    'inscription_active' => 1,
    'remise_quantite_active' => 1
];

// Charger depuis JSON
$paramsFile = __DIR__ . '/../config/parametres.json';
if (file_exists($paramsFile)) {
    $savedParams = json_decode(file_get_contents($paramsFile), true);
    if ($savedParams) {
        $params = array_merge($params, $savedParams);
    }
}

// Sauvegarder
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'save_params':
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
                'email_notifications' => cleanInput($_POST['email_notifications']),
                'maintenance_mode' => isset($_POST['maintenance_mode']) ? 1 : 0,
                'inscription_active' => isset($_POST['inscription_active']) ? 1 : 0,
                'remise_quantite_active' => isset($_POST['remise_quantite_active']) ? 1 : 0
            ];

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
            break;

        case 'change_password':
            $currentPwd = $_POST['current_password'] ?? '';
            $newPwd = $_POST['new_password'] ?? '';
            $confirmPwd = $_POST['confirm_password'] ?? '';

            if (empty($currentPwd) || empty($newPwd) || empty($confirmPwd)) {
                $error = 'Tous les champs sont obligatoires';
            } elseif ($newPwd !== $confirmPwd) {
                $error = 'Les mots de passe ne correspondent pas';
            } elseif (strlen($newPwd) < 8) {
                $error = 'Le mot de passe doit contenir au moins 8 caractères';
            } else {
                $adminData = $db->fetchOne("SELECT * FROM admin_users WHERE id = ?", [$admin['id']]);
                if ($adminData && password_verify($currentPwd, $adminData['password_hash'])) {
                    $newHash = password_hash($newPwd, PASSWORD_DEFAULT);
                    $db->query("UPDATE admin_users SET password_hash = ? WHERE id = ?", [$newHash, $admin['id']]);
                    $success = 'Mot de passe modifié avec succès';
                    logAdminAction($admin['id'], 'change_password', 'Changement de mot de passe');
                } else {
                    $error = 'Mot de passe actuel incorrect';
                }
            }
            break;
    }
}

// Stats
$stats = [
    'produits' => $db->fetchOne("SELECT COUNT(*) as count FROM produits WHERE actif = 1")['count'],
    'clients' => $db->fetchOne("SELECT COUNT(*) as count FROM clients")['count'],
    'commandes' => $db->fetchOne("SELECT COUNT(*) as count FROM commandes")['count'],
    'commandes_mois' => $db->fetchOne("SELECT COUNT(*) as count FROM commandes WHERE MONTH(created_at) = MONTH(NOW())")['count']
];

include __DIR__ . '/includes/header.php';
?>

<?php if ($success): ?>
    <div class="alert alert-success">✓ <?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error">✗ <?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="top-bar">
    <div>
        <h1 class="page-title">⚙️ Paramètres & Réglages</h1>
        <p class="page-subtitle">Configuration globale du site</p>
    </div>
</div>

<!-- Stats rapides -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 24px;">
    <div class="card" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; border: none;">
        <div style="font-size: 14px; opacity: 0.9;">Produits actifs</div>
        <div style="font-size: 36px; font-weight: 700; margin: 8px 0;"><?php echo $stats['produits']; ?></div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, var(--success) 0%, #059669 100%); color: white; border: none;">
        <div style="font-size: 14px; opacity: 0.9;">Clients</div>
        <div style="font-size: 36px; font-weight: 700; margin: 8px 0;"><?php echo $stats['clients']; ?></div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, var(--info) 0%, #0284c7 100%); color: white; border: none;">
        <div style="font-size: 14px; opacity: 0.9;">Commandes totales</div>
        <div style="font-size: 36px; font-weight: 700; margin: 8px 0;"><?php echo $stats['commandes']; ?></div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%); color: white; border: none;">
        <div style="font-size: 14px; opacity: 0.9;">Commandes ce mois</div>
        <div style="font-size: 36px; font-weight: 700; margin: 8px 0;"><?php echo $stats['commandes_mois']; ?></div>
    </div>
</div>

<!-- Onglets -->
<div style="margin-bottom: 24px;">
    <div style="display: flex; gap: 12px; border-bottom: 2px solid var(--border);">
        <button class="tab-btn active" onclick="showTab('general')">🏢 Général</button>
        <button class="tab-btn" onclick="showTab('commerce')">💰 Commerce</button>
        <button class="tab-btn" onclick="showTab('technique')">⚙️ Technique</button>
        <button class="tab-btn" onclick="showTab('paiement')">💳 Paiement</button>
        <button class="tab-btn" onclick="showTab('email')">📧 Emails</button>
        <button class="tab-btn" onclick="showTab('compte')">👤 Mon compte</button>
        <button class="tab-btn" onclick="showTab('outils')">🔧 Outils</button>
    </div>
</div>

<form method="POST">
    <input type="hidden" name="action" value="save_params">

    <!-- Onglet Général -->
    <div id="tab-general" class="tab-content">
        <div class="card">
            <h2 class="card-title">🏢 Informations générales</h2>

            <div class="form-group">
                <label class="form-label">Nom du site</label>
                <input type="text" name="site_nom" class="form-input" value="<?php echo htmlspecialchars($params['site_nom']); ?>">
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Email du site</label>
                    <input type="email" name="site_email" class="form-input" value="<?php echo htmlspecialchars($params['site_email']); ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="site_telephone" class="form-input" value="<?php echo htmlspecialchars($params['site_telephone']); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Adresse complète</label>
                <textarea name="site_adresse" class="form-textarea" rows="3"><?php echo htmlspecialchars($params['site_adresse']); ?></textarea>
            </div>
        </div>
    </div>

    <!-- Onglet Commerce -->
    <div id="tab-commerce" class="tab-content" style="display:none;">
        <div class="card">
            <h2 class="card-title">💰 Paramètres commerciaux</h2>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Taux de TVA (%)</label>
                    <input type="number" step="0.1" name="tva_taux" class="form-input" value="<?php echo $params['tva_taux']; ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Commande minimum HT (€)</label>
                    <input type="number" step="0.01" name="min_commande_ht" class="form-input" value="<?php echo $params['min_commande_ht']; ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Livraison gratuite à partir de (€)</label>
                    <input type="number" step="0.01" name="livraison_gratuite_seuil" class="form-input" value="<?php echo $params['livraison_gratuite_seuil']; ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Délai de livraison standard (jours)</label>
                    <input type="number" name="delai_livraison_standard" class="form-input" value="<?php echo $params['delai_livraison_standard']; ?>">
                </div>
            </div>

            <div style="margin-top: 24px;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" name="remise_quantite_active" value="1" <?php echo $params['remise_quantite_active'] ? 'checked' : ''; ?> style="width: auto;">
                    <span><strong>Activer les remises quantitatives</strong> - Prix dégressifs selon la surface</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Onglet Technique -->
    <div id="tab-technique" class="tab-content" style="display:none;">
        <div class="card">
            <h2 class="card-title">⚙️ Paramètres techniques</h2>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Fonds perdus (cm)</label>
                    <input type="number" step="0.1" name="fonds_perdu_cm" class="form-input" value="<?php echo $params['fonds_perdu_cm']; ?>">
                    <small style="color: var(--text-muted); font-size: 13px;">Marge de sécurité pour la découpe</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Zone de sécurité (cm)</label>
                    <input type="number" step="0.1" name="zone_securite_cm" class="form-input" value="<?php echo $params['zone_securite_cm']; ?>">
                    <small style="color: var(--text-muted); font-size: 13px;">Zone sans éléments importants</small>
                </div>
            </div>

            <div style="margin-top: 24px;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; margin-bottom: 12px;">
                    <input type="checkbox" name="maintenance_mode" value="1" <?php echo $params['maintenance_mode'] ? 'checked' : ''; ?> style="width: auto;">
                    <span><strong>Mode maintenance</strong> - Site inaccessible aux visiteurs</span>
                </label>

                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" name="inscription_active" value="1" <?php echo $params['inscription_active'] ? 'checked' : ''; ?> style="width: auto;">
                    <span><strong>Inscription active</strong> - Autoriser les nouvelles inscriptions</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Onglet Paiement -->
    <div id="tab-paiement" class="tab-content" style="display:none;">
        <div class="card">
            <h2 class="card-title">💳 Configuration Stripe</h2>

            <div class="form-group">
                <label class="form-label">Clé publique Stripe</label>
                <input type="text" name="stripe_public_key" class="form-input" value="<?php echo htmlspecialchars($params['stripe_public_key']); ?>" placeholder="pk_test_...">
            </div>

            <div class="form-group">
                <label class="form-label">Clé secrète Stripe</label>
                <input type="password" name="stripe_secret_key" class="form-input" value="<?php echo htmlspecialchars($params['stripe_secret_key']); ?>" placeholder="sk_test_...">
                <small style="color: var(--text-muted); font-size: 13px;">⚠️ Gardez cette clé confidentielle</small>
            </div>
        </div>
    </div>

    <!-- Onglet Email -->
    <div id="tab-email" class="tab-content" style="display:none;">
        <div class="card">
            <h2 class="card-title">📧 Configuration emails</h2>

            <div class="form-group">
                <label class="form-label">Email expéditeur</label>
                <input type="email" name="email_expediteur" class="form-input" value="<?php echo htmlspecialchars($params['email_expediteur']); ?>">
                <small style="color: var(--text-muted); font-size: 13px;">Email utilisé pour l'envoi automatique</small>
            </div>

            <div class="form-group">
                <label class="form-label">Email notifications</label>
                <input type="email" name="email_notifications" class="form-input" value="<?php echo htmlspecialchars($params['email_notifications']); ?>">
                <small style="color: var(--text-muted); font-size: 13px;">Email recevant les notifications de commandes</small>
            </div>
        </div>
    </div>

    <!-- Bouton de sauvegarde (visible dans tous les onglets sauf Compte et Outils) -->
    <div class="save-params-btn">
        <button type="submit" class="btn btn-primary" style="font-size: 16px;">
            💾 Sauvegarder les paramètres
        </button>
    </div>
</form>

<!-- Onglet Mon compte -->
<div id="tab-compte" class="tab-content" style="display:none;">
    <div class="card">
        <h2 class="card-title">👤 Mon compte administrateur</h2>

        <div style="margin-bottom: 24px; padding: 16px; background: var(--bg-hover); border-radius: var(--radius-md);">
            <div style="margin-bottom: 8px;"><strong>Utilisateur:</strong> <?php echo htmlspecialchars($admin['username']); ?></div>
            <div style="margin-bottom: 8px;"><strong>Rôle:</strong> <?php echo htmlspecialchars($admin['role']); ?></div>
            <div><strong>Dernière connexion:</strong> <?php echo date('d/m/Y H:i'); ?></div>
        </div>

        <form method="POST">
            <input type="hidden" name="action" value="change_password">

            <h3 style="font-size: 18px; margin-bottom: 16px; color: var(--primary);">🔒 Changer le mot de passe</h3>

            <div class="form-group">
                <label class="form-label">Mot de passe actuel</label>
                <input type="password" name="current_password" class="form-input" required>
            </div>

            <div class="form-group">
                <label class="form-label">Nouveau mot de passe</label>
                <input type="password" name="new_password" class="form-input" required minlength="8">
                <small style="color: var(--text-muted); font-size: 13px;">Minimum 8 caractères</small>
            </div>

            <div class="form-group">
                <label class="form-label">Confirmer le nouveau mot de passe</label>
                <input type="password" name="confirm_password" class="form-input" required>
            </div>

            <button type="submit" class="btn btn-primary">Modifier le mot de passe</button>
        </form>
    </div>
</div>

<!-- Onglet Outils -->
<div id="tab-outils" class="tab-content" style="display:none;">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
        <div class="card" style="border-left: 4px solid var(--primary);">
            <h3 style="font-size: 18px; margin-bottom: 12px; color: var(--primary);">🔨 Génération HTML</h3>
            <p style="color: var(--text-secondary); margin-bottom: 16px; font-size: 14px;">
                Générer des pages produits HTML statiques optimisées SEO
            </p>
            <a href="/admin/generer-pages-produits-html.php" class="btn btn-primary btn-sm">Lancer</a>
        </div>

        <div class="card" style="border-left: 4px solid var(--info);">
            <h3 style="font-size: 18px; margin-bottom: 12px; color: var(--info);">🔄 Migration BDD</h3>
            <p style="color: var(--text-secondary); margin-bottom: 16px; font-size: 14px;">
                Exécuter les scripts de migration de la base de données
            </p>
            <a href="/admin/executer-migration.php" class="btn btn-info btn-sm">Exécuter</a>
        </div>

        <div class="card" style="border-left: 4px solid var(--success);">
            <h3 style="font-size: 18px; margin-bottom: 12px; color: var(--success);">📥 Import CSV</h3>
            <p style="color: var(--text-secondary); margin-bottom: 16px; font-size: 14px;">
                Importer les produits depuis le fichier CSV vers la BDD
            </p>
            <a href="/admin/import-csv-to-database.php" class="btn btn-success btn-sm">Importer</a>
        </div>

        <div class="card" style="border-left: 4px solid var(--warning);">
            <h3 style="font-size: 18px; margin-bottom: 12px; color: var(--warning);">🎨 Finitions V2</h3>
            <p style="color: var(--text-secondary); margin-bottom: 16px; font-size: 14px;">
                Gérer le catalogue global des finitions disponibles
            </p>
            <a href="/admin/finitions-catalogue.php" class="btn btn-warning btn-sm">Gérer</a>
        </div>
    </div>
</div>

<style>
.tab-btn {
    background: none;
    border: none;
    padding: 12px 20px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    color: var(--text-secondary);
    border-bottom: 3px solid transparent;
    transition: all 0.3s;
}

.tab-btn:hover {
    color: var(--primary);
}

.tab-btn.active {
    color: var(--primary);
    border-bottom-color: var(--primary);
}

.save-params-btn {
    margin-top: 24px;
    padding-top: 24px;
    border-top: 2px solid var(--border);
}
</style>

<script>
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(t => t.style.display = 'none');
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));

    // Show selected tab
    document.getElementById('tab-' + tabName).style.display = 'block';
    event.target.classList.add('active');

    // Hide/show save button
    const saveBtn = document.querySelector('.save-params-btn');
    if (tabName === 'compte' || tabName === 'outils') {
        saveBtn.style.display = 'none';
    } else {
        saveBtn.style.display = 'block';
    }
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
