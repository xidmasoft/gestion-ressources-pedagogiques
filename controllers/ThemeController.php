<?php
// controllers/ThemeController.php
require_once __DIR__ . '/../models/Theme.php';
require_once __DIR__ . '/../models/Discipline.php';
require_once __DIR__ . '/../core/Auth.php';

class ThemeController {
    private $model;
    private $disciplineModel;

    public function __construct() {
        Auth::requireAdmin();
        try {
            $this->model = new Theme();
            $this->disciplineModel = new Discipline();
        } catch (\PDOException $e) {
            $this->model = null;
            $this->disciplineModel = null;
        }
    }

    public function index() {
        $pageTitle = 'Thèmes';
        if ($this->model) {
            $themes = $this->model->getAllWithDiscipline();
        } else {
            $themes = [];
        }
        require_once __DIR__ . '/../views/themes/index.php';
    }

    public function create() {
        $pageTitle = 'Ajouter un Thème';
        $errors = [];
        $name = '';
        $description = '';
        $disciplineId = '';

        $disciplines = $this->disciplineModel ? $this->disciplineModel->getAll() : [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf()) {
                $errors[] = "Jeton de sécurité invalide ou expiré.";
            }

            $name = isset($_POST['name']) ? trim($_POST['name']) : $name;
            $description = isset($_POST['description']) ? trim($_POST['description']) : $description;
            $disciplineId = filter_input(INPUT_POST, 'discipline_id', FILTER_VALIDATE_INT) ?: $disciplineId;

            if (empty($errors)) {
                if (empty($name)) {
                    $errors[] = "Le nom du thème est obligatoire.";
                }

                if (!$disciplineId) {
                    $errors[] = "La discipline est obligatoire.";
                } elseif (!$this->disciplineModel->getById($disciplineId)) {
                    $errors[] = "La discipline sélectionnée n'existe pas.";
                }

                if (empty($errors) && $this->model->nameExistsInDiscipline($name, $disciplineId)) {
                    $errors[] = "Un thème portant ce nom existe déjà dans cette discipline.";
                }
            }

            if (empty($errors)) {
                try {
                    $this->model->create($disciplineId, $name, $description);
                    setFlash('success', "Le thème a été ajouté avec succès.");
                    header("Location: /index.php?page=themes");
                    exit;
                } catch (\PDOException $e) {
                    $errors[] = "Erreur lors de l'enregistrement dans la base de données.";
                }
            }
        }

        require_once __DIR__ . '/../views/themes/create.php';
    }

    public function edit() {
        $pageTitle = 'Modifier un Thème';
        $errors = [];
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            header("Location: /index.php?page=themes");
            exit;
        }

        $theme = $this->model->getById($id);
        if (!$theme) {
            header("Location: /index.php?page=themes");
            exit;
        }

        $disciplines = $this->disciplineModel ? $this->disciplineModel->getAll() : [];
        $name = $theme['name'];
        $description = $theme['description'];
        $disciplineId = $theme['discipline_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf()) {
                $errors[] = "Jeton de sécurité invalide ou expiré.";
            }

            $name = isset($_POST['name']) ? trim($_POST['name']) : $name;
            $description = isset($_POST['description']) ? trim($_POST['description']) : $description;
            $disciplineId = filter_input(INPUT_POST, 'discipline_id', FILTER_VALIDATE_INT) ?: $disciplineId;

            if (empty($errors)) {
                if (empty($name)) {
                    $errors[] = "Le nom du thème est obligatoire.";
                }

                if (!$disciplineId) {
                    $errors[] = "La discipline est obligatoire.";
                } elseif (!$this->disciplineModel->getById($disciplineId)) {
                    $errors[] = "La discipline sélectionnée n'existe pas.";
                }

                if (empty($errors) && $this->model->nameExistsInDiscipline($name, $disciplineId, $id)) {
                    $errors[] = "Un autre thème portant ce nom existe déjà dans cette discipline.";
                }
            }

            if (empty($errors)) {
                try {
                    $this->model->update($id, $disciplineId, $name, $description);
                    setFlash('success', "Le thème a été modifié avec succès.");
                    header("Location: /index.php?page=themes");
                    exit;
                } catch (\PDOException $e) {
                    $errors[] = "Erreur lors de la mise à jour dans la base de données.";
                }
            }
        }

        require_once __DIR__ . '/../views/themes/edit.php';
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf()) {
                setFlash('error', "Jeton de sécurité invalide ou expiré.");
            } else {
                $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
                if ($id) {
                    try {
                        $theme = $this->model->getById($id);
                        if ($theme) {
                            $this->model->delete($id);
                            setFlash('success', "Le thème a été supprimé avec succès.");
                        }
                    } catch (\PDOException $e) {
                        if ($e->getCode() == 23000 && isset($e->errorInfo[1]) && $e->errorInfo[1] == 1451) {
                            setFlash('error', "Impossible de supprimer ce thème : des ressources y sont encore associées.");
                        } else {
                            setFlash('error', "Erreur lors de la suppression dans la base de données.");
                        }
                    }
                }
            }
        }
        header("Location: /index.php?page=themes");
        exit;
    }
}
