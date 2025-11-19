<?php
/**
 * Gestion Catalogue Finitions - Imprixo Admin
 * Bibliothèque globale de toutes les finitions disponibles
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../api/config.php';

verifierAdminConnecte();
$admin = getAdminInfo();
$db = Database::getInstance();

$pageTitle = 'Catalogue des finitions';

$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';

// Charger toutes les finitions du catalogue
$finitions = [];
try {
    $finitions = $db->fetchAll("SELECT * FROM finitions_catalogue ORDER BY categorie, ordre, nom");
} catch (Exception $e) {
    $error = "Erreur chargement : " . $e->getMessage();
}

// Grouper par catégorie
$finitionsParCategorie = [];
foreach ($finitions as $fin) {
    $cat = $fin['categorie'] ?? 'Autre';
    if (!isset($finitionsParCategorie[$cat])) {
        $finitionsParCategorie[$cat] = [];
    }
    $finitionsParCategorie[$cat][] = $fin;
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
        <h1 class="page-title">🎨 Catalogue des finitions</h1>
        <p class="page-subtitle"><?php echo count($finitions); ?> finition(s) disponibles</p>
    </div>
    <div class="top-bar-actions">
        <a href="/admin/finition-ajouter.php" class="btn btn-success">
            ➕ Nouvelle finition
        </a>
        <a href="/admin/produits.php" class="btn btn-secondary">
            ← Retour aux produits
        </a>
    </div>
</div>

<!-- Info card -->
<div class="card" style="background: linear-gradient(135deg, #fee 0%, #fdd 100%); border-left: 4px solid var(--primary);">
    <h3 style="font-size: 18px; margin-bottom: 12px; color: var(--primary);">💡 Comment ça fonctionne ?</h3>
    <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 12px;">
        Le catalogue est une <strong>bibliothèque globale</strong> de toutes vos finitions.
        Pour activer une finition sur un produit :
    </p>
    <ol style="color: var(--text-secondary); font-size: 14px; margin-left: 20px; line-height: 1.8;">
        <li>Créez-la ici dans le catalogue (une seule fois)</li>
        <li>Allez sur un produit → Éditer</li>
        <li>Cochez les finitions que vous voulez activer</li>
        <li>Personnalisez le prix si besoin</li>
    </ol>
</div>

<?php if (empty($finitions)): ?>
    <div class="card" style="text-align: center; padding: 60px 20px;">
        <div style="font-size: 64px; margin-bottom: 20px;">🎨</div>
        <h2 style="font-size: 24px; margin-bottom: 12px; color: var(--text-primary);">Aucune finition dans le catalogue</h2>
        <p style="color: var(--text-secondary); margin-bottom: 24px;">
            Commencez par créer vos premières finitions pour les utiliser sur vos produits.
        </p>
        <a href="/admin/finition-ajouter.php" class="btn btn-primary">➕ Créer ma première finition</a>
    </div>
<?php else: ?>
    <!-- Finitions par catégorie -->
    <?php foreach ($finitionsParCategorie as $categorie => $finitionsCategorie): ?>
        <div class="card">
            <div class="card-header" style="background: var(--bg-hover); margin: -30px -30px 20px; padding: 20px 30px; border-radius: 12px 12px 0 0;">
                <h2 class="card-title" style="display: flex; align-items: center; gap: 12px;">
                    <span style="font-size: 24px;">📦</span>
                    <?php echo htmlspecialchars($categorie); ?>
                    <span class="badge badge-info" style="margin-left: auto;"><?php echo count($finitionsCategorie); ?> finition(s)</span>
                </h2>
            </div>

            <div style="display: grid; gap: 12px;">
                <?php foreach ($finitionsCategorie as $fin): ?>
                    <div style="display: flex; align-items: center; gap: 16px; padding: 16px; background: var(--bg-hover); border-radius: var(--radius-md); border-left: 4px solid <?php echo $fin['actif'] ? 'var(--primary)' : 'var(--text-muted)'; ?>;">
                        <!-- Icône -->
                        <div style="font-size: 32px; width: 50px; text-align: center;">
                            <?php echo $fin['icone'] ?: '•'; ?>
                        </div>

                        <!-- Infos -->
                        <div style="flex: 1;">
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 4px;">
                                <strong style="font-size: 16px; color: var(--text-primary);">
                                    <?php echo htmlspecialchars($fin['nom']); ?>
                                </strong>
                                <?php if (!$fin['actif']): ?>
                                    <span class="badge" style="background: var(--text-muted); color: white;">Désactivée</span>
                                <?php endif; ?>
                            </div>
                            <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 8px;">
                                <?php echo htmlspecialchars($fin['description'] ?: 'Aucune description'); ?>
                            </div>
                            <div style="font-size: 13px; color: var(--text-muted);">
                                <span>Ordre: <?php echo $fin['ordre']; ?></span>
                                <span style="margin-left: 16px;">ID: <?php echo $fin['id']; ?></span>
                            </div>
                        </div>

                        <!-- Prix -->
                        <div style="text-align: right; min-width: 120px;">
                            <div style="font-size: 24px; font-weight: 700; color: <?php echo $fin['prix_defaut'] < 0 ? 'var(--success)' : 'var(--primary)'; ?>;">
                                <?php echo $fin['prix_defaut'] >= 0 ? '+' : ''; ?><?php echo number_format($fin['prix_defaut'], 2, ',', ' '); ?> €
                            </div>
                            <div style="font-size: 12px; color: var(--text-muted);">
                                <?php
                                $typeLabels = [
                                    'fixe' => 'Prix fixe',
                                    'par_m2' => 'Par m²',
                                    'par_ml' => 'Par mètre linéaire',
                                    'pourcentage' => 'Pourcentage'
                                ];
                                echo $typeLabels[$fin['type_prix_defaut']] ?? $fin['type_prix_defaut'];
                                ?>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div style="display: flex; gap: 8px;">
                            <a href="/admin/finition-editer.php?id=<?php echo $fin['id']; ?>" class="btn btn-primary btn-sm">
                                ✏️ Éditer
                            </a>
                            <a
                                href="/admin/finition-supprimer.php?id=<?php echo $fin['id']; ?>"
                                class="btn btn-danger btn-sm"
                                data-confirm="Supprimer cette finition du catalogue ?"
                            >
                                🗑️
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Actions rapides -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
    <div class="card" style="border-left: 4px solid var(--primary);">
        <h3 style="font-size: 18px; margin-bottom: 12px; color: var(--primary);">➕ Ajouter une finition</h3>
        <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 16px;">
            Créez une nouvelle finition dans le catalogue global
        </p>
        <a href="/admin/finition-ajouter.php" class="btn btn-success btn-sm">Créer une finition</a>
    </div>

    <div class="card" style="border-left: 4px solid var(--info);">
        <h3 style="font-size: 18px; margin-bottom: 12px; color: var(--info);">📦 Utiliser sur un produit</h3>
        <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 16px;">
            Activez ces finitions sur vos produits
        </p>
        <a href="/admin/produits.php" class="btn btn-secondary btn-sm">Voir les produits</a>
    </div>

    <div class="card" style="border-left: 4px solid var(--warning);">
        <h3 style="font-size: 18px; margin-bottom: 12px; color: var(--warning);">📊 Statistiques</h3>
        <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 16px;">
            <strong><?php echo count($finitions); ?></strong> finitions créées<br>
            <strong><?php echo count($finitionsParCategorie); ?></strong> catégories
        </p>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
