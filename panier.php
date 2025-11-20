<?php
$pageTitle = 'Mon Panier - Imprixo';
$pageDescription = '';
include __DIR__ . '/includes/header.php';
?>

    <script>fetch('/includes/header.html').then(r=>r.text()).then(html=>document.getElementById('header-placeholder').innerHTML=html)</script>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        

        <h1 class="text-4xl font-black text-gray-900 mb-8">🛒 Mon Panier</h1>

        <!-- Panier vide -->
        <div id="empty-cart" class="hidden text-center py-16 bg-white rounded-xl shadow-lg">
            <div class="text-6xl mb-4">🛒</div>
            <h3 class="text-2xl font-black text-gray-900 mb-2">Votre panier est vide</h3>
            <p class="text-gray-600 mb-6">Ajoutez des produits pour commencer votre commande</p>
            <a href="/produits.html" class="inline-block btn-primary text-white px-8 py-3 rounded-lg font-bold">
                Voir nos produits
            </a>
        </div>

        <!-- Panier avec articles -->
        <div id="cart-content" class="grid lg:grid-cols-3 gap-8">
            <!-- Liste articles -->
            <div class="lg:col-span-2 space-y-4" id="cart-items"></div>

            <!-- Résumé commande -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-8">
                    <h2 class="text-2xl font-black text-gray-900 mb-6">Résumé</h2>
                    
                    <div class="space-y-3 mb-6 pb-6 border-b">
                        <div class="flex justify-between text-gray-700">
                            <span>Sous-total</span>
                            <span class="font-bold" id="subtotal">0.00€</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>TVA (20%)</span>
                            <span class="font-bold" id="tva">0.00€</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>Livraison</span>
                            <span class="font-bold text-green-600">GRATUITE</span>
                        </div>
                    </div>

                    <div class="flex justify-between text-xl font-black text-gray-900 mb-6">
                        <span>TOTAL TTC</span>
                        <span class="text-red-600" id="total">0.00€</span>
                    </div>

                    <button onclick="proceedToCheckout()" class="btn-primary w-full text-white px-6 py-4 rounded-lg font-black text-lg mb-3">
                        COMMANDER
                    </button>

                    <button onclick="clearCart()" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-bold transition">
                        Vider le panier
                    </button>

                    <div class="mt-6 text-center text-sm text-gray-600">
                        <p>✓ Paiement sécurisé</p>
                        <p>✓ Livraison rapide Europe</p>
                        <p>✓ Support client dédié</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>fetch('/includes/footer.html').then(r=>r.text()).then(html=>document.getElementById('footer-placeholder').innerHTML=html)</script>

    <script>
    // Charger le panier
    function loadCart() {
        try {
            return JSON.parse(localStorage.getItem('visuprint_cart') || '[]');
        } catch {
            return [];
        }
    }

    // Sauvegarder le panier
    function saveCart(cart) {
        localStorage.setItem('visuprint_cart', JSON.stringify(cart));
    }

    // Afficher le panier
    function displayCart() {
        const cart = loadCart();
        const emptyCart = document.getElementById('empty-cart');
        const cartContent = document.getElementById('cart-content');
        const cartItems = document.getElementById('cart-items');

        if (cart.length === 0) {
            emptyCart.classList.remove('hidden');
            cartContent.classList.add('hidden');
            return;
        }

        emptyCart.classList.add('hidden');
        cartContent.classList.remove('hidden');

        let html = '';
        let subtotal = 0;

        cart.forEach((item, index) => {
            const itemTotal = item.prix || 0;
            subtotal += itemTotal;

            // Extraire les infos de config
            const largeur = item.config?.largeur || '-';
            const hauteur = item.config?.hauteur || '-';
            const surface = item.config?.surface ? item.config.surface.toFixed(2) : '-';
            const quantite = item.config?.quantite || 1;
            const finition = item.config?.finition || 'Standard';

            html += `
                <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-gray-200">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-black text-gray-900 mb-2">${item.nom}</h3>
                            <div class="space-y-1 text-sm text-gray-600">
                                <p>📐 Dimensions: <strong>${largeur}×${hauteur} cm</strong> (${surface} m²)</p>
                                <p>📦 Quantité: <strong>${quantite}</strong></p>
                                <p>🎨 Finition: <strong>${finition}</strong></p>
                            </div>
                        </div>
                        <div class="text-right ml-4">
                            <div class="text-2xl font-black text-red-600 mb-1">${itemTotal.toFixed(2)}€</div>
                            <div class="text-xs text-gray-500">TTC</div>
                        </div>
                    </div>
                    <div class="flex gap-3 pt-4 border-t">
                        <button onclick="removeItem(${index})" class="flex-1 bg-red-100 hover:bg-red-200 text-red-800 px-4 py-2 rounded-lg font-bold transition">
                            🗑️ Supprimer
                        </button>
                        <a href="/produit/${item.id}.html" class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-800 px-4 py-2 rounded-lg font-bold text-center transition">
                            ✏️ Modifier
                        </a>
                    </div>
                </div>
            `;
        });

        cartItems.innerHTML = html;

        // Calculer totaux
        const tva = subtotal * 0.20;
        const total = subtotal;

        document.getElementById('subtotal').textContent = (subtotal / 1.20).toFixed(2) + '€';
        document.getElementById('tva').textContent = tva.toFixed(2) + '€';
        document.getElementById('total').textContent = total.toFixed(2) + '€';
    }

    // Supprimer un article
    function removeItem(index) {
        const cart = loadCart();
        cart.splice(index, 1);
        saveCart(cart);
        displayCart();
    }

    // Vider le panier
    function clearCart() {
        if (confirm('Êtes-vous sûr de vouloir vider votre panier ?')) {
            saveCart([]);
            displayCart();
        }
    }

    // Passer à la commande
    function proceedToCheckout() {
        const cart = loadCart();
        if (cart.length === 0) {
            alert('Votre panier est vide !');
            return;
        }

        // Vérifier si client connecté
        const client = JSON.parse(localStorage.getItem('client') || 'null');
        if (!client) {
            if (confirm('Vous devez être connecté pour commander. Voulez-vous vous connecter maintenant ?')) {
                window.location.href = '/login-client.html?redirect=panier.html';
            }
            return;
        }

        // Rediriger vers la page de commande
        window.location.href = '/commande.html';
    }

    // Charger au démarrage
    displayCart();
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
