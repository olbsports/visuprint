<?php
/**
 * Création Commande Manuelle - Imprixo Admin
 * Pour commandes téléphoniques ou sur place
 */

require_once __DIR__ . '/auth.php';

verifierAdminConnecte();
$admin = getAdminInfo();
$db = Database::getInstance();

$error = '';
$success = '';

// Récupérer la liste des clients pour l'autocomplete
$clients = $db->fetchAll("SELECT id, prenom, nom, email FROM clients ORDER BY nom, prenom");

// Récupérer les produits depuis le CSV
$produits = [];
$csvFile = __DIR__ . '/../CATALOGUE_COMPLET_VISUPRINT.csv';
if (file_exists($csvFile)) {
    $handle = fopen($csvFile, 'r');
    $headers = fgetcsv($handle);
    while ($row = fgetcsv($handle)) {
        $produits[] = [
            'code' => $row[0],
            'nom' => $row[1],
            'categorie' => $row[2],
            'prix_0_10' => $row[3],
            'prix_11_50' => $row[4],
            'prix_51_100' => $row[5],
            'prix_101_300' => $row[6],
            'prix_300_plus' => $row[7],
        ];
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
    $notes = cleanInput($_POST['notes']);

    // Validation
    if (!$clientId || !$produitCode || !$largeur || !$hauteur || !$quantite) {
        $error = 'Tous les champs obligatoires doivent être remplis';
    } else {
        // Récupérer le client
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
                // Calculer le prix
                $surface = ($largeur * $hauteur) / 10000; // en m²
                $surfaceTotale = $surface * $quantite;

                // Prix dégressif
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

                // Multiplicateur impression
                $multiplicateur = 1;
                if ($impression === 'double_meme') {
                    $multiplicateur = 1.5;
                } elseif ($impression === 'double_different') {
                    $multiplicateur = 1.8;
                }

                $totalHT = $prixUnitaireM2 * $surface * $quantite * $multiplicateur;
                $totalTTC = $totalHT * 1.20;

                // Créer la commande
                $numeroCommande = 'CMD-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

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

                $commandeId = $db->getLastInsertId();

                // Créer la ligne de commande
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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Commande - Imprixo Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f5f7fa;
            color: #2c3e50;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 40px;
        }

        .back-link {
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 10px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #17a2b8;
        }

        .section {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #ecf0f1;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
            color: #555;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group textarea {
            min-height: 80px;
            resize: vertical;
            font-family: inherit;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
        }

        .btn {
            padding: 14px 28px;
            border-radius: 8px;
            border: none;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
            margin-left: 10px;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        .form-footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #ecf0f1;
        }

        .required {
            color: #e74c3c;
        }

        .help-text {
            font-size: 13px;
            color: #7f8c8d;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="/admin/commandes.php" class="back-link">← Retour aux commandes</a>
        <h1>📦 Nouvelle commande manuelle</h1>
    </div>

    <div class="container">
        <div class="alert-info">
            <strong>ℹ️ Commande manuelle</strong><br>
            Utilisez ce formulaire pour créer une commande pour un client existant (commande téléphonique, sur place, etc.).
            La commande sera créée avec le statut "Nouveau" et le paiement "En attente".
        </div>

        <?php if ($error): ?>
            <div class="alert-error">✗ <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <!-- Sélection du client -->
            <div class="section">
                <h2 class="section-title">👤 Client</h2>

                <div class="form-group">
                    <label>Sélectionner un client <span class="required">*</span></label>
                    <select name="client_id" required>
                        <option value="">-- Choisir un client --</option>
                        <?php foreach ($clients as $c): ?>
                            <option value="<?php echo $c['id']; ?>" <?php echo (isset($_POST['client_id']) && $_POST['client_id'] == $c['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($c['prenom'] . ' ' . $c['nom'] . ' (' . $c['email'] . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="help-text">Le client doit exister dans la base. <a href="nouveau-client.php" target="_blank">Créer un nouveau client</a></div>
                </div>
            </div>

            <!-- Configuration du produit -->
            <div class="section">
                <h2 class="section-title">🎨 Produit et Configuration</h2>

                <div class="form-group">
                    <label>Produit <span class="required">*</span></label>
                    <select name="produit_code" required>
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
                            <option value="<?php echo htmlspecialchars($p['code']); ?>" <?php echo (isset($_POST['produit_code']) && $_POST['produit_code'] == $p['code']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($p['nom'] . ' (' . $p['code'] . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                        <?php if ($lastCategorie) echo '</optgroup>'; ?>
                    </select>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Largeur (cm) <span class="required">*</span></label>
                        <input type="number" name="largeur" step="0.1" min="1" value="<?php echo htmlspecialchars($_POST['largeur'] ?? '100'); ?>" required>
                        <div class="help-text">Entre 10 et 500 cm</div>
                    </div>

                    <div class="form-group">
                        <label>Hauteur (cm) <span class="required">*</span></label>
                        <input type="number" name="hauteur" step="0.1" min="1" value="<?php echo htmlspecialchars($_POST['hauteur'] ?? '100'); ?>" required>
                        <div class="help-text">Entre 10 et 500 cm</div>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Quantité <span class="required">*</span></label>
                        <input type="number" name="quantite" min="1" value="<?php echo htmlspecialchars($_POST['quantite'] ?? '1'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Type d'impression <span class="required">*</span></label>
                        <select name="impression" required>
                            <option value="simple">Simple face</option>
                            <option value="double_meme">Double face - même visuel (×1.5)</option>
                            <option value="double_different">Double face - visuels différents (×1.8)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Notes internes</label>
                    <textarea name="notes" placeholder="Notes, remarques, fichiers reçus, etc."><?php echo htmlspecialchars($_POST['notes'] ?? ''); ?></textarea>
                    <div class="help-text">Ces notes ne seront visibles que par les administrateurs</div>
                </div>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary">✓ Créer la commande</button>
                <a href="/admin/commandes.php" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>
