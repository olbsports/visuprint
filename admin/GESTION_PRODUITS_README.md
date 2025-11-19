# Système de Gestion des Produits - Imprixo Admin

## Vue d'ensemble

Système complet de gestion des produits permettant d'ajouter, modifier et supprimer des produits du catalogue Imprixo via l'interface admin.

## Fichiers créés/modifiés

### 1. `/admin/produits.php` (AMÉLIORÉ)
**Interface principale de gestion des produits**

Nouvelles fonctionnalités :
- ✓ Bouton "Ajouter un produit" vers nouveau-produit.php
- ✓ Bouton "Régénérer toutes les pages HTML" vers generer-pages-produits-html.php
- ✓ Recherche en temps réel (ID, nom, sous-titre, description)
- ✓ Filtre par catégorie
- ✓ Actions pour chaque produit :
  - 👁️ Voir la page HTML
  - ✏️ Éditer le produit
  - 🗑️ Supprimer le produit
- ✓ Messages de succès/erreur via paramètres GET
- ✓ Statistiques mises à jour selon les filtres

### 2. `/admin/nouveau-produit.php` (NOUVEAU)
**Formulaire d'ajout de produit**

Fonctionnalités :
- ✓ Formulaire complet avec tous les champs du CSV (25 colonnes)
- ✓ Validation des champs obligatoires
- ✓ Vérification de l'unicité de l'ID produit
- ✓ Liste déroulante des catégories existantes
- ✓ Ajout automatique au CSV
- ✓ Génération automatique de la page HTML du produit
- ✓ Redirection avec message de succès
- ✓ Gestion des erreurs avec messages clairs

Champs du formulaire :
- **Informations de base** : ID, Catégorie, Nom, Sous-titre, Descriptions
- **Caractéristiques techniques** : Poids, Épaisseur, Format max, Usage, Durée de vie, etc.
- **Prix et tarification** : 5 paliers de prix dégressifs + coûts d'achat
- **Logistique** : Commande minimum, Délai, Unité de vente

### 3. `/admin/editer-produit.php` (NOUVEAU)
**Formulaire d'édition de produit**

Fonctionnalités :
- ✓ Chargement automatique des données du produit via ID (GET)
- ✓ Formulaire pré-rempli avec toutes les valeurs actuelles
- ✓ ID produit en lecture seule (non modifiable)
- ✓ Mise à jour du CSV
- ✓ Régénération automatique de la page HTML
- ✓ Bouton de suppression directement accessible
- ✓ Validation et gestion des erreurs

### 4. `/admin/supprimer-produit.php` (NOUVEAU)
**Script de suppression de produit**

Fonctionnalités :
- ✓ Vérification de l'existence du produit
- ✓ Suppression de la ligne dans le CSV
- ✓ Suppression du fichier HTML correspondant
- ✓ Redirection avec message de confirmation
- ✓ Gestion des erreurs (produit non trouvé, etc.)

### 5. `/admin/helpers/generer-page-produit.php` (NOUVEAU)
**Helper pour la génération des pages HTML**

Fonctionnalités :
- ✓ Fonction `genererPageProduitHTML($produit)` : Génère le HTML d'un produit
- ✓ Fonction `genererEtSauvegarderPageProduit($produit, $outputDir)` : Génère et sauvegarde
- ✓ Code mutualisé entre nouveau-produit.php et editer-produit.php
- ✓ Template HTML complet avec :
  - SEO optimisé (meta tags, Open Graph, Schema.org)
  - Design responsive (Tailwind CSS)
  - Prix dégressifs affichés
  - Caractéristiques techniques
  - Compatibilité avec le header/footer du site

## Structure du CSV

Fichier : `/CATALOGUE_COMPLET_VISUPRINT.csv`

