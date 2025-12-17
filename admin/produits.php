<?php
/**
 * Liste des produits (admin)
 */
require_once 'auth.php';
require_once '../includes/functions.php';

$page_title = 'Gestion des produits';

$pdo = getDB();
$stmt = $pdo->query("SELECT p.*, c.nom as categorie_nom FROM produits p JOIN categories c ON p.categorie_id = c.id ORDER BY p.created_at DESC");
$produits = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="admin-page">
    <div class="page-header">
        <h1>Gestion des produits</h1>
        <a href="produit_ajout.php" class="btn btn-primary">Ajouter un produit</a>
    </div>
    
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Prix</th>
                <th>Stock</th>
                <th>Promo</th>
                <th>Actif</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produits as $produit): ?>
                <tr>
                    <td><?php echo $produit['id']; ?></td>
                    <td>
                        <?php if ($produit['image']): ?>
                            <img src="<?php echo baseUrl('uploads/' . $produit['image']); ?>" alt="" class="admin-thumb">
                        <?php else: ?>
                            <span class="no-image">-</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($produit['nom']); ?></td>
                    <td><?php echo htmlspecialchars($produit['categorie_nom']); ?></td>
                    <td>
                        <?php echo formatPrice($produit['prix']); ?>
                        <?php if ($produit['prix_promotion']): ?>
                            <br><small style="color: #e74c3c;"><?php echo formatPrice($produit['prix_promotion']); ?></small>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $produit['stock']; ?></td>
                    <td><?php echo $produit['en_promotion'] ? '✓' : '-'; ?></td>
                    <td><?php echo $produit['actif'] ? '✓' : '-'; ?></td>
                    <td>
                        <a href="produit_edit.php?id=<?php echo $produit['id']; ?>" class="btn btn-small">Modifier</a>
                        <a href="produit_delete.php?id=<?php echo $produit['id']; ?>" class="btn btn-small btn-danger" 
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>

