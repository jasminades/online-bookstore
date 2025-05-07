<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . './../dao/AuthDao.php';
require_once './services/AuthService.php';


Flight::group('/auth', function () {
    /**
     * @OA\Post(
     *     path="/auth/register",
     *     summary="Register new user.",
     *     tags={"auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="first_name", type="string", example="John"),
     *             @OA\Property(property="last_name", type="string", example="Doe"),
     *             @OA\Property(property="email", type="string", example="demo@gmail.com"),
     *             @OA\Property(property="password", type="string", example="some_password"),
     *         )
     *     ),
     *     @OA\Response(response=200, description="User registered"),
     *     @OA\Response(response=500, description="Registration error")
     * )
     */
    Flight::route('POST /register', function () {
        $data = Flight::request()->data->getData();
        $response = Flight::auth_service()->register($data);

        if ($response['success']) {
            Flight::json(['message' => 'User registered successfully', 'data' => $response['data']]);
        } else {
            Flight::halt(500, $response['error']);
        }
    });

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     summary="Login user.",
     *     tags={"auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="demo@gmail.com"),
     *             @OA\Property(property="password", type="string", example="some_password")
     *         )
     *     ),
     *     @OA\Response(response=200, description="JWT token returned"),
     *     @OA\Response(response=500, description="Login error")
     * )
     */
    Flight::route('POST /login', function () {
        $data = Flight::request()->data->getData();
        $response = Flight::auth_service()->login($data);

        if ($response['success']) {
            Flight::json(['message' => 'Login successful', 'data' => $response['data']]);
        } else {
            Flight::halt(500, $response['error']);
        }
    });
});
