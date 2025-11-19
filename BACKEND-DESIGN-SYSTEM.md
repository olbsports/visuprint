# 🎨 Backend Design System - VisuPrint Pro

## Vue d'ensemble

Votre backend admin a été complètement redesigné avec un **Design System moderne et professionnel** ! Tout est maintenant cohérent, harmonieux et facile à utiliser.

---

## 🎯 Ce qui a changé

### ✅ **AVANT** (l'ancien système)
- ❌ Design disparate sur chaque page
- ❌ Pas de navigation unifiée
- ❌ Couleurs incohérentes
- ❌ Code CSS dupliqué partout
- ❌ Pas de système de composants

### ✨ **APRÈS** (le nouveau système)
- ✅ Design cohérent sur toutes les pages
- ✅ Navigation sidebar professionnelle
- ✅ Palette de couleurs harmonieuse
- ✅ Templates réutilisables (header/footer)
- ✅ Composants standardisés

---

## 📋 Structure du nouveau backend

```
admin/
├── includes/
│   ├── header.php    ← Navigation sidebar + CSS global
│   └── footer.php    ← Scripts + fermeture HTML
│
├── index.php          ← Tableau de bord (redesigné)
├── produits.php       ← Liste produits en grid (redesigné)
├── editer-produit.php ← Formulaire édition (redesigné)
├── finitions-catalogue.php
└── ...autres pages
```

---

## 🎨 Charte graphique

### Couleurs principales

| Couleur | Code Hex | Usage |
|---------|----------|-------|
| **Primary** | `#667eea` | Boutons principaux, liens, highlights |
| **Secondary** | `#764ba2` | Accents, dégradés |
| **Success** | `#10b981` | Confirmations, statuts positifs |
| **Warning** | `#f59e0b` | Alertes, attention |
| **Danger** | `#ef4444` | Erreurs, suppressions |
| **Info** | `#3b82f6` | Informations, badges |

### Couleurs neutres

| Couleur | Code Hex | Usage |
|---------|----------|-------|
| **Background** | `#f8fafc` | Fond de page |
| **Card** | `#ffffff` | Fond des cartes |
| **Border** | `#e2e8f0` | Bordures |
| **Text Primary** | `#1e293b` | Texte principal |
| **Text Secondary** | `#64748b` | Texte secondaire |
| **Text Muted** | `#94a3b8` | Texte discret |

### Dégradés (utilisés dans le dashboard)

```css
/* Gradient principal (sidebar, boutons) */
linear-gradient(135deg, #667eea 0%, #764ba2 100%)

/* Gradient rose */
linear-gradient(135deg, #f093fb 0%, #f5576c 100%)

/* Gradient bleu */
linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)

/* Gradient vert */
linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)
```

---

## 🧩 Composants disponibles

### 1. **Navigation Sidebar**

La sidebar est fixe sur le côté gauche avec :
- Logo en haut
- Menu hiérarchique par sections
- Indicateur de page active
- Footer utilisateur avec déconnexion
- Responsive (collapse sur mobile)

**Sections du menu :**
- 📊 Principal : Tableau de bord
- 📦 Produits : Produits, Nouveau produit, Finitions
- 🛍️ Commandes : Commandes, Nouvelle commande
- 👥 Clients : Clients, Nouveau client
- 🔨 Outils : Générer pages, Paramètres

### 2. **Top Bar**

Utilisée en haut de chaque page pour afficher :
- Titre de la page
- Sous-titre explicatif
- Boutons d'actions rapides

```php
<div class="top-bar">
    <div>
        <h1 class="page-title">📊 Titre de la page</h1>
        <p class="page-subtitle">Description</p>
    </div>
    <div class="top-bar-actions">
        <a href="#" class="btn btn-primary">Action</a>
    </div>
</div>
```

### 3. **Cards**

Conteneurs pour le contenu :

```php
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Titre de la carte</h2>
    </div>
    <!-- Contenu -->
</div>
```

### 4. **Boutons**

Différents styles disponibles :

```html
<a href="#" class="btn btn-primary">Primaire</a>
<a href="#" class="btn btn-success">Succès</a>
<a href="#" class="btn btn-warning">Attention</a>
<a href="#" class="btn btn-danger">Danger</a>
<a href="#" class="btn btn-secondary">Secondaire</a>

<!-- Petit bouton -->
<a href="#" class="btn btn-primary btn-sm">Petit</a>
```

### 5. **Badges**

Pour afficher les statuts :

```html
<span class="badge badge-success">Payé</span>
<span class="badge badge-warning">En attente</span>
<span class="badge badge-danger">Refusé</span>
<span class="badge badge-info">Nouveau</span>
```

### 6. **Alerts**

Messages de feedback :

```php
<div class="alert alert-success">✓ Opération réussie</div>
<div class="alert alert-error">✗ Une erreur est survenue</div>
<div class="alert alert-warning">⚠ Attention requise</div>
<div class="alert alert-info">ℹ Information</div>
```

### 7. **Formulaires**

Structure standard :

