-- Script pour corriger le problème de contrainte de clé étrangère
-- Exécuter ce script si vous avez rencontré l'erreur #1452

USE droguerie_db;

-- Désactiver temporairement la vérification des clés étrangères
SET FOREIGN_KEY_CHECKS = 0;

-- Supprimer toutes les données
DELETE FROM produits;
DELETE FROM categories;

-- Réinitialiser l'auto-increment
ALTER TABLE categories AUTO_INCREMENT = 1;
ALTER TABLE produits AUTO_INCREMENT = 1;

-- Réactiver la vérification des clés étrangères
SET FOREIGN_KEY_CHECKS = 1;

-- Maintenant vous pouvez exécuter les INSERT INTO categories et produits
-- depuis le fichier database.sql

