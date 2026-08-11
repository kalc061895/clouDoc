<?php

// Creamos un grupo de rutas bajo el prefijo 'modulo-asistencia'
$routes->group('contrato', ['namespace' => 'Modules\Seleccion\Controllers'], function ($routes) {
    ############################## CONTRATACION #############################

    /**
     * Rutas para la gestion de convocatorias
     */
    $routes->group('admin',  function ($routes) {

        $routes->group('convocatorias', function ($routes) {

            $routes->get('/', 'Contratacion\ConvocatoriaController::index');
            $routes->get('listar', 'Contratacion\ConvocatoriaController::listar');
            $routes->post('guardar', 'Contratacion\ConvocatoriaController::guardar');
            $routes->get('editar/(:num)', 'Contratacion\ConvocatoriaController::editar/$1');
            $routes->post('eliminar/(:num)', 'Contratacion\ConvocatoriaController::eliminar/$1');
        });

        $routes->group('plazas', function ($routes) {

            $routes->get('/', 'Contratacion\PlazaController::index');
            $routes->get('listar/(:num)', 'Contratacion\PlazaController::listar/$1'); // id_convocatoria
            $routes->post('guardar', 'Contratacion\PlazaController::guardar');
            $routes->get('editar/(:num)', 'Contratacion\PlazaController::editar/$1');
            $routes->post('eliminar/(:num)', 'Contratacion\PlazaController::eliminar/$1');
        });

        $routes->group('postulacion', function ($routes) {

            $routes->get('resumen', 'Contratacion\AdminController::index');
            $routes->get('listar/(:num)', 'Contratacion\AdminController::listarPostulaciones/$1'); // id_convocatoria

            $routes->get('', 'Contratacion\AdminController::index');
            $routes->get('convocatorias', 'Contratacion\AdminController::convocatorias');
            $routes->get('plazas/(:num)', 'Contratacion\AdminController::plazas/$1');
            $routes->get('constancia/(:num)', 'Contratacion\PostulacionController::constanciaAdmin/$1');
        });
    });

    $routes->group('postulante', function ($routes) {

        $routes->get('convocatorias', 'Contratacion\ConvocatoriaController::vigentes');
        $routes->get('convocatorias/listar', 'Contratacion\ConvocatoriaController::listarVigentes');
        $routes->post('postulacion/iniciar', 'Contratacion\PostulacionController::iniciar');
        $routes->post('postulacion/datos-personales', 'Contratacion\PostulacionController::guardarDatos');
        $routes->post('postulacion/formacion', 'Contratacion\FormacionController::guardar');
        $routes->post('postulacion/experiencia', 'Contratacion\ExperienciaController::guardar');
        $routes->post('anexos/subir', 'Contratacion\Postulante\AnexoController::subir');
        $routes->get('resultado', 'Contratacion\PostulanteController::verResultado');

        $routes->post('guardar-datos', 'Contratacion\PostulanteController::guardarDatos');
        $routes->get('ver-datos', 'Contratacion\PostulanteController::verDatos');
        $routes->group('formacion', function ($routes) {
            $routes->post('guardar', 'Contratacion\FormacionController::guardar');
            $routes->get('listar', 'Contratacion\FormacionController::listar');
            $routes->post('eliminar/(:num)', 'Contratacion\FormacionController::eliminar/$1');
        });
        $routes->group('experiencia', function ($routes) {

            $routes->get('listar', 'Contratacion\ExperienciaController::listar');
            $routes->post('guardar', 'Contratacion\ExperienciaController::guardar');
            $routes->post('eliminar/(:num)', 'Contratacion\ExperienciaController::eliminar/$1');
        });

        $routes->group('capacitaciones', function ($routes) {
            $routes->get('listar', 'Contratacion\CapacitacionController::listar');
            $routes->post('guardar', 'Contratacion\CapacitacionController::guardar');
            $routes->post('eliminar/(:num)', 'Contratacion\CapacitacionController::eliminar/$1');
        });
        $routes->group('extra', function ($routes) {
            $routes->get('listar', 'Contratacion\InformacionExtraController::listar');
            $routes->post('guardar', 'Contratacion\InformacionExtraController::guardar');
            $routes->post('eliminar/(:num)', 'Contratacion\InformacionExtraController::eliminar/$1');
        });
        $routes->get('listarplazas', 'Contratacion\PlazaController::listarPlazas');
        $routes->post('postulacion/confirmar', 'Contratacion\PostulacionController::confirmarPostulacion');
        $routes->get(
            'postulacion/constancia/(:num)',
            'Contratacion\PostulacionController::constancia/$1'
        );
    });

    $routes->group('comision', function ($routes) {

        $routes->get('evaluacion', 'Contratacion\EvaluacionController::index');
        $routes->get('evaluacion/convocatorias', 'Contratacion\EvaluacionController::convocatorias');
        $routes->get('evaluacion/postulantes/(:num)', 'Contratacion\EvaluacionController::postulantes/$1');
        $routes->get('evaluar/ver/(:num)', 'Contratacion\EvaluacionController::postulacion/$1');
        $routes->post('calificacionprevia/guardar', 'Contratacion\EvaluacionController::guardar');
        $routes->get('reporte', 'Contratacion\EvaluacionController::resultados');
        $routes->post('reporte/preevaluacion/(:num)', 'Contratacion\EvaluacionController::resultadosPreEvaluacion/$1');
    });
});

###################### 
