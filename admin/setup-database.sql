-- ============================================
-- IMPRIXO - STRUCTURE BASE DE DONNÉES
-- ============================================

-- Table produits avec gestion complète
CREATE TABLE IF NOT EXISTS `produits` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `id_produit` VARCHAR(50) UNIQUE NOT NULL,
  `categorie` VARCHAR(100) NOT NULL,
  `nom_produit` VARCHAR(255) NOT NULL,
  `sous_titre` VARCHAR(255),
  `description_courte` TEXT,
  `description_longue` TEXT,
  
  -- Spécifications techniques
  `poids_m2` DECIMAL(10,2),
  `epaisseur` VARCHAR(50),
  `format_max_cm` VARCHAR(50),
  `usage` VARCHAR(255),
  `duree_vie` VARCHAR(50),
  `certification` VARCHAR(255),
  `finition_defaut` VARCHAR(255),
  `impression_faces` VARCHAR(50),
  
  -- Prix dégressifs (€/m²)
  `prix_0_10` DECIMAL(10,2) NOT NULL,
  `prix_11_50` DECIMAL(10,2) NOT NULL,
  `prix_51_100` DECIMAL(10,2) NOT NULL,
  `prix_101_300` DECIMAL(10,2) NOT NULL,
  `prix_300_plus` DECIMAL(10,2) NOT NULL,
  
  -- Commande
  `commande_min_euro` DECIMAL(10,2),
  `delai_standard_jours` INT DEFAULT 3,
  `unite_vente` VARCHAR(20) DEFAULT 'm²',
  
  -- Contenu
  `image_url` VARCHAR(512),
  `image_principale` VARCHAR(512),
  `images_secondaires` TEXT, -- JSON array
  `video_url` VARCHAR(512),
  
  -- Statut
  `actif` BOOLEAN DEFAULT 1,
  `stock_disponible` BOOLEAN DEFAULT 1,
  `nouveau` BOOLEAN DEFAULT 0,
  `best_seller` BOOLEAN DEFAULT 0,
  
  -- SEO
  `slug` VARCHAR(255) UNIQUE,
  `meta_title` VARCHAR(255),
  `meta_description` TEXT,
  `meta_keywords` TEXT,
  
  -- Timestamps
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  INDEX idx_categorie (categorie),
  INDEX idx_actif (actif),
  INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table finitions (options configurateur)
CREATE TABLE IF NOT EXISTS `produits_finitions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `produit_id` INT NOT NULL,
  `nom` VARCHAR(100) NOT NULL,
  `description` VARCHAR(255),
  `prix_supplement` DECIMAL(10,2) DEFAULT 0,
  `type_prix` ENUM('fixe', 'par_m2', 'par_ml') DEFAULT 'fixe',
  `ordre` INT DEFAULT 0,
  `actif` BOOLEAN DEFAULT 1,
  
  FOREIGN KEY (produit_id) REFERENCES produits(id) ON DELETE CASCADE,
  INDEX idx_produit (produit_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table promotions
CREATE TABLE IF NOT EXISTS `promotions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `produit_id` INT,
  `type` ENUM('pourcentage', 'fixe', 'prix_special') DEFAULT 'pourcentage',
  `valeur` DECIMAL(10,2) NOT NULL, -- % ou € selon type
  `prix_special` DECIMAL(10,2), -- Prix promo direct si type=prix_special
  
  -- Titre et description
  `titre` VARCHAR(255),
  `description` TEXT,
  `badge_texte` VARCHAR(50) DEFAULT 'PROMO',
  
  -- Dates
  `date_debut` DATETIME,
  `date_fin` DATETIME,
  
  -- Options
  `afficher_countdown` BOOLEAN DEFAULT 0,
  `afficher_badge` BOOLEAN DEFAULT 1,
  
  -- Application
  `applique_sur` ENUM('produit', 'categorie', 'global') DEFAULT 'produit',
  `categorie` VARCHAR(100),
  `code_promo` VARCHAR(50), -- Si promo à code
  
  -- Statut
  `actif` BOOLEAN DEFAULT 1,
  `utilisation_max` INT, -- Nombre max d'utilisations
  `utilisation_count` INT DEFAULT 0,
  
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (produit_id) REFERENCES produits(id) ON DELETE CASCADE,
  INDEX idx_produit (produit_id),
  INDEX idx_dates (date_debut, date_fin),
  INDEX idx_actif (actif),
  INDEX idx_code (code_promo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table formats prédéfinis (optionnel, pour override par produit)
CREATE TABLE IF NOT EXISTS `produits_formats` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `produit_id` INT,
  `nom` VARCHAR(100) NOT NULL,
  `largeur_cm` DECIMAL(10,2) NOT NULL,
  `hauteur_cm` DECIMAL(10,2) NOT NULL,
  `categorie_format` VARCHAR(50), -- iso, kakemono, rollup, panneau, carre, grand
  `ordre` INT DEFAULT 0,
  `actif` BOOLEAN DEFAULT 1,
  
  FOREIGN KEY (produit_id) REFERENCES produits(id) ON DELETE CASCADE,
  INDEX idx_produit (produit_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table historique modifications produits
CREATE TABLE IF NOT EXISTS `produits_historique` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `produit_id` INT NOT NULL,
  `admin_user_id` INT,
  `action` ENUM('creation', 'modification', 'suppression', 'activation', 'desactivation') NOT NULL,
  `champs_modifies` TEXT, -- JSON
  `valeurs_avant` TEXT, -- JSON
  `valeurs_apres` TEXT, -- JSON
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (produit_id) REFERENCES produits(id) ON DELETE CASCADE,
  INDEX idx_produit (produit_id),
  INDEX idx_date (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table admin_users (si elle n'existe pas déjà)
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(255) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `nom` VARCHAR(100),
  `prenom` VARCHAR(100),
  `role` ENUM('admin', 'editeur', 'visualiseur') DEFAULT 'admin',
  `actif` BOOLEAN DEFAULT 1,
  `derniere_connexion` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  INDEX idx_email (email),
  INDEX idx_actif (actif)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Créer l'admin par défaut (password: admin123)
INSERT IGNORE INTO `admin_users` (`email`, `password`, `nom`, `prenom`, `role`)
VALUES ('admin@imprixo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'Imprixo', 'admin');

-- ============================================
-- VUES UTILES
-- ============================================

-- Vue produits avec promotions actives
CREATE OR REPLACE VIEW `v_produits_avec_promos` AS
SELECT 
    p.*,
    pr.id as promo_id,
    pr.type as promo_type,
    pr.valeur as promo_valeur,
    pr.prix_special as promo_prix_special,
    pr.titre as promo_titre,
    pr.badge_texte as promo_badge,
    pr.date_debut as promo_date_debut,
    pr.date_fin as promo_date_fin,
    pr.afficher_countdown as promo_countdown,
    CASE 
        WHEN pr.type = 'pourcentage' THEN p.prix_0_10 * (1 - pr.valeur/100)
        WHEN pr.type = 'fixe' THEN p.prix_0_10 - pr.valeur
        WHEN pr.type = 'prix_special' THEN pr.prix_special
        ELSE p.prix_0_10
    END as prix_promo
FROM produits p
LEFT JOIN promotions pr ON pr.produit_id = p.id 
    AND pr.actif = 1
    AND (pr.date_debut IS NULL OR pr.date_debut <= NOW())
    AND (pr.date_fin IS NULL OR pr.date_fin >= NOW());

