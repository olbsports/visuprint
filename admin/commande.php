<?php
/**
 * Détail Commande - Imprixo Admin
 */

require_once __DIR__ . '/auth.php';

verifierAdminConnecte();
$admin = getAdminInfo();
$db = Database::getInstance();

$commandeId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$commandeId) {
    header('Location: /admin/commandes.php');
    exit;
}

// Récupérer la commande
$commande = $db->fetchOne("SELECT * FROM commandes WHERE id = ?", [$commandeId]);

if (!$commande) {
    die('Commande non trouvée');
}

$pageTitle = 'Commande ' . $commande['numero_commande'];

// Récupérer les lignes
$lignes = $db->fetchAll("SELECT * FROM lignes_commande WHERE commande_id = ?", [$commandeId]);

// Traitement des actions
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'update_statut':
            $nouveauStatut = cleanInput($_POST['statut']);
            $db->query(
                "UPDATE commandes SET statut = ?, updated_at = NOW() WHERE id = ?",
                [$nouveauStatut, $commandeId]
            );
            logAdminAction($admin['id'], 'update_commande', "Statut commande {$commande['numero_commande']} -> $nouveauStatut");
            $success = 'Statut mis à jour avec succès';
            $commande = $db->fetchOne("SELECT * FROM commandes WHERE id = ?", [$commandeId]);
            break;

        case 'add_tracking':
            $transporteur = cleanInput($_POST['transporteur']);
            $numeroSuivi = cleanInput($_POST['numero_suivi']);
            $db->query(
                "UPDATE commandes
                SET transporteur = ?, numero_suivi = ?, date_expedition = NOW(), statut = 'expedie'
                WHERE id = ?",
                [$transporteur, $numeroSuivi, $commandeId]
            );
            logAdminAction($admin['id'], 'add_tracking', "Ajout suivi commande {$commande['numero_commande']}");
            $success = 'Informations de suivi ajoutées';
            $commande = $db->fetchOne("SELECT * FROM commandes WHERE id = ?", [$commandeId]);
            break;

        case 'add_note':
            $note = cleanInput($_POST['note']);
            $db->query("UPDATE commandes SET notes_admin = ? WHERE id = ?", [$note, $commandeId]);
            logAdminAction($admin['id'], 'add_note', "Note ajoutée commande {$commande['numero_commande']}");
            $success = 'Note enregistrée';
            $commande = $db->fetchOne("SELECT * FROM commandes WHERE id = ?", [$commandeId]);
            break;
    }
}

include __DIR__ . '/includes/header.php';
?>

<?php if ($success): ?>
    <div class="alert alert-success">✓ <?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<div class="top-bar">
    <div>
        <h1 class="page-title">📦 Commande <?php echo htmlspecialchars($commande['numero_commande']); ?></h1>
        <p class="page-subtitle"><?php echo date('d/m/Y H:i', strtotime($commande['created_at'])); ?></p>
    </div>
    <div class="top-bar-actions">
        <a href="/admin/commandes.php" class="btn btn-secondary">← Retour</a>
    </div>
</div>

