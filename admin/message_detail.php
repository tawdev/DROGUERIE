<?php
/**
 * Détail d'un message de contact (admin)
 */
require_once 'auth.php';
require_once '../includes/functions.php';

$page_title = 'Détail du message';

$message_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$message_id) {
    redirect(baseUrl('admin/messages_contact.php'));
}

$pdo = getDB();
$stmt = $pdo->prepare("SELECT * FROM messages_contact WHERE id = ?");
$stmt->execute([$message_id]);
$message = $stmt->fetch();

if (!$message) {
    redirect(baseUrl('admin/messages_contact.php'));
}

// Marquer comme lu si ce n'est pas déjà fait
if (!$message['lu']) {
    markMessageAsRead($message_id);
    $message['lu'] = 1;
}

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'delete':
                $stmt = $pdo->prepare("DELETE FROM messages_contact WHERE id = ?");
                $stmt->execute([$message_id]);
                redirect(baseUrl('admin/messages_contact.php'));
                break;
        }
    }
}

include 'includes/header.php';
?>

<div class="admin-page">
    <div class="admin-page-header">
        <h1>Détail du message</h1>
        <a href="messages_contact.php" class="btn btn-secondary">← Retour à la liste</a>
    </div>
    
    <div class="message-detail">
        <div class="message-header">
            <div class="message-info">
                <h2><?php echo htmlspecialchars($message['nom']); ?></h2>
                <div class="message-meta">
                    <span class="meta-item">
                        <strong>Email:</strong> 
                        <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>">
                            <?php echo htmlspecialchars($message['email']); ?>
                        </a>
                    </span>
                    <?php if ($message['telephone']): ?>
                        <span class="meta-item">
                            <strong>Téléphone:</strong> 
                            <a href="tel:<?php echo htmlspecialchars($message['telephone']); ?>">
                                <?php echo htmlspecialchars($message['telephone']); ?>
                            </a>
                        </span>
                    <?php endif; ?>
                    <span class="meta-item">
                        <strong>Date:</strong> 
                        <?php echo date('d/m/Y à H:i', strtotime($message['created_at'])); ?>
                    </span>
                    <?php if ($message['sujet']): ?>
                        <span class="meta-item">
                            <strong>Sujet:</strong> 
                            <?php 
                            $sujets = [
                                'question' => 'Question générale',
                                'commande' => 'Question sur une commande',
                                'produit' => 'Question sur un produit',
                                'livraison' => 'Livraison',
                                'autre' => 'Autre'
                            ];
                            echo $sujets[$message['sujet']] ?? $message['sujet'];
                            ?>
                        </span>
                    <?php endif; ?>
                    <span class="meta-item">
                        <strong>Statut:</strong> 
                        <?php if ($message['lu']): ?>
                            <span class="badge badge-read">Lu</span>
                        <?php else: ?>
                            <span class="badge badge-unread">Non lu</span>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        </div>
        
        <div class="message-content">
            <h3>Message</h3>
            <div class="message-text">
                <?php echo nl2br(htmlspecialchars($message['message'])); ?>
            </div>
        </div>
        
        <div class="message-actions">
            <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>?subject=Re: <?php echo urlencode($message['sujet'] ?: 'Votre message'); ?>" class="btn btn-primary">
                Répondre par email
            </a>
            <?php if ($message['telephone']): ?>
                <a href="tel:<?php echo htmlspecialchars($message['telephone']); ?>" class="btn btn-secondary">
                    Appeler
                </a>
            <?php endif; ?>
            <form method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');">
                <input type="hidden" name="action" value="delete">
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

