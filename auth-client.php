<?php
/**
 * Authentification Client - Imprixo
 * Gestion des sessions clients
 */

// Démarrer la session uniquement si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/api/config.php';

/**
 * Nettoyer les données utilisateur
 */
if (!function_exists('cleanInput')) {
    function cleanInput($data) {
        if (is_null($data)) {
            return '';
        }
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }
}

/**
 * Vérifier si le client est connecté
 */
function estClientConnecte() {
    return isset($_SESSION['client_id']) && $_SESSION['client_id'] > 0;
}

/**
 * Vérifier que le client est connecté, sinon rediriger vers login
 */
function verifierClientConnecte() {
    if (!estClientConnecte()) {
        header('Location: /login-client.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

/**
 * Récupérer les informations du client connecté
 */
function getClientInfo() {
    if (!estClientConnecte()) {
        return null;
    }

    $db = Database::getInstance();
    $client = $db->fetchOne(
        "SELECT * FROM clients WHERE id = ?",
        [$_SESSION['client_id']]
    );

    return $client;
}

/**
 * Connecter un client
 */
function connecterClient($email, $motDePasse) {
    $db = Database::getInstance();

    // Récupérer le client par email
    $client = $db->fetchOne(
        "SELECT * FROM clients WHERE email = ?",
        [$email]
    );

    if (!$client) {
        return ['success' => false, 'error' => 'Email ou mot de passe incorrect'];
    }

    // Vérifier le mot de passe
    if (!isset($client['password']) || !password_verify($motDePasse, $client['password'])) {
        return ['success' => false, 'error' => 'Email ou mot de passe incorrect'];
    }

    // Créer la session
    $_SESSION['client_id'] = $client['id'];
    $_SESSION['client_email'] = $client['email'];
    $_SESSION['client_nom'] = $client['nom'];
    $_SESSION['client_prenom'] = $client['prenom'];

    // Mettre à jour la dernière connexion
    $db->query(
        "UPDATE clients SET derniere_connexion = NOW() WHERE id = ?",
        [$client['id']]
    );

    return ['success' => true, 'client' => $client];
}

/**
 * Déconnecter le client
 */
function deconnecterClient() {
    $_SESSION = [];
    session_destroy();
}

/**
 * Créer un compte client
 */
function creerCompteClient($email, $motDePasse, $prenom, $nom, $telephone = null) {
    $db = Database::getInstance();

    // Vérifier si l'email existe déjà
    $existingClient = $db->fetchOne(
        "SELECT id FROM clients WHERE email = ?",
        [$email]
    );

    if ($existingClient) {
        return ['success' => false, 'error' => 'Un compte existe déjà avec cet email'];
    }

    // Hasher le mot de passe
    $passwordHash = password_hash($motDePasse, PASSWORD_BCRYPT);

    // Créer le client
    $db->query(
        "INSERT INTO clients (email, password, prenom, nom, telephone, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, NOW(), NOW())",
        [$email, $passwordHash, $prenom, $nom, $telephone]
    );

    $clientId = $db->lastInsertId();

    // Connecter automatiquement
    $_SESSION['client_id'] = $clientId;
    $_SESSION['client_email'] = $email;
    $_SESSION['client_nom'] = $nom;
    $_SESSION['client_prenom'] = $prenom;

    return ['success' => true, 'client_id' => $clientId];
}
