<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Admin - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo baseUrl('assets/css/style.css'); ?>">
    <link rel="stylesheet" href="<?php echo baseUrl('assets/css/admin.css'); ?>">
</head>
<body>
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="admin-logo">
                <h2>Admin</h2>
            </div>
            <nav class="admin-nav">
                <ul>
                    <li><a href="<?php echo baseUrl('admin/dashboard.php'); ?>">Tableau de bord</a></li>
                    <li><a href="<?php echo baseUrl('admin/produits.php'); ?>">Produits</a></li>
                    <li><a href="<?php echo baseUrl('admin/produit_ajout.php'); ?>">Ajouter un produit</a></li>
                    <li><a href="<?php echo baseUrl('admin/categories.php'); ?>">Catégories</a></li>
                    <li><a href="<?php echo baseUrl('admin/associer_images_categories.php'); ?>">Associer images catégories</a></li>
                    <li><a href="<?php echo baseUrl('admin/commandes.php'); ?>">Commandes</a></li>
                    <li><a href="<?php echo baseUrl('admin/messages_contact.php'); ?>">Messages de contact</a></li>
                    <li><a href="<?php echo baseUrl('admin/logout.php'); ?>">Déconnexion</a></li>
                </ul>
            </nav>
        </aside>
        <main class="admin-main">
            <header class="admin-header">
                <h1><?php echo $page_title ?? 'Administration'; ?></h1>
                <div class="admin-user">
                    Connecté en tant que: <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong>
                    <a href="<?php echo baseUrl(); ?>" target="_blank">Voir le site</a>
                </div>
            </header>
            <div class="admin-content">

