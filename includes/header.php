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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <style>
        :root {
            --primary: #e63946;
            --primary-dark: #d62839;
            --bg: #ffffff;
            --text: #2b2d42;
            --border: #dee2e6;
            --success: #06d6a0;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; color: var(--text); }
        
        /* Top Bar */
        .topbar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #fff;
            padding: 8px 0;
            font-size: 0.85rem;
        }
        .topbar-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }
        .topbar-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .topbar a {
            color: #fff;
            text-decoration: none;
            opacity: 0.95;
        }
        .topbar a:hover { opacity: 1; }
        
        /* Header */
        .site-header {
            background: #fff;
            border-bottom: 2px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        .header-main {
            max-width: 1400px;
            margin: 0 auto;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .logo {
            font-size: 2rem;
            font-weight: 900;
            color: var(--primary);
            text-decoration: none;
            white-space: nowrap;
        }
        .search-box {
            flex: 1;
            max-width: 500px;
            position: relative;
        }
        .search-box input {
            width: 100%;
            padding: 12px 45px 12px 18px;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 1rem;
        }
        .search-box input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.1);
        }
        .search-box button {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }
        .header-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            border: none;
            cursor: pointer;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
        }
        .btn-secondary {
            background: #f8f9fa;
            color: var(--text);
            border: 2px solid var(--border);
        }
        .cart-btn {
            position: relative;
        }
        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ef476f;
            color: #fff;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            font-size: 0.75rem;
            font-weight: 900;
            display: none;
            align-items: center;
            justify-content: center;
        }
        
        /* Navigation */
        .main-nav {
            background: #f8f9fa;
            border-bottom: 1px solid var(--border);
        }
        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .nav-menu {
            display: flex;
            list-style: none;
            gap: 5px;
        }
        .nav-menu > li > a {
            display: block;
            padding: 16px 20px;
            color: var(--text);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
        }
        .nav-menu > li > a:hover {
            background: #fff;
            color: var(--primary);
        }
        .dropdown {
            position: relative;
        }
        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            background: #fff;
            min-width: 280px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            border-radius: 12px;
            padding: 12px 0;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s;
            z-index: 1000;
        }
        .dropdown-menu a {
            display: block;
            padding: 12px 20px;
            color: var(--text);
            text-decoration: none;
            font-size: 0.95rem;
        }
        .dropdown-menu a:hover {
            background: #f8f9fa;
            color: var(--primary);
            padding-left: 25px;
        }
        .mega-menu {
            min-width: 800px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            padding: 30px;
        }
        .mega-menu h4 {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--primary);
            text-transform: uppercase;
            margin-bottom: 15px;
        }
        .mega-menu ul {
            list-style: none;
        }
        .mega-menu li {
            margin-bottom: 8px;
        }
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.8rem;
            cursor: pointer;
            color: var(--text);
        }
        
        @media (max-width: 768px) {
            .topbar-content { flex-direction: column; gap: 10px; }
            .header-main { flex-wrap: wrap; }
            .search-box { order: 3; width: 100%; max-width: 100%; }
            .mobile-toggle { display: block; }
            .nav-menu { display: none; }
            .mega-menu { grid-template-columns: 1fr; min-width: 90vw; }
        }
    </style>
</head>
<body>

<!-- Top Bar -->
<div class="topbar">
    <div class="topbar-content">
        <div style="display:flex;gap:20px;flex-wrap:wrap;">
            <div class="topbar-item">✓ Livraison 48h</div>
            <div class="topbar-item">✓ Prix dégressifs</div>
            <div class="topbar-item">✓ +10 000 clients</div>
        </div>
        <div style="display:flex;gap:20px;">
            <div class="topbar-item">📞 <a href="tel:0123456789">01 23 45 67 89</a></div>
            <div class="topbar-item">✉️ <a href="mailto:contact@imprixo.fr">contact@imprixo.fr</a></div>
        </div>
    </div>
</div>

