<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('/', 'Home::index');

/**
 * AUTH (SHIELD ONLY)
 */
service('auth')->routes($routes);
$routes->get('post-login', 'AuthController::postLoginRedirect');
$routes->get('logout', 'AuthController::logout');

/**
 * HEALTH SYSTEM
 */
$routes->get('/health', 'Health::index');

/**
 * ADMIN OBSERVABILITY
 */
$routes->group('admin', function ($routes) {
    $routes->get('health', 'Admin\HealthController::index');
    $routes->get('queue', 'Admin\QueueController::index');
    $routes->get('queue/failed', 'Admin\QueueController::failed');
    $routes->get('queue/retry/(:any)', 'Admin\QueueController::retry/$1');
});