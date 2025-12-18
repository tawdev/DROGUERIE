<?php
/**
 * Page À propos
 */
require_once 'config/config.php';
require_once 'includes/functions.php';

$page_title = 'À propos';

include 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <a href="<?php echo baseUrl(); ?>" class="btn-back">
            <span class="back-icon">←</span> Retour à l'accueil
        </a>
        <h1>À propos de nous</h1>
        <p>Découvrez notre histoire et nos valeurs</p>
    </div>
</section>

<section class="about-section">
    <div class="container">
        <!-- Section Histoire avec image -->
        <div class="about-story">
            <div class="about-story-content">
                <div class="about-story-text">
                    <h2>Notre histoire</h2>
                    <p>
                        Droguerie Maroc est née de la passion pour offrir aux familles marocaines 
                        un accès facile et rapide à des produits de qualité pour leurs projets de construction et rénovation. 
                        Fondée avec l'ambition de moderniser l'expérience d'achat de matériaux de construction et de quincaillerie, 
                        nous avons créé une plateforme en ligne qui combine commodité, qualité et service client exceptionnel.
                    </p>
                    <p>
                        Depuis nos débuts, nous nous engageons à proposer une large gamme de produits 
                        soigneusement sélectionnés, allant des matériaux de construction aux outils, 
                        en passant par la plomberie, l'électricité et bien plus encore.
                    </p>
                </div>
                <div class="about-story-image">
                    <div class="story-image-placeholder">
                        <div class="image-icon">🏗️</div>
                        <div class="image-text">Notre équipe</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Section Mission, Valeurs, Vision -->
        <div class="about-features">
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <div class="feature-icon">🎯</div>
                </div>
                <h3>Notre mission</h3>
                <p>
                    Faciliter l'accès aux matériaux de construction et produits de quincaillerie de qualité 
                    pour tous les Marocains, en offrant un service en ligne pratique, fiable et accessible.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <div class="feature-icon">⭐</div>
                </div>
                <h3>Nos valeurs</h3>
                <p>
                    Qualité, transparence, service client et engagement envers la satisfaction 
                    de nos clients sont au cœur de tout ce que nous faisons.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <div class="feature-icon">🚀</div>
                </div>
                <h3>Notre vision</h3>
                <p>
                    Devenir la référence en matière de matériaux de construction et quincaillerie en ligne au Maroc, 
                    en continuant à innover et à améliorer l'expérience client.
                </p>
            </div>
        </div>
        
        <!-- Section Pourquoi nous choisir avec images -->
        <div class="about-why">
            <h2>Pourquoi nous choisir ?</h2>
            <div class="why-list">
                <div class="why-item">
                    <div class="why-icon-wrapper">
                        <span class="why-icon">✓</span>
                    </div>
                    <div class="why-content">
                        <h4>Produits de qualité</h4>
                        <p>Nous sélectionnons rigoureusement nos produits pour garantir leur qualité et leur efficacité.</p>
                    </div>
                    <div class="why-image">
                        <div class="why-image-placeholder">
                            <span class="why-image-icon">⭐</span>
                        </div>
                    </div>
                </div>
                
                <div class="why-item">
                    <div class="why-icon-wrapper">
                        <span class="why-icon">✓</span>
                    </div>
                    <div class="why-content">
                        <h4>Livraison rapide</h4>
                        <p>Service de livraison efficace pour vous faire recevoir vos commandes dans les meilleurs délais.</p>
                    </div>
                    <div class="why-image">
                        <div class="why-image-placeholder">
                            <span class="why-image-icon">🚚</span>
                        </div>
                    </div>
                </div>
                
                <div class="why-item">
                    <div class="why-icon-wrapper">
                        <span class="why-icon">✓</span>
                    </div>
                    <div class="why-content">
                        <h4>Prix compétitifs</h4>
                        <p>Des prix attractifs et des promotions régulières pour vous offrir le meilleur rapport qualité-prix.</p>
                    </div>
                    <div class="why-image">
                        <div class="why-image-placeholder">
                            <span class="why-image-icon">💰</span>
                        </div>
                    </div>
                </div>
                
                <div class="why-item">
                    <div class="why-icon-wrapper">
                        <span class="why-icon">✓</span>
                    </div>
                    <div class="why-content">
                        <h4>Service client dédié</h4>
                        <p>Une équipe à votre écoute pour répondre à toutes vos questions et vous accompagner.</p>
                    </div>
                    <div class="why-image">
                        <div class="why-image-placeholder">
                            <span class="why-image-icon">💬</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Section Statistiques -->
        <div class="about-stats">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-icon">📦</div>
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Produits</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">👥</div>
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">Clients satisfaits</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">🚚</div>
                    <div class="stat-number">24h</div>
                    <div class="stat-label">Livraison rapide</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">⭐</div>
                    <div class="stat-number">4.8/5</div>
                    <div class="stat-label">Note moyenne</div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

