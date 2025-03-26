<?php

require '../vendor/autoload.php';
require_once './dao/Database.php';
require_once './dao/ReviewsDAO.php';


Flight::route('GET /reviews/@book_id', function($book_id){
    Flight::json(ReviewsDAO::getAllByBook($book_id));
});


Flight::route('GET /reviews/@id', function($id){
    Flight::json(ReviewsDAO::getById($id));
});


Flight::route('POST /reviews', function(){
    $data = Flight::request()->data->getData();
    ReviewsDAO::create($data['book_id'], $data['user_id'], $data['rating'], $data['comment']);
    Flight::json(["message" => "Review created successfully"]);
});


Flight::route('PUT /reviews/@id', function($id){
    $data = Flight::request()->data->getData();
    ReviewsDAO::update($id, $data['rating'], $data['comment']);
    Flight::json(["message" => "Review updated successfully"]);
});


Flight::route('DELETE /reviews/@id', function($id){
    ReviewsDAO::delete($id);
    Flight::json(["message" => "Review deleted successfully"]);
});

Flight::start();
