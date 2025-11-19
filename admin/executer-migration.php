<?php
/**
 * Migration Base de Données COMPLÈTE - Imprixo Admin
 * Crée TOUTES les tables et importe le CSV
 */

require_once __DIR__ . '/auth.php';
verifierAdminConnecte();
$admin = getAdminInfo();

$pageTitle = 'Migration Base de Données';

$success = '';
$error = '';
$logs = [];

// Exécuter la migration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['execute'])) {
    try {
        $db = Database::getInstance();
        $logs[] = '🔧 Début de la migration complète...';

        // 1. Table produits
        $logs[] = '📦 Création table produits...';
        $db->query("
            CREATE TABLE IF NOT EXISTS produits (
                id INT AUTO_INCREMENT PRIMARY KEY,
                id_produit VARCHAR(50) UNIQUE NOT NULL,
                nom_produit VARCHAR(255) NOT NULL,
                sous_titre VARCHAR(255),
                categorie VARCHAR(100) NOT NULL,
                description_courte TEXT,
                description_longue TEXT,
                prix_0_10 DECIMAL(10,2) DEFAULT 0,
                prix_11_50 DECIMAL(10,2) DEFAULT 0,
                prix_51_100 DECIMAL(10,2) DEFAULT 0,
                prix_101_300 DECIMAL(10,2) DEFAULT 0,
                prix_300_plus DECIMAL(10,2) DEFAULT 0,
                poids_m2 VARCHAR(50),
                epaisseur VARCHAR(50),
                format_max_cm VARCHAR(50),
                usage VARCHAR(255),
                duree_vie VARCHAR(100),
                certification VARCHAR(100),
                finition VARCHAR(255),
                delai_standard_jours INT DEFAULT 3,
                actif BOOLEAN DEFAULT 1,
                slug VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_categorie (categorie),
                INDEX idx_actif (actif),
                INDEX idx_slug (slug)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        $logs[] = '✓ Table produits créée';

        // 2. Table finitions_catalogue
        $logs[] = '🎨 Création table finitions_catalogue...';
        $db->query("
            CREATE TABLE IF NOT EXISTS finitions_catalogue (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nom VARCHAR(255) NOT NULL,
                description TEXT,
                categorie VARCHAR(100),
                prix_defaut DECIMAL(10,2) DEFAULT 0,
                type_prix_defaut ENUM('fixe', 'par_m2', 'par_ml', 'pourcentage') DEFAULT 'fixe',
                icone VARCHAR(50),
                actif BOOLEAN DEFAULT 1,
                ordre INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_categorie (categorie),
                INDEX idx_actif (actif)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        $logs[] = '✓ Table finitions_catalogue créée';

        // 3. Table produits_finitions
        $logs[] = '🔗 Création table produits_finitions...';
        $db->query("
            CREATE TABLE IF NOT EXISTS produits_finitions (
                id INT AUTO_INCREMENT PRIMARY KEY,
                produit_id INT NOT NULL,
                finition_catalogue_id INT,
                nom VARCHAR(255) NOT NULL,
                prix DECIMAL(10,2) DEFAULT 0,
                type_prix ENUM('fixe', 'par_m2', 'par_ml', 'pourcentage') DEFAULT 'fixe',
                ordre INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (produit_id) REFERENCES produits(id) ON DELETE CASCADE,
                FOREIGN KEY (finition_catalogue_id) REFERENCES finitions_catalogue(id) ON DELETE SET NULL,
                INDEX idx_produit (produit_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        $logs[] = '✓ Table produits_finitions créée';

        // 4. Table clients
        $logs[] = '👥 Création table clients...';
        $db->query("
            CREATE TABLE IF NOT EXISTS clients (
                id INT AUTO_INCREMENT PRIMARY KEY,
                prenom VARCHAR(100) NOT NULL,
                nom VARCHAR(100) NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                telephone VARCHAR(20),
                entreprise VARCHAR(255),
                siret VARCHAR(50),
                adresse_facturation TEXT,
                code_postal_facturation VARCHAR(10),
                ville_facturation VARCHAR(100),
                pays_facturation VARCHAR(100) DEFAULT 'France',
                password_hash VARCHAR(255),
                actif BOOLEAN DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_email (email)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        $logs[] = '✓ Table clients créée';

        // 5. Table commandes
        $logs[] = '📋 Création table commandes...';
        $db->query("
            CREATE TABLE IF NOT EXISTS commandes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                numero_commande VARCHAR(50) UNIQUE NOT NULL,
                client_id INT,
                client_prenom VARCHAR(100),
                client_nom VARCHAR(100),
                client_email VARCHAR(255),
                client_telephone VARCHAR(20),
                adresse_facturation TEXT,
                code_postal_facturation VARCHAR(10),
                ville_facturation VARCHAR(100),
                pays_facturation VARCHAR(100),
                adresse_livraison TEXT,
                code_postal_livraison VARCHAR(10),
                ville_livraison VARCHAR(100),
                pays_livraison VARCHAR(100),
                total_ht DECIMAL(10,2) DEFAULT 0,
                total_tva DECIMAL(10,2) DEFAULT 0,
                total_ttc DECIMAL(10,2) DEFAULT 0,
                statut ENUM('nouveau', 'confirme', 'en_production', 'expedie', 'livre', 'annule') DEFAULT 'nouveau',
                statut_paiement ENUM('en_attente', 'paye', 'refuse') DEFAULT 'en_attente',
                date_paiement TIMESTAMP NULL,
                transporteur VARCHAR(100),
                numero_suivi VARCHAR(100),
                date_expedition TIMESTAMP NULL,
                notes_admin TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE SET NULL,
                INDEX idx_numero (numero_commande),
                INDEX idx_statut (statut),
                INDEX idx_client (client_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        $logs[] = '✓ Table commandes créée';

        // 6. Table lignes_commande
        $logs[] = '📦 Création table lignes_commande...';
        $db->query("
            CREATE TABLE IF NOT EXISTS lignes_commande (
                id INT AUTO_INCREMENT PRIMARY KEY,
                commande_id INT NOT NULL,
                produit_id INT,
                produit_code VARCHAR(50),
                produit_nom VARCHAR(255),
                produit_categorie VARCHAR(100),
                largeur DECIMAL(10,2),
                hauteur DECIMAL(10,2),
                surface DECIMAL(10,4),
                quantite INT DEFAULT 1,
                impression ENUM('simple', 'double_meme', 'double_different') DEFAULT 'simple',
                prix_unitaire_m2 DECIMAL(10,2),
                prix_ligne_ht DECIMAL(10,2),
                prix_ligne_ttc DECIMAL(10,2),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (commande_id) REFERENCES commandes(id) ON DELETE CASCADE,
                FOREIGN KEY (produit_id) REFERENCES produits(id) ON DELETE SET NULL,
                INDEX idx_commande (commande_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        $logs[] = '✓ Table lignes_commande créée';

        // 7. Table promotions
        $logs[] = '🎁 Création table promotions...';
        $db->query("
            CREATE TABLE IF NOT EXISTS promotions (
                id INT AUTO_INCREMENT PRIMARY KEY,
                produit_id INT NOT NULL,
                type ENUM('pourcentage', 'prix_fixe') DEFAULT 'pourcentage',
                valeur DECIMAL(10,2),
                prix_special DECIMAL(10,2),
                titre VARCHAR(255),
                badge_texte VARCHAR(50),
                badge_couleur VARCHAR(20) DEFAULT '#e63946',
                date_debut TIMESTAMP NULL,
                date_fin TIMESTAMP NULL,
                afficher_countdown BOOLEAN DEFAULT 0,
                actif BOOLEAN DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (produit_id) REFERENCES produits(id) ON DELETE CASCADE,
                INDEX idx_produit (produit_id),
                INDEX idx_actif (actif)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        $logs[] = '✓ Table promotions créée';

        // 8. Table admin_users (vérifier si existe)
        $logs[] = '👤 Vérification table admin_users...';
        $db->query("
            CREATE TABLE IF NOT EXISTS admin_users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(100) UNIQUE NOT NULL,
                password_hash VARCHAR(255) NOT NULL,
                email VARCHAR(255),
                role VARCHAR(50) DEFAULT 'admin',
                actif BOOLEAN DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        $logs[] = '✓ Table admin_users vérifiée';

        // 9. Table admin_logs
        $logs[] = '📝 Création table admin_logs...';
        $db->query("
            CREATE TABLE IF NOT EXISTS admin_logs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                admin_id INT,
                action VARCHAR(100),
                details TEXT,
                ip_address VARCHAR(50),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (admin_id) REFERENCES admin_users(id) ON DELETE SET NULL,
                INDEX idx_admin (admin_id),
                INDEX idx_action (action)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        $logs[] = '✓ Table admin_logs créée';

        // IMPORT CSV vers BDD
        $logs[] = '';
        $logs[] = '📥 IMPORT DU CSV VERS LA BDD...';
        $csvFile = __DIR__ . '/../CATALOGUE_COMPLET_VISUPRINT.csv';

        if (file_exists($csvFile)) {
            $handle = fopen($csvFile, 'r');
            $headers = fgetcsv($handle);
            $imported = 0;
            $updated = 0;

            while ($row = fgetcsv($handle)) {
                if (count($row) >= 8) {
                    $idProduit = $row[0];
                    $nomProduit = $row[1];
                    $categorie = $row[2];

                    // Vérifier si existe
                    $existing = $db->fetchOne("SELECT id FROM produits WHERE id_produit = ?", [$idProduit]);

                    if ($existing) {
                        // Update
                        $db->query("
                            UPDATE produits SET
                                nom_produit = ?,
                                categorie = ?,
                                prix_0_10 = ?,
                                prix_11_50 = ?,
                                prix_51_100 = ?,
                                prix_101_300 = ?,
                                prix_300_plus = ?,
                                updated_at = NOW()
                            WHERE id_produit = ?
                        ", [
                            $nomProduit, $categorie,
                            floatval($row[3]), floatval($row[4]), floatval($row[5]),
                            floatval($row[6]), floatval($row[7]),
                            $idProduit
                        ]);
                        $updated++;
                    } else {
                        // Insert
                        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $nomProduit));
                        $db->query("
                            INSERT INTO produits (
                                id_produit, nom_produit, categorie, slug,
                                prix_0_10, prix_11_50, prix_51_100, prix_101_300, prix_300_plus,
                                actif
                            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)
                        ", [
                            $idProduit, $nomProduit, $categorie, $slug,
                            floatval($row[3]), floatval($row[4]), floatval($row[5]),
                            floatval($row[6]), floatval($row[7])
                        ]);
                        $imported++;
                    }
                }
            }
            fclose($handle);

            $logs[] = "✓ Produits importés: $imported";
            $logs[] = "✓ Produits mis à jour: $updated";
        } else {
            $logs[] = "⚠️ Fichier CSV introuvable";
        }

        $logs[] = '';
        $logs[] = '✅ MIGRATION TERMINÉE AVEC SUCCÈS !';
        $success = 'Migration complète effectuée ! Base de données prête.';

    } catch (Exception $e) {
        $error = 'Erreur lors de la migration : ' . $e->getMessage();
        $logs[] = '✗ ' . $e->getMessage();
    }
}

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
        <h1 class="page-title">🔧 Migration Base de Données COMPLÈTE</h1>
        <p class="page-subtitle">Créer toutes les tables + importer le CSV</p>
    </div>
    <div class="top-bar-actions">
        <a href="/admin/parametres.php" class="btn btn-secondary">← Retour</a>
    </div>
</div>

<?php if (empty($logs)): ?>
    <!-- Avant migration -->
    <div class="card" style="background: linear-gradient(135deg, #fff3cd 0%, #ffe8a1 100%); border-left: 4px solid var(--warning);">
        <h3 style="color: var(--warning); margin-bottom: 12px; font-size: 20px;">⚠️ Migration complète de la base de données</h3>
        <p style="color: var(--text-secondary); margin-bottom: 16px;">
            Cette opération va créer TOUTES les tables nécessaires et importer les produits depuis le CSV.
        </p>
        <ul style="color: var(--text-secondary); margin-left: 20px; line-height: 1.8; margin-bottom: 16px;">
            <li>✅ Création de 9 tables (produits, clients, commandes, finitions, etc.)</li>
            <li>📥 Import automatique du CSV vers la table produits</li>
            <li>🔄 Si les tables existent déjà, elles seront conservées</li>
            <li>⚡ Les produits existants seront mis à jour</li>
        </ul>
    </div>

    <div class="card">
        <h3 style="font-size: 18px; margin-bottom: 16px; color: var(--primary); font-weight: 700;">📋 Tables qui seront créées</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 12px; margin-bottom: 24px;">
            <div style="padding: 12px; background: var(--bg-hover); border-left: 3px solid var(--primary); border-radius: var(--radius-md);">
                <strong>📦 produits</strong> - Catalogue complet
            </div>
            <div style="padding: 12px; background: var(--bg-hover); border-left: 3px solid var(--info); border-radius: var(--radius-md);">
                <strong>🎨 finitions_catalogue</strong> - Finitions globales
            </div>
            <div style="padding: 12px; background: var(--bg-hover); border-left: 3px solid var(--success); border-radius: var(--radius-md);">
                <strong>🔗 produits_finitions</strong> - Liens finitions
            </div>
            <div style="padding: 12px; background: var(--bg-hover); border-left: 3px solid var(--warning); border-radius: var(--radius-md);">
                <strong>👥 clients</strong> - Base clients
            </div>
            <div style="padding: 12px; background: var(--bg-hover); border-left: 3px solid var(--primary); border-radius: var(--radius-md);">
                <strong>📋 commandes</strong> - Gestion commandes
            </div>
            <div style="padding: 12px; background: var(--bg-hover); border-left: 3px solid var(--info); border-radius: var(--radius-md);">
                <strong>📦 lignes_commande</strong> - Détails commandes
            </div>
            <div style="padding: 12px; background: var(--bg-hover); border-left: 3px solid var(--success); border-radius: var(--radius-md);">
                <strong>🎁 promotions</strong> - Promos produits
            </div>
            <div style="padding: 12px; background: var(--bg-hover); border-left: 3px solid var(--warning); border-radius: var(--radius-md);">
                <strong>👤 admin_users</strong> - Admins
            </div>
            <div style="padding: 12px; background: var(--bg-hover); border-left: 3px solid var(--primary); border-radius: var(--radius-md);">
                <strong>📝 admin_logs</strong> - Logs actions
            </div>
        </div>

        <form method="POST">
            <button type="submit" name="execute" value="1" class="btn btn-primary" style="font-size: 16px;"
                    onclick="return confirm('Lancer la migration complète ? Les tables existantes seront conservées.');">
                🚀 Lancer la migration complète
            </button>
        </form>
    </div>

<?php else: ?>
    <!-- Après migration -->
    <div class="card" style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border-left: 4px solid var(--success);">
        <h3 style="color: var(--success); margin-bottom: 12px; font-size: 20px;">✅ Migration terminée</h3>
        <p style="color: var(--text-secondary); margin-bottom: 16px;">
            Votre base de données est maintenant complète et prête à l'emploi !
        </p>

        <div style="background: white; padding: 20px; border-radius: var(--radius-md); font-family: monospace; font-size: 13px; max-height: 500px; overflow-y: auto;">
            <?php foreach ($logs as $log): ?>
                <div style="margin-bottom: 6px; color: var(--text-primary);"><?php echo htmlspecialchars($log); ?></div>
            <?php endforeach; ?>
        </div>

        <div style="margin-top: 24px; display: flex; gap: 12px;">
            <a href="/admin/produits.php" class="btn btn-primary">📦 Voir les produits</a>
            <a href="/admin/index.php" class="btn btn-secondary">← Tableau de bord</a>
        </div>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
