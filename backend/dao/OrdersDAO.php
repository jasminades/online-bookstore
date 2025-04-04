<?php

class OrdersDAO {
    public static function create($user_id, $total_price, $status = 'pending') {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("INSERT INTO Orders (user_id, total_price, status, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$user_id, $total_price, $status]);
            return $conn->lastInsertId();  
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    

    public static function getAllByUser($user_id) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("SELECT * FROM Orders WHERE user_id = ? ORDER BY created_at DESC");
            $stmt->execute([$user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }


    public static function getById($id) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("SELECT * FROM Orders WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }


    public static function update($id, $total_price, $status) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("UPDATE Orders SET total_price = ?, status = ? WHERE id = ?");
            $stmt->execute([$total_price, $status, $id]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }


    public static function delete($id) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("DELETE FROM Orders WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
}
?>
