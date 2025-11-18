<?php
/**
 * Déconnexion Client - Imprixo
 */

require_once __DIR__ . '/auth-client.php';

deconnecterClient();

header('Location: /index.html');
exit;
