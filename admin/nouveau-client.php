<?php
/**
 * Création Client - Imprixo Admin
 */

require_once __DIR__ . '/auth.php';

verifierAdminConnecte();
$admin = getAdminInfo();
$db = Database::getInstance();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = cleanInput($_POST['prenom']);
    $nom = cleanInput($_POST['nom']);
    $email = cleanInput($_POST['email']);
    $telephone = cleanInput($_POST['telephone']);
    $entreprise = cleanInput($_POST['entreprise']);
    $siret = cleanInput($_POST['siret']);
    $adresse_facturation = cleanInput($_POST['adresse_facturation']);
    $code_postal_facturation = cleanInput($_POST['code_postal_facturation']);
    $ville_facturation = cleanInput($_POST['ville_facturation']);
    $pays_facturation = cleanInput($_POST['pays_facturation']) ?: 'France';

    // Validation
    if (!$prenom || !$nom || !$email) {
        $error = 'Le prénom, nom et email sont obligatoires';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email invalide';
    } else {
        // Vérifier si email existe déjà
        $existingClient = $db->fetchOne(
            "SELECT id FROM clients WHERE email = ?",
            [$email]
        );

        if ($existingClient) {
            $error = 'Un client avec cet email existe déjà';
        } else {
            // Créer le client
            $db->query(
                "INSERT INTO clients (
                    prenom, nom, email, telephone, entreprise, siret,
                    adresse_facturation, code_postal_facturation, ville_facturation, pays_facturation,
                    created_at, updated_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())",
                [
                    $prenom, $nom, $email, $telephone, $entreprise, $siret,
                    $adresse_facturation, $code_postal_facturation, $ville_facturation, $pays_facturation
                ]
            );

            $clientId = $db->getLastInsertId();

            logAdminAction($admin['id'], 'create_client', "Création client $email");

            header("Location: /admin/client.php?id=$clientId&created=1");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Client - Imprixo Admin</title>
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
            max-width: 900px;
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

        .section {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .section-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 30px;
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

        .form-group input {
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

        .section-subtitle {
            margin: 30px 0 20px;
            font-size: 16px;
            color: #666;
            font-weight: 600;
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
    </style>
</head>
<body>
    <div class="header">
        <a href="/admin/clients.php" class="back-link">← Retour à la liste des clients</a>
        <h1>➕ Nouveau client</h1>
    </div>

    <div class="container">
        <?php if ($error): ?>
            <div class="alert-error">✗ <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="section">
            <h2 class="section-title">Informations du client</h2>

            <form method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Prénom <span class="required">*</span></label>
                        <input type="text" name="prenom" value="<?php echo htmlspecialchars($_POST['prenom'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Nom <span class="required">*</span></label>
                        <input type="text" name="nom" value="<?php echo htmlspecialchars($_POST['nom'] ?? ''); ?>" required>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Email <span class="required">*</span></label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="tel" name="telephone" value="<?php echo htmlspecialchars($_POST['telephone'] ?? ''); ?>" placeholder="06 12 34 56 78">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Entreprise</label>
                        <input type="text" name="entreprise" value="<?php echo htmlspecialchars($_POST['entreprise'] ?? ''); ?>" placeholder="Nom de l'entreprise (optionnel)">
                    </div>

                    <div class="form-group">
                        <label>SIRET</label>
                        <input type="text" name="siret" value="<?php echo htmlspecialchars($_POST['siret'] ?? ''); ?>" placeholder="123 456 789 00012">
                    </div>
                </div>

                <h3 class="section-subtitle">📍 Adresse de facturation</h3>

                <div class="form-group">
                    <label>Adresse</label>
                    <input type="text" name="adresse_facturation" value="<?php echo htmlspecialchars($_POST['adresse_facturation'] ?? ''); ?>" placeholder="12 rue de la République">
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Code postal</label>
                        <input type="text" name="code_postal_facturation" value="<?php echo htmlspecialchars($_POST['code_postal_facturation'] ?? ''); ?>" placeholder="75001">
                    </div>

                    <div class="form-group">
                        <label>Ville</label>
                        <input type="text" name="ville_facturation" value="<?php echo htmlspecialchars($_POST['ville_facturation'] ?? ''); ?>" placeholder="Paris">
                    </div>
                </div>

                <div class="form-group">
                    <label>Pays</label>
                    <input type="text" name="pays_facturation" value="<?php echo htmlspecialchars($_POST['pays_facturation'] ?? 'France'); ?>">
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn btn-primary">✓ Créer le client</button>
                    <a href="/admin/clients.php" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
