<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

/** * ROOT */
$routes->match(['get', 'head'], '/', 'Home::index');

/** * AUTH (SHIELD ONLY) */
service('auth')->routes($routes);

$routes->get( 'post-login', 'AuthController::postLoginRedirect' );
$routes->get( 'set-organization/(:num)', 'AuthController::setOrganization/$1' );
$routes->get( 'logout', 'AuthController::logout' );

/** * HEALTH SYSTEM */
$routes->match( ['get', 'head'], 'health', 'Health::index' );

/** * ADMIN OBSERVABILITY */
$routes->group('admin', function (RouteCollection $routes) { $routes->match( ['get', 'head'], 'health', 'Admin\HealthController::index' );

    $routes->get( 'queue', 'Admin\QueueController::index' );
    $routes->get( 'queue/failed', 'Admin\QueueController::failed' );
    $routes->get( 'queue/retry/(:any)', 'Admin\QueueController::retry/$1' );
});