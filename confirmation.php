<?php
$pageTitle = 'Commande confirmée - Imprixo';
$pageDescription = '';
include __DIR__ . '/includes/header.php';
?>

<div id="header-placeholder"></div>
    <script>fetch('/includes/header.html').then(r=>r.text()).then(html=>document.getElementById('header-placeholder').innerHTML=html)</script>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Animation succès -->
        <div class="text-center mb-8">
            <div class="checkmark text-8xl mb-4">✅</div>
            <h1 class="text-4xl font-black text-gray-900 mb-3">Commande confirmée !</h1>
            <p class="text-xl text-gray-600">Merci pour votre confiance</p>
        </div>

        <!-- Détails commande -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
            <div class="border-l-4 border-green-500 pl-4 mb-6">
                <h2 class="text-2xl font-black text-gray-900 mb-2">Commande <span id="order-id" class="text-red-600"></span></h2>
                <p class="text-gray-600">Passée le <span id="order-date"></span></p>
            </div>

            <!-- Prochaines étapes -->
            <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6 mb-6">
                <h3 class="font-black text-blue-900 mb-4">📋 Prochaines étapes</h3>
                <ol class="space-y-3 text-sm">
                    <li class="flex items-start">
                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center font-bold mr-3 mt-0.5">1</span>
                        <div>
                            <div class="font-bold">Email de confirmation</div>
                            <div class="text-gray-700">Vous recevrez un email avec le récapitulatif de votre commande</div>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center font-bold mr-3 mt-0.5">2</span>
                        <div>
                            <div class="font-bold" id="step2-title">Coordonnées bancaires</div>
                            <div class="text-gray-700" id="step2-desc">Nous vous envoyons les coordonnées pour le virement</div>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center font-bold mr-3 mt-0.5">3</span>
                        <div>
                            <div class="font-bold">Envoi de vos fichiers</div>
                            <div class="text-gray-700">Envoyez vos fichiers graphiques à <a href="mailto:impression@imprixo.com" class="text-red-600 font-bold">impression@imprixo.com</a></div>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center font-bold mr-3 mt-0.5">4</span>
                        <div>
                            <div class="font-bold">Production & livraison</div>
                            <div class="text-gray-700">Délai de production 3-5 jours + livraison Europe</div>
                        </div>
                    </li>
                </ol>
            </div>

            <!-- Récapitulatif articles -->
            <div class="mb-6">
                <h3 class="text-xl font-black text-gray-900 mb-4">📦 Vos articles</h3>
                <div id="order-items" class="space-y-3"></div>
            </div>

            <!-- Adresse livraison -->
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-xl font-black text-gray-900 mb-3">🚚 Adresse de livraison</h3>
                    <div id="delivery-address" class="text-gray-700"></div>
                </div>
                <div>
                    <h3 class="text-xl font-black text-gray-900 mb-3">💰 Montant</h3>
                    <div class="space-y-2 text-gray-700">
                        <div class="flex justify-between">
                            <span>Sous-total HT</span>
                            <span class="font-bold" id="total-ht">0.00€</span>
                        </div>
                        <div class="flex justify-between">
                            <span>TVA (20%)</span>
                            <span class="font-bold" id="total-tva">0.00€</span>
                        </div>
                        <div class="flex justify-between text-xl font-black text-red-600 pt-2 border-t">
                            <span>TOTAL TTC</span>
                            <span id="total-ttc">0.00€</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact -->
            <div class="bg-yellow-50 border-2 border-yellow-400 rounded-lg p-6">
                <h3 class="font-black text-yellow-900 mb-3">💬 Une question ?</h3>
                <p class="text-sm text-gray-700 mb-3">Notre équipe est à votre disposition du lundi au vendredi de 9h à 18h.</p>
                <div class="flex flex-wrap gap-4">
                    <a href="mailto:contact@imprixo.com" class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-2 rounded-lg font-bold transition">
                        📧 contact@imprixo.com
                    </a>
                    <a href="tel:+33123456789" class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-2 rounded-lg font-bold transition">
                        📞 +33 1 23 45 67 89
                    </a>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-wrap gap-4 justify-center">
            <a href="/mon-compte.html" class="btn-primary text-white px-8 py-4 rounded-lg font-black">
                Voir mes commandes
            </a>
            <a href="/produits.html" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-8 py-4 rounded-lg font-black transition">
                Continuer mes achats
            </a>
        </div>
    </div>

    <div id="footer-placeholder"></div>
    <script>fetch('/includes/footer.html').then(r=>r.text()).then(html=>document.getElementById('footer-placeholder').innerHTML=html)</script>

    <script>
    // Récupérer ID commande depuis URL
    const urlParams = new URLSearchParams(window.location.search);
    const orderId = urlParams.get('id');

    if (!orderId) {
        window.location.href = '/index.html';
    }

    // Charger la commande
    const commandes = JSON.parse(localStorage.getItem('commandes') || '[]');
    const commande = commandes.find(c => c.id === orderId);

    if (!commande) {
        alert('Commande introuvable');
        window.location.href = '/index.html';
    }

    // Afficher les informations
    document.getElementById('order-id').textContent = commande.id;
    document.getElementById('order-date').textContent = new Date(commande.date).toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });

    // Adapter le texte selon le mode de paiement
    if (commande.paiement === 'devis') {
        document.getElementById('step2-title').textContent = 'Devis personnalisé';
        document.getElementById('step2-desc').textContent = 'Nous vous envoyons un devis détaillé sous 24h';
    }

    // Afficher les articles
    let itemsHTML = '';
    commande.articles.forEach(item => {
        const qte = item.config?.quantite || 1;
        const largeur = item.config?.largeur || '-';
        const hauteur = item.config?.hauteur || '-';
        
        itemsHTML += `
            <div class="bg-gray-50 rounded-lg p-4 flex justify-between items-center">
                <div class="flex-1">
                    <div class="font-bold text-gray-900">${item.nom}</div>
                    <div class="text-sm text-gray-600">
                        ${largeur}×${hauteur} cm • Qté: ${qte}
                    </div>
                </div>
                <div class="font-black text-red-600">${item.prix.toFixed(2)}€</div>
            </div>
        `;
    });
    document.getElementById('order-items').innerHTML = itemsHTML;

    // Afficher l'adresse
    const livraison = commande.livraison;
    document.getElementById('delivery-address').innerHTML = `
        <div class="text-sm space-y-1">
            <div class="font-bold">${livraison.entreprise}</div>
            <div>${livraison.contact}</div>
            <div>${livraison.adresse}</div>
            <div>${livraison.cp} ${livraison.ville}</div>
            <div>${livraison.pays}</div>
            ${livraison.instructions ? `<div class="italic text-gray-600 mt-2">${livraison.instructions}</div>` : ''}
        </div>
    `;

    // Afficher les totaux
    document.getElementById('total-ht').textContent = commande.total_ht.toFixed(2) + '€';
    document.getElementById('total-tva').textContent = commande.tva.toFixed(2) + '€';
    document.getElementById('total-ttc').textContent = commande.total_ttc.toFixed(2) + '€';
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
