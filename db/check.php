<?php

declare(strict_types=1);

use App\Repositories\PostRepository;
use App\Repositories\ServiceRepository;

require dirname(__DIR__) . '/src/bootstrap.php';

$services = new ServiceRepository();
$posts = new PostRepository();
$ptas = $services->findBySlug('plantas-de-tratamientos-de-aguas-servidas-ptas', true);

printf("Servicios publicados: %d\n", count($services->allPublishedOrdered()));
printf("Bloques PTAS: %d\n", count($ptas['content'] ?? []));
printf("Primera nota: %s\n", $posts->published()[0]['title'] ?? 'sin notas');
