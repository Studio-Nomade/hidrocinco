<?php

declare(strict_types=1);

use App\Controllers\HomeController;
use App\Router;

require dirname(__DIR__) . '/src/bootstrap.php';

$router = new Router();
$router->get('/', [HomeController::class, 'index']);

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
