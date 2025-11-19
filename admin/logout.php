<?php
/**
 * Déconnexion Admin - Imprixo Admin
 */

require_once __DIR__ . '/auth.php';

deconnecterAdmin();

header('Location: /admin/login.php');
exit;
