<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/inicio', 'Home::index');

/**
 * Si necesitas manejarel login y register de forma particular
 * 
 */
// $routes->get('login', '\App\Controllers\Auth\LoginController::loginView');
// $routes->get('register', '\App\Controllers\Auth\RegisterController::registerView');
//service('auth')->routes($routes, ['except' => ['login', 'register']]);

service('auth')->routes($routes);

/**
 * Rutas para usuarios externos 
 * - nuevo tramite
 * - buscar tramite
 * - tupa tramite
 * 
 * 
 */
$routes->get('/','ExpedienteController::index');
$routes->get('/nuevoexpediente','ExpedienteController::nuevoexpediente');
$routes->get('/buscarexpediente','ExpedienteController::buscarexpediente');
$routes->post('/buscarexpediente','ExpedienteController::infoexpediente');
$routes->get('/tupaexpediente','ExpedienteController::tupaexpediente');
