<?php
/**
 * Page panier
 */
require_once 'config/config.php';
require_once 'includes/functions.php';

$page_title = 'Panier';

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update':
                $produit_id = (int)$_POST['produit_id'];
                $quantite = (int)$_POST['quantite'];
                updatePanier($produit_id, $quantite);
                break;
            case 'remove':
                $produit_id = (int)$_POST['produit_id'];
                removeFromPanier($produit_id);
                break;
            case 'clear':
                clearPanier();
                break;
        }
        redirect(baseUrl('panier.php'));
    }
}

// Récupérer le contenu du panier
$panier = getPanier();
$panier_items = [];
$total = 0;

foreach ($panier as $produit_id => $quantite) {
    $produit = getProduit($produit_id);
    if ($produit) {
        $prix = getPrixProduit($produit);
        $sous_total = $prix * $quantite;
        $total += $sous_total;
        
        $panier_items[] = [
            'produit' => $produit,
            'quantite' => $quantite,
            'prix' => $prix,
            'sous_total' => $sous_total
        ];
    }
}

include 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>Mon Panier</h1>
        <p>Vérifiez vos articles avant de passer commande</p>
    </div>
</section>

<section class="cart-section">
    <div class="container">
        <?php if (empty($panier_items)): ?>
            <div class="cart-empty">
                <div class="cart-empty-icon">🛒</div>
                <h2>Votre panier est vide</h2>
                <p>Vous n'avez pas encore ajouté de produits à votre panier.</p>
                <a href="<?php echo baseUrl('catalogue.php'); ?>" class="btn btn-primary">Découvrir nos produits</a>
            </div>
        <?php else: ?>
            <div class="cart-content">
                <div class="cart-items">
                    <div class="cart-table-wrapper">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Prix unitaire</th>
                                    <th>Quantité</th>
                                    <th>Sous-total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($panier_items as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="cart-item-product">
                                                <div class="cart-item-image">
                                                    <?php if ($item['produit']['image']): ?>
                                                        <img src="<?php echo baseUrl('uploads/' . $item['produit']['image']); ?>" alt="<?php echo htmlspecialchars($item['produit']['nom']); ?>">
                                                    <?php else: ?>
                                                        <div class="product-placeholder small">Img</div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="cart-item-info">
                                                    <h3><a href="<?php echo baseUrl('produit.php?id=' . $item['produit']['id']); ?>"><?php echo htmlspecialchars($item['produit']['nom']); ?></a></h3>
                                                    <p class="cart-item-category"><?php echo htmlspecialchars($item['produit']['categorie_nom']); ?></p>
                                                    <?php if ($item['produit']['en_promotion']): ?>
                                                        <span class="badge-promo-small">PROMO</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="cart-price">
                                            <?php if ($item['produit']['en_promotion'] && $item['produit']['prix_promotion']): ?>
                                                <span class="price-old-small"><?php echo formatPrice($item['produit']['prix']); ?></span>
                                                <span class="price-new-small"><?php echo formatPrice($item['prix']); ?></span>
                                            <?php else: ?>
                                                <span class="price-value"><?php echo formatPrice($item['prix']); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <form method="POST" class="quantity-form">
                                                <input type="hidden" name="action" value="update">
                                                <input type="hidden" name="produit_id" value="<?php echo $item['produit']['id']; ?>">
                                                <div class="quantity-controls">
                                                    <button type="button" class="qty-btn qty-minus" onclick="updateQuantity(<?php echo $item['produit']['id']; ?>, -1)">−</button>
                                                    <input type="number" name="quantite" id="qty-<?php echo $item['produit']['id']; ?>" value="<?php echo $item['quantite']; ?>" min="1" max="<?php echo $item['produit']['stock']; ?>" onchange="this.form.submit()">
                                                    <button type="button" class="qty-btn qty-plus" onclick="updateQuantity(<?php echo $item['produit']['id']; ?>, 1)">+</button>
                                                </div>
                                            </form>
                                        </td>
                                        <td class="cart-subtotal">
                                            <strong><?php echo formatPrice($item['sous_total']); ?></strong>
                                        </td>
                                        <td>
                                            <form method="POST" class="inline-form">
                                                <input type="hidden" name="action" value="remove">
                                                <input type="hidden" name="produit_id" value="<?php echo $item['produit']['id']; ?>">
                                                <button type="submit" class="btn-remove" title="Supprimer">
                                                    <span class="remove-icon">🗑️</span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="cart-actions">
                        <a href="<?php echo baseUrl('catalogue.php'); ?>" class="btn btn-secondary">
                            <span>←</span> Continuer mes achats
                        </a>
                        <form method="POST" class="inline-form">
                            <input type="hidden" name="action" value="clear">
                            <button type="submit" class="btn btn-outline" onclick="return confirm('Êtes-vous sûr de vouloir vider le panier ?');">
                                Vider le panier
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="cart-summary">
                    <h2>Résumé de la commande</h2>
                    <div class="summary-content">
                        <div class="summary-row">
                            <span>Sous-total</span>
                            <span class="summary-value"><?php echo formatPrice($total); ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Livraison</span>
                            <span class="summary-value summary-info">À calculer</span>
                        </div>
                        <div class="summary-divider"></div>
                        <div class="summary-row total">
                            <span>Total</span>
                            <span class="summary-value"><?php echo formatPrice($total); ?></span>
                        </div>
                    </div>
                    <a href="<?php echo baseUrl('commande.php'); ?>" class="btn btn-primary btn-large btn-block">
                        Passer la commande →
                    </a>
                    <div class="summary-security">
                        <span class="security-icon">🔒</span>
                        <span>Paiement sécurisé</span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

