<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService {
    private $auth_dao;

    public function __construct() {
        $this->auth_dao = new AuthDao();
    }

    public function register($data) {
        if (empty($data['email']) || empty($data['password'])) {
            return ['success' => false, 'error' => 'Email and password are required.'];
        }

        if ($this->auth_dao->get_user_by_email($data['email'])) {
            return ['success' => false, 'error' => 'Email already registered.'];
        }

        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['role'] = $data['role'] ?? 'customer'; 
        $data['name'] = $data['name'] ?? 'New User';

        $newUser = $this->auth_dao->create_user($data);

        if (!$newUser) {
            return ['success' => false, 'error' => 'User creation failed.'];
        }

        unset($data['password']);
        return ['success' => true, 'data' => $data];
    }

    public function login($data) {
        if (empty($data['email']) || empty($data['password'])) {
            return ['success' => false, 'error' => 'Email and password are required.'];
        }

        $user = $this->auth_dao->get_user_by_email($data['email']);
        if (!$user || !password_verify($data['password'], $user['password'])) {
            return ['success' => false, 'error' => 'Invalid email or password.'];
        }

        unset($user['password']);

        $payload = [
            'user' => $user,
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24)
        ];

        $token = JWT::encode($payload, Config::JWT_SECRET(), 'HS256');


        return ['success' => true, 'data' => array_merge($user, ['token' => $token])];
    }
}
