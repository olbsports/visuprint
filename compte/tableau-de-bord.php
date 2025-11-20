<?php
$pageTitle = 'Mon Tableau de Bord | Imprixo';
$pageDescription = 'Gérez vos commandes, devis, fichiers et paramètres depuis votre espace client Imprixo';
include __DIR__ . '/../includes/header.php';
?>

<div class="top-bar">
        <div class="container">
            <h1>👋 Bonjour, Jean Dupont</h1>
            <p>Bienvenue dans votre espace client Imprixo</p>
        </div>
    </div>

    <div class="stats">
        <div class="stat-card">
            <div class="stat-label">Commandes</div>
            <div class="stat-value">12</div>
            <p style="color:#06d6a0;font-size:0.9rem">↗ +3 ce mois</p>
        </div>
        <div class="stat-card">
            <div class="stat-label">Devis en cours</div>
            <div class="stat-value">3</div>
            <p style="color:#ffd166;font-size:0.9rem">En attente validation</p>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total dépensé</div>
            <div class="stat-value">2 845€</div>
            <p style="color:#6c757d;font-size:0.9rem">Depuis 2024</p>
        </div>
        <div class="stat-card">
            <div class="stat-label">Points fidélité</div>
            <div class="stat-value">1 240</div>
            <p style="color:#06d6a0;font-size:0.9rem">= 62€ de réduction</p>
        </div>
    </div>

    <div class="content">
        <div class="section">
            <div class="section-title">📦 Commandes récentes</div>
            <ul class="order-list">
                <li class="order-item">
                    <div>
                        <strong>#CMD-2024-001234</strong><br>
                        <small style="color:#6c757d">Dibond 3mm · 12m² · 15/11/2024</small>
                    </div>
                    <div>
                        <span class="badge badge-success">Livrée</span>
                    </div>
                </li>
                <li class="order-item">
                    <div>
                        <strong>#CMD-2024-001225</strong><br>
                        <small style="color:#6c757d">Bâche PVC · 24m² · 12/11/2024</small>
                    </div>
                    <div>
                        <span class="badge badge-warning">En production</span>
                    </div>
                </li>
            </ul>
            <a href="/compte/mes-commandes.html" class="btn">Voir toutes mes commandes →</a>
        </div>

        <div class="section">
            <div class="section-title">💰 Actions rapides</div>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:15px">
                <a href="/devis-express.html" class="btn">📝 Nouveau devis</a>
                <a href="/configurateur.html" class="btn">🎨 Configurer un produit</a>
                <a href="/compte/mes-fichiers.html" class="btn">📁 Mes fichiers</a>
                <a href="/compte/parametres.html" class="btn">⚙️ Paramètres</a>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
