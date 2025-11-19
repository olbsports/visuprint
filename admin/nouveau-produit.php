<?php
/**
 * Ajouter un nouveau produit au catalogue - Imprixo Admin
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../api/config.php';

verifierAdminConnecte();
$admin = getAdminInfo();
$db = Database::getInstance();

$error = '';
$success = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation
    $requiredFields = ['CODE', 'CATEGORIE', 'NOM_PRODUIT', 'DESCRIPTION_COURTE'];
    $missingFields = [];

    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $missingFields[] = $field;
        }
    }

    if (!empty($missingFields)) {
        $error = "Champs obligatoires manquants : " . implode(', ', $missingFields);
    } else {
        try {
            // Vérifier si le code existe déjà
            $existing = $db->fetchOne("SELECT id FROM produits WHERE code = ?", [$_POST['CODE']]);

            if ($existing) {
                $error = "Le code produit '{$_POST['CODE']}' existe déjà. Veuillez utiliser un code unique.";
            } else {
                // Insérer le produit
                $db->query(
                    "INSERT INTO produits (
                        code, categorie, nom, sous_titre, description_courte, description_longue,
                        poids_m2, epaisseur, format_max, usage, duree_vie, certification, finition, impression_faces,
                        prix_simple_face, prix_double_face,
                        prix_0_10, prix_11_50, prix_51_100, prix_101_300, prix_300_plus,
                        commande_min, delai_jours, unite_vente, image_url, actif, created_at, updated_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW(), NOW())",
                    [
                        $_POST['CODE'],
                        $_POST['CATEGORIE'],
                        $_POST['NOM_PRODUIT'],
                        $_POST['SOUS_TITRE'] ?? null,
                        $_POST['DESCRIPTION_COURTE'],
                        $_POST['DESCRIPTION_LONGUE'] ?? null,
                        $_POST['POIDS_M2'] ?? null,
                        $_POST['EPAISSEUR'] ?? null,
                        $_POST['FORMAT_MAX_CM'] ?? null,
                        $_POST['USAGE'] ?? null,
                        $_POST['DUREE_VIE'] ?? null,
                        $_POST['CERTIFICATION'] ?? null,
                        $_POST['FINITION'] ?? null,
                        $_POST['IMPRESSION_FACES'] ?? null,
                        $_POST['PRIX_SIMPLE_FACE'] ?? null,
                        $_POST['PRIX_DOUBLE_FACE'] ?? null,
                        $_POST['PRIX_0_10_M2'] ?? null,
                        $_POST['PRIX_11_50_M2'] ?? null,
                        $_POST['PRIX_51_100_M2'] ?? null,
                        $_POST['PRIX_101_300_M2'] ?? null,
                        $_POST['PRIX_300_PLUS_M2'] ?? null,
                        $_POST['COMMANDE_MIN_EURO'] ?? 25,
                        $_POST['DELAI_STANDARD_JOURS'] ?? 3,
                        $_POST['UNITE_VENTE'] ?? 'm²',
                        $_POST['IMAGE_URL'] ?? null
                    ]
                );

                // Log admin action
                logAdminAction($admin['id'] ?? 0, 'ajout_produit', "Produit {$_POST['CODE']} ajouté");

                // Redirection
                header('Location: produits.php?success=' . urlencode('Produit ajouté avec succès !'));
                exit;
            }
        } catch (Exception $e) {
            $error = "Erreur lors de l'ajout : " . $e->getMessage();
        }
    }
}

// Charger les catégories existantes depuis la BDD
$categories = [];
try {
    $categoriesData = $db->fetchAll("SELECT DISTINCT categorie FROM produits ORDER BY categorie");
    $categories = array_column($categoriesData, 'categorie');
} catch (Exception $e) {
    // Erreur silencieuse
}

$pageTitle = 'Nouveau produit';
include __DIR__ . '/includes/header.php';
?>

<?php if ($error): ?>
    <div class="alert alert-error">✗ <?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success">✓ <?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<div class="top-bar">
    <div>
        <h1 class="page-title">➕ Nouveau produit</h1>
        <p class="page-subtitle">Ajouter un produit au catalogue</p>
    </div>
    <div class="top-bar-actions">
        <a href="/admin/produits.php" class="btn btn-secondary">← Retour aux produits</a>
    </div>
</div>

<div class="card">
    <form method="POST" action="">
        <div class="section-title">📋 Informations de base</div>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Code Produit <span class="required">*</span></label>
                <input type="text" name="CODE" required placeholder="Ex: FX-2MM" class="form-input" value="<?php echo htmlspecialchars($_POST['CODE'] ?? ''); ?>">
                <small style="color: var(--text-muted); font-size: 12px;">Code unique du produit (lettres, chiffres, tirets)</small>
            </div>

            <div class="form-group">
                <label class="form-label">Catégorie <span class="required">*</span></label>
                <select name="CATEGORIE" required class="form-select">
                    <option value="">Sélectionner une catégorie</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo (isset($_POST['CATEGORIE']) && $_POST['CATEGORIE'] === $cat) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat); ?>
                        </option>
                    <?php endforeach; ?>
                    <option value="__new__">➕ Nouvelle catégorie...</option>
                </select>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Nom du produit <span class="required">*</span></label>
                <input type="text" name="NOM_PRODUIT" required placeholder="Ex: Forex 2mm (PVC Foam)" class="form-input" value="<?php echo htmlspecialchars($_POST['NOM_PRODUIT'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Sous-titre</label>
                <input type="text" name="SOUS_TITRE" placeholder="Ex: PVC Foam - Ultra léger" class="form-input" value="<?php echo htmlspecialchars($_POST['SOUS_TITRE'] ?? ''); ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Description courte <span class="required">*</span></label>
            <textarea name="DESCRIPTION_COURTE" required placeholder="Description courte du produit (1-2 lignes)" class="form-textarea"><?php echo htmlspecialchars($_POST['DESCRIPTION_COURTE'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Description longue</label>
            <textarea name="DESCRIPTION_LONGUE" placeholder="Description détaillée du produit" class="form-textarea" style="min-height: 150px;"><?php echo htmlspecialchars($_POST['DESCRIPTION_LONGUE'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Image URL</label>
            <input type="url" name="IMAGE_URL" placeholder="https://..." class="form-input" value="<?php echo htmlspecialchars($_POST['IMAGE_URL'] ?? ''); ?>">
            <small style="color: var(--text-muted); font-size: 12px;">URL de l'image du produit (Unsplash, CDN, etc.)</small>
        </div>

        <div class="section-title">⚙️ Caractéristiques techniques</div>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Poids (kg/m²)</label>
                <input type="text" name="POIDS_M2" placeholder="Ex: 1.4" class="form-input" value="<?php echo htmlspecialchars($_POST['POIDS_M2'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Épaisseur</label>
                <input type="text" name="EPAISSEUR" placeholder="Ex: 2mm" class="form-input" value="<?php echo htmlspecialchars($_POST['EPAISSEUR'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Format maximum (cm)</label>
                <input type="text" name="FORMAT_MAX_CM" placeholder="Ex: 200x300" class="form-input" value="<?php echo htmlspecialchars($_POST['FORMAT_MAX_CM'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Usage</label>
                <input type="text" name="USAGE" placeholder="Ex: Intérieur/Extérieur abrité" class="form-input" value="<?php echo htmlspecialchars($_POST['USAGE'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Durée de vie</label>
                <input type="text" name="DUREE_VIE" placeholder="Ex: 1-2 ans" class="form-input" value="<?php echo htmlspecialchars($_POST['DUREE_VIE'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Certification</label>
                <input type="text" name="CERTIFICATION" placeholder="Ex: M1 - Ignifugé" class="form-input" value="<?php echo htmlspecialchars($_POST['CERTIFICATION'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Finition</label>
                <input type="text" name="FINITION" placeholder="Ex: Mat blanc" class="form-input" value="<?php echo htmlspecialchars($_POST['FINITION'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Impression faces</label>
                <select name="IMPRESSION_FACES" class="form-select">
                    <option value="">Sélectionner</option>
                    <option value="Simple ou double" <?php echo (isset($_POST['IMPRESSION_FACES']) && $_POST['IMPRESSION_FACES'] === 'Simple ou double') ? 'selected' : ''; ?>>Simple ou double</option>
                    <option value="Simple face" <?php echo (isset($_POST['IMPRESSION_FACES']) && $_POST['IMPRESSION_FACES'] === 'Simple face') ? 'selected' : ''; ?>>Simple face</option>
                    <option value="Double face" <?php echo (isset($_POST['IMPRESSION_FACES']) && $_POST['IMPRESSION_FACES'] === 'Double face') ? 'selected' : ''; ?>>Double face</option>
                </select>
            </div>
        </div>

        <div class="section-title">💰 Prix et tarification</div>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Prix simple face (€/m²)</label>
                <input type="number" step="0.01" name="PRIX_SIMPLE_FACE" placeholder="12.00" class="form-input" value="<?php echo htmlspecialchars($_POST['PRIX_SIMPLE_FACE'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Prix double face (€/m²)</label>
                <input type="number" step="0.01" name="PRIX_DOUBLE_FACE" placeholder="18.00" class="form-input" value="<?php echo htmlspecialchars($_POST['PRIX_DOUBLE_FACE'] ?? ''); ?>">
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Prix 0-10 m² (€/m²)</label>
                <input type="number" step="0.01" name="PRIX_0_10_M2" placeholder="32.00" class="form-input" value="<?php echo htmlspecialchars($_POST['PRIX_0_10_M2'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Prix 11-50 m² (€/m²)</label>
                <input type="number" step="0.01" name="PRIX_11_50_M2" placeholder="28.00" class="form-input" value="<?php echo htmlspecialchars($_POST['PRIX_11_50_M2'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Prix 51-100 m² (€/m²)</label>
                <input type="number" step="0.01" name="PRIX_51_100_M2" placeholder="25.00" class="form-input" value="<?php echo htmlspecialchars($_POST['PRIX_51_100_M2'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Prix 101-300 m² (€/m²)</label>
                <input type="number" step="0.01" name="PRIX_101_300_M2" placeholder="22.00" class="form-input" value="<?php echo htmlspecialchars($_POST['PRIX_101_300_M2'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Prix 300+ m² (€/m²)</label>
                <input type="number" step="0.01" name="PRIX_300_PLUS_M2" placeholder="20.00" class="form-input" value="<?php echo htmlspecialchars($_POST['PRIX_300_PLUS_M2'] ?? ''); ?>">
            </div>
        </div>

        <div class="section-title">📦 Logistique</div>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Commande minimum (€)</label>
                <input type="number" step="0.01" name="COMMANDE_MIN_EURO" placeholder="25" class="form-input" value="<?php echo htmlspecialchars($_POST['COMMANDE_MIN_EURO'] ?? '25'); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Délai standard (jours)</label>
                <input type="number" name="DELAI_STANDARD_JOURS" placeholder="3" class="form-input" value="<?php echo htmlspecialchars($_POST['DELAI_STANDARD_JOURS'] ?? '3'); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Unité de vente</label>
                <select name="UNITE_VENTE" class="form-select">
                    <option value="m²" <?php echo (!isset($_POST['UNITE_VENTE']) || $_POST['UNITE_VENTE'] === 'm²') ? 'selected' : ''; ?>>m²</option>
                    <option value="ml" <?php echo (isset($_POST['UNITE_VENTE']) && $_POST['UNITE_VENTE'] === 'ml') ? 'selected' : ''; ?>>ml (mètre linéaire)</option>
                    <option value="unité" <?php echo (isset($_POST['UNITE_VENTE']) && $_POST['UNITE_VENTE'] === 'unité') ? 'selected' : ''; ?>>unité</option>
                </select>
            </div>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 30px; padding-top: 20px; border-top: 2px solid var(--border);">
            <button type="submit" class="btn btn-success">💾 Enregistrer le produit</button>
            <a href="/admin/produits.php" class="btn btn-secondary">✖ Annuler</a>
        </div>
    </form>
</div>

<style>
.section-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--primary);
    margin: 30px 0 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--primary);
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.required {
    color: var(--danger);
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
