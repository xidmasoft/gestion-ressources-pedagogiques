<?php
// controllers/ResourceController.php
require_once __DIR__ . '/../models/Resource.php';

class ResourceController {
    private $model;

    public function __construct() {
        try {
            $this->model = new Resource();
        } catch (\PDOException $e) {
            $this->model = null;
        }
    }

    public function index() {
        $pageTitle = 'Ressources';
        require_once __DIR__ . '/../views/resources/index.php';
    }
}