<!-- Main Header -->
<header class="site-header">
    <div class="header-main">
        <a href="/" class="logo">🎨 Imprixo</a>
        
        <div class="search-box">
            <form action="/produits.html" method="GET">
                <input type="text" name="q" placeholder="Rechercher un produit..." autocomplete="off">
                <button type="submit">🔍</button>
            </form>
        </div>
        
        <div class="header-actions">
            <a href="/devis-express.html" class="btn btn-primary">
                <span>📝</span><span>Devis gratuit</span>
            </a>
            <a href="<?php echo $client_connecte ? '/compte/tableau-de-bord.html' : '/inscription.html'; ?>" class="btn btn-secondary">
                <span>👤</span><span><?php echo $client_connecte ? 'Mon compte' : 'Inscription'; ?></span>
            </a>
            <a href="/panier.html" class="btn btn-secondary cart-btn">
                <span>🛒</span>
                <span class="cart-count" id="cartCount">0</span>
            </a>
        </div>
        
        <button class="mobile-toggle" onclick="toggleMobileMenu()">☰</button>
    </div>

    <!-- Navigation -->
    <nav class="main-nav">
        <div class="nav-container">
            <ul class="nav-menu" id="mainNav">
                <li><a href="/">Accueil</a></li>
                
                <li class="dropdown">
                    <a href="/catalogue.html">Produits ▾</a>
                    <div class="dropdown-menu mega-menu">
                        <div>
                            <h4>📦 Catégories</h4>
                            <ul>
                                <li><a href="/categorie/supports-rigides-pvc.html">Supports PVC</a></li>
                                <li><a href="/categorie/supports-aluminium.html">Supports Alu</a></li>
                                <li><a href="/categorie/baches-souples.html">Bâches</a></li>
                                <li><a href="/categorie/textiles.html">Textiles</a></li>
                                <li><a href="/categorie/panneaux-mousse.html">Panneaux Mousse</a></li>
                                <li><a href="/categorie/kakemonos.html">Kakémonos</a></li>
                                <li><a href="/categorie/adhesifs.html">Adhésifs</a></li>
                                <li><a href="/categorie/accessoires.html">Accessoires</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4>🎯 Par Usage</h4>
                            <ul>
                                <li><a href="/application/enseignes-magasin.html">Enseignes</a></li>
                                <li><a href="/application/stands-salons.html">Stands</a></li>
                                <li><a href="/application/signaletique-interieure.html">Signalétique</a></li>
                                <li><a href="/application/affichage-exterieur.html">Affichage Ext.</a></li>
                                <li><a href="/application/decoration-evenementielle.html">Déco Event</a></li>
                                <li><a href="/application/communication-chantier.html">Chantier</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4>⭐ Sélection</h4>
                            <ul>
                                <li><a href="/meilleures-ventes.html"><strong>🏆 Meilleures Ventes</strong></a></li>
                                <li><a href="/promotions.html"><strong>🔥 Promos -40%</strong></a></li>
                                <li><a href="/produits.html">Tous les Produits</a></li>
                                <li><a href="/tarifs.html">Grille Tarifaire</a></li>
                            </ul>
                        </div>
                    </div>
                </li>
                
                <li class="dropdown">
                    <a href="#">Outils ▾</a>
                    <div class="dropdown-menu">
                        <a href="/devis-express.html"><strong>📝 Devis Express</strong></a>
                        <a href="/calculateur-prix.html">💰 Calculateur Prix</a>
                        <a href="/comparateur-supports.html">🔍 Comparateur</a>
                        <a href="/guide-choix.html">🎯 Guide Choix</a>
                        <a href="/configurateur.html">🎨 Configurateur</a>
                        <a href="/upload-fichier.html">📤 Upload</a>
                    </div>
                </li>
                
                <li class="dropdown">
                    <a href="#">Ressources ▾</a>
                    <div class="dropdown-menu">
                        <a href="/guides/guide-impression-grand-format.html"><strong>📚 Guide Complet</strong></a>
                        <a href="/guides/guide-supports-pvc.html">Guide PVC</a>
                        <a href="/guides/guide-baches-publicitaires.html">Guide Bâches</a>
                        <a href="/blog/">Blog</a>
                        <a href="/faq.html">FAQ</a>
                        <a href="/lexique/">Lexique</a>
                    </div>
                </li>
                
                <li class="dropdown">
                    <a href="/espace-pro.html">Pro ▾</a>
                    <div class="dropdown-menu">
                        <a href="/espace-pro.html"><strong>💼 Espace Pro</strong></a>
                        <a href="/tarifs-pro.html">Tarifs Pro</a>
                        <a href="/api-documentation.html">API</a>
                        <a href="/partenaires.html">Partenaires</a>
                    </div>
                </li>
                
                <li class="dropdown">
                    <a href="/a-propos.html">Entreprise ▾</a>
                    <div class="dropdown-menu">
                        <a href="/a-propos.html">À Propos</a>
                        <a href="/notre-expertise.html">Expertise</a>
                        <a href="/qualite-certifications.html">Qualité</a>
                        <a href="/engagements-eco.html">Éco</a>
                        <a href="/contact.html">Contact</a>
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
