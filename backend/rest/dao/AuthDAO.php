<?php

class AuthDao {
    private $conn;
    private $table = "users";

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function get_user_by_email($email) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }

    public function create_user($user) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (first_name, last_name, email, password, role) 
                                      VALUES (:first_name, :last_name, :email, :password, :role)");
    

        $stmt->bindParam(':first_name', $user['first_name']);
        $stmt->bindParam(':last_name', $user['last_name']);
        $stmt->bindParam(':email', $user['email']);
        $stmt->bindParam(':password', $user['password']);
        $stmt->bindParam(':role', $user['role']);
    
        if ($stmt->execute()) {
            $user['id'] = $this->conn->lastInsertId();
            unset($user['password']); 
            return $user;
        }
    
        return false;
    }    
    
}
