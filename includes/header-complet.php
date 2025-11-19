<?php
// Header Imprixo - Navigation complète 113 pages
$current_page = basename($_SERVER['PHP_SELF'] ?? '');
$client_connecte = false;
if (file_exists(__DIR__ . '/../auth-client.php')) {
    @include_once __DIR__ . '/../auth-client.php';
    if (function_exists('estClientConnecte')) {
        $client_connecte = estClientConnecte();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
:root{--primary:#e63946;--primary-dark:#d62839;--bg:#fff;--text:#2b2d42;--border:#dee2e6}
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;color:var(--text)}
.top-bar{background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff;padding:8px 0;font-size:0.85rem}
.top-bar-content{max-width:1400px;margin:0 auto;padding:0 20px;display:flex;justify-content:space-between;flex-wrap:wrap;gap:15px}
.top-bar-item{display:flex;align-items:center;gap:6px}
.top-bar a{color:#fff;text-decoration:none}
.site-header{background:#fff;border-bottom:2px solid var(--border);position:sticky;top:0;z-index:1000;box-shadow:0 2px 12px rgba(0,0,0,0.08)}
.main-header{max-width:1400px;margin:0 auto;padding:15px 20px;display:flex;justify-content:space-between;align-items:center;gap:20px;flex-wrap:wrap}
.logo{font-size:2rem;font-weight:900;color:var(--primary);text-decoration:none;display:flex;align-items:center;gap:10px}
.header-search{flex:1;max-width:500px}
.header-search input{width:100%;padding:12px 18px;border:2px solid var(--border);border-radius:8px;font-size:1rem}
.header-search input:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(230,57,70,0.1)}
.header-actions{display:flex;gap:10px}
.header-btn{padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:700;display:flex;align-items:center;gap:8px;white-space:nowrap}
.btn-primary{background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff}
.btn-secondary{background:#f8f9fa;color:var(--text);border:2px solid var(--border)}
.cart-btn{position:relative}
.cart-count{position:absolute;top:-8px;right:-8px;background:#ef476f;color:#fff;width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:900}
.main-nav{background:#f8f9fa;border-bottom:1px solid var(--border)}
.nav-content{max-width:1400px;margin:0 auto;padding:0 20px;display:flex}
.nav-menu{display:flex;list-style:none;gap:5px}
.nav-link{display:block;padding:16px 20px;color:var(--text);text-decoration:none;font-weight:600;transition:all 0.2s}
.nav-link:hover{background:#fff;color:var(--primary)}
.dropdown{position:relative}
.dropdown:hover .dropdown-menu{opacity:1;visibility:visible;transform:translateY(0)}
.dropdown-menu{position:absolute;top:100%;left:0;background:#fff;min-width:280px;box-shadow:0 8px 32px rgba(0,0,0,0.12);border-radius:12px;padding:12px 0;opacity:0;visibility:hidden;transform:translateY(-10px);transition:all 0.3s;z-index:1000}
.dropdown-menu a{display:block;padding:12px 20px;color:var(--text);text-decoration:none;font-size:0.95rem}
.dropdown-menu a:hover{background:#f8f9fa;color:var(--primary);padding-left:25px}
.menu-section{padding:8px 20px;font-size:0.8rem;font-weight:700;color:#6c757d;text-transform:uppercase;border-top:1px solid var(--border);margin-top:8px}
.menu-section:first-child{border:none;margin:0}
.mega-menu{min-width:800px;display:grid;grid-template-columns:repeat(3,1fr);gap:30px;padding:30px}
.mega-menu h4{font-size:0.85rem;font-weight:700;color:var(--primary);text-transform:uppercase;margin-bottom:15px}
.mega-menu ul{list-style:none}
.mega-menu li{margin-bottom:8px}
.mobile-toggle{display:none;background:none;border:none;font-size:1.8rem;cursor:pointer}
@media (max-width:768px){
.mobile-toggle{display:block}
.nav-menu{display:none}
.header-search{order:3;width:100%;max-width:100%}
.header-btn span:not(.cart-count){display:none}
.mega-menu{grid-template-columns:1fr;min-width:90vw}
}
</style>
</head>
<body>

<!-- Top Bar -->
<div class="top-bar">
<div class="top-bar-content">
<div style="display:flex;gap:20px">
<div class="top-bar-item">✓ Livraison 48h</div>
<div class="top-bar-item">✓ Prix dégressifs</div>
<div class="top-bar-item">✓ +10 000 clients</div>
</div>
<div style="display:flex;gap:20px">
<div class="top-bar-item">📞 <a href="tel:0123456789">01 23 45 67 89</a></div>
<div class="top-bar-item">✉️ <a href="mailto:contact@imprixo.fr">contact@imprixo.fr</a></div>
</div>
</div>
</div>

<!-- Main Header -->
<header class="site-header">
<div class="main-header">
<a href="/" class="logo"><span>🎨</span><span>Imprixo</span></a>

<div class="header-search">
<form action="/produits.html" method="GET">
<input type="text" name="q" placeholder="Rechercher un produit...">
</form>
</div>

<div class="header-actions">
<a href="/devis-express.html" class="header-btn btn-primary"><span>📝</span><span>Devis gratuit</span></a>
<a href="<?php echo $client_connecte ? '/compte/tableau-de-bord.html' : '/inscription.html'; ?>" class="header-btn btn-secondary">
<span>👤</span><span><?php echo $client_connecte ? 'Mon compte' : 'Inscription'; ?></span>
</a>
<a href="/panier.html" class="header-btn btn-secondary cart-btn"><span>🛒</span><span>Panier</span><span class="cart-count" id="cartCount">0</span></a>
</div>

<button class="mobile-toggle" onclick="toggleMenu()">☰</button>
</div>

<!-- Navigation -->
<nav class="main-nav">
<div class="nav-content">
<ul class="nav-menu" id="mainMenu">
<li><a href="/" class="nav-link">Accueil</a></li>

<li class="dropdown">
<a href="/catalogue.html" class="nav-link">Produits ▾</a>
<div class="dropdown-menu mega-menu">
<div>
<h4>📦 Catégories</h4>
<ul>
<li><a href="/categorie/supports-rigides-pvc.html">Supports PVC Rigides</a></li>
<li><a href="/categorie/supports-aluminium.html">Supports Aluminium</a></li>
<li><a href="/categorie/baches-souples.html">Bâches Souples</a></li>
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
<li><a href="/application/enseignes-magasin.html">Enseignes Magasin</a></li>
<li><a href="/application/stands-salons.html">Stands & Salons</a></li>
<li><a href="/application/signaletique-interieure.html">Signalétique Intérieure</a></li>
<li><a href="/application/affichage-exterieur.html">Affichage Extérieur</a></li>
<li><a href="/application/decoration-evenementielle.html">Déco Événementielle</a></li>
<li><a href="/application/communication-chantier.html">Communication Chantier</a></li>
</ul>
</div>
<div>
<h4>⭐ Sélection</h4>
<ul>
<li><a href="/meilleures-ventes.html"><strong>🏆 Meilleures Ventes</strong></a></li>
<li><a href="/promotions.html"><strong>🔥 Promotions -40%</strong></a></li>
<li><a href="/produits.html">📋 Tous les Produits</a></li>
<li><a href="/tarifs.html">💰 Grille Tarifaire</a></li>
</ul>
</div>
</div>
</li>

<li class="dropdown">
<a href="#" class="nav-link">Outils ▾</a>
<div class="dropdown-menu">
<a href="/devis-express.html"><strong>📝 Devis Express</strong></a>
<a href="/calculateur-prix.html">💰 Calculateur Prix</a>
<a href="/comparateur-supports.html">🔍 Comparateur</a>
<a href="/guide-choix.html">🎯 Guide de Choix</a>
<a href="/configurateur.html">🎨 Configurateur</a>
<a href="/upload-fichier.html">📤 Upload Fichiers</a>
</div>
</li>

<li class="dropdown">
<a href="#" class="nav-link">Ressources ▾</a>
<div class="dropdown-menu">
<div class="menu-section">📚 Guides</div>
<a href="/guides/guide-impression-grand-format.html"><strong>Guide Complet</strong></a>
<a href="/guides/guide-supports-pvc.html">Guide PVC</a>
<a href="/guides/guide-baches-publicitaires.html">Guide Bâches</a>
<div class="menu-section">✍️ Blog & FAQ</div>
<a href="/blog/">Blog</a>
<a href="/faq.html">FAQ</a>
<a href="/lexique/">Lexique A-Z</a>
</div>
</li>

<li class="dropdown">
<a href="/espace-pro.html" class="nav-link">Pro ▾</a>
<div class="dropdown-menu">
<a href="/espace-pro.html"><strong>💼 Espace Pro</strong></a>
<a href="/tarifs-pro.html">💰 Tarifs Pro</a>
<a href="/api-documentation.html">🔌 API</a>
<a href="/partenaires.html">🤝 Partenaires</a>
</div>
</li>

<li class="dropdown">
<a href="/a-propos.html" class="nav-link">Entreprise ▾</a>
<div class="dropdown-menu">
<a href="/a-propos.html">🏢 À Propos</a>
<a href="/notre-expertise.html">⚡ Expertise</a>
<a href="/qualite-certifications.html">✓ Qualité</a>
<a href="/engagements-eco.html">🌱 Éco</a>
<a href="/contact.html">📧 Contact</a>
</div>
</li>
</ul>
</div>
</nav>
</header>

<script>
function toggleMenu(){
const menu=document.getElementById('mainMenu');
menu.style.display=menu.style.display==='flex'?'none':'flex';
if(menu.style.display==='flex'){menu.style.flexDirection='column';menu.style.position='absolute';menu.style.background='#fff';menu.style.width='100%';menu.style.left='0';menu.style.boxShadow='0 8px 32px rgba(0,0,0,0.12)';menu.style.padding='20px';menu.style.zIndex='999';}
}
function updateCartCount(){
const cart=JSON.parse(localStorage.getItem('cart')||'[]');
const count=cart.reduce((sum,item)=>sum+(item.quantity||1),0);
const badge=document.getElementById('cartCount');
if(badge){badge.textContent=count;badge.style.display=count>0?'flex':'none';}
}
updateCartCount();
window.addEventListener('storage',updateCartCount);
</script>
