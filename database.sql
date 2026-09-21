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
    CONSTRAINT fk_themes_disciplines FOREIGN KEY (discipline_id) REFERENCES disciplines(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Création de la table users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('user','admin') NOT NULL DEFAULT 'user',
    status ENUM('active','locked') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Création de la table login_attempts pour le brute-force
CREATE TABLE IF NOT EXISTS login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    attempt_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email_time (email, attempt_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Création de la table resources
CREATE TABLE IF NOT EXISTS resources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    theme_id INT NOT NULL,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(255) NOT NULL,
    file_size INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_resources_themes FOREIGN KEY (theme_id) REFERENCES themes(id) ON DELETE RESTRICT,
    CONSTRAINT fk_resources_users FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Création d'index pour optimiser les recherches
CREATE INDEX idx_themes_discipline ON themes(discipline_id);
CREATE INDEX idx_resources_theme ON resources(theme_id);
CREATE INDEX idx_resources_user ON resources(user_id);

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

-- Admin par défaut
INSERT INTO users (id, name, email, password_hash, role) VALUES
(1, 'Administrateur', 'admin@grp.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'); -- password: password

-- Ressources
INSERT INTO resources (theme_id, user_id, title, description, file_path, file_type, file_size) VALUES
(1, 1, 'Support de cours HTML5', 'Introduction à HTML5', 'demo_html5.pdf', 'application/pdf', 1024000),
(2, 1, 'Exercices PHP PDO', 'TP sur l''utilisation de PDO en PHP', 'demo_pdo.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 512000),
(3, 1, 'Commandes de base Linux', 'Mémento des commandes bash', 'demo_linux.pdf', 'application/pdf', 2048000),
(4, 1, 'Schéma réseau LAN', 'Exemple d''architecture d''un réseau local', 'demo_lan.png', 'image/png', 256000);
