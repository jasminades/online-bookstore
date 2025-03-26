<?php

class BooksDao{
    
    public static function create($title, $author, $price, $category_id){
        try{
            $conn = Database::getConnection();
            $stmt = $conn->prepare("INSERT INTO Books (title, author, price, category_id, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$title, $author, $price, $category_id]);
        }catch (PDOException $e){
            echo "Error: " . $e->getMessage();
        }
    }


    public static function getAll(){
        try{
            $conn = Database::getConnection();
            $stmt = $conn->query("SELECT * FROM Books");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e){
            echo "Error: " . $e->getMessage();
        }
    }


    public static function getById($id){
        try{
            $conn = Database::getConnection();
            $stmt = $conn->prepare("SELECT * FROM Books WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }catch (PDOException $e){
            echo "Error: " . $e->getMessage();
        }
    }


    public static function update($id, $title, $author, $price, $category_id){
        try{
            $conn = Database::getConnection();
            $stmt = $conn->prepare("UPDATE Books SET title = ?, author = ?, price = ?, category_id = ? WHERE id = ?");
            $stmt->execute([$title, $author, $price, $category_id, $id]);
        }catch (PDOException $e){
            echo "Error: " . $e->getMessage();
        }
    }


    public static function delete($id){
        try{
            $conn = Database::getConnection();
            $stmt = $conn->prepare("DELETE FROM Books WHERE id = ?");
            $stmt->execute([$id]);
        }catch (PDOException $e){
            echo "Error: " . $e->getMessage();
        }
    }
}