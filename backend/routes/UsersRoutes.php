<?php

require_once './services/UsersService.php';

$usersService = new UsersService();
$auth_middleware = Flight::get('auth_middleware');


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

Flight::route('GET /users', function() {
    $auth = new AuthMiddleware();
    $headers = getallheaders();
    $token = str_replace('Bearer ', '', $headers['Authorization'] ?? '');

    $auth->verifyToken($token); 
    $auth->authorizeRole('admin'); 

    Flight::json(Flight::usersService()->get_all());
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

Flight::route('GET /users/@id', function($id) {
     $auth = new AuthMiddleware();
    $headers = getallheaders();
    $token = str_replace('Bearer ', '', $headers['Authorization'] ?? '');

    $auth->verifyToken($token); 
    $auth->authorizeRole('admin'); 
    Flight::json(Flight::usersService()->get_by_id($id));
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

 Flight::route('POST /users', function() use ($usersService){
     $auth = new AuthMiddleware();
    $headers = getallheaders();
    $token = str_replace('Bearer ', '', $headers['Authorization'] ?? '');

    $auth->verifyToken($token); 
    $auth->authorizeRole('admin'); 
    $data = Flight::request()->data->getData();
    Flight::json($usersService->add($data));
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
     $auth = new AuthMiddleware();
    $headers = getallheaders();
    $token = str_replace('Bearer ', '', $headers['Authorization'] ?? '');

    $auth->verifyToken($token); 
    $auth->authorizeRole('admin'); 

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
    $auth = new AuthMiddleware();
    $headers = getallheaders();
    $token = str_replace('Bearer ', '', $headers['Authorization'] ?? '');

    $auth->verifyToken($token); 
    $auth->authorizeRole('admin'); 

    Flight::json($usersService->deleteUser($id));
});

