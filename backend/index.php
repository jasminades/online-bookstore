<?php
/* if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, Authentication");
    exit(0);
}
 */

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, Authentication");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}



require './../vendor/autoload.php';
require './dao/Database.php';
require './data/roles.php';


use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require './routes/AuthRoutes.php';
Flight::register('auth_service', "AuthService");
require_once __DIR__ . '/middleware/AuthMiddleware.php';

Flight::register('auth_middleware', "AuthMiddleware");
Flight::register('booksService', 'BooksService');
Flight::register('categoriesService', 'CategoriesService');
Flight::register('ordersService', 'OrdersService');
Flight::register('reviewsService', 'ReviewsService');
Flight::register('usersService', 'UsersService');

Flight::route('GET /', function() {
    echo "working!";
});

require_once './routes/UsersRoutes.php';
require_once './routes/BooksRoutes.php';
require_once './routes/CategoriesRoutes.php';
require_once './routes/OrdersRoutes.php';
require_once './routes/ReviewsRoutes.php';




Flight::start();