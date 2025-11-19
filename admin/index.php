<?php
/**
 * Dashboard Admin - VisuPrint Pro
 */

require_once __DIR__ . '/auth.php';

verifierAdminConnecte();
$admin = getAdminInfo();
$db = Database::getInstance();

$pageTitle = 'Tableau de bord';

// Statistiques
$stats = [
    'commandes_jour' => $db->fetchOne("SELECT COUNT(*) as count FROM commandes WHERE DATE(created_at) = CURDATE()")['count'] ?? 0,
    'commandes_mois' => $db->fetchOne("SELECT COUNT(*) as count FROM commandes WHERE MONTH(created_at) = MONTH(CURDATE())")['count'] ?? 0,
    'ca_jour' => $db->fetchOne("SELECT COALESCE(SUM(total_ttc), 0) as total FROM commandes WHERE DATE(created_at) = CURDATE() AND statut_paiement = 'paye'")['total'] ?? 0,
    'ca_mois' => $db->fetchOne("SELECT COALESCE(SUM(total_ttc), 0) as total FROM commandes WHERE MONTH(created_at) = MONTH(CURDATE()) AND statut_paiement = 'paye'")['total'] ?? 0,
    'commandes_attente' => $db->fetchOne("SELECT COUNT(*) as count FROM commandes WHERE statut = 'nouveau'")['count'] ?? 0,
    'commandes_production' => $db->fetchOne("SELECT COUNT(*) as count FROM commandes WHERE statut = 'en_production'")['count'] ?? 0,
    'total_produits' => $db->fetchOne("SELECT COUNT(*) as count FROM produits")['count'] ?? 0,
    'total_clients' => $db->fetchOne("SELECT COUNT(*) as count FROM clients")['count'] ?? 0,
];

// Dernières commandes
$dernieresCommandes = $db->fetchAll(
    "SELECT * FROM commandes ORDER BY created_at DESC LIMIT 10"
) ?? [];

include __DIR__ . '/includes/header.php';
?>

<div class="top-bar">
    <div>
        <h1 class="page-title">📊 Tableau de bord</h1>
        <p class="page-subtitle">Bienvenue, <?php echo htmlspecialchars($admin['prenom'] ?? $admin['username']); ?> !</p>
    </div>
    <div class="top-bar-actions">
        <a href="/admin/nouvelle-commande.php" class="btn btn-primary">
            ➕ Nouvelle commande
        </a>
    </div>
</div>

<!-- Statistiques principales -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Commandes aujourd'hui</div>
        <div style="font-size: 36px; font-weight: 700;"><?php echo $stats['commandes_jour']; ?></div>
        <div style="font-size: 12px; opacity: 0.8; margin-top: 8px;">📦 Nouvelles commandes</div>
    </div>

    <div class="card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border: none;">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Commandes ce mois</div>
        <div style="font-size: 36px; font-weight: 700;"><?php echo $stats['commandes_mois']; ?></div>
        <div style="font-size: 12px; opacity: 0.8; margin-top: 8px;">📅 Total mensuel</div>
    </div>

    <div class="card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border: none;">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">CA aujourd'hui</div>
        <div style="font-size: 36px; font-weight: 700;"><?php echo number_format($stats['ca_jour'], 0, ',', ' '); ?> €</div>
        <div style="font-size: 12px; opacity: 0.8; margin-top: 8px;">💰 Chiffre d'affaires</div>
    </div>

    <div class="card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; border: none;">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">CA ce mois</div>
        <div style="font-size: 36px; font-weight: 700;"><?php echo number_format($stats['ca_mois'], 0, ',', ' '); ?> €</div>
        <div style="font-size: 12px; opacity: 0.8; margin-top: 8px;">💵 Total mensuel</div>
    </div>

    <div class="card">
        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 8px;">En attente</div>
        <div style="font-size: 36px; font-weight: 700; color: var(--warning);"><?php echo $stats['commandes_attente']; ?></div>
        <div style="font-size: 12px; color: var(--text-muted); margin-top: 8px;">⏳ À traiter</div>
    </div>

    <div class="card">
        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 8px;">En production</div>
        <div style="font-size: 36px; font-weight: 700; color: var(--info);"><?php echo $stats['commandes_production']; ?></div>
        <div style="font-size: 12px; color: var(--text-muted); margin-top: 8px;">🏭 En cours</div>
    </div>

    <div class="card">
        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 8px;">Produits</div>
        <div style="font-size: 36px; font-weight: 700; color: var(--primary);"><?php echo $stats['total_produits']; ?></div>
        <div style="font-size: 12px; color: var(--text-muted); margin-top: 8px;">🏷️ Catalogue</div>
    </div>

    <div class="card">
        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 8px;">Clients</div>
        <div style="font-size: 36px; font-weight: 700; color: var(--secondary);"><?php echo $stats['total_clients']; ?></div>
        <div style="font-size: 12px; color: var(--text-muted); margin-top: 8px;">👥 Base clients</div>
    </div>
