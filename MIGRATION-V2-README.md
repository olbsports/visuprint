# 🎨 MIGRATION V2 - CONTRÔLE TOTAL SUR FINITIONS & PROMOTIONS

## ✅ Quoi de neuf ?

### Avant (V1):
- ❌ Finitions automatiques créées pour tous les produits
- ❌ Pas de contrôle granulaire
- ❌ Pas de conditions sur promotions

### Maintenant (V2):
- ✅ **Catalogue global de finitions** - Tu crées TOUTES les finitions possibles
- ✅ **Choix libre par produit** - Tu coches celles que tu veux activer
- ✅ **Finitions personnalisées** - Crée les tiennes avec prix, conditions, icônes
- ✅ **Promotions avec conditions** - Selon finition, m², quantité, etc.
- ✅ **Prix surchargeables** - Prix par défaut modifiable produit par produit

---

## 🚀 INSTALLATION

### Étape 1: Lancer la migration V2

Ouvre ton navigateur:
```
https://ton-domaine.com/admin/executer-migration.php
```

⚠️ **Important**: Utilise le nouveau fichier `migration-update-database-v2.sql`

### Étape 2: Le catalogue de finitions est pré-rempli

Le script crée automatiquement **20+ finitions** dans le catalogue :

**PVC:**
- Standard, Contrecollage, Découpe forme, Angles arrondis

**Aluminium:**
- Standard, Perçage, Cadre noir, Cadre argent

**Bâche:**
- Standard, Œillets 50cm, Œillets 25cm, Ourlet renforcé, Sandow élastique

**Textile:**
- Standard, Baguettes bois, Baguettes alu, Confection sur mesure, Œillets textiles

**Universel (Tous produits):**
- Livraison express, Fichier fourni (-10€), Installation

---

## 🎨 GESTION DES FINITIONS

### Page Catalogue (`/admin/finitions-catalogue.php`)

Tu y trouves TOUTES les finitions disponibles. C'est ta **bibliothèque globale**.

**Tu peux:**
- ✅ Voir toutes les finitions par catégorie
- ✅ Créer de nouvelles finitions personnalisées
- ✅ Éditer les finitions existantes
- ✅ Activer/Désactiver des finitions
- ✅ Définir des prix par défaut

### Créer une finition (`/admin/finition-ajouter.php`)

**Champs disponibles:**
- **Nom**: Ex: "Œillets tous les 50cm"
- **Description**: Détails pour le client
- **Catégorie**: PVC, Alu, Bâche, Textile, Tous, ou Aucune
- **Prix défaut**: +15€, +8€/m², -10€ (négatif = réduction !)
- **Type prix**: Fixe, Par m², Par ml, Pourcentage
- **Icône**: Emoji ou texte court (⭕, 🎨, etc.)
- **Ordre**: Pour trier l'affichage

**Catégories expliquées:**
- **"Tous"**: Apparaît automatiquement sur tous les produits
- **"PVC"**: Apparaît automatiquement sur les produits PVC
- **"Aucune"**: Tu dois l'activer manuellement produit par produit
- **Vide**: Disponible mais pas automatique

---

## 🏷️ ACTIVER FINITIONS SUR UN PRODUIT

### Dans `/admin/editer-produit.php`

**Section "🎨 Finitions et options":**

1. Tu vois TOUTES les finitions du catalogue
2. Tu coches celles que tu veux activer pour CE produit
3. Tu peux **surcharger le prix** pour ce produit spécifique
4. Tu peux ajouter des **conditions**:
   - Surface min/max (m²)
   - Largeur/Hauteur min (cm)

**Exemple:**
```
☑️ Standard (0€)                    [Actif]
☑️ Contrecollage (+8€/m²)           [Actif]
     └─ Prix pour ce produit: +10€/m² (au lieu de 8€)
     └─ Condition: Surface min 5m²
☐ Découpe forme (+15€)              [Inactif pour ce produit]
```

---

## 🎁 PROMOTIONS AVEC CONDITIONS

### Nouvelles conditions disponibles

Dans `/admin/editer-produit.php` section **"🎁 Promotion":**

**Conditions de base:**
- ✅ Dates début/fin
- ✅ Countdown

**Nouvelles conditions:**
- ✅ **Surface min/max**: Promo active seulement entre X et Y m²
- ✅ **Quantité min**: Client doit commander au moins X unités
- ✅ **Finitions requises**: Promo active seulement si certaines finitions choisies
- ✅ **Finitions exclues**: Promo inactive si certaines finitions choisies
- ✅ **Code promo**: Code optionnel à saisir
- ✅ **Utilisations max**: Limite le nombre d'utilisations

**Exemples de promotions:**
```
Promo 1: -20% sur commandes > 50m²
  └─ Type: Pourcentage (-20%)
  └─ Condition surface min: 50m²

Promo 2: -30€ si finition "Contrecollage" choisie
  └─ Type: Fixe (-30€)
  └─ Finitions requises: [ID_Contrecollage]

Promo 3: Prix spécial 15€/m² SANS découpe forme
  └─ Type: Prix spécial (15€/m²)
  └─ Finitions exclues: [ID_Decoupe]
  └─ Surface min: 10m²
```

---

## 📋 STRUCTURE BASE DE DONNÉES

### Nouvelles tables

