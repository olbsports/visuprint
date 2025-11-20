<?php
$pageTitle = 'Contact - Imprixo | Impression Grand Format Professionnelle';
$pageDescription = 'Contactez Imprixo pour vos besoins en impression grand format. Devis gratuit, support professionnel et réponse rapide. Tel : 01 23 45 67 89';
include __DIR__ . '/includes/header.php';
?>

<!-- Header chargé dynamiquement -->
    <div id="header-placeholder"></div>

    <!-- Hero Section -->
    <section class="gradient-bg text-white py-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">
                Contactez-nous
            </h1>
            <p class="text-xl md:text-2xl opacity-90 max-w-3xl mx-auto">
                Une question ? Un projet ? Notre équipe d'experts est à votre écoute pour vous accompagner
            </p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="max-w-7xl mx-auto px-4 py-16">
        <!-- Contact Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            <div class="contact-card text-center">
                <div class="contact-icon mx-auto">
                    📧
                </div>
                <h3 class="text-xl font-bold mb-2">Email</h3>
                <a href="mailto:contact@imprixo.fr" class="text-lg text-blue-600 hover:underline">
                    contact@imprixo.fr
                </a>
                <p class="text-gray-600 mt-2 text-sm">
                    Réponse sous 24h
                </p>
            </div>

            <div class="contact-card text-center">
                <div class="contact-icon mx-auto">
                    📞
                </div>
                <h3 class="text-xl font-bold mb-2">Téléphone</h3>
                <a href="tel:+33123456789" class="text-lg text-blue-600 hover:underline">
                    01 23 45 67 89
                </a>
                <p class="text-gray-600 mt-2 text-sm">
                    Lun-Ven 9h-18h
                </p>
            </div>

            <div class="contact-card text-center">
                <div class="contact-icon mx-auto">
                    🕒
                </div>
                <h3 class="text-xl font-bold mb-2">Horaires</h3>
                <p class="text-lg font-semibold">
                    Lun-Ven : 9h-18h
                </p>
                <p class="text-gray-600 mt-2 text-sm">
                    Fermé Sam-Dim
                </p>
            </div>
        </div>

        <!-- Form & Map Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div class="contact-card">
                <h2 class="text-3xl font-bold mb-2">
                    <span class="gradient-text">Envoyez-nous un message</span>
                </h2>
                <p class="text-gray-600 mb-8">
                    Remplissez le formulaire ci-dessous et nous vous répondrons dans les plus brefs délais
                </p>

                <div id="success-message" class="success-message">
                    ✓ Votre message a été envoyé avec succès ! Nous vous répondrons rapidement.
                </div>

                <div id="error-message" class="error-message">
                    ✕ Une erreur s'est produite. Veuillez réessayer.
                </div>

                <form id="contact-form" onsubmit="handleSubmit(event)">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="form-label" for="nom">
                                Nom *
                            </label>
                            <input
                                type="text"
                                id="nom"
                                name="nom"
                                required
                                class="form-input"
                                placeholder="Dupont"
                            >
                        </div>

                        <div>
                            <label class="form-label" for="prenom">
                                Prénom *
                            </label>
                            <input
                                type="text"
                                id="prenom"
                                name="prenom"
                                required
                                class="form-input"
                                placeholder="Jean"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="form-label" for="email">
                                Email *
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                required
                                class="form-input"
                                placeholder="jean.dupont@example.com"
                            >
                        </div>

                        <div>
                            <label class="form-label" for="telephone">
                                Téléphone
                            </label>
                            <input
                                type="tel"
                                id="telephone"
                                name="telephone"
                                class="form-input"
                                placeholder="06 12 34 56 78"
                            >
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="form-label" for="sujet">
                            Sujet *
                        </label>
                        <select id="sujet" name="sujet" required class="form-input">
                            <option value="">Sélectionnez un sujet</option>
                            <option value="devis">Demande de devis</option>
                            <option value="commande">Question sur une commande</option>
                            <option value="produit">Information produit</option>
                            <option value="livraison">Livraison</option>
                            <option value="technique">Support technique</option>
                            <option value="partenariat">Partenariat</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="form-label" for="message">
                            Message *
                        </label>
                        <textarea
                            id="message"
                            name="message"
                            required
                            rows="6"
                            class="form-input"
                            placeholder="Décrivez votre projet ou votre question..."
                        ></textarea>
                    </div>

                    <div class="mb-6">
                        <label class="flex items-start">
                            <input
                                type="checkbox"
                                required
                                class="mt-1 mr-3"
                                style="width: 18px; height: 18px;"
                            >
                            <span class="text-sm text-gray-600">
                                J'accepte que mes données soient utilisées pour traiter ma demande de contact conformément à la
                                <a href="/politique-confidentialite.html" class="text-blue-600 hover:underline">politique de confidentialité</a>
                            </span>
                        </label>
                    </div>

                    <button type="submit" class="btn-submit w-full">
                        📨 Envoyer le message
                    </button>
                </form>
            </div>

            <!-- Map & Additional Info -->
            <div>
                <!-- Map -->
                <div class="map-container mb-8">
                    <div class="map-placeholder">
                        <div class="text-6xl mb-4">📍</div>
                        <p class="text-lg font-semibold">Paris, France</p>
                        <p class="text-sm mt-2">Carte Google Maps à intégrer</p>
                        <!-- Pour intégrer une vraie carte Google Maps, utiliser :
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.9914406081493!2d2.292292615674143!3d48.85837007928746!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e2964e34e2d%3A0x8ddca9ee380ef7e0!2sEiffel%20Tower!5e0!3m2!1sen!2sfr!4v1234567890"
                            width="100%"
                            height="100%"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                        -->
                    </div>
                </div>

                <!-- FAQ Quick Links -->
                <div class="contact-card">
                    <h3 class="text-2xl font-bold mb-4">
                        <span class="gradient-text">Questions fréquentes</span>
                    </h3>
                    <p class="text-gray-600 mb-6">
                        Consultez notre FAQ pour des réponses rapides aux questions courantes
                    </p>

                    <div class="space-y-3">
                        <a href="/faq.html#delais" class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <span class="font-semibold">⏱️ Quels sont les délais de livraison ?</span>
                        </a>
                        <a href="/faq.html#formats" class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <span class="font-semibold">📏 Quels formats proposez-vous ?</span>
                        </a>
                        <a href="/faq.html#paiement" class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <span class="font-semibold">💳 Quels moyens de paiement acceptez-vous ?</span>
                        </a>
                        <a href="/faq.html#fichiers" class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <span class="font-semibold">📁 Comment préparer mes fichiers ?</span>
                        </a>
                    </div>

                    <a href="/faq.html" class="block mt-6 text-center py-3 px-6 border-2 border-purple-600 text-purple-600 font-bold rounded-lg hover:bg-purple-600 hover:text-white transition-all">
                        Voir toutes les questions →
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="gradient-bg text-white py-16 mt-16">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold mb-6">
                Besoin d'un devis rapide ?
            </h2>
            <p class="text-xl mb-8 opacity-90">
                Appelez-nous directement ou utilisez notre calculateur de prix en ligne
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="tel:+33123456789" class="bg-white text-purple-600 px-8 py-4 rounded-lg font-bold text-lg hover:shadow-xl transition-all">
                    📞 01 23 45 67 89
                </a>
                <a href="/tarifs.html" class="bg-red-600 text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-red-700 transition-all">
                    💰 Calculer mon prix
                </a>
            </div>
        </div>
    </section>

    <!-- Footer chargé dynamiquement -->
    <div id="footer-placeholder"></div>

    <!-- Scripts -->
    <script>
        // Charger le header
        fetch('/includes/header.html')
            .then(r => r.text())
            .then(html => document.getElementById('header-placeholder').innerHTML = html)
            .catch(err => console.error('Erreur chargement header:', err));

        // Charger le footer
        fetch('/includes/footer.html')
            .then(r => r.text())
            .then(html => document.getElementById('footer-placeholder').innerHTML = html)
            .catch(err => console.error('Erreur chargement footer:', err));

        // Gestion du formulaire
        function handleSubmit(event) {
            event.preventDefault();

            // Récupérer les données du formulaire
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData.entries());

            // Afficher un message de succès (simulé)
            document.getElementById('success-message').style.display = 'block';
            document.getElementById('error-message').style.display = 'none';

            // Réinitialiser le formulaire
            event.target.reset();

            // Faire défiler vers le message de succès
            document.getElementById('success-message').scrollIntoView({ behavior: 'smooth', block: 'center' });

            // Masquer le message après 5 secondes
            setTimeout(() => {
                document.getElementById('success-message').style.display = 'none';
            }, 5000);

            // En production, envoyer les données au serveur
            console.log('Données du formulaire:', data);

            /* Exemple d'envoi au serveur :
            fetch('/api/contact.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    document.getElementById('success-message').style.display = 'block';
                    document.getElementById('error-message').style.display = 'none';
                    event.target.reset();
                } else {
                    document.getElementById('error-message').style.display = 'block';
                    document.getElementById('success-message').style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                document.getElementById('error-message').style.display = 'block';
                document.getElementById('success-message').style.display = 'none';
            });
            */
        }
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
