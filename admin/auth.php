<?php
/**
 * Vérification de l'authentification admin
 */
require_once '../config/config.php';

if (!isset($_SESSION['admin_id'])) {
    redirect(baseUrl('admin/index.php'));
}

