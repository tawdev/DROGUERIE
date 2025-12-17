<?php
/**
 * Gestion des catégories (admin)
 */
require_once 'auth.php';
require_once '../includes/functions.php';

$page_title = 'Gestion des catégories';

$pdo = getDB();
$stmt = $pdo->query("SELECT * FROM categories ORDER BY ordre ASC, nom ASC");
$categories = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="admin-page">
    <h1>Gestion des catégories</h1>
    
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Description</th>
                <th>Ordre</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $categorie): ?>
                <tr>
                    <td><?php echo $categorie['id']; ?></td>
                    <td><?php echo htmlspecialchars($categorie['nom']); ?></td>
                    <td><?php echo htmlspecialchars(substr($categorie['description'] ?? '', 0, 50)); ?>...</td>
                    <td><?php echo $categorie['ordre']; ?></td>
                    <td>
                        <a href="categorie_edit.php?id=<?php echo $categorie['id']; ?>" class="btn btn-small">Modifier</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>

