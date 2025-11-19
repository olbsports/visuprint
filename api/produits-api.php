<?php
/**
 * API REST Produits - Imprixo
 * Gestion complète des produits via BDD
 */

require_once __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$db = Database::getInstance();
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// GET - Lister produits ou obtenir un produit
if ($method === 'GET') {
    $id = $_GET['id'] ?? null;
    
    if ($id) {
        // Un produit spécifique
        $produit = $db->fetchOne(
            "SELECT p.*, 
                    pr.id as promo_id, pr.type as promo_type, pr.valeur as promo_valeur,
                    pr.prix_special as promo_prix, pr.titre as promo_titre, pr.badge_texte as promo_badge,
                    pr.date_debut as promo_date_debut, pr.date_fin as promo_date_fin,
                    pr.afficher_countdown as promo_countdown
             FROM produits p
             LEFT JOIN promotions pr ON pr.produit_id = p.id AND pr.actif = 1
             WHERE p.id = ?",
            [$id]
        );
        
        if (!$produit) {
            jsonResponse(['error' => 'Produit introuvable'], 404);
        }
        
        // Charger les finitions
        $finitions = $db->fetchAll(
            "SELECT * FROM produits_finitions WHERE produit_id = ? AND actif = 1 ORDER BY ordre",
            [$id]
        );
        
        $produit['finitions'] = $finitions;
        
        jsonResponse(['success' => true, 'produit' => $produit]);
    } else {
        // Liste des produits
        $where = [];
        $params = [];
        
        if (isset($_GET['categorie'])) {
            $where[] = "categorie = ?";
            $params[] = $_GET['categorie'];
        }
        
        if (isset($_GET['actif'])) {
            $where[] = "actif = ?";
            $params[] = $_GET['actif'] === 'true' ? 1 : 0;
        }
        
        if (isset($_GET['search'])) {
            $where[] = "(nom_produit LIKE ? OR id_produit LIKE ? OR description_courte LIKE ?)";
            $search = '%' . $_GET['search'] . '%';
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }
        
        $whereClause = count($where) > 0 ? 'WHERE ' . implode(' AND ', $where) : '';
        
        $produits = $db->fetchAll(
            "SELECT * FROM v_produits_avec_promos $whereClause ORDER BY nom_produit",
            $params
        );
        
        jsonResponse(['success' => true, 'produits' => $produits, 'total' => count($produits)]);
    }
}

// POST - Créer un produit
elseif ($method === 'POST') {
    // Vérifier auth admin (TODO: améliorer)
    session_start();
    if (!isset($_SESSION[ADMIN_SESSION_NAME])) {
        jsonResponse(['error' => 'Non autorisé'], 401);
    }
    
    $required = ['id_produit', 'nom_produit', 'categorie', 'prix_0_10', 'prix_11_50', 'prix_51_100', 'prix_101_300', 'prix_300_plus'];
    foreach ($required as $field) {
        if (!isset($input[$field]) || $input[$field] === '') {
            jsonResponse(['error' => "Champ requis: $field"], 400);
        }
    }
    
    // Générer slug
    $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $input['nom_produit']));
    
    try {
        $db->query(
            "INSERT INTO produits (
                id_produit, categorie, nom_produit, sous_titre, description_courte, description_longue,
                prix_0_10, prix_11_50, prix_51_100, prix_101_300, prix_300_plus,
                delai_standard_jours, certification, image_url, slug, actif
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $input['id_produit'],
                $input['categorie'],
                $input['nom_produit'],
                $input['sous_titre'] ?? '',
                $input['description_courte'] ?? '',
                $input['description_longue'] ?? '',
                $input['prix_0_10'],
                $input['prix_11_50'],
                $input['prix_51_100'],
                $input['prix_101_300'],
                $input['prix_300_plus'],
                $input['delai_standard_jours'] ?? 3,
                $input['certification'] ?? '',
                $input['image_url'] ?? '',
                $slug,
                $input['actif'] ?? 1
            ]
        );
        
        $produitId = $db->lastInsertId();
        
        // Ajouter finitions si présentes
        if (isset($input['finitions']) && is_array($input['finitions'])) {
            foreach ($input['finitions'] as $index => $fin) {
                $db->query(
                    "INSERT INTO produits_finitions (produit_id, nom, description, prix_supplement, type_prix, ordre)
                     VALUES (?, ?, ?, ?, ?, ?)",
                    [
                        $produitId,
                        $fin['nom'],
                        $fin['description'] ?? '',
                        $fin['prix_supplement'] ?? 0,
                        $fin['type_prix'] ?? 'fixe',
                        $index
                    ]
                );
            }
        }
        
        // Log
        logAdminAction($_SESSION[ADMIN_SESSION_NAME]['id'], 'creation_produit', "Produit {$input['id_produit']} créé");
        
        jsonResponse(['success' => true, 'id' => $produitId, 'message' => 'Produit créé']);
        
    } catch (PDOException $e) {
        jsonResponse(['error' => 'Erreur création produit', 'details' => $e->getMessage()], 500);
    }
}

