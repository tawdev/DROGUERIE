<?php
/**
 * Script pour corriger le mot de passe admin dans la base de données
 * Exécutez ce script une seule fois pour mettre à jour le mot de passe
 */
require_once '../config/config.php';

// Désactiver en production - décommenter cette ligne après utilisation
// die('Script désactivé');

$message = '';
$success = false;

try {
    $pdo = getDB();
    
    // Vérifier si l'admin existe
    $stmt = $pdo->prepare("SELECT id FROM admins WHERE username = 'admin'");
    $stmt->execute();
    $admin = $stmt->fetch();
    
    if ($admin) {
        // Hasher le mot de passe "admin123"
        $hashed_password = password_hash('admin123', PASSWORD_DEFAULT);
        
        // Mettre à jour le mot de passe
        $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE username = 'admin'");
        $stmt->execute([$hashed_password]);
        
        $success = true;
        $message = "✅ Mot de passe admin mis à jour avec succès!<br><br>";
        $message .= "<strong>Identifiants de connexion:</strong><br>";
        $message .= "Username: <strong>admin</strong><br>";
        $message .= "Password: <strong>admin123</strong><br><br>";
        $message .= "Vous pouvez maintenant vous connecter.";
    } else {
        // Créer l'admin s'il n'existe pas
        $hashed_password = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO admins (username, password, email) VALUES (?, ?, ?)");
        $stmt->execute(['admin', $hashed_password, 'admin@droguerie.ma']);
        
        $success = true;
        $message = "✅ Compte admin créé avec succès!<br><br>";
        $message .= "<strong>Identifiants de connexion:</strong><br>";
        $message .= "Username: <strong>admin</strong><br>";
        $message .= "Password: <strong>admin123</strong><br><br>";
        $message .= "Vous pouvez maintenant vous connecter.";
    }
} catch (Exception $e) {
    $message = "Erreur: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corriger le mot de passe Admin</title>
    <link rel="stylesheet" href="<?php echo baseUrl('assets/css/style.css'); ?>">
</head>
<body>
    <div class="admin-login">
        <div class="login-box" style="max-width: 600px;">
            <h1>Corriger le mot de passe Admin</h1>
            
            <?php if ($message): ?>
                <div class="alert <?php echo $success ? 'alert-success' : 'alert-error'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <p style="text-align: center; margin-top: 2rem;">
                    <a href="index.php" class="btn btn-primary">Aller à la page de connexion</a>
                </p>
            <?php endif; ?>
            
            <p style="text-align: center; margin-top: 1.5rem;">
                <a href="index.php">Retour à la connexion</a> | 
                <a href="<?php echo baseUrl(); ?>">Retour au site</a>
            </p>
            
            <div style="margin-top: 2rem; padding: 1rem; background: #fff3cd; border-radius: 4px; font-size: 0.9rem;">
                <strong>⚠️ Important:</strong> Supprimez ce fichier (fix_admin_password.php) après utilisation pour des raisons de sécurité.
            </div>
        </div>
    </div>
</body>
</html>