<!-- Informations principales -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">📋 Informations générales</h2>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        <div style="padding: 16px; background: var(--bg-hover); border-radius: var(--radius-md); border-left: 4px solid var(--primary);">
            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">CLIENT</div>
            <div style="font-weight: 600; margin-bottom: 8px;">
                <?php echo htmlspecialchars($commande['client_prenom'] . ' ' . $commande['client_nom']); ?>
            </div>
            <div style="font-size: 14px; color: var(--text-secondary);">
                <?php echo htmlspecialchars($commande['client_email']); ?>
            </div>
            <?php if ($commande['client_telephone']): ?>
                <div style="font-size: 14px; color: var(--text-secondary); margin-top: 4px;">
                    📞 <?php echo htmlspecialchars($commande['client_telephone']); ?>
                </div>
            <?php endif; ?>
        </div>

        <div style="padding: 16px; background: var(--bg-hover); border-radius: var(--radius-md); border-left: 4px solid var(--success);">
            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">MONTANT TOTAL</div>
            <div style="font-size: 28px; font-weight: 700; color: var(--success);">
                <?php echo number_format($commande['total_ttc'], 2, ',', ' '); ?> €
            </div>
            <div style="font-size: 14px; color: var(--text-secondary); margin-top: 4px;">
                HT: <?php echo number_format($commande['total_ht'], 2, ',', ' '); ?> €
            </div>
        </div>

        <div style="padding: 16px; background: var(--bg-hover); border-radius: var(--radius-md); border-left: 4px solid var(--info);">
            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">STATUT</div>
            <div style="margin-bottom: 8px;">
                <?php
                $statutBadges = [
                    'nouveau' => 'info',
                    'confirme' => 'info',
                    'en_production' => 'warning',
                    'expedie' => 'success',
                    'livre' => 'success',
                    'annule' => 'danger'
                ];
                $badgeClass = $statutBadges[$commande['statut']] ?? 'info';
                ?>
                <span class="badge badge-<?php echo $badgeClass; ?>">
                    <?php echo ucfirst(str_replace('_', ' ', $commande['statut'])); ?>
                </span>
            </div>
        </div>

        <div style="padding: 16px; background: var(--bg-hover); border-radius: var(--radius-md); border-left: 4px solid var(--warning);">
            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">PAIEMENT</div>
            <div style="margin-bottom: 8px;">
                <?php
                $paiementBadges = [
                    'paye' => 'success',
                    'en_attente' => 'warning',
                    'refuse' => 'danger'
                ];
                $badgeClass = $paiementBadges[$commande['statut_paiement']] ?? 'warning';
                ?>
                <span class="badge badge-<?php echo $badgeClass; ?>">
                    <?php echo ucfirst(str_replace('_', ' ', $commande['statut_paiement'])); ?>
                </span>
            </div>
            <?php if ($commande['date_paiement']): ?>
                <div style="font-size: 12px; color: var(--text-secondary);">
                    Le <?php echo date('d/m/Y H:i', strtotime($commande['date_paiement'])); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Produits commandés -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">🛒 Produits commandés</h2>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Configuration</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Total TTC</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lignes as $ligne): ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($ligne['produit_nom']); ?></strong><br>
                            <small style="color: var(--text-muted);">Réf: <?php echo htmlspecialchars($ligne['produit_code']); ?></small>
                        </td>
                        <td>
                            <?php echo $ligne['surface']; ?> m²
                            <?php if ($ligne['largeur'] && $ligne['hauteur']): ?>
                                <br><small>(<?php echo $ligne['largeur']; ?>×<?php echo $ligne['hauteur']; ?>cm)</small>
                            <?php endif; ?>
                            <br><small><?php echo ucfirst($ligne['impression']); ?> face</small>
                        </td>
                        <td><?php echo $ligne['quantite']; ?></td>
                        <td><?php echo number_format($ligne['prix_unitaire_m2'], 2, ',', ' '); ?> €/m²</td>
                        <td><strong><?php echo number_format($ligne['prix_ligne_ttc'], 2, ',', ' '); ?> €</strong></td>
                    </tr>
                <?php endforeach; ?>
                <tr style="background: var(--bg-hover); font-weight: 700;">
                    <td colspan="4" style="text-align: right;">TOTAL TTC</td>
                    <td style="font-size: 18px; color: var(--success);">
                        <?php echo number_format($commande['total_ttc'], 2, ',', ' '); ?> €
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Adresses -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">📍 Adresses</h2>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div>
            <h3 style="margin-bottom: 12px; font-size: 14px; color: var(--text-secondary); font-weight: 600;">Facturation</h3>
            <div style="padding: 16px; background: var(--bg-hover); border-radius: var(--radius-md); border-left: 4px solid var(--primary);">
                <?php echo nl2br(htmlspecialchars($commande['adresse_facturation'])); ?><br>
                <?php echo htmlspecialchars($commande['code_postal_facturation']); ?>
                <?php echo htmlspecialchars($commande['ville_facturation']); ?><br>
                <?php echo htmlspecialchars($commande['pays_facturation']); ?>
            </div>
        </div>

        <div>
            <h3 style="margin-bottom: 12px; font-size: 14px; color: var(--text-secondary); font-weight: 600;">Livraison</h3>
            <div style="padding: 16px; background: var(--bg-hover); border-radius: var(--radius-md); border-left: 4px solid var(--info);">
                <?php echo nl2br(htmlspecialchars($commande['adresse_livraison'])); ?><br>
                <?php echo htmlspecialchars($commande['code_postal_livraison']); ?>
                <?php echo htmlspecialchars($commande['ville_livraison']); ?><br>
                <?php echo htmlspecialchars($commande['pays_livraison']); ?>
            </div>
        </div>
    </div>
</div>

<!-- Actions -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    <!-- Changer statut -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">🔄 Changer le statut</h2>
        </div>

        <form method="POST">
            <input type="hidden" name="action" value="update_statut">

            <div class="form-group">
                <label class="form-label">Nouveau statut</label>
                <select name="statut" class="form-select">
                    <option value="nouveau" <?php echo $commande['statut'] === 'nouveau' ? 'selected' : ''; ?>>Nouveau</option>
                    <option value="confirme" <?php echo $commande['statut'] === 'confirme' ? 'selected' : ''; ?>>Confirmé</option>
                    <option value="en_production" <?php echo $commande['statut'] === 'en_production' ? 'selected' : ''; ?>>En production</option>
                    <option value="expedie" <?php echo $commande['statut'] === 'expedie' ? 'selected' : ''; ?>>Expédié</option>
                    <option value="livre" <?php echo $commande['statut'] === 'livre' ? 'selected' : ''; ?>>Livré</option>
                    <option value="annule" <?php echo $commande['statut'] === 'annule' ? 'selected' : ''; ?>>Annulé</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>

    <!-- Suivi -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">📦 Informations d'expédition</h2>
        </div>

        <?php if ($commande['numero_suivi']): ?>
            <div style="background: var(--bg-success); padding: 16px; border-radius: var(--radius-md); margin-bottom: 16px; border-left: 4px solid var(--success);">
                <strong>Transporteur:</strong> <?php echo htmlspecialchars($commande['transporteur']); ?><br>
                <strong>Numéro de suivi:</strong> <?php echo htmlspecialchars($commande['numero_suivi']); ?><br>
                <strong>Date d'expédition:</strong> <?php echo date('d/m/Y', strtotime($commande['date_expedition'])); ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="action" value="add_tracking">

            <div class="form-group">
                <label class="form-label">Transporteur</label>
                <input type="text" name="transporteur" class="form-input" value="<?php echo htmlspecialchars($commande['transporteur'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Numéro de suivi</label>
                <input type="text" name="numero_suivi" class="form-input" value="<?php echo htmlspecialchars($commande['numero_suivi'] ?? ''); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
</div>

<!-- Notes -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">📝 Notes internes</h2>
    </div>

    <form method="POST">
        <input type="hidden" name="action" value="add_note">

        <div class="form-group">
            <textarea name="note" class="form-textarea" rows="5" placeholder="Notes internes (non visibles par le client)"><?php echo htmlspecialchars($commande['notes_admin'] ?? ''); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer la note</button>
    </form>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
