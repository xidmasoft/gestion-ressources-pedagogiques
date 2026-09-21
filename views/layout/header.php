<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? h($pageTitle) . ' - ' : '' ?>Gestionnaire de Ressources Pédagogiques</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100 bg-light text-dark">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/index.php?page=dashboard">Gestionnaire Pédagogique</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <?php $current_page = $_GET['page'] ?? 'dashboard'; ?>
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $current_page === 'dashboard' ? 'active fw-semibold' : '' ?>" href="/index.php?page=dashboard">Tableau de Bord</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $current_page === 'resources' ? 'active fw-semibold' : '' ?>" href="/index.php?page=resources">Ressources</a>
                        </li>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $current_page === 'disciplines' ? 'active fw-semibold' : '' ?>" href="/index.php?page=disciplines">Disciplines</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $current_page === 'themes' ? 'active fw-semibold' : '' ?>" href="/index.php?page=themes">Thèmes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $current_page === 'users' ? 'active fw-semibold' : '' ?>" href="/index.php?page=users">Utilisateurs</a>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item ms-lg-3">
                            <form action="/index.php?page=logout" method="POST" class="d-inline">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-outline-light btn-sm mt-1">Déconnexion (<?= h($_SESSION['user_name'] ?? '') ?>)</button>
                            </form>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <main class="container my-5 flex-grow-1">
