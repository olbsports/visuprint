<?php
/**
 * Gestion Clients - Imprixo Admin
 */

require_once __DIR__ . '/auth.php';

verifierAdminConnecte();
$admin = getAdminInfo();
$db = Database::getInstance();

$pageTitle = 'Gestion des clients';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 30;
$offset = ($page - 1) * $perPage;

// Filtres
$filtreRecherche = isset($_GET['search']) ? cleanInput($_GET['search']) : '';

// Construire la requête
$where = [];
$params = [];

if ($filtreRecherche) {
    $where[] = '(email LIKE ? OR nom LIKE ? OR prenom LIKE ? OR telephone LIKE ?)';
    $search = "%$filtreRecherche%";
    $params[] = $search;
    $params[] = $search;
    $params[] = $search;
    $params[] = $search;
}

$whereClause = empty($where) ? '1=1' : implode(' AND ', $where);

// Compter le total
$total = $db->fetchOne(
    "SELECT COUNT(*) as count FROM clients WHERE $whereClause",
    $params
)['count'] ?? 0;

$totalPages = ceil($total / $perPage);

// Récupérer les clients
$clients = $db->fetchAll(
    "SELECT * FROM clients
    WHERE $whereClause
    ORDER BY created_at DESC
    LIMIT $perPage OFFSET $offset",
    $params
) ?? [];

// Stats
$stats = [
    'total' => $db->fetchOne("SELECT COUNT(*) as count FROM clients")['count'] ?? 0,
    'avec_commandes' => $db->fetchOne("SELECT COUNT(DISTINCT client_id) as count FROM commandes")['count'] ?? 0,
    'ca_total' => $db->fetchOne("SELECT COALESCE(SUM(total_ttc), 0) as total FROM commandes WHERE statut_paiement = 'paye'")['total'] ?? 0,
];

include __DIR__ . '/includes/header.php';
?>

<div class="top-bar">
    <div>
        <h1 class="page-title">👥 Gestion des clients</h1>
        <p class="page-subtitle"><?php echo $total; ?> client(s) trouvé(s)</p>
    </div>
    <div class="top-bar-actions">
        <a href="/admin/nouveau-client.php" class="btn btn-success">
            ➕ Nouveau client
        </a>
    </div>
</div>

<!-- Stats rapides -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 24px;">
    <div class="card" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; border: none;">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Total clients</div>
        <div style="font-size: 36px; font-weight: 700;"><?php echo $stats['total']; ?></div>
        <div style="font-size: 12px; opacity: 0.8; margin-top: 8px;">👥 Base clients</div>
    </div>

    <div class="card" style="background: linear-gradient(135deg, var(--secondary) 0%, #1a1b2e 100%); color: white; border: none;">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Clients actifs</div>
        <div style="font-size: 36px; font-weight: 700;"><?php echo $stats['avec_commandes']; ?></div>
        <div style="font-size: 12px; opacity: 0.8; margin-top: 8px;">🛍️ Ont commandé</div>
    </div>

    <div class="card" style="background: linear-gradient(135deg, var(--success) 0%, #059669 100%); color: white; border: none;">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">CA total</div>
        <div style="font-size: 36px; font-weight: 700;"><?php echo number_format($stats['ca_total'], 0, ',', ' '); ?> €</div>
        <div style="font-size: 12px; opacity: 0.8; margin-top: 8px;">💰 Chiffre d'affaires</div>
    </div>
</div>

<!-- Filtres -->
<div class="card">
    <form method="GET" action="" style="display: flex; gap: 16px; align-items: end;">
        <div class="form-group" style="flex: 1; margin: 0;">
            <label class="form-label">🔍 Rechercher un client</label>
            <input
                type="text"
                name="search"
                class="form-input"
                placeholder="Email, nom, prénom, téléphone..."
                value="<?php echo htmlspecialchars($filtreRecherche); ?>"
            >
        </div>

        <button type="submit" class="btn btn-primary">Rechercher</button>
        <?php if ($filtreRecherche): ?>
            <a href="/admin/clients.php" class="btn btn-secondary">Réinitialiser</a>
        <?php endif; ?>
    </form>
</div>

<!-- Liste des clients -->
<?php if (empty($clients)): ?>
    <div class="card" style="text-align: center; padding: 60px 20px;">
        <div style="font-size: 64px; margin-bottom: 20px;">👥</div>
        <h2 style="font-size: 24px; margin-bottom: 12px;">Aucun client trouvé</h2>
        <p style="color: var(--text-secondary); margin-bottom: 24px;">
            <?php if ($filtreRecherche): ?>
                Aucun client ne correspond à votre recherche.
            <?php else: ?>
                Vous n'avez pas encore de clients enregistrés.
            <?php endif; ?>
        </p>
        <a href="/admin/nouveau-client.php" class="btn btn-primary">➕ Ajouter un client</a>
    </div>
<?php else: ?>
    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Adresse</th>
                        <th>Inscrit le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clients as $client): ?>
                        <tr>
                            <td>
                                <strong style="color: var(--text-primary);">
                                    <?php echo htmlspecialchars($client['prenom'] . ' ' . $client['nom']); ?>
                                </strong>
                                <?php if (!empty($client['societe'])): ?>
                                    <br><small style="color: var(--text-muted);">🏢 <?php echo htmlspecialchars($client['societe']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($client['email']); ?></td>
                            <td><?php echo htmlspecialchars($client['telephone'] ?? '-'); ?></td>
                            <td>
                                <?php if (!empty($client['adresse'])): ?>
                                    <?php echo htmlspecialchars($client['adresse']); ?><br>
                                    <small style="color: var(--text-muted);">
                                        <?php echo htmlspecialchars($client['code_postal'] . ' ' . $client['ville']); ?>
                                    </small>
                                <?php else: ?>
                                    <span style="color: var(--text-muted);">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($client['created_at'])); ?></td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="/admin/client.php?id=<?php echo $client['id']; ?>" class="btn btn-primary btn-sm">
                                        👁️ Voir
                                    </a>
                                </div>
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
                    <a href="?page=<?php echo $page - 1; ?><?php echo $filtreRecherche ? '&search=' . urlencode($filtreRecherche) : ''; ?>" class="btn btn-secondary btn-sm">
                        ← Précédent
                    </a>
                <?php endif; ?>

                <span style="color: var(--text-secondary);">
                    Page <?php echo $page; ?> sur <?php echo $totalPages; ?>
                </span>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?><?php echo $filtreRecherche ? '&search=' . urlencode($filtreRecherche) : ''; ?>" class="btn btn-secondary btn-sm">
                        Suivant →
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
