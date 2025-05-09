<?php

require_once './dao/UsersDAO.php';
require_once './services/BaseService.php'; 

class UsersService extends BaseService {

    public function __construct() {
        $usersDao = new UsersDAO(); 
        parent::__construct($usersDao);
    }

    public function createUser($data) {
        $this->validateUserData($data);
    
        $role = isset($data['role']) ? $data['role'] : 'customer';
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
    
        $entity = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $hashedPassword,
            'role' => $role,
            'created_at' => date('Y-m-d H:i:s')
        ];
    
        return $this->add($entity);
    }
    
    public function updateUser($id, $data) {
        $this->validateUserData($data);
    
        $entity = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role']
        ];
    
        $this->update($entity, $id); 
        
        return ["message" => "User updated successfully"];
    }
    

    public function deleteUser($id) {
        $this->delete($id);
        return ["message" => "User deleted successfully"];
    }


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
?>
