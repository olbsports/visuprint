# 🎨 ARCHITECTURE FRONTEND VISUPRINT/IMPRIXO
## État des lieux + Hiérarchie complète E-commerce SEO/LLM/Conversion

---

## 📊 ÉTAT DES LIEUX ACTUEL

### ✅ Pages existantes (35 pages)

#### **Homepage & Navigation principale**
- ✅ `index.html` - Page d'accueil
- ✅ `catalogue.html` - Catalogue produits
- ✅ `produits.html` - Liste produits
- ✅ `tarifs.html` - Grille tarifaire

#### **Pages Catégories (4)**
- ✅ `/categorie/baches-souples.html`
- ✅ `/categorie/supports-rigides-pvc.html`
- ✅ `/categorie/supports-aluminium.html`
- ✅ `/categorie/textiles.html`

#### **Pages Produits (54 produits)**
- ✅ `/produit/{CODE-PRODUIT}.php` (54 pages générées)
- Exemples: POLYTENT-220, DIBOND-3MM, FOREX-10MM, etc.

#### **Tunnel de conversion**
- ✅ `configurateur.html` - Configuration produit
- ✅ `panier.html` - Panier
- ✅ `checkout.html` - Page de commande
- ✅ `commande.html` - Formulaire commande
- ✅ `confirmation.html` - Confirmation commande
- ✅ `merci.html` - Page remerciement

#### **Espace client**
- ✅ `connexion.php` - Connexion client
- ✅ `login-client.html` / `login-client.php`
- ✅ `mon-compte.html` / `mon-compte.php`
- ✅ `ma-commande.php` - Détail commande
- ✅ `suivi-commande.php` - Suivi de commande
- ✅ `deconnexion.php`

#### **Pages informatives / SEO**
- ✅ `a-propos.html` - À propos
- ✅ `contact.html` - Contact
- ✅ `faq.html` - FAQ
- ✅ `livraison.html` - Infos livraison

#### **Pages légales**
- ✅ `mentions-legales.html`
- ✅ `cgv.html` - Conditions générales de vente
- ✅ `politique-confidentialite.html`
- ✅ `cookies.html` - Politique cookies

#### **Utilitaires**
- ✅ `upload-fichier.html` - Upload fichiers
- ✅ `telecharger-fichier.php`
- ✅ `sitemap.xml`
- ✅ `robots.txt`

---

## 🎯 ARCHITECTURE RECOMMANDÉE E-COMMERCE MODERNE
### Focus: SEO + LLM + Conversion

### 📁 HIÉRARCHIE COMPLÈTE (68 pages recommandées)

