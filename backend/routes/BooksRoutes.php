<?php
echo "to be loaded!";
require_once './services/BooksService.php';
echo "to be loaded!";
$bookService = new BooksService();

echo "service loaded!";

/**
 * @OA\Get(
 *     path="/books",
 *     summary="Get all books",
 *     tags={"Books"},
 *     @OA\Response(
 *         response=200,
 *         description="List of books",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Book"))
 *     )
 * )
 */
Flight::route('GET /books', function() use ($bookService){
    Flight::json($bookService->getAllBooks());
});



/**
 * @OA\Get(
 *     path="/books/{id}",
 *     summary="Get book by ID",
 *     tags={"Books"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Book ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Book details",
 *         @OA\JsonContent(ref="#/components/schemas/Book")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Book not found"
 *     )
 * )
 */
Flight::route('GET /books/@id', function($id) use ($bookService){
    try{
        Flight::json($bookService->getBookById($id));
    }catch(Exception $e){
        Flight::json(["error" => $e->getMessage()], 404);
    }
});

/**
 * @OA\Post(
 *     path="/books",
 *     summary="Create a new book",
 *     tags={"Books"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title", "author", "price", "category_id"},
 *             @OA\Property(property="title", type="string"),
 *             @OA\Property(property="author", type="string"),
 *             @OA\Property(property="price", type="number", format="float"),
 *             @OA\Property(property="category_id", type="integer")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Book created successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     )
 * )
 */
Flight::route('POST /books', function() use ($bookService){
    try{
        $data = Flight::request()->data->getData();
        $bookService->createBook($data);
        Flight::json(["message" => "Book created successfully"]);
    }catch(Exception $e){
        Flight::json(["error" => $e->getMessage()], 400);
    }
});

/**
 * @OA\Put(
 *     path="/books/{id}",
 *     summary="Update a book",
 *     tags={"Books"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Book ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title", "author", "price", "category_id"},
 *             @OA\Property(property="title", type="string"),
 *             @OA\Property(property="author", type="string"),
 *             @OA\Property(property="price", type="number", format="float"),
 *             @OA\Property(property="category_id", type="integer")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Book updated successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Book not found"
 *     )
 * )
 */
Flight::route('PUT /books/@id', function($id) use ($bookService){
    try{
        $data = Flight::request()->data->getData();
        $bookService->updateBook($id, $data);
        Flight::json(["message" => "Book updated successfully"]);
    }catch(Exception $e){
        Flight::json(["error" => $e->getMessage()], 400);
    }
});

/**
 * @OA\Delete(
 *     path="/books/{id}",
 *     summary="Delete a book",
 *     tags={"Books"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Book ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Book deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Book not found"
 *     )
 * )
 */
Flight::route('DELETE /books/@id', function($id) use ($bookService){
    try{
        $bookService->deleteBook($id);
        Flight::json(["message" => "Book deleted successfully"]);
    }catch(Exception $e){
        Flight::json(["error" => $e->getMessage()], 400);
    }
});
