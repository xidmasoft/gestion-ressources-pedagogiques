<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-4">
    <h1 class="h3 mb-0">Gestion des Thèmes</h1>
    <a href="/index.php?page=themes&action=create" class="btn btn-primary btn-sm">Ajouter un thème</a>
</div>



<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nom du thème</th>
                        <th>Discipline</th>
                        <th>Description</th>
                        <th>Date de création</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($themes)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Aucun thème trouvé.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($themes as $t): ?>
                            <tr>
                                <td class="align-middle fw-medium"><?= h($t['name']) ?></td>
                                <td class="align-middle"><span class="badge bg-secondary"><?= h($t['discipline_name']) ?></span></td>
                                <td class="align-middle"><?= h($t['description']) ?></td>
                                <td class="align-middle"><?= date('d/m/Y H:i', strtotime($t['created_at'])) ?></td>
                                <td class="align-middle text-end">
                                    <a href="/index.php?page=themes&action=edit&id=<?= $t['id'] ?>" class="btn btn-sm btn-outline-secondary mb-1 mb-md-0">Modifier</a>
                                    <form action="/index.php?page=themes&action=delete" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce thème ? Cette action est irréversible.');">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= $t['id'] ?>">
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
