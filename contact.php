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
            <span class="back-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
            </span> Retour à l'accueil
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
                            <div class="contact-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                            </div>
                        </div>
                        <div class="contact-item-content">
                            <h3>Adresse</h3>
                            <p>Casablanca, Maroc</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon-wrapper">
                            <div class="contact-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="contact-item-content">
                            <h3>Téléphone</h3>
                            <p><a href="tel:+212XXXXXXXXX">+212 XXX XXX XXX</a></p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon-wrapper">
                            <div class="contact-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </div>
                        </div>
                        <div class="contact-item-content">
                            <h3>Email</h3>
                            <p><a href="mailto:contact@droguerie.ma">contact@droguerie.ma</a></p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon-wrapper">
                            <div class="contact-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                            </div>
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
                        <a href="#" class="social-link" title="Facebook">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                            </svg>
                        </a>
                        <a href="#" class="social-link" title="Instagram">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                            </svg>
                        </a>
                        <a href="#" class="social-link" title="WhatsApp">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                            </svg>
                        </a>
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

