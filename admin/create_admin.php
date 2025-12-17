<?php
/**
 * Script pour créer un compte admin
 * À supprimer après utilisation pour des raisons de sécurité
 */
require_once '../config/config.php';

// Désactiver en production - décommenter cette ligne après utilisation
// die('Script désactivé');

$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $email = sanitize($_POST['email'] ?? '');
    
    if ($username && $password) {
        if (strlen($password) >= 6) {
            $pdo = getDB();
            
            // Vérifier si l'utilisateur existe déjà
            $stmt = $pdo->prepare("SELECT id FROM admins WHERE username = ?");
            $stmt->execute([$username]);
            $existing = $stmt->fetch();
            
            if (!$existing) {
                // Hasher le mot de passe
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Créer l'admin
                $stmt = $pdo->prepare("INSERT INTO admins (username, password, email) VALUES (?, ?, ?)");
                $stmt->execute([$username, $hashed_password, $email ?: null]);
                
                $success = true;
                $message = "Compte admin créé avec succès! Username: " . htmlspecialchars($username);
            } else {
                $message = "Ce nom d'utilisateur existe déjà.";
            }
        } else {
            $message = "Le mot de passe doit contenir au moins 6 caractères.";
        }
    } else {
        $message = "Veuillez remplir tous les champs obligatoires.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte Admin</title>
    <link rel="stylesheet" href="<?php echo baseUrl('assets/css/style.css'); ?>">
</head>
<body>
    <div class="admin-login">
        <div class="login-box" style="max-width: 500px;">
            <h1>Créer un compte Admin</h1>
            
            <?php if ($message): ?>
                <div class="alert <?php echo $success ? 'alert-success' : 'alert-error'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!$success): ?>
                <form method="POST">
                    <div class="form-group">
                        <label for="username">Nom d'utilisateur *</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe *</label>
                        <input type="password" id="password" name="password" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label for="email">Email (optionnel)</label>
                        <input type="email" id="email" name="email">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Créer le compte</button>
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
                <strong>⚠️ Important:</strong> Supprimez ce fichier (create_admin.php) après utilisation pour des raisons de sécurité.
            </div>
        </div>
    </div>
</body>
</html>