```html
<div class="form-group">
    <label class="form-label">Nom du champ</label>
    <input type="text" class="form-input" placeholder="Saisie...">
    <small class="form-help">Texte d'aide</small>
</div>

<!-- Grid pour 2 colonnes -->
<div class="form-grid">
    <div class="form-group">...</div>
    <div class="form-group">...</div>
</div>
```

### 8. **Tables**

Tableaux avec style unifié :

```html
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Colonne 1</th>
                <th>Colonne 2</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Donnée 1</td>
                <td>Donnée 2</td>
            </tr>
        </tbody>
    </table>
</div>
```

---

## 📄 Comment créer une nouvelle page admin

### Étape 1 : Structure de base

```php
<?php
/**
 * Ma nouvelle page - VisuPrint Pro
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../api/config.php';

verifierAdminConnecte();
$admin = getAdminInfo();
$db = Database::getInstance();

$pageTitle = 'Ma nouvelle page';

// ... votre logique PHP ...

include __DIR__ . '/includes/header.php';
?>

<!-- Votre contenu HTML ici -->

<div class="top-bar">
    <div>
        <h1 class="page-title">🎨 Ma nouvelle page</h1>
        <p class="page-subtitle">Description de ma page</p>
    </div>
</div>

<div class="card">
    <!-- Votre contenu -->
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
```

### Étape 2 : Ajouter au menu

Éditer `/admin/includes/header.php` et ajouter dans la section appropriée :

```php
<a href="/admin/ma-page.php" class="menu-item">
    <span class="menu-item-icon">🎨</span>
    <span class="menu-item-text">Ma Page</span>
</a>
```

---

## 🎯 Pages déjà redesignées

### ✅ `index.php` - Tableau de bord
- **8 cartes statistiques** avec dégradés colorés
- **Liste des dernières commandes** en tableau
- **Actions rapides** pour accès directs
- **Responsive** sur tous les écrans

### ✅ `produits.php` - Gestion produits
- **Grille de cartes** responsive (auto-fill)
- **Filtres** recherche + catégorie
- **Badges promotions** si actives
- **Hover effects** sur les cartes
- **Images produits** avec placeholder élégant

### ✅ `editer-produit.php` - Édition produit
- **Formulaire structuré** par sections
- **Finitions catalogue** avec checkboxes
- **Prix personnalisables** par produit
- **Navigation claire** avec breadcrumb

### ✅ `finitions-catalogue.php`
- Déjà créée avec le bon design
- Liste des finitions par catégories
- Actions d'édition/suppression

---

## 🚀 Variables CSS disponibles

Toutes les variables CSS sont définies dans `/admin/includes/header.php` :

```css
:root {
    /* Couleurs */
    --primary: #667eea;
    --secondary: #764ba2;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --info: #3b82f6;

    /* Backgrounds */
    --bg-main: #f8fafc;
    --bg-card: #ffffff;
    --bg-hover: #f1f5f9;

    /* Textes */
    --text-primary: #1e293b;
    --text-secondary: #64748b;
    --text-muted: #94a3b8;

    /* Borders */
    --border: #e2e8f0;

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
```

**Utilisation :**
```css
.mon-element {
    color: var(--primary);
    background: var(--bg-card);
    border-radius: var(--radius-md);
    padding: var(--spacing-lg);
    box-shadow: var(--shadow-md);
}
```

---

## 📱 Responsive Design

Le backend est **entièrement responsive** :

- **Desktop (>768px)** : Sidebar complète (280px)
- **Mobile (<768px)** : Sidebar compacte (70px) avec icônes seulement

**Breakpoint automatique :**
```css
@media (max-width: 768px) {
    /* Sidebar se réduit automatiquement */
    /* Textes disparaissent, icônes restent */
}
```

---

## 🎨 Exemples de mise en page

### Grid responsive (auto-fill)

```html
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
    <div class="card">Carte 1</div>
    <div class="card">Carte 2</div>
    <div class="card">Carte 3</div>
</div>
```

### Flex horizontal

```html
<div style="display: flex; gap: 16px; align-items: center;">
    <div style="flex: 1;">Élément qui grandit</div>
    <button class="btn btn-primary">Action</button>
</div>
```

---

## 🔧 Maintenance

### Modifier les couleurs globales

Éditer `/admin/includes/header.php` section `:root {}` et changer les valeurs des variables.

### Ajouter un élément au menu

Éditer `/admin/includes/header.php` section `<nav class="sidebar-menu">`.

### Modifier le logo

Éditer `/admin/includes/header.php` section `.sidebar-logo`.

---

## 💡 Bonnes pratiques

1. **Toujours utiliser** `header.php` et `footer.php`
2. **Utiliser les variables CSS** plutôt que des couleurs en dur
3. **Respecter la hiérarchie** : top-bar → cards → contenu
4. **Utiliser les classes** existantes (btn, badge, alert, etc.)
5. **Tester le responsive** sur mobile

---

## 🎉 Résultat final

Votre backend est maintenant :
- ✅ **Professionnel** et moderne
- ✅ **Cohérent** sur toutes les pages
- ✅ **Facile à maintenir** (DRY principle)
- ✅ **Extensible** (ajout de pages facile)
- ✅ **Responsive** (mobile-friendly)
- ✅ **Rapide** (CSS optimisé)

**Profitez de votre nouveau backend ! 🚀**
