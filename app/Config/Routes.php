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
$routes->get('/', 'ExpedienteController::index');
//$routes->get('/', 'SeederController::index');

$routes->get('/nuevoexpediente', 'ExpedienteController::nuevoexpediente');
$routes->post('/nuevoexpediente', 'ExpedienteController::store');
//$routes->post('/nuevoexpediente', 'ExpedienteController::upload');
$routes->get('/cargoexpediente/(:num)', 'ExpedienteController::generateReceiptPDF/$1');
$routes->get('/buscarexpediente', 'ExpedienteController::buscarexpediente');
$routes->post('/buscarexpediente', 'ExpedienteController::infoexpediente');
$routes->get('/tupaexpediente', 'ExpedienteController::tupaexpediente');


/**
 * Configuracion de la Tabla menu
 * 
 */
$routes->get('configuracion/menus', 'ConfigController::menus');
$routes->get('menus', 'MenuController::index');
$routes->get('menus/(:num)', 'MenuController::show/$1');
$routes->post('menus', 'MenuController::create');
$routes->put('menus/(:num)', 'MenuController::update/$1');
$routes->delete('menus/(:num)', 'MenuController::delete/$1');

/**
 * Configuracion de Usuarios
 */

$routes->get('configuracion/usuarios', 'UserController::index');
$routes->post('configuracion/guardarusuario', 'UserController::store');
$routes->post('configuracion/eliminarusuario', 'UserController::delete');
$routes->get('configuracion/editar', 'UserController::edit');
$routes->post('configuracion/actualizar', 'UserController::update');

/**
 * Configuracion de Menu por Grupo Usuario
 */

$routes->get('configuracion/perfiles', 'MenuGroupUserController::index');
$routes->post('menugroupuser/', 'MenuGroupUserController::fetchData');
$routes->post('menugroupuser/save', 'MenuGroupUserController::save');
$routes->get('menugroupuser/delete/(:num)', 'MenuGroupUserController::delete/$1');
$routes->get('menugroupuser/getMenus', 'MenuGroupUserController::getMenus');
$routes->get('menugroupuser/getGroups', 'MenuGroupUserController::getGroups');


/**
 * Opciones de Mesa de Partes
 */
$routes->get('mesa_de_partes/no_leidos', 'TramiteController::getNuevosExpedientes');
$routes->get('mesa_de_partes/detalle', 'TramiteController::getDetallesExpedientes');
$routes->post('mesa_de_partes/derivar', 'TramiteController::postDerivarExpediente');
$routes->post('mesa_de_partes/atender', 'TramiteController::postAtenderExpediente');
$routes->post('mesa_de_partes/observar', 'TramiteController::postObservarExpediente');
$routes->get('mesa_de_partes/para_despacho', 'TramiteController::getDerivados');
$routes->get('mesa_de_partes/observados',  'TramiteController::getExpedientesObservados');
$routes->get('mesa_de_partes/derivados', 'TramiteController::getExpedientesDerivados');
$routes->get('mesa_de_partes/atendidos',  'TramiteController::getExpedientesAtendidos');
$routes->get('mesa_de_partes/todos_expediente',  'TramiteController::getExpedienteTodo');
$routes->post('mesa_de_partes/fetch_expedientes',  'TramiteController::fetch_expedientes');

/**
 * Opciones de Mesa de Partes
 */

$routes->get('expediente/para_recibir', 'TramiteController::getExpedientePorOficina');
$routes->get('expediente/derivados', 'TramiteController::getExpedientesDerivados');
$routes->get('expediente/observados', 'TramiteController::getExpedientesObservados');
$routes->get('expediente/atendidos', 'TramiteController::getExpedientesAtendidos');
$routes->get('expediente/todos', 'TramiteController::getExpedientesTodos');
$routes->get('expediente/detalle', 'TramiteController::getDetallesExpedientes');
$routes->post('expediente/derivar', 'TramiteController::postDerivarExpediente');
$routes->post('expediente/observar', 'TramiteController::postObservarExpediente');

