# 🚀 INSTRUCTIONS RAPIDES - MIGRATION

## ✅ Situation : Vous avez DÉJÀ vos produits dans la base

**Pas de panique !** Vos produits ne seront **PAS supprimés**.

Cette migration va simplement **ajouter les nouvelles fonctionnalités** sans toucher à vos données existantes.

---

## 📋 Ce que la migration va ajouter

### Nouvelles colonnes à la table `produits`:
- `image_url` - Pour les images produits
- `actif` - Activer/désactiver un produit
- `nouveau` - Badge "Nouveau"
- `best_seller` - Badge "Best Seller"
- `meta_title`, `meta_description`, `meta_keywords` - SEO
- `created_at`, `updated_at` - Dates de création/modification

### Nouvelles tables:
- ✅ `produits_finitions` - Options configurateur par produit
- ✅ `promotions` - Promotions avec countdown et badges
- ✅ `produits_formats` - Formats prédéfinis
- ✅ `produits_historique` - Historique des modifications
- ✅ `admin_users` - Comptes administrateurs
- ✅ `v_produits_avec_promos` - Vue calcul automatique prix promos

### Finitions par défaut créées automatiquement:
- **PVC** : Standard, Contrecollage, Découpe forme
- **Aluminium** : Standard, Perçage, Cadre
- **Bâche** : Standard, Œillets, Ourlet
- **Textile** : Standard, Baguettes, Confection

---

## 🎯 ÉTAPES D'INSTALLATION (3 minutes)

### 1️⃣ Ouvrir votre navigateur

```
https://votre-domaine.com/admin/executer-migration.php
```

### 2️⃣ Cliquer sur "Lancer la migration"

Le script va automatiquement :
- Ajouter les nouvelles colonnes
- Créer les nouvelles tables
- Créer les finitions par défaut
- Créer le compte admin

### 3️⃣ Vérifier le résultat

Vous verrez :
```
✅ Migration réussie !
📦 Produits dans la base : 54
🎨 Finitions créées : XX
👤 Compte admin : admin@imprixo.com / admin123
```

### 4️⃣ Se connecter à l'admin

```
URL : https://votre-domaine.com/admin/
Email : admin@imprixo.com
Mot de passe : admin123
```

⚠️ **CHANGEZ LE MOT DE PASSE** après la première connexion !

### 5️⃣ Supprimer le fichier de migration (sécurité)

Après avoir vérifié que tout fonctionne, supprimez :
```
/admin/executer-migration.php
```

---

## 🎨 Ce que vous pouvez faire maintenant

### Dans l'admin produits (`/admin/produits.php`) :
- ✅ Voir tous vos produits
- ✅ Rechercher et filtrer par catégorie
- ✅ Éditer n'importe quel produit

### Dans l'édition produit (`/admin/editer-produit.php`) :
- ✅ Ajouter des images (URL)
- ✅ Modifier les 5 prix dégressifs
- ✅ Gérer les finitions personnalisées
- ✅ Créer des promotions avec countdown
- ✅ Tout modifier en temps réel !

---

## ❓ FAQ

**Q: Mes produits vont être supprimés ?**
R: **NON !** La migration ajoute uniquement de nouvelles colonnes et tables. Rien n'est supprimé.

**Q: Je dois refaire tout le CSV ?**
R: **NON !** Vos produits sont déjà dans la base. La migration ajoute juste les nouvelles fonctionnalités.

**Q: Que se passe-t-il si je relance la migration ?**
R: Rien de grave. Le script utilise `IF NOT EXISTS` et `INSERT IGNORE`, donc il ne créera pas de doublons.

**Q: Je peux revenir en arrière ?**
R: Oui, si vous avez fait un backup avant. Sinon, les nouvelles colonnes resteront vides jusqu'à ce que vous les remplissiez.

**Q: Les finitions par défaut vont écraser les miennes ?**
R: Non, le script vérifie si des finitions existent déjà avant d'en créer.

---

## 🆘 En cas de problème

### Erreur de connexion à la base :
Vérifiez `/api/config.php` :
```php
define('DB_NAME', 'ispy2055_imprixo_ecommerce');
define('DB_USER', 'votre_user');
define('DB_PASS', 'votre_password');
```

### Migration ne démarre pas :
- Vérifiez que le fichier `migration-update-database.sql` existe
- Vérifiez les permissions PHP
- Consultez les logs Apache/PHP

### Produits ne s'affichent pas :
- Videz le cache de votre navigateur
- Vérifiez que la colonne `actif` est à `1` dans la base

---

## 📞 Support

Si vous rencontrez un problème :
1. Vérifiez les logs d'erreur PHP
2. Vérifiez la configuration `/api/config.php`
3. Testez la connexion MySQL
4. Consultez `SETUP-DATABASE-README.md` pour plus de détails

---

## ✨ Bon à savoir

Après la migration, vous aurez accès à :
- **API REST complète** : `/api/produits-api.php`
- **Gestion finitions** : personnalisables par produit
- **Système promotions** : avec countdown en temps réel
- **Historique complet** : de toutes les modifications
- **Images produits** : via URL (Unsplash, CDN, etc.)

**Tout est prêt pour gérer vos produits de A à Z ! 🎉**
