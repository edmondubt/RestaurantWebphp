<?php
require_once 'db.php';

class User {

    private $conn;
    private $table = 'users';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }


    public function register($username, $email, $password, $role = 'user') {

       
        $check = $this->conn->prepare(
            "SELECT id FROM {$this->table} WHERE username = :username OR email = :email"
        );
        $check->execute([
            ':username' => $username,
            ':email' => $email
        ]);

        if ($check->rowCount() > 0) {
            return 'exists';
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO {$this->table} (username, email, password, role)
                VALUES (:username, :email, :password, :role)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $password,
            ':role' => $role
        ]);
    }

 
    public function login($username, $password) {

        $sql = "SELECT * FROM {$this->table} WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':username' => $username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $password === $user['password']) {
        return $user;
        }

        return false;
    }
}
