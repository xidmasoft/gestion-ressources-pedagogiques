<?php
// controllers/ResourceController.php
require_once __DIR__ . '/../models/Resource.php';
require_once __DIR__ . '/../models/Theme.php';
require_once __DIR__ . '/../models/Discipline.php';

class ResourceController {
    private $model;
    private $themeModel;
    private $disciplineModel;

    // Allowed MIME types and extensions
    private $allowedMimes = [
        'application/pdf' => 'pdf',
        'application/msword' => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/vnd.ms-excel' => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
        'application/vnd.ms-powerpoint' => 'ppt',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
        'text/plain' => 'txt',
        'image/jpeg' => 'jpg',
        'image/png' => 'png'
    ];
    private $maxFileSize = 10485760; // 10 MB

    public function __construct() {
        try {
            $this->model = new Resource();
            $this->themeModel = new Theme();
            $this->disciplineModel = new Discipline();
        } catch (\PDOException $e) {
            $this->model = null;
            $this->themeModel = null;
            $this->disciplineModel = null;
        }
    }

    public function index() {
        $pageTitle = 'Ressources';

        $q = trim($_GET['q'] ?? '');
        $disciplineId = filter_input(INPUT_GET, 'discipline_id', FILTER_VALIDATE_INT);
        $themeId = filter_input(INPUT_GET, 'theme_id', FILTER_VALIDATE_INT);

        if ($this->model) {
            // Coherence check
            if ($disciplineId && $themeId) {
                $theme = $this->themeModel->getById($themeId);
                if ($theme && $theme['discipline_id'] != $disciplineId) {
                    $themeId = null; // Ignore theme if it doesn't belong to the selected discipline
                }
            }

            $resources = $this->model->search($q, $disciplineId, $themeId);
            $disciplines = $this->disciplineModel->getAll();
            $themes = $this->themeModel->getAllWithDiscipline();
        } else {
            $resources = [];
            $disciplines = [];
            $themes = [];
        }

        require_once __DIR__ . '/../views/resources/index.php';
    }

    private function handleUpload($fileArray, &$errors) {
        if ($fileArray['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Erreur lors du téléversement du fichier.";
            return false;
        }

        if ($fileArray['size'] > $this->maxFileSize) {
            $errors[] = "Le fichier dépasse la taille maximale autorisée (10 Mo).";
            return false;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $fileArray['tmp_name']);
        finfo_close($finfo);

        if (!array_key_exists($mime, $this->allowedMimes)) {
            $errors[] = "Le type de fichier n'est pas autorisé.";
            return false;
        }

        $ext = $this->allowedMimes[$mime];

        // Prevent executable files bypassing magic bytes
        $dangerousExts = ['php', 'php3', 'php4', 'php5', 'phtml', 'phar', 'cgi', 'html', 'js', 'exe', 'sh', 'bat'];
        $originalExt = strtolower(pathinfo($fileArray['name'], PATHINFO_EXTENSION));
        if (in_array($originalExt, $dangerousExts)) {
            $errors[] = "Ce format de fichier est strictement interdit pour des raisons de sécurité.";
            return false;
        }

        $newFileName = bin2hex(random_bytes(16)) . '.' . $ext;
        $uploadDir = __DIR__ . '/../uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $destination = $uploadDir . $newFileName;

        if (move_uploaded_file($fileArray['tmp_name'], $destination)) {
            return [
                'path' => $newFileName,
                'mime' => $mime,
                'size' => $fileArray['size']
            ];
        } else {
            $errors[] = "Impossible d'enregistrer le fichier sur le serveur.";
            return false;
        }
    }

    public function create() {
        $pageTitle = 'Ajouter une Ressource';
        $errors = [];
        $title = '';
        $description = '';
        $themeId = '';

        // Simplification for the view: fetch all themes
        $themes = $this->themeModel ? $this->themeModel->getAllWithDiscipline() : [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf()) {
                $errors[] = "Jeton de sécurité invalide ou expiré.";
            }

            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $themeId = filter_input(INPUT_POST, 'theme_id', FILTER_VALIDATE_INT);

            if (empty($errors)) {
                if (empty($title)) {
                    $errors[] = "Le titre de la ressource est obligatoire.";
                }

                if (!$themeId) {
                    $errors[] = "Le thème est obligatoire.";
                } elseif (!$this->themeModel->getById($themeId)) {
                    $errors[] = "Le thème sélectionné n'existe pas.";
                }

                if (empty($errors) && $this->model->titleExistsInTheme($title, $themeId)) {
                    $errors[] = "Une ressource portant ce titre existe déjà dans ce thème.";
                }
            }

            if (empty($errors)) {
                if (!isset($_FILES['file']) || $_FILES['file']['error'] === UPLOAD_ERR_NO_FILE) {
                    $errors[] = "Le fichier est obligatoire.";
                } else {
                    $fileData = $this->handleUpload($_FILES['file'], $errors);
                    if ($fileData && empty($errors)) {
                        try {
                            $this->model->create($themeId, $title, $description, $fileData['path'], $fileData['mime'], $fileData['size']);
                            $_SESSION['success'] = "La ressource a été ajoutée avec succès.";
                            header("Location: /index.php?page=resources");
                            exit;
                        } catch (\PDOException $e) {
                            $errors[] = "Erreur lors de l'enregistrement dans la base de données.";
                            // Clean up file if db fails
                            @unlink(__DIR__ . '/../uploads/' . $fileData['path']);
                        }
                    }
                }
            }
        }

        require_once __DIR__ . '/../views/resources/create.php';
    }

