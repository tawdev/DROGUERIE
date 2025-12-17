<?php
/**
 * Détail d'une commande (admin)
 */
require_once 'auth.php';
require_once '../includes/functions.php';

$page_title = 'Détail de la commande';

$commande_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$pdo = getDB();

$stmt = $pdo->prepare("SELECT * FROM commandes WHERE id = ?");
$stmt->execute([$commande_id]);
$commande = $stmt->fetch();

if (!$commande) {
    redirect('commandes.php');
}

// Récupérer les détails de la commande
$stmt = $pdo->prepare("SELECT cd.*, p.nom as produit_nom, p.image as produit_image 
                       FROM commande_details cd 
                       JOIN produits p ON cd.produit_id = p.id 
                       WHERE cd.commande_id = ?");
$stmt->execute([$commande_id]);
$details = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="admin-page">
    <h1>Commande #<?php echo $commande['id']; ?></h1>
    
    <div class="order-detail">
        <div class="order-info">
            <h2>Informations client</h2>
            <p><strong>Nom:</strong> <?php echo htmlspecialchars($commande['nom_client']); ?></p>
            <p><strong>Téléphone:</strong> <?php echo htmlspecialchars($commande['telephone']); ?></p>
            <?php if ($commande['email']): ?>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($commande['email']); ?></p>
            <?php endif; ?>
            <p><strong>Adresse:</strong> <?php echo nl2br(htmlspecialchars($commande['adresse'])); ?></p>
            <p><strong>Ville:</strong> <?php echo htmlspecialchars($commande['ville']); ?></p>
            <?php if ($commande['code_postal']): ?>
                <p><strong>Code postal:</strong> <?php echo htmlspecialchars($commande['code_postal']); ?></p>
            <?php endif; ?>
            <p><strong>Statut:</strong> <span class="badge badge-<?php echo $commande['statut']; ?>"><?php echo $commande['statut']; ?></span></p>
            <p><strong>Date:</strong> <?php echo date('d/m/Y H:i', strtotime($commande['created_at'])); ?></p>
            <?php if ($commande['notes']): ?>
                <p><strong>Notes:</strong> <?php echo nl2br(htmlspecialchars($commande['notes'])); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="order-items-detail">
            <h2>Produits commandés</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Prix unitaire</th>
                        <th>Sous-total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($details as $detail): ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <?php if ($detail['produit_image']): ?>
                                        <img src="<?php echo baseUrl('uploads/' . $detail['produit_image']); ?>" alt="" class="admin-thumb">
                                    <?php endif; ?>
                                    <?php echo htmlspecialchars($detail['produit_nom']); ?>
                                </div>
                            </td>
                            <td><?php echo $detail['quantite']; ?></td>
                            <td><?php echo formatPrice($detail['prix_unitaire']); ?></td>
                            <td><?php echo formatPrice($detail['sous_total']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">Total</th>
                        <th><?php echo formatPrice($commande['total']); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    
    <div class="form-actions">
        <a href="commande_edit.php?id=<?php echo $commande['id']; ?>" class="btn btn-primary">Modifier le statut</a>
        <a href="commandes.php" class="btn btn-secondary">Retour à la liste</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

