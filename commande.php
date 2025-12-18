<?php
/**
 * Page de commande
 */
require_once 'config/config.php';
require_once 'includes/functions.php';

$page_title = 'Commande';

// Vérifier que le panier n'est pas vide
$panier = getPanier();
if (empty($panier)) {
    redirect(baseUrl('panier.php'));
}

// Traitement du formulaire
$errors = [];
$success = false;
$commande_id = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation
    $nom_client = sanitize($_POST['nom_client'] ?? '');
    $telephone = sanitize($_POST['telephone'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $adresse = sanitize($_POST['adresse'] ?? '');
    $ville = sanitize($_POST['ville'] ?? '');
    $code_postal = sanitize($_POST['code_postal'] ?? '');
    $notes = sanitize($_POST['notes'] ?? '');
    
    if (empty($nom_client)) {
        $errors[] = 'Le nom est requis';
    }
    if (empty($telephone)) {
        $errors[] = 'Le téléphone est requis';
    }
    if (empty($adresse)) {
        $errors[] = 'L\'adresse est requise';
    }
    if (empty($ville)) {
        $errors[] = 'La ville est requise';
    }
    
    if (empty($errors)) {
        try {
            $commande_id = createCommande([
                'nom_client' => $nom_client,
                'telephone' => $telephone,
                'email' => $email,
                'adresse' => $adresse,
                'ville' => $ville,
                'code_postal' => $code_postal,
                'notes' => $notes
            ]);
            $success = true;
        } catch (Exception $e) {
            $errors[] = 'Une erreur est survenue lors de la création de la commande.';
        }
    }
}

// Récupérer le contenu du panier pour affichage
$panier_items = [];
$total = 0;

foreach ($panier as $produit_id => $quantite) {
    $produit = getProduit($produit_id);
    if ($produit) {
        $prix = getPrixProduit($produit);
        $sous_total = $prix * $quantite;
        $total += $sous_total;
        
        $panier_items[] = [
            'produit' => $produit,
            'quantite' => $quantite,
            'prix' => $prix,
            'sous_total' => $sous_total
        ];
    }
}

include 'includes/header.php';
?>

<section class="order-section">
    <div class="container">
        <?php if (!$success): ?>
            <a href="<?php echo baseUrl('panier.php'); ?>" class="btn-back">
                <span class="back-icon">←</span> Retour au panier
            </a>
        <?php endif; ?>
        <h1>Passer la commande</h1>
        
        <?php if ($success): ?>
            <div class="order-confirmation">
                <div class="order-confirmation-icon"></div>
                <h2>Commande confirmée !</h2>
                <p>Votre commande a été enregistrée avec succès.</p>
                <div class="order-number">Numéro de commande: #<?php echo $commande_id; ?></div>
                <div class="order-info">
                    <p>Nous vous contacterons dans les plus brefs délais pour confirmer votre commande et organiser la livraison.</p>
                </div>
                <div class="order-confirmation-actions">
                    <a href="<?php echo baseUrl(); ?>" class="btn btn-primary">Retour à l'accueil</a>
                    <a href="<?php echo baseUrl('catalogue.php'); ?>" class="btn-back">← Retour au catalogue</a>
                </div>
            </div>
        <?php else: ?>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <div class="order-content">
                <div class="order-form-container">
                    <h2>Informations de livraison</h2>
                    <form method="POST" class="order-form">
                        <div class="form-group">
                            <label for="nom_client">Nom complet *</label>
                            <input type="text" id="nom_client" name="nom_client" required 
                                   value="<?php echo isset($_POST['nom_client']) ? htmlspecialchars($_POST['nom_client']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="telephone">Téléphone *</label>
                            <input type="tel" id="telephone" name="telephone" required 
                                   value="<?php echo isset($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" 
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="adresse">Adresse *</label>
                            <textarea id="adresse" name="adresse" rows="3" required><?php echo isset($_POST['adresse']) ? htmlspecialchars($_POST['adresse']) : ''; ?></textarea>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="ville">Ville *</label>
                                <input type="text" id="ville" name="ville" required 
                                       value="<?php echo isset($_POST['ville']) ? htmlspecialchars($_POST['ville']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="code_postal">Code postal</label>
                                <input type="text" id="code_postal" name="code_postal" 
                                       value="<?php echo isset($_POST['code_postal']) ? htmlspecialchars($_POST['code_postal']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="notes">Notes (optionnel)</label>
                            <textarea id="notes" name="notes" rows="3"><?php echo isset($_POST['notes']) ? htmlspecialchars($_POST['notes']) : ''; ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-large btn-block">Confirmer la commande</button>
                    </form>
                </div>
                
                <div class="order-summary">
                    <h2>Résumé de la commande</h2>
                    <div class="order-items">
                        <?php foreach ($panier_items as $item): ?>
                            <div class="order-item">
                                <div class="order-item-info">
                                    <h4><?php echo htmlspecialchars($item['produit']['nom']); ?></h4>
                                    <p>Quantité: <?php echo $item['quantite']; ?> × <?php echo formatPrice($item['prix']); ?></p>
                                </div>
                                <div class="order-item-total">
                                    <?php echo formatPrice($item['sous_total']); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="order-total">
                        <div class="summary-row">
                            <span>Sous-total:</span>
                            <span><?php echo formatPrice($total); ?></span>
                        </div>
                        <div class="summary-row total">
                            <span>Total:</span>
                            <span><?php echo formatPrice($total); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

