<?php

require_once __DIR__ . "/BaseDao.php";

class CategoriesDAO extends BaseDao {
    public function __construct() {
        parent::__construct("categories"); 
    }

    public function create($name){
        try{
            $stmt = $this->connection->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->execute([$name]);
            return $this->connection->lastInsertId();
        } catch (PDOException $e){
            echo "Error: " . $e->getMessage();
        }
    }

    public function get_all(){
        try{
            $stmt = $this->connection->query("SELECT * FROM categories");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e){
            echo "Error: " . $e->getMessage();
        }
    }

    public function get_by_id($id) {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM categories WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e){
            echo "Error: " . $e->getMessage();
        }
    }

    public function update($id, $name) {
        try {
            $stmt = $this->connection->prepare("UPDATE categories SET name = ? WHERE id = ?");
            $stmt->execute([$name, $id]);
        } catch (PDOException $e){
            echo "Error: " . $e->getMessage();
        }
    }

    public function deleteCategory($id) {
        return $this->delete($id); 
    }
}

