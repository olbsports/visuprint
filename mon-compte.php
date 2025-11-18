<?php
/**
 * Mon Compte - Espace Client Imprixo
 */

require_once __DIR__ . '/auth-client.php';

verifierClientConnecte();
$client = getClientInfo();
$db = Database::getInstance();

// Récupérer les commandes du client
$commandes = $db->fetchAll(
    "SELECT * FROM commandes
    WHERE client_id = ?
    ORDER BY created_at DESC",
    [$client['id']]
);

// Statistiques du client
$stats = $db->fetchOne(
    "SELECT
        COUNT(*) as nb_commandes,
        COALESCE(SUM(CASE WHEN statut_paiement = 'paye' THEN total_ttc ELSE 0 END), 0) as ca_total,
        COALESCE(AVG(CASE WHEN statut_paiement = 'paye' THEN total_ttc ELSE NULL END), 0) as panier_moyen
    FROM commandes
    WHERE client_id = ?",
    [$client['id']]
);

$welcome = isset($_GET['welcome']) ? true : false;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte - Imprixo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap');
        * { font-family: 'Roboto', sans-serif; }

        .stat-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-nouveau { background: #3498db; color: white; }
        .badge-confirme { background: #9b59b6; color: white; }
        .badge-en_production { background: #f39c12; color: white; }
        .badge-expedie { background: #27ae60; color: white; }
        .badge-livre { background: #16a085; color: white; }
        .badge-annule { background: #e74c3c; color: white; }

        .badge-paye { background: #27ae60; color: white; }
        .badge-en_attente { background: #95a5a6; color: white; }
        .badge-echoue { background: #e74c3c; color: white; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white border-b-2 border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/index.html"><span class="text-3xl font-black text-gray-900">Imprixo</span></a>
            <div class="flex items-center gap-6">
                <a href="/catalogue.html" class="text-gray-700 hover:text-red-600 font-medium">Catalogue</a>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($client['prenom'] . ' ' . $client['nom']); ?></div>
                        <div class="text-xs text-gray-500"><?php echo htmlspecialchars($client['email']); ?></div>
                    </div>
                    <a href="/logout-client.php" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-medium transition">
                        Déconnexion
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 py-12">
        <!-- Welcome message -->
        <?php if ($welcome): ?>
            <div class="bg-green-50 border-l-4 border-green-600 p-6 rounded-lg mb-8">
                <h2 class="text-xl font-bold text-green-900 mb-2">🎉 Bienvenue sur Imprixo !</h2>
                <p class="text-green-800">Votre compte a été créé avec succès. Vous pouvez maintenant passer commande et suivre vos impressions.</p>
            </div>
        <?php endif; ?>

        <!-- Titre et avatar -->
        <div class="flex items-center gap-6 mb-8">
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center text-white text-3xl font-black">
                <?php echo strtoupper(substr($client['prenom'], 0, 1) . substr($client['nom'], 0, 1)); ?>
            </div>
            <div>
                <h1 class="text-4xl font-black text-gray-900">Mon Espace Client</h1>
                <p class="text-gray-600 mt-1">Gérez vos commandes et téléchargez vos fichiers</p>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="stat-card rounded-xl p-6 border-2 border-gray-200">
                <div class="text-sm text-gray-600 font-semibold mb-2">📦 COMMANDES TOTALES</div>
                <div class="text-4xl font-black text-gray-900"><?php echo $stats['nb_commandes']; ?></div>
            </div>

            <div class="stat-card rounded-xl p-6 border-2 border-gray-200">
                <div class="text-sm text-gray-600 font-semibold mb-2">💰 CA TOTAL</div>
                <div class="text-4xl font-black text-green-600"><?php echo number_format($stats['ca_total'], 2, ',', ' '); ?> €</div>
            </div>

            <div class="stat-card rounded-xl p-6 border-2 border-gray-200">
                <div class="text-sm text-gray-600 font-semibold mb-2">📊 PANIER MOYEN</div>
                <div class="text-4xl font-black text-blue-600"><?php echo number_format($stats['panier_moyen'], 2, ',', ' '); ?> €</div>
            </div>
        </div>

        <!-- Liste des commandes -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gray-50 border-b-2 border-gray-200 px-8 py-6 flex justify-between items-center">
                <h2 class="text-2xl font-black text-gray-900">📋 Mes Commandes</h2>
                <a href="/catalogue.html" class="bg-red-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-red-700 transition">
                    + Nouvelle commande
                </a>
            </div>

            <?php if (empty($commandes)): ?>
                <div class="text-center py-20">
                    <div class="text-6xl mb-4">📦</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucune commande</h3>
                    <p class="text-gray-600 mb-6">Vous n'avez pas encore passé de commande</p>
                    <a href="/catalogue.html" class="inline-block bg-red-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-red-700 transition">
                        Découvrir notre catalogue
                    </a>
                </div>
            <?php else: ?>
                <div class="divide-y divide-gray-200">
                    <?php foreach ($commandes as $cmd): ?>
                        <div class="px-8 py-6 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-4 mb-2">
                                        <h3 class="text-xl font-bold text-gray-900"><?php echo htmlspecialchars($cmd['numero_commande']); ?></h3>
                                        <span class="badge badge-<?php echo $cmd['statut']; ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $cmd['statut'])); ?>
                                        </span>
                                        <span class="badge badge-<?php echo $cmd['statut_paiement']; ?>">
                                            Paiement: <?php echo ucfirst($cmd['statut_paiement']); ?>
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-6 text-sm text-gray-600">
                                        <span>📅 <?php echo date('d/m/Y à H:i', strtotime($cmd['created_at'])); ?></span>
                                        <span class="font-bold text-gray-900">💰 <?php echo number_format($cmd['total_ttc'], 2, ',', ' '); ?> € TTC</span>
                                        <?php if ($cmd['date_expedition']): ?>
                                            <span>🚚 Expédié le <?php echo date('d/m/Y', strtotime($cmd['date_expedition'])); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <a href="/ma-commande.php?id=<?php echo $cmd['id']; ?>" class="bg-red-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-red-700 transition">
                                    Voir le détail →
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Informations du compte -->
        <div class="mt-8 bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-black text-gray-900 mb-6">👤 Mes Informations</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-semibold text-gray-600">Nom complet</label>
                    <div class="text-lg font-bold text-gray-900"><?php echo htmlspecialchars($client['prenom'] . ' ' . $client['nom']); ?></div>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-600">Email</label>
                    <div class="text-lg font-bold text-gray-900"><?php echo htmlspecialchars($client['email']); ?></div>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-600">Téléphone</label>
                    <div class="text-lg font-bold text-gray-900"><?php echo htmlspecialchars($client['telephone'] ?? 'Non renseigné'); ?></div>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-600">Client depuis</label>
                    <div class="text-lg font-bold text-gray-900"><?php echo date('d/m/Y', strtotime($client['created_at'])); ?></div>
                </div>

                <?php if ($client['adresse_facturation']): ?>
                    <div class="md:col-span-2">
                        <label class="text-sm font-semibold text-gray-600">Adresse de facturation</label>
                        <div class="text-lg text-gray-900">
                            <?php echo nl2br(htmlspecialchars($client['adresse_facturation'])); ?><br>
                            <?php echo htmlspecialchars($client['code_postal_facturation'] . ' ' . $client['ville_facturation']); ?><br>
                            <?php echo htmlspecialchars($client['pays_facturation'] ?? 'France'); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer class="bg-gray-900 text-gray-300 py-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 text-center text-sm">
            © 2025 Imprixo - Tous droits réservés •
            <a href="/cgv.html" class="hover:text-white ml-2">CGV</a> •
            <a href="/mentions-legales.html" class="hover:text-white ml-2">Mentions légales</a> •
            <a href="/politique-confidentialite.html" class="hover:text-white ml-2">Confidentialité</a>
        </div>
    </footer>
</body>
</html>
