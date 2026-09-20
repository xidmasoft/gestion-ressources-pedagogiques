<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-4">
    <h1 class="h3 mb-0">Gestion des Ressources</h1>
    <a href="/index.php?page=resources&action=create" class="btn btn-primary btn-sm">Ajouter une ressource</a>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= h($_SESSION['success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= h($_SESSION['error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Titre</th>
                        <th>Discipline / Thème</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Taille</th>
                        <th>Date de création</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($resources)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Aucune ressource trouvée.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($resources as $r): ?>
                            <tr>
                                <td class="align-middle fw-medium"><?= h($r['title']) ?></td>
                                <td class="align-middle">
                                    <span class="badge bg-secondary"><?= h($r['discipline_name']) ?></span><br>
                                    <small class="text-muted"><?= h($r['theme_name']) ?></small>
                                </td>
                                <td class="align-middle"><?= h($r['description']) ?></td>
                                <td class="align-middle">
                                    <span class="badge bg-info text-dark"><?= h(strtoupper(pathinfo($r['file_path'], PATHINFO_EXTENSION))) ?></span>
                                </td>
                                <td class="align-middle"><?= round($r['file_size'] / 1024 / 1024, 2) ?> Mo</td>
                                <td class="align-middle"><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
                                <td class="align-middle text-end">
                                    <a href="/index.php?page=resources&action=download&id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-primary mb-1 mb-md-0" target="_blank">Télécharger</a>
                                    <a href="/index.php?page=resources&action=edit&id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-secondary mb-1 mb-md-0">Modifier</a>
                                    <form action="/index.php?page=resources&action=delete" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette ressource ? Le fichier sera définitivement supprimé.');">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
