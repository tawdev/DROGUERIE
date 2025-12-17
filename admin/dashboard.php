<?php
/**
 * Tableau de bord admin
 */
require_once 'auth.php';
require_once '../includes/functions.php';

$page_title = 'Tableau de bord';

// Statistiques
$pdo = getDB();

$stats = [
    'produits' => $pdo->query("SELECT COUNT(*) FROM produits WHERE actif = 1")->fetchColumn(),
    'commandes' => $pdo->query("SELECT COUNT(*) FROM commandes")->fetchColumn(),
    'commandes_attente' => $pdo->query("SELECT COUNT(*) FROM commandes WHERE statut = 'en_attente'")->fetchColumn(),
    'categories' => $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn(),
    'messages_non_lus' => $pdo->query("SELECT COUNT(*) FROM messages_contact WHERE lu = 0")->fetchColumn(),
];

// Dernières commandes
$stmt = $pdo->query("SELECT * FROM commandes ORDER BY created_at DESC LIMIT 5");
$dernieres_commandes = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="admin-dashboard">
    <h1>Tableau de bord</h1>
    
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Produits</h3>
            <p class="stat-number"><?php echo $stats['produits']; ?></p>
        </div>
        <div class="stat-card">
            <h3>Commandes</h3>
            <p class="stat-number"><?php echo $stats['commandes']; ?></p>
        </div>
        <div class="stat-card">
            <h3>En attente</h3>
            <p class="stat-number"><?php echo $stats['commandes_attente']; ?></p>
        </div>
        <div class="stat-card">
            <h3>Catégories</h3>
            <p class="stat-number"><?php echo $stats['categories']; ?></p>
        </div>
        <div class="stat-card">
            <h3>Messages non lus</h3>
            <p class="stat-number">
                <a href="<?php echo baseUrl('admin/messages_contact.php?filter=unread'); ?>" style="color: inherit; text-decoration: none;">
                    <?php echo $stats['messages_non_lus']; ?>
                </a>
            </p>
        </div>
    </div>
    
    <div class="dashboard-section">
        <h2>Dernières commandes</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Téléphone</th>
                    <th>Total</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dernieres_commandes as $commande): ?>
                    <tr>
                        <td>#<?php echo $commande['id']; ?></td>
                        <td><?php echo htmlspecialchars($commande['nom_client']); ?></td>
                        <td><?php echo htmlspecialchars($commande['telephone']); ?></td>
                        <td><?php echo formatPrice($commande['total']); ?></td>
                        <td><span class="badge badge-<?php echo $commande['statut']; ?>"><?php echo $commande['statut']; ?></span></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($commande['created_at'])); ?></td>
                        <td><a href="commande_detail.php?id=<?php echo $commande['id']; ?>" class="btn btn-small">Voir</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

