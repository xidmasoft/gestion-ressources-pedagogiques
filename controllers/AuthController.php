<?php
// controllers/AuthController.php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../core/Auth.php';

class AuthController {
    private $userModel;

    public function __construct() {
        try {
            $this->userModel = new User();
        } catch (\PDOException $e) {
            $this->userModel = null;
        }
    }

    public function login() {
        if (Auth::check()) {
            header("Location: /index.php?page=dashboard");
            die();
        }

        $errors = [];
        $email = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf()) {
                $errors[] = "Requête invalide ou expirée.";
            } else {
                $email = isset($_POST['email']) ? trim($_POST['email']) : $email;
                $password = isset($_POST['password']) ? $_POST['password'] : $password;

                if (empty($email) || empty($password)) {
                    $errors[] = "Veuillez remplir tous les champs.";
                } elseif ($this->userModel) {
                    // Check brute force limits
                    $attempts = $this->userModel->getRecentFailedAttempts($email, 5);
                    if ($attempts >= 5) {
                        $errors[] = "Email ou mot de passe incorrect."; // Generic message, but actually rate limited
                    } else {
                        $user = $this->userModel->getByEmail($email);
                        if ($user && $user['status'] === 'active' && password_verify($password, $user['password_hash'])) {
                            // Success
                            $this->userModel->clearFailedAttempts($email);

                            session_regenerate_id(true);
                            $_SESSION['user_id'] = $user['id'];
                            $_SESSION['user_name'] = $user['name'];
                            $_SESSION['role'] = $user['role'];

                            header("Location: /index.php?page=dashboard");
                            die();
                        } else {
                            $this->userModel->logFailedAttempt($email);
                            $errors[] = "Email ou mot de passe incorrect.";
                        }
                    }
                } else {
                    $errors[] = "Erreur de connexion à la base de données.";
                }
            }
        }

        $pageTitle = 'Connexion';
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function logout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
            $_SESSION = [];
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
            session_destroy();
            header("Location: /index.php?page=login");
            die();
        }

        // Si GET ou pas de CSRF, on refuse ou on redirige
        http_response_code(403);
        die("Déconnexion non autorisée.");
    }
}
