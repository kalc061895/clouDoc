<?php

// Creamos un grupo de rutas bajo el prefijo 'modulo-asistencia'
$routes->group('seleccion', ['namespace' => 'Modules\Seleccion\Controllers'], function ($routes) {
    ############################## CONTRATACION #############################

    // Vista Principal
    $routes->get('convocatorias', 'ConvocatoriaController::index');

    // 2. Endpoints AJAX para la misma vista
    $routes->group('api', function ($routes) {
        $routes->get('convocatorias', 'ConvocatoriaController::listarApi');
        $routes->post('convocatorias', 'ConvocatoriaController::crearApi');
        $routes->put('convocatorias/(:num)', 'ConvocatoriaController::actualizarApi/$1');
        $routes->delete('convocatorias/(:num)', 'ConvocatoriaController::eliminarApi/$1');

        // Lookups para cargar selects
        $routes->get('tipos-convocatoria-lookup', 'ConvocatoriaController::tiposLookup');
        $routes->get('estados-convocatoria-lookup', 'ConvocatoriaController::estadosLookup');
    });





    /**
     * Rutas para la gestion de convocatorias
     */
    $routes->group('admin',  function ($routes) {

        $routes->group('convocatorias', function ($routes) {

            $routes->get('/', 'ConvocatoriaController::index');
            $routes->get('listar', 'ConvocatoriaController::listar');
            $routes->post('guardar', 'ConvocatoriaController::guardar');
            $routes->get('editar/(:num)', 'ConvocatoriaController::editar/$1');
            $routes->post('eliminar/(:num)', 'ConvocatoriaController::eliminar/$1');
        });

        $routes->group('plazas', function ($routes) {

            $routes->get('/', 'PlazaController::index');
            $routes->get('listar/(:num)', 'PlazaController::listar/$1'); // id_convocatoria
            $routes->post('guardar', 'PlazaController::guardar');
            $routes->get('editar/(:num)', 'PlazaController::editar/$1');
            $routes->post('eliminar/(:num)', 'PlazaController::eliminar/$1');
        });

        $routes->group('postulacion', function ($routes) {

            $routes->get('resumen', 'AdminController::index');
            $routes->get('listar/(:num)', 'AdminController::listarPostulaciones/$1'); // id_convocatoria

            $routes->get('', 'AdminController::index');
            $routes->get('convocatorias', 'AdminController::convocatorias');
            $routes->get('plazas/(:num)', 'AdminController::plazas/$1');
            $routes->get('constancia/(:num)', 'PostulacionController::constanciaAdmin/$1');
        });
    });

    $routes->group('postulante', function ($routes) {

        $routes->get('convocatorias', 'ConvocatoriaController::vigentes');
        $routes->get('convocatorias/listar', 'ConvocatoriaController::listarVigentes');
        $routes->post('postulacion/iniciar', 'PostulacionController::iniciar');
        $routes->post('postulacion/datos-personales', 'PostulacionController::guardarDatos');
        $routes->post('postulacion/formacion', 'FormacionController::guardar');
        $routes->post('postulacion/experiencia', 'ExperienciaController::guardar');
        $routes->post('anexos/subir', 'Postulante\AnexoController::subir');
        $routes->get('resultado', 'PostulanteController::verResultado');

        $routes->post('guardar-datos', 'PostulanteController::guardarDatos');
        $routes->get('ver-datos', 'PostulanteController::verDatos');
        $routes->group('formacion', function ($routes) {
            $routes->post('guardar', 'FormacionController::guardar');
            $routes->get('listar', 'FormacionController::listar');
            $routes->post('eliminar/(:num)', 'FormacionController::eliminar/$1');
        });
        $routes->group('experiencia', function ($routes) {

            $routes->get('listar', 'ExperienciaController::listar');
            $routes->post('guardar', 'ExperienciaController::guardar');
            $routes->post('eliminar/(:num)', 'ExperienciaController::eliminar/$1');
        });

        $routes->group('capacitaciones', function ($routes) {
            $routes->get('listar', 'CapacitacionController::listar');
            $routes->post('guardar', 'CapacitacionController::guardar');
            $routes->post('eliminar/(:num)', 'CapacitacionController::eliminar/$1');
        });
        $routes->group('extra', function ($routes) {
            $routes->get('listar', 'InformacionExtraController::listar');
            $routes->post('guardar', 'InformacionExtraController::guardar');
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
