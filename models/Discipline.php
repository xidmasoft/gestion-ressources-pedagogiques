<?php
// models/Discipline.php
require_once __DIR__ . '/../config/database.php';

class Discipline {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM disciplines ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM disciplines WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function nameExists($name, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM disciplines WHERE name = :name AND id != :id");
            $stmt->execute(['name' => $name, 'id' => $excludeId]);
        } else {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM disciplines WHERE name = :name");
            $stmt->execute(['name' => $name]);
        }
        return $stmt->fetchColumn() > 0;
    }

    public function create($name, $description) {
        $stmt = $this->pdo->prepare("INSERT INTO disciplines (name, description) VALUES (:name, :description)");
        return $stmt->execute([
            'name' => $name,
            'description' => $description
        ]);
    }

    public function update($id, $name, $description) {
        $stmt = $this->pdo->prepare("UPDATE disciplines SET name = :name, description = :description WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'name' => $name,
            'description' => $description
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM disciplines WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getTotalCount() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM disciplines");
        return (int) $stmt->fetchColumn();
    }
}
