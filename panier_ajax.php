<?php
/**
 * Gestion AJAX du panier
 */
require_once 'config/config.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $produit_id = isset($_POST['produit_id']) ? (int)$_POST['produit_id'] : 0;
    $quantite = isset($_POST['quantite']) ? (int)$_POST['quantite'] : 1;
    
    switch ($action) {
        case 'add':
            if ($produit_id > 0) {
                $produit = getProduit($produit_id);
                if ($produit && $produit['stock'] > 0) {
                    addToPanier($produit_id, $quantite);
                    echo json_encode([
                        'success' => true,
                        'count' => getPanierCount(),
                        'message' => 'Produit ajouté au panier'
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Produit non disponible'
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Produit invalide'
                ]);
            }
            break;
            
        default:
            echo json_encode([
                'success' => false,
                'message' => 'Action invalide'
            ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'count' => getPanierCount()
    ]);
}