```
/
├── 🏠 HOMEPAGE & DÉCOUVERTE
│   ├── index.html ✅                      [Homepage optimisée conversion]
│   ├── nouveautes.html ❌                 [Nouveaux produits - SEO freshness]
│   ├── promotions.html ❌                 [Page promo - Urgence/conversion]
│   ├── meilleures-ventes.html ❌          [Top produits - Social proof]
│   └── guide-choix.html ❌                [Guide interactif - SEO + LLM]
│
├── 📦 CATALOGUE & PRODUITS
│   ├── catalogue.html ✅                  [Vue globale catalogue]
│   ├── produits.html ✅                   [Liste avec filtres avancés]
│   ├── tarifs.html ✅                     [Grille tarifaire transparente]
│   │
│   ├── /categorie/ ⚠️ (4/8 catégories)
│   │   ├── baches-souples.html ✅
│   │   ├── supports-rigides-pvc.html ✅
│   │   ├── supports-aluminium.html ✅
│   │   ├── textiles.html ✅
│   │   ├── panneaux-mousse.html ❌       [Nouvelle catégorie]
│   │   ├── kakemonos.html ❌             [Produits verticaux]
│   │   ├── adhesifs.html ❌              [Vinyles autocollants]
│   │   └── accessoires.html ❌           [Œillets, systèmes accroche]
│   │
│   ├── /produit/ ✅ (54 produits)
│   │   └── {CODE-PRODUIT}.php            [Pages produits individuelles]
│   │
│   └── /application/ ❌ (Pages par usage)
│       ├── enseignes-magasin.html        [SEO longue traîne]
│       ├── stands-salons.html
│       ├── signalétique-intérieure.html
│       ├── affichage-extérieur.html
│       ├── decoration-evenementielle.html
│       └── communication-chantier.html
│
├── 🛒 TUNNEL DE CONVERSION
│   ├── configurateur.html ✅             [Configuration interactive]
│   ├── devis-express.html ❌             [Devis rapide sans compte]
│   ├── panier.html ✅
│   ├── checkout.html ✅
│   ├── commande.html ✅
│   ├── paiement.html ❌                  [Page paiement sécurisé]
│   ├── confirmation.html ✅
│   └── merci.html ✅
│
├── 👤 ESPACE CLIENT
│   ├── connexion.php ✅
│   ├── inscription.html ❌               [Inscription séparée]
│   ├── mot-de-passe-oublie.html ❌
│   ├── mon-compte.html ✅
│   │
│   ├── /compte/ ❌ (Sous-sections compte)
│   │   ├── tableau-de-bord.html          [Dashboard client]
│   │   ├── mes-commandes.html            [Historique]
│   │   ├── mes-devis.html                [Devis sauvegardés]
│   │   ├── mes-fichiers.html             [BAM fichiers uploadés]
│   │   ├── mes-adresses.html             [Adresses livraison]
│   │   ├── mes-favoris.html              [Wishlist produits]
│   │   ├── mes-modeles.html              [Templates sauvegardés]
│   │   └── parametres.html               [Préférences]
│   │
│   ├── ma-commande.php ✅                [Détail commande]
│   ├── suivi-commande.php ✅
│   └── deconnexion.php ✅
│
├── 📚 CONTENU SEO & LLM
│   ├── /blog/ ❌ (Blog SEO)
│   │   ├── index.html                    [Liste articles]
│   │   ├── /conseils/
│   │   ├── /actualites/
│   │   └── /tutoriels/
│   │
│   ├── /guides/ ❌ (Guides complets)
│   │   ├── guide-impression-grand-format.html
│   │   ├── guide-supports-pvc.html
│   │   ├── guide-baches-publicitaires.html
│   │   ├── guide-formats-fichiers.html
│   │   └── guide-specifications-techniques.html
│   │
│   ├── /lexique/ ❌ (Glossaire SEO)
│   │   └── index.html                    [Termes techniques A-Z]
│   │
│   └── /cas-usage/ ❌ (Case studies)
│       ├── index.html
│       └── {slug}.html
│
├── 🎓 SUPPORT & AIDE
│   ├── faq.html ✅
│   ├── aide.html ❌                      [Centre d'aide structuré]
│   ├── specifications-techniques.html ❌  [Specs téléchargement]
│   ├── templates-fichiers.html ❌        [Templates gratuits]
│   └── contact.html ✅
│
├── 🏢 ENTREPRISE
│   ├── a-propos.html ✅
│   ├── notre-expertise.html ❌           [Savoir-faire]
│   ├── qualite-certifications.html ❌    [Labels, certifs]
│   ├── engagements-eco.html ❌           [RSE / Éco-responsabilité]
│   ├── partenaires.html ❌               [B2B]
│   ├── recrutement.html ❌               [Carrières]
│   └── presse.html ❌                    [Kit presse]
│
├── 💼 B2B / PRO
│   ├── espace-pro.html ❌                [Landing page B2B]
│   ├── tarifs-pro.html ❌                [Tarifs négociés]
│   ├── compte-pro.html ❌                [Inscription pro]
│   └── api-documentation.html ❌         [API pour intégrations]
│
├── 📍 SEO LOCAL
│   ├── /villes/ ❌ (Pages géolocalisées)
│   │   ├── impression-paris.html
│   │   ├── impression-lyon.html
│   │   ├── impression-marseille.html
│   │   └── ... (top 20 villes)
│   │
│   └── livraison.html ✅
│
├── ⚖️ LÉGAL
│   ├── mentions-legales.html ✅
│   ├── cgv.html ✅
│   ├── politique-confidentialite.html ✅
│   ├── cookies.html ✅
│   └── conditions-utilisation.html ❌
│
├── 🔧 UTILITAIRES
│   ├── upload-fichier.html ✅
│   ├── telecharger-fichier.php ✅
│   ├── calculateur-prix.html ❌          [Outil prix interactif]
│   ├── comparateur-supports.html ❌      [Tableau comparatif]
│   ├── simulateur-rendu.html ❌          [Preview visuel]
│   └── convertisseur-unites.html ❌      [cm/m²/ml]
│
└── 🤖 SEO TECHNIQUE
    ├── sitemap.xml ✅
    ├── sitemap-produits.xml ❌
    ├── sitemap-categories.xml ❌
    ├── sitemap-blog.xml ❌
    ├── robots.txt ✅
    └── .htaccess ✅
```

