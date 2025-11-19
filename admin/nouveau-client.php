<?php
/**
 * Nouveau Client - Imprixo Admin
 */

require_once __DIR__ . '/auth.php';

verifierAdminConnecte();
$admin = getAdminInfo();
$db = Database::getInstance();

$pageTitle = 'Nouveau Client';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = cleanInput($_POST['prenom']);
    $nom = cleanInput($_POST['nom']);
    $email = cleanInput($_POST['email']);
    $telephone = cleanInput($_POST['telephone'] ?? '');
    $entreprise = cleanInput($_POST['entreprise'] ?? '');
    $siret = cleanInput($_POST['siret'] ?? '');
    $adresse = cleanInput($_POST['adresse'] ?? '');
    $code_postal = cleanInput($_POST['code_postal'] ?? '');
    $ville = cleanInput($_POST['ville'] ?? '');
    $pays = cleanInput($_POST['pays'] ?? 'France');

    if (!$prenom || !$nom || !$email) {
        $error = 'Le prénom, nom et email sont obligatoires';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email invalide';
    } else {
        $existing = $db->fetchOne("SELECT id FROM clients WHERE email = ?", [$email]);

        if ($existing) {
            $error = 'Un client avec cet email existe déjà';
        } else {
            try {
                $db->query(
                    "INSERT INTO clients (prenom, nom, email, telephone, entreprise, siret,
                     adresse_facturation, code_postal_facturation, ville_facturation, pays_facturation,
                     created_at, updated_at)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())",
                    [$prenom, $nom, $email, $telephone, $entreprise, $siret,
                     $adresse, $code_postal, $ville, $pays]
                );

                $clientId = $db->lastInsertId();
                logAdminAction($admin['id'], 'create_client', "Création client $email");

                header("Location: /admin/client.php?id=$clientId&created=1");
                exit;
            } catch (Exception $e) {
                $error = 'Erreur : ' . $e->getMessage();
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
        <h1 class="page-title">➕ Nouveau Client</h1>
        <p class="page-subtitle">Créer un nouveau client</p>
    </div>
    <div class="top-bar-actions">
        <a href="/admin/clients.php" class="btn btn-secondary">← Retour</a>
    </div>
</div>

<div class="card">
    <form method="POST">
        <h3 style="font-size: 18px; margin-bottom: 20px; color: var(--primary); font-weight: 700;">👤 Informations personnelles</h3>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
            <div class="form-group">
                <label class="form-label">Prénom <span style="color: var(--danger);">*</span></label>
                <input type="text" name="prenom" class="form-input" required value="<?php echo htmlspecialchars($_POST['prenom'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Nom <span style="color: var(--danger);">*</span></label>
                <input type="text" name="nom" class="form-input" required value="<?php echo htmlspecialchars($_POST['nom'] ?? ''); ?>">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
            <div class="form-group">
                <label class="form-label">Email <span style="color: var(--danger);">*</span></label>
                <input type="email" name="email" class="form-input" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Téléphone</label>
                <input type="tel" name="telephone" class="form-input" value="<?php echo htmlspecialchars($_POST['telephone'] ?? ''); ?>">
            </div>
        </div>

        <h3 style="font-size: 18px; margin: 30px 0 20px; color: var(--secondary); font-weight: 700;">🏢 Informations entreprise</h3>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
            <div class="form-group">
                <label class="form-label">Entreprise</label>
                <input type="text" name="entreprise" class="form-input" value="<?php echo htmlspecialchars($_POST['entreprise'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">SIRET</label>
                <input type="text" name="siret" class="form-input" value="<?php echo htmlspecialchars($_POST['siret'] ?? ''); ?>">
            </div>
        </div>

        <h3 style="font-size: 18px; margin: 30px 0 20px; color: var(--secondary); font-weight: 700;">📍 Adresse de facturation</h3>

        <div class="form-group">
            <label class="form-label">Adresse</label>
            <input type="text" name="adresse" class="form-input" value="<?php echo htmlspecialchars($_POST['adresse'] ?? ''); ?>">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 2fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label">Code postal</label>
                <input type="text" name="code_postal" class="form-input" value="<?php echo htmlspecialchars($_POST['code_postal'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Ville</label>
                <input type="text" name="ville" class="form-input" value="<?php echo htmlspecialchars($_POST['ville'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Pays</label>
                <input type="text" name="pays" class="form-input" value="<?php echo htmlspecialchars($_POST['pays'] ?? 'France'); ?>">
            </div>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 30px; padding-top: 20px; border-top: 2px solid var(--border);">
            <button type="submit" class="btn btn-primary">💾 Créer le client</button>
            <a href="/admin/clients.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
