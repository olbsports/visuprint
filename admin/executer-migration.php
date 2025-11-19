<?php
/**
 * Script de migration Base de Données - Imprixo Admin
 * À exécuter UNE SEULE FOIS pour mettre à jour la structure
 */

require_once __DIR__ . '/auth.php';
verifierAdminConnecte();
$admin = getAdminInfo();

$pageTitle = 'Migration Base de Données';

// Sécurité
$migrationTerminee = false; // Passer à true après exécution réussie

$success = '';
$error = '';
$logs = [];

// Exécuter la migration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['execute'])) {
    if ($migrationTerminee) {
        $error = 'Migration déjà effectuée !';
    } else {
        try {
            $db = Database::getInstance();

            $logs[] = '🔧 Début de la migration...';

            // Exemple de migration - adapter selon vos besoins
            // $db->query("ALTER TABLE produits ADD COLUMN nouvelle_colonne VARCHAR(255)");
            // $logs[] = '✓ Table produits mise à jour';

            $logs[] = '✓ Migration terminée avec succès !';
            $success = 'Migration effectuée avec succès !';
            $migrationTerminee = true;

        } catch (Exception $e) {
            $error = 'Erreur lors de la migration : ' . $e->getMessage();
            $logs[] = '✗ ' . $e->getMessage();
        }
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
        <h1 class="page-title">🔧 Migration Base de Données</h1>
        <p class="page-subtitle">Mettre à jour la structure de la base de données</p>
    </div>
    <div class="top-bar-actions">
        <a href="/admin/parametres.php" class="btn btn-secondary">← Retour</a>
    </div>
</div>

<?php if (!$migrationTerminee): ?>
    <!-- Avant migration -->
    <div class="card" style="background: linear-gradient(135deg, #fff3cd 0%, #ffe8a1 100%); border-left: 4px solid var(--warning);">
        <h3 style="color: var(--warning); margin-bottom: 12px; font-size: 20px;">⚠️ Attention</h3>
        <p style="color: var(--text-secondary); margin-bottom: 16px;">
            Cette opération va modifier la structure de votre base de données.
        </p>
        <ul style="color: var(--text-secondary); margin-left: 20px; line-height: 1.8; margin-bottom: 16px;">
            <li>Faites une sauvegarde complète avant de continuer</li>
            <li>Ne pas interrompre le processus une fois lancé</li>
            <li>Cette migration ne peut être exécutée qu'une seule fois</li>
        </ul>
    </div>

    <div class="card">
        <h3 style="font-size: 18px; margin-bottom: 16px; color: var(--primary); font-weight: 700;">📋 Détails de la migration</h3>
        <p style="color: var(--text-secondary); margin-bottom: 16px;">
            Cette migration va appliquer les modifications suivantes à votre base de données :
        </p>
        <ul style="color: var(--text-secondary); margin-left: 20px; line-height: 1.8; margin-bottom: 24px;">
            <li>Mise à jour de la structure des tables</li>
            <li>Ajout de nouvelles colonnes si nécessaire</li>
            <li>Optimisation des index</li>
        </ul>

        <form method="POST">
            <button type="submit" name="execute" value="1" class="btn btn-primary" onclick="return confirm('Êtes-vous sûr de vouloir exécuter la migration ? Assurez-vous d\'avoir fait une sauvegarde !');">
                🚀 Exécuter la migration
            </button>
        </form>
    </div>

<?php else: ?>
    <!-- Après migration -->
    <div class="card" style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border-left: 4px solid var(--success);">
        <h3 style="color: var(--success); margin-bottom: 12px; font-size: 20px;">✓ Migration terminée</h3>
        <p style="color: var(--text-secondary); margin-bottom: 16px;">
            La migration a été exécutée avec succès. Votre base de données est à jour.
        </p>

        <?php if (!empty($logs)): ?>
            <div style="background: white; padding: 16px; border-radius: var(--radius-md); font-family: monospace; font-size: 13px; margin-top: 16px;">
                <?php foreach ($logs as $log): ?>
                    <div style="margin-bottom: 4px;"><?php echo htmlspecialchars($log); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div style="margin-top: 24px;">
            <a href="/admin/index.php" class="btn btn-primary">← Retour au tableau de bord</a>
        </div>
    </div>
<?php endif; ?>

<div class="card" style="background: var(--bg-hover); border-left: 4px solid var(--info);">
    <h3 style="color: var(--info); margin-bottom: 12px; font-size: 18px;">💡 Informations</h3>
    <p style="color: var(--text-secondary); font-size: 14px;">
        Cette page permet d'exécuter les scripts de migration nécessaires lors de mises à jour importantes.
        En cas de problème, restaurez votre sauvegarde et contactez le support.
    </p>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
