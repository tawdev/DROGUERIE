<?php
/**
 * Page d'accueil
 */
require_once 'config/config.php';
require_once 'includes/functions.php';

$page_title = 'Accueil';

// Récupérer les produits en promotion
$produits_promotion = getProduits(null, 6, true);

// Récupérer les dernières catégories
$categories = getCategories();

include 'includes/header.php';
?>

<section class="hero">
    <div class="slider">
        <div class="slide active">
            <div class="slide-content">
                <h2>Bienvenue dans votre droguerie en ligne</h2>
                <p>Découvrez notre large sélection de produits de qualité</p>
                <a href="<?php echo baseUrl('catalogue.php'); ?>" class="btn btn-primary">Voir le catalogue</a>
            </div>
        </div>
        <div class="slide">
            <div class="slide-content">
                <h2>Promotions exceptionnelles</h2>
                <p>Profitez de nos offres spéciales sur tous nos produits</p>
                <a href="<?php echo baseUrl('catalogue.php?promotion=1'); ?>" class="btn btn-primary">Voir les promotions</a>
            </div>
        </div>
        <div class="slide">
            <div class="slide-content">
                <h2>Livraison rapide partout au Maroc</h2>
                <p>Commandez aujourd'hui, recevez demain</p>
                <a href="<?php echo baseUrl('catalogue.php'); ?>" class="btn btn-primary">Commander maintenant</a>
            </div>
        </div>
    </div>
    <div class="slider-controls">
        <button class="slider-btn prev" onclick="changeSlide(-1)">‹</button>
        <button class="slider-btn next" onclick="changeSlide(1)">›</button>
    </div>
    <div class="slider-dots">
        <span class="dot active" onclick="currentSlide(1)"></span>
        <span class="dot" onclick="currentSlide(2)"></span>
        <span class="dot" onclick="currentSlide(3)"></span>
    </div>
</section>

<section class="categories-section">
    <div class="container">
        <h2 class="section-title">Nos Catégories</h2>
        <div class="categories-grid">
            <?php foreach ($categories as $categorie): ?>
                <div class="category-card">
                    <a href="<?php echo baseUrl('catalogue.php?categorie=' . $categorie['id']); ?>">
                        <div class="category-image">
                            <?php if ($categorie['image']): ?>
                                <img src="<?php echo baseUrl('uploads/' . $categorie['image']); ?>" alt="<?php echo htmlspecialchars($categorie['nom']); ?>">
                            <?php else: ?>
                                <div class="category-placeholder"><?php echo substr($categorie['nom'], 0, 1); ?></div>
                            <?php endif; ?>
                        </div>
                        <h3><?php echo htmlspecialchars($categorie['nom']); ?></h3>
                        <p><?php echo htmlspecialchars($categorie['description'] ?? ''); ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php if (!empty($produits_promotion)): ?>
<section class="promotions-section">
    <div class="container">
        <h2 class="section-title">Promotions du moment</h2>
        <div class="products-grid">
            <?php foreach ($produits_promotion as $produit): ?>
                <div class="product-card">
                    <?php if ($produit['en_promotion']): ?>
                        <span class="badge-promo">PROMO</span>
                    <?php endif; ?>
                    <div class="product-image">
                        <a href="<?php echo baseUrl('produit.php?id=' . $produit['id']); ?>">
                            <?php if ($produit['image']): ?>
                                <img src="<?php echo baseUrl('uploads/' . $produit['image']); ?>" alt="<?php echo htmlspecialchars($produit['nom']); ?>">
                            <?php else: ?>
                                <div class="product-placeholder">Image</div>
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="product-info">
                        <h3><a href="<?php echo baseUrl('produit.php?id=' . $produit['id']); ?>"><?php echo htmlspecialchars($produit['nom']); ?></a></h3>
                        <p class="product-category"><?php echo htmlspecialchars($produit['categorie_nom']); ?></p>
                        <div class="product-price">
                            <?php if ($produit['en_promotion'] && $produit['prix_promotion']): ?>
                                <span class="price-old"><?php echo formatPrice($produit['prix']); ?></span>
                                <span class="price-new"><?php echo formatPrice($produit['prix_promotion']); ?></span>
                            <?php else: ?>
                                <span class="price"><?php echo formatPrice($produit['prix']); ?></span>
                            <?php endif; ?>
                        </div>
                        <button class="btn btn-add-cart" onclick="addToCart(<?php echo $produit['id']; ?>)">Ajouter au panier</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

