<?php

declare(strict_types=1);

use App\Controllers\HomeController;
use App\Controllers\BlogController;
use App\Controllers\ServiceController;
use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\ServiceAdminController;
use App\Router;

require dirname(__DIR__) . '/src/bootstrap.php';

$router = new Router();
$router->get('/', [HomeController::class, 'index']);
$router->get('/servicios/{slug}', [ServiceController::class, 'show']);
$router->get('/blog', [BlogController::class, 'index']);
$router->get('/blog/{slug}', [BlogController::class, 'show']);
$router->get('/admin/login', [AuthController::class, 'showLogin']);
$router->post('/admin/login', [AuthController::class, 'login']);
$router->post('/admin/logout', [AuthController::class, 'logout']);
$router->get('/admin', [DashboardController::class, 'index']);
$router->get('/admin/servicios', [ServiceAdminController::class, 'index']);
$router->get('/admin/servicios/nuevo', [ServiceAdminController::class, 'create']);
$router->post('/admin/servicios/orden', [ServiceAdminController::class, 'reorder']);
$router->post('/admin/servicios', [ServiceAdminController::class, 'store']);
$router->get('/admin/servicios/{id}/editar', [ServiceAdminController::class, 'edit']);
$router->post('/admin/servicios/{id}/eliminar', [ServiceAdminController::class, 'destroy']);
$router->post('/admin/servicios/{id}', [ServiceAdminController::class, 'update']);
$router->get('/{legacySlug}', [ServiceController::class, 'redirectLegacy']);

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
