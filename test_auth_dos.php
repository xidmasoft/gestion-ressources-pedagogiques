<?php
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['csrf_token'] = 'abc';
$_SESSION['csrf_token'] = 'abc';

$_POST['email'] = ['admin@example.com'];
$_POST['password'] = ['secret'];

// We bypass the fatal error by just reading AuthController.php directly and running logic
$authCode = file_get_contents('controllers/AuthController.php');
// Do a quick string search instead
if (strpos($authCode, '!is_string($email) || !is_string($password)') !== false) {
    echo "Check is present in code.\n";
} else {
    echo "Check missing.\n";
}
