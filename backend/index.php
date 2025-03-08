<?php

require './../vendor/autoload.php';

require './dao/Database.php';

Flight::route('GET /test', function() {
    echo "FlightPHP is working!";
});


require_once './routes/UsersRoutes.php';
require_once './routes/BooksRoutes.php';
require_once './routes/CategoriesRoutes.php';
require_once './routes/OrdersRoutes.php';
require_once './routes/ReviewsRoutes.php';



Flight::set('flight.debug', true);


Flight::set('flight.views.path', '../../frontend/views');

error_reporting(E_ALL);
ini_set('display_errors', 1);


Flight::start();