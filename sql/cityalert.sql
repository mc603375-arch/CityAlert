CREATE DATABASE IF NOT EXISTS cityalert;
USE cityalert;

-- TABLE users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('citoyen', 'agent', 'admin') DEFAULT 'citoyen',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- TABLE categories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(100) NOT NULL,
    priorite ENUM('basse', 'normale', 'haute', 'urgente') DEFAULT 'normale',
    delai_jours INT DEFAULT 7
);

-- TABLE signalements
CREATE TABLE signalements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    adresse VARCHAR(255) NOT NULL,
    photo VARCHAR(255) DEFAULT NULL,
    statut ENUM('nouveau', 'en_cours', 'resolu', 'rejete') DEFAULT 'nouveau',
    priorite ENUM('basse', 'normale', 'haute', 'urgente') DEFAULT 'normale',
    user_id INT NOT NULL,
    categorie_id INT NOT NULL,
    agent_id INT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (categorie_id) REFERENCES categories(id),
    FOREIGN KEY (agent_id) REFERENCES users(id)
);

-- TABLE historique_statuts
CREATE TABLE historique_statuts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    signalement_id INT NOT NULL,
    ancien_statut VARCHAR(50),
    nouveau_statut VARCHAR(50) NOT NULL,
    commentaire TEXT,
    agent_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (signalement_id) REFERENCES signalements(id),
    FOREIGN KEY (agent_id) REFERENCES users(id)
);

-- TABLE commentaires
CREATE TABLE commentaires (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contenu TEXT NOT NULL,
    signalement_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (signalement_id) REFERENCES signalements(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Donnees initiales
INSERT INTO categories (libelle, priorite, delai_jours) VALUES
('Voirie', 'haute', 5),
('Eclairage', 'normale', 7),
('Dechets', 'normale', 3),
('Eau et assainissement', 'urgente', 2);