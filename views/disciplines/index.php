<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-4">
    <h1 class="h3 mb-0">Gestion des Disciplines</h1>
    <a href="/index.php?page=disciplines&action=create" class="btn btn-primary btn-sm">Ajouter une discipline</a>
</div>



<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Date de création</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($disciplines)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Aucune discipline trouvée.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($disciplines as $d): ?>
                            <tr>
                                <td class="align-middle fw-medium"><?= h($d['name']) ?></td>
                                <td class="align-middle"><?= h($d['description']) ?></td>
                                <td class="align-middle"><?= date('d/m/Y H:i', strtotime($d['created_at'])) ?></td>
                                <td class="align-middle text-end">
                                    <a href="/index.php?page=disciplines&action=edit&id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-secondary mb-1 mb-md-0">Modifier</a>
                                    <form action="/index.php?page=disciplines&action=delete" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette discipline ? Cette action est irréversible.');">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= $d['id'] ?>">
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