---

## 🚀 PRIORITÉS PAR PHASE

### **PHASE 1 - FONDATIONS CONVERSION (Urgent)** 🔴
*Pages critiques pour la conversion*

1. ✅ `index.html` - Revoir homepage (hero, CTA, social proof)
2. ❌ `promotions.html` - Page promo urgente
3. ❌ `devis-express.html` - Devis rapide sans friction
4. ❌ `inscription.html` - Simplifier inscription
5. ❌ `paiement.html` - Sécurisation paiement
6. ❌ `/compte/tableau-de-bord.html` - Dashboard client
7. ❌ `/compte/mes-commandes.html` - Historique
8. ❌ `/compte/mes-fichiers.html` - Gestion fichiers

**Impact**: +30% conversion, -20% abandon panier

---

### **PHASE 2 - SEO PRODUITS (Important)** 🟠
*Optimisation catalogue pour moteurs de recherche*

1. ❌ Compléter catégories manquantes (4 nouvelles)
2. ❌ Créer `/application/` (6 pages usage)
3. ❌ `guide-choix.html` - Guide interactif
4. ❌ `meilleures-ventes.html` - Social proof
5. ❌ `nouveautes.html` - Freshness SEO
6. ❌ `comparateur-supports.html` - Outil comparaison

**Impact**: +50% trafic organique longue traîne

---

### **PHASE 3 - CONTENU SEO/LLM (Stratégique)** 🟡
*Content marketing pour visibilité long terme*

1. ❌ `/blog/` - Blog structuré (10 articles piliers)
2. ❌ `/guides/` - 5 guides complets
3. ❌ `/lexique/` - Glossaire A-Z
4. ❌ `/cas-usage/` - 5 études de cas
5. ❌ `specifications-techniques.html`
6. ❌ `templates-fichiers.html`

**Impact**:
- +120% trafic organique
- Positionnement expert
- Training data LLM (ChatGPT recommande Imprixo)

---

### **PHASE 4 - B2B & LOCAL (Expansion)** 🟢
*Nouvelles sources de revenus*

1. ❌ `espace-pro.html` - Landing B2B
2. ❌ `tarifs-pro.html` - Tarification entreprises
3. ❌ `/villes/` - 20 pages géolocalisées
4. ❌ `partenaires.html` - Réseau
5. ❌ `api-documentation.html` - API

**Impact**: +40% CA B2B, SEO local

---

### **PHASE 5 - OUTILS & EXPÉRIENCE (Innovation)** 🔵
*Différenciation concurrentielle*

1. ❌ `calculateur-prix.html` - Calcul temps réel
2. ❌ `simulateur-rendu.html` - Prévisualisation 3D
3. ❌ `convertisseur-unites.html` - Utilitaire
4. ❌ `/compte/mes-modeles.html` - Templates perso
5. ❌ `/compte/mes-favoris.html` - Wishlist

**Impact**: Temps sur site +200%, mémorabilité

