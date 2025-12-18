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
        <a href="javascript:history.back()" class="btn-back">
            <span class="back-icon">←</span> Retour
        </a>
        <nav class="breadcrumb">
            <a href="<?php echo baseUrl(); ?>">Accueil</a> 
            <span class="breadcrumb-separator">/</span>
            <a href="<?php echo baseUrl('catalogue.php?categorie=' . $produit['categorie_id']); ?>"><?php echo htmlspecialchars($produit['categorie_nom']); ?></a> 
            <span class="breadcrumb-separator">/</span>
            <span><?php echo htmlspecialchars($produit['nom']); ?></span>
        </nav>
        
        <div class="product-detail">
            <div class="product-detail-image">
                <?php if ($produit['image']): ?>
                    <img src="<?php echo baseUrl('uploads/' . $produit['image']); ?>" alt="<?php echo htmlspecialchars($produit['nom']); ?>">
                <?php else: ?>
                    <div class="product-placeholder large">
                        <span class="placeholder-icon">📦</span>
                        <span>Image non disponible</span>
                    </div>
                <?php endif; ?>
                <?php if ($produit['en_promotion']): ?>
                    <span class="badge-promo large">PROMO</span>
                <?php endif; ?>
            </div>
            
            <div class="product-detail-info">
                <div class="product-header-info">
                    <h1><?php echo htmlspecialchars($produit['nom']); ?></h1>
                    
                    <?php if ($produit['marque']): ?>
                        <div class="product-brand-info">
                            <span class="brand-label">Marque:</span>
                            <span class="brand-value"><?php echo htmlspecialchars($produit['marque']); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="product-category-info">
                        <span class="category-label">Catégorie:</span>
                        <a href="<?php echo baseUrl('catalogue.php?categorie=' . $produit['categorie_id']); ?>" class="category-link">
                            <?php echo htmlspecialchars($produit['categorie_nom']); ?>
                        </a>
                    </div>
                </div>
                
                <div class="product-price-detail">
                    <?php if ($produit['en_promotion'] && $produit['prix_promotion']): ?>
                        <div class="price-main">
                            <div class="price-old-wrapper">
                                <span class="price-old"><?php echo formatPrice($produit['prix']); ?></span>
                                <span class="discount-badge">-<?php echo round((($produit['prix'] - $produit['prix_promotion']) / $produit['prix']) * 100); ?>%</span>
                            </div>
                            <div class="price-new-wrapper">
                                <span class="price-new"><?php echo formatPrice($produit['prix_promotion']); ?></span>
                                <span class="price-unit">/ <?php echo htmlspecialchars($produit['unite']); ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="price-main">
                            <span class="price"><?php echo formatPrice($produit['prix']); ?></span>
                            <span class="price-unit">/ <?php echo htmlspecialchars($produit['unite']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="product-stock-detail">
                    <?php if ($produit['stock'] > 0): ?>
                        <div class="stock-info available">
                            <span class="stock-icon">✓</span>
                            <div class="stock-text">
                                <strong>En stock</strong>
                                <span><?php echo $produit['stock']; ?> disponible<?php echo $produit['stock'] > 1 ? 's' : ''; ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="stock-info unavailable">
                            <span class="stock-icon">✗</span>
                            <div class="stock-text">
                                <strong>Rupture de stock</strong>
                                <span>Ce produit n'est actuellement pas disponible</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="product-actions-detail">
                    <div class="quantity-selector">
                        <label for="quantity">Quantité:</label>
                        <div class="quantity-controls-detail">
                            <button type="button" class="qty-btn-detail qty-minus" onclick="changeQuantity(-1)">−</button>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $produit['stock']; ?>" readonly>
                            <button type="button" class="qty-btn-detail qty-plus" onclick="changeQuantity(1)">+</button>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-large btn-add-to-cart" 
                            onclick="addToCart(<?php echo $produit['id']; ?>, document.getElementById('quantity').value)" 
                            <?php echo $produit['stock'] <= 0 ? 'disabled' : ''; ?>>
                        <span class="cart-icon-large">🛒</span>
                        Ajouter au panier
                    </button>
                </div>
                
                <?php if ($produit['description']): ?>
                    <div class="product-description">
                        <h3>Description du produit</h3>
                        <div class="description-content">
                            <?php echo nl2br(htmlspecialchars($produit['description'])); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
function changeQuantity(change) {
    const input = document.getElementById('quantity');
    let currentValue = parseInt(input.value) || 1;
    const max = parseInt(input.getAttribute('max')) || 999;
    const min = parseInt(input.getAttribute('min')) || 1;
    
    let newValue = currentValue + change;
    
    if (newValue < min) newValue = min;
    if (newValue > max) newValue = max;
    
    input.value = newValue;
}
</script>

<?php include 'includes/footer.php'; ?>

