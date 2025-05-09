<?php

require_once './dao/UsersDAO.php';

class UsersService {

    private $usersDao;

    public function __construct() {
        $this->usersDao = new UsersDAO(); 
    }

    public function getAllUsers() {
        return $this->usersDao->getAll(); 
    }

    public function getUserById($id) {
        return $this->usersDao->getById($id); 
    }

    public function createUser($data) {
        $this->validateUserData($data);

        $role = isset($data['role']) ? $data['role'] : 'customer';
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        $this->usersDao->create($data['name'], $data['email'], $hashedPassword, $role); 
        return ["message" => "User created successfully"];
    }

    public function updateUser($id, $data) {
        $this->validateUserData($data);
        
        $this->usersDao->update($id, $data['name'], $data['email'], $data['role']); 
        return ["message" => "User updated successfully"];
    }

    public function deleteUser($id) {
        $this->usersDao->delete($id);
        return ["message" => "User deleted successfully"];
    }

    // validation
    private function validateUserData($data){
        $errors = [];

        if (!isset($data['name']) || strlen(trim($data['name'])) < 3) {
            $errors[] = "Name must be at least 3 characters long.";
        }

        if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        if (!isset($data['password']) || strlen($data['password']) < 8 || !preg_match('/[A-Za-z]/', $data['password']) || !preg_match('/\d/', $data['password'])) {
            $errors[] = "Password must be at least 8 characters long and include letters and numbers.";
        }

        if (isset($data['role']) && !in_array($data['role'], ['customer', 'admin'])) {
            $errors[] = "Invalid role. Allowed values: 'customer', 'admin'.";
        }

        if (!empty($errors)) {
            throw new Exception(implode(" ", $errors));
        }
    }
}