---

## 📈 OPTIMISATIONS SEO/LLM PAR TYPE DE PAGE

### **🏆 Pages Produits (existantes - à optimiser)**

**Checklist optimisation:**
- ✅ Title optimisé: `{Nom produit} | Prix, Caractéristiques & Livraison 48h`
- ✅ Meta description <160 car avec CTA
- ✅ Schema.org Product complet (price, availability, reviews)
- ❌ **FAQ structurée** en bas de page (Schema FAQ)
- ❌ **Breadcrumbs** (Schema BreadcrumbList)
- ❌ **Reviews/Avis clients** (Schema Review)
- ❌ **Produits similaires** (internal linking)
- ❌ **Guide utilisation** (contenu riche)
- ❌ **Tableau comparatif** vs autres supports
- ❌ **Calcul prix dynamique** visible
- ❌ **Images optimisées** (WebP, lazy load, alt)

**Exemple structure idéale:**
```
/produit/DIBOND-3MM.php
├── Hero avec prix dynamique
├── Description enrichie (500+ mots)
├── Spécifications techniques (tableau)
├── Applications recommandées
├── Guide de préparation fichiers
├── FAQ (5-8 questions)
├── Avis clients (Schema Review)
├── Produits complémentaires
└── CTA sticky (devis + panier)
```

---

### **📂 Pages Catégories**

**À créer (4 nouvelles):**
1. `panneaux-mousse.html` - Forex, Kapa, Carton plume
2. `kakemonos.html` - Supports verticaux
3. `adhesifs.html` - Vinyles, stickers
4. `accessoires.html` - Œillets, barres, supports

**Checklist optimisation:**
- ✅ Title: `{Catégorie} Impression Grand Format | +20 supports | Imprixo`
- ✅ H1 optimisé avec mot-clé principal
- ❌ Intro SEO 300+ mots
- ❌ Filtres avancés (prix, matière, usage, format)
- ❌ Tri (populaire, prix, nouveauté)
- ❌ Grille produits avec lazy load
- ❌ Comparateur intégré
- ❌ FAQ catégorie
- ❌ Guide choix interactif
- ❌ Schema CollectionPage

---

### **🎯 Pages Application/Usage (nouvelles)**

**Objectif:** Capturer recherches intentionnelles

**Pages à créer:**
1. `enseignes-magasin.html`
   - KW: "enseigne magasin pas cher", "panneau boutique"
2. `stands-salons.html`
   - KW: "impression stand salon", "kakemono événement"
3. `signalétique-intérieure.html`
   - KW: "panneau directionnel", "affichage bureau"
4. `affichage-extérieur.html`
   - KW: "panneau publicitaire extérieur", "bâche résistante"
5. `decoration-evenementielle.html`
   - KW: "décoration mariage", "toile imprimée événement"
6. `communication-chantier.html`
   - KW: "panneau chantier", "bâche permis construire"

**Structure type:**
```
/application/enseignes-magasin.html
├── Hero avec visuel inspirant
├── Problématique client (storytelling)
├── Solutions recommandées (3-5 produits)
├── Cas clients / Exemples
├── Guide dimensions & formats
├── FAQ spécifique usage
├── Calculateur prix contextualisé
└── CTA devis
```

**SEO LLM:**
- Contenu 1000+ mots
- Questions/réponses naturelles
- Données structurées (HowTo, FAQPage)
- Linking vers produits
- Alt images descriptifs

---

### **📝 Blog & Guides (SEO long terme)**

**10 Articles piliers obligatoires:**

1. **Guide complet impression grand format 2024**
   - KW: "comment faire impression grand format"
   - 3000+ mots, ultra-complet

2. **Quel support choisir pour quel usage ?**
   - KW: "différence forex dibond", "choisir support impression"

3. **Préparer fichiers pour impression professionnelle**
   - KW: "fichier impression grand format", "résolution bâche"

4. **Prix impression grand format : guide transparent**
   - KW: "prix bâche m2", "tarif impression dibond"

