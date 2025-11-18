<?php
/**
 * Générateur de Spécifications Techniques PDF
 * Imprixo - Normes professionnelles d'impression
 */

require_once __DIR__ . '/auth-client.php';

verifierClientConnecte();
$client = getClientInfo();
$db = Database::getInstance();

$ligneId = isset($_GET['ligne_id']) ? (int)$_GET['ligne_id'] : 0;

if (!$ligneId) {
    die('Ligne de commande non spécifiée');
}

// Récupérer la ligne de commande
$ligne = $db->fetchOne(
    "SELECT lc.*, c.numero_commande, c.client_id
    FROM lignes_commande lc
    JOIN commandes c ON c.id = lc.commande_id
    WHERE lc.id = ?",
    [$ligneId]
);

if (!$ligne || $ligne['client_id'] != $client['id']) {
    die('Ligne de commande introuvable');
}

// Calculs des dimensions
$largeur_cm = $ligne['largeur'];
$hauteur_cm = $ligne['hauteur'];

// Fonds perdus (3mm = 0.3cm de chaque côté)
$fond_perdu_cm = 0.3;
$largeur_avec_fond_perdu = $largeur_cm + (2 * $fond_perdu_cm);
$hauteur_avec_fond_perdu = $hauteur_cm + (2 * $fond_perdu_cm);

// Zone de sécurité (3mm = 0.3cm depuis le bord fini)
$zone_securite_cm = 0.3;
$largeur_zone_securite = $largeur_cm - (2 * $zone_securite_cm);
$hauteur_zone_securite = $hauteur_cm - (2 * $zone_securite_cm);

// Résolution recommandée
$resolution_min_dpi = 150;
$resolution_ideale_dpi = 300;

// Calculer pixels pour résolution idéale
$largeur_px_ideale = round(($largeur_avec_fond_perdu / 2.54) * $resolution_ideale_dpi);
$hauteur_px_ideale = round(($hauteur_avec_fond_perdu / 2.54) * $resolution_ideale_dpi);

