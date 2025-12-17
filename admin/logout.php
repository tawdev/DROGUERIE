<?php
/**
 * Déconnexion admin
 */
require_once '../config/config.php';

session_destroy();
redirect(baseUrl('admin/index.php'));

