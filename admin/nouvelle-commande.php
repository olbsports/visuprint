<?php
/**
 * Nouvelle Commande - Imprixo Admin
 * Création manuelle avec personnalisation des prix
 */

require_once __DIR__ . '/auth.php';

verifierAdminConnecte();
$admin = getAdminInfo();
$db = Database::getInstance();

$pageTitle = 'Nouvelle Commande';

$error = '';

// Charger clients et produits CSV
$clients = $db->fetchAll("SELECT id, prenom, nom, email FROM clients ORDER BY nom, prenom");

$produits = [];
$csvFile = __DIR__ . '/../CATALOGUE_COMPLET_VISUPRINT.csv';
if (file_exists($csvFile)) {
    $handle = fopen($csvFile, 'r');
    $headers = fgetcsv($handle);
    while ($row = fgetcsv($handle)) {
        if (count($row) >= 8) {
            $produits[] = [
                'code' => $row[0],
                'nom' => $row[1],
                'categorie' => $row[2],
                'prix_0_10' => floatval($row[3]),
                'prix_11_50' => floatval($row[4]),
                'prix_51_100' => floatval($row[5]),
                'prix_101_300' => floatval($row[6]),
                'prix_300_plus' => floatval($row[7]),
            ];
        }
    }
    fclose($handle);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientId = (int)$_POST['client_id'];
    $produitCode = cleanInput($_POST['produit_code']);
    $largeur = (float)$_POST['largeur'];
    $hauteur = (float)$_POST['hauteur'];
    $quantite = (int)$_POST['quantite'];
    $impression = cleanInput($_POST['impression']);
    $notes = cleanInput($_POST['notes'] ?? '');

    // NOUVEAUTÉ: Prix personnalisables
    $prixPersonnalise = isset($_POST['prix_personnalise']) && $_POST['prix_personnalise'] === '1';
    $prixUnitaireManuel = $prixPersonnalise ? (float)$_POST['prix_unitaire_manuel'] : 0;
    $remisePourcent = $prixPersonnalise ? (float)($_POST['remise_pourcent'] ?? 0) : 0;
    $remiseMontant = $prixPersonnalise ? (float)($_POST['remise_montant'] ?? 0) : 0;

    if (!$clientId || !$produitCode || !$largeur || !$hauteur || !$quantite) {
        $error = 'Tous les champs obligatoires doivent être remplis';
    } else {
        $client = $db->fetchOne("SELECT * FROM clients WHERE id = ?", [$clientId]);

        if (!$client) {
            $error = 'Client introuvable';
        } else {
            // Trouver le produit
            $produit = null;
            foreach ($produits as $p) {
                if ($p['code'] === $produitCode) {
                    $produit = $p;
                    break;
                }
            }

            if (!$produit) {
                $error = 'Produit introuvable';
            } else {
                // Calculer surface
                $surface = ($largeur * $hauteur) / 10000; // en m²
                $surfaceTotale = $surface * $quantite;

                // Prix unitaire
                if ($prixPersonnalise && $prixUnitaireManuel > 0) {
                    // Prix manuel saisi
                    $prixUnitaireM2 = $prixUnitaireManuel;
                } else {
                    // Prix dégressif automatique
                    if ($surfaceTotale > 300) {
                        $prixUnitaireM2 = $produit['prix_300_plus'];
                    } elseif ($surfaceTotale > 100) {
                        $prixUnitaireM2 = $produit['prix_101_300'];
                    } elseif ($surfaceTotale > 50) {
                        $prixUnitaireM2 = $produit['prix_51_100'];
                    } elseif ($surfaceTotale > 10) {
                        $prixUnitaireM2 = $produit['prix_11_50'];
                    } else {
                        $prixUnitaireM2 = $produit['prix_0_10'];
                    }
                }

                // Multiplicateur impression
                $multiplicateur = 1;
                if ($impression === 'double_meme') {
                    $multiplicateur = 1.5;
                } elseif ($impression === 'double_different') {
                    $multiplicateur = 1.8;
                }

                $totalHT = $prixUnitaireM2 * $surface * $quantite * $multiplicateur;

                // Appliquer remises personnalisées
                if ($prixPersonnalise) {
                    if ($remisePourcent > 0) {
                        $totalHT = $totalHT * (1 - $remisePourcent / 100);
                    }
                    if ($remiseMontant > 0) {
                        $totalHT = max(0, $totalHT - $remiseMontant);
                    }
                }

                $totalTTC = $totalHT * 1.20;

                // Créer la commande
                $numeroCommande = 'CMD-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));

                $db->query(
                    "INSERT INTO commandes (
                        numero_commande, client_id,
                        client_prenom, client_nom, client_email, client_telephone,
                        adresse_facturation, code_postal_facturation, ville_facturation, pays_facturation,
                        adresse_livraison, code_postal_livraison, ville_livraison, pays_livraison,
                        total_ht, total_tva, total_ttc,
                        statut, statut_paiement, notes_admin,
                        created_at, updated_at
                    ) VALUES (
                        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW()
                    )",
                    [
                        $numeroCommande, $clientId,
                        $client['prenom'], $client['nom'], $client['email'], $client['telephone'] ?? '',
                        $client['adresse_facturation'] ?? '', $client['code_postal_facturation'] ?? '',
                        $client['ville_facturation'] ?? '', $client['pays_facturation'] ?? 'France',
                        $client['adresse_facturation'] ?? '', $client['code_postal_facturation'] ?? '',
                        $client['ville_facturation'] ?? '', $client['pays_facturation'] ?? 'France',
                        $totalHT, $totalTTC - $totalHT, $totalTTC,
                        'nouveau', 'en_attente', $notes
                    ]
                );

                $commandeId = $db->lastInsertId();

                // Créer ligne de commande
                $db->query(
                    "INSERT INTO lignes_commande (
                        commande_id, produit_code, produit_nom, produit_categorie,
                        largeur, hauteur, surface, quantite, impression,
                        prix_unitaire_m2, prix_ligne_ht, prix_ligne_ttc,
                        created_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
                    [
                        $commandeId, $produit['code'], $produit['nom'], $produit['categorie'],
                        $largeur, $hauteur, $surface, $quantite, $impression,
                        $prixUnitaireM2, $totalHT, $totalTTC
                    ]
                );

                logAdminAction($admin['id'], 'create_commande', "Création commande manuelle $numeroCommande");

                header("Location: /admin/commande.php?id=$commandeId&created=1");
                exit;
            }
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
        <h1 class="page-title">➕ Nouvelle Commande Manuelle</h1>
        <p class="page-subtitle">Créer une commande avec prix personnalisables</p>
    </div>
    <div class="top-bar-actions">
        <a href="/admin/commandes.php" class="btn btn-secondary">← Retour</a>
    </div>
</div>

<div class="card" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-left: 4px solid var(--info);">
    <div style="display: flex; gap: 12px; align-items: start;">
        <div style="font-size: 24px;">ℹ️</div>
        <div>
            <strong style="color: var(--info);">Commande manuelle</strong>
            <p style="color: var(--text-secondary); margin-top: 4px; font-size: 14px;">
                Pour commandes téléphoniques, sur place, ou avec conditions spéciales.
                Vous pouvez personnaliser les prix et appliquer des remises.
            </p>
        </div>
    </div>
</div>

<form method="POST" id="commandeForm">
    <!-- Client -->
    <div class="card">
        <h2 class="card-title">👤 Sélection du client</h2>

        <div class="form-group">
            <label class="form-label">Client <span style="color: var(--danger);">*</span></label>
            <select name="client_id" class="form-select" required>
                <option value="">-- Choisir un client --</option>
                <?php foreach ($clients as $c): ?>
                    <option value="<?php echo $c['id']; ?>">
                        <?php echo htmlspecialchars($c['prenom'] . ' ' . $c['nom'] . ' (' . $c['email'] . ')'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <small style="color: var(--text-muted); font-size: 13px;">
                <a href="/admin/nouveau-client.php" target="_blank" style="color: var(--primary);">+ Créer un nouveau client</a>
            </small>
        </div>
    </div>

    <!-- Produit & Configuration -->
    <div class="card">
        <h2 class="card-title">🎨 Produit et Configuration</h2>

        <div class="form-group">
            <label class="form-label">Produit <span style="color: var(--danger);">*</span></label>
            <select name="produit_code" class="form-select" required id="produitSelect">
                <option value="">-- Choisir un produit --</option>
                <?php
                $lastCategorie = '';
                foreach ($produits as $p):
                    if ($p['categorie'] !== $lastCategorie):
                        if ($lastCategorie) echo '</optgroup>';
                        echo '<optgroup label="' . htmlspecialchars($p['categorie']) . '">';
                        $lastCategorie = $p['categorie'];
                    endif;
                ?>
                    <option value="<?php echo htmlspecialchars($p['code']); ?>"
                            data-prix-min="<?php echo $p['prix_300_plus']; ?>"
                            data-prix-max="<?php echo $p['prix_0_10']; ?>">
                        <?php echo htmlspecialchars($p['nom'] . ' - De ' . $p['prix_300_plus'] . '€ à ' . $p['prix_0_10'] . '€/m²'); ?>
                    </option>
                <?php endforeach; ?>
                <?php if ($lastCategorie) echo '</optgroup>'; ?>
            </select>
        </div>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
            <div class="form-group">
                <label class="form-label">Largeur (cm) <span style="color: var(--danger);">*</span></label>
                <input type="number" name="largeur" class="form-input" step="0.1" min="10" max="500" value="100" required id="largeurInput">
                <small style="color: var(--text-muted); font-size: 13px;">Entre 10 et 500 cm</small>
            </div>

            <div class="form-group">
                <label class="form-label">Hauteur (cm) <span style="color: var(--danger);">*</span></label>
                <input type="number" name="hauteur" class="form-input" step="0.1" min="10" max="500" value="100" required id="hauteurInput">
                <small style="color: var(--text-muted); font-size: 13px;">Entre 10 et 500 cm</small>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
            <div class="form-group">
                <label class="form-label">Quantité <span style="color: var(--danger);">*</span></label>
                <input type="number" name="quantite" class="form-input" min="1" value="1" required id="quantiteInput">
            </div>

            <div class="form-group">
                <label class="form-label">Type d'impression <span style="color: var(--danger);">*</span></label>
                <select name="impression" class="form-select" required>
                    <option value="simple">Simple face (×1)</option>
                    <option value="double_meme">Double face - même visuel (×1.5)</option>
                    <option value="double_different">Double face - visuels différents (×1.8)</option>
                </select>
            </div>
        </div>

        <!-- Surface calculée -->
        <div style="padding: 16px; background: var(--bg-hover); border-radius: var(--radius-md); margin-top: 16px;">
            <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 4px;">Surface totale calculée:</div>
            <div style="font-size: 24px; font-weight: 700; color: var(--primary);" id="surfaceDisplay">1.00 m²</div>
        </div>
    </div>

    <!-- NOUVEAUTÉ: Personnalisation Prix -->
    <div class="card" style="border-left: 4px solid var(--warning);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 class="card-title" style="margin: 0;">💰 Personnalisation des Prix</h2>
            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                <input type="checkbox" name="prix_personnalise" value="1" id="prixPersonnaliseCheck" style="width: auto; margin: 0;">
                <span style="font-weight: 600;">Activer la personnalisation</span>
            </label>
        </div>

        <div id="prixPersonnaliseFields" style="display: none;">
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Prix unitaire manuel (€/m²)</label>
                    <input type="number" name="prix_unitaire_manuel" class="form-input" step="0.01" min="0" placeholder="Laisser vide pour auto">
                    <small style="color: var(--text-muted); font-size: 13px;">Si vide, prix dégressif auto</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Remise (%)</label>
                    <input type="number" name="remise_pourcent" class="form-input" step="0.1" min="0" max="100" placeholder="Ex: 15">
                    <small style="color: var(--text-muted); font-size: 13px;">Pourcentage de réduction</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Remise fixe (€)</label>
                    <input type="number" name="remise_montant" class="form-input" step="0.01" min="0" placeholder="Ex: 50">
                    <small style="color: var(--text-muted); font-size: 13px;">Montant en euros</small>
                </div>
            </div>

            <div style="padding: 16px; background: #fff3cd; border-left: 4px solid var(--warning); border-radius: var(--radius-md); margin-top: 16px;">
                <strong style="color: var(--warning);">⚠️ Attention:</strong>
                <p style="color: var(--text-secondary); margin-top: 4px; font-size: 14px;">
                    Les remises et prix personnalisés s'appliquent après le calcul automatique.
                    Utilisez avec précaution pour les conditions commerciales spéciales.
                </p>
            </div>
        </div>
    </div>

    <!-- Notes -->
    <div class="card">
        <h2 class="card-title">📝 Notes internes</h2>

        <div class="form-group">
            <label class="form-label">Notes administrateur</label>
            <textarea name="notes" class="form-textarea" rows="4" placeholder="Notes, remarques, fichiers reçus, conditions spéciales..."></textarea>
            <small style="color: var(--text-muted); font-size: 13px;">Visibles uniquement par les administrateurs</small>
        </div>
    </div>

    <!-- Actions -->
    <div style="display: flex; gap: 12px; justify-content: flex-end;">
        <a href="/admin/commandes.php" class="btn btn-secondary">Annuler</a>
        <button type="submit" class="btn btn-primary" style="font-size: 16px;">✓ Créer la commande</button>
    </div>
</form>

<script>
// Calculer surface en temps réel
function calculateSurface() {
    const largeur = parseFloat(document.getElementById('largeurInput').value) || 0;
    const hauteur = parseFloat(document.getElementById('hauteurInput').value) || 0;
    const quantite = parseInt(document.getElementById('quantiteInput').value) || 1;

    const surface = (largeur * hauteur / 10000) * quantite;
    document.getElementById('surfaceDisplay').textContent = surface.toFixed(2) + ' m²';
}

document.getElementById('largeurInput').addEventListener('input', calculateSurface);
document.getElementById('hauteurInput').addEventListener('input', calculateSurface);
document.getElementById('quantiteInput').addEventListener('input', calculateSurface);

// Toggle personnalisation prix
document.getElementById('prixPersonnaliseCheck').addEventListener('change', function() {
    document.getElementById('prixPersonnaliseFields').style.display = this.checked ? 'block' : 'none';
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
