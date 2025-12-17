<?php
/**
 * Liste des messages de contact (admin)
 */
require_once 'auth.php';
require_once '../includes/functions.php';

$page_title = 'Messages de contact';

$pdo = getDB();

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'mark_read':
                $message_id = (int)$_POST['message_id'];
                markMessageAsRead($message_id);
                redirect(baseUrl('admin/messages_contact.php'));
                break;
            case 'delete':
                $message_id = (int)$_POST['message_id'];
                $stmt = $pdo->prepare("DELETE FROM messages_contact WHERE id = ?");
                $stmt->execute([$message_id]);
                redirect(baseUrl('admin/messages_contact.php'));
                break;
        }
    }
}

// Filtre
$filter = $_GET['filter'] ?? 'all';
$sql = "SELECT * FROM messages_contact";
$params = [];

if ($filter === 'unread') {
    $sql .= " WHERE lu = 0";
} elseif ($filter === 'read') {
    $sql .= " WHERE lu = 1";
}

$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$messages = $stmt->fetchAll();

// Statistiques
$stats = [
    'total' => $pdo->query("SELECT COUNT(*) FROM messages_contact")->fetchColumn(),
    'unread' => $pdo->query("SELECT COUNT(*) FROM messages_contact WHERE lu = 0")->fetchColumn(),
    'read' => $pdo->query("SELECT COUNT(*) FROM messages_contact WHERE lu = 1")->fetchColumn(),
];

include 'includes/header.php';
?>

<div class="admin-page">
    <div class="admin-page-header">
        <h1>Messages de contact</h1>
        <div class="admin-filters">
            <a href="?filter=all" class="filter-btn <?php echo $filter === 'all' ? 'active' : ''; ?>">
                Tous (<?php echo $stats['total']; ?>)
            </a>
            <a href="?filter=unread" class="filter-btn <?php echo $filter === 'unread' ? 'active' : ''; ?>">
                Non lus (<?php echo $stats['unread']; ?>)
            </a>
            <a href="?filter=read" class="filter-btn <?php echo $filter === 'read' ? 'active' : ''; ?>">
                Lus (<?php echo $stats['read']; ?>)
            </a>
        </div>
    </div>
    
    <?php if (empty($messages)): ?>
        <div class="admin-empty">
            <p>Aucun message trouvé.</p>
        </div>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Sujet</th>
                    <th>Message</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $message): ?>
                    <tr class="<?php echo !$message['lu'] ? 'unread' : ''; ?>">
                        <td><?php echo date('d/m/Y H:i', strtotime($message['created_at'])); ?></td>
                        <td><strong><?php echo htmlspecialchars($message['nom']); ?></strong></td>
                        <td><?php echo htmlspecialchars($message['email']); ?></td>
                        <td><?php echo htmlspecialchars($message['telephone'] ?? '-'); ?></td>
                        <td>
                            <?php 
                            $sujets = [
                                'question' => 'Question générale',
                                'commande' => 'Question commande',
                                'produit' => 'Question produit',
                                'livraison' => 'Livraison',
                                'autre' => 'Autre'
                            ];
                            echo $sujets[$message['sujet']] ?? ($message['sujet'] ?: '-');
                            ?>
                        </td>
                        <td class="message-preview">
                            <?php echo htmlspecialchars(mb_substr($message['message'], 0, 50)) . (mb_strlen($message['message']) > 50 ? '...' : ''); ?>
                        </td>
                        <td>
                            <?php if ($message['lu']): ?>
                                <span class="badge badge-read">Lu</span>
                            <?php else: ?>
                                <span class="badge badge-unread">Non lu</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="message_detail.php?id=<?php echo $message['id']; ?>" class="btn btn-small">Voir</a>
                            <?php if (!$message['lu']): ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="mark_read">
                                    <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                    <button type="submit" class="btn btn-small btn-secondary">Marquer lu</button>
                                </form>
                            <?php endif; ?>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                <button type="submit" class="btn btn-small btn-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

