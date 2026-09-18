<?php
// models/Discipline.php
require_once __DIR__ . '/../config/database.php';

class Discipline {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
    }
}
