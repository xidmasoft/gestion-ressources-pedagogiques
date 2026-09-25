<?php
// views/layout/messages.php

$successMessages = getFlash('success');
$errorMessages = getFlash('error');

if (!empty($successMessages)):
    foreach ($successMessages as $msg): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= h($msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endforeach;
endif;

if (!empty($errorMessages)):
    foreach ($errorMessages as $msg): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= h($msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endforeach;
endif;
?>
