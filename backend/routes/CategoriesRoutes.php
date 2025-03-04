<?php

require '../vendor/autoload.php';
require_once './dao/Database.php';
require './dao/CategoriesDAO.php';

Flight::route('GET /categories', function(){
    Flight::json(CategoriesDAO::getAll());
});


Flight::route('GET /categories/@id', function($id){
    Flight::json(CategoriesDAO::getById($id));
});


Flight::route('POST /categories', function(){
    $data = Flight::request()->data->getData();
    CategoriesDAO::create($data['name']);
    Flight::json(["message" => "Category created successfully"]);
});


Flight::route('PUT /categories/@id', function($id){
    $data = Flight::request()->data->getData();
    CategoriesDAO::update($id, $data['name']);
    Flight::json(["message" => "Category updated successfully"]);
});


Flight::route('DELETE /categories/@id', function($id){
    CategoriesDAO::delete($id);
    Flight::json(["message"=>"Category deleted successfully"]);
});


Flight::start();