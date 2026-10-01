<?php

$routes->group('firma', ['namespace' => 'Modules\Firma\Controllers'], static function ($routes) {
    $routes->get('/', 'FirmaController::index');
    $routes->get('documentos', 'FirmaController::listar');
    $routes->post('documentos', 'FirmaController::subir', ['filter' => 'csrf']);
    $routes->post('roles/(:num)', 'FirmaController::importarRol/$1', ['filter' => 'csrf']);
    $routes->get('documentos/(:num)/versiones', 'FirmaController::versiones/$1');
    $routes->get('documentos/(:num)/pdf/(:num)', 'FirmaController::pdf/$1/$2');
    $routes->post('documentos/(:num)/firmar', 'FirmaController::iniciar/$1', ['filter' => 'csrf']);
    $routes->get('operaciones/(:num)', 'FirmaController::estado/$1');
    $routes->post('operaciones/(:num)/cancelar', 'FirmaController::cancelar/$1', ['filter' => 'csrf']);
    // El cliente de escritorio no comparte sesión: estos tres endpoints exigen tokens temporales.
    $routes->post('cliente/parametros', 'ClienteController::parametros');
    $routes->get('cliente/documento/(:segment)', 'ClienteController::documento/$1');
    $routes->post('cliente/recibir/(:segment)', 'ClienteController::recibir/$1');
});
