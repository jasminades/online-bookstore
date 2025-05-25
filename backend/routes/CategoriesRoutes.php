<?php

require_once './services/CategoriesService.php';

$categoriesService = new CategoriesService();

/**
 * @OA\Get(
 *     path="/categories",
 *     summary="Get all categories",
 *     tags={"Categories"},
 *     @OA\Response(
 *         response=200,
 *         description="List of categories",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Category"))
 *     )
 * )
 */
Flight::route('GET /categories', function() use ($categoriesService){
    try{
        $categories = $categoriesService->getAllCategories();
        Flight::json($categories);
    }catch(Exception $e){
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Get(
 *     path="/categories/{id}",
 *     summary="Get category by ID",
 *     tags={"Categories"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Category ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Category details",
 *         @OA\JsonContent(ref="#/components/schemas/Category")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Category not found"
 *     )
 * )
 */
Flight::route('GET /categories/@id', function($id) use ($categoriesService){
    $auth = new AuthMiddleware();
    $headers = getallheaders();
    $token = str_replace('Bearer ', '', $headers['Authorization'] ?? '');

    $auth->verifyToken($token); 
    $auth->authorizeRole('admin');

    try{
        $category = $categoriesService->getCategoryById($id);
        Flight::json($category);
    }catch (Exception $e){
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Post(
 *     path="/categories",
 *     summary="Create a new category",
 *     tags={"Categories"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name"},
 *             @OA\Property(property="name", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Category created successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     )
 * )
 */
Flight::route('POST /categories', function() use ($categoriesService) {
    $auth = new AuthMiddleware();
    $headers = getallheaders();
    $token = str_replace('Bearer ', '', $headers['Authorization'] ?? '');

    $auth->verifyToken($token); 
    $auth->authorizeRole('admin');

    try {
        $data = Flight::request()->data->getData();
        $categoryId = $categoriesService->createCategory($data);
        Flight::json(["message" => "Category created successfully", "category_id" => $categoryId]);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Put(
 *     path="/categories/{id}",
 *     summary="Update a category",
 *     tags={"Categories"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Category ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name"},
 *             @OA\Property(property="name", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Category updated successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Category not found"
 *     )
 * )
 */
Flight::route('PUT /categories/@id', function($id) use ($categoriesService) {
    $auth = new AuthMiddleware();
    $headers = getallheaders();
    $token = str_replace('Bearer ', '', $headers['Authorization'] ?? '');

    $auth->verifyToken($token); 
    $auth->authorizeRole('admin');

    try {
        $data = Flight::request()->data->getData();
        $categoriesService->updateCategory($id, $data);
        Flight::json(["message" => "Category updated successfully"]);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Delete(
 *     path="/categories/{id}",
 *     summary="Delete a category",
 *     tags={"Categories"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Category ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Category deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Category not found"
 *     )
 * )
 */

Flight::route('DELETE /categories/@id', function($id) use ($categoriesService) {
    $auth = new AuthMiddleware();
    $headers = getallheaders();
    $token = str_replace('Bearer ', '', $headers['Authorization'] ?? '');

    $auth->verifyToken($token); 
    $auth->authorizeRole('admin');
    
    try {
        $categoriesService->deleteCategory($id);
        Flight::json(["message" => "Category deleted successfully"]);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});
