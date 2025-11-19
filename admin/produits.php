<?php
/**
 * Gestion Produits - Imprixo Admin
 * Lecture depuis BDD
 */

require_once __DIR__ . '/auth.php';

verifierAdminConnecte();
$admin = getAdminInfo();
$db = Database::getInstance();

$pageTitle = 'Gestion des produits';

$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';

// Filtres
$recherche = isset($_GET['q']) ? trim($_GET['q']) : '';
$filtreCategorie = isset($_GET['cat']) ? $_GET['cat'] : '';

// Charger produits depuis BDD
$where = ['p.actif = 1'];
$params = [];

if ($recherche) {
    $where[] = "(code LIKE ? OR nom LIKE ?)";
    $search = '%' . $recherche . '%';
    $params = array_merge($params, [$search, $search]);
}

if ($filtreCategorie) {
    $where[] = "categorie = ?";
    $params[] = $filtreCategorie;
}

$whereClause = 'WHERE ' . implode(' AND ', $where);

try {
    $produits = $db->fetchAll(
        "SELECT p.*,
                pr.id as promo_id,
                pr.badge_texte as promo_badge,
                pr.badge_couleur as promo_couleur,
                pr.valeur as promo_valeur
         FROM produits p
         LEFT JOIN promotions pr ON pr.produit_id = p.id AND pr.actif = 1
             AND (pr.date_debut IS NULL OR pr.date_debut <= NOW())
             AND (pr.date_fin IS NULL OR pr.date_fin >= NOW())
         $whereClause
         ORDER BY categorie, nom",
        $params
    );

    // Récupérer catégories
    $categories = $db->fetchAll("SELECT DISTINCT categorie FROM produits WHERE actif = 1 ORDER BY categorie");
} catch (Exception $e) {
    $error = "Erreur: " . $e->getMessage();
    $produits = [];
    $categories = [];
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
        <h1 class="page-title">📦 Gestion des produits</h1>
        <p class="page-subtitle"><?php echo count($produits); ?> produit(s) dans le catalogue</p>
    </div>
    <div class="top-bar-actions">
        <a href="/admin/nouveau-produit.php" class="btn btn-success">➕ Nouveau produit</a>
        <a href="/admin/finitions-catalogue.php" class="btn btn-primary">🎨 Finitions</a>
    </div>
</div>

<!-- Filtres -->
<div class="card">
    <form method="GET" style="display: flex; gap: 16px; align-items: end; flex-wrap: wrap;">
        <div class="form-group" style="flex: 1; min-width: 250px; margin: 0;">
            <label class="form-label">🔍 Rechercher</label>
            <input type="text" name="q" class="form-input" placeholder="ID, nom..." value="<?php echo htmlspecialchars($recherche); ?>">
        </div>

        <div class="form-group" style="min-width: 200px; margin: 0;">
            <label class="form-label">Catégorie</label>
            <select name="cat" class="form-select">
                <option value="">Toutes</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat['categorie']); ?>" <?php echo $cat['categorie'] === $filtreCategorie ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['categorie']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Filtrer</button>
        <?php if ($recherche || $filtreCategorie): ?>
            <a href="/admin/produits.php" class="btn btn-secondary">Réinitialiser</a>
        <?php endif; ?>
    </form>
</div>

<!-- Liste produits -->
<?php if (empty($produits)): ?>
    <div class="card" style="text-align: center; padding: 60px 20px;">
        <div style="font-size: 64px; margin-bottom: 20px;">📦</div>
        <h2 style="font-size: 24px; margin-bottom: 12px;">Aucun produit trouvé</h2>
        <p style="color: var(--text-secondary); margin-bottom: 24px;">
            <?php if ($recherche || $filtreCategorie): ?>
                Aucun produit ne correspond à vos critères.
            <?php else: ?>
                Le catalogue est vide. <a href="/admin/executer-migration.php" style="color: var(--primary);">Exécutez la migration</a> pour importer les produits.
            <?php endif; ?>
        </p>
    </div>
<?php else: ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px;">
        <?php foreach ($produits as $p): ?>
            <div class="card" style="transition: transform 0.2s; position: relative;">
                <!-- Badge promo -->
                <?php if ($p['promo_id']): ?>
                    <div style="position: absolute; top: 12px; right: 12px; z-index: 10;">
                        <span class="badge" style="background: <?php echo htmlspecialchars($p['promo_couleur'] ?? '#e63946'); ?>; color: white; font-size: 11px;">
                            <?php echo htmlspecialchars($p['promo_badge'] ?? 'PROMO'); ?>
                            <?php if ($p['promo_valeur']): ?>
                                -<?php echo $p['promo_valeur']; ?>%
                            <?php endif; ?>
                        </span>
                    </div>
                <?php endif; ?>

                <!-- Header -->
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
                    <span class="badge badge-info"><?php echo htmlspecialchars($p['categorie']); ?></span>
                    <span style="font-family: monospace; font-size: 12px; color: var(--text-muted);">
                        <?php echo htmlspecialchars($p['code']); ?>
                    </span>
                </div>

                <!-- Nom -->
                <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 16px; color: var(--text-primary);">
                    <?php echo htmlspecialchars($p['nom']); ?>
                </h3>

                <!-- Prix dégressifs -->
                <div style="background: var(--bg-hover); padding: 16px; border-radius: var(--radius-md); margin-bottom: 16px;">
                    <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 8px; font-weight: 600;">Prix dégressifs au m²</div>

                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; font-size: 13px;">
                        <div>
                            <span style="color: var(--text-muted);">0-10 m²:</span>
                            <strong style="color: var(--primary);"><?php echo number_format($p['prix_0_10'], 2); ?>€</strong>
                        </div>
                        <div>
                            <span style="color: var(--text-muted);">11-50 m²:</span>
                            <strong style="color: var(--primary);"><?php echo number_format($p['prix_11_50'], 2); ?>€</strong>
                        </div>
                        <div>
                            <span style="color: var(--text-muted);">51-100 m²:</span>
                            <strong style="color: var(--success);"><?php echo number_format($p['prix_51_100'], 2); ?>€</strong>
                        </div>
                        <div>
                            <span style="color: var(--text-muted);">101-300 m²:</span>
                            <strong style="color: var(--success);"><?php echo number_format($p['prix_101_300'], 2); ?>€</strong>
                        </div>
                    </div>

                    <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--border); text-align: center;">
                        <span style="color: var(--text-muted); font-size: 12px;">300+ m²:</span>
                        <strong style="color: var(--success); font-size: 20px; margin-left: 8px;">
                            <?php echo number_format($p['prix_300_plus'], 2); ?>€
                        </strong>
                        <span style="color: var(--success); font-size: 11px; font-weight: 600;">MEILLEUR PRIX</span>
                    </div>
                </div>

                <!-- Actions -->
                <div style="display: flex; gap: 8px;">
                    <a href="/admin/editer-produit.php?id=<?php echo urlencode($p['code']); ?>" class="btn btn-primary btn-sm" style="flex: 1;">
                        ✏️ Éditer
                    </a>
                    <a href="/admin/supprimer-produit.php?id=<?php echo urlencode($p['code']); ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Supprimer <?php echo htmlspecialchars($p['nom']); ?> ?');">
                        🗑️
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<style>
.card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
