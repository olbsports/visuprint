<?php
/**
 * Gestion Produits - VisuPrint Pro
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../api/config.php';

verifierAdminConnecte();
$admin = getAdminInfo();
$db = Database::getInstance();

$pageTitle = 'Gestion des produits';

$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';

// Filtres
$recherche = isset($_GET['q']) ? trim($_GET['q']) : '';
$filtreCategorie = isset($_GET['cat']) ? $_GET['cat'] : '';

// Charger les produits
$where = ['actif = 1'];
$params = [];

if ($recherche) {
    $where[] = "(id_produit LIKE ? OR nom_produit LIKE ? OR sous_titre LIKE ? OR description_courte LIKE ?)";
    $search = '%' . $recherche . '%';
    $params = array_merge($params, [$search, $search, $search, $search]);
}

if ($filtreCategorie) {
    $where[] = "categorie = ?";
    $params[] = $filtreCategorie;
}

$whereClause = 'WHERE ' . implode(' AND ', $where);

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
     ORDER BY categorie, nom_produit",
    $params
);

// Récupérer les catégories uniques
$categories = $db->fetchAll("SELECT DISTINCT categorie FROM produits WHERE actif = 1 ORDER BY categorie");

include __DIR__ . '/includes/header.php';
?>

<?php if ($success): ?>
    <div class="alert alert-success">
        ✓ <?php echo htmlspecialchars($success); ?>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error">
        ✗ <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<div class="top-bar">
    <div>
        <h1 class="page-title">📦 Gestion des produits</h1>
        <p class="page-subtitle"><?php echo count($produits); ?> produit(s) dans le catalogue</p>
    </div>
    <div class="top-bar-actions">
        <a href="/admin/nouveau-produit.php" class="btn btn-success">
            ➕ Nouveau produit
        </a>
        <a href="/admin/finitions-catalogue.php" class="btn btn-primary">
            🎨 Finitions
        </a>
    </div>
</div>

<!-- Filtres -->
<div class="card">
    <form method="GET" action="" style="display: flex; gap: 16px; align-items: end;">
        <div class="form-group" style="flex: 1; margin: 0;">
            <label class="form-label">🔍 Rechercher</label>
            <input
                type="text"
                name="q"
                class="form-input"
                placeholder="ID, nom, description..."
                value="<?php echo htmlspecialchars($recherche); ?>"
            >
        </div>

        <div class="form-group" style="min-width: 200px; margin: 0;">
            <label class="form-label">Catégorie</label>
            <select name="cat" class="form-select">
                <option value="">Toutes les catégories</option>
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

<!-- Liste des produits -->
<?php if (empty($produits)): ?>
    <div class="card" style="text-align: center; padding: 60px 20px;">
        <div style="font-size: 64px; margin-bottom: 20px;">📦</div>
        <h2 style="font-size: 24px; margin-bottom: 12px; color: var(--text-primary);">Aucun produit trouvé</h2>
        <p style="color: var(--text-secondary); margin-bottom: 24px;">
            <?php if ($recherche || $filtreCategorie): ?>
                Aucun produit ne correspond à vos critères de recherche.
            <?php else: ?>
                Commencez par ajouter votre premier produit au catalogue.
            <?php endif; ?>
        </p>
        <a href="/admin/nouveau-produit.php" class="btn btn-primary">➕ Créer mon premier produit</a>
    </div>
<?php else: ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px;">
        <?php foreach ($produits as $produit): ?>
            <div class="card" style="position: relative; padding: 0; overflow: hidden; transition: all 0.3s ease;">
                <!-- Badge promotion si active -->
                <?php if ($produit['promo_id']): ?>
                    <div style="position: absolute; top: 16px; right: 16px; z-index: 10;">
                        <span class="badge" style="background: <?php echo htmlspecialchars($produit['promo_couleur'] ?? '#e63946'); ?>; color: white; font-size: 11px; padding: 6px 12px;">
                            <?php echo htmlspecialchars($produit['promo_badge'] ?? 'PROMO'); ?>
                            <?php if ($produit['promo_valeur']): ?>
                                -<?php echo $produit['promo_valeur']; ?>%
                            <?php endif; ?>
                        </span>
                    </div>
                <?php endif; ?>

                <!-- Image -->
                <div style="height: 200px; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;">
                    <?php if (!empty($produit['image_url'])): ?>
                        <img
                            src="<?php echo htmlspecialchars($produit['image_url']); ?>"
                            alt="<?php echo htmlspecialchars($produit['nom_produit']); ?>"
                            style="width: 100%; height: 100%; object-fit: cover;"
                        >
                    <?php else: ?>
                        <div style="font-size: 48px; opacity: 0.3;">🎨</div>
                    <?php endif; ?>
                </div>

                <!-- Contenu -->
                <div style="padding: 20px;">
                    <!-- Catégorie -->
                    <div style="margin-bottom: 8px;">
                        <span class="badge badge-info" style="font-size: 11px;">
                            <?php echo htmlspecialchars($produit['categorie']); ?>
                        </span>
                    </div>

                    <!-- Titre -->
                    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 4px; color: var(--text-primary);">
                        <?php echo htmlspecialchars($produit['nom_produit']); ?>
                    </h3>

                    <!-- ID Produit -->
                    <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 12px;">
                        ID: <?php echo htmlspecialchars($produit['id_produit']); ?>
                    </div>

                    <!-- Description -->
                    <?php if (!empty($produit['description_courte'])): ?>
                        <p style="font-size: 14px; color: var(--text-secondary); line-height: 1.5; margin-bottom: 16px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            <?php echo htmlspecialchars($produit['description_courte']); ?>
                        </p>
                    <?php endif; ?>

                    <!-- Prix -->
                    <div style="margin-bottom: 16px; padding: 12px; background: var(--bg-hover); border-radius: var(--radius-md);">
                        <div style="font-size: 12px; color: var(--text-secondary); margin-bottom: 4px;">Prix de base</div>
                        <div style="font-size: 24px; font-weight: 700; color: var(--primary);">
                            <?php echo number_format($produit['prix_0_10'] ?? 0, 2, ',', ' '); ?> €
                            <span style="font-size: 14px; font-weight: 400; color: var(--text-muted);">/m²</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div style="display: flex; gap: 8px;">
                        <a href="/admin/editer-produit.php?id=<?php echo urlencode($produit['id_produit']); ?>" class="btn btn-primary" style="flex: 1; justify-content: center;">
                            ✏️ Éditer
                        </a>
                        <a
                            href="/admin/supprimer-produit.php?id=<?php echo urlencode($produit['id_produit']); ?>"
                            class="btn btn-danger"
                            style="justify-content: center;"
                            data-confirm="Êtes-vous sûr de vouloir supprimer ce produit ?"
                        >
                            🗑️
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<style>
    .card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl) !important;
    }
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
