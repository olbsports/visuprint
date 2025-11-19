<?php
/**
 * Migration : Paramètres vers BDD - Imprixo Admin
 * Crée la table parametres et importe depuis JSON
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../api/config.php';

verifierAdminConnecte();
$admin = getAdminInfo();
$db = Database::getInstance();

$pageTitle = 'Migration Paramètres → BDD';
include __DIR__ . '/includes/header.php';

$success = [];
$errors = [];

try {
    // 1. Créer la table parametres
    echo "<div class='card'><div class='card-body'>";
    echo "<h2 style='color: var(--primary); margin-bottom: 20px;'>🔧 Migration des paramètres vers la BDD</h2>";

    echo "<p style='margin-bottom: 20px;'>📦 Création de la table <code>parametres</code>...</p>";

    $db->query("
        CREATE TABLE IF NOT EXISTS parametres (
            id INT AUTO_INCREMENT PRIMARY KEY,
            cle VARCHAR(100) UNIQUE NOT NULL,
            valeur TEXT,
            type ENUM('string', 'int', 'float', 'bool') DEFAULT 'string',
            categorie ENUM('general', 'commerce', 'technique', 'paiement', 'email', 'autre') DEFAULT 'autre',
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_cle (cle),
            INDEX idx_categorie (categorie)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    $success[] = "✅ Table `parametres` créée avec succès";

    // 2. Importer depuis JSON si existe
    $paramsFile = __DIR__ . '/../config/parametres.json';
    $imported = 0;

    if (file_exists($paramsFile)) {
        echo "<p style='margin: 20px 0;'>📄 Import depuis <code>parametres.json</code>...</p>";

        $jsonParams = json_decode(file_get_contents($paramsFile), true);

        if ($jsonParams) {
            // Définition des paramètres avec leurs métadonnées
            $paramsDefinitions = [
                // Général
                'site_nom' => ['type' => 'string', 'categorie' => 'general', 'description' => 'Nom du site'],
                'site_email' => ['type' => 'string', 'categorie' => 'general', 'description' => 'Email de contact'],
                'site_telephone' => ['type' => 'string', 'categorie' => 'general', 'description' => 'Téléphone de contact'],
                'site_adresse' => ['type' => 'string', 'categorie' => 'general', 'description' => 'Adresse physique'],

                // Commerce
                'tva_taux' => ['type' => 'float', 'categorie' => 'commerce', 'description' => 'Taux de TVA (%)'],
                'livraison_gratuite_seuil' => ['type' => 'float', 'categorie' => 'commerce', 'description' => 'Seuil livraison gratuite (€)'],
                'min_commande_ht' => ['type' => 'float', 'categorie' => 'commerce', 'description' => 'Montant minimum commande HT (€)'],

                // Technique
                'delai_livraison_standard' => ['type' => 'int', 'categorie' => 'technique', 'description' => 'Délai de livraison standard (jours)'],
                'fonds_perdu_cm' => ['type' => 'float', 'categorie' => 'technique', 'description' => 'Fonds perdu (cm)'],
                'zone_securite_cm' => ['type' => 'float', 'categorie' => 'technique', 'description' => 'Zone de sécurité (cm)'],
                'maintenance_mode' => ['type' => 'bool', 'categorie' => 'technique', 'description' => 'Mode maintenance'],

                // Paiement
                'stripe_public_key' => ['type' => 'string', 'categorie' => 'paiement', 'description' => 'Clé publique Stripe'],
                'stripe_secret_key' => ['type' => 'string', 'categorie' => 'paiement', 'description' => 'Clé secrète Stripe'],

                // Email
                'email_expediteur' => ['type' => 'string', 'categorie' => 'email', 'description' => 'Email expéditeur'],
                'email_notifications' => ['type' => 'string', 'categorie' => 'email', 'description' => 'Email notifications admin'],

                // Autre
                'inscription_active' => ['type' => 'bool', 'categorie' => 'autre', 'description' => 'Inscription clients activée'],
                'remise_quantite_active' => ['type' => 'bool', 'categorie' => 'autre', 'description' => 'Remise quantité active'],
            ];

            foreach ($jsonParams as $cle => $valeur) {
                $def = $paramsDefinitions[$cle] ?? ['type' => 'string', 'categorie' => 'autre', 'description' => ''];

                // Vérifier si existe déjà
                $existing = $db->fetchOne("SELECT id FROM parametres WHERE cle = ?", [$cle]);

                if ($existing) {
                    // Mettre à jour
                    $db->query(
                        "UPDATE parametres SET valeur = ?, type = ?, categorie = ?, description = ? WHERE cle = ?",
                        [$valeur, $def['type'], $def['categorie'], $def['description'], $cle]
                    );
                } else {
                    // Insérer
                    $db->query(
                        "INSERT INTO parametres (cle, valeur, type, categorie, description) VALUES (?, ?, ?, ?, ?)",
                        [$cle, $valeur, $def['type'], $def['categorie'], $def['description']]
                    );
                    $imported++;
                }
            }

            $success[] = "✅ {$imported} paramètres importés depuis JSON";
        }
    } else {
        echo "<p style='color: var(--warning); margin: 20px 0;'>⚠️ Aucun fichier parametres.json trouvé, création des paramètres par défaut...</p>";

        // Créer paramètres par défaut
        $defaults = [
            ['site_nom', 'Imprixo', 'string', 'general', 'Nom du site'],
            ['site_email', 'contact@imprixo.fr', 'string', 'general', 'Email de contact'],
            ['site_telephone', '01 23 45 67 89', 'string', 'general', 'Téléphone de contact'],
            ['site_adresse', '', 'string', 'general', 'Adresse physique'],
            ['tva_taux', '20', 'float', 'commerce', 'Taux de TVA (%)'],
            ['livraison_gratuite_seuil', '200', 'float', 'commerce', 'Seuil livraison gratuite (€)'],
            ['min_commande_ht', '25', 'float', 'commerce', 'Montant minimum commande HT (€)'],
            ['delai_livraison_standard', '3', 'int', 'technique', 'Délai de livraison standard (jours)'],
            ['fonds_perdu_cm', '0.3', 'float', 'technique', 'Fonds perdu (cm)'],
            ['zone_securite_cm', '0.3', 'float', 'technique', 'Zone de sécurité (cm)'],
            ['maintenance_mode', '0', 'bool', 'technique', 'Mode maintenance'],
            ['stripe_public_key', '', 'string', 'paiement', 'Clé publique Stripe'],
            ['stripe_secret_key', '', 'string', 'paiement', 'Clé secrète Stripe'],
            ['email_expediteur', 'noreply@imprixo.fr', 'string', 'email', 'Email expéditeur'],
            ['email_notifications', 'admin@imprixo.fr', 'string', 'email', 'Email notifications admin'],
            ['inscription_active', '1', 'bool', 'autre', 'Inscription clients activée'],
            ['remise_quantite_active', '1', 'bool', 'autre', 'Remise quantité active'],
        ];

        foreach ($defaults as $param) {
            $existing = $db->fetchOne("SELECT id FROM parametres WHERE cle = ?", [$param[0]]);
            if (!$existing) {
                $db->query(
                    "INSERT INTO parametres (cle, valeur, type, categorie, description) VALUES (?, ?, ?, ?, ?)",
                    $param
                );
                $imported++;
            }
        }

        $success[] = "✅ {$imported} paramètres par défaut créés";
    }

    // 3. Afficher résultat
    echo "<div style='margin-top: 30px; padding: 20px; background: var(--success-bg); border-left: 4px solid var(--success); border-radius: var(--radius-md);'>";
    foreach ($success as $msg) {
        echo "<p style='color: var(--success); font-weight: 600; margin: 8px 0;'>{$msg}</p>";
    }
    echo "</div>";

    // Afficher les paramètres
    echo "<h3 style='margin: 30px 0 20px; color: var(--primary);'>📋 Paramètres en base de données :</h3>";

    $allParams = $db->fetchAll("SELECT * FROM parametres ORDER BY categorie, cle");

    echo "<table style='width: 100%; border-collapse: collapse;'>";
    echo "<thead><tr style='background: var(--bg-hover); border-bottom: 2px solid var(--border);'>";
    echo "<th style='padding: 12px; text-align: left;'>Catégorie</th>";
    echo "<th style='padding: 12px; text-align: left;'>Clé</th>";
    echo "<th style='padding: 12px; text-align: left;'>Valeur</th>";
    echo "<th style='padding: 12px; text-align: left;'>Type</th>";
    echo "</tr></thead><tbody>";

    foreach ($allParams as $p) {
        $valeurAffichee = $p['valeur'];
        if (strlen($valeurAffichee) > 50) {
            $valeurAffichee = substr($valeurAffichee, 0, 50) . '...';
        }
        if (strpos($p['cle'], 'secret') !== false || strpos($p['cle'], 'password') !== false) {
            $valeurAffichee = '••••••••';
        }

        echo "<tr style='border-bottom: 1px solid var(--border);'>";
        echo "<td style='padding: 12px;'><span class='badge badge-info'>{$p['categorie']}</span></td>";
        echo "<td style='padding: 12px; font-family: monospace; font-weight: 600;'>{$p['cle']}</td>";
        echo "<td style='padding: 12px;'>{$valeurAffichee}</td>";
        echo "<td style='padding: 12px;'><code>{$p['type']}</code></td>";
        echo "</tr>";
    }

    echo "</tbody></table>";

    echo "<div style='margin-top: 30px; padding: 20px; background: var(--info-bg); border-left: 4px solid var(--info); border-radius: var(--radius-md);'>";
    echo "<p style='font-weight: 600; margin-bottom: 10px;'>ℹ️ Prochaines étapes :</p>";
    echo "<ol style='margin: 10px 0; padding-left: 20px;'>";
    echo "<li>La page <strong>parametres.php</strong> va maintenant utiliser la BDD</li>";
    echo "<li>L'ancien fichier <code>parametres.json</code> peut être supprimé</li>";
    echo "<li>Tous les paramètres sont sauvegardés avec la BDD automatiquement</li>";
    echo "</ol>";
    echo "</div>";

    echo "<div style='margin-top: 20px;'>";
    echo "<a href='/admin/parametres.php' class='btn btn-primary'>→ Voir les paramètres</a>";
    echo "<a href='/admin/produits.php' class='btn btn-secondary' style='margin-left: 12px;'>← Retour admin</a>";
    echo "</div>";

    echo "</div></div>";

    // Log action
    logAdminAction($admin['id'] ?? 0, 'migration_parametres_bdd', "Migration paramètres vers BDD effectuée");

} catch (Exception $e) {
    echo "<div class='alert alert-error'>✗ Erreur : " . htmlspecialchars($e->getMessage()) . "</div>";
}

include __DIR__ . '/includes/footer.php';
?>
