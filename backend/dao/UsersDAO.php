<?php

class UsersDAO {
    public static function create($name, $email, $password, $role = 'customer') {
        try {
            $conn = Database::getConnection();
            
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
            
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt->execute([$name, $email, $hashedPassword, $role]);
            
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo "Error: Email already exists.";
            } else {
                echo "Error: " . $e->getMessage();
            }
        }
    }

    public static function getAll() { 
        try {
            $conn = Database::getConnection();
            $stmt = $conn->query("SELECT * FROM Users");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public static function getById($id) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("SELECT * FROM Users WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public static function update($id, $name, $email, $role) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("UPDATE Users SET name = ?, email = ?, role = ? WHERE id = ?");
            $stmt->execute([$name, $email, $role, $id]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public static function updatePartial($id, $fields){
        try {
            $conn = Database::getConnection();
            $columns = array_keys($fields);
            $values = array_values($fields);

            $setClause = implode(", ", array_map(fn($col) => "$col = ?", $columns));
            $stmt = $conn->prepare("UPDATE Users SET $setClause WHERE id = ?");
            $values[] = $id;
            $stmt->execute($values);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public static function delete($id) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("DELETE FROM Users WHERE id = ?");
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

?>
