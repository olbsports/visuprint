<?php
$pageTitle = 'Gestion Promotions - Imprixo Admin';
$pageDescription = '';
include __DIR__ . '/includes/header.php';
?>

<!-- Header -->
    

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Navigation -->
        <div class="bg-white rounded-xl shadow-lg p-4 mb-8 flex flex-wrap gap-3">
            <a href="/admin-dashboard.html" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-bold">📊 Dashboard</a>
            <a href="/admin-produits.html" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-bold">📦 Produits</a>
            <a href="/admin-promotions.html" class="bg-red-600 text-white px-6 py-3 rounded-lg font-bold">🔥 Promotions</a>
        </div>

        <h2 class="text-3xl font-black text-gray-900 mb-8">🔥 Gestion des promotions</h2>

        <!-- Info -->
        <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-6 mb-6">
            <h3 class="font-black text-blue-900 mb-3">💡 Comment créer une promotion</h3>
            <p class="text-sm text-gray-700">Pour créer une promotion sur un produit, allez dans <strong>Produits</strong> → <strong>Modifier le produit</strong> → Onglet <strong>Promotion</strong></p>
        </div>

        <!-- Produits en promotion -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h3 class="text-2xl font-black mb-6">📦 Produits en promotion</h3>
            <div id="promo-products">
                <div class="text-center py-12 text-gray-500">
                    <div class="text-6xl mb-4">🔥</div>
                    <p class="text-xl font-semibold">Aucune promotion active</p>
                </div>
            </div>
        </div>
    </div>

    <script src="/init-products.js"></script>
    <script>
    if (localStorage.getItem('admin_logged') !== 'true') {
        window.location.href = '/admin-login.html';
    }

    function logout() {
        localStorage.removeItem('admin_logged');
        window.location.href = '/admin-login.html';
    }

    function loadPromoProducts() {
        initProductsIfNeeded();
        
        const products = JSON.parse(localStorage.getItem('products') || '[]');
        const promoProducts = products.filter(p => p.promo && !p.inactive);

        if (promoProducts.length === 0) return;

        let html = '';
        promoProducts.forEach(product => {
            const promo = product.promo;
            const isActive = !promo.end_date || new Date(promo.end_date) > new Date();
            const reduction = ((product.prix_0_10 - promo.prix_promo) / product.prix_0_10 * 100).toFixed(0);

            html += `
                <div class="border-2 ${isActive ? 'border-orange-500' : 'border-gray-300'} rounded-lg p-6 mb-4">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <h3 class="text-xl font-black">${product.nom}</h3>
                                <span class="bg-orange-500 text-white text-xs px-3 py-1 rounded-full font-bold">${promo.badge || 'PROMO'}</span>
                                ${!isActive ? '<span class="bg-gray-500 text-white text-xs px-3 py-1 rounded-full font-bold">EXPIRÉE</span>' : ''}
                            </div>
                            <div class="text-sm text-gray-600 mb-3">${promo.titre || 'Promotion'}</div>
                            
                            <div class="flex items-center gap-4">
                                <div class="text-gray-500 line-through">${product.prix_0_10}€/m²</div>
                                <div class="text-3xl font-black text-red-600">${promo.prix_promo}€/m²</div>
                                <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full font-bold">-${reduction}%</div>
                            </div>

                            ${promo.start_date || promo.end_date ? `
                                <div class="mt-3 text-sm text-gray-600">
                                    ${promo.start_date ? `Début: ${new Date(promo.start_date).toLocaleDateString('fr-FR')}` : ''}<br>
                                    ${promo.end_date ? `Fin: ${new Date(promo.end_date).toLocaleDateString('fr-FR')}` : 'Sans limite de temps'}
                                    ${promo.countdown ? ' • <strong>Compte à rebours activé</strong>' : ''}
                                </div>
                            ` : ''}
                        </div>

                        <div class="flex gap-2">
                            <a href="/admin-produit-edit.html?id=${product.id}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-bold transition text-sm">
                                ✏️ Modifier
                            </a>
                            <button onclick="removePromo('${product.id}')" 
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-bold transition text-sm">
                                ✕ Retirer
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        document.getElementById('promo-products').innerHTML = html;
    }

    function removePromo(productId) {
        if (!confirm('Retirer la promotion de ce produit ?')) return;

        let products = JSON.parse(localStorage.getItem('products') || '[]');
        const product = products.find(p => p.id === productId);
        
        if (product) {
            delete product.promo;
            localStorage.setItem('products', JSON.stringify(products));
            loadPromoProducts();
        }
    }

    loadPromoProducts();
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
