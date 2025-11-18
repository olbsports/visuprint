<!-- Header Imprixo - À inclure dans toutes les pages -->
<?php
// Détecter si on est sur une page PHP ou HTML
$current_page = basename($_SERVER['PHP_SELF']);

// Vérifier si client connecté (si auth-client.php existe)
$client_connecte = false;
$client_nom = '';
if (file_exists(__DIR__ . '/auth-client.php')) {
    @include_once __DIR__ . '/auth-client.php';
    if (function_exists('estClientConnecte')) {
        $client_connecte = estClientConnecte();
        if ($client_connecte) {
            $client_info = getClientInfo();
            $client_nom = $client_info['prenom'] ?? '';
        }
    }
}
?>
<header class="bg-white border-b-2 border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <a href="/index.html" class="text-3xl font-black text-gray-900 hover:text-red-600 transition">
                Imprixo
            </a>

            <!-- Navigation -->
            <nav class="hidden md:flex items-center gap-8">
                <a href="/index.html" class="text-gray-700 hover:text-red-600 font-medium transition">
                    Accueil
                </a>
                <a href="/catalogue.html" class="text-gray-700 hover:text-red-600 font-medium transition">
                    Catalogue
                </a>
                <a href="/a-propos.html" class="text-gray-700 hover:text-red-600 font-medium transition">
                    À propos
                </a>
                <a href="/cgv.html" class="text-gray-700 hover:text-red-600 font-medium transition">
                    CGV
                </a>

                <?php if ($client_connecte): ?>
                    <!-- Client connecté -->
                    <div class="flex items-center gap-4 border-l-2 border-gray-200 pl-8">
                        <a href="/mon-compte.php" class="flex items-center gap-2 text-gray-700 hover:text-red-600 font-medium transition">
                            <span class="w-8 h-8 rounded-full bg-red-600 text-white flex items-center justify-center text-sm font-bold">
                                <?php echo strtoupper(substr($client_nom, 0, 1)); ?>
                            </span>
                            Mon compte
                        </a>
                        <a href="/logout-client.php" class="text-gray-500 hover:text-red-600 text-sm transition">
                            Déconnexion
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Client non connecté -->
                    <a href="/login-client.php" class="bg-red-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-red-700 transition">
                        Connexion
                    </a>
                <?php endif; ?>
            </nav>

            <!-- Menu mobile -->
            <button id="mobile-menu-btn" class="md:hidden text-gray-700 hover:text-red-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Menu mobile déroulant -->
        <div id="mobile-menu" class="hidden md:hidden mt-4 pb-4 border-t-2 border-gray-200 pt-4">
            <nav class="flex flex-col gap-4">
                <a href="/index.html" class="text-gray-700 hover:text-red-600 font-medium">Accueil</a>
                <a href="/catalogue.html" class="text-gray-700 hover:text-red-600 font-medium">Catalogue</a>
                <a href="/a-propos.html" class="text-gray-700 hover:text-red-600 font-medium">À propos</a>
                <a href="/cgv.html" class="text-gray-700 hover:text-red-600 font-medium">CGV</a>
                <?php if ($client_connecte): ?>
                    <a href="/mon-compte.php" class="text-gray-700 hover:text-red-600 font-medium">Mon compte</a>
                    <a href="/logout-client.php" class="text-gray-500 hover:text-red-600 text-sm">Déconnexion</a>
                <?php else: ?>
                    <a href="/login-client.php" class="bg-red-600 text-white px-6 py-2 rounded-lg font-bold text-center">Connexion</a>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</header>

<script>
// Toggle menu mobile
document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
    document.getElementById('mobile-menu').classList.toggle('hidden');
});
</script>
