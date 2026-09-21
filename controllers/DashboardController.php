<?php
// controllers/DashboardController.php
require_once __DIR__ . '/../models/Discipline.php';
require_once __DIR__ . '/../models/Theme.php';
require_once __DIR__ . '/../models/Resource.php';
require_once __DIR__ . '/../core/Auth.php';

class DashboardController {
    public function index() {
        Auth::requireLogin();
        $pageTitle = 'Tableau de Bord';

        $disciplineModel = new Discipline();
        $themeModel = new Theme();
        $resourceModel = new Resource();

        // Global stats
        $totalDisciplines = $disciplineModel->getTotalCount();
        $totalThemes = $themeModel->getTotalCount();
        $totalResources = $resourceModel->getTotalCount();

        // Distributions
        $distributionByDiscipline = $resourceModel->getDistributionByDiscipline();
        $distributionByTheme = $resourceModel->getDistributionByTheme(10);

        // Latest resources
        $latestResources = $resourceModel->getLatest(5);

        require_once __DIR__ . '/../views/dashboard.php';
    }
}
