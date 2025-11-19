-- =====================================================
-- MIGRATION V2: Système flexible avec contrôle total
-- Base de données: ispy2055_imprixo_ecommerce
-- =====================================================
-- SANS finitions automatiques
-- AVEC catalogue global de finitions
-- AVEC conditions sur promotions
-- =====================================================

-- =====================================================
-- 1. AJOUT DES COLONNES MANQUANTES À LA TABLE PRODUITS
-- =====================================================

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
-- 2. CRÉATION TABLE CATALOGUE FINITIONS GLOBALES
-- =====================================================

CREATE TABLE IF NOT EXISTS `finitions_catalogue` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nom` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `categorie` VARCHAR(100) DEFAULT NULL COMMENT 'PVC, Aluminium, Bâche, Textile, Tous',
  `prix_defaut` DECIMAL(10,2) DEFAULT 0,
  `type_prix_defaut` ENUM('fixe', 'par_m2', 'par_ml', 'pourcentage') DEFAULT 'fixe',
  `icone` VARCHAR(50) DEFAULT NULL COMMENT 'Emoji ou classe CSS',
  `actif` BOOLEAN DEFAULT 1,
  `ordre` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_categorie` (`categorie`),
  INDEX `idx_actif` (`actif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 3. CRÉATION TABLE FINITIONS PAR PRODUIT (avec conditions)
-- =====================================================

CREATE TABLE IF NOT EXISTS `produits_finitions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `produit_id` INT NOT NULL,
  `finition_catalogue_id` INT DEFAULT NULL COMMENT 'Lien vers catalogue ou NULL si custom',
  `nom` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `prix_supplement` DECIMAL(10,2) DEFAULT 0,
  `type_prix` ENUM('fixe', 'par_m2', 'par_ml', 'pourcentage') DEFAULT 'fixe',

  -- Conditions d'affichage
  `condition_surface_min` DECIMAL(10,2) DEFAULT NULL COMMENT 'Surface minimum en m²',
  `condition_surface_max` DECIMAL(10,2) DEFAULT NULL COMMENT 'Surface maximum en m²',
  `condition_largeur_min` DECIMAL(10,2) DEFAULT NULL COMMENT 'Largeur min en cm',
  `condition_hauteur_min` DECIMAL(10,2) DEFAULT NULL COMMENT 'Hauteur min en cm',

  `actif` BOOLEAN DEFAULT 1,
  `ordre` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`produit_id`) REFERENCES `produits`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`finition_catalogue_id`) REFERENCES `finitions_catalogue`(`id`) ON DELETE SET NULL,
  INDEX `idx_produit_finitions` (`produit_id`),
  INDEX `idx_catalogue` (`finition_catalogue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 4. CRÉATION TABLE PROMOTIONS (avec conditions étendues)
-- =====================================================

CREATE TABLE IF NOT EXISTS `promotions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `produit_id` INT NOT NULL,
  `type` ENUM('pourcentage', 'fixe', 'prix_special') DEFAULT 'pourcentage',
  `valeur` DECIMAL(10,2) NOT NULL COMMENT 'Valeur de la réduction (% ou €)',
  `prix_special` DECIMAL(10,2) DEFAULT NULL COMMENT 'Prix spécial si type = prix_special',

  -- Informations affichage
  `titre` VARCHAR(255) DEFAULT NULL COMMENT 'Titre de la promotion',
  `badge_texte` VARCHAR(50) DEFAULT 'PROMO' COMMENT 'Texte du badge',
  `badge_couleur` VARCHAR(7) DEFAULT '#e63946' COMMENT 'Couleur hexa du badge',
  `date_debut` DATETIME DEFAULT NULL,
  `date_fin` DATETIME DEFAULT NULL,
  `afficher_countdown` BOOLEAN DEFAULT 0,

  -- Conditions d'application
  `condition_surface_min` DECIMAL(10,2) DEFAULT NULL COMMENT 'Surface min en m² pour activer promo',
  `condition_surface_max` DECIMAL(10,2) DEFAULT NULL COMMENT 'Surface max en m²',
  `condition_quantite_min` INT DEFAULT NULL COMMENT 'Quantité minimum',
  `condition_finitions` JSON DEFAULT NULL COMMENT 'IDs finitions requises: [1,2,3]',
  `condition_sans_finitions` JSON DEFAULT NULL COMMENT 'IDs finitions exclues',
  `code_promo` VARCHAR(50) DEFAULT NULL COMMENT 'Code promo optionnel',
  `utilisation_max` INT DEFAULT NULL COMMENT 'Nombre max d\'utilisations',
  `utilisations_count` INT DEFAULT 0 COMMENT 'Compteur utilisations',

  `actif` BOOLEAN DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`produit_id`) REFERENCES `produits`(`id`) ON DELETE CASCADE,
  INDEX `idx_produit_promo` (`produit_id`),
  INDEX `idx_promo_dates` (`date_debut`, `date_fin`),
  INDEX `idx_promo_actif` (`actif`),
  INDEX `idx_code_promo` (`code_promo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 5. CRÉATION TABLE FORMATS
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
-- 6. CRÉATION TABLE HISTORIQUE
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
-- 7. CRÉATION TABLE ADMIN_USERS
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
-- 8. CRÉATION VUE PRODUITS AVEC PROMOTIONS
-- =====================================================

DROP VIEW IF EXISTS `v_produits_avec_promos`;

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
    pr.condition_surface_min as promo_surface_min,
    pr.condition_surface_max as promo_surface_max,
    pr.condition_quantite_min as promo_quantite_min,

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
-- 9. INSERTION FINITIONS CATALOGUE PAR DÉFAUT
-- =====================================================
-- Ces finitions sont dans le CATALOGUE GLOBAL
-- Tu choisis lesquelles activer pour chaque produit
-- Tu peux aussi créer les tiennes !
-- =====================================================

INSERT IGNORE INTO `finitions_catalogue` (`nom`, `description`, `categorie`, `prix_defaut`, `type_prix_defaut`, `icone`, `ordre`) VALUES
-- PVC / Forex
('Standard', 'Sans options particulières', 'PVC', 0, 'fixe', '✓', 0),
('Contrecollage', 'Sur support rigide', 'PVC', 8, 'par_m2', '🔲', 1),
('Découpe forme', 'Découpe personnalisée', 'PVC', 15, 'fixe', '✂️', 2),
('Angles arrondis', 'Coins arrondis', 'PVC', 5, 'fixe', '⭕', 3),

-- Aluminium
('Standard Alu', 'Sans options', 'Aluminium', 0, 'fixe', '✓', 10),
('Perçage', 'Trous pour fixation', 'Aluminium', 3, 'fixe', '⚫', 11),
('Cadre noir', 'Cadre aluminium noir', 'Aluminium', 25, 'fixe', '🖼️', 12),
('Cadre argent', 'Cadre aluminium argenté', 'Aluminium', 25, 'fixe', '🖼️', 13),

-- Bâche
('Standard Bâche', 'Sans options', 'Bâche', 0, 'fixe', '✓', 20),
('Œillets tous les 50cm', 'Œillets métalliques', 'Bâche', 5, 'par_ml', '⭕', 21),
('Œillets tous les 25cm', 'Œillets rapprochés', 'Bâche', 8, 'par_ml', '⭕', 22),
('Ourlet renforcé', 'Ourlet cousu', 'Bâche', 2, 'par_ml', '📏', 23),
('Sandow élastique', 'Tendeurs élastiques', 'Bâche', 1, 'par_ml', '〰️', 24),

-- Textile
('Standard Textile', 'Sans options', 'Textile', 0, 'fixe', '✓', 30),
('Baguettes bois', 'Baguettes haut/bas', 'Textile', 8, 'fixe', '🪵', 31),
('Baguettes alu', 'Baguettes aluminium', 'Textile', 12, 'fixe', '⚙️', 32),
('Confection sur mesure', 'Couture personnalisée', 'Textile', 15, 'fixe', '✂️', 33),
('Œillets textiles', 'Œillets pour suspension', 'Textile', 5, 'fixe', '⭕', 34),

-- Options universelles (Tous produits)
('Livraison express', 'Délai réduit à 48h', 'Tous', 30, 'fixe', '⚡', 100),
('Fichier fourni', 'Fichier graphique prêt', 'Tous', -10, 'fixe', '📄', 101),
('Installation', 'Pose sur site', 'Tous', 50, 'fixe', '🔧', 102);

-- =====================================================
-- MIGRATION TERMINÉE ✅
-- =====================================================
-- ✅ Tables créées avec conditions
-- ✅ Catalogue de finitions pré-rempli
-- ✅ AUCUNE finition automatique sur les produits
-- ✅ Tu choisis tout manuellement dans l'admin
-- =====================================================