// Header pour téléchargement PDF
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Spécifications Techniques - <?php echo htmlspecialchars($ligne['numero_commande']); ?></title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', Arial, sans-serif;
            line-height: 1.6;
            color: #2c3e50;
            padding: 40px;
            background: white;
        }

        .header {
            border-bottom: 5px solid #e63946;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .logo {
            font-size: 36px;
            font-weight: 900;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 20px;
            color: #e63946;
            font-weight: 700;
        }

        .commande-info {
            background: #f8f9fa;
            padding: 20px;
            border-left: 5px solid #3498db;
            margin-bottom: 30px;
        }

        .commande-info h2 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #3498db;
        }

        .section {
            margin-bottom: 35px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 22px;
            font-weight: 900;
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 3px solid #e63946;
        }

        .specs-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .spec-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #e63946;
        }

        .spec-label {
            font-size: 12px;
            color: #7f8c8d;
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .spec-value {
            font-size: 20px;
            font-weight: 700;
            color: #2c3e50;
        }

        .alert {
            background: #fff3cd;
            border-left: 5px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }

        .alert-title {
            font-weight: 700;
            color: #856404;
            margin-bottom: 8px;
        }

        .alert ul {
            margin-left: 20px;
            color: #856404;
        }

        .success-box {
            background: #d4edda;
            border-left: 5px solid #28a745;
            padding: 15px;
            margin: 20px 0;
        }

        .diagram {
            border: 2px solid #2c3e50;
            padding: 30px;
            text-align: center;
            margin: 20px 0;
            background: white;
            position: relative;
        }

        .diagram-inner {
            border: 3px dashed #e63946;
            padding: 25px;
            position: relative;
        }

        .diagram-core {
            border: 3px solid #27ae60;
            padding: 40px;
            background: #d4edda;
        }

        .dimension-label {
            font-size: 11px;
            font-weight: 700;
            color: #666;
            margin: 5px 0;
        }

        .legend {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .legend-color {
            width: 30px;
            height: 15px;
            margin-right: 10px;
            border-radius: 3px;
        }

        .checklist {
            background: #e8f5e9;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .checklist h3 {
            color: #27ae60;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .checklist ul {
            list-style: none;
            padding-left: 0;
        }

        .checklist li {
            padding: 8px 0;
            padding-left: 30px;
            position: relative;
        }

        .checklist li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #27ae60;
            font-weight: 700;
            font-size: 18px;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            text-align: center;
            color: #7f8c8d;
            font-size: 12px;
        }

        .btn-print {
            display: inline-block;
            background: #e63946;
            color: white;
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: 700;
            text-decoration: none;
            margin: 20px 0;
            cursor: pointer;
            border: none;
            font-size: 16px;
        }

        .btn-print:hover {
            background: #d62839;
        }

        @media print {
            body {
                padding: 20px;
            }
            .btn-print {
                display: none;
            }
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        table th {
            background: #2c3e50;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 700;
        }

        table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e0e0e0;
        }

        table tr:nth-child(even) {
            background: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">IMPRIXO</div>
        <div class="subtitle">Spécifications Techniques d'Impression</div>
    </div>

    <button onclick="window.print()" class="btn-print">🖨️ Imprimer / Enregistrer en PDF</button>

    <div class="commande-info">
        <h2>📦 Informations de commande</h2>
        <p><strong>Commande:</strong> <?php echo htmlspecialchars($ligne['numero_commande']); ?></p>
        <p><strong>Produit:</strong> <?php echo htmlspecialchars($ligne['produit_nom']); ?> (<?php echo htmlspecialchars($ligne['produit_code']); ?>)</p>
        <p><strong>Impression:</strong> <?php echo ucfirst($ligne['impression']); ?> face</p>
        <p><strong>Quantité:</strong> <?php echo $ligne['quantite']; ?> exemplaire(s)</p>
    </div>

    <!-- DIMENSIONS -->
    <div class="section">
        <h2 class="section-title">📏 DIMENSIONS</h2>

        <div class="specs-grid">
            <div class="spec-box">
                <div class="spec-label">Format Fini (après coupe)</div>
                <div class="spec-value"><?php echo $largeur_cm; ?> × <?php echo $hauteur_cm; ?> cm</div>
            </div>

            <div class="spec-box">
                <div class="spec-label">Surface totale</div>
                <div class="spec-value"><?php echo number_format($ligne['surface'], 2, ',', ''); ?> m²</div>
            </div>

            <div class="spec-box" style="border-left-color: #e63946;">
                <div class="spec-label">📐 Format avec fonds perdus</div>
                <div class="spec-value" style="color: #e63946;"><?php echo $largeur_avec_fond_perdu; ?> × <?php echo $hauteur_avec_fond_perdu; ?> cm</div>
            </div>

            <div class="spec-box" style="border-left-color: #27ae60;">
                <div class="spec-label">🛡️ Zone de sécurité</div>
                <div class="spec-value" style="color: #27ae60;"><?php echo $largeur_zone_securite; ?> × <?php echo $hauteur_zone_securite; ?> cm</div>
            </div>
        </div>

        <!-- Schéma visuel -->
        <div class="diagram">
            <div style="position: absolute; top: 5px; left: 50%; transform: translateX(-50%); font-size: 10px; font-weight: 700; color: #2c3e50;">
                <?php echo $largeur_avec_fond_perdu; ?> cm (avec fonds perdus)
            </div>

            <div style="position: absolute; left: 5px; top: 50%; transform: translateY(-50%) rotate(-90deg); font-size: 10px; font-weight: 700; color: #2c3e50;">
                <?php echo $hauteur_avec_fond_perdu; ?> cm
            </div>

            <div class="diagram-inner">
                <div style="position: absolute; top: 5px; left: 50%; transform: translateX(-50%); font-size: 9px; font-weight: 700; color: #e63946;">
                    <?php echo $largeur_cm; ?> cm (format fini)
                </div>

                <div class="diagram-core">
                    <div style="font-weight: 700; color: #27ae60; font-size: 14px;">ZONE DE SÉCURITÉ</div>
                    <div style="font-size: 11px; color: #27ae60; margin-top: 5px;">
                        <?php echo $largeur_zone_securite; ?> × <?php echo $hauteur_zone_securite; ?> cm
                    </div>
                    <div style="font-size: 10px; color: #666; margin-top: 10px;">
                        Placez tous les textes et<br>éléments importants ici
                    </div>
                </div>
            </div>
        </div>

        <div class="legend">
            <div class="legend-item">
                <div class="legend-color" style="background: #2c3e50;"></div>
                <span><strong>Zone noire:</strong> Format avec fonds perdus (à fournir)</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: none; border: 2px dashed #e63946;"></div>
                <span><strong>Ligne rouge pointillée:</strong> Ligne de coupe finale</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #d4edda;"></div>
                <span><strong>Zone verte:</strong> Zone de sécurité (textes et éléments importants)</span>
            </div>
        </div>
    </div>

    <!-- RÉSOLUTION ET QUALITÉ -->
    <div class="section">
        <h2 class="section-title">🎨 RÉSOLUTION ET QUALITÉ</h2>

        <table>
            <tr>
                <th>Paramètre</th>
                <th>Valeur Minimale</th>
                <th>Valeur Idéale</th>
            </tr>
            <tr>
                <td><strong>Résolution</strong></td>
                <td><?php echo $resolution_min_dpi; ?> DPI</td>
                <td style="background: #d4edda; font-weight: 700;"><?php echo $resolution_ideale_dpi; ?> DPI ⭐</td>
            </tr>
            <tr>
                <td><strong>Dimensions en pixels</strong></td>
                <td>-</td>
                <td style="background: #d4edda; font-weight: 700;"><?php echo $largeur_px_ideale; ?> × <?php echo $hauteur_px_ideale; ?> px</td>
            </tr>
            <tr>
                <td><strong>Profil colorimétrique</strong></td>
                <td colspan="2" style="font-weight: 700; color: #e63946;">CMJN (CMYK) - ISO Coated v2 300%</td>
            </tr>
            <tr>
                <td><strong>Formats acceptés</strong></td>
                <td colspan="2">PDF, AI, EPS, PSD, TIFF, JPG (haute qualité)</td>
            </tr>
        </table>

        <div class="alert">
            <div class="alert-title">⚠️ ATTENTION - Profil Couleur</div>
            <ul>
                <li>Utilisez OBLIGATOIREMENT le mode <strong>CMJN</strong> (pas RVB)</li>
                <li>Les fichiers en RVB seront automatiquement convertis, ce qui peut altérer les couleurs</li>
                <li>Pour un rendu fidèle, travaillez directement en CMJN dès le départ</li>
            </ul>
        </div>
    </div>

    <!-- FONDS PERDUS ET ZONES -->
    <div class="section">
        <h2 class="section-title">✂️ FONDS PERDUS ET ZONES DE SÉCURITÉ</h2>

        <div class="success-box">
            <strong>📐 Fonds perdus (Bleed):</strong> +<?php echo $fond_perdu_cm * 10; ?>mm (<?php echo $fond_perdu_cm; ?>cm) de chaque côté
        </div>

        <p style="margin: 15px 0;">
            Les <strong>fonds perdus</strong> sont une marge de sécurité indispensable pour garantir une découpe parfaite.
            Prolongez votre visuel de <?php echo $fond_perdu_cm * 10; ?>mm au-delà du format fini pour éviter les liserés blancs après la coupe.
        </p>

        <div class="success-box">
            <strong>🛡️ Zone de sécurité:</strong> <?php echo $zone_securite_cm * 10; ?>mm (<?php echo $zone_securite_cm; ?>cm) depuis le bord fini
        </div>

        <p style="margin: 15px 0;">
            La <strong>zone de sécurité</strong> garantit que vos textes et éléments importants ne seront pas coupés.
            Placez tous les éléments critiques à au moins <?php echo $zone_securite_cm * 10; ?>mm des bords.
        </p>
    </div>

    <!-- CHECKLIST -->
    <div class="section">
        <h2 class="section-title">✅ CHECKLIST AVANT ENVOI</h2>

        <div class="checklist">
            <h3>Vérifiez ces points avant d'envoyer votre fichier:</h3>
            <ul>
                <li>Les dimensions du fichier incluent les fonds perdus (+<?php echo $fond_perdu_cm * 10; ?>mm de chaque côté)</li>
                <li>Format final: <strong><?php echo $largeur_avec_fond_perdu; ?> × <?php echo $hauteur_avec_fond_perdu; ?> cm</strong></li>
                <li>La résolution est d'au moins <strong><?php echo $resolution_ideale_dpi; ?> DPI</strong></li>
                <li>Le profil colorimétrique est <strong>CMJN (CMYK)</strong></li>
                <li>Les textes sont vectorisés ou les polices sont incorporées</li>
                <li>Tous les éléments importants sont dans la zone de sécurité</li>
                <li>Le visuel est prolongé dans les fonds perdus (pas de blanc)</li>
                <li>Le format de fichier est compatible (PDF, AI, EPS, PSD, TIFF)</li>
            </ul>
        </div>
    </div>

    <!-- CONTACT -->
    <div class="section">
        <h2 class="section-title">📞 BESOIN D'AIDE ?</h2>
        <p style="font-size: 14px; line-height: 1.8;">
            <strong>Email:</strong> contact@imprixo.fr<br>
            <strong>Téléphone:</strong> 01 23 45 67 89<br>
            <strong>Horaires:</strong> Lundi-Vendredi 9h-18h
        </p>
        <p style="margin-top: 15px; font-size: 13px; color: #7f8c8d;">
            Notre équipe technique est à votre disposition pour vérifier vos fichiers avant impression.
            N'hésitez pas à nous contacter en cas de doute !
        </p>
    </div>

    <div class="footer">
        <p><strong>IMPRIXO</strong> - Impression professionnelle grand format</p>
        <p>www.imprixo.fr • contact@imprixo.fr</p>
        <p style="margin-top: 10px;">Document généré le <?php echo date('d/m/Y à H:i'); ?></p>
    </div>

    <script>
        // Auto-print si paramètre ?print=1
        if (window.location.search.includes('print=1')) {
            window.print();
        }
    </script>
</body>
</html>
