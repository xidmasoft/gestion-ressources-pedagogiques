<?php
$pageTitle = 'Ressource Introuvable';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="row justify-content-center text-center mt-5">
    <div class="col-md-6">
        <h1 class="display-1 text-warning fw-bold">404</h1>
        <h2 class="h3 mb-4">Ressource Introuvable</h2>
        <p class="lead text-muted mb-5">La ressource que vous avez demandée n'existe pas ou a été supprimée.</p>
        <a href="/index.php?page=dashboard" class="btn btn-primary">Retour au Tableau de bord</a>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
