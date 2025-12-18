<?php
/**
 * Page de contact
 */
require_once 'config/config.php';
require_once 'includes/functions.php';

$page_title = 'Contact';

$message_success = '';
$message_error = '';

// Traitement du formulaire de contact
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = sanitize($_POST['nom'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $telephone = sanitize($_POST['telephone'] ?? '');
    $sujet = sanitize($_POST['sujet'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    
    // Validation
    if (empty($nom) || empty($email) || empty($message)) {
        $message_error = 'Veuillez remplir tous les champs obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message_error = 'Veuillez entrer une adresse email valide.';
    } else {
        try {
            // Enregistrer le message dans la base de données
            $message_data = [
                'nom' => $nom,
                'email' => $email,
                'telephone' => !empty($telephone) ? $telephone : null,
                'sujet' => !empty($sujet) ? $sujet : null,
                'message' => $message
            ];
            
            $message_id = saveMessageContact($message_data);
            
            if ($message_id) {
                $message_success = 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.';
                
                // Réinitialiser les champs
                $nom = $email = $telephone = $sujet = $message = '';
            } else {
                $message_error = 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer.';
            }
        } catch (Exception $e) {
            $message_error = 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer plus tard.';
            error_log('Erreur enregistrement message contact: ' . $e->getMessage());
        }
    }
}

include 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <a href="<?php echo baseUrl(); ?>" class="btn-back">
            <span class="back-icon">←</span> Retour à l'accueil
        </a>
        <h1>Contactez-nous</h1>
        <p>Nous sommes là pour répondre à toutes vos questions</p>
    </div>
</section>

<section class="contact-section">
    <div class="container">
        <div class="contact-wrapper">
            <div class="contact-info">
                <div class="contact-info-header">
                    <h2>Informations de contact</h2>
                    <p>N'hésitez pas à nous contacter pour toute question ou demande</p>
                </div>
                
                <div class="contact-items-list">
                    <div class="contact-item">
                        <div class="contact-icon-wrapper">
                            <div class="contact-icon">📍</div>
                        </div>
                        <div class="contact-item-content">
                            <h3>Adresse</h3>
                            <p>Casablanca, Maroc</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon-wrapper">
                            <div class="contact-icon">📞</div>
                        </div>
                        <div class="contact-item-content">
                            <h3>Téléphone</h3>
                            <p><a href="tel:+212XXXXXXXXX">+212 XXX XXX XXX</a></p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon-wrapper">
                            <div class="contact-icon">✉️</div>
                        </div>
                        <div class="contact-item-content">
                            <h3>Email</h3>
                            <p><a href="mailto:contact@droguerie.ma">contact@droguerie.ma</a></p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon-wrapper">
                            <div class="contact-icon">🕒</div>
                        </div>
                        <div class="contact-item-content">
                            <h3>Heures d'ouverture</h3>
                            <p>Lundi - Vendredi: <strong>9h00 - 18h00</strong></p>
                            <p>Samedi: <strong>9h00 - 13h00</strong></p>
                        </div>
                    </div>
                </div>
                
                <div class="contact-social">
                    <h3>Suivez-nous</h3>
                    <div class="social-links">
                        <a href="#" class="social-link" title="Facebook">📘</a>
                        <a href="#" class="social-link" title="Instagram">📷</a>
                        <a href="#" class="social-link" title="WhatsApp">💬</a>
                    </div>
                </div>
            </div>
            
            <div class="contact-form-wrapper">
                <div class="form-header">
                    <h2>Envoyez-nous un message</h2>
                    <p>Remplissez le formulaire ci-dessous et nous vous répondrons rapidement</p>
                </div>
                
                <?php if ($message_success): ?>
                    <div class="alert alert-success">
                        <span class="alert-icon">✓</span>
                        <span><?php echo $message_success; ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ($message_error): ?>
                    <div class="alert alert-error">
                        <span class="alert-icon">✗</span>
                        <span><?php echo $message_error; ?></span>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="" class="contact-form">
                    <div class="form-group">
                        <label for="nom">Nom complet <span class="required">*</span></label>
                        <input type="text" id="nom" name="nom" required 
                               value="<?php echo isset($nom) ? htmlspecialchars($nom) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email <span class="required">*</span></label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="telephone">Téléphone</label>
                        <input type="tel" id="telephone" name="telephone" 
                               value="<?php echo isset($telephone) ? htmlspecialchars($telephone) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="sujet">Sujet</label>
                        <select id="sujet" name="sujet">
                            <option value="">Sélectionnez un sujet</option>
                            <option value="question" <?php echo (isset($sujet) && $sujet === 'question') ? 'selected' : ''; ?>>Question générale</option>
                            <option value="commande" <?php echo (isset($sujet) && $sujet === 'commande') ? 'selected' : ''; ?>>Question sur une commande</option>
                            <option value="produit" <?php echo (isset($sujet) && $sujet === 'produit') ? 'selected' : ''; ?>>Question sur un produit</option>
                            <option value="livraison" <?php echo (isset($sujet) && $sujet === 'livraison') ? 'selected' : ''; ?>>Livraison</option>
                            <option value="autre" <?php echo (isset($sujet) && $sujet === 'autre') ? 'selected' : ''; ?>>Autre</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message <span class="required">*</span></label>
                        <textarea id="message" name="message" rows="6" required><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Envoyer le message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

