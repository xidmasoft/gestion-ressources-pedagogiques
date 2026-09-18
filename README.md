# Gestionnaire de Ressources Pédagogiques

## Présentation du projet
Une application web simple permettant à un formateur de gérer, organiser et retrouver ses ressources pédagogiques.

## Technologies
- **Backend :** PHP 8.3+, MySQL (via PDO)
- **Frontend :** HTML5, CSS3, JavaScript, jQuery, Bootstrap 5
- **Architecture :** MVC personnalisé simplifié

## Prérequis
- Serveur web (Apache/Nginx)
- PHP 8.3 ou supérieur
- MySQL ou MariaDB

## Installation et configuration
1. Cloner ce dépôt dans votre répertoire web.
2. Importer le fichier `database.sql` dans votre serveur de base de données pour créer les tables et les données de démonstration.
3. Configurer les accès à la base de données dans le fichier `config/database.php` (modifier `DB_HOST`, `DB_NAME`, `DB_USER` et `DB_PASS`).

## Lancement en environnement local
Si vous disposez de PHP CLI, vous pouvez lancer l'application avec le serveur intégré :
```bash
php -S localhost:8000
```
Puis accédez à `http://localhost:8000` depuis votre navigateur.
