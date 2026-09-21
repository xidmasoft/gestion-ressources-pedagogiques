<?php
// models/User.php
require_once __DIR__ . '/../config/database.php';

class User {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
    }

    public function getByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function emailExists($email, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email AND id != :id");
            $stmt->execute(['email' => $email, 'id' => $excludeId]);
        } else {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
        }
        return $stmt->fetchColumn() > 0;
    }

    public function create($name, $email, $password, $role) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (:name, :email, :hash, :role)");
        return $stmt->execute([
            'name' => $name,
            'email' => $email,
            'hash' => $hash,
            'role' => $role
        ]);
    }

    public function update($id, $name, $email, $role) {
        $stmt = $this->pdo->prepare("UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'role' => $role
        ]);
    }

    public function updatePassword($id, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE users SET password_hash = :hash WHERE id = :id");
        return $stmt->execute(['id' => $id, 'hash' => $hash]);
    }

    public function setStatus($id, $status) {
        $stmt = $this->pdo->prepare("UPDATE users SET status = :status WHERE id = :id");
        return $stmt->execute(['id' => $id, 'status' => $status]);
    }

    // --- Brute-force protection methods ---
    public function logFailedAttempt($email) {
        $stmt = $this->pdo->prepare("INSERT INTO login_attempts (email) VALUES (:email)");
        $stmt->execute(['email' => $email]);
    }

    public function getRecentFailedAttempts($email, $minutes = 5) {
        // Compatibilité MySQL et SQLite pour les tests
        $driver = $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        if ($driver === 'sqlite') {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM login_attempts WHERE email = :email AND attempt_time > datetime('now', '-' || :minutes || ' minutes')");
        } else {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM login_attempts WHERE email = :email AND attempt_time > DATE_SUB(NOW(), INTERVAL :minutes MINUTE)");
        }
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':minutes', (int) $minutes, PDO::PARAM_INT);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function clearFailedAttempts($email) {
        $stmt = $this->pdo->prepare("DELETE FROM login_attempts WHERE email = :email");
        $stmt->execute(['email' => $email]);
    }
}
