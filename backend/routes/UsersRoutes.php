<?php

require_once './services/UsersService.php';

$usersService = new UsersService();

/**
 * @OA\Get(
 *     path="/users",
 *     summary="Get all users",
 *     tags={"Users"},
 *     @OA\Response(
 *         response=200,
 *         description="List of all users",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/User"))
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Error fetching users"
 *     )
 * )
 */
Flight::route('GET /users', function() use ($usersService) {
    Flight::json($usersService->getAllUsers());
});

/**
 * @OA\Get(
 *     path="/users/{id}",
 *     summary="Get a user by ID",
 *     tags={"Users"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="User ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User details",
 *         @OA\JsonContent(ref="#/components/schemas/User")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     )
 * )
 */
Flight::route('GET /users/@id', function($id) use ($usersService) {
    Flight::json($usersService->getUserById($id));
});

/**
 * @OA\Post(
 *     path="/users",
 *     summary="Create a new user",
 *     tags={"Users"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email", "password"},
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="email", type="string"),
 *             @OA\Property(property="password", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="User created successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     )
 * )
 */
Flight::route('POST /users', function() use ($usersService) {
    $data = Flight::request()->data->getData();
    try {
        Flight::json($usersService->createUser($data));
    } catch (Exception $e) {
        Flight::json(["error" => $e->getMessage()], 400);
    }
});

/**
 * @OA\Put(
 *     path="/users/{id}",
 *     summary="Update an existing user",
 *     tags={"Users"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="User ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email", "password"},
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="email", type="string"),
 *             @OA\Property(property="password", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User updated successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     )
 * )
 */
Flight::route('PUT /users/@id', function($id) use ($usersService) {
    $data = Flight::request()->data->getData();
    try {
        Flight::json($usersService->updateUser($id, $data));
    } catch (Exception $e) {
        Flight::json(["error" => $e->getMessage()], 400);
    }
});

/**
 * @OA\Delete(
 *     path="/users/{id}",
 *     summary="Delete a user by ID",
 *     tags={"Users"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="User ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     )
 * )
 */
Flight::route('DELETE /users/@id', function($id) use ($usersService) {
    Flight::json($usersService->deleteUser($id));
});

