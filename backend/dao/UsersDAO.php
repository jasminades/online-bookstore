<?php

require_once __DIR__ . "/BaseDao.php";

class UsersDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("users");
    }

    public function add($name, $email, $password, $role = 'customer')
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        try {
            return $this->insert(
                ["name", "email", "password", "role", "created_at"],
                [$name, $email, $hashedPassword, $role, date('Y-m-d H:i:s')]
            );
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo "Error: Email already exists.";
            } else {
                echo "Error: " . $e->getMessage();
            }
        }
    }

    public function get_all() {
        return $this->query("SELECT * FROM users", []);
    }
    

    public function get_by_id($id)
    {
        return $this->query_unique("SELECT * FROM users WHERE id = :id", ['id' => $id]);
    }

    public function update($id, $name, $email, $role)
    {
        return $this->execute(
            "UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id",
            ['name' => $name, 'email' => $email, 'role' => $role, 'id' => $id]
        );
    }


    public function deleteUser($id)
    {
        return $this->delete($id);
    }
}
