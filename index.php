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
                <h2>Matériaux de construction & Quincaillerie</h2>
                <p>Tout ce dont vous avez besoin pour vos projets de construction et rénovation</p>
                <div class="slide-buttons">
                    <a href="<?php echo baseUrl('catalogue.php'); ?>" class="btn btn-primary btn-large">Voir le catalogue</a>
                    <a href="<?php echo baseUrl('apropos.php'); ?>" class="btn btn-secondary btn-large">En savoir plus</a>
                </div>
            </div>
        </div>
        <div class="slide">
            <div class="slide-content">
                <h2>🔥 Promotions exceptionnelles</h2>
                <p>Profitez de nos offres spéciales sur une large sélection de produits</p>
                <div class="slide-buttons">
                    <a href="<?php echo baseUrl('catalogue.php?promotion=1'); ?>" class="btn btn-primary btn-large">Voir les promotions</a>
                </div>
            </div>
        </div>
        <div class="slide">
            <div class="slide-content">
                <h2>🚚 Livraison rapide partout au Maroc</h2>
                <p>Commandez aujourd'hui, recevez rapidement. Service client disponible 7j/7</p>
                <div class="slide-buttons">
                    <a href="<?php echo baseUrl('catalogue.php'); ?>" class="btn btn-primary btn-large">Commander maintenant</a>
                    <a href="<?php echo baseUrl('contact.php'); ?>" class="btn btn-secondary btn-large">Nous contacter</a>
                </div>
            </div>
        </div>
    </div>
    <div class="slider-controls">
        <button class="slider-btn prev" onclick="changeSlide(-1)" aria-label="Slide précédent">‹</button>
        <button class="slider-btn next" onclick="changeSlide(1)" aria-label="Slide suivant">›</button>
    </div>
    <div class="slider-dots">
        <span class="dot active" onclick="currentSlide(1)" aria-label="Slide 1"></span>
        <span class="dot" onclick="currentSlide(2)" aria-label="Slide 2"></span>
        <span class="dot" onclick="currentSlide(3)" aria-label="Slide 3"></span>
    </div>
</section>

<!-- Section avantages -->
<section class="features-section">
    <div class="container">
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon">🚚</div>
                <h3>Livraison rapide</h3>
                <p>Livraison partout au Maroc</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">💰</div>
                <h3>Meilleurs prix</h3>
                <p>Prix compétitifs garantis</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">✅</div>
                <h3>Qualité certifiée</h3>
                <p>Produits de qualité supérieure</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">💬</div>
                <h3>Support client</h3>
                <p>Assistance 7j/7</p>
            </div>
        </div>
    </div>
</section>

<section class="categories-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Nos Catégories</h2>
            <p class="section-subtitle">Explorez notre large gamme de produits organisés par catégories</p>
        </div>
        <div class="categories-grid">
            <?php foreach ($categories as $categorie): 
                $cat_produits = getProduits($categorie['id']);
                $cat_count = count($cat_produits);
            ?>
                <div class="category-card">
                    <a href="<?php echo baseUrl('catalogue.php?categorie=' . $categorie['id']); ?>">
                        <div class="category-image">
                            <?php if ($categorie['image']): ?>
                                <img src="<?php echo baseUrl('uploads/' . $categorie['image']); ?>" alt="<?php echo htmlspecialchars($categorie['nom']); ?>">
                            <?php else: ?>
                                <div class="category-placeholder">
                                    <span class="category-icon">📦</span>
                                </div>
                            <?php endif; ?>
                            <div class="category-overlay">
                                <span class="category-count"><?php echo $cat_count; ?> produit<?php echo $cat_count > 1 ? 's' : ''; ?></span>
                            </div>
                        </div>
                        <div class="category-content">
                            <h3><?php echo htmlspecialchars($categorie['nom']); ?></h3>
                            <p><?php echo htmlspecialchars($categorie['description'] ?? 'Découvrez nos produits'); ?></p>
                            <span class="category-link">Voir les produits →</span>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php if (!empty($produits_promotion)): ?>
<section class="promotions-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">🔥 Promotions du moment</h2>
            <p class="section-subtitle">Profitez de nos offres spéciales limitées dans le temps</p>
        </div>
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
                        <div class="product-header">
                            <h3><a href="<?php echo baseUrl('produit.php?id=' . $produit['id']); ?>"><?php echo htmlspecialchars($produit['nom']); ?></a></h3>
                            <p class="product-category"><?php echo htmlspecialchars($produit['categorie_nom']); ?></p>
                        </div>
                        
                        <?php if ($produit['marque']): ?>
                            <div class="product-brand">
                                <span class="brand-label">Marque:</span>
                                <span class="brand-name"><?php echo htmlspecialchars($produit['marque']); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="product-price">
                            <?php if ($produit['en_promotion'] && $produit['prix_promotion']): ?>
                                <div class="price-wrapper">
                                    <span class="price-old"><?php echo formatPrice($produit['prix']); ?></span>
                                    <span class="price-new"><?php echo formatPrice($produit['prix_promotion']); ?></span>
                                </div>
                                <?php 
                                $reduction = round((($produit['prix'] - $produit['prix_promotion']) / $produit['prix']) * 100);
                                if ($reduction > 0):
                                ?>
                                    <span class="price-discount">-<?php echo $reduction; ?>%</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="price"><?php echo formatPrice($produit['prix']); ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="product-stock">
                            <?php if ($produit['stock'] > 0): ?>
                                <span class="stock-available">
                                    <span class="stock-icon">✓</span>
                                    En stock (<?php echo $produit['stock']; ?>)
                                </span>
                            <?php else: ?>
                                <span class="stock-unavailable">
                                    <span class="stock-icon">✗</span>
                                    Rupture de stock
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="product-actions">
                            <a href="<?php echo baseUrl('produit.php?id=' . $produit['id']); ?>" class="btn btn-view">
                                Voir détails
                            </a>
                            <button class="btn btn-add-cart" 
                                    onclick="addToCart(<?php echo $produit['id']; ?>)" 
                                    <?php echo $produit['stock'] <= 0 ? 'disabled' : ''; ?>>
                                <span class="cart-icon-btn">🛒</span>
                                Ajouter
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="section-footer">
            <a href="<?php echo baseUrl('catalogue.php?promotion=1'); ?>" class="btn btn-primary btn-large">Voir toutes les promotions →</a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

