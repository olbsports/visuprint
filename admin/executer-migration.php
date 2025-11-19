<?php
/**
 * Script de migration - Ajout des nouvelles fonctionnalités
 * À exécuter UNE SEULE FOIS pour mettre à jour la base de données
 */

require_once __DIR__ . '/../api/config.php';

// Sécurité: accès restreint en développement
$allowed = true; // Mettre à false après migration

if (!$allowed) {
    die('❌ Migration déjà effectuée. Supprimez ce fichier pour plus de sécurité.');
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Migration Base de Données - Imprixo</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            color: #667eea;
            font-size: 32px;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 16px;
        }
        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
        }
        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
        }
        .success-box {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .error-box {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .btn {
            display: inline-block;
            padding: 15px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s;
        }
        .btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .log {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            max-height: 400px;
            overflow-y: auto;
            margin-top: 20px;
        }
        .log-item {
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .log-item:last-child {
            border-bottom: none;
        }
        .log-success { color: #28a745; }
        .log-error { color: #dc3545; }
        .log-info { color: #2196F3; }
        ul {
            margin-left: 20px;
            line-height: 1.8;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔄 Migration Base de Données</h1>
        <p class="subtitle">Mise à jour vers la nouvelle version avec finitions et promotions</p>

        <?php if (!isset($_GET['execute'])): ?>

            <div class="info-box">
                <strong>ℹ️ Cette migration va ajouter :</strong>
                <ul>
                    <li>Nouvelles colonnes à la table <code>produits</code> (image_url, actif, nouveau, best_seller, SEO, dates)</li>
                    <li>Table <code>produits_finitions</code> pour les options configurateur</li>
                    <li>Table <code>promotions</code> pour les promotions avec countdown</li>
                    <li>Table <code>produits_formats</code> pour les formats prédéfinis</li>
                    <li>Table <code>produits_historique</code> pour l'historique des modifications</li>
                    <li>Table <code>admin_users</code> pour les comptes admin</li>
                    <li>Vue <code>v_produits_avec_promos</code> pour les calculs automatiques</li>
                    <li>Finitions par défaut selon les catégories (PVC, Alu, Bâche, Textile)</li>
                </ul>
            </div>

            <div class="warning-box">
                <strong>⚠️ IMPORTANT :</strong>
                <ul>
                    <li>Vos produits existants <strong>NE SERONT PAS SUPPRIMÉS</strong></li>
                    <li>Seules les nouvelles colonnes et tables seront ajoutées</li>
                    <li>Un compte admin sera créé : <code>admin@imprixo.com</code> / <code>admin123</code></li>
                    <li>Pensez à faire un backup avant de lancer la migration (recommandé)</li>
                </ul>
            </div>

            <form method="GET" style="margin-top: 30px;">
                <input type="hidden" name="execute" value="1">
                <button type="submit" class="btn">🚀 Lancer la migration</button>
            </form>

        <?php else: ?>

            <?php
            // Exécuter la migration
            $db = Database::getInstance();
            $sqlFile = __DIR__ . '/migration-update-database.sql';

            if (!file_exists($sqlFile)) {
                echo '<div class="error-box">❌ Fichier migration-update-database.sql non trouvé !</div>';
                exit;
            }

            $sql = file_get_contents($sqlFile);
            $statements = array_filter(array_map('trim', explode(';', $sql)));

            echo '<div class="log">';
            echo '<div class="log-item log-info"><strong>📋 Début de la migration...</strong></div>';

            $success = 0;
            $errors = 0;

            foreach ($statements as $statement) {
                // Ignorer les commentaires et lignes vides
                if (empty($statement) || strpos($statement, '--') === 0) {
                    continue;
                }

                try {
                    $db->query($statement);

                    // Extraire le type d'action pour l'affichage
                    if (stripos($statement, 'CREATE TABLE') !== false) {
                        preg_match('/CREATE TABLE.*?`([^`]+)`/i', $statement, $matches);
                        $table = $matches[1] ?? 'inconnue';
                        echo '<div class="log-item log-success">✓ Table créée : ' . htmlspecialchars($table) . '</div>';
                    } elseif (stripos($statement, 'ALTER TABLE') !== false) {
                        preg_match('/ALTER TABLE.*?`([^`]+)`/i', $statement, $matches);
                        $table = $matches[1] ?? 'inconnue';
                        echo '<div class="log-item log-success">✓ Table modifiée : ' . htmlspecialchars($table) . '</div>';
                    } elseif (stripos($statement, 'CREATE VIEW') !== false) {
                        echo '<div class="log-item log-success">✓ Vue créée : v_produits_avec_promos</div>';
                    } elseif (stripos($statement, 'INSERT') !== false) {
                        preg_match('/INSERT.*?INTO.*?`([^`]+)`/i', $statement, $matches);
                        $table = $matches[1] ?? 'inconnue';
                        echo '<div class="log-item log-success">✓ Données insérées dans : ' . htmlspecialchars($table) . '</div>';
                    }

                    $success++;
                } catch (Exception $e) {
                    // Certaines erreurs sont normales (table existe déjà, etc.)
                    if (stripos($e->getMessage(), 'already exists') === false &&
                        stripos($e->getMessage(), 'Duplicate') === false) {
                        echo '<div class="log-item log-error">✗ Erreur : ' . htmlspecialchars($e->getMessage()) . '</div>';
                        $errors++;
                    }
                }
            }

            echo '<div class="log-item log-info"><strong>📊 Résumé :</strong></div>';
            echo '<div class="log-item log-success">✓ Opérations réussies : ' . $success . '</div>';
            if ($errors > 0) {
                echo '<div class="log-item log-error">✗ Erreurs : ' . $errors . '</div>';
            }
            echo '<div class="log-item log-info"><strong>🎉 Migration terminée !</strong></div>';
            echo '</div>';

            // Vérifier le nombre de produits
            try {
                $count = $db->fetchOne("SELECT COUNT(*) as count FROM produits");
                echo '<div class="success-box">';
                echo '<strong>✅ Migration réussie !</strong><br><br>';
                echo '📦 Produits dans la base : <strong>' . $count['count'] . '</strong><br>';
                echo '🎨 Finitions par défaut créées selon les catégories<br>';
                echo '👤 Compte admin : <code>admin@imprixo.com</code> / <code>admin123</code><br><br>';
                echo '<strong>⚠️ N\'oubliez pas de changer le mot de passe admin !</strong>';
                echo '</div>';

                // Compter les finitions créées
                $finitions = $db->fetchOne("SELECT COUNT(*) as count FROM produits_finitions");
                if ($finitions['count'] > 0) {
                    echo '<div class="info-box">';
                    echo '<strong>🎨 Finitions créées : ' . $finitions['count'] . '</strong><br>';
                    echo 'Les finitions par défaut ont été ajoutées à vos produits selon leur catégorie.';
                    echo '</div>';
                }

            } catch (Exception $e) {
                echo '<div class="error-box">❌ Erreur lors de la vérification : ' . htmlspecialchars($e->getMessage()) . '</div>';
            }

            echo '<div style="margin-top: 30px;">';
            echo '<a href="produits.php" class="btn">📦 Voir mes produits</a> ';
            echo '<a href="index.php" class="btn" style="background: #28a745;">🏠 Dashboard Admin</a>';
            echo '</div>';
            ?>

        <?php endif; ?>

        <div style="margin-top: 40px; padding-top: 20px; border-top: 2px solid #e0e0e0; color: #666; font-size: 14px;">
            <strong>💡 Prochaines étapes :</strong>
            <ol style="margin-left: 20px; margin-top: 10px; line-height: 1.8;">
                <li>Connectez-vous à l'admin : <code>admin@imprixo.com</code> / <code>admin123</code></li>
                <li>Changez le mot de passe admin dans Paramètres</li>
                <li>Vérifiez vos produits dans la liste</li>
                <li>Ajoutez des images URL aux produits</li>
                <li>Configurez les finitions personnalisées si besoin</li>
                <li>Créez vos premières promotions !</li>
                <li><strong>Supprimez ce fichier après migration</strong> pour la sécurité</li>
            </ol>
        </div>
    </div>
</body>
</html>
