<?php
/**
 * Modifier le statut d'une commande (admin)
 */
require_once 'auth.php';
require_once '../includes/functions.php';

$page_title = 'Modifier une commande';

$commande_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$pdo = getDB();

$stmt = $pdo->prepare("SELECT * FROM commandes WHERE id = ?");
$stmt->execute([$commande_id]);
$commande = $stmt->fetch();

if (!$commande) {
    redirect('commandes.php');
}

$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $statut = sanitize($_POST['statut'] ?? '');
    $notes = sanitize($_POST['notes'] ?? '');
    
    $stmt = $pdo->prepare("UPDATE commandes SET statut = ?, notes = ? WHERE id = ?");
    $stmt->execute([$statut, $notes, $commande_id]);
    $success = true;
    
    $stmt = $pdo->prepare("SELECT * FROM commandes WHERE id = ?");
    $stmt->execute([$commande_id]);
    $commande = $stmt->fetch();
}

include 'includes/header.php';
?>

<div class="admin-page">
    <h1>Modifier la commande #<?php echo $commande['id']; ?></h1>
    
    <?php if ($success): ?>
        <div class="alert alert-success">Commande modifiée avec succès !</div>
    <?php endif; ?>
    
    <form method="POST" class="admin-form">
        <div class="form-group">
            <label for="statut">Statut *</label>
            <select id="statut" name="statut" required>
                <option value="en_attente" <?php echo $commande['statut'] == 'en_attente' ? 'selected' : ''; ?>>En attente</option>
                <option value="confirmee" <?php echo $commande['statut'] == 'confirmee' ? 'selected' : ''; ?>>Confirmée</option>
                <option value="expediee" <?php echo $commande['statut'] == 'expediee' ? 'selected' : ''; ?>>Expédiée</option>
                <option value="livree" <?php echo $commande['statut'] == 'livree' ? 'selected' : ''; ?>>Livrée</option>
                <option value="annulee" <?php echo $commande['statut'] == 'annulee' ? 'selected' : ''; ?>>Annulée</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea id="notes" name="notes" rows="4"><?php echo htmlspecialchars($commande['notes']); ?></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="commande_detail.php?id=<?php echo $commande['id']; ?>" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

