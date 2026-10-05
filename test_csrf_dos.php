<?php
$_SESSION = ['csrf_token' => 'abc'];
$_POST = ['csrf_token' => ['xyz']];

require_once 'core/helpers.php';
try {
    $result = verify_csrf();
    echo "CSRF returned: " . ($result ? 'true' : 'false') . "\n";
} catch (Throwable $e) {
    echo "CSRF Error: " . $e->getMessage() . "\n";
}
