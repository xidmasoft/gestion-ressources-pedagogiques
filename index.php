<?php
// index.php

require_once 'core/helpers.php';
require_once 'config/database.php';

session_start();

// Génération du token CSRF s'il n'existe pas
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Inclusions des contrôleurs
require_once 'controllers/DashboardController.php';
require_once 'controllers/DisciplineController.php';
require_once 'controllers/ThemeController.php';
require_once 'controllers/ResourceController.php';

// Routeur simple
$page = $_GET['page'] ?? 'dashboard';

switch ($page) {
    case 'dashboard':
        $controller = new DashboardController();
        $controller->index();
        break;
    case 'disciplines':
        $controller = new DisciplineController();
        $action = $_GET['action'] ?? 'index';
        if ($action === 'create') {
            $controller->create();
        } elseif ($action === 'edit') {
            $controller->edit();
        } elseif ($action === 'delete') {
            $controller->delete();
        } else {
            $controller->index();
        }
        break;
    case 'themes':
        $controller = new ThemeController();
        $action = $_GET['action'] ?? 'index';
        if ($action === 'create') {
            $controller->create();
        } elseif ($action === 'edit') {
            $controller->edit();
        } elseif ($action === 'delete') {
            $controller->delete();
        } else {
            $controller->index();
        }
        break;
    case 'resources':
        $controller = new ResourceController();
        $controller->index();
        break;
    default:
        http_response_code(404);
        // On inclut un header simple pour ne pas casser le design si erreur 404
        $pageTitle = '404 - Page introuvable';
        require_once 'views/layout/header.php';
        echo "<div class='text-center mt-5'><h1>404</h1><p>Page introuvable.</p><a href='/index.php?page=dashboard' class='btn btn-primary'>Retour à l'accueil</a></div>";
        require_once 'views/layout/footer.php';
        break;
}
