<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="<?php echo baseUrl('assets/css/style.css'); ?>">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-top">
                <div class="logo">
                    <a href="<?php echo baseUrl(); ?>">
                        <span class="logo-main">Droguerie</span>
                        <span class="logo-sub">Maroc</span>
                    </a>
                </div>
                <nav class="main-nav" id="mainNav">
                    <ul>
                        <li><a href="<?php echo baseUrl(); ?>">Accueil</a></li>
                        <li><a href="<?php echo baseUrl('catalogue.php'); ?>">Catalogue</a></li>
                        <li><a href="<?php echo baseUrl('apropos.php'); ?>">À propos</a></li>
                        <li><a href="<?php echo baseUrl('nos.php'); ?>">Nos services</a></li>
                        <li><a href="<?php echo baseUrl('contact.php'); ?>">Contact</a></li>
                    </ul>
                </nav>
                <div class="header-actions">
                    <a href="<?php echo baseUrl('panier.php'); ?>" class="cart-link">
                        <span class="cart-icon">🛒</span>
                        <span class="cart-count"><?php echo getPanierCount(); ?></span>
                    </a>
                    <button class="mobile-menu-toggle" id="mobileMenuToggle">☰</button>
                </div>
            </div>
        </div>
    </header>
    <main class="main-content">
