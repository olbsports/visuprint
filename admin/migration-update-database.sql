-- =====================================================
-- MIGRATION: Ajout des nouvelles fonctionnalités
-- Base de données: ispy2055_imprixo_ecommerce
-- =====================================================
-- Ce script ajoute les nouvelles tables et colonnes
-- SANS supprimer les produits existants
-- =====================================================

-- =====================================================
-- 1. AJOUT DES COLONNES MANQUANTES À LA TABLE PRODUITS
-- =====================================================

-- Vérifier et ajouter les colonnes si elles n'existent pas
ALTER TABLE `produits`
ADD COLUMN IF NOT EXISTS `image_url` VARCHAR(512) DEFAULT NULL AFTER `description_longue`,
ADD COLUMN IF NOT EXISTS `actif` BOOLEAN DEFAULT 1 AFTER `unite_vente`,
ADD COLUMN IF NOT EXISTS `nouveau` BOOLEAN DEFAULT 0 AFTER `actif`,
ADD COLUMN IF NOT EXISTS `best_seller` BOOLEAN DEFAULT 0 AFTER `nouveau`,
ADD COLUMN IF NOT EXISTS `meta_title` VARCHAR(255) DEFAULT NULL AFTER `best_seller`,
ADD COLUMN IF NOT EXISTS `meta_description` TEXT DEFAULT NULL AFTER `meta_title`,
ADD COLUMN IF NOT EXISTS `meta_keywords` TEXT DEFAULT NULL AFTER `meta_description`,
ADD COLUMN IF NOT EXISTS `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER `meta_keywords`,
ADD COLUMN IF NOT EXISTS `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;

