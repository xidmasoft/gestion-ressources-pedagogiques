<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-4">
    <h1 class="h3 mb-0">Modifier une Ressource</h1>
    <a href="/index.php?page=resources" class="btn btn-outline-secondary btn-sm">Retour à la liste</a>
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
        <form action="/index.php?page=resources&action=edit&id=<?= h($id) ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="theme_id" class="form-label">Thème d'appartenance <span class="text-danger">*</span></label>
                <select class="form-select" id="theme_id" name="theme_id" required>
                    <option value="">-- Sélectionner un thème --</option>
                    <?php
                    $currentDiscipline = '';
                    foreach ($themes as $t):
                        if ($currentDiscipline !== $t['discipline_name']):
                            if ($currentDiscipline !== '') echo '</optgroup>';
                            $currentDiscipline = $t['discipline_name'];
                            echo '<optgroup label="' . h($currentDiscipline) . '">';
                        endif;
                    ?>
                        <option value="<?= $t['id'] ?>" <?= (isset($themeId) && $themeId == $t['id']) ? 'selected' : '' ?>>
                            <?= h($t['name']) ?>
                        </option>
                    <?php
                    endforeach;
                    if ($currentDiscipline !== '') echo '</optgroup>';
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Titre de la ressource <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" value="<?= h($title ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= h($description ?? '') ?></textarea>
            </div>

            <div class="mb-4 p-3 bg-light border rounded">
                <label class="form-label fw-bold">Fichier actuel</label>
                <p class="mb-3">
                    <span class="badge bg-info text-dark"><?= h(strtoupper(pathinfo($resource['file_path'], PATHINFO_EXTENSION))) ?></span>
                    <?= round($resource['file_size'] / 1024 / 1024, 2) ?> Mo
                    <a href="/index.php?page=resources&action=download&id=<?= $resource['id'] ?>" target="_blank" class="ms-2">Télécharger pour vérifier</a>
                </p>

                <hr>

                <label for="file" class="form-label fw-bold">Remplacer le fichier (Optionnel)</label>
                <input type="file" class="form-control" id="file" name="file">
                <div class="form-text">
                    <p class="mb-1">Laissez ce champ vide si vous ne souhaitez pas modifier le fichier actuel.</p>
                    <p class="mb-1">Taille maximale : <strong>10 Mo</strong></p>
                    <p class="mb-0">Formats autorisés : PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, JPG, PNG.</p>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary">Mettre à jour la ressource</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
