    </main>
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><?php echo SITE_NAME; ?></h3>
                    <p>Votre droguerie en ligne au Maroc. Produits de qualité pour votre maison et votre hygiène.</p>
                </div>
                <div class="footer-section">
                    <h3>Contact</h3>
                    <p>Téléphone: +212 524308038</p>
                    <p>Email: contact@droguerie.com</p>
                    <p>Adresse: N, TAW10, lot Iguder, 48 AV Alla El Fassi Marrakech 40000, Morocco</p>
                </div>
                <div class="footer-section">
                    <h3>Liens rapides</h3>
                    <ul>
                        <li><a href="<?php echo baseUrl(); ?>">Accueil</a></li>
                        <li><a href="<?php echo baseUrl('catalogue.php'); ?>">Catalogue</a></li>
                        <li><a href="<?php echo baseUrl('apropos.php'); ?>">À propos</a></li>
                        <li><a href="<?php echo baseUrl('nos.php'); ?>">Nos services</a></li>
                        <li><a href="<?php echo baseUrl('contact.php'); ?>">Contact</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
    <script src="<?php echo baseUrl('assets/js/main.js'); ?>"></script>
</body>
</html>