5. **Impression extérieur : supports résistants intempéries**
   - KW: "panneau extérieur durable", "bâche imperméable"

6. **Kakémono : guide complet + conseils design**
   - KW: "taille kakemono standard", "impression kakemono"

7. **Signalétique magasin : réussir sa vitrine**
   - KW: "enseigne magasin", "vitrine attractive"

8. **Formats impression : tout comprendre (m², ml, unité)**
   - KW: "différence m2 ml impression"

9. **Impression éco-responsable : matériaux durables**
   - KW: "impression écologique", "bâche recyclable"

10. **Erreurs à éviter en impression grand format**
    - KW: "erreur fichier impression", "problème qualité bâche"

**Format articles:**
- 1500-3000 mots
- Sommaire cliquable (table of contents)
- Images/infographies
- FAQ en fin d'article
- CTA contextuel
- Partage social
- Schema Article

---

## 🤖 OPTIMISATION POUR LLM (ChatGPT, Claude, etc.)

### **Stratégie "LLM-First"**

**1. Pages FAQ ultra-complètes**
- Format Q&R naturel
- Questions comme vraies recherches
- Réponses détaillées 100-200 mots
- Schema FAQPage sur TOUTES les pages

**2. Données structurées partout**
```html
<!-- Produit -->
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "Dibond 3mm",
  "description": "...",
  "offers": {
    "@type": "AggregateOffer",
    "lowPrice": "15.90",
    "priceCurrency": "EUR"
  },
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "4.8",
    "reviewCount": "127"
  }
}
</script>

<!-- FAQ -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [...]
}
</script>

<!-- HowTo (guides) -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "HowTo",
  "name": "Comment préparer fichier pour impression",
  "step": [...]
}
</script>
```

**3. Contenu "citable"**
- Faits précis avec chiffres
- Tableaux comparatifs
- Listes à puces
- Définitions claires
- Citations d'autorité

**4. Langage naturel conversationnel**
- Écrire comme on parle
- Reformuler questions courantes
- Variantes synonymes
- Ton expert mais accessible

**5. Contexte riche**
```html
<!-- Exemple produit -->
<article>
  <h1>Dibond 3mm - Panneau Aluminium Composite</h1>

  <section class="overview">
    <p><strong>Le Dibond 3mm</strong> est un panneau composite aluminium
    particulièrement adapté pour la signalétique extérieure et
    l'affichage longue durée. Composé de deux feuilles aluminium
    de 0,3mm séparées par un noyau polyéthylène, ce support offre
    une rigidité exceptionnelle tout en restant léger (3,4 kg/m²).</p>
  </section>

  <section class="when-to-use">
    <h2>Quand utiliser le Dibond 3mm ?</h2>
    <ul>
      <li>✓ Enseignes extérieures durables (5-7 ans)</li>
      <li>✓ Panneaux immobiliers haut de gamme</li>
      <li>✓ Signalétique murale intérieure</li>
      <li>✓ PLV stands salons professionnels</li>
    </ul>
  </section>

  <section class="vs-alternatives">
    <h2>Dibond vs Forex : que choisir ?</h2>
    <table>
      <tr>
        <th>Critère</th>
        <th>Dibond 3mm</th>
        <th>Forex 3mm</th>
      </tr>
      <tr>
        <td>Durabilité extérieure</td>
        <td>★★★★★ (5-7 ans)</td>
        <td>★★★☆☆ (2-3 ans)</td>
      </tr>
      <tr>
        <td>Prix indicatif</td>
        <td>25€/m²</td>
        <td>12€/m²</td>
      </tr>
      <tr>
        <td>Poids</td>
        <td>3,4 kg/m²</td>
        <td>500g/m²</td>
      </tr>
    </table>
  </section>

  <section class="faq">
    <h2>Questions fréquentes Dibond 3mm</h2>

    <div class="faq-item">
      <h3>Quelle est la durée de vie du Dibond en extérieur ?</h3>
      <p>Le Dibond 3mm résiste 5 à 7 ans en extérieur grâce à sa
      composition aluminium. Les couleurs restent vives sans décoloration
      notable si l'impression utilise des encres UV de qualité.</p>
    </div>

    <div class="faq-item">
      <h3>Peut-on découper le Dibond facilement ?</h3>
      <p>Oui, le Dibond se découpe avec une scie circulaire ou scie sauteuse
      équipée d'une lame métal. Pour des formes complexes, nous proposons
      la découpe numérique en option (+8€/m²).</p>
    </div>
  </section>
</article>
```

