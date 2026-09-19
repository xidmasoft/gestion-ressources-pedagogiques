<?php
// models/Theme.php
require_once __DIR__ . '/../config/database.php';

class Theme {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
    }

    public function getAllWithDiscipline() {
        $sql = "SELECT t.*, d.name as discipline_name
                FROM themes t
                JOIN disciplines d ON t.discipline_id = d.id
                ORDER BY d.name ASC, t.name ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM themes WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function nameExistsInDiscipline($name, $disciplineId, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM themes WHERE name = :name AND discipline_id = :discipline_id AND id != :id");
            $stmt->execute(['name' => $name, 'discipline_id' => $disciplineId, 'id' => $excludeId]);
        } else {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM themes WHERE name = :name AND discipline_id = :discipline_id");
            $stmt->execute(['name' => $name, 'discipline_id' => $disciplineId]);
        }
        return $stmt->fetchColumn() > 0;
    }

    public function create($disciplineId, $name, $description) {
        $stmt = $this->pdo->prepare("INSERT INTO themes (discipline_id, name, description) VALUES (:discipline_id, :name, :description)");
        return $stmt->execute([
            'discipline_id' => $disciplineId,
            'name' => $name,
            'description' => $description
        ]);
    }

    public function update($id, $disciplineId, $name, $description) {
        $stmt = $this->pdo->prepare("UPDATE themes SET discipline_id = :discipline_id, name = :name, description = :description WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'discipline_id' => $disciplineId,
            'name' => $name,
            'description' => $description
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM themes WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
