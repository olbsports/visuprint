<?php
/**
 * Éditer Finition - Imprixo Admin
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../api/config.php';

verifierAdminConnecte();
$admin = getAdminInfo();
$db = Database::getInstance();

$pageTitle = 'Éditer une finition';

$error = '';
$success = '';

// Charger la finition
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id === 0) {
    header('Location: finitions-catalogue.php?error=' . urlencode('ID finition manquant'));
    exit;
}

try {
    $finition = $db->fetchOne("SELECT * FROM finitions_catalogue WHERE id = ?", [$id]);
    if (!$finition) {
        header('Location: finitions-catalogue.php?error=' . urlencode('Finition non trouvée'));
        exit;
    }
} catch (Exception $e) {
    header('Location: finitions-catalogue.php?error=' . urlencode($e->getMessage()));
    exit;
}

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
                "UPDATE finitions_catalogue
                 SET nom = ?, description = ?, categorie = ?, prix_defaut = ?,
                     type_prix_defaut = ?, icone = ?, actif = ?, ordre = ?, updated_at = NOW()
                 WHERE id = ?",
                [$nom, $description, $categorie, $prix_defaut, $type_prix_defaut, $icone, $actif, $ordre, $id]
            );
            $success = "Finition modifiée avec succès !";

            // Recharger les données
            $finition = $db->fetchOne("SELECT * FROM finitions_catalogue WHERE id = ?", [$id]);

        } catch (Exception $e) {
            $error = "Erreur : " . $e->getMessage();
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
        <h1 class="page-title">✏️ Éditer une finition</h1>
        <p class="page-subtitle"><?php echo htmlspecialchars($finition['nom']); ?></p>
    </div>
    <div class="top-bar-actions">
        <a href="/admin/finitions-catalogue.php" class="btn btn-secondary">
            ← Retour au catalogue
        </a>
    </div>
</div>

<div class="card">
    <form method="POST" action="">
        <div class="form-group">
            <label class="form-label">Nom de la finition <span style="color: var(--danger);">*</span></label>
            <input
                type="text"
                name="nom"
                class="form-input"
                required
                value="<?php echo htmlspecialchars($finition['nom']); ?>"
                placeholder="Ex: Œillets tous les 50cm"
            >
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea
                name="description"
                class="form-textarea"
                rows="3"
                placeholder="Description détaillée de la finition"
            ><?php echo htmlspecialchars($finition['description'] ?? ''); ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
            <div class="form-group">
                <label class="form-label">Catégorie</label>
                <select name="categorie" class="form-select">
                    <option value="">-- Aucune catégorie --</option>
                    <option value="PVC" <?php echo $finition['categorie'] === 'PVC' ? 'selected' : ''; ?>>PVC / Forex</option>
                    <option value="Aluminium" <?php echo $finition['categorie'] === 'Aluminium' ? 'selected' : ''; ?>>Aluminium</option>
                    <option value="Bâche" <?php echo $finition['categorie'] === 'Bâche' ? 'selected' : ''; ?>>Bâche</option>
                    <option value="Textile" <?php echo $finition['categorie'] === 'Textile' ? 'selected' : ''; ?>>Textile</option>
                    <option value="Tous" <?php echo $finition['categorie'] === 'Tous' ? 'selected' : ''; ?>>Tous les produits</option>
                </select>
                <small style="display: block; margin-top: 5px; color: var(--text-muted); font-size: 13px;">
                    Laissez vide pour activation manuelle, ou "Tous" pour tous les produits
                </small>
            </div>

            <div class="form-group">
                <label class="form-label">Icône</label>
                <input
                    type="text"
                    name="icone"
                    class="form-input"
                    value="<?php echo htmlspecialchars($finition['icone'] ?? ''); ?>"
                    placeholder="Ex: 🎨 ou ⭕"
                >
                <small style="display: block; margin-top: 5px; color: var(--text-muted); font-size: 13px;">
                    Emoji ou texte court pour identifier visuellement
                </small>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
            <div class="form-group">
                <label class="form-label">Prix par défaut (€)</label>
                <input
                    type="number"
                    step="0.01"
                    name="prix_defaut"
                    class="form-input"
                    value="<?php echo htmlspecialchars($finition['prix_defaut']); ?>"
                >
                <small style="display: block; margin-top: 5px; color: var(--text-muted); font-size: 13px;">
                    Peut être modifié produit par produit. Utilisez négatif pour réduction
                </small>
            </div>

            <div class="form-group">
                <label class="form-label">Type de prix</label>
                <select name="type_prix_defaut" class="form-select">
                    <option value="fixe" <?php echo $finition['type_prix_defaut'] === 'fixe' ? 'selected' : ''; ?>>Fixe (ex: +15€)</option>
                    <option value="par_m2" <?php echo $finition['type_prix_defaut'] === 'par_m2' ? 'selected' : ''; ?>>Par m² (ex: +8€/m²)</option>
                    <option value="par_ml" <?php echo $finition['type_prix_defaut'] === 'par_ml' ? 'selected' : ''; ?>>Par mètre linéaire</option>
                    <option value="pourcentage" <?php echo $finition['type_prix_defaut'] === 'pourcentage' ? 'selected' : ''; ?>>Pourcentage (ex: +10%)</option>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
            <div class="form-group">
                <label class="form-label">Ordre d'affichage</label>
                <input
                    type="number"
                    name="ordre"
                    class="form-input"
                    value="<?php echo htmlspecialchars($finition['ordre']); ?>"
                >
                <small style="display: block; margin-top: 5px; color: var(--text-muted); font-size: 13px;">
                    Les finitions sont triées par ordre croissant
                </small>
            </div>

            <div class="form-group">
                <label class="form-label" style="display: flex; align-items: center; gap: 10px;">
                    <input
                        type="checkbox"
                        name="actif"
                        value="1"
                        <?php echo $finition['actif'] ? 'checked' : ''; ?>
                        style="width: auto; margin: 0;"
                    >
                    <span>Finition active</span>
                </label>
                <small style="display: block; margin-top: 5px; color: var(--text-muted); font-size: 13px;">
                    Si désactivée, elle n'apparaîtra plus dans les choix
                </small>
            </div>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 30px; padding-top: 20px; border-top: 2px solid var(--border);">
            <button type="submit" class="btn btn-primary">
                💾 Enregistrer les modifications
            </button>
            <a href="/admin/finitions-catalogue.php" class="btn btn-secondary">
                Annuler
            </a>
        </div>
    </form>
</div>

<!-- Info card -->
<div class="card" style="background: var(--bg-hover); border-left: 4px solid var(--info);">
    <h3 style="font-size: 18px; margin-bottom: 16px; color: var(--info);">💡 À savoir</h3>
    <ul style="line-height: 1.8; margin-left: 20px; color: var(--text-secondary);">
        <li><strong>Catégorie "Tous"</strong> : La finition apparaîtra automatiquement pour tous les produits</li>
        <li><strong>Catégorie spécifique</strong> : Apparaît seulement pour les produits de cette catégorie</li>
        <li><strong>Aucune catégorie</strong> : Tu devras l'activer manuellement produit par produit</li>
        <li><strong>Prix négatif</strong> : Pour faire des réductions (ex: -10€ si le client fournit son fichier)</li>
        <li><strong>Ordre</strong> : Les finitions avec ordre plus petit apparaissent en premier</li>
    </ul>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
