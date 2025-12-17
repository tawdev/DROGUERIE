<?php
/**
 * Script pour réinitialiser le mot de passe admin
 * À supprimer après utilisation pour des raisons de sécurité
 */
require_once '../config/config.php';

// Désactiver en production - décommenter cette ligne après utilisation
// die('Script désactivé');

$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if ($username && $new_password && $confirm_password) {
        if ($new_password === $confirm_password) {
            if (strlen($new_password) >= 6) {
                $pdo = getDB();
                
                // Vérifier si l'utilisateur existe
                $stmt = $pdo->prepare("SELECT id FROM admins WHERE username = ?");
                $stmt->execute([$username]);
                $admin = $stmt->fetch();
                
                if ($admin) {
                    // Hasher le nouveau mot de passe
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    
                    // Mettre à jour le mot de passe
                    $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE username = ?");
                    $stmt->execute([$hashed_password, $username]);
                    
                    $success = true;
                    $message = "Mot de passe mis à jour avec succès pour l'utilisateur: " . htmlspecialchars($username);
                } else {
                    $message = "L'utilisateur n'existe pas.";
                }
            } else {
                $message = "Le mot de passe doit contenir au moins 6 caractères.";
            }
        } else {
            $message = "Les mots de passe ne correspondent pas.";
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe Admin</title>
    <link rel="stylesheet" href="<?php echo baseUrl('assets/css/style.css'); ?>">
</head>
<body>
    <div class="admin-login">
        <div class="login-box" style="max-width: 500px;">
            <h1>Réinitialiser le mot de passe Admin</h1>
            
            <?php if ($message): ?>
                <div class="alert <?php echo $success ? 'alert-success' : 'alert-error'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!$success): ?>
                <form method="POST">
                    <div class="form-group">
                        <label for="username">Nom d'utilisateur</label>
                        <input type="text" id="username" name="username" value="admin" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">Nouveau mot de passe</label>
                        <input type="password" id="new_password" name="new_password" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirmer le mot de passe</label>
                        <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Réinitialiser le mot de passe</button>
                </form>
            <?php else: ?>
                <p style="text-align: center; margin-top: 2rem;">
                    <a href="index.php" class="btn btn-primary">Aller à la page de connexion</a>
                </p>
            <?php endif; ?>
            
            <p style="text-align: center; margin-top: 1.5rem;">
                <a href="index.php">Retour à la connexion</a> | 
                <a href="<?php echo baseUrl(); ?>">Retour au site</a>
            </p>
            
            <div style="margin-top: 2rem; padding: 1rem; background: #fff3cd; border-radius: 4px; font-size: 0.9rem;">
                <strong>⚠️ Important:</strong> Supprimez ce fichier (reset_password.php) après utilisation pour des raisons de sécurité.
            </div>
        </div>
    </div>
</body>
</html>

