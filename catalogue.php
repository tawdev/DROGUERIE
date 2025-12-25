<?php
/**
 * Page catalogue des produits
 */
require_once 'config/config.php';
require_once 'includes/functions.php';

$page_title = 'Catalogue';

// Récupérer les paramètres
$categorie_id = isset($_GET['categorie']) ? (int)$_GET['categorie'] : null;
$en_promotion = isset($_GET['promotion']) && $_GET['promotion'] == '1';

// Récupérer les produits
$produits = getProduits($categorie_id, null, $en_promotion);
$categories = getCategories();

// Calculer les totaux pour les filtres
$total_produits = count(getProduits());
$total_promotions = count(getProduits(null, null, true));

// Récupérer le nom de la catégorie si sélectionnée
$categorie_nom = null;
if ($categorie_id) {
    $categorie = getCategorie($categorie_id);
    $categorie_nom = $categorie ? $categorie['nom'] : null;
}

include 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <a href="<?php echo $categorie_id ? baseUrl('catalogue.php') : baseUrl(); ?>" class="btn-back">
            <span class="back-icon">←</span> <?php echo $categorie_id ? 'Retour au catalogue' : 'Retour à l\'accueil'; ?>
        </a>
        <h1>
            <?php 
            if ($en_promotion) {
                echo 'Promotions';
            } elseif ($categorie_nom) {
                echo htmlspecialchars($categorie_nom);
            } else {
                echo 'Catalogue des produits';
            }
            ?>
        </h1>
        <p>
            <?php 
            if ($en_promotion) {
                echo 'Découvrez nos offres spéciales et économisez sur vos produits préférés';
            } elseif ($categorie_nom) {
                echo 'Explorez notre sélection de produits dans cette catégorie';
            } else {
                echo 'Découvrez notre large gamme de produits de qualité';
            }
            ?>
        </p>
    </div>
</section>

<!-- Section Catégories en Slider -->
<section class="categories-slider-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Parcourir par catégorie</h2>
            <p class="section-subtitle">Sélectionnez une catégorie pour découvrir nos produits</p>
        </div>
        <div class="categories-slider-wrapper">
            <div class="categories-slider" id="categoriesSlider">
                <div class="categories-slider-track">
                    <div class="category-card-slide <?php echo !$categorie_id && !$en_promotion ? 'active' : ''; ?>">
                        <a href="<?php echo baseUrl('catalogue.php'); ?>" class="category-card-link <?php echo !$categorie_id && !$en_promotion ? 'active' : ''; ?>">
                            <div class="category-card-image">
                                <div class="category-placeholder">
                                    <span class="category-icon">📦</span>
                                </div>
                                <?php if (!$categorie_id && !$en_promotion): ?>
                                    <div class="category-active-badge">✓ Actif</div>
                                <?php endif; ?>
                            </div>
                            <div class="category-card-content">
                                <h3>Tous les produits</h3>
                                <p><?php echo $total_produits; ?> produit<?php echo $total_produits > 1 ? 's' : ''; ?></p>
                                <span class="category-link">Voir tout →</span>
                            </div>
                        </a>
                    </div>
                    <div class="category-card-slide <?php echo $en_promotion ? 'active' : ''; ?>">
                        <a href="<?php echo baseUrl('catalogue.php?promotion=1'); ?>" class="category-card-link <?php echo $en_promotion ? 'active' : ''; ?>">
                            <div class="category-card-image">
                                <div class="category-placeholder promo">
                                    <span class="category-icon">🔥</span>
                                </div>
                                <?php if ($en_promotion): ?>
                                    <div class="category-active-badge">✓ Actif</div>
                                <?php endif; ?>
                            </div>
                            <div class="category-card-content">
                                <h3>Promotions</h3>
                                <p><?php echo $total_promotions; ?> offre<?php echo $total_promotions > 1 ? 's' : ''; ?></p>
                                <span class="category-link">Voir les promos →</span>
                            </div>
                        </a>
                    </div>
                    <?php foreach ($categories as $categorie): 
                        $cat_produits = getProduits($categorie['id']);
                        $cat_count = count($cat_produits);
                        $isActive = ($categorie_id == $categorie['id']);
                    ?>
                        <div class="category-card-slide <?php echo $isActive ? 'active' : ''; ?>">
                            <a href="<?php echo baseUrl('catalogue.php?categorie=' . $categorie['id']); ?>" class="category-card-link <?php echo $isActive ? 'active' : ''; ?>">
                                <div class="category-card-image">
                                    <?php if ($categorie['image']): ?>
                                        <img src="<?php echo baseUrl('uploads/' . $categorie['image']); ?>" alt="<?php echo htmlspecialchars($categorie['nom']); ?>">
                                    <?php else: ?>
                                        <div class="category-placeholder">
                                            <span class="category-icon">📋</span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="category-overlay">
                                        <span class="category-count"><?php echo $cat_count; ?> produit<?php echo $cat_count > 1 ? 's' : ''; ?></span>
                                    </div>
                                    <?php if ($isActive): ?>
                                        <div class="category-active-badge">✓ Actif</div>
                                    <?php endif; ?>
                                </div>
                                <div class="category-card-content">
                                    <h3><?php echo htmlspecialchars($categorie['nom']); ?></h3>
                                    <p><?php echo htmlspecialchars($categorie['description'] ?? 'Découvrez nos produits'); ?></p>
                                    <span class="category-link">Voir les produits →</span>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <button class="slider-nav-btn slider-prev" id="categoriesPrev" aria-label="Catégories précédentes">‹</button>
            <button class="slider-nav-btn slider-next" id="categoriesNext" aria-label="Catégories suivantes">›</button>
            <div class="slider-dots-categories" id="categoriesDots"></div>
        </div>
    </div>
</section>

<section class="catalogue-section">
    <div class="container">
        <div class="catalogue-layout">
            <!-- Contenu principal -->
            <main class="catalogue-main">
                <?php if (empty($produits)): ?>
            <div class="no-products">
                <div class="no-products-icon">📦</div>
                <h2>Aucun produit trouvé</h2>
                <p>Il n'y a actuellement aucun produit disponible dans cette catégorie.</p>
                <a href="<?php echo baseUrl('catalogue.php'); ?>" class="btn btn-primary">Voir tout le catalogue</a>
            </div>
        <?php else: ?>
                <div class="catalogue-toolbar">
                    <div class="products-count">
                        <span class="count-number"><?php echo count($produits); ?></span>
                        <span class="count-text">produit<?php echo count($produits) > 1 ? 's' : ''; ?> trouvé<?php echo count($produits) > 1 ? 's' : ''; ?></span>
                    </div>
                </div>
                
                <div class="products-grid">
                <?php foreach ($produits as $produit): ?>
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
                            <h3 class="product-name">
                                <a href="<?php echo baseUrl('produit.php?id=' . $produit['id']); ?>">
                                    <?php echo htmlspecialchars($produit['nom']); ?>
                                </a>
                            </h3>
                            
                            <div class="product-price">
                                <?php if ($produit['en_promotion'] && $produit['prix_promotion']): ?>
                                    <div class="price-container">
                                        <span class="price-old"><?php echo formatPrice($produit['prix']); ?></span>
                                        <span class="price-new"><?php echo formatPrice($produit['prix_promotion']); ?></span>
                                    </div>
                                <?php else: ?>
                                    <span class="price"><?php echo formatPrice($produit['prix']); ?></span>
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
                <?php endif; ?>
            </main>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

