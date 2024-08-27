<?php

use App\Core\Routes;

require_once __DIR__ . '/../vendor/autoload.php';

header('Content-type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

new Routes();