</div>

<!-- Dernières commandes -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">📋 Dernières commandes</h2>
    </div>

    <?php if (empty($dernieresCommandes)): ?>
        <div style="text-align: center; padding: 60px 20px; color: var(--text-muted);">
            <div style="font-size: 48px; margin-bottom: 16px;">📦</div>
            <div style="font-size: 18px; margin-bottom: 8px; color: var(--text-secondary);">Aucune commande pour le moment</div>
            <div style="font-size: 14px;">Les nouvelles commandes apparaîtront ici</div>
        </div>
    <?php else: ?>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>N° Commande</th>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Paiement</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dernieresCommandes as $cmd): ?>
                        <tr>
                            <td><strong style="color: var(--primary);"><?php echo htmlspecialchars($cmd['numero_commande']); ?></strong></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($cmd['created_at'])); ?></td>
                            <td><?php echo htmlspecialchars($cmd['client_prenom'] . ' ' . $cmd['client_nom']); ?></td>
                            <td><strong><?php echo number_format($cmd['total_ttc'], 2, ',', ' '); ?> €</strong></td>
                            <td>
                                <?php
                                $statutBadges = [
                                    'nouveau' => 'info',
                                    'confirme' => 'success',
                                    'en_production' => 'warning',
                                    'expedie' => 'success',
                                    'livre' => 'success',
                                    'annule' => 'danger'
                                ];
                                $badgeClass = $statutBadges[$cmd['statut']] ?? 'info';
                                ?>
                                <span class="badge badge-<?php echo $badgeClass; ?>"><?php echo ucfirst(str_replace('_', ' ', $cmd['statut'])); ?></span>
                            </td>
                            <td>
                                <?php
                                $paiementBadges = [
                                    'paye' => 'success',
                                    'en_attente' => 'warning',
                                    'refuse' => 'danger'
                                ];
                                $badgeClass = $paiementBadges[$cmd['statut_paiement']] ?? 'warning';
                                ?>
                                <span class="badge badge-<?php echo $badgeClass; ?>"><?php echo ucfirst(str_replace('_', ' ', $cmd['statut_paiement'])); ?></span>
                            </td>
                            <td>
                                <a href="commande.php?id=<?php echo $cmd['id']; ?>" class="btn btn-primary btn-sm">Voir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div style="margin-top: 20px; text-align: center;">
        <a href="/admin/commandes.php" class="btn btn-secondary">Voir toutes les commandes →</a>
    </div>
</div>

<!-- Actions rapides -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
    <div class="card" style="border-left: 4px solid var(--primary);">
        <h3 style="font-size: 18px; margin-bottom: 12px; color: var(--primary);">🎨 Gestion des produits</h3>
        <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 16px;">
            Gérez votre catalogue de produits et finitions
        </p>
        <div style="display: flex; gap: 10px;">
            <a href="/admin/produits.php" class="btn btn-secondary btn-sm">Produits</a>
            <a href="/admin/finitions-catalogue.php" class="btn btn-secondary btn-sm">Finitions</a>
        </div>
    </div>

    <div class="card" style="border-left: 4px solid var(--success);">
        <h3 style="font-size: 18px; margin-bottom: 12px; color: var(--success);">🛍️ Nouvelle commande</h3>
        <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 16px;">
            Créer une commande manuelle pour un client
        </p>
        <a href="/admin/nouvelle-commande.php" class="btn btn-success btn-sm">Créer une commande</a>
    </div>

    <div class="card" style="border-left: 4px solid var(--warning);">
        <h3 style="font-size: 18px; margin-bottom: 12px; color: var(--warning);">🔨 Outils</h3>
        <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 16px;">
            Générer les pages HTML des produits
        </p>
        <a href="/admin/generer-pages-produits-html.php" class="btn btn-warning btn-sm">Générer les pages</a>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