// PUT - Modifier un produit
elseif ($method === 'PUT') {
    session_start();
    if (!isset($_SESSION[ADMIN_SESSION_NAME])) {
        jsonResponse(['error' => 'Non autorisé'], 401);
    }
    
    $id = $_GET['id'] ?? null;
    if (!$id) {
        jsonResponse(['error' => 'ID produit requis'], 400);
    }
    
    try {
        // Mettre à jour le produit
        $updates = [];
        $params = [];
        
        $allowedFields = [
            'categorie', 'nom_produit', 'sous_titre', 'description_courte', 'description_longue',
            'prix_0_10', 'prix_11_50', 'prix_51_100', 'prix_101_300', 'prix_300_plus',
            'delai_standard_jours', 'certification', 'image_url', 'actif'
        ];
        
        foreach ($allowedFields as $field) {
            if (isset($input[$field])) {
                $updates[] = "$field = ?";
                $params[] = $input[$field];
            }
        }
        
        if (count($updates) > 0) {
            $params[] = $id;
            $db->query(
                "UPDATE produits SET " . implode(', ', $updates) . " WHERE id = ?",
                $params
            );
        }
        
        // Mettre à jour finitions si présentes
        if (isset($input['finitions']) && is_array($input['finitions'])) {
            // Supprimer anciennes finitions
            $db->query("DELETE FROM produits_finitions WHERE produit_id = ?", [$id]);
            
            // Ajouter nouvelles finitions
            foreach ($input['finitions'] as $index => $fin) {
                $db->query(
                    "INSERT INTO produits_finitions (produit_id, nom, description, prix_supplement, type_prix, ordre)
                     VALUES (?, ?, ?, ?, ?, ?)",
                    [
                        $id,
                        $fin['nom'],
                        $fin['description'] ?? '',
                        $fin['prix_supplement'] ?? 0,
                        $fin['type_prix'] ?? 'fixe',
                        $index
                    ]
                );
            }
        }
        
        // Gestion promotion
        if (isset($input['promo'])) {
            if ($input['promo']['actif'] ?? false) {
                // Créer ou mettre à jour promo
                $existing = $db->fetchOne("SELECT id FROM promotions WHERE produit_id = ?", [$id]);
                
                if ($existing) {
                    $db->query(
                        "UPDATE promotions SET 
                            type = ?, valeur = ?, prix_special = ?, titre = ?, badge_texte = ?,
                            date_debut = ?, date_fin = ?, afficher_countdown = ?, actif = 1
                         WHERE id = ?",
                        [
                            $input['promo']['type'] ?? 'pourcentage',
                            $input['promo']['valeur'] ?? 0,
                            $input['promo']['prix_special'] ?? null,
                            $input['promo']['titre'] ?? '',
                            $input['promo']['badge_texte'] ?? 'PROMO',
                            $input['promo']['date_debut'] ?? null,
                            $input['promo']['date_fin'] ?? null,
                            $input['promo']['afficher_countdown'] ?? 0,
                            $existing['id']
                        ]
                    );
                } else {
                    $db->query(
                        "INSERT INTO promotions (produit_id, type, valeur, prix_special, titre, badge_texte, date_debut, date_fin, afficher_countdown, actif)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)",
                        [
                            $id,
                            $input['promo']['type'] ?? 'pourcentage',
                            $input['promo']['valeur'] ?? 0,
                            $input['promo']['prix_special'] ?? null,
                            $input['promo']['titre'] ?? '',
                            $input['promo']['badge_texte'] ?? 'PROMO',
                            $input['promo']['date_debut'] ?? null,
                            $input['promo']['date_fin'] ?? null,
                            $input['promo']['afficher_countdown'] ?? 0
                        ]
                    );
                }
            } else {
                // Désactiver promo
                $db->query("UPDATE promotions SET actif = 0 WHERE produit_id = ?", [$id]);
            }
        }
        
        logAdminAction($_SESSION[ADMIN_SESSION_NAME]['id'], 'modification_produit', "Produit ID $id modifié");
        
        jsonResponse(['success' => true, 'message' => 'Produit mis à jour']);
        
    } catch (PDOException $e) {
        jsonResponse(['error' => 'Erreur mise à jour produit', 'details' => $e->getMessage()], 500);
    }
}

// DELETE - Supprimer un produit
elseif ($method === 'DELETE') {
    session_start();
    if (!isset($_SESSION[ADMIN_SESSION_NAME])) {
        jsonResponse(['error' => 'Non autorisé'], 401);
    }
    
    $id = $_GET['id'] ?? null;
    if (!$id) {
        jsonResponse(['error' => 'ID produit requis'], 400);
    }
    
    try {
        $db->query("DELETE FROM produits WHERE id = ?", [$id]);
        
        logAdminAction($_SESSION[ADMIN_SESSION_NAME]['id'], 'suppression_produit', "Produit ID $id supprimé");
        
        jsonResponse(['success' => true, 'message' => 'Produit supprimé']);
        
    } catch (PDOException $e) {
        jsonResponse(['error' => 'Erreur suppression produit', 'details' => $e->getMessage()], 500);
    }
}

else {
    jsonResponse(['error' => 'Méthode non autorisée'], 405);
}
