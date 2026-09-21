<?php
// controllers/UserController.php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../core/Auth.php';

class UserController {
    private $model;

    public function __construct() {
        Auth::requireAdmin();
        try {
            $this->model = new User();
        } catch (\PDOException $e) {
            $this->model = null;
        }
    }

    public function index() {
        $pageTitle = 'Gestion des Utilisateurs';
        $users = $this->model ? $this->model->getAll() : [];
        require_once __DIR__ . '/../views/users/index.php';
    }

    public function create() {
        $pageTitle = 'Ajouter un Utilisateur';
        $errors = [];
        $name = '';
        $email = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf()) {
                $errors[] = "Requête invalide ou expirée.";
            } else {
                $name = trim($_POST['name'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';
                $role = $_POST['role'] ?? 'user';

                if (empty($name) || empty($email) || empty($password)) {
                    $errors[] = "Tous les champs sont obligatoires.";
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Le format de l'e-mail est invalide.";
                } elseif (strlen($password) < 12) {
                    $errors[] = "Le mot de passe doit contenir au moins 12 caractères.";
                } elseif (!in_array($role, ['user', 'admin'])) {
                    $errors[] = "Rôle invalide.";
                } elseif ($this->model && $this->model->emailExists($email)) {
                    $errors[] = "Cet e-mail est déjà utilisé.";
                } else {
                    if ($this->model && $this->model->create($name, $email, $password, $role)) {
                        $_SESSION['success'] = "Utilisateur créé avec succès.";
                        header("Location: /index.php?page=users");
                        die();
                    } else {
                        $errors[] = "Erreur lors de la création de l'utilisateur.";
                    }
                }
            }
        }
        require_once __DIR__ . '/../views/users/create.php';
    }

    public function edit() {
        $pageTitle = 'Modifier un Utilisateur';
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            header("Location: /index.php?page=users");
            die();
        }

        $user = $this->model ? $this->model->getById($id) : null;
        if (!$user) {
            header("Location: /index.php?page=users");
            die();
        }

        $errors = [];
        $name = $user['name'];
        $email = $user['email'];
        $role = $user['role'];
        $status = $user['status'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf()) {
                $errors[] = "Requête invalide ou expirée.";
            } else {
                $name = trim($_POST['name'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $role = $_POST['role'] ?? 'user';
                $status = $_POST['status'] ?? 'active';

                if (empty($name) || empty($email)) {
                    $errors[] = "Le nom et l'e-mail sont obligatoires.";
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Format d'e-mail invalide.";
                } elseif (!in_array($role, ['user', 'admin']) || !in_array($status, ['active', 'locked'])) {
                    $errors[] = "Données invalides.";
                } elseif ($this->model->emailExists($email, $id)) {
                    $errors[] = "Cet e-mail est déjà utilisé par un autre utilisateur.";
                } else {
                    // Prevent demoting or locking the last active admin
                    if ($user['role'] === 'admin' && ($role !== 'admin' || $status !== 'active')) {
                        $allUsers = $this->model->getAll();
                        $activeAdminsCount = 0;
                        foreach ($allUsers as $u) {
                            if ($u['role'] === 'admin' && $u['status'] === 'active') {
                                $activeAdminsCount++;
                            }
                        }
                        if ($activeAdminsCount <= 1) {
                            $errors[] = "Vous ne pouvez pas modifier le rôle ou verrouiller le dernier administrateur actif.";
                        }
                    }

                    if (empty($errors)) {
                        if ($this->model->update($id, $name, $email, $role) && $this->model->setStatus($id, $status)) {
                            $_SESSION['success'] = "Utilisateur mis à jour avec succès.";
                            header("Location: /index.php?page=users");
                            die();
                        } else {
                            $errors[] = "Erreur lors de la mise à jour.";
                        }
                    }
                }
            }
        }
        require_once __DIR__ . '/../views/users/edit.php';
    }

    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf()) {
                $_SESSION['error'] = "Requête invalide ou expirée.";
            } else {
                $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
                $password = $_POST['password'] ?? '';

                if (!$id || strlen($password) < 12) {
                    $_SESSION['error'] = "Le mot de passe doit contenir au moins 12 caractères.";
                } elseif ($this->model) {
                    $this->model->updatePassword($id, $password);
                    $_SESSION['success'] = "Mot de passe réinitialisé avec succès.";
                }
            }
        }
        header("Location: /index.php?page=users");
        die();
    }
}
