<?php

// Creamos un grupo de rutas bajo el prefijo 'modulo-asistencia'
$routes->group('asistencia', ['namespace' => 'Modules\Asistencia\Controllers'], function ($routes) {

    $routes->get('horario', 'ProgramacionController::index');

    $routes->get('programacion/calendario', 'ProgramacionController::index');
    $routes->get('programacion/eventos', 'ProgramacionController::listar');
    $routes->post('programacion/eventos', 'ProgramacionController::guardar');
    $routes->put('programacion/eventos/(:num)', 'ProgramacionController::actualizar/$1');
    //$routes->post('programacion/eliminar/(:num)', 'ProgramacionController::eliminar/$1');
    $routes->delete('programacion/eventos/(:num)', 'ProgramacionController::eliminar/$1');
    $routes->get('programacion/trabajadores', 'ProgramacionController::trabajadores');


    $routes->get('programacion/pdf', 'ProgramacionController::pdfMensual');

    $routes->get('programacion/excel', 'ProgramacionController::loadFromExcel');
});

###################### 