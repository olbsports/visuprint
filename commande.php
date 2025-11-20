<?php
$pageTitle = 'Finaliser ma commande - Imprixo';
$pageDescription = '';
include __DIR__ . '/includes/header.php';
?>

    <script>fetch('/includes/header.html').then(r=>r.text()).then(html=>document.getElementById('header-placeholder').innerHTML=html)</script>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        

        <!-- Étapes -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex justify-between items-center">
                <div class="step active flex-1 text-center">
                    <div class="text-3xl mb-2">🛒</div>
                    <div class="font-bold">Panier</div>
                </div>
                <div class="h-1 bg-gray-300 flex-1"></div>
                <div class="step active flex-1 text-center">
                    <div class="text-3xl mb-2">📋</div>
                    <div class="font-bold">Validation</div>
                </div>
                <div class="h-1 bg-gray-300 flex-1"></div>
                <div class="step flex-1 text-center">
                    <div class="text-3xl mb-2">✓</div>
                    <div class="font-bold">Confirmation</div>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Formulaire -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informations client -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-black text-gray-900 mb-6">📋 Vos informations</h2>
                    <div id="client-info" class="space-y-4"></div>
                </div>

                <!-- Adresse livraison -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-black text-gray-900 mb-6">🚚 Adresse de livraison</h2>
                    <form id="delivery-form" class="space-y-4">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Entreprise *</label>
                                <input type="text" id="entreprise" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Contact *</label>
                                <input type="text" id="contact" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Adresse *</label>
                            <input type="text" id="adresse" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none">
                        </div>
                        <div class="grid md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Code postal *</label>
                                <input type="text" id="cp" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Ville *</label>
                                <input type="text" id="ville" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Pays *</label>
                                <select id="pays" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none">
                                    <option value="FR">France</option>
                                    <option value="BE">Belgique</option>
                                    <option value="DE">Allemagne</option>
                                    <option value="ES">Espagne</option>
                                    <option value="IT">Italie</option>
                                    <option value="NL">Pays-Bas</option>
                                    <option value="LU">Luxembourg</option>
                                    <option value="CH">Suisse</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Instructions de livraison (optionnel)</label>
                            <textarea id="instructions" rows="3" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none"></textarea>
                        </div>
                    </form>
                </div>

                <!-- Mode de paiement -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-black text-gray-900 mb-6">💳 Paiement</h2>
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-red-600">
                            <input type="radio" name="payment" value="virement" checked class="mr-3">
                            <div class="flex-1">
                                <div class="font-bold">Virement bancaire</div>
                                <div class="text-sm text-gray-600">Coordonnées envoyées par email</div>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-red-600">
                            <input type="radio" name="payment" value="devis" class="mr-3">
                            <div class="flex-1">
                                <div class="font-bold">Demande de devis</div>
                                <div class="text-sm text-gray-600">Nous vous envoyons un devis détaillé</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- CGV -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <label class="flex items-start cursor-pointer">
                        <input type="checkbox" id="cgv" class="mt-1 mr-3">
                        <span class="text-sm text-gray-700">
                            J'accepte les <a href="/cgv.html" class="text-red-600 font-bold hover:underline">Conditions Générales de Vente</a> *
                        </span>
                    </label>
                </div>
            </div>

            <!-- Récapitulatif -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-8">
                    <h2 class="text-2xl font-black text-gray-900 mb-6">📦 Récapitulatif</h2>
                    
                    <div id="order-items" class="space-y-3 mb-6 pb-6 border-b"></div>

                    <div class="space-y-3 mb-6 pb-6 border-b">
                        <div class="flex justify-between text-gray-700">
                            <span>Sous-total HT</span>
                            <span class="font-bold" id="subtotal-ht">0.00€</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>TVA (20%)</span>
                            <span class="font-bold" id="tva-amount">0.00€</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>Livraison</span>
                            <span class="font-bold text-green-600">GRATUITE</span>
                        </div>
                    </div>

                    <div class="flex justify-between text-xl font-black text-gray-900 mb-6">
                        <span>TOTAL TTC</span>
                        <span class="text-red-600" id="total-ttc">0.00€</span>
                    </div>

                    <button onclick="validateOrder()" class="btn-primary w-full text-white px-6 py-4 rounded-lg font-black text-lg">
                        VALIDER LA COMMANDE
                    </button>

                    <div class="mt-6 text-xs text-gray-600 space-y-1">
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
    // Vérifier connexion
    const client = JSON.parse(localStorage.getItem('client') || 'null');
    if (!client) {
        alert('Vous devez être connecté pour passer commande.');
        window.location.href = '/login-client.html?redirect=commande.html';
    }

    // Charger panier
    const cart = JSON.parse(localStorage.getItem('visuprint_cart') || '[]');
    if (cart.length === 0) {
        alert('Votre panier est vide !');
        window.location.href = '/panier.html';
    }

    // Afficher infos client
    const clientInfo = document.getElementById('client-info');
    clientInfo.innerHTML = `
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <div class="text-sm text-gray-600">Nom</div>
                <div class="font-bold">${client.prenom} ${client.nom}</div>
            </div>
            <div>
                <div class="text-sm text-gray-600">Email</div>
                <div class="font-bold">${client.email}</div>
            </div>
            <div>
                <div class="text-sm text-gray-600">Téléphone</div>
                <div class="font-bold">${client.telephone || '-'}</div>
            </div>
        </div>
    `;

    // Pré-remplir le formulaire si données existantes
    if (client.entreprise) document.getElementById('entreprise').value = client.entreprise;
    if (client.nom) document.getElementById('contact').value = client.prenom + ' ' + client.nom;

    // Afficher récapitulatif
    let subtotalTTC = 0;
    let itemsHTML = '';

    cart.forEach(item => {
        const total = item.prix || 0;
        subtotalTTC += total;
        const qte = item.config?.quantite || 1;
        
        itemsHTML += `
            <div class="flex justify-between text-sm">
                <div class="flex-1 pr-2">
                    <div class="font-bold text-gray-900">${item.nom}</div>
                    <div class="text-xs text-gray-600">Qté: ${qte}</div>
                </div>
                <div class="font-bold text-gray-900">${total.toFixed(2)}€</div>
            </div>
        `;
    });

    document.getElementById('order-items').innerHTML = itemsHTML;

    const subtotalHT = subtotalTTC / 1.20;
    const tva = subtotalTTC - subtotalHT;

    document.getElementById('subtotal-ht').textContent = subtotalHT.toFixed(2) + '€';
    document.getElementById('tva-amount').textContent = tva.toFixed(2) + '€';
    document.getElementById('total-ttc').textContent = subtotalTTC.toFixed(2) + '€';

    // Valider la commande
    function validateOrder() {
        // Vérifier CGV
        if (!document.getElementById('cgv').checked) {
            alert('Vous devez accepter les CGV pour continuer.');
            return;
        }

        // Vérifier formulaire livraison
        const form = document.getElementById('delivery-form');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Créer la commande
        const commandes = JSON.parse(localStorage.getItem('commandes') || '[]');
        
        const nouvelleCommande = {
            id: 'CMD-' + Date.now(),
            client_id: client.id,
            client_nom: client.prenom + ' ' + client.nom,
            client_email: client.email,
            date: new Date().toISOString(),
            statut: 'En attente',
            articles: cart,
            livraison: {
                entreprise: document.getElementById('entreprise').value,
                contact: document.getElementById('contact').value,
                adresse: document.getElementById('adresse').value,
                cp: document.getElementById('cp').value,
                ville: document.getElementById('ville').value,
                pays: document.getElementById('pays').value,
                instructions: document.getElementById('instructions').value
            },
            paiement: document.querySelector('input[name="payment"]:checked').value,
            total_ht: subtotalHT,
            tva: tva,
            total_ttc: subtotalTTC
        };

        commandes.push(nouvelleCommande);
        localStorage.setItem('commandes', JSON.stringify(commandes));

        // Vider le panier
        localStorage.setItem('visuprint_cart', '[]');

        // Rediriger vers confirmation
        window.location.href = '/confirmation.html?id=' + nouvelleCommande.id;
    }
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
