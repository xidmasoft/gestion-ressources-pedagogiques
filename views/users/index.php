<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-4">
    <h1 class="h3 mb-0">Gestion des Utilisateurs</h1>
    <a href="/index.php?page=users&action=create" class="btn btn-primary btn-sm">Ajouter un utilisateur</a>
</div>



<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Date de création</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Aucun utilisateur trouvé.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td class="align-middle fw-medium"><?= h($u['name']) ?></td>
                                <td class="align-middle"><?= h($u['email']) ?></td>
                                <td class="align-middle">
                                    <span class="badge bg-<?= $u['role'] === 'admin' ? 'danger' : 'primary' ?>"><?= h($u['role']) ?></span>
                                </td>
                                <td class="align-middle">
                                    <span class="badge bg-<?= $u['status'] === 'active' ? 'success' : 'secondary' ?>"><?= h($u['status']) ?></span>
                                </td>
                                <td class="align-middle"><?= date('d/m/Y H:i', strtotime($u['created_at'])) ?></td>
                                <td class="align-middle text-end">
                                    <a href="/index.php?page=users&action=edit&id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-secondary">Modifier</a>
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
