<?php
$pageTitle = 'Connexion Admin - Imprixo';
$pageDescription = '';
include __DIR__ . '/includes/header.php';
?>

<div class="max-w-md w-full">
        <!-- Logo -->
        <div class="text-center mb-8">
            <h1 class="text-5xl font-black text-white mb-2">
                Imprixo<span class="text-red-600">Admin</span>
            </h1>
            <p class="text-gray-400">Espace d'administration</p>
        </div>

        <!-- Formulaire -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-2xl font-black text-gray-900 mb-6">🔐 Connexion</h2>

            <div id="error-msg" class="hidden bg-red-100 border-2 border-red-600 text-red-800 px-4 py-3 rounded-lg mb-4 font-bold">
                Identifiants incorrects
            </div>

            <form id="login-form" class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" required 
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Mot de passe</label>
                    <input type="password" id="password" required 
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none">
                </div>

                <button type="submit" class="btn-primary w-full text-white px-6 py-4 rounded-lg font-black text-lg">
                    SE CONNECTER
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-gray-600">
                <p>Compte admin par défaut :</p>
                <p class="font-bold text-gray-900">admin@imprixo.com / admin123</p>
            </div>
        </div>

        <!-- Retour site -->
        <div class="text-center mt-6">
            <a href="/index.html" class="text-white hover:text-red-500 font-bold transition">
                ← Retour au site
            </a>
        </div>
    </div>

    <script>
    // Créer admin par défaut si n'existe pas
    if (!localStorage.getItem('admin_user')) {
        localStorage.setItem('admin_user', JSON.stringify({
            email: 'admin@imprixo.com',
            password: 'admin123',
            name: 'Administrateur'
        }));
    }

    document.getElementById('login-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const admin = JSON.parse(localStorage.getItem('admin_user'));

        if (email === admin.email && password === admin.password) {
            localStorage.setItem('admin_logged', 'true');
            window.location.href = '/admin-dashboard.html';
        } else {
            document.getElementById('error-msg').classList.remove('hidden');
        }
    });
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
