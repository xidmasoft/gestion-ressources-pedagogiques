<?php
// controllers/ThemeController.php
require_once __DIR__ . '/../models/Theme.php';

class ThemeController {
    private $model;

    public function __construct() {
        $this->model = new Theme();
    }
}
