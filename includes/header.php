<?php
/**
 * HEADER UNIVERSEL IMPRIXO - Utilisé sur TOUTES les pages
 * Navigation complète + Search + Panier dynamique
 */

// Détecter la page courante
$current_page = basename($_SERVER['PHP_SELF'] ?? $_SERVER['SCRIPT_NAME'] ?? '');
$current_path = $_SERVER['REQUEST_URI'] ?? '';

// Vérifier si client connecté
$client_connecte = false;
$client_nom = '';
if (file_exists(__DIR__ . '/../auth-client.php')) {
    @include_once __DIR__ . '/../auth-client.php';
    if (function_exists('estClientConnecte')) {
        $client_connecte = estClientConnecte();
        if ($client_connecte && function_exists('getClientInfo')) {
            $info = getClientInfo();
            $client_nom = ($info['prenom'] ?? '') . ' ' . ($info['nom'] ?? '');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Imprixo - Impression Grand Format Professionnelle'; ?></title>
    <meta name="description" content="<?php echo $pageDescription ?? 'Impression grand format professionnelle - Forex, Dibond, Bâches PVC - Livraison 48h'; ?>">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Custom Styles -->
    <link rel="stylesheet" href="/assets/css/styles.css">

    <!-- Tailwind CSS (pour classes utility) -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

<!-- Top Bar -->
<div class="topbar">
    <div class="topbar-content">
        <div style="display:flex;gap:20px;flex-wrap:wrap;">
            <div class="topbar-item"><i class="fas fa-check-circle"></i> Livraison 48h</div>
            <div class="topbar-item"><i class="fas fa-tag"></i> Prix dégressifs</div>
            <div class="topbar-item"><i class="fas fa-users"></i> +10 000 clients</div>
        </div>
        <div style="display:flex;gap:20px;">
            <div class="topbar-item"><i class="fas fa-phone"></i> <a href="tel:0123456789">01 23 45 67 89</a></div>
            <div class="topbar-item"><i class="fas fa-envelope"></i> <a href="mailto:contact@imprixo.fr">contact@imprixo.fr</a></div>
        </div>
    </div>
</div>

<!-- Main Header -->
<header class="site-header">
    <div class="header-main">
        <a href="/" class="logo"><i class="fas fa-palette"></i> Imprixo</a>

        <div class="search-box">
            <form action="/produits.html" method="GET">
                <input type="text" name="q" placeholder="Rechercher un produit..." autocomplete="off">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <div class="header-actions">
            <a href="/contact.php" class="btn btn-primary">
                <i class="fas fa-envelope"></i><span>Contact</span>
            </a>
            <a href="<?php echo $client_connecte ? '/mon-compte.php' : '/inscription.php'; ?>" class="btn btn-secondary">
                <i class="fas fa-user"></i><span><?php echo $client_connecte ? 'Mon compte' : 'Inscription'; ?></span>
            </a>
            <a href="/panier.php" class="btn btn-secondary cart-btn">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count" id="cartCount">0</span>
            </a>
        </div>

        <button class="mobile-toggle" onclick="toggleMobileMenu()"><i class="fas fa-bars"></i></button>
    </div>

    <!-- Navigation -->
    <nav class="main-nav">
        <div class="nav-container">
            <ul class="nav-menu" id="mainNav">
                <li><a href="/">Accueil</a></li>
                
                <li class="dropdown">
                    <a href="/catalogue.html"><i class="fas fa-box"></i> Produits <i class="fas fa-chevron-down"></i></a>
                    <div class="dropdown-menu mega-menu">
                        <div>
                            <h4><i class="fas fa-th-large"></i> Catégories</h4>
                            <ul>
                                <li><a href="/categorie/supports-rigides-pvc.html"><i class="fas fa-file"></i> Supports PVC</a></li>
                                <li><a href="/categorie/supports-aluminium.html"><i class="fas fa-gem"></i> Supports Alu</a></li>
                                <li><a href="/categorie/baches-souples.html"><i class="fas fa-flag"></i> Bâches</a></li>
                                <li><a href="/categorie/textiles.html"><i class="fas fa-tshirt"></i> Textiles</a></li>
                                <li><a href="/categorie/panneaux-mousse.html"><i class="fas fa-square"></i> Panneaux Mousse</a></li>
                                <li><a href="/categorie/kakemonos.html"><i class="fas fa-scroll"></i> Kakémonos</a></li>
                                <li><a href="/categorie/adhesifs.html"><i class="fas fa-sticky-note"></i> Adhésifs</a></li>
                                <li><a href="/categorie/accessoires.html"><i class="fas fa-tools"></i> Accessoires</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4><i class="fas fa-bullseye"></i> Par Usage</h4>
                            <ul>
                                <li><a href="/application/enseignes-magasin.html"><i class="fas fa-store"></i> Enseignes</a></li>
                                <li><a href="/application/stands-salons.html"><i class="fas fa-booth-curtain"></i> Stands</a></li>
                                <li><a href="/application/signaletique-interieure.html"><i class="fas fa-signs-post"></i> Signalétique</a></li>
                                <li><a href="/application/affichage-exterieur.html"><i class="fas fa-external-link-alt"></i> Affichage Ext.</a></li>
                                <li><a href="/application/decoration-evenementielle.html"><i class="fas fa-calendar-check"></i> Déco Event</a></li>
                                <li><a href="/application/communication-chantier.html"><i class="fas fa-hard-hat"></i> Chantier</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4><i class="fas fa-star"></i> Sélection</h4>
                            <ul>
                                <li><a href="/meilleures-ventes.html"><strong><i class="fas fa-trophy"></i> Meilleures Ventes</strong></a></li>
                                <li><a href="/promotions.html"><strong><i class="fas fa-fire"></i> Promos -40%</strong></a></li>
                                <li><a href="/produits.html"><i class="fas fa-list"></i> Tous les Produits</a></li>
                                <li><a href="/tarifs.html"><i class="fas fa-table"></i> Grille Tarifaire</a></li>
                            </ul>
                        </div>
                    </div>
                </li>

                <li class="dropdown">
                    <a href="#"><i class="fas fa-book"></i> Ressources <i class="fas fa-chevron-down"></i></a>
                    <div class="dropdown-menu">
                        <a href="/guides/guide-impression-grand-format.html"><strong><i class="fas fa-book-open"></i> Guide Complet</strong></a>
                        <a href="/guides/guide-supports-pvc.html"><i class="fas fa-file-alt"></i> Guide PVC</a>
                        <a href="/guides/guide-baches-publicitaires.html"><i class="fas fa-file-alt"></i> Guide Bâches</a>
                        <a href="/blog/"><i class="fas fa-blog"></i> Blog</a>
                        <a href="/faq.html"><i class="fas fa-question-circle"></i> FAQ</a>
                        <a href="/lexique/"><i class="fas fa-spell-check"></i> Lexique</a>
                    </div>
                </li>

                <li class="dropdown">
                    <a href="/espace-pro.html"><i class="fas fa-briefcase"></i> Pro <i class="fas fa-chevron-down"></i></a>
                    <div class="dropdown-menu">
                        <a href="/espace-pro.html"><strong><i class="fas fa-briefcase"></i> Espace Pro</strong></a>
                        <a href="/tarifs-pro.html"><i class="fas fa-file-contract"></i> Tarifs Pro</a>
                        <a href="/api-documentation.html"><i class="fas fa-code"></i> API</a>
                        <a href="/partenaires.html"><i class="fas fa-handshake"></i> Partenaires</a>
                    </div>
                </li>

                <li class="dropdown">
                    <a href="/a-propos.html"><i class="fas fa-building"></i> Entreprise <i class="fas fa-chevron-down"></i></a>
                    <div class="dropdown-menu">
                        <a href="/a-propos.html"><i class="fas fa-info-circle"></i> À Propos</a>
                        <a href="/notre-expertise.html"><i class="fas fa-award"></i> Expertise</a>
                        <a href="/qualite-certifications.html"><i class="fas fa-certificate"></i> Qualité</a>
                        <a href="/engagements-eco.html"><i class="fas fa-leaf"></i> Éco</a>
                        <a href="/contact.html"><i class="fas fa-envelope"></i> Contact</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>

<script>
function toggleMobileMenu() {
    const nav = document.getElementById('mainNav');
    if (nav.style.display === 'flex') {
        nav.style.display = 'none';
    } else {
        nav.style.display = 'flex';
        nav.style.flexDirection = 'column';
        nav.style.position = 'absolute';
        nav.style.background = '#fff';
        nav.style.width = '100%';
        nav.style.left = '0';
        nav.style.boxShadow = '0 8px 32px rgba(0,0,0,0.12)';
        nav.style.padding = '20px';
        nav.style.zIndex = '999';
    }
}

function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const count = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);
    const badge = document.getElementById('cartCount');
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'flex' : 'none';
    }
}

updateCartCount();
window.addEventListener('storage', updateCartCount);
</script>
