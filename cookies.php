<?php
$pageTitle = 'Gestion des Cookies - Politique Cookies | Imprixo';
$pageDescription = 'Politique de gestion des cookies Imprixo ✓ RGPD conforme ✓ Paramétrage cookies ✓ Respect de la vie privée';
include __DIR__ . '/includes/header.php';
?>

<!-- HEADER -->
    <div id="header-placeholder"></div>
    <script>fetch('/includes/header.html').then(r=>r.text()).then(html=>document.getElementById('header-placeholder').innerHTML=html)</script>

    <!-- HERO -->
    <section class="hero-gradient text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="badge badge-red mb-4">Confidentialité</span>
            <h1 class="text-5xl font-black mb-6">
                Gestion des Cookies
            </h1>
            <p class="text-xl text-gray-300">
                Notre politique d'utilisation des cookies et de protection de vos données
            </p>
        </div>
    </section>

    <!-- CONTENU -->
    <section class="py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- PARAMÈTRES COOKIES -->
            <div class="bg-white rounded-2xl border-2 border-gray-200 p-8 mb-8">
                <h2 class="text-3xl font-black text-gray-900 mb-6">🍪 Paramètres des Cookies</h2>

                <p class="text-gray-700 mb-8">
                    Gérez vos préférences de cookies pour votre navigation sur Imprixo.fr
                </p>

                <div class="space-y-6">
                    <!-- Cookies essentiels -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="font-black text-lg">Cookies Essentiels</h3>
                                <p class="text-sm text-gray-600">Obligatoires - Toujours actifs</p>
                            </div>
                            <div class="toggle-switch active" style="cursor: not-allowed; opacity: 0.5;"></div>
                        </div>
                        <p class="text-gray-700 text-sm">
                            Ces cookies sont nécessaires au fonctionnement du site et ne peuvent pas être désactivés.
                            Ils permettent de mémoriser votre panier, vos préférences de navigation, et d'assurer la sécurité du site.
                        </p>
                    </div>

                    <!-- Cookies fonctionnels -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="font-black text-lg">Cookies Fonctionnels</h3>
                                <p class="text-sm text-gray-600">Recommandés</p>
                            </div>
                            <div class="toggle-switch active" id="toggle-functional"></div>
                        </div>
                        <p class="text-gray-700 text-sm">
                            Ces cookies permettent d'améliorer votre expérience en mémorisant vos choix
                            (langue, région, préférences d'affichage) et en personnalisant le contenu du site.
                        </p>
                    </div>

                    <!-- Cookies analytiques -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="font-black text-lg">Cookies Analytiques</h3>
                                <p class="text-sm text-gray-600">Facultatifs</p>
                            </div>
                            <div class="toggle-switch" id="toggle-analytics"></div>
                        </div>
                        <p class="text-gray-700 text-sm">
                            Ces cookies nous aident à comprendre comment vous utilisez notre site,
                            à identifier les pages les plus visitées et à améliorer nos services.
                            Données anonymisées via Google Analytics.
                        </p>
                    </div>

                    <!-- Cookies marketing -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="font-black text-lg">Cookies Marketing</h3>
                                <p class="text-sm text-gray-600">Facultatifs</p>
                            </div>
                            <div class="toggle-switch" id="toggle-marketing"></div>
                        </div>
                        <p class="text-gray-700 text-sm">
                            Ces cookies permettent de suivre votre navigation pour vous proposer des publicités
                            personnalisées et mesurer l'efficacité de nos campagnes marketing.
                        </p>
                    </div>
                </div>

                <div class="mt-8 flex gap-4">
                    <button class="btn-primary text-white px-8 py-3 rounded-lg font-bold flex-1" onclick="saveCookiePreferences()">
                        Enregistrer mes Préférences
                    </button>
                    <button class="border-2 border-gray-300 px-8 py-3 rounded-lg font-bold hover:border-red-600 hover:bg-red-50 hover:text-red-600 transition" onclick="acceptAllCookies()">
                        Tout Accepter
                    </button>
                    <button class="border-2 border-gray-300 px-8 py-3 rounded-lg font-bold hover:border-red-600 hover:bg-red-50 hover:text-red-600 transition" onclick="rejectAllCookies()">
                        Tout Refuser
                    </button>
                </div>
            </div>

            <!-- QU'EST-CE QU'UN COOKIE -->
            <div class="bg-white rounded-2xl border-2 border-gray-200 p-8 mb-8">
                <h2 class="text-3xl font-black text-gray-900 mb-6">📋 Qu'est-ce qu'un Cookie ?</h2>

                <p class="text-gray-700 mb-4">
                    Un <strong>cookie</strong> est un petit fichier texte stocké sur votre appareil (ordinateur, smartphone, tablette)
                    lorsque vous visitez un site web. Les cookies permettent au site de se souvenir de vos actions et préférences
                    pendant une période donnée.
                </p>

                <p class="text-gray-700 mb-4">
                    Les cookies ne contiennent aucun virus et ne peuvent pas accéder aux fichiers de votre appareil.
                    Ils sont utilisés uniquement pour améliorer votre expérience de navigation.
                </p>
            </div>

            <!-- TYPES DE COOKIES -->
            <div class="bg-white rounded-2xl border-2 border-gray-200 p-8 mb-8">
                <h2 class="text-3xl font-black text-gray-900 mb-6">🔍 Types de Cookies Utilisés</h2>

                <div class="space-y-6">
                    <div>
                        <h3 class="font-black mb-3">1. Cookies de Session</h3>
                        <p class="text-gray-700">
                            Cookies temporaires supprimés dès que vous fermez votre navigateur.
                            Ils permettent de maintenir votre connexion active pendant votre visite.
                        </p>
                    </div>

                    <div>
                        <h3 class="font-black mb-3">2. Cookies Persistants</h3>
                        <p class="text-gray-700">
                            Cookies qui restent sur votre appareil pendant une durée déterminée (jusqu'à 13 mois maximum).
                            Ils permettent de mémoriser vos préférences entre deux visites.
                        </p>
                    </div>

                    <div>
                        <h3 class="font-black mb-3">3. Cookies Tiers</h3>
                        <p class="text-gray-700 mb-3">
                            Cookies déposés par des services externes (Google Analytics, réseaux sociaux, publicité).
                            Nous utilisons :
                        </p>
                        <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                            <li><strong>Google Analytics</strong> - Analyse d'audience anonymisée</li>
                            <li><strong>Facebook Pixel</strong> - Mesure de l'efficacité publicitaire</li>
                            <li><strong>Google Ads</strong> - Publicité ciblée</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- GESTION -->
            <div class="bg-white rounded-2xl border-2 border-gray-200 p-8 mb-8">
                <h2 class="text-3xl font-black text-gray-900 mb-6">⚙️ Comment Gérer vos Cookies ?</h2>

                <p class="text-gray-700 mb-6">
                    Vous pouvez gérer vos préférences de cookies à tout moment via :
                </p>

                <div class="space-y-4">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="font-black mb-3">1. Paramètres du Site</h3>
                        <p class="text-gray-700 mb-3">
                            Utilisez le panneau de gestion des cookies ci-dessus pour activer/désactiver
                            chaque catégorie de cookies.
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="font-black mb-3">2. Paramètres du Navigateur</h3>
                        <p class="text-gray-700 mb-3">
                            Vous pouvez configurer votre navigateur pour accepter ou refuser les cookies :
                        </p>
                        <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4 text-sm">
                            <li><strong>Chrome</strong> : Paramètres → Confidentialité et sécurité → Cookies</li>
                            <li><strong>Firefox</strong> : Préférences → Vie privée et sécurité → Cookies</li>
                            <li><strong>Safari</strong> : Préférences → Confidentialité → Cookies</li>
                            <li><strong>Edge</strong> : Paramètres → Confidentialité → Cookies</li>
                        </ul>
                    </div>

                    <div class="bg-red-50 border-2 border-red-600 rounded-lg p-6">
                        <h3 class="font-black mb-3">⚠️ Attention</h3>
                        <p class="text-gray-700">
                            Le blocage de certains cookies peut affecter le fonctionnement du site
                            (panier, connexion, préférences). Nous recommandons de conserver au minimum les cookies essentiels.
                        </p>
                    </div>
                </div>
            </div>

            <!-- DURÉE DE CONSERVATION -->
            <div class="bg-white rounded-2xl border-2 border-gray-200 p-8 mb-8">
                <h2 class="text-3xl font-black text-gray-900 mb-6">⏱️ Durée de Conservation</h2>

                <div class="space-y-3">
                    <div class="flex justify-between p-4 bg-gray-50 rounded-lg">
                        <span class="font-bold">Cookies essentiels</span>
                        <span class="text-gray-600">Session (fermée du navigateur)</span>
                    </div>
                    <div class="flex justify-between p-4 bg-gray-50 rounded-lg">
                        <span class="font-bold">Cookies fonctionnels</span>
                        <span class="text-gray-600">6 mois maximum</span>
                    </div>
                    <div class="flex justify-between p-4 bg-gray-50 rounded-lg">
                        <span class="font-bold">Cookies analytiques</span>
                        <span class="text-gray-600">13 mois maximum</span>
                    </div>
                    <div class="flex justify-between p-4 bg-gray-50 rounded-lg">
                        <span class="font-bold">Cookies marketing</span>
                        <span class="text-gray-600">13 mois maximum</span>
                    </div>
                </div>
            </div>

            <!-- CONTACT -->
            <div class="bg-white rounded-2xl border-2 border-gray-200 p-8">
                <h2 class="text-3xl font-black text-gray-900 mb-6">📧 Nous Contacter</h2>

                <p class="text-gray-700 mb-6">
                    Pour toute question concernant notre politique de cookies ou la protection de vos données :
                </p>

                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="space-y-3">
                        <div>📧 Email : <a href="mailto:contact@imprixo.fr" class="text-red-600 font-bold hover:underline">contact@imprixo.fr</a></div>
                        <div>📞 Téléphone : <a href="tel:0123456789" class="text-red-600 font-bold hover:underline">01 23 45 67 89</a></div>
                        <div>🕒 Horaires : Lun-Ven 9h-18h</div>
                    </div>
                </div>

                <div class="mt-6 text-sm text-gray-600">
                    <p>
                        Vous disposez d'un droit d'accès, de rectification, de suppression et d'opposition
                        concernant vos données personnelles. Pour en savoir plus, consultez notre
                        <a href="/politique-confidentialite.html" class="text-red-600 font-bold hover:underline">Politique de Confidentialité</a>.
                    </p>
                </div>
            </div>

        </div>
    </section>

    <!-- FOOTER -->
    <div id="footer-placeholder"></div>
    <script>fetch('/includes/footer.html').then(r=>r.text()).then(html=>document.getElementById('footer-placeholder').innerHTML=html)</script>

    <!-- SCRIPTS -->
    <script>
        // Toggle switches
        document.querySelectorAll('.toggle-switch').forEach(toggle => {
            if (!toggle.id) return;

            toggle.addEventListener('click', function() {
                this.classList.toggle('active');
            });
        });

        // Save preferences
        function saveCookiePreferences() {
            const functional = document.getElementById('toggle-functional').classList.contains('active');
            const analytics = document.getElementById('toggle-analytics').classList.contains('active');
            const marketing = document.getElementById('toggle-marketing').classList.contains('active');

            localStorage.setItem('cookie-functional', functional);
            localStorage.setItem('cookie-analytics', analytics);
            localStorage.setItem('cookie-marketing', marketing);

            alert('✓ Vos préférences ont été enregistrées !');
        }

        // Accept all
        function acceptAllCookies() {
            document.getElementById('toggle-functional').classList.add('active');
            document.getElementById('toggle-analytics').classList.add('active');
            document.getElementById('toggle-marketing').classList.add('active');
            saveCookiePreferences();
        }

        // Reject all
        function rejectAllCookies() {
            document.getElementById('toggle-functional').classList.remove('active');
            document.getElementById('toggle-analytics').classList.remove('active');
            document.getElementById('toggle-marketing').classList.remove('active');
            saveCookiePreferences();
        }

        // Load preferences
        window.addEventListener('load', function() {
            const functional = localStorage.getItem('cookie-functional') === 'true';
            const analytics = localStorage.getItem('cookie-analytics') === 'true';
            const marketing = localStorage.getItem('cookie-marketing') === 'true';

            if (functional) document.getElementById('toggle-functional').classList.add('active');
            if (analytics) document.getElementById('toggle-analytics').classList.add('active');
            if (marketing) document.getElementById('toggle-marketing').classList.add('active');
        });
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
