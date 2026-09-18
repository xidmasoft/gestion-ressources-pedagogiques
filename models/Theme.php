<?php
// models/Theme.php
require_once __DIR__ . '/../config/database.php';

class Theme {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
    }
}
