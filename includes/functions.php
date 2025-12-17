<?php
/**
 * Fonctions utilitaires
 */

/**
 * Récupérer toutes les catégories
 */
function getCategories() {
    $pdo = getDB();
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY ordre ASC, nom ASC");
    return $stmt->fetchAll();
}

/**
 * Récupérer une catégorie par ID
 */
function getCategorie($id) {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * Récupérer les produits
 */
function getProduits($categorie_id = null, $limit = null, $en_promotion = false) {
    $pdo = getDB();
    $sql = "SELECT p.*, c.nom as categorie_nom 
            FROM produits p 
            JOIN categories c ON p.categorie_id = c.id 
            WHERE p.actif = 1";
    
    $params = [];
    
    if ($categorie_id) {
        $sql .= " AND p.categorie_id = ?";
        $params[] = $categorie_id;
    }
    
    if ($en_promotion) {
        $sql .= " AND p.en_promotion = 1";
    }
    
    $sql .= " ORDER BY p.created_at DESC";
    
    if ($limit) {
        $sql .= " LIMIT " . (int)$limit;
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Récupérer un produit par ID
 */
function getProduit($id) {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT p.*, c.nom as categorie_nom 
                          FROM produits p 
                          JOIN categories c ON p.categorie_id = c.id 
                          WHERE p.id = ? AND p.actif = 1");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * Récupérer le prix du produit (avec promotion si applicable)
 */
function getPrixProduit($produit) {
    return $produit['en_promotion'] && $produit['prix_promotion'] 
        ? $produit['prix_promotion'] 
        : $produit['prix'];
}

/**
 * Gestion du panier
 */
function getPanier() {
    return $_SESSION['panier'] ?? [];
}

function addToPanier($produit_id, $quantite = 1) {
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }
    
    if (isset($_SESSION['panier'][$produit_id])) {
        $_SESSION['panier'][$produit_id] += $quantite;
    } else {
        $_SESSION['panier'][$produit_id] = $quantite;
    }
}

function updatePanier($produit_id, $quantite) {
    if (isset($_SESSION['panier'][$produit_id])) {
        if ($quantite > 0) {
            $_SESSION['panier'][$produit_id] = $quantite;
        } else {
            unset($_SESSION['panier'][$produit_id]);
        }
    }
}

function removeFromPanier($produit_id) {
    if (isset($_SESSION['panier'][$produit_id])) {
        unset($_SESSION['panier'][$produit_id]);
    }
}

function clearPanier() {
    $_SESSION['panier'] = [];
}

function getPanierTotal() {
    $panier = getPanier();
    $total = 0;
    
    foreach ($panier as $produit_id => $quantite) {
        $produit = getProduit($produit_id);
        if ($produit) {
            $prix = getPrixProduit($produit);
            $total += $prix * $quantite;
        }
    }
    
    return $total;
}

function getPanierCount() {
    $panier = getPanier();
    return array_sum($panier);
}

/**
 * Créer une commande
 */
function createCommande($data) {
    $pdo = getDB();
    
    try {
        $pdo->beginTransaction();
        
        // Calculer le total
        $total = getPanierTotal();
        
        // Insérer la commande
        $stmt = $pdo->prepare("INSERT INTO commandes (nom_client, telephone, email, adresse, ville, code_postal, total, notes) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['nom_client'],
            $data['telephone'],
            $data['email'] ?? null,
            $data['adresse'],
            $data['ville'],
            $data['code_postal'] ?? null,
            $total,
            $data['notes'] ?? null
        ]);
        
        $commande_id = $pdo->lastInsertId();
        
        // Insérer les détails de la commande
        $panier = getPanier();
        $stmt = $pdo->prepare("INSERT INTO commande_details (commande_id, produit_id, quantite, prix_unitaire, sous_total) 
                               VALUES (?, ?, ?, ?, ?)");
        
        foreach ($panier as $produit_id => $quantite) {
            $produit = getProduit($produit_id);
            if ($produit) {
                $prix = getPrixProduit($produit);
                $sous_total = $prix * $quantite;
                
                $stmt->execute([
                    $commande_id,
                    $produit_id,
                    $quantite,
                    $prix,
                    $sous_total
                ]);
            }
        }
        
        $pdo->commit();
        clearPanier();
        
        return $commande_id;
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

/**
 * Enregistrer un message de contact
 */
function saveMessageContact($data) {
    $pdo = getDB();
    
    try {
        $stmt = $pdo->prepare("INSERT INTO messages_contact (nom, email, telephone, sujet, message) 
                             VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['nom'],
            $data['email'],
            $data['telephone'] ?? null,
            $data['sujet'] ?? null,
            $data['message']
        ]);
        
        return $pdo->lastInsertId();
    } catch (Exception $e) {
        throw $e;
    }
}

/**
 * Récupérer tous les messages de contact
 */
function getMessagesContact($lu = null) {
    $pdo = getDB();
    
    $sql = "SELECT * FROM messages_contact";
    $params = [];
    
    if ($lu !== null) {
        $sql .= " WHERE lu = ?";
        $params[] = $lu ? 1 : 0;
    }
    
    $sql .= " ORDER BY created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Marquer un message comme lu
 */
function markMessageAsRead($message_id) {
    $pdo = getDB();
    
    $stmt = $pdo->prepare("UPDATE messages_contact SET lu = 1 WHERE id = ?");
    $stmt->execute([$message_id]);
    
    return $stmt->rowCount() > 0;
}

