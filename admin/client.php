<?php
/**
 * Détail Client - Imprixo Admin
 */

require_once __DIR__ . '/auth.php';

verifierAdminConnecte();
$admin = getAdminInfo();
$db = Database::getInstance();

$clientId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$clientId) {
    header('Location: /admin/clients.php');
    exit;
}

// Récupérer le client
$client = $db->fetchOne("SELECT * FROM clients WHERE id = ?", [$clientId]);

if (!$client) {
    die('Client non trouvé');
}

$pageTitle = 'Client: ' . $client['prenom'] . ' ' . $client['nom'];

// Traitement des actions
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'update':
            $prenom = cleanInput($_POST['prenom']);
            $nom = cleanInput($_POST['nom']);
            $email = cleanInput($_POST['email']);
            $telephone = cleanInput($_POST['telephone']);
            $entreprise = cleanInput($_POST['entreprise']);
            $siret = cleanInput($_POST['siret']);
            $adresse_facturation = cleanInput($_POST['adresse_facturation']);
            $code_postal_facturation = cleanInput($_POST['code_postal_facturation']);
            $ville_facturation = cleanInput($_POST['ville_facturation']);
            $pays_facturation = cleanInput($_POST['pays_facturation']);

            if (!$prenom || !$nom || !$email) {
                $error = 'Le prénom, nom et email sont obligatoires';
                break;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Email invalide';
                break;
            }

            // Vérifier si email existe déjà
            $existingClient = $db->fetchOne(
                "SELECT id FROM clients WHERE email = ? AND id != ?",
                [$email, $clientId]
            );

            if ($existingClient) {
                $error = 'Cet email est déjà utilisé par un autre client';
                break;
            }

            $db->query(
                "UPDATE clients SET
                    prenom = ?, nom = ?, email = ?, telephone = ?, entreprise = ?, siret = ?,
                    adresse_facturation = ?, code_postal_facturation = ?, ville_facturation = ?, pays_facturation = ?,
                    updated_at = NOW()
                WHERE id = ?",
                [$prenom, $nom, $email, $telephone, $entreprise, $siret,
                 $adresse_facturation, $code_postal_facturation, $ville_facturation, $pays_facturation,
                 $clientId]
            );

            logAdminAction($admin['id'], 'update_client', "Modification client {$client['email']}");
            $success = 'Client mis à jour avec succès';

            // Recharger
            $client = $db->fetchOne("SELECT * FROM clients WHERE id = ?", [$clientId]);
            break;

        case 'delete':
            $nbCommandes = $db->fetchOne(
                "SELECT COUNT(*) as count FROM commandes WHERE client_id = ?",
                [$clientId]
            )['count'];

            if ($nbCommandes > 0) {
                $error = "Impossible de supprimer ce client : il a $nbCommandes commande(s) associée(s)";
                break;
            }

            $db->query("DELETE FROM clients WHERE id = ?", [$clientId]);
            logAdminAction($admin['id'], 'delete_client', "Suppression client {$client['email']}");

            header('Location: /admin/clients.php?deleted=1');
            exit;
    }
}

// Récupérer les commandes
$commandes = $db->fetchAll(
    "SELECT * FROM commandes WHERE client_id = ? ORDER BY created_at DESC",
    [$clientId]
);

// Stats
$stats = $db->fetchOne(
    "SELECT COUNT(*) as nb_commandes, COALESCE(SUM(total_ttc), 0) as ca_total, COALESCE(AVG(total_ttc), 0) as panier_moyen
     FROM commandes WHERE client_id = ? AND statut_paiement = 'paye'",
    [$clientId]
);

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
        <h1 class="page-title">👤 <?php echo htmlspecialchars($client['prenom'] . ' ' . $client['nom']); ?></h1>
        <p class="page-subtitle">Inscrit le <?php echo date('d/m/Y', strtotime($client['created_at'])); ?></p>
    </div>
    <div class="top-bar-actions">
        <a href="/admin/clients.php" class="btn btn-secondary">← Retour</a>
    </div>
</div>

