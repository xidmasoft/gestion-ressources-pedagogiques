<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-4">
    <h1 class="h3 mb-0">Modifier une Discipline</h1>
    <a href="/index.php?page=disciplines" class="btn btn-outline-secondary btn-sm">Retour à la liste</a>
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

<div class="card">
    <div class="card-body">
        <form action="/index.php?page=disciplines&action=edit&id=<?= h($id) ?>" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Nom de la discipline <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" value="<?= h($name ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4"><?= h($description ?? '') ?></textarea>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary">Mettre à jour la discipline</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
