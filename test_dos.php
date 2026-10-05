<?php
$_SESSION['csrf_token'] = 'token';
$_POST['csrf_token'] = ['array_token'];

function verify_csrf(): bool {
    $sessionToken = $_SESSION['csrf_token'] ?? '';
    $postToken = $_POST['csrf_token'] ?? '';

    if (empty($sessionToken) || empty($postToken)) {
        return false;
    }

    return hash_equals($sessionToken, $postToken);
}

try {
    verify_csrf();
    echo "Success\n";
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
