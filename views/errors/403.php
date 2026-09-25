<?php
$pageTitle = 'Accès Refusé';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="row justify-content-center text-center mt-5">
    <div class="col-md-6">
        <h1 class="display-1 text-danger fw-bold">403</h1>
        <h2 class="h3 mb-4">Accès Refusé</h2>
        <p class="lead text-muted mb-5">Vous n'avez pas les privilèges nécessaires pour accéder à cette page ou effectuer cette action.</p>
        <a href="/index.php?page=dashboard" class="btn btn-primary">Retour au Tableau de bord</a>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
