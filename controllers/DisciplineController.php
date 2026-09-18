<?php
// controllers/DisciplineController.php
require_once __DIR__ . '/../models/Discipline.php';

class DisciplineController {
    private $model;

    public function __construct() {
        $this->model = new Discipline();
    }
}
