<?php
// controllers/DisciplineController.php
require_once __DIR__ . '/../models/Discipline.php';
require_once __DIR__ . '/../core/Auth.php';

class DisciplineController {
    private $model;

    public function __construct() {
        Auth::requireAdmin();
        try {
            $this->model = new Discipline();
        } catch (\PDOException $e) {
            $this->model = null;
        }
    }

    public function index() {
        $pageTitle = 'Disciplines';
        if ($this->model) {
            $disciplines = $this->model->getAll();
        } else {
            $disciplines = [];
        }
        require_once __DIR__ . '/../views/disciplines/index.php';
    }

    public function create() {
        $pageTitle = 'Ajouter une Discipline';
        $errors = [];
        $name = '';
        $description = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf()) {
                $errors[] = "Jeton de sécurité invalide ou expiré.";
            }

            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (empty($errors) && empty($name)) {
                $errors[] = "Le nom de la discipline est obligatoire.";
            } elseif ($this->model->nameExists($name)) {
                $errors[] = "Une discipline portant ce nom existe déjà.";
            }

            if (empty($errors)) {
                try {
                    $this->model->create($name, $description);
                    $_SESSION['success'] = "La discipline a été ajoutée avec succès.";
                    header("Location: /index.php?page=disciplines");
                    exit;
                } catch (\PDOException $e) {
                    $errors[] = "Erreur lors de l'enregistrement dans la base de données.";
                }
            }
        }

        require_once __DIR__ . '/../views/disciplines/create.php';
    }

    public function edit() {
        $pageTitle = 'Modifier une Discipline';
        $errors = [];
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            header("Location: /index.php?page=disciplines");
            exit;
        }

        $discipline = $this->model->getById($id);
        if (!$discipline) {
            header("Location: /index.php?page=disciplines");
            exit;
        }

        $name = $discipline['name'];
        $description = $discipline['description'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf()) {
                $errors[] = "Jeton de sécurité invalide ou expiré.";
            }

            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (empty($errors)) {
                if (empty($name)) {
                    $errors[] = "Le nom de la discipline est obligatoire.";
                } elseif ($this->model->nameExists($name, $id)) {
                    $errors[] = "Une autre discipline portant ce nom existe déjà.";
                }
            }

            if (empty($errors)) {
                try {
                    $this->model->update($id, $name, $description);
                    $_SESSION['success'] = "La discipline a été modifiée avec succès.";
                    header("Location: /index.php?page=disciplines");
                    exit;
                } catch (\PDOException $e) {
                    $errors[] = "Erreur lors de la mise à jour dans la base de données.";
                }
            }
        }

        require_once __DIR__ . '/../views/disciplines/edit.php';
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf()) {
                $_SESSION['error'] = "Jeton de sécurité invalide ou expiré.";
            } else {
                $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
                if ($id) {
                    try {
                        $discipline = $this->model->getById($id);
                        if ($discipline) {
                            $this->model->delete($id);
                            $_SESSION['success'] = "La discipline a été supprimée avec succès.";
                        }
                    } catch (\PDOException $e) {
                        if ($e->getCode() == 23000) {
                            $_SESSION['error'] = "Suppression impossible : cette discipline contient des thèmes. Veuillez d'abord supprimer ou déplacer ses thèmes.";
                        } else {
                            $_SESSION['error'] = "Erreur lors de la suppression dans la base de données.";
                        }
                    }
                }
            }
        }
        header("Location: /index.php?page=disciplines");
        exit;
    }
}
