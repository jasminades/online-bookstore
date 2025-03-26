<?php

require '../vendor/autoload.php';
require_once './dao/Database.php';
require_once './dao/OrdersDAO.php';


Flight::route('GET /orders/user/@user_id', function($user_id){
    Flight::json(OrdersDAO::getAllByUser($user_id));
});


Flight::route('GET /orders/@id', function($id){
    Flight::json(OrdersDAO::getById($id));
});


Flight::route('POST /orders', function(){
    $data = Flight::request()->data->getData();

    if (!isset($data['user_id'], $data['total_price']) || empty($data['user_id']) || empty($data['total_price'])) {
        Flight::json(["error" => "User ID and Total Price are required"], 400);
        return;
    }

    $order_id = OrdersDAO::create($data['user_id'], $data['total_price'], $data['status'] ?? 'pending');
    Flight::json(["message" => "Order created successfully", "order_id" => $order_id]);
});


Flight::route('PUT /orders/@id', function($id){
    $data = Flight::request()->data->getData();

    if (!isset($data['total_price'], $data['status']) || empty($data['total_price']) || empty($data['status'])) {
        Flight::json(["error" => "Total Price and Status are required"], 400);
        return;
    }

    $updated = OrdersDAO::update($id, $data['total_price'], $data['status']);

    if ($updated) {
        Flight::json(["message" => "Order updated successfully"]);
    } else {
        Flight::json(["error" => "Order not found"], 404);
    }
});


Flight::route('DELETE /orders/@id', function($id){
    $deleted = OrdersDAO::delete($id);

    if ($deleted) {
        Flight::json(["message" => "Order deleted successfully"]);
    } else {
        Flight::json(["error" => "Order not found"], 404);
    }
});

Flight::start();
