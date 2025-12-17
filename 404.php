<?php
/**
 * Page 404 - Produit non trouvé
 */
require_once 'config/config.php';
$page_title = 'Page non trouvée';
include 'includes/header.php';
?>

<section class="error-section">
    <div class="container">
        <div class="error-content">
            <h1>404</h1>
            <h2>Produit non trouvé</h2>
            <p>Le produit que vous recherchez n'existe pas ou n'est plus disponible.</p>
            <a href="<?php echo baseUrl('catalogue.php'); ?>" class="btn btn-primary">Voir le catalogue</a>
            <a href="<?php echo baseUrl(); ?>" class="btn btn-secondary">Retour à l'accueil</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

