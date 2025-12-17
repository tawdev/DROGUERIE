<?php
/**
 * Supprimer un produit (admin)
 */
require_once 'auth.php';
require_once '../config/config.php';

$produit_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($produit_id > 0) {
    $pdo = getDB();
    
    // Récupérer l'image pour la supprimer
    $stmt = $pdo->prepare("SELECT image FROM produits WHERE id = ?");
    $stmt->execute([$produit_id]);
    $produit = $stmt->fetch();
    
    // Supprimer le produit
    $stmt = $pdo->prepare("DELETE FROM produits WHERE id = ?");
    $stmt->execute([$produit_id]);
    
    // Supprimer l'image si elle existe
    if ($produit && $produit['image'] && file_exists(UPLOAD_PATH . $produit['image'])) {
        unlink(UPLOAD_PATH . $produit['image']);
    }
}

redirect('produits.php');

