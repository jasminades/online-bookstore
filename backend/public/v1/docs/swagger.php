<?php

require __DIR__ . '/../../../../vendor/autoload.php';

define('BASE_URL', 'http://localhost:8888/book-store/backend/');
error_reporting(0);

$openapi = \OpenApi\Generator::scan(['../../../routes', './']);
header('Content-Type: application/x-yaml');
echo $openapi->toYaml();
?>