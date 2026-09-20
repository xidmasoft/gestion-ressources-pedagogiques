<?php
// models/Resource.php
require_once __DIR__ . '/../config/database.php';

class Resource {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
    }

    public function getAllWithDetails() {
        $sql = "SELECT r.*, t.name as theme_name, d.name as discipline_name
                FROM resources r
                JOIN themes t ON r.theme_id = t.id
                JOIN disciplines d ON t.discipline_id = d.id
                ORDER BY d.name ASC, t.name ASC, r.title ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM resources WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function titleExistsInTheme($title, $themeId, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM resources WHERE title = :title AND theme_id = :theme_id AND id != :id");
            $stmt->execute(['title' => $title, 'theme_id' => $themeId, 'id' => $excludeId]);
        } else {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM resources WHERE title = :title AND theme_id = :theme_id");
            $stmt->execute(['title' => $title, 'theme_id' => $themeId]);
        }
        return $stmt->fetchColumn() > 0;
    }

    public function create($themeId, $title, $description, $filePath, $fileType, $fileSize) {
        $stmt = $this->pdo->prepare("INSERT INTO resources (theme_id, title, description, file_path, file_type, file_size) VALUES (:theme_id, :title, :description, :file_path, :file_type, :file_size)");
        return $stmt->execute([
            'theme_id' => $themeId,
            'title' => $title,
            'description' => $description,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'file_size' => $fileSize
        ]);
    }

    public function update($id, $themeId, $title, $description, $filePath, $fileType, $fileSize) {
        $stmt = $this->pdo->prepare("UPDATE resources SET theme_id = :theme_id, title = :title, description = :description, file_path = :file_path, file_type = :file_type, file_size = :file_size WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'theme_id' => $themeId,
            'title' => $title,
            'description' => $description,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'file_size' => $fileSize
        ]);
    }

    public function updateMetadata($id, $themeId, $title, $description) {
        $stmt = $this->pdo->prepare("UPDATE resources SET theme_id = :theme_id, title = :title, description = :description WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'theme_id' => $themeId,
            'title' => $title,
            'description' => $description
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM resources WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
