<?php

require '../vendor/autoload.php';
require_once './dao/Database.php';
require './dao/UsersDAO.php';


Flight::route('GET /users', function(){
    Flight::json(UsersDAO::getAll()); // Fixed method name
});


Flight::route('GET /users/@id', function($id){
    Flight::json(UsersDAO::getById($id));
});


Flight::route('POST /users', function() {
    $data = Flight::request()->data->getData();
    $role = isset($data['role']) ? $data['role'] : 'customer'; // Default role
    UsersDAO::create($data['name'], $data['email'], $data['password'], $role);
    Flight::json(["message" => "User created successfully"]);
});


Flight::route('PUT /users/@id', function($id) {
    $data = Flight::request()->data->getData();
    UsersDAO::update($id, $data['name'], $data['email'], $data['role']);
    Flight::json(["message" => "User updated successfully"]);
});


Flight::route('DELETE /users/@id', function($id) {
    UsersDAO::delete($id);
    Flight::json(["message" => "User deleted successfully"]);
});


Flight::start();
