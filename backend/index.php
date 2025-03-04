<?php
require 'vendor/autoload.php';

Flight::route('GET /', function(){
    echo 'Hello, Flight!';
});

Flight::start();
