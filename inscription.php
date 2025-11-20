<?php
$pageTitle = 'Créer mon compte Imprixo - Avantages exclusifs | Imprixo';
$pageDescription = 'Créez votre compte Imprixo gratuitement et profitez d\'avantages exclusifs : devis sauvegardés, historique commandes, prix préférentiels, livraison rapide';
include __DIR__ . '/includes/header.php';
?>

<div class="container">
        <div class="card">
            <div class="left">
                <h1>🎁 Rejoignez Imprixo</h1>
                <p>Créez votre compte gratuitement et profitez d'avantages exclusifs</p>
                <ul>
                    <li>✓ Devis sauvegardés automatiquement</li>
                    <li>✓ Historique de vos commandes</li>
                    <li>✓ Gestion de vos fichiers uploadés</li>
                    <li>✓ Prix préférentiels -5% dès la 1ère commande</li>
                    <li>✓ Livraison express offerte</li>
                    <li>✓ Support prioritaire 7j/7</li>
                    <li>✓ Programme fidélité avec cashback</li>
                </ul>
            </div>
            <div class="right">
                <h2>Créer mon compte</h2>
                <form action="/api/register.php" method="POST">
                    <div class="form-group">
                        <label>Prénom</label>
                        <input type="text" name="prenom" required>
                    </div>
                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text" name="nom" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="tel" name="telephone" placeholder="06 12 34 56 78">
                    </div>
                    <div class="form-group">
                        <label>Mot de passe</label>
                        <input type="password" name="password" minlength="8" required>
                        <small style="color:#6c757d;font-size:0.85rem">Min 8 caractères</small>
                    </div>
                    <div class="form-group">
                        <label><input type="checkbox" required> J'accepte les <a href="/cgv.html" target="_blank">CGV</a></label>
                    </div>
                    <button type="submit" class="btn">Créer mon compte →</button>
                </form>
                <div class="login-link">
                    Déjà inscrit ? <a href="/connexion.php">Se connecter</a>
                </div>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/includes/footer.php'; ?>
