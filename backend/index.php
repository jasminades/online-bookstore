<?php

require './../vendor/autoload.php';
require './dao/Database.php';

Flight::route('GET /', function() {
    echo "working!";
});

require_once './routes/UsersRoutes.php';
require_once './routes/BooksRoutes.php';
require_once './routes/CategoriesRoutes.php';
require_once './routes/OrdersRoutes.php';
require_once './routes/ReviewsRoutes.php';


Flight::start();