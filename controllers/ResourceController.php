<?php
// controllers/ResourceController.php
require_once __DIR__ . '/../models/Resource.php';

class ResourceController {
    private $model;

    public function __construct() {
        $this->model = new Resource();
    }
}
