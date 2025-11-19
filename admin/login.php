<?php
/**
 * Page de connexion Admin - Imprixo Admin
 */

require_once __DIR__ . '/auth.php';

// Si déjà connecté, rediriger
if (session_status() === PHP_SESSION_NONE) {
    session_name(ADMIN_SESSION_NAME);
    session_start();
}

if (isset($_SESSION['admin_id'])) {
    header('Location: /admin/index.php');
    exit;
}

$error = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = cleanInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Veuillez remplir tous les champs';
    } else {
        $db = Database::getInstance();

        $admin = $db->fetchOne(
            "SELECT * FROM admin_users WHERE username = ? AND actif = TRUE",
            [$username]
        );

        if ($admin && password_verify($password, $admin['password_hash'])) {
            connecterAdmin($admin['id'], $admin['username'], $admin['role']);
            header('Location: /admin/index.php');
            exit;
        } else {
            $error = 'Identifiants incorrects';
            if ($admin) {
                logAdminAction($admin['id'], 'login_failed', 'Tentative de connexion échouée');
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
    <title>Connexion Admin - Imprixo</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(135deg, #e63946 0%, #d62839 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            padding: 50px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 450px;
        }

        .logo {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-icon {
            font-size: 64px;
            margin-bottom: 16px;
        }

        .logo h1 {
            color: #e63946;
            font-size: 32px;
            margin-bottom: 8px;
            font-weight: 900;
        }

        .logo p {
            color: #666;
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #e63946;
            box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #e63946 0%, #d62839 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 8px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(230, 57, 70, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .error {
            background: #fee;
            color: #c33;
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            border-left: 4px solid #c33;
            font-size: 14px;
        }

        .info-box {
            margin-top: 24px;
            padding: 16px;
            background: #f8f9fa;
            border-radius: 8px;
            font-size: 13px;
            color: #666;
            border-left: 4px solid #e63946;
        }

        .info-box strong {
            color: #333;
        }

        code {
            background: #fff;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            color: #e63946;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <div class="logo-icon">🖨️</div>
            <h1>Imprixo</h1>
            <p>Administration</p>
        </div>

        <?php if ($error): ?>
            <div class="error">⚠️ <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn-login">
                Se connecter
            </button>
        </form>

        <div class="info-box">
            <strong>📝 Premier démarrage ?</strong><br>
            Identifiants par défaut :<br>
            • Utilisateur : <code>admin</code><br>
            • Mot de passe : <code>Admin123!</code><br>
            <br>
            <strong>⚠️ Changez-le immédiatement !</strong>
        </div>
    </div>
</body>
</html>
