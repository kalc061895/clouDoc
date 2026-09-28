<?php

namespace Modules\Legajos\Config;

/**
 * Rutas del Módulo de Legajos de Personal
 */
$routes->group('legajos', ['namespace' => 'Modules\Legajos\Controllers'], function ($routes) {
    // 1. Vistas Principales
    $routes->get('/', 'LegajosController::index');
    $routes->get('listar', 'LegajosController::listar');
    $routes->get('ver/(:num)', 'LegajosController::ver/$1');
    $routes->get('estadisticas', 'LegajosController::estadisticas');

    // 2. Gestión de Servidores Públicos
    $routes->post('guardar-servidor', 'LegajosController::guardarServidor');
    $routes->post('eliminar-servidor/(:num)', 'LegajosController::eliminarServidor/$1');
    $routes->get('obtener-servidor/(:num)', 'LegajosController::obtenerServidor/$1');

    // 3. Sección 1: Datos Filiatorios y Familiares
    $routes->post('familiares/guardar', 'LegajosController::guardarFamiliar');
    $routes->post('familiares/eliminar/(:num)', 'LegajosController::eliminarFamiliar/$1');

    // 4. Sección 2: Formación Académica
    $routes->post('formacion/guardar', 'LegajosController::guardarFormacion');
    $routes->post('formacion/eliminar/(:num)', 'LegajosController::eliminarFormacion/$1');

    // 5. Sección 3: Experiencia Laboral Previa
    $routes->post('experiencia/guardar', 'LegajosController::guardarExperiencia');
    $routes->post('experiencia/eliminar/(:num)', 'LegajosController::eliminarExperiencia/$1');

    // 6. Sección 4: Movimientos de Personal y Desplazamientos
    $routes->post('movimientos/guardar', 'LegajosController::guardarMovimiento');
    $routes->post('movimientos/eliminar/(:num)', 'LegajosController::eliminarMovimiento/$1');

    // 7. Sección 5: Evaluaciones y Capacitaciones
    $routes->post('capacitaciones/guardar', 'LegajosController::guardarCapacitacion');
    $routes->post('capacitaciones/eliminar/(:num)', 'LegajosController::eliminarCapacitacion/$1');

    // 8. Sección 6: Méritos y Sanciones (PAD)
    $routes->post('meritos-sanciones/guardar', 'LegajosController::guardarSancionMerito');
    $routes->post('meritos-sanciones/eliminar/(:num)', 'LegajosController::eliminarSancionMerito/$1');

    // 9. Expediente Digital y Documentos Sustentatorios
    $routes->post('documentos/guardar', 'LegajosController::guardarDocumentoDigital');
    $routes->post('documentos/eliminar/(:num)', 'LegajosController::eliminarDocumentoDigital/$1');
    $routes->get('ver-documento', 'LegajosController::verDocumento');
    $routes->get('descargar-documento/(:num)', 'LegajosController::descargarDocumento/$1');
});

