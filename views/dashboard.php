<?php require_once __DIR__ . '/layout/header.php'; ?>

<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 border-bottom pb-2">Tableau de Bord</h1>
        <p class="text-muted">Vue d'ensemble de vos ressources pédagogiques.</p>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Global stats -->
    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center py-4">
                <h5 class="card-title text-muted mb-3">Disciplines</h5>
                <h2 class="display-6 fw-bold text-primary"><?= (int) $totalDisciplines ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center py-4">
                <h5 class="card-title text-muted mb-3">Thèmes</h5>
                <h2 class="display-6 fw-bold text-success"><?= (int) $totalThemes ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center py-4">
                <h5 class="card-title text-muted mb-3">Ressources</h5>
                <h2 class="display-6 fw-bold text-info"><?= (int) $totalResources ?></h2>
            </div>
        </div>
    </div>
</div>

<!-- Quick actions -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex flex-wrap gap-2 justify-content-center">
                <a href="/index.php?page=disciplines&action=create" class="btn btn-outline-primary">Ajouter une discipline</a>
                <a href="/index.php?page=themes&action=create" class="btn btn-outline-success">Ajouter un thème</a>
                <a href="/index.php?page=resources&action=create" class="btn btn-outline-info">Ajouter une ressource</a>
                <a href="/index.php?page=resources" class="btn btn-primary">Voir toutes les ressources</a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Distribution by Discipline -->
    <div class="col-lg-6">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-white border-bottom pb-2 pt-3">
                <h5 class="card-title mb-0">Répartition par discipline</h5>
            </div>
            <div class="card-body">
                <?php if (empty($distributionByDiscipline)): ?>
                    <p class="text-muted text-center my-4">Aucune donnée disponible.</p>
                <?php else: ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($distributionByDiscipline as $dist): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <?= h($dist['discipline_name']) ?>
                                <span class="badge bg-primary rounded-pill"><?= (int) $dist['count'] ?> ressource<?= (int)$dist['count'] > 1 ? 's' : '' ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Distribution by Theme (Top 10) -->
    <div class="col-lg-6">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-white border-bottom pb-2 pt-3">
                <h5 class="card-title mb-0">Top 10 des thèmes</h5>
            </div>
            <div class="card-body p-0 table-responsive">
                <?php if (empty($distributionByTheme)): ?>
                    <p class="text-muted text-center my-4">Aucune donnée disponible.</p>
                <?php else: ?>
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Thème</th>
                                <th>Discipline</th>
                                <th class="text-end">Ressources</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($distributionByTheme as $dist): ?>
                                <tr>
                                    <td><?= h($dist['theme_name']) ?></td>
                                    <td><span class="badge bg-secondary"><?= h($dist['discipline_name']) ?></span></td>
                                    <td class="text-end fw-bold"><?= (int) $dist['count'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Latest Resources -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom pb-2 pt-3">
                <h5 class="card-title mb-0">Dernières ressources ajoutées</h5>
            </div>
            <div class="card-body p-0 table-responsive">
                <?php if (empty($latestResources)): ?>
                    <p class="text-muted text-center my-4">Aucune ressource disponible.</p>
                <?php else: ?>
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Titre</th>
                                <th>Discipline / Thème</th>
                                <th>Type</th>
                                <th>Date d'ajout</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($latestResources as $resource): ?>
                                <tr>
                                    <td><?= h($resource['title']) ?></td>
                                    <td>
                                        <span class="badge bg-secondary"><?= h($resource['discipline_name']) ?></span><br>
                                        <small class="text-muted"><?= h($resource['theme_name']) ?></small>
                                    </td>
                                    <td>
                                        <?php
                                            $ext = strtoupper(pathinfo($resource['file_path'], PATHINFO_EXTENSION));
                                            $badgeClass = 'bg-secondary';
                                            if ($ext === 'PDF') $badgeClass = 'bg-info text-dark';
                                            elseif (in_array($ext, ['DOC', 'DOCX'])) $badgeClass = 'bg-primary';
                                            elseif (in_array($ext, ['XLS', 'XLSX'])) $badgeClass = 'bg-success';
                                            elseif (in_array($ext, ['PPT', 'PPTX'])) $badgeClass = 'bg-warning text-dark';
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= h($ext) ?></span>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($resource['created_at'])) ?></td>
                                    <td class="text-end">
                                        <a href="/index.php?page=resources&action=download&id=<?= $resource['id'] ?>" class="btn btn-sm btn-outline-primary" target="_blank">Télécharger</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
