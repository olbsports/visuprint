<?php
/**
 * Détail / Édition Client - Imprixo Admin
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
$client = $db->fetchOne(
    "SELECT * FROM clients WHERE id = ?",
    [$clientId]
);

if (!$client) {
    die('Client non trouvé');
}

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

            // Validation
            if (!$prenom || !$nom || !$email) {
                $error = 'Le prénom, nom et email sont obligatoires';
                break;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Email invalide';
                break;
            }

            // Vérifier si email existe déjà (sauf pour ce client)
            $existingClient = $db->fetchOne(
                "SELECT id FROM clients WHERE email = ? AND id != ?",
                [$email, $clientId]
            );

            if ($existingClient) {
                $error = 'Cet email est déjà utilisé par un autre client';
                break;
            }

            // Mettre à jour
            $db->query(
                "UPDATE clients SET
                    prenom = ?,
                    nom = ?,
                    email = ?,
                    telephone = ?,
                    entreprise = ?,
                    siret = ?,
                    adresse_facturation = ?,
                    code_postal_facturation = ?,
                    ville_facturation = ?,
                    pays_facturation = ?,
                    updated_at = NOW()
                WHERE id = ?",
                [
                    $prenom, $nom, $email, $telephone, $entreprise, $siret,
                    $adresse_facturation, $code_postal_facturation, $ville_facturation, $pays_facturation,
                    $clientId
                ]
            );

            logAdminAction($admin['id'], 'update_client', "Modification client {$client['email']}");

            $success = 'Client mis à jour avec succès';

            // Recharger les données
            $client = $db->fetchOne("SELECT * FROM clients WHERE id = ?", [$clientId]);
            break;

        case 'delete':
            // Vérifier si le client a des commandes
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
            break;
    }
}

// Récupérer les commandes du client
$commandes = $db->fetchAll(
    "SELECT * FROM commandes
    WHERE client_id = ?
    ORDER BY created_at DESC",
    [$clientId]
);

// Stats client
$stats = $db->fetchOne(
    "SELECT
        COUNT(*) as nb_commandes,
        COALESCE(SUM(total_ttc), 0) as ca_total,
        COALESCE(AVG(total_ttc), 0) as panier_moyen
    FROM commandes
    WHERE client_id = ? AND statut_paiement = 'paye'",
    [$clientId]
);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client: <?php echo htmlspecialchars($client['prenom'] . ' ' . $client['nom']); ?> - Admin</title>
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
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left-color: #28a745;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left-color: #dc3545;
        }

        .main-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ecf0f1;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
            color: #555;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
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

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }

        .stat-label {
            font-size: 12px;
            color: #7f8c8d;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
        }

        .client-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 32px;
            margin: 0 auto 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            text-align: left;
            padding: 12px;
            background: #f8f9fa;
            color: #666;
            font-weight: 600;
            font-size: 13px;
        }

        td {
            padding: 15px 12px;
            border-bottom: 1px solid #ecf0f1;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge.nouveau { background: #3498db; color: white; }
        .badge.expedie { background: #27ae60; color: white; }
        .badge.paye { background: #27ae60; color: white; }

        .delete-section {
            background: #fff5f5;
            border: 2px dashed #e74c3c;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .delete-section h3 {
            color: #e74c3c;
            margin-bottom: 10px;
        }

        .delete-section p {
            color: #666;
            margin-bottom: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="/admin/clients.php" class="back-link">← Retour à la liste des clients</a>
        <h1>👤 Client: <?php echo htmlspecialchars($client['prenom'] . ' ' . $client['nom']); ?></h1>
    </div>

    <div class="container">
        <?php if ($success): ?>
            <div class="alert alert-success">✓ <?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error">✗ <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="main-grid">
            <!-- Formulaire d'édition -->
            <div>
                <div class="section">
                    <h2 class="section-title">✏️ Informations du client</h2>

                    <form method="POST">
                        <input type="hidden" name="action" value="update">

                        <div class="form-grid">
                            <div class="form-group">
                                <label>Prénom *</label>
                                <input type="text" name="prenom" value="<?php echo htmlspecialchars($client['prenom']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Nom *</label>
                                <input type="text" name="nom" value="<?php echo htmlspecialchars($client['nom']); ?>" required>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label>Email *</label>
                                <input type="email" name="email" value="<?php echo htmlspecialchars($client['email']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Téléphone</label>
                                <input type="tel" name="telephone" value="<?php echo htmlspecialchars($client['telephone'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label>Entreprise</label>
                                <input type="text" name="entreprise" value="<?php echo htmlspecialchars($client['entreprise'] ?? ''); ?>">
                            </div>

                            <div class="form-group">
                                <label>SIRET</label>
                                <input type="text" name="siret" value="<?php echo htmlspecialchars($client['siret'] ?? ''); ?>">
                            </div>
                        </div>

                        <h3 style="margin: 25px 0 15px; font-size: 16px; color: #666;">📍 Adresse de facturation</h3>

                        <div class="form-group">
                            <label>Adresse</label>
                            <input type="text" name="adresse_facturation" value="<?php echo htmlspecialchars($client['adresse_facturation'] ?? ''); ?>">
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label>Code postal</label>
                                <input type="text" name="code_postal_facturation" value="<?php echo htmlspecialchars($client['code_postal_facturation'] ?? ''); ?>">
                            </div>

                            <div class="form-group">
                                <label>Ville</label>
                                <input type="text" name="ville_facturation" value="<?php echo htmlspecialchars($client['ville_facturation'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Pays</label>
                            <input type="text" name="pays_facturation" value="<?php echo htmlspecialchars($client['pays_facturation'] ?? 'France'); ?>">
                        </div>

                        <button type="submit" class="btn btn-primary">💾 Enregistrer les modifications</button>
                    </form>
                </div>

                <!-- Historique des commandes -->
                <div class="section">
                    <h2 class="section-title">📦 Historique des commandes (<?php echo count($commandes); ?>)</h2>

                    <?php if (empty($commandes)): ?>
                        <p style="text-align: center; color: #999; padding: 40px 0;">Aucune commande pour ce client</p>
                    <?php else: ?>
                        <table>
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
                                        <td><strong><?php echo htmlspecialchars($cmd['numero_commande']); ?></strong></td>
                                        <td><?php echo date('d/m/Y', strtotime($cmd['created_at'])); ?></td>
                                        <td><strong><?php echo number_format($cmd['total_ttc'], 2, ',', ' '); ?> €</strong></td>
                                        <td><span class="badge <?php echo $cmd['statut']; ?>"><?php echo ucfirst($cmd['statut']); ?></span></td>
                                        <td><a href="commande.php?id=<?php echo $cmd['id']; ?>" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">Voir</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Avatar et infos -->
                <div class="section">
                    <div class="client-avatar">
                        <?php echo strtoupper(substr($client['prenom'], 0, 1) . substr($client['nom'], 0, 1)); ?>
                    </div>

                    <p style="text-align: center; color: #666; margin-bottom: 20px;">
                        Inscrit le <?php echo date('d/m/Y', strtotime($client['created_at'])); ?>
                    </p>

                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-label">Commandes payées</div>
                            <div class="stat-value" style="color: #667eea;"><?php echo $stats['nb_commandes']; ?></div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-label">CA total</div>
                            <div class="stat-value" style="color: #27ae60;"><?php echo number_format($stats['ca_total'], 2, ',', ' '); ?> €</div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-label">Panier moyen</div>
                            <div class="stat-value" style="color: #f39c12;"><?php echo number_format($stats['panier_moyen'], 2, ',', ' '); ?> €</div>
                        </div>
                    </div>
                </div>

                <!-- Zone de danger -->
                <div class="section delete-section">
                    <h3>⚠️ Zone de danger</h3>
                    <p>La suppression d'un client est définitive et irréversible.</p>

                    <?php if (count($commandes) > 0): ?>
                        <p style="background: #ffe5e5; padding: 10px; border-radius: 5px; color: #c0392b;">
                            <strong>Impossible de supprimer :</strong><br>
                            Ce client a <?php echo count($commandes); ?> commande(s) associée(s)
                        </p>
                    <?php else: ?>
                        <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce client ? Cette action est irréversible.');">
                            <input type="hidden" name="action" value="delete">
                            <button type="submit" class="btn btn-danger">🗑️ Supprimer ce client</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
