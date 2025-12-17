-- Script pour mettre à jour les catégories et produits
-- Exécuter ce script pour remplacer les anciennes données

USE droguerie_db;

-- Suppression des anciennes données
DELETE FROM produits;
DELETE FROM categories;

-- Réinitialisation de l'auto-increment pour commencer à 1
ALTER TABLE categories AUTO_INCREMENT = 1;
ALTER TABLE produits AUTO_INCREMENT = 1;

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

-- Insertion des produits (voir le fichier database.sql pour la liste complète)
-- Note: Les produits sont insérés dans database.sql

