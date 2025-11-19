<?php
/**
 * Header et Navigation - Backend VisuPrint Pro
 * Design System unifié pour toutes les pages admin
 */

// Vérifier que l'admin est connecté
if (!isset($admin)) {
    $admin = getAdminInfo();
}

// Déterminer la page active pour le menu
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Administration'; ?> - VisuPrint Pro</title>
    <style>
        /* ===================================
           DESIGN SYSTEM - VISUPRINT PRO
           Palette de couleurs et styles globaux
        =================================== */

        :root {
            /* Couleurs principales */
            --primary: #667eea;
            --primary-dark: #5568d3;
            --primary-light: #7c8ef0;
            --secondary: #764ba2;
            --accent: #f093fb;

            /* Couleurs sémantiques */
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;

            /* Couleurs neutres */
            --bg-main: #f8fafc;
            --bg-card: #ffffff;
            --bg-hover: #f1f5f9;
            --border: #e2e8f0;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;

            /* Ombres */
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);

            /* Spacing */
            --spacing-xs: 4px;
            --spacing-sm: 8px;
            --spacing-md: 16px;
            --spacing-lg: 24px;
            --spacing-xl: 32px;

            /* Border radius */
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;
        }

        /* Reset & Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: var(--bg-main);
            color: var(--text-primary);
            line-height: 1.6;
        }

        /* Layout Principal */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Navigation */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: var(--shadow-xl);
            z-index: 1000;
        }

        .sidebar-header {
            padding: var(--spacing-xl);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-logo {
            font-size: 24px;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-logo-icon {
            font-size: 32px;
        }

        .sidebar-menu {
            flex: 1;
            padding: var(--spacing-md) 0;
        }

        .menu-section {
            margin-bottom: var(--spacing-lg);
        }

        .menu-section-title {
            padding: var(--spacing-sm) var(--spacing-lg);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: var(--spacing-xs);
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px var(--spacing-lg);
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 15px;
            border-left: 3px solid transparent;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .menu-item.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-left-color: white;
            font-weight: 600;
        }

        .menu-item-icon {
            font-size: 20px;
            width: 24px;
            text-align: center;
        }

        .menu-item-badge {
            margin-left: auto;
            background: var(--danger);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .sidebar-footer {
            padding: var(--spacing-lg);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            margin-bottom: var(--spacing-md);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 600;
        }

        .user-details {
            flex: 1;
        }

        .user-name {
            font-weight: 600;
            font-size: 14px;
        }

        .user-role {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
        }

        .btn-logout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: var(--radius-md);
            color: white;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: var(--spacing-xl);
        }

        /* Top Bar */
        .top-bar {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            padding: var(--spacing-lg);
            margin-bottom: var(--spacing-xl);
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .page-subtitle {
            font-size: 14px;
            color: var(--text-secondary);
            margin-top: 4px;
        }

        .top-bar-actions {
            display: flex;
            gap: var(--spacing-md);
        }

        /* Cards */
        .card {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            padding: var(--spacing-xl);
            box-shadow: var(--shadow-sm);
            margin-bottom: var(--spacing-lg);
        }

        .card-header {
            margin-bottom: var(--spacing-lg);
            padding-bottom: var(--spacing-md);
            border-bottom: 2px solid var(--border);
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-success:hover {
            background: #059669;
        }

        .btn-warning {
            background: var(--warning);
            color: white;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-secondary {
            background: var(--bg-hover);
            color: var(--text-primary);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background: var(--border);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fed7aa; color: #92400e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-info { background: #dbeafe; color: #1e40af; }

        /* Alerts */
        .alert {
            padding: var(--spacing-md);
            border-radius: var(--radius-md);
            margin-bottom: var(--spacing-lg);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid var(--success);
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid var(--danger);
        }

        .alert-warning {
            background: #fed7aa;
            color: #92400e;
            border-left: 4px solid var(--warning);
        }

        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            border-left: 4px solid var(--info);
        }

        /* Forms */
        .form-group {
            margin-bottom: var(--spacing-lg);
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: var(--spacing-sm);
            color: var(--text-primary);
            font-size: 14px;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid var(--border);
            border-radius: var(--radius-md);
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s ease;
            background: var(--bg-card);
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-help {
            display: block;
            margin-top: var(--spacing-xs);
            font-size: 12px;
            color: var(--text-muted);
        }

        /* Tables */
        .table-container {
            overflow-x: auto;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            background: var(--bg-card);
        }

        .table thead {
            background: var(--bg-hover);
        }

        .table th {
            padding: 14px 16px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table td {
            padding: 16px;
            border-top: 1px solid var(--border);
        }

        .table tbody tr:hover {
            background: var(--bg-hover);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }

            .sidebar-header,
            .menu-section-title,
            .menu-item-text,
            .sidebar-footer .user-details,
            .btn-logout span {
                display: none;
            }

            .sidebar-logo {
                justify-content: center;
            }

            .menu-item {
                justify-content: center;
            }

            .main-content {
                margin-left: 70px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="/admin/index.php" class="sidebar-logo">
                    <span class="sidebar-logo-icon">🎨</span>
                    <span>VisuPrint Pro</span>
                </a>
            </div>

            <nav class="sidebar-menu">
                <div class="menu-section">
                    <div class="menu-section-title">Principal</div>
                    <a href="/admin/index.php" class="menu-item <?php echo $currentPage === 'index.php' ? 'active' : ''; ?>">
                        <span class="menu-item-icon">📊</span>
                        <span class="menu-item-text">Tableau de bord</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Produits</div>
                    <a href="/admin/produits.php" class="menu-item <?php echo in_array($currentPage, ['produits.php', 'editer-produit.php', 'nouveau-produit.php']) ? 'active' : ''; ?>">
                        <span class="menu-item-icon">📦</span>
                        <span class="menu-item-text">Tous les produits</span>
                    </a>
                    <a href="/admin/nouveau-produit.php" class="menu-item">
                        <span class="menu-item-icon">➕</span>
                        <span class="menu-item-text">Nouveau produit</span>
                    </a>
                    <a href="/admin/finitions-catalogue.php" class="menu-item <?php echo in_array($currentPage, ['finitions-catalogue.php', 'finition-editer.php', 'finition-ajouter.php']) ? 'active' : ''; ?>">
                        <span class="menu-item-icon">🎨</span>
                        <span class="menu-item-text">Finitions</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Commandes</div>
                    <a href="/admin/commandes.php" class="menu-item <?php echo in_array($currentPage, ['commandes.php', 'commande.php']) ? 'active' : ''; ?>">
                        <span class="menu-item-icon">🛍️</span>
                        <span class="menu-item-text">Commandes</span>
                    </a>
                    <a href="/admin/nouvelle-commande.php" class="menu-item">
                        <span class="menu-item-icon">➕</span>
                        <span class="menu-item-text">Nouvelle commande</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Clients</div>
                    <a href="/admin/clients.php" class="menu-item <?php echo in_array($currentPage, ['clients.php', 'client.php']) ? 'active' : ''; ?>">
                        <span class="menu-item-icon">👥</span>
                        <span class="menu-item-text">Tous les clients</span>
                    </a>
                    <a href="/admin/nouveau-client.php" class="menu-item">
                        <span class="menu-item-icon">➕</span>
                        <span class="menu-item-text">Nouveau client</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Outils</div>
                    <a href="/admin/generer-pages-produits-html.php" class="menu-item">
                        <span class="menu-item-icon">🔨</span>
                        <span class="menu-item-text">Générer pages</span>
                    </a>
                    <a href="/admin/parametres.php" class="menu-item <?php echo $currentPage === 'parametres.php' ? 'active' : ''; ?>">
                        <span class="menu-item-icon">⚙️</span>
                        <span class="menu-item-text">Paramètres</span>
                    </a>
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($admin['prenom'] ?? 'A', 0, 1)); ?>
                    </div>
                    <div class="user-details">
                        <div class="user-name"><?php echo htmlspecialchars($admin['prenom'] ?? 'Admin'); ?> <?php echo htmlspecialchars($admin['nom'] ?? ''); ?></div>
                        <div class="user-role"><?php echo ucfirst($admin['role'] ?? 'Admin'); ?></div>
                    </div>
                </div>
                <a href="/admin/logout.php" class="btn-logout">
                    <span>🚪</span>
                    <span>Déconnexion</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
