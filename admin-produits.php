<?php
$pageTitle = 'Gestion Produits - Imprixo Admin';
$pageDescription = '';
include __DIR__ . '/includes/header.php';
?>

<!-- Header Admin -->
    

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Navigation -->
        <div class="bg-white rounded-xl shadow-lg p-4 mb-8 flex flex-wrap gap-3">
            <a href="/admin-dashboard.html" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-bold">
                📊 Dashboard
            </a>
            <a href="/admin-produits.html" class="bg-red-600 text-white px-6 py-3 rounded-lg font-bold">
                📦 Produits
            </a>
            <a href="/admin-promotions.html" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-bold">
                🔥 Promotions
            </a>
        </div>

        <!-- En-tête avec actions -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-black text-gray-900">📦 Gestion des produits</h2>
            <a href="/admin-produit-edit.html?action=new" class="btn-primary text-white px-6 py-3 rounded-lg font-black">
                + Nouveau produit
            </a>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="grid md:grid-cols-4 gap-4">
                <input type="text" id="search" placeholder="🔍 Rechercher un produit..." 
                       class="px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none">
                
                <select id="filter-category" class="px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none">
                    <option value="">Toutes les catégories</option>
                    <option value="PVC">PVC</option>
                    <option value="Aluminium">Aluminium</option>
                    <option value="Bache">Bâche</option>
                    <option value="Textile">Textile</option>
                </select>

                <select id="filter-status" class="px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none">
                    <option value="">Tous les statuts</option>
                    <option value="active">Actif</option>
                    <option value="promo">En promo</option>
                    <option value="inactive">Inactif</option>
                </select>

                <button onclick="resetFilters()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-3 rounded-lg font-bold">
                    Réinitialiser
                </button>
            </div>
        </div>

        <!-- Liste des produits -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="mb-4 text-sm text-gray-600">
                <span id="product-count" class="font-bold">0</span> produit(s)
            </div>
            
            <div id="products-list" class="space-y-3">
                <!-- Chargement -->
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">⏳</div>
                    <p class="text-xl font-semibold text-gray-600">Chargement des produits...</p>
                </div>
            </div>
        </div>
    </div>

    <script src="/init-products.js"></script>
    <script>
    // Vérifier connexion admin
    if (localStorage.getItem('admin_logged') !== 'true') {
        window.location.href = '/admin-login.html';
    }

    function logout() {
        localStorage.removeItem('admin_logged');
        window.location.href = '/admin-login.html';
    }

    // Charger et afficher les produits
    function loadProducts() {
        initProductsIfNeeded(); // Initialiser si besoin
        
        let products = JSON.parse(localStorage.getItem('products') || '[]');
        
        // Appliquer filtres
        const search = document.getElementById('search').value.toLowerCase();
        const category = document.getElementById('filter-category').value;
        const status = document.getElementById('filter-status').value;

        products = products.filter(p => {
            const matchSearch = !search || p.nom.toLowerCase().includes(search) || p.id.toLowerCase().includes(search);
            const matchCategory = !category || p.categorie.includes(category);
            const matchStatus = !status || 
                (status === 'active' && !p.inactive && !p.promo) ||
                (status === 'promo' && p.promo) ||
                (status === 'inactive' && p.inactive);
            
            return matchSearch && matchCategory && matchStatus;
        });

        document.getElementById('product-count').textContent = products.length;

        if (products.length === 0) {
            document.getElementById('products-list').innerHTML = `
                <div class="text-center py-12 text-gray-500">
                    <div class="text-6xl mb-4">📭</div>
                    <p class="text-xl font-semibold">Aucun produit trouvé</p>
                </div>
            `;
            return;
        }

        let html = '';
        products.forEach(product => {
            const promoPrice = product.promo ? product.promo.prix_promo : null;
            const hasPromo = product.promo && (!product.promo.end_date || new Date(product.promo.end_date) > new Date());
            
            html += `
                <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-red-600 transition ${product.inactive ? 'opacity-50' : ''}">
                    <div class="flex items-center gap-4">
                        <img src="${product.image_url || 'https://via.placeholder.com/100x100/e63946/ffffff?text=' + encodeURIComponent(product.nom)}" 
                             alt="${product.nom}" class="w-20 h-20 object-cover rounded-lg">
                        
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="text-lg font-black">${product.nom}</h3>
                                ${product.inactive ? '<span class="bg-gray-500 text-white text-xs px-2 py-1 rounded font-bold">INACTIF</span>' : ''}
                                ${hasPromo ? '<span class="bg-orange-500 text-white text-xs px-2 py-1 rounded font-bold">PROMO</span>' : ''}
                            </div>
                            <div class="text-sm text-gray-600 mb-2">${product.id} • ${product.categorie}</div>
                            <div class="flex items-center gap-3">
                                <div class="font-bold ${hasPromo ? 'line-through text-gray-500' : 'text-red-600'}">${product.prix_0_10}€/m²</div>
                                ${hasPromo ? `<div class="font-black text-red-600">${promoPrice}€/m²</div>` : ''}
                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <a href="/admin-produit-edit.html?id=${product.id}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-bold text-center transition text-sm">
                                ✏️ Modifier
                            </a>
                            <button onclick="toggleProduct('${product.id}')" 
                                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-bold transition text-sm">
                                ${product.inactive ? '✓ Activer' : '✕ Désactiver'}
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        document.getElementById('products-list').innerHTML = html;
    }

    // Activer/Désactiver un produit
    function toggleProduct(id) {
        let products = JSON.parse(localStorage.getItem('products') || '[]');
        const product = products.find(p => p.id === id);
        
        if (product) {
            product.inactive = !product.inactive;
            localStorage.setItem('products', JSON.stringify(products));
            loadProducts();
        }
    }

    // Réinitialiser filtres
    function resetFilters() {
        document.getElementById('search').value = '';
        document.getElementById('filter-category').value = '';
        document.getElementById('filter-status').value = '';
        loadProducts();
    }

    // Écouteurs
    document.getElementById('search').addEventListener('input', loadProducts);
    document.getElementById('filter-category').addEventListener('change', loadProducts);
    document.getElementById('filter-status').addEventListener('change', loadProducts);

    // Charger au démarrage
    loadProducts();
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
