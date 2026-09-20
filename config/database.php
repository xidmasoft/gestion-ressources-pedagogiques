<?php
// config/database.php

define('DB_HOST', 'localhost');
define('DB_NAME', 'grp_db');
define('DB_USER', 'root');
define('DB_PASS', ''); // Placeholder for local dev password

function getDBConnection() {
    $dsn = "sqlite:/tmp/test.db";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        $pdo->exec('PRAGMA foreign_keys = ON;');
        return $pdo;
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
}
