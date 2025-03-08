<?php

class CategoriesDAO{
    public static function create($name){
        try{
            $conn = Database::getConnection();
            $stmt = $conn->prepare("INSERT INTO Categories (name) VALUES (?)");
            $stmt->execute([$name]);
            return $conn->lastInsertId();
        }catch (PDOException $e){
            echo "Error: " . $e->getMessage();
        }
    }


    public static function getAll(){
        try{
            $conn = Database::getConnection();
            $stmt = $conn->query("SELECT * FROM Categories");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException $e){
            echo "Error: " . $e->getMessage();
        }
    }

    
    public static function getById($id) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("SELECT * FROM Categories WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }


    public static function update($id, $name) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("UPDATE Categories SET name = ? WHERE id = ?");
            $stmt->execute([$name, $id]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }


    public static function delete($id) {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("DELETE FROM Categories WHERE id = ?");
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}