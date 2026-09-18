-- Script de création de la base de données pour le Gestionnaire de Ressources Pédagogiques

-- Création de la table disciplines
CREATE TABLE IF NOT EXISTS disciplines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Création de la table themes
CREATE TABLE IF NOT EXISTS themes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    discipline_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_themes_disciplines FOREIGN KEY (discipline_id) REFERENCES disciplines(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Création de la table resources
CREATE TABLE IF NOT EXISTS resources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    theme_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(255) NOT NULL,
    file_size INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_resources_themes FOREIGN KEY (theme_id) REFERENCES themes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Création d'index pour optimiser les recherches
CREATE INDEX idx_themes_discipline ON themes(discipline_id);
CREATE INDEX idx_resources_theme ON resources(theme_id);

-- Insertion de données de démonstration

-- Disciplines
INSERT INTO disciplines (id, name, description) VALUES
(1, 'Développement Web', 'Apprentissage des technologies du web (front-end et back-end)'),
(2, 'Systèmes et Réseaux', 'Administration de systèmes d''exploitation et conception d''architectures réseaux');

-- Themes
INSERT INTO themes (id, discipline_id, name, description) VALUES
(1, 1, 'HTML & CSS', 'Bases de la structuration et mise en page web'),
(2, 1, 'PHP & MySQL', 'Développement back-end et bases de données'),
(3, 2, 'Linux', 'Administration système sous environnement GNU/Linux'),
(4, 2, 'Réseaux TCP/IP', 'Protocoles réseaux et adressage IP');

-- Ressources
INSERT INTO resources (theme_id, title, description, file_path, file_type, file_size) VALUES
(1, 'Support de cours HTML5', 'Introduction à HTML5', 'demo_html5.pdf', 'application/pdf', 1024000),
(2, 'Exercices PHP PDO', 'TP sur l''utilisation de PDO en PHP', 'demo_pdo.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 512000),
(3, 'Commandes de base Linux', 'Mémento des commandes bash', 'demo_linux.pdf', 'application/pdf', 2048000),
(4, 'Schéma réseau LAN', 'Exemple d''architecture d''un réseau local', 'demo_lan.png', 'image/png', 256000);
