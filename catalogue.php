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

<section class="catalogue-section">
    <div class="container">
        <div class="catalogue-layout">
            <!-- Sidebar de filtres à gauche -->
            <aside class="catalogue-sidebar">
                <div class="sidebar-header">
                    <h2>Filtres</h2>
                    <button class="sidebar-toggle" id="sidebarToggle">☰</button>
                </div>
                
                <div class="sidebar-content" id="sidebarContent">
                    <div class="filter-group">
                        <h3 class="filter-title">Catégories</h3>
                        <ul class="filter-list">
                            <li>
                                <a href="<?php echo baseUrl('catalogue.php'); ?>" 
                                   class="filter-link <?php echo !$categorie_id && !$en_promotion ? 'active' : ''; ?>">
                                    <span class="filter-icon">📦</span>
                                    <span>Tous les produits</span>
                                    <span class="filter-count"><?php echo $total_produits; ?></span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo baseUrl('catalogue.php?promotion=1'); ?>" 
                                   class="filter-link <?php echo $en_promotion ? 'active' : ''; ?>">
                                    <span class="filter-icon">🔥</span>
                                    <span>Promotions</span>
                                    <span class="filter-count"><?php echo $total_promotions; ?></span>
                                </a>
                            </li>
                            <?php foreach ($categories as $cat): 
                                $cat_produits = getProduits($cat['id']);
                                $cat_count = count($cat_produits);
                            ?>
                                <li>
                                    <a href="<?php echo baseUrl('catalogue.php?categorie=' . $cat['id']); ?>" 
                                       class="filter-link <?php echo $categorie_id == $cat['id'] ? 'active' : ''; ?>">
                                        <span class="filter-icon">📋</span>
                                        <span><?php echo htmlspecialchars($cat['nom']); ?></span>
                                        <span class="filter-count"><?php echo $cat_count; ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    
                    <div class="filter-group">
                        <h3 class="filter-title">Informations</h3>
                        <div class="filter-info">
                            <p>Parcourez notre catalogue complet de matériaux de construction, quincaillerie et outillage.</p>
                            <p>Tous nos produits sont disponibles en stock et livrés rapidement.</p>
                        </div>
                    </div>
                </div>
            </aside>

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

