<?php

use App\Core\Auth;
use App\Core\Route;
use App\Core\ExceptionsHandler;

// Including global settings
include(__DIR__ . '/../App/config.php');
require ROOT . 'vendor/autoload.php';

$route = new Route();
$route->start();
$auth = new Auth();

// Registering custom error and exception handlers
(new ExceptionsHandler())->register();

/* echo '<pre>';
print_r(get_declared_classes());
echo '</pre>'; */
