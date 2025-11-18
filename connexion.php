<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Imprixo</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="/"><h1>🎨 <span class="brand">Imprixo</span></h1></a>
                </div>
                <nav class="nav">
                    <a href="/">Accueil</a>
                    <a href="/catalogue.html">Catalogue</a>
                </nav>
            </div>
        </div>
    </header>

    <section style="padding: 80px 0;">
        <div class="container" style="max-width: 500px;">
            <h1 style="text-align: center; font-size: 36px; margin-bottom: 40px;">Connexion</h1>

            <!-- Formulaire de connexion -->
            <div style="background: white; border-radius: 16px; padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                <form id="loginForm" style="margin-bottom: 30px;">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label>Mot de passe</label>
                        <input type="password" name="password" required>
                    </div>

                    <div id="loginError" style="display: none; background: #fee; color: #c33; padding: 12px; border-radius: 8px; margin-bottom: 20px;"></div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 16px;">
                        Se connecter
                    </button>
                </form>

                <div style="text-align: center; color: #666; margin-bottom: 20px;">
                    Pas encore de compte ?
                </div>

                <button id="showRegisterBtn" class="btn btn-outline" style="width: 100%;">
                    Créer un compte
                </button>
            </div>

            <!-- Formulaire d'inscription (caché par défaut) -->
            <div id="registerSection" style="display: none; background: white; border-radius: 16px; padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); margin-top: 20px;">
                <h3 style="margin-bottom: 25px;">Créer un compte</h3>

                <form id="registerForm">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label>Prénom</label>
                            <input type="text" name="prenom" required>
                        </div>

                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" name="nom" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="tel" name="telephone">
                    </div>

                    <div class="form-group">
                        <label>Mot de passe</label>
                        <input type="password" name="password" required minlength="6">
                        <small style="color: #666;">Minimum 6 caractères</small>
                    </div>

                    <div id="registerError" style="display: none; background: #fee; color: #c33; padding: 12px; border-radius: 8px; margin-bottom: 20px;"></div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 16px;">
                        S'inscrire
                    </button>
                </form>

                <button id="showLoginBtn" class="btn btn-outline" style="width: 100%; margin-top: 15px;">
                    Retour à la connexion
                </button>
            </div>
        </div>
    </section>

    <script src="/js/app.js"></script>
    <script>
        // Toggle formulaires
        document.getElementById('showRegisterBtn').addEventListener('click', () => {
            document.getElementById('registerSection').style.display = 'block';
            document.querySelector('form#loginForm').closest('div').style.display = 'none';
        });

        document.getElementById('showLoginBtn').addEventListener('click', () => {
            document.getElementById('registerSection').style.display = 'none';
            document.querySelector('form#loginForm').closest('div').style.display = 'block';
        });

        // Connexion
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);

            try {
                const result = await Imprixo.loginClient(data.email, data.password);

                if (result.success) {
                    window.location.href = '/mon-compte.php';
                }
            } catch (error) {
                document.getElementById('loginError').textContent = error.message;
                document.getElementById('loginError').style.display = 'block';
            }
        });

        // Inscription
        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);

            try {
                const result = await Imprixo.registerClient(data);

                if (result.success) {
                    window.location.href = '/mon-compte.php';
                }
            } catch (error) {
                document.getElementById('registerError').textContent = error.message;
                document.getElementById('registerError').style.display = 'block';
            }
        });
    </script>
</body>
</html>
