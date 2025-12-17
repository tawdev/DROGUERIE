<?php
/**
 * Modifier un produit (admin)
 */
require_once 'auth.php';
require_once '../includes/functions.php';

$page_title = 'Modifier un produit';

$produit_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$pdo = getDB();
$stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
$stmt->execute([$produit_id]);
$produit = $stmt->fetch();

if (!$produit) {
    redirect('produits.php');
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = sanitize($_POST['nom'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $prix = floatval($_POST['prix'] ?? 0);
    $prix_promotion = !empty($_POST['prix_promotion']) ? floatval($_POST['prix_promotion']) : null;
    $stock = intval($_POST['stock'] ?? 0);
    $categorie_id = intval($_POST['categorie_id'] ?? 0);
    $marque = sanitize($_POST['marque'] ?? '');
    $unite = sanitize($_POST['unite'] ?? 'unité');
    $en_promotion = isset($_POST['en_promotion']) ? 1 : 0;
    $actif = isset($_POST['actif']) ? 1 : 0;
    
    // Validation
    if (empty($nom)) $errors[] = 'Le nom est requis';
    if ($prix <= 0) $errors[] = 'Le prix doit être supérieur à 0';
    if ($categorie_id <= 0) $errors[] = 'La catégorie est requise';
    
    // Gestion de l'upload d'image
    $image = $produit['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (in_array($_FILES['image']['type'], $allowed)) {
            // Supprimer l'ancienne image
            if ($image && file_exists(UPLOAD_PATH . $image)) {
                unlink(UPLOAD_PATH . $image);
            }
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $image = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_PATH . $image);
        } else {
            $errors[] = 'Format d\'image non supporté';
        }
    }
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE produits SET nom = ?, description = ?, prix = ?, prix_promotion = ?, image = ?, stock = ?, categorie_id = ?, marque = ?, unite = ?, en_promotion = ?, actif = ? WHERE id = ?");
        $stmt->execute([$nom, $description, $prix, $prix_promotion, $image, $stock, $categorie_id, $marque, $unite, $en_promotion, $actif, $produit_id]);
        $success = true;
        
        // Récupérer le produit mis à jour
        $stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
        $stmt->execute([$produit_id]);
        $produit = $stmt->fetch();
    }
}

$categories = getCategories();

include 'includes/header.php';
?>

<div class="admin-page">
    <h1>Modifier un produit</h1>
    
    <?php if ($success): ?>
        <div class="alert alert-success">Produit modifié avec succès ! <a href="produits.php">Voir la liste</a></div>
    <?php endif; ?>
    
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data" class="admin-form">
        <div class="form-group">
            <label for="nom">Nom du produit *</label>
            <input type="text" id="nom" name="nom" required value="<?php echo htmlspecialchars($produit['nom']); ?>">
        </div>
        
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($produit['description']); ?></textarea>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="prix">Prix (MAD) *</label>
                <input type="number" id="prix" name="prix" step="0.01" min="0" required value="<?php echo $produit['prix']; ?>">
            </div>
            
            <div class="form-group">
                <label for="prix_promotion">Prix promotionnel (MAD)</label>
                <input type="number" id="prix_promotion" name="prix_promotion" step="0.01" min="0" value="<?php echo $produit['prix_promotion']; ?>">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="categorie_id">Catégorie *</label>
                <select id="categorie_id" name="categorie_id" required>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo $produit['categorie_id'] == $cat['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" id="stock" name="stock" min="0" value="<?php echo $produit['stock']; ?>">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="marque">Marque</label>
                <input type="text" id="marque" name="marque" value="<?php echo htmlspecialchars($produit['marque']); ?>">
            </div>
            
            <div class="form-group">
                <label for="unite">Unité</label>
                <input type="text" id="unite" name="unite" value="<?php echo htmlspecialchars($produit['unite']); ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label for="image">Image</label>
            <?php if ($produit['image']): ?>
                <div class="current-image">
                    <img src="<?php echo baseUrl('uploads/' . $produit['image']); ?>" alt="" style="max-width: 200px; margin-bottom: 1rem;">
                </div>
            <?php endif; ?>
            <input type="file" id="image" name="image" accept="image/*">
        </div>
        
        <div class="form-group">
            <label>
                <input type="checkbox" name="en_promotion" value="1" <?php echo $produit['en_promotion'] ? 'checked' : ''; ?>>
                En promotion
            </label>
        </div>
        
        <div class="form-group">
            <label>
                <input type="checkbox" name="actif" value="1" <?php echo $produit['actif'] ? 'checked' : ''; ?>>
                Actif
            </label>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="produits.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

