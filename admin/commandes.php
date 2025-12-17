<?php
/**
 * Liste des commandes (admin)
 */
require_once 'auth.php';
require_once '../includes/functions.php';

$page_title = 'Gestion des commandes';

$pdo = getDB();
$stmt = $pdo->query("SELECT * FROM commandes ORDER BY created_at DESC");
$commandes = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="admin-page">
    <h1>Gestion des commandes</h1>
    
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Téléphone</th>
                <th>Ville</th>
                <th>Total</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($commandes as $commande): ?>
                <tr>
                    <td>#<?php echo $commande['id']; ?></td>
                    <td><?php echo htmlspecialchars($commande['nom_client']); ?></td>
                    <td><?php echo htmlspecialchars($commande['telephone']); ?></td>
                    <td><?php echo htmlspecialchars($commande['ville']); ?></td>
                    <td><?php echo formatPrice($commande['total']); ?></td>
                    <td>
                        <span class="badge badge-<?php echo $commande['statut']; ?>">
                            <?php 
                            $statuts = [
                                'en_attente' => 'En attente',
                                'confirmee' => 'Confirmée',
                                'expediee' => 'Expédiée',
                                'livree' => 'Livrée',
                                'annulee' => 'Annulée'
                            ];
                            echo $statuts[$commande['statut']] ?? $commande['statut'];
                            ?>
                        </span>
                    </td>
                    <td><?php echo date('d/m/Y H:i', strtotime($commande['created_at'])); ?></td>
                    <td>
                        <a href="commande_detail.php?id=<?php echo $commande['id']; ?>" class="btn btn-small">Voir</a>
                        <a href="commande_edit.php?id=<?php echo $commande['id']; ?>" class="btn btn-small">Modifier</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>

