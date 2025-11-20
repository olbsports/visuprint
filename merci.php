<?php
$pageTitle = 'Commande confirmée - Imprixo';
$pageDescription = '';
include __DIR__ . '/includes/header.php';
?>

<section class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg p-12 text-center border border-gray-200">
                <div class="text-8xl mb-6">✅</div>
                <h1 class="text-4xl font-black text-gray-900 mb-4">Commande confirmée !</h1>
                <p class="text-xl text-gray-600 mb-8">Merci pour votre confiance. Votre commande a bien été enregistrée.</p>

                <div class="bg-gray-50 rounded-lg p-6 mb-8 text-left">
                    <h2 class="font-bold text-gray-900 mb-4">Prochaines étapes</h2>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <span class="text-2xl">📧</span>
                            <div>
                                <div class="font-semibold">Confirmation par email</div>
                                <div class="text-sm text-gray-600">Vous allez recevoir un email de confirmation avec tous les détails de votre commande</div>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-2xl">📁</span>
                            <div>
                                <div class="font-semibold">Envoi de vos fichiers</div>
                                <div class="text-sm text-gray-600">Vous pouvez envoyer vos fichiers d'impression via le lien dans l'email</div>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-2xl">🖨️</span>
                            <div>
                                <div class="font-semibold">Production</div>
                                <div class="text-sm text-gray-600">Nous lançons la production sous 24-48h après réception de vos fichiers</div>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-2xl">📦</span>
                            <div>
                                <div class="font-semibold">Livraison</div>
                                <div class="text-sm text-gray-600">Livraison express en 48-72h ouvrés</div>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/mon-compte.php" class="btn-primary text-white px-8 py-4 rounded font-bold text-lg inline-block">
                        Suivre ma commande
                    </a>
                    <a href="/catalogue.html" class="btn-secondary text-white px-8 py-4 rounded font-bold text-lg inline-block">
                        Continuer mes achats
                    </a>
                </div>
            </div>

            <div class="mt-8 bg-blue-50 border-2 border-blue-200 rounded-lg p-6">
                <h3 class="font-bold text-gray-900 mb-3">Besoin d'aide ?</h3>
                <p class="text-gray-700 mb-4">Notre équipe est à votre disposition pour toute question sur votre commande.</p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex items-center gap-2">
                        <span class="text-2xl">📞</span>
                        <div>
                            <div class="text-sm text-gray-600">Téléphone</div>
                            <div class="font-bold text-gray-900">01 23 45 67 89</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-2xl">📧</span>
                        <div>
                            <div class="text-sm text-gray-600">Email</div>
                            <div class="font-bold text-gray-900">contact@imprixo.fr</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    

    <script>
        // Nettoyer le panier au chargement
        localStorage.removeItem('visuprint_cart');
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
