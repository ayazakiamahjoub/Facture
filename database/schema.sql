-- Script de création de la base de données Pioneer Tech ProjectManager
-- Exécuter ce script pour initialiser la base de données

CREATE DATABASE IF NOT EXISTS pioneer_tech CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pioneer_tech;

-- Table des utilisateurs (gestion des comptes utilisateurs)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('admin', 'employé') NOT NULL DEFAULT 'employé',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    actif BOOLEAN DEFAULT TRUE,
    INDEX idx_email (email),
    INDEX idx_role (role)
);

-- Table des clients
CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_client VARCHAR(150) NOT NULL,
    email VARCHAR(150),
    telephone VARCHAR(20),
    adresse TEXT,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    actif BOOLEAN DEFAULT TRUE,
    INDEX idx_nom_client (nom_client),
    INDEX idx_email_client (email)
);

-- Table des projets
CREATE TABLE projets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre_projet VARCHAR(200) NOT NULL,
    description TEXT,
    id_client INT NOT NULL,
    statut ENUM('en cours', 'terminé', 'annulé') NOT NULL DEFAULT 'en cours',
    date_debut DATE,
    date_fin_prevue DATE,
    date_fin_reelle DATE,
    budget DECIMAL(10,2),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_client) REFERENCES clients(id) ON DELETE CASCADE,
    INDEX idx_titre_projet (titre_projet),
    INDEX idx_statut (statut),
    INDEX idx_client (id_client)
);

-- Table de l'équipe projet (relation many-to-many entre users et projets)
CREATE TABLE equipe_projet (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_projet INT NOT NULL,
    id_user INT NOT NULL,
    role_projet VARCHAR(50) NOT NULL DEFAULT 'membre',
    date_assignation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_fin_assignation DATE NULL,
    actif BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_projet) REFERENCES projets(id) ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_projet_actif (id_projet, id_user, actif),
    INDEX idx_projet (id_projet),
    INDEX idx_user (id_user),
    INDEX idx_role_projet (role_projet)
);

-- Table des factures
CREATE TABLE factures (
    id_facture INT AUTO_INCREMENT PRIMARY KEY,
    numero_facture VARCHAR(50) UNIQUE NOT NULL,
    id_client INT NOT NULL,
    id_projet INT,
    montant DECIMAL(10,2) NOT NULL,
    date_facture DATE NOT NULL,
    date_echeance DATE,
    statut ENUM('payée', 'impayée', 'en_retard') NOT NULL DEFAULT 'impayée',
    description TEXT,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_client) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (id_projet) REFERENCES projets(id) ON DELETE SET NULL,
    INDEX idx_numero_facture (numero_facture),
    INDEX idx_client_facture (id_client),
    INDEX idx_projet_facture (id_projet),
    INDEX idx_statut_facture (statut),
    INDEX idx_date_facture (date_facture)
);

-- Insertion de données de test Pioneer Tech
-- Utilisateur admin par défaut
INSERT INTO users (nom, email, mot_de_passe, role) VALUES
('Administrateur Pioneer Tech', 'admin@pioneertech.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Jean Dupont', 'jean.dupont@pioneertech.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employé'),
('Marie Martin', 'marie.martin@pioneertech.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employé');

-- Clients de test
INSERT INTO clients (nom_client, email, telephone, adresse) VALUES 
('Entreprise ABC', 'contact@abc.com', '01.23.45.67.89', '123 Rue de la Paix, 75001 Paris'),
('Société XYZ', 'info@xyz.fr', '01.98.76.54.32', '456 Avenue des Champs, 69000 Lyon'),
('StartUp Tech', 'hello@startup.tech', '01.11.22.33.44', '789 Boulevard Innovation, 31000 Toulouse');

-- Projets de test
INSERT INTO projets (titre_projet, description, id_client, statut, date_debut, date_fin_prevue, budget) VALUES 
('Site Web Corporate', 'Développement du site web institutionnel', 1, 'en cours', '2024-01-15', '2024-03-15', 15000.00),
('Application Mobile', 'Création d\'une application mobile iOS/Android', 2, 'en cours', '2024-02-01', '2024-06-01', 25000.00),
('Système CRM', 'Implémentation d\'un système de gestion client', 3, 'terminé', '2023-10-01', '2023-12-31', 35000.00);

-- Équipes projet de test
INSERT INTO equipe_projet (id_projet, id_user, role_projet) VALUES 
(1, 1, 'chef'),
(1, 2, 'développeur'),
(2, 1, 'chef'),
(2, 3, 'développeur'),
(3, 2, 'chef'),
(3, 3, 'développeur');

-- Factures de test
INSERT INTO factures (numero_facture, id_client, id_projet, montant, date_facture, date_echeance, statut, description) VALUES 
('FAC-2024-001', 1, 1, 7500.00, '2024-02-15', '2024-03-15', 'impayée', 'Acompte 50% - Site Web Corporate'),
('FAC-2024-002', 2, 2, 12500.00, '2024-03-01', '2024-04-01', 'impayée', 'Acompte 50% - Application Mobile'),
('FAC-2023-015', 3, 3, 35000.00, '2023-12-31', '2024-01-31', 'payée', 'Solde final - Système CRM');
