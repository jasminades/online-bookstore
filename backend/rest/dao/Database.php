<?php

require_once __DIR__ . './../../config.php';

class Database {
    private static $db;

    public static function getConnection() {
        if (!self::$db) {
            try {
                self::$db = new PDO('mysql:host=localhost;dbname=bookstore;charset=utf8', 'root', 'root'); 
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return self::$db;
    }
}

Database::getConnection();