<!-- Stats rapides -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 24px;">
    <div class="card" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; border: none;">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Commandes payées</div>
        <div style="font-size: 36px; font-weight: 700;"><?php echo $stats['nb_commandes']; ?></div>
        <div style="font-size: 12px; opacity: 0.8; margin-top: 8px;">📦 Total</div>
    </div>

    <div class="card" style="background: linear-gradient(135deg, var(--success) 0%, #059669 100%); color: white; border: none;">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">CA total</div>
        <div style="font-size: 36px; font-weight: 700;"><?php echo number_format($stats['ca_total'], 0, ',', ' '); ?> €</div>
        <div style="font-size: 12px; opacity: 0.8; margin-top: 8px;">💰 Chiffre d'affaires</div>
    </div>

    <div class="card" style="background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%); color: white; border: none;">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Panier moyen</div>
        <div style="font-size: 36px; font-weight: 700;"><?php echo number_format($stats['panier_moyen'], 0, ',', ' '); ?> €</div>
        <div style="font-size: 12px; opacity: 0.8; margin-top: 8px;">📊 Moyenne</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    <!-- Formulaire principal -->
    <div>
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">✏️ Informations du client</h2>
            </div>

            <form method="POST">
                <input type="hidden" name="action" value="update">

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                    <div class="form-group">
                        <label class="form-label">Prénom <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="prenom" class="form-input" value="<?php echo htmlspecialchars($client['prenom']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nom <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="nom" class="form-input" value="<?php echo htmlspecialchars($client['nom']); ?>" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                    <div class="form-group">
                        <label class="form-label">Email <span style="color: var(--danger);">*</span></label>
                        <input type="email" name="email" class="form-input" value="<?php echo htmlspecialchars($client['email']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Téléphone</label>
                        <input type="tel" name="telephone" class="form-input" value="<?php echo htmlspecialchars($client['telephone'] ?? ''); ?>">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                    <div class="form-group">
                        <label class="form-label">Entreprise</label>
                        <input type="text" name="entreprise" class="form-input" value="<?php echo htmlspecialchars($client['entreprise'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">SIRET</label>
                        <input type="text" name="siret" class="form-input" value="<?php echo htmlspecialchars($client['siret'] ?? ''); ?>">
                    </div>
                </div>

                <h3 style="margin: 25px 0 15px; font-size: 16px; color: var(--text-secondary);">📍 Adresse de facturation</h3>

                <div class="form-group">
                    <label class="form-label">Adresse</label>
                    <input type="text" name="adresse_facturation" class="form-input" value="<?php echo htmlspecialchars($client['adresse_facturation'] ?? ''); ?>">
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                    <div class="form-group">
                        <label class="form-label">Code postal</label>
                        <input type="text" name="code_postal_facturation" class="form-input" value="<?php echo htmlspecialchars($client['code_postal_facturation'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ville</label>
                        <input type="text" name="ville_facturation" class="form-input" value="<?php echo htmlspecialchars($client['ville_facturation'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Pays</label>
                    <input type="text" name="pays_facturation" class="form-input" value="<?php echo htmlspecialchars($client['pays_facturation'] ?? 'France'); ?>">
                </div>

                <button type="submit" class="btn btn-primary">💾 Enregistrer les modifications</button>
            </form>
        </div>

        <!-- Historique commandes -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">📦 Historique des commandes (<?php echo count($commandes); ?>)</h2>
            </div>

            <?php if (empty($commandes)): ?>
                <div style="text-align: center; padding: 60px 20px;">
                    <div style="font-size: 64px; margin-bottom: 16px;">📦</div>
                    <p style="color: var(--text-secondary);">Aucune commande pour ce client</p>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>N° Commande</th>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($commandes as $cmd): ?>
                                <tr>
                                    <td><strong style="color: var(--primary);"><?php echo htmlspecialchars($cmd['numero_commande']); ?></strong></td>
                                    <td><?php echo date('d/m/Y', strtotime($cmd['created_at'])); ?></td>
                                    <td><strong><?php echo number_format($cmd['total_ttc'], 2, ',', ' '); ?> €</strong></td>
                                    <td>
                                        <?php
                                        $statutBadges = [
                                            'nouveau' => 'info',
                                            'confirme' => 'success',
                                            'en_production' => 'warning',
                                            'expedie' => 'success',
                                            'livre' => 'success',
                                            'annule' => 'danger'
                                        ];
                                        $badgeClass = $statutBadges[$cmd['statut']] ?? 'info';
                                        ?>
                                        <span class="badge badge-<?php echo $badgeClass; ?>"><?php echo ucfirst(str_replace('_', ' ', $cmd['statut'])); ?></span>
                                    </td>
                                    <td>
                                        <a href="/admin/commande.php?id=<?php echo $cmd['id']; ?>" class="btn btn-primary btn-sm">👁️ Voir</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Sidebar -->
    <div>
        <!-- Avatar -->
        <div class="card" style="text-align: center;">
            <div style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 40px; margin: 0 auto 16px;">
                <?php echo strtoupper(substr($client['prenom'], 0, 1) . substr($client['nom'], 0, 1)); ?>
            </div>
            <div style="font-size: 20px; font-weight: 700; margin-bottom: 8px; color: var(--text-primary);">
                <?php echo htmlspecialchars($client['prenom'] . ' ' . $client['nom']); ?>
            </div>
            <div style="color: var(--text-muted); font-size: 14px;">
                <?php echo htmlspecialchars($client['email']); ?>
            </div>
        </div>

        <!-- Zone de danger -->
        <div class="card" style="background: #fff5f5; border: 2px dashed var(--danger);">
            <h3 style="color: var(--danger); margin-bottom: 12px; font-size: 18px;">⚠️ Zone de danger</h3>
            <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 16px;">
                La suppression d'un client est définitive et irréversible.
            </p>

            <?php if (count($commandes) > 0): ?>
                <div style="background: #ffe5e5; padding: 12px; border-radius: 8px; color: var(--danger); font-size: 14px;">
                    <strong>Impossible de supprimer :</strong><br>
                    Ce client a <?php echo count($commandes); ?> commande(s) associée(s)
                </div>
            <?php else: ?>
                <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce client ? Cette action est irréversible.');">
                    <input type="hidden" name="action" value="delete">
                    <button type="submit" class="btn btn-danger btn-sm">🗑️ Supprimer ce client</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
