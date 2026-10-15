<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-4">
    <h1 class="h3 mb-0">Modifier un Utilisateur</h1>
    <a href="/index.php?page=users" class="btn btn-outline-secondary btn-sm">Retour à la liste</a>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger" role="alert">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= h($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informations générales</h5>
            </div>
            <div class="card-body">
                <form action="/index.php?page=users&action=edit&id=<?= h($id) ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= h($name ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= h($email ?? '') ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Rôle</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="user" <?= (isset($role) && $role === 'user') ? 'selected' : '' ?>>Utilisateur</option>
                                <option value="admin" <?= (isset($role) && $role === 'admin') ? 'selected' : '' ?>>Administrateur</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Statut</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="active" <?= (isset($status) && $status === 'active') ? 'selected' : '' ?>>Actif</option>
                                <option value="locked" <?= (isset($status) && $status === 'locked') ? 'selected' : '' ?>>Verrouillé</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Réinitialiser le mot de passe</h5>
            </div>
            <div class="card-body">
                <form action="/index.php?page=users&action=reset_password" method="POST" onsubmit="return confirm('Confirmer la réinitialisation du mot de passe de cet utilisateur ?');">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= h($id) ?>">
                    <div class="mb-3">
                        <label for="password" class="form-label">Nouveau mot de passe <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="password" name="password" required minlength="12" placeholder="Minimum 12 caractères">
                    </div>
                    <button type="submit" class="btn btn-warning w-100">Réinitialiser</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
