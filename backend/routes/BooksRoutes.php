<?php

require '../vendor/autoload.php';
require_once './dao/Database.php';
require './dao/BooksDAO.php';


Flight::route('GET /books', function(){
    Flight::json(BooksDAO::getAll());
});


Flight::route('GET /books/@id', function($id){
    Flight::json(BooksDAO::getById($id));
});


Flight::route('POST /books', function() {
    $data = Flight::request()->data->getData();
    BooksDAO::create($data['title'], $data['author'], $data['price'], $data['category_id']);
    Flight::json(["message" => "Book created successfully"]);
});


Flight::route('PUT /books/@id', function($id) {
    $data = Flight::request()->data->getData();
    BooksDAO::update($id, $data['title'], $data['author'], $data['price'], $data['category_id']);
    Flight::json(["message" => "Book updated successfully"]);
});



Flight::route('DELETE /books/@id', function($id) {
    BooksDAO::delete($id);
    Flight::json(["message" => "Book deleted successfully"]);
});


Flight::start();