<?php
/**
 * Détail Commande Client - Imprixo
 * Upload fichiers + téléchargement specs techniques
 */

require_once __DIR__ . '/auth-client.php';

verifierClientConnecte();
$client = getClientInfo();
$db = Database::getInstance();

$commandeId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$commandeId) {
    header('Location: /mon-compte.php');
    exit;
}

// Récupérer la commande (vérifier qu'elle appartient au client)
$commande = $db->fetchOne(
    "SELECT * FROM commandes WHERE id = ? AND client_id = ?",
    [$commandeId, $client['id']]
);

if (!$commande) {
    header('Location: /mon-compte.php');
    exit;
}

// Récupérer les lignes de commande
$lignes = $db->fetchAll(
    "SELECT * FROM lignes_commande WHERE commande_id = ?",
    [$commandeId]
);

// Traitement upload fichier
$uploadSuccess = false;
$uploadError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fichier'])) {
    $ligneId = (int)$_POST['ligne_id'];
    $file = $_FILES['fichier'];

    // Vérifier que la ligne appartient à cette commande
    $ligneExists = false;
    foreach ($lignes as $l) {
        if ($l['id'] == $ligneId) {
            $ligneExists = true;
            break;
        }
    }

    if (!$ligneExists) {
        $uploadError = 'Ligne de commande invalide';
    } elseif ($file['error'] !== UPLOAD_ERR_OK) {
        $uploadError = 'Erreur lors de l\'upload';
    } else {
        // Extensions autorisées
        $allowedExts = ['pdf', 'ai', 'eps', 'psd', 'jpg', 'jpeg', 'png', 'tif', 'tiff'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExts)) {
            $uploadError = 'Format de fichier non autorisé. Formats acceptés: ' . implode(', ', $allowedExts);
        } elseif ($file['size'] > 500 * 1024 * 1024) { // 500 MB max
            $uploadError = 'Fichier trop volumineux (max 500 MB)';
        } else {
            // Créer le dossier uploads s'il n'existe pas
            $uploadsDir = __DIR__ . '/uploads/commandes/' . $commande['numero_commande'];
            if (!is_dir($uploadsDir)) {
                mkdir($uploadsDir, 0755, true);
            }

            // Nom du fichier sécurisé
            $fileName = 'ligne_' . $ligneId . '_' . time() . '.' . $ext;
            $filePath = $uploadsDir . '/' . $fileName;

            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                // Enregistrer dans la base
                $db->query(
                    "UPDATE lignes_commande SET
                        fichier_client = ?,
                        fichier_client_original = ?,
                        fichier_upload_date = NOW()
                    WHERE id = ?",
                    [$fileName, $file['name'], $ligneId]
                );

                $uploadSuccess = true;

                // Recharger les lignes
                $lignes = $db->fetchAll(
                    "SELECT * FROM lignes_commande WHERE commande_id = ?",
                    [$commandeId]
                );
            } else {
                $uploadError = 'Erreur lors de la sauvegarde du fichier';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande <?php echo htmlspecialchars($commande['numero_commande']); ?> - Imprixo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap');
        * { font-family: 'Roboto', sans-serif; }

        .badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
        }

        .badge-nouveau { background: #3498db; color: white; }
        .badge-confirme { background: #9b59b6; color: white; }
        .badge-en_production { background: #f39c12; color: white; }
        .badge-expedie { background: #27ae60; color: white; }
        .badge-livre { background: #16a085; color: white; }
        .badge-paye { background: #27ae60; color: white; }
        .badge-en_attente { background: #95a5a6; color: white; }

        .upload-zone {
            border: 3px dashed #cbd5e0;
            transition: all 0.3s;
        }

        .upload-zone:hover {
            border-color: #e63946;
            background: #fff5f5;
        }

        .file-icon {
            font-size: 48px;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white border-b-2 border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/index.html"><span class="text-3xl font-black text-gray-900">Imprixo</span></a>
            <div class="flex items-center gap-6">
                <a href="/mon-compte.php" class="text-gray-700 hover:text-red-600 font-medium">← Mon compte</a>
                <a href="/logout-client.php" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-medium transition">
                    Déconnexion
                </a>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 py-12">
        <!-- Messages -->
        <?php if ($uploadSuccess): ?>
            <div class="bg-green-50 border-l-4 border-green-600 p-6 rounded-lg mb-6">
                <p class="text-green-900 font-bold">✓ Fichier envoyé avec succès !</p>
                <p class="text-green-800 text-sm mt-1">Votre fichier a été reçu et sera traité par notre équipe.</p>
            </div>
        <?php endif; ?>

        <?php if ($uploadError): ?>
            <div class="bg-red-50 border-l-4 border-red-600 p-6 rounded-lg mb-6">
                <p class="text-red-900 font-bold">✗ <?php echo htmlspecialchars($uploadError); ?></p>
            </div>
        <?php endif; ?>

        <!-- Titre et badges -->
        <div class="mb-8">
            <div class="flex items-center gap-4 mb-3">
                <h1 class="text-4xl font-black text-gray-900">Commande <?php echo htmlspecialchars($commande['numero_commande']); ?></h1>
                <span class="badge badge-<?php echo $commande['statut']; ?>">
                    <?php echo ucfirst(str_replace('_', ' ', $commande['statut'])); ?>
                </span>
                <span class="badge badge-<?php echo $commande['statut_paiement']; ?>">
                    <?php echo ucfirst($commande['statut_paiement']); ?>
                </span>
            </div>
            <p class="text-gray-600">Passée le <?php echo date('d/m/Y à H:i', strtotime($commande['created_at'])); ?></p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Produits commandés -->
                <?php foreach ($lignes as $index => $ligne): ?>
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <h2 class="text-2xl font-black text-gray-900 mb-6">
                            📦 Produit <?php echo $index + 1; ?>: <?php echo htmlspecialchars($ligne['produit_nom']); ?>
                        </h2>

                        <!-- Configuration -->
                        <div class="grid grid-cols-2 gap-4 mb-6 bg-gray-50 rounded-lg p-6">
                            <div>
                                <div class="text-sm text-gray-600 font-semibold mb-1">Dimensions</div>
                                <div class="text-lg font-bold text-gray-900"><?php echo $ligne['largeur']; ?> × <?php echo $ligne['hauteur']; ?> cm</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600 font-semibold mb-1">Surface</div>
                                <div class="text-lg font-bold text-gray-900"><?php echo number_format($ligne['surface'], 2, ',', ''); ?> m²</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600 font-semibold mb-1">Quantité</div>
                                <div class="text-lg font-bold text-gray-900"><?php echo $ligne['quantite']; ?> exemplaire(s)</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600 font-semibold mb-1">Impression</div>
                                <div class="text-lg font-bold text-gray-900"><?php echo ucfirst($ligne['impression']); ?> face</div>
                            </div>
                        </div>

                        <!-- Télécharger specs techniques -->
                        <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6 mb-6">
                            <h3 class="font-bold text-blue-900 mb-3">📄 Spécifications Techniques</h3>
                            <p class="text-sm text-blue-800 mb-4">
                                Téléchargez le PDF avec toutes les spécifications techniques (dimensions finales, zones de sécurité, fonds perdus, résolution, profil colorimétrique).
                            </p>
                            <a href="/generer-specs.php?ligne_id=<?php echo $ligne['id']; ?>"
                               target="_blank"
                               class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-700 transition">
                                ⬇ Télécharger les specs PDF
                            </a>
                        </div>

                        <!-- Upload fichier -->
                        <div class="border-t-2 border-gray-200 pt-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">📤 Envoyer votre fichier</h3>

                            <?php if ($ligne['fichier_client']): ?>
                                <!-- Fichier déjà envoyé -->
                                <div class="bg-green-50 border-2 border-green-200 rounded-lg p-6">
                                    <div class="flex items-center gap-4">
                                        <div class="file-icon">✅</div>
                                        <div class="flex-1">
                                            <div class="font-bold text-green-900 text-lg">Fichier reçu</div>
                                            <div class="text-sm text-green-800"><?php echo htmlspecialchars($ligne['fichier_client_original']); ?></div>
                                            <div class="text-xs text-green-700 mt-1">
                                                Envoyé le <?php echo date('d/m/Y à H:i', strtotime($ligne['fichier_upload_date'])); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-sm text-green-800 mt-4">
                                        Vous pouvez envoyer un nouveau fichier pour remplacer celui-ci.
                                    </p>
                                </div>
                            <?php endif; ?>

                            <!-- Formulaire upload -->
                            <form method="POST" enctype="multipart/form-data" class="mt-4">
                                <input type="hidden" name="ligne_id" value="<?php echo $ligne['id']; ?>">

                                <div class="upload-zone rounded-lg p-8 text-center cursor-pointer" onclick="document.getElementById('fichier-<?php echo $ligne['id']; ?>').click()">
                                    <div class="file-icon mb-4">📁</div>
                                    <p class="text-lg font-bold text-gray-900 mb-2">Cliquez pour sélectionner un fichier</p>
                                    <p class="text-sm text-gray-600 mb-4">ou glissez-déposez votre fichier ici</p>
                                    <p class="text-xs text-gray-500">
                                        <strong>Formats acceptés:</strong> PDF, AI, EPS, PSD, JPG, PNG, TIF<br>
                                        <strong>Taille max:</strong> 500 MB<br>
                                        <strong>Résolution minimale:</strong> 150 DPI
                                    </p>
                                </div>

                                <input type="file"
                                       id="fichier-<?php echo $ligne['id']; ?>"
                                       name="fichier"
                                       accept=".pdf,.ai,.eps,.psd,.jpg,.jpeg,.png,.tif,.tiff"
                                       class="hidden"
                                       onchange="this.form.submit()">
                            </form>

                            <!-- Conseils -->
                            <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                                <p class="text-sm font-semibold text-yellow-900 mb-2">💡 Conseils pour votre fichier</p>
                                <ul class="text-sm text-yellow-800 space-y-1">
                                    <li>• Incluez 3mm de fonds perdus de chaque côté</li>
                                    <li>• Respectez les zones de sécurité (3mm depuis le bord)</li>
                                    <li>• Utilisez le profil CMJN (pas RVB)</li>
                                    <li>• Vectorisez les textes ou incorporez les polices</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Colonne latérale -->
            <div class="lg:col-span-1">
                <!-- Récapitulatif -->
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-4">
                    <h2 class="text-xl font-black text-gray-900 mb-6">💰 Récapitulatif</h2>

                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-gray-700">
                            <span>Total HT</span>
                            <span class="font-bold"><?php echo number_format($commande['total_ht'], 2, ',', ' '); ?> €</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>TVA (20%)</span>
                            <span class="font-bold"><?php echo number_format($commande['total_tva'], 2, ',', ' '); ?> €</span>
                        </div>
                        <div class="border-t-2 border-gray-200 pt-3 flex justify-between text-xl">
                            <span class="font-black text-gray-900">Total TTC</span>
                            <span class="font-black text-red-600"><?php echo number_format($commande['total_ttc'], 2, ',', ' '); ?> €</span>
                        </div>
                    </div>

                    <?php if ($commande['statut_paiement'] === 'paye'): ?>
                        <div class="bg-green-50 rounded-lg p-4 mb-6">
                            <p class="text-green-900 font-bold">✓ Paiement validé</p>
                            <p class="text-xs text-green-700 mt-1">Le <?php echo date('d/m/Y', strtotime($commande['date_paiement'])); ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Livraison -->
                    <?php if ($commande['transporteur']): ?>
                        <div class="border-t-2 border-gray-200 pt-6">
                            <h3 class="font-bold text-gray-900 mb-3">🚚 Livraison</h3>
                            <div class="text-sm space-y-2">
                                <div>
                                    <span class="text-gray-600">Transporteur:</span>
                                    <span class="font-bold text-gray-900"><?php echo htmlspecialchars($commande['transporteur']); ?></span>
                                </div>
                                <div>
                                    <span class="text-gray-600">N° de suivi:</span>
                                    <span class="font-bold text-gray-900"><?php echo htmlspecialchars($commande['numero_suivi']); ?></span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Expédié le:</span>
                                    <span class="font-bold text-gray-900"><?php echo date('d/m/Y', strtotime($commande['date_expedition'])); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Adresse de livraison -->
                    <div class="border-t-2 border-gray-200 pt-6 mt-6">
                        <h3 class="font-bold text-gray-900 mb-3">📍 Adresse de livraison</h3>
                        <div class="text-sm text-gray-700">
                            <?php echo nl2br(htmlspecialchars($commande['adresse_livraison'])); ?><br>
                            <?php echo htmlspecialchars($commande['code_postal_livraison'] . ' ' . $commande['ville_livraison']); ?><br>
                            <?php echo htmlspecialchars($commande['pays_livraison']); ?>
                        </div>
                    </div>
                </div>
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
