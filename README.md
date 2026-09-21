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

## Phase 9B: Authentication & Authorization
This application uses a role-based authorization model ('user' or 'admin') managed locally:
- **Accounts** must be explicitly created by an administrator. There is no public registration.
- **Login / Logout**: The application is private. A valid session is required for all actions (Dashboard, Resources). Logout enforces POST requests and CSRF protection.
- **Security & Password Management**:
    - Database and PDO handle authentication securely (no plaintext passwords, minimum 12 chars).
    - Upon login, the session is protected via `session_regenerate_id(true)` to prevent session fixation.
    - Rate-limiting (brute force mitigation) is implemented server-side.
- **Resource Ownership (IDOR Prevention)**: A user can only edit or delete a resource they created, verified by `user_id`. Administrators have universal access.
- **Database Migration**: During deployment, a `users` table is established and `resources.user_id` is applied retroactively to secure existing data to the default Admin account.
