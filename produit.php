<?php
/**
 * Page détail d'un produit
 */
require_once 'config/config.php';
require_once 'includes/functions.php';

$page_title = 'Détail du produit';

// Récupérer l'ID du produit
$produit_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$produit = getProduit($produit_id);

if (!$produit) {
    header('HTTP/1.0 404 Not Found');
    include '404.php';
    exit;
}

$page_title = $produit['nom'];

include 'includes/header.php';
?>

<section class="product-detail-section">
    <div class="container">
        <div class="product-detail">
            <div class="product-detail-image">
                <?php if ($produit['image']): ?>
                    <img src="<?php echo baseUrl('uploads/' . $produit['image']); ?>" alt="<?php echo htmlspecialchars($produit['nom']); ?>">
                <?php else: ?>
                    <div class="product-placeholder large">Image non disponible</div>
                <?php endif; ?>
                <?php if ($produit['en_promotion']): ?>
                    <span class="badge-promo large">PROMO</span>
                <?php endif; ?>
            </div>
            
            <div class="product-detail-info">
                <nav class="breadcrumb">
                    <a href="<?php echo baseUrl(); ?>">Accueil</a> / 
                    <a href="<?php echo baseUrl('catalogue.php?categorie=' . $produit['categorie_id']); ?>"><?php echo htmlspecialchars($produit['categorie_nom']); ?></a> / 
                    <span><?php echo htmlspecialchars($produit['nom']); ?></span>
                </nav>
                
                <h1><?php echo htmlspecialchars($produit['nom']); ?></h1>
                
                <?php if ($produit['marque']): ?>
                    <p class="product-brand">Marque: <strong><?php echo htmlspecialchars($produit['marque']); ?></strong></p>
                <?php endif; ?>
                
                <div class="product-price-detail">
                    <?php if ($produit['en_promotion'] && $produit['prix_promotion']): ?>
                        <span class="price-old"><?php echo formatPrice($produit['prix']); ?></span>
                        <span class="price-new"><?php echo formatPrice($produit['prix_promotion']); ?></span>
                        <span class="discount">-<?php echo round((($produit['prix'] - $produit['prix_promotion']) / $produit['prix']) * 100); ?>%</span>
                    <?php else: ?>
                        <span class="price"><?php echo formatPrice($produit['prix']); ?></span>
                    <?php endif; ?>
                    <span class="price-unit">/ <?php echo htmlspecialchars($produit['unite']); ?></span>
                </div>
                
                <div class="product-stock-detail">
                    <?php if ($produit['stock'] > 0): ?>
                        <span class="stock-available">✓ En stock (<?php echo $produit['stock']; ?> disponible<?php echo $produit['stock'] > 1 ? 's' : ''; ?>)</span>
                    <?php else: ?>
                        <span class="stock-unavailable">✗ Rupture de stock</span>
                    <?php endif; ?>
                </div>
                
                <?php if ($produit['description']): ?>
                    <div class="product-description">
                        <h3>Description</h3>
                        <p><?php echo nl2br(htmlspecialchars($produit['description'])); ?></p>
                    </div>
                <?php endif; ?>
                
                <div class="product-actions">
                    <div class="quantity-selector">
                        <label for="quantity">Quantité:</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $produit['stock']; ?>">
                    </div>
                    <button class="btn btn-primary btn-large" 
                            onclick="addToCart(<?php echo $produit['id']; ?>, document.getElementById('quantity').value)" 
                            <?php echo $produit['stock'] <= 0 ? 'disabled' : ''; ?>>
                        Ajouter au panier
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

