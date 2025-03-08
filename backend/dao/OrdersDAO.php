<?php

class OrdersDAO {

    public static function create($user_id, $total_price, $status = 'pending') {
        try {
            $conn = Database::getConnection();  
            $stmt = $conn->prepare("INSERT INTO Orders (user_id, total_price, status, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$user_id, $total_price, $status]);
            return $conn->lastInsertId();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo "Error: Duplicate entry.";
            } else {
                echo "Error: " . $e->getMessage();
            }
        }
    }

    public static function getAll() {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->query("SELECT * FROM Orders ORDER BY created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    public static function getAllByUser($user_id) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("SELECT * FROM Orders WHERE user_id = ? ORDER BY created_at DESC");
            $stmt->execute([$user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public static function getById($id) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("SELECT * FROM Orders WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public static function update($id, $total_price, $status) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("UPDATE Orders SET total_price = ?, status = ? WHERE id = ?");
            $stmt->execute([$total_price, $status, $id]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public static function updatePartial($id, $fields) {
        try {
            $conn = Database::getConnection();
            $columns = array_keys($fields);
            $values = array_values($fields);

            $setClause = implode(", ", array_map(fn($col) => "$col = ?", $columns));
            $stmt = $conn->prepare("UPDATE Orders SET $setClause WHERE id = ?");
            $values[] = $id;
            $stmt->execute($values);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public static function delete($id) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("DELETE FROM Orders WHERE id = ?");
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