**`finitions_catalogue`** - Bibliothèque globale
```sql
- nom, description, categorie
- prix_defaut, type_prix_defaut
- icone, actif, ordre
```

**`produits_finitions`** - Finitions par produit (avec conditions)
```sql
- produit_id, finition_catalogue_id (lien)
- nom, prix_supplement, type_prix
- condition_surface_min, condition_surface_max
- condition_largeur_min, condition_hauteur_min
```

**`promotions`** - Promotions avec conditions étendues
```sql
- Champs existants: type, valeur, dates, countdown
- NOUVEAUX: condition_surface_min/max
- NOUVEAUX: condition_quantite_min
- NOUVEAUX: condition_finitions (JSON)
- NOUVEAUX: condition_sans_finitions (JSON)
- NOUVEAUX: code_promo, utilisation_max
```

---

## 🔄 WORKFLOW RECOMMANDÉ

### 1. Configurer ton catalogue de finitions

```
/admin/finitions-catalogue.php
└─ Crée TOUTES tes finitions possibles
└─ Définis prix par défaut et catégories
└─ Active/Désactive selon tes besoins
```

### 2. Éditer tes produits

```
/admin/editer-produit.php?id=FX-2MM
└─ Section "🎨 Finitions et options"
└─ Coche les finitions que tu veux activer
└─ Surcharg

e le prix si besoin
└─ Ajoute des conditions (surface min/max)
```

### 3. Créer tes promotions

```
Même page, section "🎁 Promotion"
└─ Active la promo
└─ Choisis le type (%, fixe, prix spécial)
└─ Ajoute des conditions:
   - Surface min 50m²
   - Avec finition "Contrecollage"
   - Sans finition "Découpe forme"
   - Code promo "NOEL2024"
```

---

## 💡 CAS D'USAGE

### Cas 1: Finition universelle "Livraison express"

**Dans le catalogue:**
```
Nom: Livraison express
Catégorie: Tous
Prix: +30€ (fixe)
```
→ Apparaît automatiquement sur TOUS les produits

### Cas 2: Réduction si client fournit fichier

**Dans le catalogue:**
```
Nom: Fichier fourni
Prix: -10€ (fixe)
Description: Le client fournit son fichier prêt
```
→ Prix négatif = réduction !

### Cas 3: Promo sur grosses commandes avec contrecollage

**Dans l'édition produit:**
```
Promotion:
  Type: Pourcentage -25%
  Surface min: 100m²
  Finitions requises: [Contrecollage]
  Badge: "PROMO GROS VOLUME"
```
→ Client doit commander >100m² AVEC contrecollage pour avoir -25%

### Cas 4: Prix dégressif selon surface

**Créer 3 promotions:**
```
Promo 1: -10% si 20-50m²
Promo 2: -15% si 50-100m²
Promo 3: -20% si >100m²
```

---

## ⚙️ PARAMÈTRES AVANCÉS

### Finitions avec conditions

Tu peux limiter l'affichage d'une finition selon:
- **Surface**: Ex: "Contrecollage" disponible seulement >5m²
- **Dimensions**: Ex: "Cadre" disponible seulement si largeur >60cm

### Promotions avec code

```
Code promo: NOEL2024
Utilisations max: 100
```
→ Le code expire après 100 utilisations

---

## 🆘 FAQ

**Q: Mes anciennes finitions vont disparaître ?**
R: Non ! Les finitions déjà activées sur tes produits restent. Le catalogue est juste une bibliothèque pour en ajouter de nouvelles.

**Q: Différence entre "activer" et "créer" une finition ?**
R:
- **Créer** = Ajouter au catalogue global (une seule fois)
- **Activer** = Cocher pour un produit spécifique (autant de fois que tu veux)

**Q: Pourquoi mettre "Catégorie: Tous" ?**
R: Pour que la finition apparaisse automatiquement sur tous les produits (ex: Livraison express)

**Q: Prix négatif ?**
R: Oui ! Utilisé pour les réductions (ex: -10€ si le client fournit son fichier)

**Q: Conditions JSON pour promotions ?**
R: C'est automatique. Quand tu choisis des finitions dans l'interface, elles sont stockées en JSON [1,2,3]

---

## ✨ AVANTAGES DE CE SYSTÈME

### Avant:
```
PVC-2MM: Standard, Contrecollage, Découpe (forcé)
PVC-3MM: Standard, Contrecollage, Découpe (forcé)
ALU-1MM: Standard, Contrecollage, Découpe (pas adapté !)
```

### Maintenant:
```
PVC-2MM: ☑️ Standard, ☑️ Contrecollage, ☐ Découpe
PVC-3MM: ☑️ Standard, ☐ Contrecollage, ☑️ Découpe
ALU-1MM: ☑️ Standard Alu, ☑️ Perçage, ☑️ Cadre noir
```

**TU CHOISIS TOUT !** 🎉

---

## 📞 Support

Fichiers modifiés:
- `/admin/migration-update-database-v2.sql` - Migration BDD
- `/admin/finitions-catalogue.php` - Gestion catalogue
- `/admin/finition-editer.php` - Ajouter/Éditer finition
- `/admin/editer-produit.php` - Sélection finitions par produit

**Tout est prêt ! Lance juste la migration et c'est parti ! 🚀**
