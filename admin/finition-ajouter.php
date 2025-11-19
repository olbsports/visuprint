<?php
/**
 * Ajouter une finition au catalogue - Imprixo Admin
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../api/config.php';

verifierAdminConnecte();
$admin = getAdminInfo();
$db = Database::getInstance();

$pageTitle = 'Nouvelle finition';

$error = '';
$success = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $categorie = trim($_POST['categorie'] ?? '');
    $prix_defaut = floatval($_POST['prix_defaut'] ?? 0);
    $type_prix_defaut = $_POST['type_prix_defaut'] ?? 'fixe';
    $icone = trim($_POST['icone'] ?? '');
    $actif = isset($_POST['actif']) ? 1 : 0;
    $ordre = intval($_POST['ordre'] ?? 0);

    if (empty($nom)) {
        $error = "Le nom est obligatoire";
    } else {
        try {
            $db->query(
                "INSERT INTO finitions_catalogue (nom, description, categorie, prix_defaut, type_prix_defaut, icone, actif, ordre)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                [$nom, $description, $categorie, $prix_defaut, $type_prix_defaut, $icone, $actif, $ordre]
            );

            header('Location: finitions-catalogue.php?success=' . urlencode('Finition créée avec succès'));
            exit;
        } catch (Exception $e) {
            $error = "Erreur : " . $e->getMessage();
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<?php if ($error): ?>
    <div class="alert alert-error">✗ <?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="top-bar">
    <div>
        <h1 class="page-title">➕ Nouvelle finition</h1>
        <p class="page-subtitle">Ajouter une finition au catalogue global</p>
    </div>
    <div class="top-bar-actions">
        <a href="/admin/finitions-catalogue.php" class="btn btn-secondary">← Retour au catalogue</a>
    </div>
</div>

<div class="card">
    <form method="POST" action="">
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Nom <span style="color: var(--danger);">*</span></label>
                <input type="text" name="nom" class="form-input" required value="<?php echo htmlspecialchars($_POST['nom'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Catégorie</label>
                <select name="categorie" class="form-select">
                    <option value="">Aucune catégorie</option>
                    <option value="PVC">PVC</option>
                    <option value="Aluminium">Aluminium</option>
                    <option value="Bâche">Bâche</option>
                    <option value="Textile">Textile</option>
                    <option value="Tous">Tous produits</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-textarea"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Prix par défaut (€)</label>
                <input type="number" step="0.01" name="prix_defaut" class="form-input" value="<?php echo htmlspecialchars($_POST['prix_defaut'] ?? '0'); ?>">
                <small class="form-help">Peut être négatif pour une réduction (ex: -10)</small>
            </div>

            <div class="form-group">
                <label class="form-label">Type de prix</label>
                <select name="type_prix_defaut" class="form-select">
                    <option value="fixe">Prix fixe</option>
                    <option value="par_m2">Par m²</option>
                    <option value="par_ml">Par mètre linéaire</option>
                    <option value="pourcentage">Pourcentage</option>
                </select>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Icône (emoji)</label>
                <input type="text" name="icone" class="form-input" placeholder="🎨" value="<?php echo htmlspecialchars($_POST['icone'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Ordre d'affichage</label>
                <input type="number" name="ordre" class="form-input" value="<?php echo htmlspecialchars($_POST['ordre'] ?? '0'); ?>">
            </div>
        </div>

        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="checkbox" name="actif" value="1" checked style="width: 20px; height: 20px;">
                <span>Finition active</span>
            </label>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 24px; padding-top: 24px; border-top: 2px solid var(--border);">
            <button type="submit" class="btn btn-success">✓ Créer la finition</button>
            <a href="/admin/finitions-catalogue.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
