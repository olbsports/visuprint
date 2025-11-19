<?php
/**
 * Liste Commandes - Imprixo Admin
 * Page complète de gestion des commandes avec filtres et actions
 */

require_once __DIR__ . '/auth.php';

verifierAdminConnecte();
$admin = getAdminInfo();
$db = Database::getInstance();

$pageTitle = 'Gestion des commandes';

// Paramètres de pagination et filtres
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 25;
$offset = ($page - 1) * $perPage;

// Filtres
$filtreStatut = isset($_GET['statut']) ? cleanInput($_GET['statut']) : '';
$filtrePaiement = isset($_GET['paiement']) ? cleanInput($_GET['paiement']) : '';
$filtreRecherche = isset($_GET['search']) ? cleanInput($_GET['search']) : '';
$filtreDateDebut = isset($_GET['date_debut']) ? cleanInput($_GET['date_debut']) : '';
$filtreDateFin = isset($_GET['date_fin']) ? cleanInput($_GET['date_fin']) : '';

// Construire la requête
$where = ['1=1'];
$params = [];

if ($filtreStatut) {
    $where[] = 'statut = ?';
    $params[] = $filtreStatut;
}

if ($filtrePaiement) {
    $where[] = 'statut_paiement = ?';
    $params[] = $filtrePaiement;
}

if ($filtreRecherche) {
    $where[] = '(numero_commande LIKE ? OR client_email LIKE ? OR client_nom LIKE ? OR client_prenom LIKE ?)';
    $search = "%$filtreRecherche%";
    $params[] = $search;
    $params[] = $search;
    $params[] = $search;
    $params[] = $search;
}

if ($filtreDateDebut) {
    $where[] = 'DATE(created_at) >= ?';
    $params[] = $filtreDateDebut;
}

if ($filtreDateFin) {
    $where[] = 'DATE(created_at) <= ?';
    $params[] = $filtreDateFin;
}

$whereClause = implode(' AND ', $where);

// Compter le total
$total = $db->fetchOne(
    "SELECT COUNT(*) as count FROM commandes WHERE $whereClause",
    $params
)['count'] ?? 0;

$totalPages = ceil($total / $perPage);

// Récupérer les commandes
$commandes = $db->fetchAll(
    "SELECT * FROM commandes
    WHERE $whereClause
    ORDER BY created_at DESC
    LIMIT $perPage OFFSET $offset",
    $params
) ?? [];

// Statistiques rapides
$stats = [
    'total' => $db->fetchOne("SELECT COUNT(*) as count FROM commandes")['count'] ?? 0,
    'nouveau' => $db->fetchOne("SELECT COUNT(*) as count FROM commandes WHERE statut = 'nouveau'")['count'] ?? 0,
    'en_production' => $db->fetchOne("SELECT COUNT(*) as count FROM commandes WHERE statut = 'en_production'")['count'] ?? 0,
    'expedie' => $db->fetchOne("SELECT COUNT(*) as count FROM commandes WHERE statut = 'expedie'")['count'] ?? 0,
    'attente_paiement' => $db->fetchOne("SELECT COUNT(*) as count FROM commandes WHERE statut_paiement = 'en_attente'")['count'] ?? 0,
];

include __DIR__ . '/includes/header.php';
?>

<div class="top-bar">
    <div>
        <h1 class="page-title">🛍️ Gestion des commandes</h1>
        <p class="page-subtitle"><?php echo $total; ?> commande(s) trouvée(s)</p>
    </div>
    <div class="top-bar-actions">
        <a href="/admin/nouvelle-commande.php" class="btn btn-success">
            ➕ Nouvelle commande
        </a>
    </div>
</div>

<!-- Stats rapides -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="card" style="border-left: 4px solid var(--primary);">
        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Total</div>
        <div style="font-size: 28px; font-weight: 700; color: var(--primary);"><?php echo $stats['total']; ?></div>
    </div>
    <div class="card" style="border-left: 4px solid var(--info);">
        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Nouvelles</div>
        <div style="font-size: 28px; font-weight: 700; color: var(--info);"><?php echo $stats['nouveau']; ?></div>
    </div>
    <div class="card" style="border-left: 4px solid var(--warning);">
        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">En production</div>
        <div style="font-size: 28px; font-weight: 700; color: var(--warning);"><?php echo $stats['en_production']; ?></div>
    </div>
    <div class="card" style="border-left: 4px solid var(--success);">
        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Expédiées</div>
        <div style="font-size: 28px; font-weight: 700; color: var(--success);"><?php echo $stats['expedie']; ?></div>
    </div>
    <div class="card" style="border-left: 4px solid var(--danger);">
        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Attente paiement</div>
        <div style="font-size: 28px; font-weight: 700; color: var(--danger);"><?php echo $stats['attente_paiement']; ?></div>
    </div>
</div>

