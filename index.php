<?php
// index.php

require_once 'core/helpers.php';
require_once 'config/database.php';

// Routeur simple
$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'home':
        // Affiche la page d'accueil (à implémenter)
        echo "<h1>Bienvenue sur le Gestionnaire de Ressources Pédagogiques</h1>";
        break;
    default:
        http_response_code(404);
        echo "<h1>404 - Page introuvable</h1>";
        break;
}
