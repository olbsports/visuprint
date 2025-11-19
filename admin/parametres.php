<?php
/**
 * Paramètres - Imprixo Admin
 */

require_once __DIR__ . '/auth.php';

verifierAdminConnecte();
$admin = getAdminInfo();
$db = Database::getInstance();

$pageTitle = 'Paramètres';

$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';

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
        <h1 class="page-title">⚙️ Paramètres</h1>
        <p class="page-subtitle">Configuration de votre administration</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 20px;">
    <!-- Mon compte -->
    <div class="card" style="border-left: 4px solid var(--primary);">
        <h2 style="font-size: 20px; margin-bottom: 16px; color: var(--primary);">👤 Mon compte</h2>
        <div style="margin-bottom: 12px;">
            <div style="font-size: 14px; color: var(--text-muted); margin-bottom: 4px;">Utilisateur</div>
            <div style="font-weight: 600;"><?php echo htmlspecialchars($admin['username'] ?? 'Admin'); ?></div>
        </div>
        <div style="margin-bottom: 12px;">
            <div style="font-size: 14px; color: var(--text-muted); margin-bottom: 4px;">Nom complet</div>
            <div style="font-weight: 600;"><?php echo htmlspecialchars(($admin['prenom'] ?? '') . ' ' . ($admin['nom'] ?? '')); ?></div>
        </div>
        <div style="margin-bottom: 20px;">
            <div style="font-size: 14px; color: var(--text-muted); margin-bottom: 4px;">Rôle</div>
            <span class="badge badge-info"><?php echo ucfirst($admin['role'] ?? 'Admin'); ?></span>
        </div>
        <a href="#" class="btn btn-secondary btn-sm">Modifier mon profil</a>
    </div>

    <!-- Base de données -->
    <div class="card" style="border-left: 4px solid var(--info);">
        <h2 style="font-size: 20px; margin-bottom: 16px; color: var(--info);">💾 Base de données</h2>
        <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 16px;">
            Migrer la base de données vers la dernière version
        </p>
        <a href="/admin/executer-migration.php" class="btn btn-info btn-sm">
            🔄 Exécuter la migration
        </a>
    </div>

    <!-- Génération pages -->
    <div class="card" style="border-left: 4px solid var(--warning);">
        <h2 style="font-size: 20px; margin-bottom: 16px; color: var(--warning);">🔨 Génération HTML</h2>
        <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 16px;">
            Générer les pages HTML des produits pour le site
        </p>
        <a href="/admin/generer-pages-produits-html.php" class="btn btn-warning btn-sm">
            📄 Générer les pages
        </a>
    </div>

    <!-- Finitions -->
    <div class="card" style="border-left: 4px solid var(--success);">
        <h2 style="font-size: 20px; margin-bottom: 16px; color: var(--success);">🎨 Finitions</h2>
        <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 16px;">
            Gérer le catalogue global de finitions
        </p>
        <a href="/admin/finitions-catalogue.php" class="btn btn-success btn-sm">
            Gérer les finitions
        </a>
    </div>
</div>

<!-- Informations système -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">ℹ️ Informations système</h2>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
        <div>
            <div style="font-size: 14px; color: var(--text-muted); margin-bottom: 4px;">Nom de l'application</div>
            <div style="font-weight: 600;">Imprixo Admin</div>
        </div>
        <div>
            <div style="font-size: 14px; color: var(--text-muted); margin-bottom: 4px;">Version PHP</div>
            <div style="font-weight: 600;"><?php echo phpversion(); ?></div>
        </div>
        <div>
            <div style="font-size: 14px; color: var(--text-muted); margin-bottom: 4px;">Base de données</div>
            <div style="font-weight: 600;">MySQL (ispy2055_imprixo_ecommerce)</div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
