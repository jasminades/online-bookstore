<?php

class ReviewsDAO {
    public static function create($book_id, $user_id, $rating, $comment) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("INSERT INTO Reviews (book_id, user_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$book_id, $user_id, $rating, $comment]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public static function getAllByBook($book_id) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("SELECT * FROM Reviews WHERE book_id = ?");
            $stmt->execute([$book_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public static function getById($id) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("SELECT * FROM Reviews WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public static function update($id, $rating, $comment) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("UPDATE Reviews SET rating = ?, comment = ? WHERE id = ?");
            $stmt->execute([$rating, $comment, $id]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public static function delete($id) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("DELETE FROM Reviews WHERE id = ?");
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

?>