<!-- Filtres -->
<div class="card">
    <form method="GET" action="">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 16px;">
            <div class="form-group" style="margin: 0;">
                <label class="form-label">🔍 Rechercher</label>
                <input
                    type="text"
                    name="search"
                    class="form-input"
                    placeholder="N°, email, nom..."
                    value="<?php echo htmlspecialchars($filtreRecherche); ?>"
                >
            </div>

            <div class="form-group" style="margin: 0;">
                <label class="form-label">Statut</label>
                <select name="statut" class="form-select">
                    <option value="">Tous les statuts</option>
                    <option value="nouveau" <?php echo $filtreStatut === 'nouveau' ? 'selected' : ''; ?>>Nouveau</option>
                    <option value="confirme" <?php echo $filtreStatut === 'confirme' ? 'selected' : ''; ?>>Confirmé</option>
                    <option value="en_production" <?php echo $filtreStatut === 'en_production' ? 'selected' : ''; ?>>En production</option>
                    <option value="expedie" <?php echo $filtreStatut === 'expedie' ? 'selected' : ''; ?>>Expédié</option>
                    <option value="livre" <?php echo $filtreStatut === 'livre' ? 'selected' : ''; ?>>Livré</option>
                    <option value="annule" <?php echo $filtreStatut === 'annule' ? 'selected' : ''; ?>>Annulé</option>
                </select>
            </div>

            <div class="form-group" style="margin: 0;">
                <label class="form-label">Paiement</label>
                <select name="paiement" class="form-select">
                    <option value="">Tous</option>
                    <option value="paye" <?php echo $filtrePaiement === 'paye' ? 'selected' : ''; ?>>Payé</option>
                    <option value="en_attente" <?php echo $filtrePaiement === 'en_attente' ? 'selected' : ''; ?>>En attente</option>
                    <option value="refuse" <?php echo $filtrePaiement === 'refuse' ? 'selected' : ''; ?>>Refusé</option>
                </select>
            </div>

            <div class="form-group" style="margin: 0;">
                <label class="form-label">Date début</label>
                <input
                    type="date"
                    name="date_debut"
                    class="form-input"
                    value="<?php echo htmlspecialchars($filtreDateDebut); ?>"
                >
            </div>

            <div class="form-group" style="margin: 0;">
                <label class="form-label">Date fin</label>
                <input
                    type="date"
                    name="date_fin"
                    class="form-input"
                    value="<?php echo htmlspecialchars($filtreDateFin); ?>"
                >
            </div>
        </div>

        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary">Filtrer</button>
            <?php if ($filtreRecherche || $filtreStatut || $filtrePaiement || $filtreDateDebut || $filtreDateFin): ?>
                <a href="/admin/commandes.php" class="btn btn-secondary">Réinitialiser</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Liste des commandes -->
<?php if (empty($commandes)): ?>
    <div class="card" style="text-align: center; padding: 60px 20px;">
        <div style="font-size: 64px; margin-bottom: 20px;">🛍️</div>
        <h2 style="font-size: 24px; margin-bottom: 12px;">Aucune commande trouvée</h2>
        <p style="color: var(--text-secondary); margin-bottom: 24px;">
            <?php if ($filtreRecherche || $filtreStatut || $filtrePaiement): ?>
                Aucune commande ne correspond à vos filtres.
            <?php else: ?>
                Vous n'avez pas encore de commandes.
            <?php endif; ?>
        </p>
        <a href="/admin/nouvelle-commande.php" class="btn btn-primary">➕ Créer une commande</a>
    </div>
<?php else: ?>
    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>N° Commande</th>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Montant TTC</th>
                        <th>Statut</th>
                        <th>Paiement</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commandes as $cmd): ?>
                        <tr>
                            <td>
                                <strong style="color: var(--primary);">
                                    <?php echo htmlspecialchars($cmd['numero_commande']); ?>
                                </strong>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($cmd['created_at'])); ?></td>
                            <td>
                                <?php echo htmlspecialchars($cmd['client_prenom'] . ' ' . $cmd['client_nom']); ?><br>
                                <small style="color: var(--text-muted);"><?php echo htmlspecialchars($cmd['client_email']); ?></small>
                            </td>
                            <td><strong><?php echo number_format($cmd['total_ttc'], 2, ',', ' '); ?> €</strong></td>
                            <td>
                                <?php
                                $statutBadges = [
                                    'nouveau' => 'info',
                                    'confirme' => 'info',
                                    'en_production' => 'warning',
                                    'expedie' => 'success',
                                    'livre' => 'success',
                                    'annule' => 'danger'
                                ];
                                $badgeClass = $statutBadges[$cmd['statut']] ?? 'info';
                                ?>
                                <span class="badge badge-<?php echo $badgeClass; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $cmd['statut'])); ?>
                                </span>
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
                                <span class="badge badge-<?php echo $badgeClass; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $cmd['statut_paiement'])); ?>
                                </span>
                            </td>
                            <td>
                                <a href="/admin/commande.php?id=<?php echo $cmd['id']; ?>" class="btn btn-primary btn-sm">
                                    👁️ Voir
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div style="margin-top: 24px; display: flex; justify-content: center; gap: 8px; align-items: center;">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?><?php echo $filtreStatut ? '&statut=' . urlencode($filtreStatut) : ''; ?><?php echo $filtrePaiement ? '&paiement=' . urlencode($filtrePaiement) : ''; ?><?php echo $filtreRecherche ? '&search=' . urlencode($filtreRecherche) : ''; ?>" class="btn btn-secondary btn-sm">
                        ← Précédent
                    </a>
                <?php endif; ?>

                <span style="color: var(--text-secondary);">
                    Page <?php echo $page; ?> sur <?php echo $totalPages; ?>
                </span>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?><?php echo $filtreStatut ? '&statut=' . urlencode($filtreStatut) : ''; ?><?php echo $filtrePaiement ? '&paiement=' . urlencode($filtrePaiement) : ''; ?><?php echo $filtreRecherche ? '&search=' . urlencode($filtreRecherche) : ''; ?>" class="btn btn-secondary btn-sm">
                        Suivant →
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
