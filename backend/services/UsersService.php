<?php

require_once './dao/UsersDAO.php';

class UsersService {

    public function getAllUsers() {
        return UsersDAO::getAll();
    }

    public function getUserById($id) {
        return UsersDAO::getById($id);
    }

    
    public function createUser($data) {
        $this->validateUserData($data);

        $role = isset($data['role']) ? $data['role'] : 'customer';
        UsersDAO::create($data['name'], $data['email'], $data['password'], $role);
        return ["message" => "User created successfully"];
    }

    public function updateUser($id, $data) {
        $this->validateUserData($data);
        
        UsersDAO::update($id, $data['name'], $data['email'], $data['role']);
        return ["message" => "User updated successfully"];
    }

    
    public function deleteUser($id) {
        UsersDAO::delete($id);
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