    public function edit() {
        $pageTitle = 'Modifier une Ressource';
        $errors = [];
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            header("Location: /index.php?page=resources");
            exit;
        }

        $resource = $this->model->getById($id);
        if (!$resource) {
            header("Location: /index.php?page=resources");
            exit;
        }

        $themes = $this->themeModel ? $this->themeModel->getAllWithDiscipline() : [];
        $title = $resource['title'];
        $description = $resource['description'];
        $themeId = $resource['theme_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf()) {
                $errors[] = "Jeton de sécurité invalide ou expiré.";
            }

            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $themeId = filter_input(INPUT_POST, 'theme_id', FILTER_VALIDATE_INT);

            if (empty($errors)) {
                if (empty($title)) {
                    $errors[] = "Le titre de la ressource est obligatoire.";
                }

                if (!$themeId) {
                    $errors[] = "Le thème est obligatoire.";
                } elseif (!$this->themeModel->getById($themeId)) {
                    $errors[] = "Le thème sélectionné n'existe pas.";
                }

                if (empty($errors) && $this->model->titleExistsInTheme($title, $themeId, $id)) {
                    $errors[] = "Une autre ressource portant ce titre existe déjà dans ce thème.";
                }
            }

            if (empty($errors)) {
                $fileData = null;
                $newFileUploaded = isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE;

                if ($newFileUploaded) {
                    $fileData = $this->handleUpload($_FILES['file'], $errors);
                }

                if (empty($errors)) {
                    try {
                        if ($newFileUploaded && $fileData) {
                            $this->model->update($id, $themeId, $title, $description, $fileData['path'], $fileData['mime'], $fileData['size']);
                            // Safe to delete old file
                            $oldFilePath = __DIR__ . '/../uploads/' . $resource['file_path'];
                            if (file_exists($oldFilePath)) {
                                @unlink($oldFilePath);
                            }
                        } else {
                            $this->model->updateMetadata($id, $themeId, $title, $description);
                        }
                        $_SESSION['success'] = "La ressource a été modifiée avec succès.";
                        header("Location: /index.php?page=resources");
                        exit;
                    } catch (\PDOException $e) {
                        $errors[] = "Erreur lors de la mise à jour dans la base de données.";
                        if ($newFileUploaded && $fileData) {
                            @unlink(__DIR__ . '/../uploads/' . $fileData['path']);
                        }
                    }
                }
            }
        }

        require_once __DIR__ . '/../views/resources/edit.php';
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf()) {
                $_SESSION['error'] = "Jeton de sécurité invalide ou expiré.";
            } else {
                $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
                if ($id) {
                    try {
                        $resource = $this->model->getById($id);
                        if ($resource) {
                            $this->model->delete($id);
                            $filePath = __DIR__ . '/../uploads/' . $resource['file_path'];
                            if (file_exists($filePath)) {
                                @unlink($filePath);
                            }
                            $_SESSION['success'] = "La ressource a été supprimée avec succès.";
                        }
                    } catch (\PDOException $e) {
                        $_SESSION['error'] = "Erreur lors de la suppression dans la base de données.";
                    }
                }
            }
        }
        header("Location: /index.php?page=resources");
        exit;
    }

    public function download() {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            http_response_code(404);
            die("Ressource non trouvée.");
        }

        $resource = $this->model->getById($id);
        if (!$resource) {
            http_response_code(404);
            die("Ressource non trouvée.");
        }

        // Prevent path traversal
        $filename = basename($resource['file_path']);
        $filePath = __DIR__ . '/../uploads/' . $filename;

        if (!file_exists($filePath)) {
            http_response_code(404);
            die("Fichier physiquement introuvable.");
        }

        $mime = $resource['file_type'];
        $size = filesize($filePath);

        // Force download with safe filename
        $downloadName = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $resource['title']) . '.' . pathinfo($filename, PATHINFO_EXTENSION);

        header('Content-Description: File Transfer');
        header('Content-Type: ' . $mime);
        header('Content-Disposition: attachment; filename="' . $downloadName . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $size);

        readfile($filePath);
        exit;
    }
}
