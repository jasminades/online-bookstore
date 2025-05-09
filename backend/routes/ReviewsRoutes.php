<?php

require_once './services/ReviewsService.php';

$reviewsService = new ReviewsService();

Flight::route('GET /reviews', function() {
    echo "Reviews route is working!";
});


/**
 * @OA\Get(
 *     path="/reviews/{book_id}",
 *     summary="Get all reviews for a book",
 *     tags={"Reviews"},
 *     @OA\Parameter(
 *         name="book_id",
 *         in="path",
 *         description="Book ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of reviews for the book",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Review"))
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Error fetching reviews"
 *     )
 * )
 */
Flight::route('GET /reviews/@book_id', function($book_id) use ($reviewsService) {
    try {
        Flight::json($reviewsService->getAllByBook($book_id));
    } catch (Exception $e) {
        Flight::json(["error" => $e->getMessage()], 400);
    }
});

/**
 * @OA\Get(
 *     path="/reviews/{id}",
 *     summary="Get a review by ID",
 *     tags={"Reviews"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Review ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review details",
 *         @OA\JsonContent(ref="#/components/schemas/Review")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Review not found"
 *     )
 * )
 */
Flight::route('GET /reviews/@id', function($id) use ($reviewsService) {
    try {
        Flight::json($reviewsService->get_by_id($id));
    } catch (Exception $e) {
        Flight::json(["error" => $e->getMessage()], 400);
    }
});

/**
 * @OA\Post(
 *     path="/reviews",
 *     summary="Create a new review",
 *     tags={"Reviews"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"book_id", "user_id", "content", "rating"},
 *             @OA\Property(property="book_id", type="integer"),
 *             @OA\Property(property="user_id", type="integer"),
 *             @OA\Property(property="content", type="string"),
 *             @OA\Property(property="rating", type="integer")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Review created successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     )
 * )
 */
Flight::route('POST /reviews', function() use ($reviewsService) {
    try {
        $data = Flight::request()->data->getData();
        $review_id = $reviewsService->createReview($data);
        Flight::json(["message" => "Review created successfully", "review_id" => $review_id]);
    } catch (Exception $e) {
        Flight::json(["error" => $e->getMessage()], 400);
    }
});

/**
 * @OA\Put(
 *     path="/reviews/{id}",
 *     summary="Update an existing review",
 *     tags={"Reviews"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Review ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"content", "rating"},
 *             @OA\Property(property="content", type="string"),
 *             @OA\Property(property="rating", type="integer")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review updated successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Review not found"
 *     )
 * )
 */
Flight::route('PUT /reviews/@id', function($id) use ($reviewsService) {
    try {
        $data = Flight::request()->data->getData();
        Flight::json($reviewsService->updateReview($id, $data));
    } catch (Exception $e) {
        Flight::json(["error" => $e->getMessage()], 400);
    }
});

/**
 * @OA\Delete(
 *     path="/reviews/{id}",
 *     summary="Delete a review by ID",
 *     tags={"Reviews"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Review ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Review not found"
 *     )
 * )
 */
Flight::route('DELETE /reviews/@id', function($id) use ($reviewsService) {
    try {
        Flight::json($reviewsService->deleteReview($id));
    } catch (Exception $e) {
        Flight::json(["error" => $e->getMessage()], 400);
    }
});

