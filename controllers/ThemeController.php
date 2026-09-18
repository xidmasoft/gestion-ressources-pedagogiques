<?php
// controllers/ThemeController.php
require_once __DIR__ . '/../models/Theme.php';

class ThemeController {
    private $model;

    public function __construct() {
        try {
            $this->model = new Theme();
        } catch (\PDOException $e) {
            $this->model = null;
        }
    }

    public function index() {
        $pageTitle = 'Thèmes';
        require_once __DIR__ . '/../views/themes/index.php';
    }
}
