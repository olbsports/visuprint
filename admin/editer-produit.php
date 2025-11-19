<?php
/**
 * Éditer un produit existant
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../api/config.php';

verifierAdminConnecte();
$admin = getAdminInfo();

$db = Database::getInstance();
$error = '';
$success = '';
$produit = null;
$finitions = [];

// Récupérer l'ID du produit
$idProduit = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($idProduit)) {
    header('Location: produits.php?error=' . urlencode('ID produit manquant'));
    exit;
}

// Charger les données du produit depuis la base de données
try {
    $produit = $db->fetchOne(
        "SELECT p.*,
                pr.id as promo_id, pr.type as promo_type, pr.valeur as promo_valeur,
                pr.prix_special as promo_prix, pr.titre as promo_titre, pr.badge_texte as promo_badge,
                pr.date_debut as promo_date_debut, pr.date_fin as promo_date_fin,
                pr.afficher_countdown as promo_countdown, pr.actif as promo_actif
         FROM produits p
         LEFT JOIN promotions pr ON pr.produit_id = p.id AND pr.actif = 1
         WHERE p.code = ?",
        [$idProduit]
    );

    if (!$produit) {
        header('Location: produits.php?error=' . urlencode('Produit non trouvé'));
        exit;
    }

    // Charger TOUTES les finitions du catalogue
    $catalogueFinitions = $db->fetchAll(
        "SELECT * FROM finitions_catalogue WHERE actif = 1 ORDER BY categorie, ordre"
    );

    // Charger les finitions déjà activées pour ce produit
    $finitions = $db->fetchAll(
        "SELECT * FROM produits_finitions WHERE produit_id = ? ORDER BY ordre",
        [$produit['id']]
    );

    // Créer un index des finitions actives par catalogue_id pour accès rapide
    $finitionsActives = [];
    foreach ($finitions as $fin) {
        if ($fin['finition_catalogue_id']) {
            $finitionsActives[$fin['finition_catalogue_id']] = $fin;
        }
    }

} catch (Exception $e) {
    header('Location: produits.php?error=' . urlencode('Erreur chargement: ' . $e->getMessage()));
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation
    $requiredFields = ['CATEGORIE', 'NOM_PRODUIT', 'DESCRIPTION_COURTE'];
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
            // Mettre à jour le produit
            $updates = [];
            $params = [];

            $allowedFields = [
                'categorie' => 'CATEGORIE',
                'nom' => 'NOM_PRODUIT',
                'sous_titre' => 'SOUS_TITRE',
                'description_courte' => 'DESCRIPTION_COURTE',
                'description_longue' => 'DESCRIPTION_LONGUE',
                'poids_m2' => 'POIDS_M2',
                'epaisseur' => 'EPAISSEUR',
                'format_max' => 'FORMAT_MAX_CM',
                'usage' => 'USAGE',
                'duree_vie' => 'DUREE_VIE',
                'certification' => 'CERTIFICATION',
                'finition' => 'FINITION',
                'impression_faces' => 'IMPRESSION_FACES',
                'prix_simple_face' => 'PRIX_SIMPLE_FACE',
                'prix_double_face' => 'PRIX_DOUBLE_FACE',
                'prix_0_10' => 'PRIX_0_10_M2',
                'prix_11_50' => 'PRIX_11_50_M2',
                'prix_51_100' => 'PRIX_51_100_M2',
                'prix_101_300' => 'PRIX_101_300_M2',
                'prix_300_plus' => 'PRIX_300_PLUS_M2',
                'commande_min' => 'COMMANDE_MIN_EURO',
                'delai_jours' => 'DELAI_STANDARD_JOURS',
                'unite_vente' => 'UNITE_VENTE',
                'image_url' => 'IMAGE_URL'
            ];

            foreach ($allowedFields as $dbField => $postField) {
                if (isset($_POST[$postField])) {
                    $updates[] = "$dbField = ?";
                    $params[] = $_POST[$postField];
                }
            }

            if (count($updates) > 0) {
                $params[] = $produit['id'];
                $db->query(
                    "UPDATE produits SET " . implode(', ', $updates) . ", updated_at = NOW() WHERE id = ?",
                    $params
                );
            }

            // Mettre à jour finitions depuis le catalogue
            if (isset($_POST['finitions_selectionnees']) && is_array($_POST['finitions_selectionnees'])) {
                // Supprimer anciennes finitions
                $db->query("DELETE FROM produits_finitions WHERE produit_id = ?", [$produit['id']]);

                // Ajouter les finitions sélectionnées depuis le catalogue
                $ordre = 0;
                foreach ($_POST['finitions_selectionnees'] as $catalogueId) {
                    // Récupérer les infos du catalogue
                    $catalogueFin = $db->fetchOne(
                        "SELECT * FROM finitions_catalogue WHERE id = ?",
                        [$catalogueId]
                    );

                    if ($catalogueFin) {
                        // Utiliser le prix personnalisé si fourni, sinon prix du catalogue
                        $prixPerso = $_POST['finition_prix'][$catalogueId] ?? null;
                        $prixFinal = ($prixPerso !== '' && $prixPerso !== null) ? $prixPerso : $catalogueFin['prix_defaut'];

                        $db->query(
                            "INSERT INTO produits_finitions (
                                produit_id, finition_catalogue_id, nom, description,
                                prix_supplement, type_prix, actif, ordre
                            ) VALUES (?, ?, ?, ?, ?, ?, 1, ?)",
                            [
                                $produit['id'],
                                $catalogueId,
                                $catalogueFin['nom'],
                                $catalogueFin['description'],
                                $prixFinal,
                                $catalogueFin['type_prix_defaut'],
                                $ordre++
                            ]
                        );
                    }
                }
            }

            // Gestion promotion
            if (isset($_POST['promo_actif'])) {
                if ($_POST['promo_actif'] == '1') {
                    // Créer ou mettre à jour promo
                    $existing = $db->fetchOne("SELECT id FROM promotions WHERE produit_id = ?", [$produit['id']]);

                    if ($existing) {
                        $db->query(
                            "UPDATE promotions SET
                                type = ?, valeur = ?, prix_special = ?, titre = ?, badge_texte = ?,
                                date_debut = ?, date_fin = ?, afficher_countdown = ?, actif = 1
                             WHERE id = ?",
                            [
                                $_POST['promo_type'] ?? 'pourcentage',
                                $_POST['promo_valeur'] ?? 0,
                                $_POST['promo_prix_special'] ?? null,
                                $_POST['promo_titre'] ?? '',
                                $_POST['promo_badge'] ?? 'PROMO',
                                $_POST['promo_date_debut'] ?? null,
                                $_POST['promo_date_fin'] ?? null,
                                isset($_POST['promo_countdown']) ? 1 : 0,
                                $existing['id']
                            ]
                        );
                    } else {
                        $db->query(
                            "INSERT INTO promotions (produit_id, type, valeur, prix_special, titre, badge_texte, date_debut, date_fin, afficher_countdown, actif)
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)",
                            [
                                $produit['id'],
                                $_POST['promo_type'] ?? 'pourcentage',
                                $_POST['promo_valeur'] ?? 0,
                                $_POST['promo_prix_special'] ?? null,
                                $_POST['promo_titre'] ?? '',
                                $_POST['promo_badge'] ?? 'PROMO',
                                $_POST['promo_date_debut'] ?? null,
                                $_POST['promo_date_fin'] ?? null,
                                isset($_POST['promo_countdown']) ? 1 : 0
                            ]
                        );
                    }
                } else {
                    // Désactiver promo
                    $db->query("UPDATE promotions SET actif = 0 WHERE produit_id = ?", [$produit['id']]);
                }
            }

            // Log admin action
            logAdminAction($admin['id'] ?? 0, 'modification_produit', "Produit {$produit['code']} modifié");

            // Redirection
            header('Location: produits.php?success=' . urlencode('Produit modifié avec succès !'));
            exit;

        } catch (Exception $e) {
            $error = "Erreur lors de la mise à jour : " . $e->getMessage();
        }
    }
}

// Charger les catégories existantes depuis la base de données
$categories = [];
try {
    $categoriesData = $db->fetchAll("SELECT DISTINCT categorie FROM produits ORDER BY categorie");
    $categories = array_column($categoriesData, 'categorie');
} catch (Exception $e) {
    // Erreur silencieuse, on continuera avec une liste vide
}

$pageTitle = 'Éditer un produit';
include __DIR__ . '/includes/header.php';
?>

<?php if ($error): ?>
    <div class="alert alert-error">✗ <?php echo $error; ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success">✓ <?php echo $success; ?></div>
<?php endif; ?>

<div class="top-bar">
    <div>
        <h1 class="page-title">✏️ Éditer un produit</h1>
        <p class="page-subtitle">Modification : <?php echo htmlspecialchars($produit['nom']); ?></p>
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
                        <label>Code Produit <span class="required">*</span></label>
                        <input type="text" name="CODE" required value="<?php echo htmlspecialchars($produit['code']); ?>" readonly style="background: #f5f5f5;">
                        <small>Le code ne peut pas être modifié</small>
                    </div>

                    <div class="form-group">
                        <label>Catégorie <span class="required">*</span></label>
                        <select name="CATEGORIE" required>
                            <option value="">Sélectionner une catégorie</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $produit['categorie'] === $cat ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Nom du produit <span class="required">*</span></label>
                        <input type="text" name="NOM_PRODUIT" required value="<?php echo htmlspecialchars($produit['nom']); ?>">
                    </div>

                    <div class="form-group">
                        <label>Sous-titre</label>
                        <input type="text" name="SOUS_TITRE" value="<?php echo htmlspecialchars($produit['sous_titre'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Description courte <span class="required">*</span></label>
                    <textarea name="DESCRIPTION_COURTE" required><?php echo htmlspecialchars($produit['description_courte']); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Description longue</label>
                    <textarea name="DESCRIPTION_LONGUE" style="min-height: 150px;"><?php echo htmlspecialchars($produit['description_longue'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Image URL</label>
                    <input type="url" name="IMAGE_URL" value="<?php echo htmlspecialchars($produit['image_url'] ?? ''); ?>" placeholder="https://...">
                    <small>URL de l'image du produit (Unsplash, CDN, etc.)</small>
                </div>

                <div class="section-title">⚙️ Caractéristiques techniques</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Poids (kg/m²)</label>
                        <input type="text" name="POIDS_M2" value="<?php echo htmlspecialchars($produit['poids_m2'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Épaisseur</label>
                        <input type="text" name="EPAISSEUR" value="<?php echo htmlspecialchars($produit['epaisseur'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Format maximum (cm)</label>
                        <input type="text" name="FORMAT_MAX_CM" value="<?php echo htmlspecialchars($produit['format_max'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Usage</label>
                        <input type="text" name="USAGE" value="<?php echo htmlspecialchars($produit['usage'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Durée de vie</label>
                        <input type="text" name="DUREE_VIE" value="<?php echo htmlspecialchars($produit['duree_vie'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Certification</label>
                        <input type="text" name="CERTIFICATION" value="<?php echo htmlspecialchars($produit['certification'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Finition</label>
                        <input type="text" name="FINITION" value="<?php echo htmlspecialchars($produit['finition'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Impression faces</label>
                        <select name="IMPRESSION_FACES">
                            <option value="">Sélectionner</option>
                            <option value="Simple ou double" <?php echo (isset($produit['impression_faces']) && $produit['impression_faces'] === 'Simple ou double') ? 'selected' : ''; ?>>Simple ou double</option>
                            <option value="Simple face" <?php echo (isset($produit['impression_faces']) && $produit['impression_faces'] === 'Simple face') ? 'selected' : ''; ?>>Simple face</option>
                            <option value="Double face" <?php echo (isset($produit['impression_faces']) && $produit['impression_faces'] === 'Double face') ? 'selected' : ''; ?>>Double face</option>
                        </select>
                    </div>
                </div>

                <div class="section-title">💰 Prix et tarification</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Prix simple face (€/m²)</label>
                        <input type="number" step="0.01" name="PRIX_SIMPLE_FACE" value="<?php echo htmlspecialchars($produit['prix_simple_face'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Prix double face (€/m²)</label>
                        <input type="number" step="0.01" name="PRIX_DOUBLE_FACE" value="<?php echo htmlspecialchars($produit['prix_double_face'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Prix 0-10 m² (€/m²)</label>
                        <input type="number" step="0.01" name="PRIX_0_10_M2" value="<?php echo htmlspecialchars($produit['prix_0_10'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Prix 11-50 m² (€/m²)</label>
                        <input type="number" step="0.01" name="PRIX_11_50_M2" value="<?php echo htmlspecialchars($produit['prix_11_50'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Prix 51-100 m² (€/m²)</label>
                        <input type="number" step="0.01" name="PRIX_51_100_M2" value="<?php echo htmlspecialchars($produit['prix_51_100'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Prix 101-300 m² (€/m²)</label>
                        <input type="number" step="0.01" name="PRIX_101_300_M2" value="<?php echo htmlspecialchars($produit['prix_101_300'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Prix 300+ m² (€/m²)</label>
                        <input type="number" step="0.01" name="PRIX_300_PLUS_M2" value="<?php echo htmlspecialchars($produit['prix_300_plus'] ?? ''); ?>">
                    </div>
                </div>

                <div class="section-title">📦 Logistique</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Commande minimum (€)</label>
                        <input type="number" step="0.01" name="COMMANDE_MIN_EURO" value="<?php echo htmlspecialchars($produit['commande_min'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Délai standard (jours)</label>
                        <input type="number" name="DELAI_STANDARD_JOURS" value="<?php echo htmlspecialchars($produit['delai_jours'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label>Unité de vente</label>
                        <select name="UNITE_VENTE">
                            <option value="m²" <?php echo (isset($produit['unite_vente']) && $produit['unite_vente'] === 'm²') ? 'selected' : ''; ?>>m²</option>
                            <option value="ml" <?php echo (isset($produit['unite_vente']) && $produit['unite_vente'] === 'ml') ? 'selected' : ''; ?>>ml (mètre linéaire)</option>
                            <option value="unité" <?php echo (isset($produit['unite_vente']) && $produit['unite_vente'] === 'unité') ? 'selected' : ''; ?>>unité</option>
                        </select>
                    </div>
                </div>

                <div class="section-title">🎨 Finitions et options</div>
                <p style="color: var(--text-secondary); margin-bottom: 15px;">
                    ✓ Cochez les finitions à activer pour ce produit depuis le catalogue global.<br>
                    💡 Vous pouvez personnaliser le prix par produit si besoin.
                    <a href="/admin/finitions-catalogue.php" target="_blank" style="color: var(--primary); text-decoration: none; font-weight: 600;">→ Gérer le catalogue</a>
                </p>

                <div id="finitions-container" style="max-height: 500px; overflow-y: auto; border: 2px solid var(--border); border-radius: var(--radius-md); padding: 20px; background: var(--bg-hover);">
                    <?php
                    $currentCategorie = '';
                    foreach ($catalogueFinitions as $catalogueFin):
                        // Afficher header de catégorie
                        if ($catalogueFin['categorie'] !== $currentCategorie) {
                            if ($currentCategorie !== '') echo '</div>';
                            $currentCategorie = $catalogueFin['categorie'];
                            echo '<div style="margin-bottom: 20px;">';
                            echo '<h4 style="color: var(--primary); margin: 15px 0 10px; padding-bottom: 5px; border-bottom: 2px solid var(--primary); font-weight: 700;">';
                            echo '📦 ' . htmlspecialchars($catalogueFin['categorie'] ?: 'Autres');
                            echo '</h4>';
                        }

                        $isActive = isset($finitionsActives[$catalogueFin['id']]);
                        $prixActuel = $isActive ? $finitionsActives[$catalogueFin['id']]['prix_supplement'] : $catalogueFin['prix_defaut'];
                    ?>
                        <div style="background: white; padding: 12px; margin-bottom: 8px; border-radius: var(--radius-sm); border: 2px solid <?php echo $isActive ? 'var(--primary)' : 'var(--border)'; ?>; display: flex; align-items: center; gap: 15px;">
                            <label style="display: flex; align-items: center; gap: 10px; flex: 1; cursor: pointer; margin: 0;">
                                <input
                                    type="checkbox"
                                    name="finitions_selectionnees[]"
                                    value="<?php echo $catalogueFin['id']; ?>"
                                    <?php echo $isActive ? 'checked' : ''; ?>
                                    style="width: 20px; height: 20px; cursor: pointer;"
                                >
                                <span style="font-size: 20px;"><?php echo $catalogueFin['icone'] ?: '•'; ?></span>
                                <div style="flex: 1;">
                                    <strong><?php echo htmlspecialchars($catalogueFin['nom']); ?></strong>
                                    <br>
                                    <small style="color: #666;"><?php echo htmlspecialchars($catalogueFin['description'] ?: ''); ?></small>
                                </div>
                            </label>
                            <div style="min-width: 200px; display: flex; align-items: center; gap: 8px;">
                                <input
                                    type="number"
                                    step="0.01"
                                    name="finition_prix[<?php echo $catalogueFin['id']; ?>]"
                                    value="<?php echo $prixActuel; ?>"
                                    placeholder="<?php echo $catalogueFin['prix_defaut']; ?>"
                                    style="width: 80px; padding: 6px; border: 1px solid #ddd; border-radius: 4px; text-align: right;"
                                >
                                <span style="color: #666; font-size: 13px;">
                                    €
                                    <?php
                                    $typeLabels = [
                                        'fixe' => 'fixe',
                                        'par_m2' => '/m²',
                                        'par_ml' => '/ml',
                                        'pourcentage' => '%'
                                    ];
                                    echo $typeLabels[$catalogueFin['type_prix_defaut']] ?? 'fixe';
                                    ?>
                                </span>
                            </div>
                        </div>
                    <?php
                    endforeach;
                    if ($currentCategorie !== '') echo '</div>';
                    ?>
                </div>
                <p style="color: var(--text-muted); font-size: 12px; margin-top: 10px;">
                    💡 Le prix affiché est celui du catalogue. Modifiez-le pour personnaliser le prix pour ce produit uniquement.
                </p>

                <div class="section-title">🎁 Promotion</div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="promo_actif" value="1" <?php echo ($produit['promo_actif'] ?? 0) ? 'checked' : ''; ?>>
                        Activer une promotion sur ce produit
                    </label>
                </div>

                <div id="promo-fields" style="display: <?php echo ($produit['promo_actif'] ?? 0) ? 'block' : 'none'; ?>;">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Type de promotion</label>
                            <select name="promo_type">
                                <option value="pourcentage" <?php echo ($produit['promo_type'] ?? '') === 'pourcentage' ? 'selected' : ''; ?>>Pourcentage</option>
                                <option value="fixe" <?php echo ($produit['promo_type'] ?? '') === 'fixe' ? 'selected' : ''; ?>>Réduction fixe</option>
                                <option value="prix_special" <?php echo ($produit['promo_type'] ?? '') === 'prix_special' ? 'selected' : ''; ?>>Prix spécial</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Valeur (% ou €)</label>
                            <input type="number" step="0.01" name="promo_valeur" value="<?php echo htmlspecialchars($produit['promo_valeur'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>Prix spécial (si applicable)</label>
                            <input type="number" step="0.01" name="promo_prix_special" value="<?php echo htmlspecialchars($produit['promo_prix'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Titre promo</label>
                            <input type="text" name="promo_titre" value="<?php echo htmlspecialchars($produit['promo_titre'] ?? ''); ?>" placeholder="Ex: SOLDES D'ÉTÉ">
                        </div>
                        <div class="form-group">
                            <label>Badge</label>
                            <input type="text" name="promo_badge" value="<?php echo htmlspecialchars($produit['promo_badge'] ?? 'PROMO'); ?>" placeholder="PROMO">
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Date début</label>
                            <input type="datetime-local" name="promo_date_debut" value="<?php echo isset($produit['promo_date_debut']) ? date('Y-m-d\TH:i', strtotime($produit['promo_date_debut'])) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Date fin</label>
                            <input type="datetime-local" name="promo_date_fin" value="<?php echo isset($produit['promo_date_fin']) ? date('Y-m-d\TH:i', strtotime($produit['promo_date_fin'])) : ''; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="promo_countdown" value="1" <?php echo ($produit['promo_countdown'] ?? 0) ? 'checked' : ''; ?>>
                            Afficher un compte à rebours
                        </label>
                    </div>
                </div>

                <script>
                // Toggle promo fields
                document.querySelector('input[name="promo_actif"]').addEventListener('change', function(e) {
                    document.getElementById('promo-fields').style.display = e.target.checked ? 'block' : 'none';
                });
                </script>

        <div style="display: flex; gap: 12px; margin-top: 30px; padding-top: 20px; border-top: 2px solid var(--border);">
            <button type="submit" class="btn btn-primary">💾 Enregistrer les modifications</button>
            <a href="/admin/produits.php" class="btn btn-secondary">✖ Annuler</a>
            <a href="/admin/supprimer-produit.php?id=<?php echo urlencode($produit['code']); ?>" class="btn btn-danger" onclick="return confirm('Supprimer définitivement ce produit ?')" style="margin-left: auto;">🗑️ Supprimer le produit</a>
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
