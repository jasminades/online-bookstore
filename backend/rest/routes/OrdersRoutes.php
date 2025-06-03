<?php

require_once './rest/services/OrdersService.php';

$ordersService = new OrdersService();

/**
 * @OA\Get(
 *     path="/orders",
 *     summary="Get all orders",
 *     tags={"Orders"},
 *     @OA\Response(
 *         response=200,
 *         description="List of orders",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Order"))
 *     )
 * )
 */
Flight::route('GET /orders', function() use ($ordersService) {
    try {
        $orders = $ordersService->getAllOrders(); 
        Flight::json($orders);
    } catch (Exception $e) {
        Flight::json(["error" => $e->getMessage()], 400);
    }
});

/**
 * @OA\Get(
 *     path="/orders/user/{user_id}",
 *     summary="Get orders by user ID",
 *     tags={"Orders"},
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         description="User ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of orders for a user",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Order"))
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid user ID"
 *     )
 * )
 */
Flight::route('GET /orders/user/@user_id', function($user_id) use ($ordersService) {
    try {
        $orders = $ordersService->getOrdersByUser($user_id);
        Flight::json($orders);
    } catch (Exception $e) {
        Flight::json(["error" => $e->getMessage()], 400);
    }
});

/**
 * @OA\Get(
 *     path="/orders/{id}",
 *     summary="Get order by ID",
 *     tags={"Orders"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Order ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order details",
 *         @OA\JsonContent(ref="#/components/schemas/Order")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Order not found"
 *     )
 * )
 */
Flight::route('GET /orders/@id', function($id) use ($ordersService) {
    try {
        $order = $ordersService->getOrderById($id);
        Flight::json($order);
    } catch (Exception $e) {
        Flight::json(["error" => $e->getMessage()], 400);
    }
});

/**
 * @OA\Post(
 *     path="/orders",
 *     summary="Create a new order",
 *     tags={"Orders"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "total_amount"},
 *             @OA\Property(property="user_id", type="integer"),
 *             @OA\Property(property="total_amount", type="number", format="float")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Order created successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     )
 * )
 */
Flight::route('POST /orders', function() use ($ordersService) {
    try {
        $data = Flight::request()->data->getData();
        $order_id = $ordersService->createOrder($data);
        Flight::json(["message" => "Order created successfully", "order_id" => $order_id]);
    } catch (Exception $e) {
        Flight::json(["error" => $e->getMessage()], 400);
    }
});

/**
 * @OA\Put(
 *     path="/orders/{id}",
 *     summary="Update an order",
 *     tags={"Orders"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Order ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"id", "user_id", "total_price", "status", "created_at", "book_id"},
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="user_id", type="integer", example=10),
 *             @OA\Property(property="total_price", type="number", format="float", example=99.99),
 *             @OA\Property(property="status", type="integer", example=0),
 *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-30T14:00:00Z"),
 *             @OA\Property(property="book_id", type="integer", example=5)
 *         )
 *     ),
 *     @OA\Response(response=200, description="Order updated successfully"),
 *     @OA\Response(response=400, description="Invalid input"),
 *     @OA\Response(response=404, description="Order not found")
 * )
 */
Flight::route('PUT /orders/@id', function($id) use ($ordersService) {
    try {
        $data = Flight::request()->data->getData(); 
        $ordersService->updateOrder($id, $data);   
        Flight::json(["message" => "Order updated successfully"]);
    } catch (Exception $e) {
        Flight::json(["error" => $e->getMessage()], 400);
    }
});



/**
 * @OA\Delete(
 *     path="/orders/{id}",
 *     summary="Delete an order",
 *     tags={"Orders"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Order ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Order not found"
 *     )
 * )
 */
Flight::route('DELETE /orders/@id', function($id) use ($ordersService) {
    try {
        $ordersService->deleteOrder($id);
        Flight::json(["message" => "Order deleted successfully"]);
    } catch (Exception $e) {
        Flight::json(["error" => $e->getMessage()], 400);
    }
});