-- =====================================================
-- 2. CRÉATION TABLE FINITIONS (si elle n'existe pas)
-- =====================================================

CREATE TABLE IF NOT EXISTS `produits_finitions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `produit_id` INT NOT NULL,
  `nom` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `prix_supplement` DECIMAL(10,2) DEFAULT 0,
  `type_prix` ENUM('fixe', 'par_m2', 'par_ml') DEFAULT 'fixe',
  `ordre` INT DEFAULT 0,
  `actif` BOOLEAN DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`produit_id`) REFERENCES `produits`(`id`) ON DELETE CASCADE,
  INDEX `idx_produit_finitions` (`produit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 3. CRÉATION TABLE PROMOTIONS (si elle n'existe pas)
-- =====================================================

CREATE TABLE IF NOT EXISTS `promotions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `produit_id` INT NOT NULL,
  `type` ENUM('pourcentage', 'fixe', 'prix_special') DEFAULT 'pourcentage',
  `valeur` DECIMAL(10,2) NOT NULL COMMENT 'Valeur de la réduction (% ou €)',
  `prix_special` DECIMAL(10,2) DEFAULT NULL COMMENT 'Prix spécial si type = prix_special',
  `titre` VARCHAR(255) DEFAULT NULL COMMENT 'Titre de la promotion',
  `badge_texte` VARCHAR(50) DEFAULT 'PROMO' COMMENT 'Texte du badge',
  `badge_couleur` VARCHAR(7) DEFAULT '#e63946' COMMENT 'Couleur hexa du badge',
  `date_debut` DATETIME DEFAULT NULL,
  `date_fin` DATETIME DEFAULT NULL,
  `afficher_countdown` BOOLEAN DEFAULT 0,
  `code_promo` VARCHAR(50) DEFAULT NULL COMMENT 'Code promo optionnel',
  `actif` BOOLEAN DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`produit_id`) REFERENCES `produits`(`id`) ON DELETE CASCADE,
  INDEX `idx_produit_promo` (`produit_id`),
  INDEX `idx_promo_dates` (`date_debut`, `date_fin`),
  INDEX `idx_promo_actif` (`actif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 4. CRÉATION TABLE FORMATS (si elle n'existe pas)
-- =====================================================

CREATE TABLE IF NOT EXISTS `produits_formats` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `produit_id` INT NOT NULL,
  `nom` VARCHAR(100) NOT NULL,
  `largeur_cm` DECIMAL(10,2) NOT NULL,
  `hauteur_cm` DECIMAL(10,2) NOT NULL,
  `actif` BOOLEAN DEFAULT 1,
  `ordre` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`produit_id`) REFERENCES `produits`(`id`) ON DELETE CASCADE,
  INDEX `idx_produit_formats` (`produit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 5. CRÉATION TABLE HISTORIQUE (si elle n'existe pas)
-- =====================================================

CREATE TABLE IF NOT EXISTS `produits_historique` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `produit_id` INT NOT NULL,
  `admin_id` INT DEFAULT NULL,
  `action` VARCHAR(50) NOT NULL COMMENT 'creation, modification, suppression',
  `champs_modifies` JSON DEFAULT NULL COMMENT 'Détails des modifications',
  `ancienne_valeur` TEXT DEFAULT NULL,
  `nouvelle_valeur` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`produit_id`) REFERENCES `produits`(`id`) ON DELETE CASCADE,
  INDEX `idx_produit_historique` (`produit_id`),
  INDEX `idx_historique_date` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 6. CRÉATION TABLE ADMIN_USERS (si elle n'existe pas)
-- =====================================================

CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(255) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `nom` VARCHAR(100) DEFAULT NULL,
  `prenom` VARCHAR(100) DEFAULT NULL,
  `role` ENUM('super_admin', 'admin', 'editeur') DEFAULT 'admin',
  `actif` BOOLEAN DEFAULT 1,
  `derniere_connexion` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_email` (`email`),
  INDEX `idx_actif` (`actif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insérer admin par défaut (seulement s'il n'existe pas)
INSERT IGNORE INTO `admin_users` (`email`, `password`, `nom`, `prenom`, `role`)
VALUES ('admin@imprixo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'Imprixo', 'super_admin');
-- Mot de passe par défaut: admin123 (À CHANGER!)

-- =====================================================
-- 7. CRÉATION VUE PRODUITS AVEC PROMOTIONS
-- =====================================================

-- Supprimer la vue si elle existe déjà
DROP VIEW IF EXISTS `v_produits_avec_promos`;

-- Créer la vue
CREATE VIEW `v_produits_avec_promos` AS
SELECT
    p.*,
    pr.id as promo_id,
    pr.type as promo_type,
    pr.valeur as promo_valeur,
    pr.prix_special as promo_prix_special,
    pr.titre as promo_titre,
    pr.badge_texte as promo_badge,
    pr.badge_couleur as promo_couleur,
    pr.date_debut as promo_date_debut,
    pr.date_fin as promo_date_fin,
    pr.afficher_countdown as promo_countdown,

    -- Calcul du prix promotionnel pour chaque tranche
    CASE
        WHEN pr.actif = 1 AND (pr.date_debut IS NULL OR pr.date_debut <= NOW()) AND (pr.date_fin IS NULL OR pr.date_fin >= NOW()) THEN
            CASE pr.type
                WHEN 'pourcentage' THEN ROUND(p.prix_0_10 * (1 - pr.valeur / 100), 2)
                WHEN 'fixe' THEN GREATEST(0, p.prix_0_10 - pr.valeur)
                WHEN 'prix_special' THEN pr.prix_special
                ELSE p.prix_0_10
            END
        ELSE p.prix_0_10
    END as prix_0_10_promo,

    CASE
        WHEN pr.actif = 1 AND (pr.date_debut IS NULL OR pr.date_debut <= NOW()) AND (pr.date_fin IS NULL OR pr.date_fin >= NOW()) THEN
            CASE pr.type
                WHEN 'pourcentage' THEN ROUND(p.prix_11_50 * (1 - pr.valeur / 100), 2)
                WHEN 'fixe' THEN GREATEST(0, p.prix_11_50 - pr.valeur)
                WHEN 'prix_special' THEN pr.prix_special
                ELSE p.prix_11_50
            END
        ELSE p.prix_11_50
    END as prix_11_50_promo,

    CASE
        WHEN pr.actif = 1 AND (pr.date_debut IS NULL OR pr.date_debut <= NOW()) AND (pr.date_fin IS NULL OR pr.date_fin >= NOW()) THEN
            CASE pr.type
                WHEN 'pourcentage' THEN ROUND(p.prix_51_100 * (1 - pr.valeur / 100), 2)
                WHEN 'fixe' THEN GREATEST(0, p.prix_51_100 - pr.valeur)
                WHEN 'prix_special' THEN pr.prix_special
                ELSE p.prix_51_100
            END
        ELSE p.prix_51_100
    END as prix_51_100_promo,

    CASE
        WHEN pr.actif = 1 AND (pr.date_debut IS NULL OR pr.date_debut <= NOW()) AND (pr.date_fin IS NULL OR pr.date_fin >= NOW()) THEN
            CASE pr.type
                WHEN 'pourcentage' THEN ROUND(p.prix_101_300 * (1 - pr.valeur / 100), 2)
                WHEN 'fixe' THEN GREATEST(0, p.prix_101_300 - pr.valeur)
                WHEN 'prix_special' THEN pr.prix_special
                ELSE p.prix_101_300
            END
        ELSE p.prix_101_300
    END as prix_101_300_promo,

    CASE
        WHEN pr.actif = 1 AND (pr.date_debut IS NULL OR pr.date_debut <= NOW()) AND (pr.date_fin IS NULL OR pr.date_fin >= NOW()) THEN
            CASE pr.type
                WHEN 'pourcentage' THEN ROUND(p.prix_300_plus * (1 - pr.valeur / 100), 2)
                WHEN 'fixe' THEN GREATEST(0, p.prix_300_plus - pr.valeur)
                WHEN 'prix_special' THEN pr.prix_special
                ELSE p.prix_300_plus
            END
        ELSE p.prix_300_plus
    END as prix_300_plus_promo

FROM `produits` p
LEFT JOIN `promotions` pr ON pr.produit_id = p.id
    AND pr.actif = 1
    AND (pr.date_debut IS NULL OR pr.date_debut <= NOW())
    AND (pr.date_fin IS NULL OR pr.date_fin >= NOW());

-- =====================================================
-- 8. CRÉATION DES FINITIONS PAR DÉFAUT
-- =====================================================

-- Finitions pour les produits PVC (si pas déjà présentes)
INSERT IGNORE INTO `produits_finitions` (`produit_id`, `nom`, `description`, `prix_supplement`, `type_prix`, `ordre`)
SELECT
    p.id,
    'Standard',
    'Inclus',
    0,
    'fixe',
    0
FROM `produits` p
WHERE p.categorie LIKE '%PVC%'
AND NOT EXISTS (
    SELECT 1 FROM `produits_finitions` pf WHERE pf.produit_id = p.id
);

INSERT IGNORE INTO `produits_finitions` (`produit_id`, `nom`, `description`, `prix_supplement`, `type_prix`, `ordre`)
SELECT
    p.id,
    'Contrecollage',
    '+8€/m²',
    8,
    'par_m2',
    1
FROM `produits` p
WHERE p.categorie LIKE '%PVC%'
AND NOT EXISTS (
    SELECT 1 FROM `produits_finitions` pf WHERE pf.produit_id = p.id AND pf.nom = 'Contrecollage'
);

INSERT IGNORE INTO `produits_finitions` (`produit_id`, `nom`, `description`, `prix_supplement`, `type_prix`, `ordre`)
SELECT
    p.id,
    'Découpe forme',
    '+15€',
    15,
    'fixe',
    2
FROM `produits` p
WHERE p.categorie LIKE '%PVC%'
AND NOT EXISTS (
    SELECT 1 FROM `produits_finitions` pf WHERE pf.produit_id = p.id AND pf.nom = 'Découpe forme'
);

-- Finitions pour les produits Aluminium
INSERT IGNORE INTO `produits_finitions` (`produit_id`, `nom`, `description`, `prix_supplement`, `type_prix`, `ordre`)
SELECT
    p.id,
    'Standard',
    'Inclus',
    0,
    'fixe',
    0
FROM `produits` p
WHERE p.categorie LIKE '%Aluminium%'
AND NOT EXISTS (
    SELECT 1 FROM `produits_finitions` pf WHERE pf.produit_id = p.id
);

INSERT IGNORE INTO `produits_finitions` (`produit_id`, `nom`, `description`, `prix_supplement`, `type_prix`, `ordre`)
SELECT
    p.id,
    'Perçage',
    '+3€',
    3,
    'fixe',
    1
FROM `produits` p
WHERE p.categorie LIKE '%Aluminium%'
AND NOT EXISTS (
    SELECT 1 FROM `produits_finitions` pf WHERE pf.produit_id = p.id AND pf.nom = 'Perçage'
);

INSERT IGNORE INTO `produits_finitions` (`produit_id`, `nom`, `description`, `prix_supplement`, `type_prix`, `ordre`)
SELECT
    p.id,
    'Cadre',
    '+25€',
    25,
    'fixe',
    2
FROM `produits` p
WHERE p.categorie LIKE '%Aluminium%'
AND NOT EXISTS (
    SELECT 1 FROM `produits_finitions` pf WHERE pf.produit_id = p.id AND pf.nom = 'Cadre'
);

-- Finitions pour les bâches
INSERT IGNORE INTO `produits_finitions` (`produit_id`, `nom`, `description`, `prix_supplement`, `type_prix`, `ordre`)
SELECT
    p.id,
    'Standard',
    'Inclus',
    0,
    'fixe',
    0
FROM `produits` p
WHERE p.categorie LIKE '%Bâche%' OR p.categorie LIKE '%bache%'
AND NOT EXISTS (
    SELECT 1 FROM `produits_finitions` pf WHERE pf.produit_id = p.id
);

INSERT IGNORE INTO `produits_finitions` (`produit_id`, `nom`, `description`, `prix_supplement`, `type_prix`, `ordre`)
SELECT
    p.id,
    'Œillets',
    'Tous les 50cm',
    5,
    'par_ml',
    1
FROM `produits` p
WHERE p.categorie LIKE '%Bâche%' OR p.categorie LIKE '%bache%'
AND NOT EXISTS (
    SELECT 1 FROM `produits_finitions` pf WHERE pf.produit_id = p.id AND pf.nom = 'Œillets'
);

INSERT IGNORE INTO `produits_finitions` (`produit_id`, `nom`, `description`, `prix_supplement`, `type_prix`, `ordre`)
SELECT
    p.id,
    'Ourlet',
    '+2€/ml',
    2,
    'par_ml',
    2
FROM `produits` p
WHERE p.categorie LIKE '%Bâche%' OR p.categorie LIKE '%bache%'
AND NOT EXISTS (
    SELECT 1 FROM `produits_finitions` pf WHERE pf.produit_id = p.id AND pf.nom = 'Ourlet'
);

-- Finitions pour les textiles
INSERT IGNORE INTO `produits_finitions` (`produit_id`, `nom`, `description`, `prix_supplement`, `type_prix`, `ordre`)
SELECT
    p.id,
    'Standard',
    'Inclus',
    0,
    'fixe',
    0
FROM `produits` p
WHERE p.categorie LIKE '%Textile%'
AND NOT EXISTS (
    SELECT 1 FROM `produits_finitions` pf WHERE pf.produit_id = p.id
);

INSERT IGNORE INTO `produits_finitions` (`produit_id`, `nom`, `description`, `prix_supplement`, `type_prix`, `ordre`)
SELECT
    p.id,
    'Baguettes',
    '+8€',
    8,
    'fixe',
    1
FROM `produits` p
WHERE p.categorie LIKE '%Textile%'
AND NOT EXISTS (
    SELECT 1 FROM `produits_finitions` pf WHERE pf.produit_id = p.id AND pf.nom = 'Baguettes'
);

INSERT IGNORE INTO `produits_finitions` (`produit_id`, `nom`, `description`, `prix_supplement`, `type_prix`, `ordre`)
SELECT
    p.id,
    'Confection',
    '+12€',
    12,
    'fixe',
    2
FROM `produits` p
WHERE p.categorie LIKE '%Textile%'
AND NOT EXISTS (
    SELECT 1 FROM `produits_finitions` pf WHERE pf.produit_id = p.id AND pf.nom = 'Confection'
);

-- =====================================================
-- MIGRATION TERMINÉE ✅
-- =====================================================
-- Les tables et colonnes ont été ajoutées
-- Vos produits existants n'ont PAS été modifiés
-- Des finitions par défaut ont été créées selon les catégories
-- =====================================================
