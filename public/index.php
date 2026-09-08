<?php

declare(strict_types=1);

use App\Controllers\HomeController;
use App\Controllers\BlogController;
use App\Controllers\ServiceController;
use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\ServiceAdminController;
use App\Controllers\Admin\PostAdminController;
use App\Controllers\Admin\SubmissionAdminController;
use App\Controllers\ContactController;
use App\Controllers\NewsletterController;
use App\Controllers\SitemapController;
use App\Controllers\AppsCincoController;
use App\Controllers\EmergencyController;
use App\Router;

require dirname(__DIR__) . '/src/bootstrap.php';

$router = new Router();
$router->get('/', [HomeController::class, 'index']);
$router->get('/servicios/{slug}', [ServiceController::class, 'show']);
$router->get('/blog', [BlogController::class, 'index']);
$router->get('/blog/{slug}', [BlogController::class, 'show']);
$router->get('/appscinco', [AppsCincoController::class, 'index']);
$router->get('/contacto', [ContactController::class, 'show']);
$router->post('/contacto', [ContactController::class, 'submit']);
$router->post('/newsletter', [NewsletterController::class, 'submit']);
$router->post('/emergencia', [EmergencyController::class, 'submit']);
$router->get('/sitemap.xml', [SitemapController::class, 'index']);
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
$router->get('/admin/blog', [PostAdminController::class, 'index']);
$router->get('/admin/blog/nueva', [PostAdminController::class, 'create']);
$router->post('/admin/blog', [PostAdminController::class, 'store']);
$router->get('/admin/blog/{id}/editar', [PostAdminController::class, 'edit']);
$router->post('/admin/blog/{id}/eliminar', [PostAdminController::class, 'destroy']);
$router->post('/admin/blog/{id}', [PostAdminController::class, 'update']);
$router->get('/admin/mensajes', [SubmissionAdminController::class, 'index']);
$router->get('/{legacySlug}', [ServiceController::class, 'redirectLegacy']);

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
