<?php
// controllers/DashboardController.php

class DashboardController {
    public function index() {
        $pageTitle = 'Tableau de Bord';
        require_once __DIR__ . '/../views/dashboard.php';
    }
}
