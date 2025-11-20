<?php
$pageTitle = 'Mon Compte - Imprixo';
$pageDescription = '';
include __DIR__ . '/includes/header.php';
?>

<!-- Header -->
    <div id="header-placeholder"></div>
    <script>fetch('/includes/header.html').then(r=>r.text()).then(html=>document.getElementById('header-placeholder').innerHTML=html)</script>

    <div class="max-w-7xl mx-auto px-4 py-12">
        <!-- Alert si non connecté -->
        <div id="not-logged-alert" class="bg-yellow-50 border-l-4 border-yellow-600 p-6 rounded-lg mb-8" style="display: none;">
            <h2 class="text-xl font-bold text-yellow-900 mb-2">⚠️ Connexion requise</h2>
            <p class="text-yellow-800 mb-4">Vous devez être connecté pour accéder à cette page.</p>
            <a href="/login-client.html" class="btn-primary text-white px-6 py-3 rounded-lg font-bold inline-block">
                🔐 Se connecter
            </a>
        </div>

        <!-- Contenu compte -->
        <div id="account-content" style="display: none;">
            <!-- Welcome message -->
            <div id="welcome-msg" class="bg-green-50 border-l-4 border-green-600 p-6 rounded-lg mb-8" style="display: none;">
                <h2 class="text-xl font-bold text-green-900 mb-2">🎉 Bienvenue sur Imprixo !</h2>
                <p class="text-green-800">Votre compte a été créé avec succès. Vous pouvez maintenant passer commande et suivre vos impressions.</p>
            </div>

            <!-- Titre et avatar -->
            <div class="flex items-center gap-6 mb-8">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center text-white text-3xl font-black" id="avatar">
                    XX
                </div>
                <div>
                    <h1 class="text-4xl font-black text-gray-900 mb-2" id="client-name">Mon Compte</h1>
                    <p class="text-gray-600" id="client-email">email@exemple.com</p>
                </div>
                <div class="ml-auto">
                    <button onclick="logout()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-bold transition">
                        🚪 Déconnexion
                    </button>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="grid md:grid-cols-3 gap-6 mb-12">
                <div class="stat-card">
                    <div class="text-5xl font-black text-red-600 mb-2" id="stat-commandes">0</div>
                    <div class="text-gray-600 font-medium">Commandes passées</div>
                </div>
                <div class="stat-card">
                    <div class="text-5xl font-black text-green-600 mb-2" id="stat-total">0€</div>
                    <div class="text-gray-600 font-medium">Total dépensé</div>
                </div>
                <div class="stat-card">
                    <div class="text-5xl font-black text-blue-600 mb-2" id="stat-panier">0</div>
                    <div class="text-gray-600 font-medium">Articles dans le panier</div>
                </div>
            </div>

            <!-- Mes informations -->
            <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-black text-gray-900 mb-6">👤 Mes informations</h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Prénom</label>
                        <input type="text" id="input-prenom" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nom</label>
                        <input type="text" id="input-nom" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                        <input type="email" id="input-email" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Téléphone</label>
                        <input type="tel" id="input-tel" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg" readonly>
                    </div>
                </div>
            </div>

            <!-- Mes commandes -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-black text-gray-900 mb-6">📦 Mes commandes</h2>
                <div id="commandes-list">
                    <div class="text-center py-12 text-gray-500">
                        <div class="text-6xl mb-4">🛒</div>
                        <p class="text-xl font-semibold">Aucune commande pour le moment</p>
                        <p class="mt-2">Découvrez nos produits et passez votre première commande !</p>
                        <a href="/catalogue.html" class="mt-6 btn-primary text-white px-8 py-3 rounded-lg font-bold inline-block">
                            📦 Voir le catalogue
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div id="footer-placeholder"></div>
    <script>fetch('/includes/footer.html').then(r=>r.text()).then(html=>document.getElementById('footer-placeholder').innerHTML=html)</script>

    <script>
        // Vérifier si client connecté
        const client = JSON.parse(localStorage.getItem('client') || 'null');
        const cart = JSON.parse(localStorage.getItem('visuprint_cart') || '[]');

        if (!client) {
            document.getElementById('not-logged-alert').style.display = 'block';
        } else {
            document.getElementById('account-content').style.display = 'block';

            // Afficher welcome si paramètre
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('welcome')) {
                document.getElementById('welcome-msg').style.display = 'block';
            }

            // Remplir les infos
            document.getElementById('avatar').textContent =
                (client.prenom[0] + client.nom[0]).toUpperCase();
            document.getElementById('client-name').textContent =
                client.prenom + ' ' + client.nom;
            document.getElementById('client-email').textContent = client.email;

            document.getElementById('input-prenom').value = client.prenom;
            document.getElementById('input-nom').value = client.nom;
            document.getElementById('input-email').value = client.email;
            document.getElementById('input-tel').value = client.telephone || 'Non renseigné';

            // Statistiques
            const commandes = JSON.parse(localStorage.getItem('commandes') || '[]');
            const commandesClient = commandes.filter(c => c.client_id === client.id);

            document.getElementById('stat-commandes').textContent = commandesClient.length;

            const totalDepense = commandesClient.reduce((sum, c) => sum + (c.total_ttc || 0), 0);
            document.getElementById('stat-total').textContent = totalDepense.toFixed(2) + '€';

            document.getElementById('stat-panier').textContent = cart.length;

            // Afficher les commandes
            if (commandesClient.length > 0) {
                let html = '';
                commandesClient.reverse().forEach(cmd => {
                    const badgeColor = cmd.statut === 'En attente' ? 'yellow' :
                                      cmd.statut === 'Validée' ? 'green' :
                                      cmd.statut === 'En production' ? 'blue' : 'gray';

                    html += `
                        <div class="border-2 border-gray-200 rounded-lg p-6 mb-4 hover:border-red-600 transition">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <div class="text-lg font-black">${cmd.id}</div>
                                    <div class="text-sm text-gray-600">${new Date(cmd.date).toLocaleDateString('fr-FR', {day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'})}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-black text-red-600">${cmd.total_ttc.toFixed(2)}€</div>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-${badgeColor}-100 text-${badgeColor}-800 mt-1">${cmd.statut}</span>
                                </div>
                            </div>
                            <div class="text-sm text-gray-700 mb-2">
                                <strong>${cmd.articles.length} article(s)</strong> • Livraison: ${cmd.livraison.ville}, ${cmd.livraison.pays}
                            </div>
                            <div class="flex gap-2 mt-4">
                                <a href="/confirmation.html?id=${cmd.id}" class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-800 px-4 py-2 rounded-lg font-bold text-center transition text-sm">
                                    Voir détails
                                </a>
                            </div>
                        </div>
                    `;
                });
                document.getElementById('commandes-list').innerHTML = html;
            }
        }

        function logout() {
            localStorage.removeItem('client');
            window.location.href = '/index.html';
        }
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
