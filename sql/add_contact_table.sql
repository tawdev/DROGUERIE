-- Script pour ajouter la table messages_contact à une base de données existante
-- Exécuter ce script si vous avez déjà créé la base de données sans cette table

USE droguerie_db;

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

