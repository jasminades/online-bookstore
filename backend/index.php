<?php


require './../vendor/autoload.php';
echo " vendor loaded!";
require './dao/Database.php';
echo " database loaded!";

require_once './routes/UsersRoutes.php';
echo " users loaded!";
require_once './routes/BooksRoutes.php';
echo " books loaded!";
require_once './routes/CategoriesRoutes.php';
echo " routes loaded!";
require_once './routes/OrdersRoutes.php';
echo " orders loaded!";
require_once './routes/ReviewsRoutes.php';
echo "reviews loaded!";

Flight::route('GET /test', function() {
    echo "Test route is working!";
});

echo "BooksRoutes.php loaded!";

Flight::set('flight.debug', true);


Flight::set('flight.views.path', '../../frontend/views');

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo $_SERVER['REQUEST_URI']; // Log the requested URL
//exit; // Stop execution temporarily to inspect


Flight::start();