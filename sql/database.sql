-- Base de données pour le site e-commerce de droguerie
-- Créer la base de données : CREATE DATABASE droguerie_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE droguerie_db;

-- Table des catégories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    ordre INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des produits
CREATE TABLE IF NOT EXISTS produits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(200) NOT NULL,
    description TEXT,
    prix DECIMAL(10, 2) NOT NULL,
    prix_promotion DECIMAL(10, 2) DEFAULT NULL,
    image VARCHAR(255),
    stock INT DEFAULT 0,
    categorie_id INT NOT NULL,
    marque VARCHAR(100),
    unite VARCHAR(50) DEFAULT 'unité',
    en_promotion BOOLEAN DEFAULT FALSE,
    actif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categorie_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des commandes
CREATE TABLE IF NOT EXISTS commandes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_client VARCHAR(100) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    adresse TEXT NOT NULL,
    ville VARCHAR(100) NOT NULL,
    code_postal VARCHAR(20),
    total DECIMAL(10, 2) NOT NULL,
    statut ENUM('en_attente', 'confirmee', 'expediee', 'livree', 'annulee') DEFAULT 'en_attente',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des détails de commande
CREATE TABLE IF NOT EXISTS commande_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL,
    produit_id INT NOT NULL,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10, 2) NOT NULL,
    sous_total DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (commande_id) REFERENCES commandes(id) ON DELETE CASCADE,
    FOREIGN KEY (produit_id) REFERENCES produits(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des administrateurs
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Suppression des anciennes données (si elles existent)
-- Désactiver temporairement la vérification des clés étrangères
SET FOREIGN_KEY_CHECKS = 0;

DELETE FROM produits;
DELETE FROM categories;

-- Réinitialisation de l'auto-increment pour commencer à 1
ALTER TABLE categories AUTO_INCREMENT = 1;
ALTER TABLE produits AUTO_INCREMENT = 1;

-- Réactiver la vérification des clés étrangères
SET FOREIGN_KEY_CHECKS = 1;

-- Insertion des nouvelles catégories
INSERT INTO categories (nom, description, ordre) VALUES
('Matériaux de construction', 'Ciment, chaux, sable, gravier, briques, parpaings, plâtre et matériaux d\'isolation', 1),
('Peinture & Revêtement', 'Peintures intérieures et extérieures, vernis, sous-couches et accessoires de peinture', 2),
('Électricité', 'Fils, câbles, interrupteurs, prises, disjoncteurs, ampoules LED et accessoires électriques', 3),
('Plomberie', 'Tuyaux PVC et cuivre, raccords, robinets, vannes, chauffe-eau et accessoires de plomberie', 4),
('Fer & Aluminium', 'Barres, tubes, plaques et profilés en fer et aluminium', 5),
('Cuivre', 'Tubes, fils, raccords, plaques et accessoires en cuivre', 6),
('Quincaillerie & Fixation', 'Vis, boulons, écrous, chevilles, charnières, serrures et accessoires de fixation', 7),
('Outillage', 'Marteaux, tournevis, pinces, clés, scies, perceuses et outils électriques', 8),
('Portes & Fenêtres', 'Serrures, poignées, charnières, profilés aluminium, vitres et accessoires', 9),
('Isolation & Finition', 'Produits d\'isolation thermique et phonique, silicone, colles, mastics et enduits', 10),
('Chantier & Sécurité', 'Échelles, brouettes, casques, gants, lunettes, chaussures et harnais de sécurité', 11);

-- Insertion des produits
INSERT INTO produits (nom, description, prix, prix_promotion, stock, categorie_id, marque, unite, en_promotion) VALUES
-- 1. Matériaux de construction
('Ciment CPJ 35', 'Ciment Portland composé CPJ 35, sac de 50kg', 65.00, 58.00, 150, 1, 'Lafarge', 'sac 50kg', TRUE),
('Chaux hydraulique', 'Chaux hydraulique naturelle, sac de 25kg', 45.00, NULL, 80, 1, 'Divers', 'sac 25kg', FALSE),
('Sable fin', 'Sable fin pour maçonnerie, mètre cube', 120.00, NULL, 200, 1, 'Divers', 'm³', FALSE),
('Gravier 5/15', 'Gravier concassé 5/15mm, mètre cube', 140.00, 125.00, 180, 1, 'Divers', 'm³', TRUE),
('Briques creuses 20x20x10', 'Briques creuses standard, palette de 500', 850.00, NULL, 50, 1, 'Divers', 'palette', FALSE),
('Parpaings 20x20x50', 'Parpaings creux standard, palette de 72', 420.00, 380.00, 60, 1, 'Divers', 'palette', TRUE),
('Plâtre fin', 'Plâtre fin de qualité, sac de 25kg', 35.00, NULL, 100, 1, 'Divers', 'sac 25kg', FALSE),
('Plaques de plâtre Placoplâtre', 'Plaques de plâtre standard 120x250cm, épaisseur 12.5mm', 45.00, NULL, 120, 1, 'Placoplâtre', 'plaque', FALSE),
('Laine de verre 100mm', 'Laine de verre pour isolation thermique, rouleau 10m²', 85.00, 75.00, 90, 1, 'Isover', 'rouleau', TRUE),
('Polystyrène expansé 50mm', 'Panneau d\'isolation polystyrène expansé, 1m x 0.5m', 25.00, NULL, 150, 1, 'Divers', 'panneau', FALSE),

-- 2. Peinture & Revêtement
('Peinture murale intérieure blanche', 'Peinture acrylique mate pour intérieur, seau de 10L', 180.00, 160.00, 80, 2, 'Ripolin', 'seau 10L', TRUE),
('Peinture murale extérieure', 'Peinture acrylique résistante aux intempéries, seau de 10L', 220.00, NULL, 70, 2, 'Ripolin', 'seau 10L', FALSE),
('Peinture pour fer', 'Peinture antirouille pour métaux, pot de 1L', 45.00, 38.00, 100, 2, 'Divers', 'pot 1L', TRUE),
('Peinture pour bois', 'Peinture spéciale bois extérieur, pot de 1L', 55.00, NULL, 85, 2, 'Divers', 'pot 1L', FALSE),
('Vernis incolore brillant', 'Vernis polyuréthane brillant, pot de 1L', 65.00, NULL, 75, 2, 'Divers', 'pot 1L', FALSE),
('Sous-couche universelle', 'Primaire d\'accrochage universel, pot de 1L', 35.00, 30.00, 110, 2, 'Divers', 'pot 1L', TRUE),
('Rouleau de peinture 18cm', 'Rouleau lisse pour peinture, manche inclus', 12.00, NULL, 200, 2, 'Divers', 'pièce', FALSE),
('Pinceau plat 5cm', 'Pinceau plat synthétique professionnel', 8.00, NULL, 250, 2, 'Divers', 'pièce', FALSE),
('Ruban de masquage 48mm', 'Ruban adhésif de masquage, rouleau de 50m', 15.00, NULL, 180, 2, 'Divers', 'rouleau', FALSE),

-- 3. Électricité
('Fils électriques 2.5mm²', 'Fils électriques rigides H07V-U, rouleau de 100m', 85.00, 75.00, 60, 3, 'Divers', 'rouleau 100m', TRUE),
('Câbles électriques souples 1.5mm²', 'Câbles souples multibrins, rouleau de 100m', 65.00, NULL, 80, 3, 'Divers', 'rouleau 100m', FALSE),
('Interrupteur simple', 'Interrupteur simple allumage, blanc', 8.00, NULL, 300, 3, 'Legrand', 'pièce', FALSE),
('Prise électrique 2P+T', 'Prise de courant 2 pôles + terre, blanc', 12.00, 10.00, 250, 3, 'Legrand', 'pièce', TRUE),
('Disjoncteur 20A', 'Disjoncteur modulaire unipolaire 20A', 45.00, NULL, 120, 3, 'Schneider', 'pièce', FALSE),
('Ampoule LED 12W', 'Ampoule LED équivalente 60W, culot E27', 25.00, 20.00, 400, 3, 'Philips', 'pièce', TRUE),
('Tableau électrique 6 modules', 'Tableau électrique avec porte, 6 modules', 180.00, NULL, 50, 3, 'Legrand', 'pièce', FALSE),
('Gaines électriques 20mm', 'Gaines rigides PVC pour électricité, longueur 3m', 8.00, NULL, 200, 3, 'Divers', 'longueur 3m', FALSE),
('Transformateur 220V/12V', 'Transformateur pour éclairage 12V, 50W', 65.00, NULL, 40, 3, 'Divers', 'pièce', FALSE),

-- 4. Plomberie
('Tuyaux PVC 32mm', 'Tuyaux PVC pression, longueur 3m', 25.00, NULL, 150, 4, 'Divers', 'longueur 3m', FALSE),
('Tuyaux PVC 50mm', 'Tuyaux PVC évacuation, longueur 3m', 35.00, 30.00, 120, 4, 'Divers', 'longueur 3m', TRUE),
('Tuyaux en cuivre 15mm', 'Tuyaux cuivre recuit, longueur 3m', 85.00, NULL, 100, 4, 'Divers', 'longueur 3m', FALSE),
('Tuyaux en cuivre 22mm', 'Tuyaux cuivre recuit, longueur 3m', 120.00, NULL, 80, 4, 'Divers', 'longueur 3m', FALSE),
('Raccords plomberie PVC', 'Raccords PVC coude 90°, diamètre 32mm', 8.00, NULL, 300, 4, 'Divers', 'pièce', FALSE),
('Robinet mélangeur lavabo', 'Robinet mélangeur chromé pour lavabo', 180.00, 160.00, 60, 4, 'Divers', 'pièce', TRUE),
('Vanne d\'arrêt 15mm', 'Vanne d\'arrêt à boisseau sphérique, 15mm', 45.00, NULL, 150, 4, 'Divers', 'pièce', FALSE),
('Mitigeur douche', 'Mitigeur thermostatique pour douche', 350.00, NULL, 40, 4, 'Divers', 'pièce', FALSE),
('Chauffe-eau électrique 100L', 'Chauffe-eau vertical électrique 100 litres', 2500.00, 2200.00, 20, 4, 'Atlantic', 'pièce', TRUE),
('Pompe à eau 1CV', 'Pompe à eau centrifuge 1CV, débit 3m³/h', 850.00, NULL, 15, 4, 'Divers', 'pièce', FALSE),
('Ruban Téflon', 'Ruban d\'étanchéité téflon, rouleau 50m', 8.00, NULL, 250, 4, 'Divers', 'rouleau', FALSE),

-- 5. Fer & Aluminium
('Barres en fer rond 12mm', 'Barres en fer rond lisse, longueur 6m', 45.00, NULL, 100, 5, 'Divers', 'barre 6m', FALSE),
('Tubes en fer carré 40x40mm', 'Tubes en fer carré, épaisseur 2mm, longueur 6m', 120.00, 105.00, 80, 5, 'Divers', 'tube 6m', TRUE),
('Plaques en acier 2mm', 'Plaques en acier, dimensions 1m x 2m', 350.00, NULL, 30, 5, 'Divers', 'plaque', FALSE),
('Profilés en aluminium 40x20mm', 'Profilés aluminium pour menuiserie, longueur 6m', 85.00, NULL, 90, 5, 'Divers', 'profilé 6m', FALSE),
('Tubes en aluminium 20mm', 'Tubes aluminium rond, épaisseur 1.5mm, longueur 6m', 65.00, NULL, 100, 5, 'Divers', 'tube 6m', FALSE),
('Plaques aluminium 2mm', 'Plaques aluminium, dimensions 1m x 2m', 450.00, NULL, 25, 5, 'Divers', 'plaque', FALSE),
('Cornières métalliques 50x50mm', 'Cornières en acier, épaisseur 3mm, longueur 6m', 95.00, 85.00, 70, 5, 'Divers', 'cornière 6m', TRUE),
('Grillage métallique 1m', 'Grillage soudé, maille 50x50mm, hauteur 1m, rouleau 25m', 180.00, NULL, 40, 5, 'Divers', 'rouleau', FALSE),

-- 6. Cuivre
('Tubes en cuivre 15mm', 'Tubes cuivre recuit pour plomberie, longueur 3m', 85.00, NULL, 100, 6, 'Divers', 'tube 3m', FALSE),
('Fils en cuivre 2.5mm²', 'Fils électriques en cuivre, rouleau de 100m', 95.00, 85.00, 70, 6, 'Divers', 'rouleau 100m', TRUE),
('Raccords en cuivre 15mm', 'Raccords coude 90° en cuivre, diamètre 15mm', 12.00, NULL, 200, 6, 'Divers', 'pièce', FALSE),
('Plaques de cuivre 1mm', 'Plaques de cuivre, dimensions 50cm x 100cm', 450.00, NULL, 20, 6, 'Divers', 'plaque', FALSE),
('Accessoires en cuivre', 'Jeu de raccords cuivre variés, 10 pièces', 85.00, NULL, 50, 6, 'Divers', 'jeu', FALSE),
('Étain / soudure cuivre', 'Fil d\'étain pour soudure cuivre, rouleau 100g', 25.00, NULL, 150, 6, 'Divers', 'rouleau', FALSE),

-- 7. Quincaillerie & Fixation
('Vis à bois 4x40mm', 'Vis à bois acier zingué, boîte de 100', 15.00, NULL, 500, 7, 'Divers', 'boîte 100', FALSE),
('Boulons M8x50mm', 'Boulons hexagonaux avec écrous, boîte de 50', 35.00, 30.00, 300, 7, 'Divers', 'boîte 50', TRUE),
('Écrous M8', 'Écrous hexagonaux M8, boîte de 100', 12.00, NULL, 400, 7, 'Divers', 'boîte 100', FALSE),
('Chevilles à expansion 8mm', 'Chevilles à expansion pour béton, boîte de 50', 18.00, NULL, 350, 7, 'Fischer', 'boîte 50', FALSE),
('Charnières 3D', 'Charnières 3D pour portes, paire', 45.00, 40.00, 200, 7, 'Divers', 'paire', TRUE),
('Serrures à encastrer', 'Serrures à encastrer pour portes, cylindre européen', 85.00, NULL, 150, 7, 'Divers', 'pièce', FALSE),
('Verrous de sécurité', 'Verrous de sécurité à pêne dormant', 65.00, NULL, 120, 7, 'Divers', 'pièce', FALSE),
('Crochets de fixation', 'Crochets métalliques pour fixation murale, lot de 10', 15.00, NULL, 400, 7, 'Divers', 'lot 10', FALSE),
('Supports de fixation', 'Supports métalliques pour tuyaux, diamètre 20mm', 8.00, NULL, 500, 7, 'Divers', 'pièce', FALSE),

-- 8. Outillage
('Marteau 500g', 'Marteau de menuisier, manche en bois', 35.00, NULL, 150, 8, 'Divers', 'pièce', FALSE),
('Tournevis cruciforme', 'Jeu de tournevis cruciformes, 6 pièces', 45.00, 38.00, 200, 8, 'Divers', 'jeu', TRUE),
('Pinces universelles', 'Pinces universelles 200mm', 25.00, NULL, 180, 8, 'Divers', 'pièce', FALSE),
('Clés plates', 'Jeu de clés plates, 8 pièces (8-19mm)', 55.00, NULL, 120, 8, 'Divers', 'jeu', FALSE),
('Scie à métaux', 'Scie à métaux avec lames, 300mm', 45.00, NULL, 100, 8, 'Divers', 'pièce', FALSE),
('Perceuse visseuse 18V', 'Perceuse visseuse sans fil 18V avec batterie', 450.00, 400.00, 50, 8, 'Bosch', 'pièce', TRUE),
('Meuleuse 125mm', 'Meuleuse d\'angle 125mm, 900W', 280.00, NULL, 40, 8, 'Divers', 'pièce', FALSE),
('Outils électriques', 'Jeu d\'outils électriques variés, 12 pièces', 350.00, NULL, 30, 8, 'Divers', 'jeu', FALSE),
('Mètre ruban 5m', 'Mètre ruban métallique, longueur 5m', 15.00, NULL, 300, 8, 'Divers', 'pièce', FALSE),

-- 9. Portes & Fenêtres
('Serrures de porte multipoints', 'Serrures de porte multipoints avec cylindre', 180.00, 160.00, 80, 9, 'Divers', 'pièce', TRUE),
('Poignées de porte', 'Poignées de porte modernes, chromées, paire', 65.00, NULL, 150, 9, 'Divers', 'paire', FALSE),
('Charnières de porte 3D', 'Charnières 3D pour portes lourdes, paire', 55.00, NULL, 120, 9, 'Divers', 'paire', FALSE),
('Profilés aluminium', 'Profilés aluminium pour fenêtres, longueur 6m', 95.00, NULL, 100, 9, 'Divers', 'profilé 6m', FALSE),
('Vitres 4mm', 'Vitres transparentes, épaisseur 4mm, dimensions 1m x 1.5m', 85.00, NULL, 60, 9, 'Divers', 'vitre', FALSE),
('Accessoires de fenêtres', 'Jeu d\'accessoires pour fenêtres (poignées, crémones)', 45.00, 40.00, 200, 9, 'Divers', 'jeu', TRUE),
('Rideaux métalliques', 'Rideaux métalliques de sécurité, dimensions sur mesure', 850.00, NULL, 20, 9, 'Divers', 'm²', FALSE),

-- 10. Isolation & Finition
('Laine de roche 100mm', 'Panneaux de laine de roche pour isolation, 1m x 0.6m', 45.00, 40.00, 100, 10, 'Rockwool', 'panneau', TRUE),
('Isolation phonique', 'Panneaux d\'isolation phonique, épaisseur 50mm', 65.00, NULL, 80, 10, 'Divers', 'panneau', FALSE),
('Silicone blanc', 'Silicone de jointoiement blanc, cartouche 310ml', 25.00, NULL, 200, 10, 'Divers', 'cartouche', FALSE),
('Colles polyuréthane', 'Colle polyuréthane pour construction, cartouche 750ml', 45.00, NULL, 150, 10, 'Divers', 'cartouche', FALSE),
('Mastics acryliques', 'Mastics acryliques pour joints, cartouche 310ml', 18.00, 15.00, 250, 10, 'Divers', 'cartouche', TRUE),
('Enduits de finition', 'Enduits de finition intérieure, sac de 25kg', 55.00, NULL, 120, 10, 'Divers', 'sac 25kg', FALSE),
('Produits d\'étanchéité', 'Produits d\'étanchéité bitumineux, seau de 20L', 180.00, NULL, 50, 10, 'Divers', 'seau 20L', FALSE),

-- 11. Chantier & Sécurité
('Échelles télescopiques 3m', 'Échelles télescopiques aluminium, hauteur 3m', 450.00, 400.00, 40, 11, 'Divers', 'pièce', TRUE),
('Brouettes de chantier', 'Brouettes métalliques pour chantier, capacité 100L', 280.00, NULL, 60, 11, 'Divers', 'pièce', FALSE),
('Casques de sécurité', 'Casques de sécurité norme CE, réglable', 45.00, NULL, 200, 11, 'Divers', 'pièce', FALSE),
('Gants de protection', 'Gants de protection cuir, paire', 25.00, 20.00, 300, 11, 'Divers', 'paire', TRUE),
('Lunettes de sécurité', 'Lunettes de protection anti-projections', 15.00, NULL, 400, 11, 'Divers', 'pièce', FALSE),
('Chaussures de sécurité', 'Chaussures de sécurité norme CE, pointure 42', 180.00, NULL, 100, 11, 'Divers', 'paire', FALSE),
('Harnais de sécurité', 'Harnais de sécurité pour travaux en hauteur', 350.00, NULL, 30, 11, 'Divers', 'pièce', FALSE);

-- Table des messages de contact
CREATE TABLE IF NOT EXISTS messages_contact (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telephone VARCHAR(20),
    sujet VARCHAR(50),
    message TEXT NOT NULL,
    lu BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertion d'un administrateur par défaut (username: admin, password: admin123)
-- Le mot de passe est hashé avec password_hash() en PHP
INSERT INTO admins (username, password, email) VALUES
('admin', '$2y$10$f/OYZsefQH3YZInOCIjGmei6ha8wN4wB.DIZ5P86CbVOxlAI4DbVy', 'admin@droguerie.ma');
-- Mot de passe en clair: admin123

