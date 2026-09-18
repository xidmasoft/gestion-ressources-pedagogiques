<?php
// models/Resource.php
require_once __DIR__ . '/../config/database.php';

class Resource {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
    }
}
