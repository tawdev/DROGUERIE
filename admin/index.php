<?php
/**
 * Page de connexion admin
 */
require_once '../config/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if ($username && $password) {
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();
        
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            redirect(baseUrl('admin/dashboard.php'));
        } else {
            $error = 'Identifiants incorrects';
        }
    } else {
        $error = 'Veuillez remplir tous les champs';
    }
}

// Si déjà connecté, rediriger
if (isset($_SESSION['admin_id'])) {
    redirect(baseUrl('admin/dashboard.php'));
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo baseUrl('assets/css/style.css'); ?>">
</head>
<body>
    <div class="admin-login">
        <div class="login-box">
            <h1>Connexion Admin</h1>
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
            </form>
            <p>
                <a href="<?php echo baseUrl(); ?>">Retour au site</a>
            </p>
        </div>
    </div>
</body>
</html>

