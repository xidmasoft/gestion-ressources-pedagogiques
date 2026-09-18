<?php
// controllers/DisciplineController.php
require_once __DIR__ . '/../models/Discipline.php';

class DisciplineController {
    private $model;

    public function __construct() {
        try {
            $this->model = new Discipline();
        } catch (\PDOException $e) {
            // Ignore DB connection errors during UI phase testing if DB is not set up
            $this->model = null;
        }
    }

    public function index() {
        $pageTitle = 'Disciplines';
        require_once __DIR__ . '/../views/disciplines/index.php';
    }
}
