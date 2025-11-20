<?php
$pageTitle = 'Dashboard Admin - Imprixo';
$pageDescription = '';
include __DIR__ . '/includes/header.php';
?>

<!-- Header Admin -->
    

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Navigation -->
        <div class="bg-white rounded-xl shadow-lg p-4 mb-8 flex flex-wrap gap-3">
            <a href="/admin-dashboard.html" class="bg-red-600 text-white px-6 py-3 rounded-lg font-bold">
                📊 Dashboard
            </a>
            <a href="/admin-produits.html" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-bold transition">
                📦 Produits
            </a>
            <a href="/admin-promotions.html" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-bold transition">
                🔥 Promotions
            </a>
            <a href="/index.html" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-bold transition">
                🌐 Voir le site
            </a>
        </div>

        <h2 class="text-3xl font-black text-gray-900 mb-8">📊 Tableau de bord</h2>

        <!-- Statistiques -->
        <div class="grid md:grid-cols-4 gap-6 mb-8">
            <div class="stat-card bg-white rounded-xl shadow-lg p-6">
                <div class="text-4xl mb-3">📦</div>
                <div class="text-3xl font-black text-gray-900" id="stat-products">54</div>
                <div class="text-sm text-gray-600 font-bold">Produits</div>
            </div>

            <div class="stat-card bg-white rounded-xl shadow-lg p-6">
                <div class="text-4xl mb-3">🛒</div>
                <div class="text-3xl font-black text-red-600" id="stat-orders">0</div>
                <div class="text-sm text-gray-600 font-bold">Commandes</div>
            </div>

            <div class="stat-card bg-white rounded-xl shadow-lg p-6">
                <div class="text-4xl mb-3">👥</div>
                <div class="text-3xl font-black text-blue-600" id="stat-clients">0</div>
                <div class="text-sm text-gray-600 font-bold">Clients</div>
            </div>

            <div class="stat-card bg-white rounded-xl shadow-lg p-6">
                <div class="text-4xl mb-3">🔥</div>
                <div class="text-3xl font-black text-orange-600" id="stat-promos">0</div>
                <div class="text-sm text-gray-600 font-bold">Promotions actives</div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h3 class="text-2xl font-black text-gray-900 mb-6">⚡ Actions rapides</h3>
            <div class="grid md:grid-cols-3 gap-4">
                <a href="/admin-produit-edit.html?action=new" class="btn-primary text-white text-center px-6 py-4 rounded-lg font-black">
                    + Nouveau produit
                </a>
                <a href="/admin-promotions.html?action=new" class="bg-orange-600 hover:bg-orange-700 text-white text-center px-6 py-4 rounded-lg font-black transition">
                    + Nouvelle promotion
                </a>
                <button onclick="syncProducts()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-4 rounded-lg font-black transition">
                    🔄 Synchroniser CSV
                </button>
            </div>
        </div>

        <!-- Dernières commandes -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h3 class="text-2xl font-black text-gray-900 mb-6">📦 Dernières commandes</h3>
            <div id="recent-orders">
                <div class="text-center py-12 text-gray-500">
                    <div class="text-6xl mb-4">📭</div>
                    <p class="text-xl font-semibold">Aucune commande pour le moment</p>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Vérifier connexion admin
    if (localStorage.getItem('admin_logged') !== 'true') {
        window.location.href = '/admin-login.html';
    }

    // Déconnexion
    function logout() {
        localStorage.removeItem('admin_logged');
        window.location.href = '/admin-login.html';
    }

    // Charger statistiques
    function loadStats() {
        // Produits
        const products = JSON.parse(localStorage.getItem('products') || '[]');
        document.getElementById('stat-products').textContent = products.length || '54';

        // Commandes
        const orders = JSON.parse(localStorage.getItem('commandes') || '[]');
        document.getElementById('stat-orders').textContent = orders.length;

        // Clients
        const clients = JSON.parse(localStorage.getItem('clients') || '[]');
        document.getElementById('stat-clients').textContent = clients.length;

        // Promotions actives
        const promos = JSON.parse(localStorage.getItem('promotions') || '[]');
        const activePromos = promos.filter(p => {
            if (!p.end_date) return true;
            return new Date(p.end_date) > new Date();
        });
        document.getElementById('stat-promos').textContent = activePromos.length;

        // Afficher dernières commandes
        if (orders.length > 0) {
            let html = '';
            orders.slice(-5).reverse().forEach(order => {
                const badgeColor = order.statut === 'En attente' ? 'yellow' :
                                  order.statut === 'Validée' ? 'green' : 'blue';
                
                html += `
                    <div class="border-2 border-gray-200 rounded-lg p-4 mb-3 hover:border-red-600 transition">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="font-black">${order.id}</div>
                                <div class="text-sm text-gray-600">${order.client_nom} • ${new Date(order.date).toLocaleDateString('fr-FR')}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-xl font-black text-red-600">${order.total_ttc.toFixed(2)}€</div>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-${badgeColor}-100 text-${badgeColor}-800">${order.statut}</span>
                            </div>
                        </div>
                    </div>
                `;
            });
            document.getElementById('recent-orders').innerHTML = html;
        }
    }

    // Synchroniser produits depuis CSV (simulé)
    function syncProducts() {
        if (confirm('Voulez-vous recharger tous les produits depuis le CSV ? Cela écrasera vos modifications.')) {
            localStorage.removeItem('products');
            alert('Produits synchronisés ! Rechargez la page.');
            location.reload();
        }
    }

    loadStats();
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