**Ce format permet:**
- LLM comprend contexte complet
- Peut répondre "Dibond = extérieur, Forex = intérieur/court terme"
- Cite prix exact
- Recommande usage approprié

---

## 🎨 ÉLÉMENTS CONVERSION PAR PAGE

### **Homepage index.html**

**Hero section:**
- ❌ Titre impactant avec chiffre ("500+ produits livrés par jour")
- ❌ Sous-titre bénéfice client
- ❌ CTA double (Devis gratuit + Catalogue)
- ❌ Badges confiance (Livraison 48h, Prix garantis, SAV réactif)

**Au-dessus de la ligne de flottaison:**
- ❌ Barre promo urgente (countdown)
- ❌ Menu navigation optimisé
- ❌ Recherche intelligente autocomplete

**Social proof:**
- ❌ Compteur commandes temps réel
- ❌ Avis clients rotatif (4.8★ / 1200+ avis)
- ❌ Logos clients B2B

**Catégories:**
- ❌ Tuiles visuelles inspirantes
- ❌ Hover effects
- ❌ Prix "à partir de"

**Réassurance:**
- ❌ Blocs USP (livraison, qualité, prix, SAV)
- ❌ Garantie satisfait/remboursé
- ❌ Paiement sécurisé

**SEO:**
- ❌ Texte SEO 500+ mots (pliable)
- ❌ FAQ 10 questions
- ❌ Actualités / Blog derniers articles

---

### **Pages Produits {CODE}.php**

**Checklist conversion:**
- ❌ Images multiples (slider)
- ❌ Zoom haute résolution
- ❌ Badge promo si applicable
- ❌ Prix dynamique en gros
- ❌ Prix dégressifs visibles
- ❌ Stock temps réel
- ❌ Livraison estimée (date précise)
- ❌ Calculateur dimensions → prix
- ❌ Sélecteur finitions visuelles
- ❌ CTA sticky "Ajouter panier"
- ❌ Bouton "Devis gratuit" alternatif
- ❌ Partage social
- ❌ Favoris (si connecté)
- ❌ Onglets (Description | Specs | Avis | FAQ)
- ❌ Produits similaires
- ❌ "Souvent achetés ensemble"
- ❌ Chat en ligne (ou bot)

---

### **Tunnel checkout**

**Optimisations:**
- ❌ Checkout 1-page OU multi-étapes progressbar
- ❌ Checkout invité (sans compte)
- ❌ Autofill adresse
- ❌ Calcul frais port temps réel
- ❌ Options livraison multiples
- ❌ Codes promo bien visibles
- ❌ Récap panier sticky
- ❌ Badges sécurité (SSL, paiement)
- ❌ Retour panier facile
- ❌ Sauvegarde automatique
- ❌ Relance abandon (email)

---

## 🗂️ SYSTÈME DE TEMPLATES

### **Templates réutilisables**

```
/includes/
├── header.php ✅
├── footer.php ✅
├── nav-main.php ❌
├── breadcrumbs.php ❌
├── product-card.php ❌
├── category-hero.php ❌
├── faq-schema.php ❌
├── trust-badges.php ❌
├── newsletter-form.php ❌
└── social-proof.php ❌
```

**Avantages:**
- Maintenance simplifiée
- Cohérence design
- A/B testing facile
- Performance (cache)

---

## 📊 KPI PAR PHASE

