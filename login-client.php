<?php
/**
 * Connexion Client - Imprixo
 */

require_once __DIR__ . '/auth-client.php';

// Si déjà connecté, rediriger vers mon compte
if (estClientConnecte()) {
    header('Location: /mon-compte.php');
    exit;
}

$error = '';
$success = '';
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'login'; // login ou register
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '/mon-compte.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($mode === 'login') {
        // Connexion
        $email = cleanInput($_POST['email']);
        $motDePasse = $_POST['password'];

        $result = connecterClient($email, $motDePasse);

        if ($result['success']) {
            header('Location: ' . $redirect);
            exit;
        } else {
            $error = $result['error'];
        }
    } elseif ($mode === 'register') {
        // Inscription
        $email = cleanInput($_POST['email']);
        $motDePasse = $_POST['password'];
        $motDePasseConfirm = $_POST['password_confirm'];
        $prenom = cleanInput($_POST['prenom']);
        $nom = cleanInput($_POST['nom']);
        $telephone = cleanInput($_POST['telephone']);

        // Validation
        if (!$email || !$motDePasse || !$prenom || !$nom) {
            $error = 'Tous les champs obligatoires doivent être remplis';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Email invalide';
        } elseif (strlen($motDePasse) < 8) {
            $error = 'Le mot de passe doit contenir au moins 8 caractères';
        } elseif ($motDePasse !== $motDePasseConfirm) {
            $error = 'Les mots de passe ne correspondent pas';
        } else {
            $result = creerCompteClient($email, $motDePasse, $prenom, $nom, $telephone);

            if ($result['success']) {
                header('Location: /mon-compte.php?welcome=1');
                exit;
            } else {
                $error = $result['error'];
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
    <title><?php echo $mode === 'register' ? 'Créer un compte' : 'Connexion'; ?> - Imprixo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap');
        * { font-family: 'Roboto', sans-serif; }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .input-focus:focus {
            outline: none;
            border-color: #e63946;
            box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <header class="bg-white border-b-2 border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/index.html"><span class="text-3xl font-black text-gray-900">Imprixo</span></a>
            <a href="/index.html" class="text-gray-600 hover:text-gray-900">← Retour à l'accueil</a>
        </div>
    </header>

    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full">
            <!-- Toggle Login/Register -->
            <div class="text-center mb-8">
                <div class="inline-flex rounded-lg overflow-hidden border-2 border-gray-200 bg-white">
                    <a href="?mode=login<?php echo $redirect !== '/mon-compte.php' ? '&redirect=' . urlencode($redirect) : ''; ?>"
                       class="px-6 py-3 font-semibold transition <?php echo $mode === 'login' ? 'bg-red-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'; ?>">
                        Connexion
                    </a>
                    <a href="?mode=register<?php echo $redirect !== '/mon-compte.php' ? '&redirect=' . urlencode($redirect) : ''; ?>"
                       class="px-6 py-3 font-semibold transition <?php echo $mode === 'register' ? 'bg-red-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'; ?>">
                        Créer un compte
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8">
                <h1 class="text-3xl font-black text-gray-900 mb-2 text-center">
                    <?php echo $mode === 'register' ? '✨ Créer un compte' : '🔐 Connexion'; ?>
                </h1>
                <p class="text-gray-600 text-center mb-8">
                    <?php echo $mode === 'register' ? 'Accédez à votre espace client en quelques secondes' : 'Accédez à votre espace client'; ?>
                </p>

                <?php if ($error): ?>
                    <div class="bg-red-50 border-l-4 border-red-600 p-4 mb-6 rounded">
                        <p class="text-red-800 font-medium">✗ <?php echo htmlspecialchars($error); ?></p>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">
                    <?php if ($mode === 'register'): ?>
                        <!-- Inscription -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Prénom *</label>
                                <input type="text" name="prenom" value="<?php echo htmlspecialchars($_POST['prenom'] ?? ''); ?>" required
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg input-focus transition">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nom *</label>
                                <input type="text" name="nom" value="<?php echo htmlspecialchars($_POST['nom'] ?? ''); ?>" required
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg input-focus transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg input-focus transition"
                                   placeholder="votre@email.fr">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Téléphone</label>
                            <input type="tel" name="telephone" value="<?php echo htmlspecialchars($_POST['telephone'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg input-focus transition"
                                   placeholder="06 12 34 56 78">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Mot de passe *</label>
                            <input type="password" name="password" required minlength="8"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg input-focus transition"
                                   placeholder="Minimum 8 caractères">
                            <p class="text-xs text-gray-500 mt-1">Minimum 8 caractères</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Confirmer le mot de passe *</label>
                            <input type="password" name="password_confirm" required minlength="8"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg input-focus transition">
                        </div>

                        <button type="submit" class="w-full bg-red-600 text-white py-4 rounded-lg font-bold text-lg hover:bg-red-700 transition shadow-lg">
                            ✓ Créer mon compte
                        </button>

                        <p class="text-center text-sm text-gray-600">
                            Déjà un compte ?
                            <a href="?mode=login<?php echo $redirect !== '/mon-compte.php' ? '&redirect=' . urlencode($redirect) : ''; ?>" class="text-red-600 font-semibold hover:underline">
                                Se connecter
                            </a>
                        </p>
                    <?php else: ?>
                        <!-- Connexion -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg input-focus transition"
                                   placeholder="votre@email.fr">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Mot de passe</label>
                            <input type="password" name="password" required
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg input-focus transition">
                        </div>

                        <button type="submit" class="w-full bg-red-600 text-white py-4 rounded-lg font-bold text-lg hover:bg-red-700 transition shadow-lg">
                            → Se connecter
                        </button>

                        <p class="text-center text-sm text-gray-600">
                            Pas encore de compte ?
                            <a href="?mode=register<?php echo $redirect !== '/mon-compte.php' ? '&redirect=' . urlencode($redirect) : ''; ?>" class="text-red-600 font-semibold hover:underline">
                                Créer un compte
                            </a>
                        </p>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Avantages espace client -->
            <div class="mt-8 bg-blue-50 rounded-lg p-6 border-2 border-blue-200">
                <h3 class="font-bold text-gray-900 mb-3">✨ Avantages de l'espace client</h3>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li>✓ Suivi de toutes vos commandes en temps réel</li>
                    <li>✓ Téléchargement des spécifications techniques (PDF)</li>
                    <li>✓ Upload de vos fichiers d'impression directement</li>
                    <li>✓ Historique complet et factures</li>
                    <li>✓ Gestion de vos adresses de livraison</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
