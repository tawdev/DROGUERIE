<?php
/**
 * Configuration générale du site
 */

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclure la configuration de la base de données
require_once __DIR__ . '/database.php';

// Configuration du site
define('SITE_NAME', 'Droguerie Maroc');
define('SITE_URL', 'http://localhost/droguerie');
define('CURRENCY', 'MAD');
define('CURRENCY_SYMBOL', 'DH');

// Chemins
define('ROOT_PATH', dirname(__DIR__));
define('UPLOAD_PATH', ROOT_PATH . '/uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');

// Créer le dossier uploads s'il n'existe pas
if (!file_exists(UPLOAD_PATH)) {
    mkdir(UPLOAD_PATH, 0777, true);
}

// Fonction pour formater le prix
function formatPrice($price) {
    return number_format($price, 2, ',', ' ') . ' ' . CURRENCY_SYMBOL;
}

// Fonction pour obtenir l'URL de base
function baseUrl($path = '') {
    return SITE_URL . '/' . ltrim($path, '/');
}

// Fonction pour rediriger
function redirect($url) {
    header("Location: " . $url);
    exit;
}

// Fonction pour sécuriser les données
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