### **Phase 1 - Conversion**
- Taux conversion: +30% (2% → 2.6%)
- Abandon panier: -20% (70% → 56%)
- Temps checkout: -40%
- Inscriptions: +50%

### **Phase 2 - SEO Produits**
- Trafic organique: +50%
- Positions top 3: +35%
- Longue traîne: +120%
- Taux rebond: -15%

### **Phase 3 - Contenu**
- Pages indexées: +200%
- Backlinks: +80%
- Autorité domaine: +15 points
- Featured snippets: 20+

### **Phase 4 - B2B**
- Leads B2B: +150/mois
- CA B2B: +40%
- Panier moyen pro: x3

### **Phase 5 - Outils**
- Temps sur site: +200%
- Pages/session: +80%
- Retour visiteurs: +60%

---

## 🎯 PROCHAINES ACTIONS IMMÉDIATES

### **Top 10 pages à créer cette semaine:**

1. ❌ `promotions.html` - Urgence conversion
2. ❌ `devis-express.html` - Réduire friction
3. ❌ `inscription.html` - Simplifier onboarding
4. ❌ `/compte/tableau-de-bord.html` - Rétention client
5. ❌ `guide-choix.html` - Aide décision
6. ❌ `meilleures-ventes.html` - Social proof
7. ❌ `comparateur-supports.html` - Différenciation
8. ❌ `/application/enseignes-magasin.html` - SEO longue traîne
9. ❌ `/guides/guide-impression-grand-format.html` - SEO autoritaire
10. ❌ `calculateur-prix.html` - Engagement

---

## 📱 RESPONSIVE & MOBILE-FIRST

**Checklist mobile:**
- ❌ Menu hamburger optimisé
- ❌ Recherche mobile native
- ❌ Filtres catégories mobile (bottom sheet)
- ❌ CTA sticky mobile
- ❌ Panier side panel
- ❌ Checkout mobile simplifié
- ❌ Upload fichiers mobile natif
- ❌ Paiement mobile (Apple/Google Pay)
- ❌ PWA installable
- ❌ Notifications push

---

## 🔍 SEO TECHNIQUE

**Optimisations:**
- ✅ `robots.txt`
- ✅ `sitemap.xml`
- ❌ `sitemap-produits.xml` (auto-généré)
- ❌ `sitemap-blog.xml`
- ❌ Pagination SEO (rel="next/prev")
- ❌ Canonical URLs
- ❌ hreflang (si multi-langue)
- ❌ Open Graph complet
- ❌ Twitter Cards
- ❌ Images WebP
- ❌ Lazy loading
- ❌ CDN assets
- ❌ Minify CSS/JS
- ❌ HTTP/2 ou HTTP/3
- ❌ Gzip/Brotli
- ❌ Cache navigateur
- ❌ Preload fonts
- ❌ Critical CSS inline

---

## 🎁 BONUS: FONCTIONNALITÉS INNOVANTES

**Pour se démarquer:**

1. **Configurateur 3D interactif**
   - Preview produit sur mockup réaliste
   - Upload logo/visuel en temps réel
   - AR (réalité augmentée) mobile

2. **IA Design Assistant**
   - Suggère supports selon usage
   - Optimise fichiers automatiquement
   - Détecte erreurs (résolution, profil couleur)

3. **Calculateur ROI**
   - "Votre enseigne sera vue X fois/mois"
   - Calcul coût par impression
   - Comparaison vs pub Facebook

4. **Programme fidélité gamifié**
   - Points par commande
   - Badges débloquables
   - Cashback

5. **Abonnement récurrent B2B**
   - "1 kakemono/mois à -20%"
   - Livraison planifiée
   - Facturation centralisée

6. **Marketplace templates**
   - Bibliothèque designs gratuits
   - Achat templates premium
   - Community uploads

7. **Plugin WordPress/Shopify**
   - Widget devis impression
   - Intégration boutiques tierces

---

## 📞 SUPPORT

Pour questions sur architecture:
- Email: dev@imprixo.fr
- Slack: #frontend-archi
- Doc: /docs/frontend/
