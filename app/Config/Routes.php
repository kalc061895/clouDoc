<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

 $routes->get('/', 'Home::index');

/**
 * Si necesitas manejarel login y register de forma particular
 * 
 */
// $routes->get('login', '\App\Controllers\Auth\LoginController::loginView');
// $routes->get('register', '\App\Controllers\Auth\RegisterController::registerView');
//service('auth')->routes($routes, ['except' => ['login', 'register']]);

service('auth')->routes($routes);
