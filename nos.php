<?php
/**
 * Page Nos services / À propos de nous
 */
require_once 'config/config.php';
require_once 'includes/functions.php';

$page_title = 'Nos services';

include 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>Nos services</h1>
        <p>Découvrez tout ce que nous vous offrons</p>
    </div>
</section>

<section class="services-section">
    <div class="container">
        <div class="services-intro">
            <p>
                Chez Droguerie Maroc, nous nous engageons à vous offrir une expérience d'achat 
                complète et satisfaisante. Découvrez tous les services que nous mettons à votre disposition.
            </p>
        </div>
        
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon">🛒</div>
                <h3>Achat en ligne</h3>
                <p>
                    Parcourez notre catalogue complet de produits depuis le confort de votre domicile. 
                    Interface intuitive et recherche facile pour trouver rapidement ce dont vous avez besoin.
                </p>
            </div>
            
            <div class="service-card">
                <div class="service-icon">🚚</div>
                <h3>Livraison à domicile</h3>
                <p>
                    Service de livraison rapide et fiable partout au Maroc. 
                    Commandez aujourd'hui et recevez vos produits dans les meilleurs délais.
                </p>
            </div>
            
            <div class="service-card">
                <div class="service-icon">💰</div>
                <h3>Promotions régulières</h3>
                <p>
                    Profitez de nos offres spéciales et promotions tout au long de l'année. 
                    Des réductions attractives sur une large sélection de produits.
                </p>
            </div>
            
            <div class="service-card">
                <div class="service-icon">📦</div>
                <h3>Large gamme de produits</h3>
                <p>
                    Des produits d'entretien ménager aux articles d'hygiène personnelle, 
                    en passant par les accessoires de beauté. Tout ce dont vous avez besoin en un seul endroit.
                </p>
            </div>
            
            <div class="service-card">
                <div class="service-icon">🔒</div>
                <h3>Paiement sécurisé</h3>
                <p>
                    Transactions sécurisées pour votre tranquillité d'esprit. 
                    Plusieurs méthodes de paiement disponibles pour votre commodité.
                </p>
            </div>
            
            <div class="service-card">
                <div class="service-icon">💬</div>
                <h3>Support client</h3>
                <p>
                    Une équipe dédiée à votre service pour répondre à toutes vos questions 
                    et vous accompagner dans vos achats. Nous sommes là pour vous aider.
                </p>
            </div>
        </div>
        
        <div class="services-process">
            <h2>Comment ça marche ?</h2>
            <div class="process-steps">
                <div class="process-step">
                    <div class="step-number">1</div>
                    <h4>Parcourez le catalogue</h4>
                    <p>Explorez notre large sélection de produits organisés par catégories</p>
                </div>
                <div class="process-step">
                    <div class="step-number">2</div>
                    <h4>Ajoutez au panier</h4>
                    <p>Sélectionnez les produits de votre choix et ajoutez-les à votre panier</p>
                </div>
                <div class="process-step">
                    <div class="step-number">3</div>
                    <h4>Passez commande</h4>
                    <p>Remplissez vos informations et validez votre commande en toute sécurité</p>
                </div>
                <div class="process-step">
                    <div class="step-number">4</div>
                    <h4>Recevez vos produits</h4>
                    <p>Profitez de la livraison rapide et recevez vos produits à domicile</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

