# 🗄️ SETUP BASE DE DONNÉES IMPRIXO

## Étape 1 : Configuration de la connexion

Éditez le fichier `/api/config.php` :

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'ispy2055_imprixo_ecommerce'); // Votre base de données
define('DB_USER', 'votre_user');                  // Votre user MySQL
define('DB_PASS', 'votre_password');              // Votre mot de passe
```

## Étape 2 : Créer la base de données

Dans phpMyAdmin ou MySQL :

```sql
CREATE DATABASE ispy2055_imprixo_ecommerce CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## Étape 3 : Importer la structure

Exécutez le fichier SQL :

```bash
mysql -u votre_user -p ispy2055_imprixo_ecommerce < /admin/setup-database.sql
```

Ou via phpMyAdmin :
1. Sélectionnez la base `ispy2055_imprixo_ecommerce`
2. Onglet "Importer"
3. Choisir `/admin/setup-database.sql`
4. Cliquez sur "Exécuter"

## Étape 4 : Mise à jour de la base de données (MIGRATION)

⚠️ **SI VOUS AVEZ DÉJÀ VOS PRODUITS :** Utilisez le script de migration au lieu de l'import CSV

### Option A : Vous avez déjà des produits (MIGRATION)

Exécutez le script de migration dans le navigateur :
```
https://votre-domaine.com/admin/executer-migration.php
```

Ce script va :
- ✅ Ajouter les nouvelles colonnes (image_url, actif, SEO, etc.)
- ✅ Créer les nouvelles tables (finitions, promotions, formats, historique)
- ✅ Créer les finitions par défaut selon vos catégories
- ✅ **CONSERVER tous vos produits existants**

### Option B : Base de données vide (IMPORT CSV)

Si vous n'avez PAS encore de produits, utilisez l'import CSV :

```bash
cd /admin
php import-csv-to-database.php
```

Ou dans le navigateur :
```
https://votre-domaine.com/admin/import-csv-to-database.php
```

## Étape 5 : Connexion Admin

URL : `https://votre-domaine.com/admin/`

**Identifiants par défaut** :
- Email : `admin@imprixo.com`
- Mot de passe : `admin123`

⚠️ **IMPORTANT** : Changez le mot de passe après la première connexion !

## Structure de la base de données

### Table `produits`
Contient tous les produits avec :
- Informations générales (nom, description, catégorie)
- Spécifications techniques
- 5 prix dégressifs
- Images, SEO
- Statut (actif/inactif, nouveau, best_seller)

### Table `produits_finitions`
Options du configurateur par produit :
- Nom de la finition
- Description
- Prix supplément
- Type de prix (fixe, par m², par ml)

### Table `promotions`
Promotions sur les produits :
- Type (pourcentage, fixe, prix spécial)
- Dates début/fin
- Badge personnalisé
- Compte à rebours
- Code promo (optionnel)

### Table `produits_formats`
Formats prédéfinis personnalisables par produit

### Table `produits_historique`
Historique de toutes les modifications

### Table `admin_users`
Comptes administrateurs

## Fonctionnalités Admin

### Gestion Produits (`/admin/produits.php`)
- Liste complète avec filtres
- Recherche
- Activation/Désactivation
- Création/Modification/Suppression

### Édition Produit (`/admin/editer-produit.php`)
- Informations générales
- 5 prix dégressifs
- Finitions personnalisables
- Promotions
- Images
- SEO

### Gestion Promotions
- Créer promotions sur produits
- Dates début/fin
- Compteur temps réel
- Badges personnalisés

## API REST

Endpoints disponibles :

```
GET  /api/produits-api.php              # Liste tous les produits
GET  /api/produits-api.php?id=123       # Un produit
POST /api/produits-api.php              # Créer produit
PUT  /api/produits-api.php?id=123       # Modifier produit
DELETE /api/produits-api.php?id=123     # Supprimer produit
```

## Sécurité

- ✅ Requêtes préparées (protection SQL injection)
- ✅ Validation des données
- ✅ Sessions admin sécurisées
- ✅ Tokens CSRF
- ✅ Historique des modifications
- ✅ Logs des actions admin

## Backup

Pour sauvegarder :

```bash
mysqldump -u votre_user -p ispy2055_imprixo_ecommerce > backup_$(date +%Y%m%d).sql
```

## Support

Pour tout problème :
1. Vérifiez `/api/config.php`
2. Vérifiez les logs Apache/PHP
3. Testez la connexion MySQL

## Vue Produits avec Promotions

La vue `v_produits_avec_promos` calcule automatiquement le prix promotionnel selon le type de promotion.

```sql
SELECT * FROM v_produits_avec_promos WHERE actif = 1;
```
