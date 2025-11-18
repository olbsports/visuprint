<!-- Header Imprixo - Menu complet professionnel -->
<?php
// Détecter si on est sur une page PHP ou HTML
$current_page = basename($_SERVER['PHP_SELF']);

// Vérifier si client connecté (si auth-client.php existe)
$client_connecte = false;
$client_nom = '';
$client_prenom = '';
if (file_exists(__DIR__ . '/../auth-client.php')) {
    @include_once __DIR__ . '/../auth-client.php';
    if (function_exists('estClientConnecte')) {
        $client_connecte = estClientConnecte();
        if ($client_connecte) {
            $client_info = getClientInfo();
            $client_nom = $client_info['nom'] ?? '';
            $client_prenom = $client_info['prenom'] ?? '';
        }
    }
}

// Fonction helper pour activer le lien actuel
function isActive($page) {
    global $current_page;
    return ($current_page === $page) ? 'active' : '';
}
?>

<style>
    /* Styles pour le header */
    .header-main {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }

    .nav-link {
        color: white;
        text-decoration: none;
        padding: 12px 20px;
        font-weight: 500;
        transition: all 0.3s;
        border-radius: 8px;
        display: inline-block;
    }

    .nav-link:hover {
        background: rgba(255,255,255,0.2);
        transform: translateY(-2px);
    }

    .nav-link.active {
        background: rgba(255,255,255,0.3);
        font-weight: 700;
    }

    .logo-text {
        font-size: 32px;
        font-weight: 900;
        color: white;
        text-decoration: none;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        transition: all 0.3s;
    }

    .logo-text:hover {
        transform: scale(1.05);
        text-shadow: 3px 3px 6px rgba(0,0,0,0.3);
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: white;
        color: #667eea;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    .btn-primary {
        background: white;
        color: #667eea;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    .dropdown {
        position: relative;
    }

    .dropdown-menu {
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        min-width: 220px;
        margin-top: 10px;
        display: none;
        z-index: 1000;
    }

    .dropdown:hover .dropdown-menu {
        display: block;
    }

    .dropdown-menu a {
        display: block;
        padding: 12px 20px;
        color: #333;
        text-decoration: none;
        transition: all 0.2s;
        border-bottom: 1px solid #f0f0f0;
    }

    .dropdown-menu a:last-child {
        border-bottom: none;
    }

    .dropdown-menu a:hover {
        background: #f8f9fa;
        color: #667eea;
        padding-left: 25px;
    }

    .mobile-menu {
        display: none;
    }

    @media (max-width: 768px) {
        .desktop-nav {
            display: none;
        }

        .mobile-menu {
            display: block;
        }

        .mobile-menu-content {
            background: white;
            border-radius: 12px;
            margin-top: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            padding: 20px;
        }

        .mobile-menu-content a {
            display: block;
            padding: 12px 15px;
            color: #333;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 8px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .mobile-menu-content a:hover,
        .mobile-menu-content a.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
    }
</style>

<header class="header-main sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <a href="/index.html" class="logo-text">
                🎨 Imprixo
            </a>

            <!-- Navigation Desktop -->
            <nav class="desktop-nav flex items-center gap-6">
                <a href="/index.html" class="nav-link <?php echo isActive('index.html'); ?>">
                    🏠 Accueil
                </a>

                <a href="/catalogue.html" class="nav-link <?php echo isActive('catalogue.html'); ?>">
                    📦 Catalogue
                </a>

                <div class="dropdown">
                    <a href="#" class="nav-link">
                        ℹ️ Informations ▼
                    </a>
                    <div class="dropdown-menu">
                        <a href="/a-propos.html">À propos</a>
                        <a href="/cgv.html">Conditions Générales</a>
                        <a href="/mentions-legales.html">Mentions légales</a>
                        <a href="/politique-confidentialite.html">Politique de confidentialité</a>
                    </div>
                </div>

                <a href="/panier.html" class="nav-link <?php echo isActive('panier.html'); ?>">
                    🛒 Panier
                </a>

                <?php if ($client_connecte): ?>
                    <!-- Client connecté -->
                    <div class="dropdown">
                        <a href="#" class="nav-link" style="display: flex; align-items: center; gap: 10px;">
                            <span class="user-avatar">
                                <?php echo strtoupper(substr($client_prenom, 0, 1)); ?>
                            </span>
                            <?php echo htmlspecialchars($client_prenom); ?> ▼
                        </a>
                        <div class="dropdown-menu">
                            <a href="/mon-compte.php">👤 Mon compte</a>
                            <a href="/mon-compte.php">📋 Mes commandes</a>
                            <a href="/logout-client.php">🚪 Déconnexion</a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Client non connecté -->
                    <a href="/login-client.php" class="btn-primary">
                        🔐 Connexion
                    </a>
                <?php endif; ?>
            </nav>

            <!-- Bouton menu mobile -->
            <button id="mobile-menu-btn" class="mobile-menu text-white text-3xl cursor-pointer">
                ☰
            </button>
        </div>

        <!-- Menu mobile -->
        <div id="mobile-menu-content" class="mobile-menu-content" style="display: none;">
            <a href="/index.html" class="<?php echo isActive('index.html'); ?>">🏠 Accueil</a>
            <a href="/catalogue.html" class="<?php echo isActive('catalogue.html'); ?>">📦 Catalogue</a>
            <a href="/panier.html" class="<?php echo isActive('panier.html'); ?>">🛒 Panier</a>

            <hr style="margin: 15px 0; border-color: #e0e0e0;">

            <a href="/a-propos.html">ℹ️ À propos</a>
            <a href="/cgv.html">📄 Conditions Générales</a>
            <a href="/mentions-legales.html">⚖️ Mentions légales</a>
            <a href="/politique-confidentialite.html">🔒 Confidentialité</a>

            <hr style="margin: 15px 0; border-color: #e0e0e0;">

            <?php if ($client_connecte): ?>
                <a href="/mon-compte.php">👤 Mon compte (<?php echo htmlspecialchars($client_prenom); ?>)</a>
                <a href="/logout-client.php">🚪 Déconnexion</a>
            <?php else: ?>
                <a href="/login-client.php" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-align: center;">🔐 Connexion</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<script>
// Toggle menu mobile
const mobileMenuBtn = document.getElementById('mobile-menu-btn');
const mobileMenuContent = document.getElementById('mobile-menu-content');

if (mobileMenuBtn && mobileMenuContent) {
    mobileMenuBtn.addEventListener('click', function() {
        if (mobileMenuContent.style.display === 'none' || mobileMenuContent.style.display === '') {
            mobileMenuContent.style.display = 'block';
            mobileMenuBtn.textContent = '✕';
        } else {
            mobileMenuContent.style.display = 'none';
            mobileMenuBtn.textContent = '☰';
        }
    });
}
</script>