25 colonnes :
```
ID_PRODUIT,CATEGORIE,NOM_PRODUIT,SOUS_TITRE,DESCRIPTION_COURTE,
DESCRIPTION_LONGUE,POIDS_M2,EPAISSEUR,FORMAT_MAX_CM,USAGE,
DUREE_VIE,CERTIFICATION,FINITION,IMPRESSION_FACES,COUT_ACHAT_M2,
PRIX_SIMPLE_FACE_M2,PRIX_DOUBLE_FACE_M2,PRIX_0_10_M2,PRIX_11_50_M2,
PRIX_51_100_M2,PRIX_101_300_M2,PRIX_300_PLUS_M2,COMMANDE_MIN_EURO,
DELAI_STANDARD_JOURS,UNITE_VENTE
```

## Workflow d'utilisation

### Ajouter un produit
1. Aller sur `/admin/produits.php`
2. Cliquer sur "➕ Ajouter un produit"
3. Remplir le formulaire (champs obligatoires marqués *)
4. Cliquer sur "💾 Enregistrer le produit"
5. Le produit est ajouté au CSV et sa page HTML est générée automatiquement
6. Redirection vers la liste avec message de succès

### Éditer un produit
1. Aller sur `/admin/produits.php`
2. Cliquer sur "✏️" à côté du produit à modifier
3. Modifier les champs souhaités
4. Cliquer sur "💾 Enregistrer les modifications"
5. Le CSV est mis à jour et la page HTML est régénérée
6. Redirection vers la liste avec message de succès

### Supprimer un produit
1. Aller sur `/admin/produits.php`
2. Cliquer sur "🗑️" à côté du produit à supprimer
3. Confirmer la suppression
4. Le produit est supprimé du CSV et son fichier HTML est supprimé
5. Redirection vers la liste avec message de confirmation

### Rechercher/Filtrer
1. Aller sur `/admin/produits.php`
2. Utiliser la barre de recherche pour chercher par ID, nom, description
3. Utiliser le filtre catégorie pour afficher seulement une catégorie
4. Les résultats et statistiques sont mis à jour en temps réel

### Régénérer toutes les pages HTML
1. Aller sur `/admin/produits.php`
2. Cliquer sur "🔄 Régénérer toutes les pages"
3. Toutes les pages HTML des produits sont recréées depuis le CSV

## Style et Design

- **Gradient violet** : #667eea → #764ba2 (header)
- **Police** : -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto
- **Boutons** :
  - Primaire (bleu violet) : #667eea
  - Succès (vert) : #27ae60
  - Danger (rouge) : #e74c3c
  - Secondaire (gris) : #95a5a6
- **Messages** :
  - Succès : fond vert clair avec bordure verte
  - Erreur : fond rouge clair avec bordure rouge
  - Info : fond bleu clair avec bordure bleue

## Sécurité

- ✓ Authentification requise (require_once auth.php)
- ✓ Validation des champs obligatoires
- ✓ Vérification de l'unicité de l'ID produit
- ✓ Échappement HTML (htmlspecialchars)
- ✓ Nettoyage des IDs pour les noms de fichiers
- ✓ Confirmation avant suppression (JavaScript)

## Notes techniques

1. **Génération HTML** : Les pages HTML sont générées au format `.html` (statique) dans le dossier `/produit/`
2. **Nom de fichier** : L'ID du produit est nettoyé (caractères spéciaux retirés) pour le nom du fichier
3. **CSV** : Format standard avec guillemets pour les champs contenant des virgules
4. **Redirection** : Utilise header() avec messages GET encodés (urlencode)
5. **Helper** : Code mutualisé dans `/admin/helpers/generer-page-produit.php`

## Améliorations futures possibles

- [ ] Upload d'images produits
- [ ] Import/Export CSV en masse
- [ ] Duplication de produit
- [ ] Historique des modifications
- [ ] Gestion des variantes de produit
- [ ] Preview avant enregistrement
- [ ] Validation côté client (JavaScript)
- [ ] API REST pour les produits

## Support

En cas de problème :
1. Vérifier les permissions des fichiers CSV et du dossier `/produit/`
2. Vérifier les logs PHP pour les erreurs
3. S'assurer que le CSV est bien formaté
4. Vérifier que le dossier `/admin/helpers/` existe et est accessible
