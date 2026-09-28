<?php

// Creamos un grupo de rutas bajo el prefijo 'modulo-asistencia'
$routes->group('seleccion', ['namespace' => 'Modules\Seleccion\Controllers'], function ($routes) {
    ############################## CONTRATACION #############################
    // En app/Config/Routes.php o en el Routes.php de tu módulo
    $routes->get('ver/documento', 'AdjuntoController::verDocumento');

    // ==========================================
    // Módulo Principal: Convocatorias Workflow
    // ==========================================
    //$routes->get('/', 'ConvocatoriaWorkflowController::index');

    $routes->group('convocatorias', ['filter' => 'group:superadmin'], function ($routes) {
        $routes->get('/', 'ConvocatoriaWorkflowController::index');

        // Vista de Configuración / Detalle (Resumen por defecto)
        $routes->get('(:num)', 'ConvocatoriaWorkflowController::detalle/$1/resumen');

        // Subpestañas dinámicas (cargos, requisitos, cronograma, documentos, anexos)
        $routes->get('partial/(:num)/(:segment)', 'ConvocatoriaWorkflowController::obtenerPartial/$1/$2');
    });

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
    $routes->group('admin', ['filter' => 'group:superadmin'], function ($routes) {
        $routes->group('anexos', function ($routes) {
            $routes->get('listar/(:num)', 'AnexoController::listar/$1');
            $routes->post('guardar', 'AnexoController::guardar');
            $routes->post('eliminar/(:num)', 'AnexoController::eliminar/$1');
        });

        $routes->group('cargos-convocatoria', function ($routes) {
            $routes->get('listar/(:num)', 'ConvocatoriaCargoController::listar/$1');
            $routes->post('guardar', 'ConvocatoriaCargoController::guardar');
            $routes->post('eliminar/(:num)', 'ConvocatoriaCargoController::eliminar/$1');
        });

        $routes->group('etapas-convocatoria', function ($routes) {
            $routes->get('listar/(:num)', 'ConvocatoriaEtapaController::listar/$1');
            $routes->post('guardar', 'ConvocatoriaEtapaController::guardar');
            $routes->post('eliminar/(:num)', 'ConvocatoriaEtapaController::eliminar/$1');
        });


        $routes->group('documentos-convocatoria', function ($routes) {
            $routes->get('listar/(:num)', 'ConvocatoriaDocumentoController::listar/$1');
            $routes->post('guardar', 'ConvocatoriaDocumentoController::guardar');
            $routes->post('eliminar/(:num)', 'ConvocatoriaDocumentoController::eliminar/$1');
        });

        $routes->group('requisitos', static function ($routes) {
            $routes->get('partial/(:num)', 'RequisitoController::renderPartial/$1');
            $routes->post('store', 'RequisitoController::store');
            $routes->post('update/(:num)', 'RequisitoController::update/$1');
            $routes->delete('delete/(:num)', 'RequisitoController::delete/$1');
        });
        $routes->group('comisiones', static function ($routes) {
            $routes->get('listar/(:num)', 'ComisionConvocatoriaController::listar/$1');
            $routes->post('guardar', 'ComisionConvocatoriaController::guardar');
            $routes->post('eliminar/(:num)', 'ComisionConvocatoriaController::eliminar/$1');
            $routes->get('miembros/(:num)', 'ComisionConvocatoriaController::miembros/$1');
            $routes->post('miembros/guardar', 'ComisionConvocatoriaController::guardarMiembro');
            $routes->post('miembros/eliminar/(:num)', 'ComisionConvocatoriaController::eliminarMiembro/$1');
            $routes->get('usuarios', 'ComisionConvocatoriaController::usuarios');
        });
        $routes->group('fichas-evaluacion', static function ($routes) {
            $routes->get('listar/(:num)', 'FichaEvaluacionController::listar/$1');
            $routes->post('guardar', 'FichaEvaluacionController::guardar');
            $routes->post('eliminar/(:num)', 'FichaEvaluacionController::eliminar/$1');
            $routes->get('criterios/(:num)', 'FichaEvaluacionController::criterios/$1');
            $routes->post('criterios/guardar', 'FichaEvaluacionController::guardarCriterio');
            $routes->post('criterios/eliminar/(:num)', 'FichaEvaluacionController::eliminarCriterio/$1');
            $routes->get('reglas/(:num)', 'FichaEvaluacionController::reglas/$1');
            $routes->post('reglas/guardar', 'FichaEvaluacionController::guardarRegla');
            $routes->post('reglas/eliminar/(:num)', 'FichaEvaluacionController::eliminarRegla/$1');
        });
    });

    $routes->group('postulacion', function ($routes) {
        $routes->get('/', 'PostulacionController::index');
        $routes->get('iniciar/(:num)', 'PostulacionController::iniciar/$1');

        // Ficha de inscripción: las acciones siempre se resuelven contra el usuario autenticado.
        $routes->group('inscripcion', function ($routes) {
            $routes->get('partial/(:num)/(:segment)', 'InscripcionController::partial/$1/$2');
            $routes->post('plaza/(:num)', 'InscripcionController::plaza/$1');
            $routes->post('datos/(:num)', 'InscripcionController::datos/$1');
            $routes->post('registro/(:num)/(:segment)', 'InscripcionController::guardar/$1/$2');
            $routes->delete('registro/(:num)/(:segment)/(:num)', 'InscripcionController::eliminar/$1/$2/$3');
            $routes->post('anexo/(:num)/(:num)', 'InscripcionController::anexo/$1/$2');
            $routes->post('declaracion/(:num)/(:num)', 'InscripcionController::declaracion/$1/$2');
            $routes->post('validar/(:num)', 'InscripcionController::validar/$1');
            $routes->post('confirmar/(:num)', 'InscripcionController::confirmar/$1');
        });

        $routes->group('iniciar', function ($routes) {

            $routes->post('(:num)/resumen', 'PostulanteController::resumen');

            // Redirección si se refresca (F5) en una parcial
            $routes->get('(:num)/(:any)', function ($convocatoriaId) {
                return redirect()->to(base_url("seleccion/postulacion/iniciar/{$convocatoriaId}"));
            });
        });

        $routes->group('postulante', function ($routes) {
            $routes->get('datos-personales/(:num)', 'PostulanteController::datosPersonales');
            $routes->post('ver-datos', 'PostulanteController::verDatos');
            $routes->post('guardar-datos', 'PostulanteController::guardarDatos');
        });

        $routes->group('profesion', function ($routes) {
            $routes->get('formacion-profesional/(:num)', 'PostulanteProfesionController::formacionProfesional');
            $routes->post('ver-datos', 'PostulanteController::verDatos');
            $routes->post('guardar-profesion', 'PostulanteProfesionController::guardarProfesion');
        });

        $routes->get('generar-solicitud/(:num)', 'GenerarDocumentoController::generarSolicitud/$1');
        $routes->get('generar-ficha-unica/(:num)', 'GenerarDocumentoController::generarFichaUnica/$1');
        $routes->get('generar-ficha-autoevaluacion/(:num)', 'GenerarDocumentoController::generarFichaAutoevaluacion/$1');
        $routes->get('generar-constancia-inscripcion/(:num)', 'GenerarDocumentoController::generarConstanciaInscripcion/$1');
    });


    /**
     * Rutas para la gestion de convocatorias
     */
    $routes->group('admin', function ($routes) {

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
    $routes->group('api', ['filter' => 'group:superadmin'], function ($routes) {

        // API REST: catálogos administrativos de Selección.
        $routes->get('tipos-convocatoria', 'TipoConvocatoriaController::index');
        $routes->get('tipos-convocatoria/(:num)', 'TipoConvocatoriaController::show/$1');
        $routes->post('tipos-convocatoria', 'TipoConvocatoriaController::create');
        $routes->put('tipos-convocatoria/(:num)', 'TipoConvocatoriaController::update/$1');
        $routes->delete('tipos-convocatoria/(:num)', 'TipoConvocatoriaController::delete/$1');

        $routes->get('estados-convocatoria', 'EstadoConvocatoriaController::index');
        $routes->get('estados-convocatoria/(:num)', 'EstadoConvocatoriaController::show/$1');
        $routes->post('estados-convocatoria', 'EstadoConvocatoriaController::create');
        $routes->put('estados-convocatoria/(:num)', 'EstadoConvocatoriaController::update/$1');
        $routes->delete('estados-convocatoria/(:num)', 'EstadoConvocatoriaController::delete/$1');

        $routes->get('tipos-cargo', 'TipoCargoController::index');
        $routes->get('tipos-cargo/(:num)', 'TipoCargoController::show/$1');
        $routes->post('tipos-cargo', 'TipoCargoController::create');
        $routes->put('tipos-cargo/(:num)', 'TipoCargoController::update/$1');
        $routes->delete('tipos-cargo/(:num)', 'TipoCargoController::delete/$1');

        $routes->get('cargo', 'CargoController::index');
        $routes->get('cargo/(:num)', 'CargoController::show/$1');
        $routes->post('cargo', 'CargoController::create');
        $routes->put('cargo/(:num)', 'CargoController::update/$1');
        $routes->delete('cargo/(:num)', 'CargoController::delete/$1');


        $routes->get('grupos-ocupacionales', 'GrupoOcupacionalController::index');
        $routes->get('grupos-ocupacionales/(:num)', 'GrupoOcupacionalController::show/$1');
        $routes->post('grupos-ocupacionales', 'GrupoOcupacionalController::create');
        $routes->put('grupos-ocupacionales/(:num)', 'GrupoOcupacionalController::update/$1');
        $routes->delete('grupos-ocupacionales/(:num)', 'GrupoOcupacionalController::delete/$1');

        $routes->get('niveles', 'NivelController::index');
        $routes->get('niveles/(:num)', 'NivelController::show/$1');
        $routes->post('niveles', 'NivelController::create');
        $routes->put('niveles/(:num)', 'NivelController::update/$1');
        $routes->delete('niveles/(:num)', 'NivelController::delete/$1');

        $routes->get('modalidades-vinculo', 'ModalidadVinculoController::index');
        $routes->get('modalidades-vinculo/(:num)', 'ModalidadVinculoController::show/$1');
        $routes->post('modalidades-vinculo', 'ModalidadVinculoController::create');
        $routes->put('modalidades-vinculo/(:num)', 'ModalidadVinculoController::update/$1');
        $routes->delete('modalidades-vinculo/(:num)', 'ModalidadVinculoController::delete/$1');

        $routes->get('profesiones', 'ProfesionController::index');
        $routes->get('profesiones/(:num)', 'ProfesionController::show/$1');
        $routes->post('profesiones', 'ProfesionController::create');
        $routes->put('profesiones/(:num)', 'ProfesionController::update/$1');
        $routes->delete('profesiones/(:num)', 'ProfesionController::delete/$1');

        $routes->get('niveles-formacion', 'NivelFormacionController::index');
        $routes->get('niveles-formacion/(:num)', 'NivelFormacionController::show/$1');
        $routes->post('niveles-formacion', 'NivelFormacionController::create');
        $routes->put('niveles-formacion/(:num)', 'NivelFormacionController::update/$1');
        $routes->delete('niveles-formacion/(:num)', 'NivelFormacionController::delete/$1');

        $routes->get('tipos-documento', 'TipoDocumentoController::index');
        $routes->get('tipos-documento/(:num)', 'TipoDocumentoController::show/$1');
        $routes->post('tipos-documento', 'TipoDocumentoController::create');
        $routes->put('tipos-documento/(:num)', 'TipoDocumentoController::update/$1');
        $routes->delete('tipos-documento/(:num)', 'TipoDocumentoController::delete/$1');

        $routes->get('estados-postulacion', 'EstadoPostulacionController::index');
        $routes->get('estados-postulacion/(:num)', 'EstadoPostulacionController::show/$1');
        $routes->post('estados-postulacion', 'EstadoPostulacionController::create');
        $routes->put('estados-postulacion/(:num)', 'EstadoPostulacionController::update/$1');
        $routes->delete('estados-postulacion/(:num)', 'EstadoPostulacionController::delete/$1');

        $routes->get('estados-expediente', 'EstadoExpedienteController::index');
        $routes->get('estados-expediente/(:num)', 'EstadoExpedienteController::show/$1');
        $routes->post('estados-expediente', 'EstadoExpedienteController::create');
        $routes->put('estados-expediente/(:num)', 'EstadoExpedienteController::update/$1');
        $routes->delete('estados-expediente/(:num)', 'EstadoExpedienteController::delete/$1');

        $routes->get('tipos-archivo', 'TipoArchivoController::index');
        $routes->get('tipos-archivo/(:num)', 'TipoArchivoController::show/$1');
        $routes->post('tipos-archivo', 'TipoArchivoController::create');
        $routes->put('tipos-archivo/(:num)', 'TipoArchivoController::update/$1');
        $routes->delete('tipos-archivo/(:num)', 'TipoArchivoController::delete/$1');

        $routes->get('etapas', 'EtapaController::index');
        $routes->get('etapas/(:num)', 'EtapaController::show/$1');
        $routes->post('etapas', 'EtapaController::create');
        $routes->put('etapas/(:num)', 'EtapaController::update/$1');
        $routes->delete('etapas/(:num)', 'EtapaController::delete/$1');

        $routes->get('tipos-bonificacion', 'TipoBonificacionController::index');
        $routes->get('tipos-bonificacion/(:num)', 'TipoBonificacionController::show/$1');
        $routes->post('tipos-bonificacion', 'TipoBonificacionController::create');
        $routes->put('tipos-bonificacion/(:num)', 'TipoBonificacionController::update/$1');
        $routes->delete('tipos-bonificacion/(:num)', 'TipoBonificacionController::delete/$1');

        $routes->get('tipos-notificacion', 'TipoNotificacionController::index');
        $routes->get('tipos-notificacion/(:num)', 'TipoNotificacionController::show/$1');
        $routes->post('tipos-notificacion', 'TipoNotificacionController::create');
        $routes->put('tipos-notificacion/(:num)', 'TipoNotificacionController::update/$1');
        $routes->delete('tipos-notificacion/(:num)', 'TipoNotificacionController::delete/$1');

        $routes->get('tipos-declaracion', 'TipoDeclaracionController::index');
        $routes->get('tipos-declaracion/(:num)', 'TipoDeclaracionController::show/$1');
        $routes->post('tipos-declaracion', 'TipoDeclaracionController::create');
        $routes->put('tipos-declaracion/(:num)', 'TipoDeclaracionController::update/$1');
        $routes->delete('tipos-declaracion/(:num)', 'TipoDeclaracionController::delete/$1');
    });


    $routes->group('comision', function ($routes) {

        $routes->get('/', 'EvaluacionController::index');
         
        $routes->group('evaluacion', ['namespace' => 'Modules\Seleccion\Controllers'], function ($routes) {
            $routes->get('/', 'EvaluacionController::index');
            $routes->get('postulantes/(:num)', 'EvaluacionController::getPostulantes/$1');
            $routes->get('formulario/(:num)', 'EvaluacionController::getFormularioEvaluacion/$1');
            $routes->post('guardar', 'EvaluacionController::guardarEvaluacion');
        });

    });
    // ==========================================
    // Vistas de Gestión (Módulo Selección)
    // ==========================================

    $routes->group('gestion', function ($routes) {

        $routes->get('tipos-convocatoria', function () {
            return view('Modules\Seleccion\Views\tipos_convocatoria\index');
        });

        $routes->get('estados-convocatoria', function () {
            return view('Modules\Seleccion\Views\estados_convocatoria\index');
        });

        $routes->get('cargo', function () {
            return view('Modules\Seleccion\Views\cargo\index');
        });

        $routes->get('tipos-cargo', function () {
            return view('Modules\Seleccion\Views\tipos_cargo\index');
        });

        $routes->get('grupos-ocupacionales', function () {
            return view('Modules\Seleccion\Views\grupos_ocupacionales\index');
        });

        $routes->get('niveles', function () {
            return view('Modules\Seleccion\Views\niveles\index');
        });

        $routes->get('modalidades-vinculo', function () {
            return view('Modules\Seleccion\Views\modalidades_vinculo\index');
        });

        $routes->get('profesiones', function () {
            return view('Modules\Seleccion\Views\profesiones\index');
        });

        $routes->get('niveles-formacion', function () {
            return view('Modules\Seleccion\Views\niveles_formacion\index');
        });

        $routes->get('tipos-documento', function () {
            return view('Modules\Seleccion\Views\tipos_documento\index');
        });

        $routes->get('estados-postulacion', function () {
            return view('Modules\Seleccion\Views\estados_postulacion\index');
        });

        $routes->get('estados-expediente', function () {
            return view('Modules\Seleccion\Views\estados_expediente\index');
        });

        $routes->get('tipos-archivo', function () {
            return view('Modules\Seleccion\Views\tipos_archivo\index');
        });

        $routes->get('etapas', function () {
            return view('Modules\Seleccion\Views\etapas\index');
        });

        $routes->get('tipos-bonificacion', function () {
            return view('Modules\Seleccion\Views\tipos_bonificacion\index');
        });

        $routes->get('tipos-notificacion', function () {
            return view('Modules\Seleccion\Views\tipos_notificacion\index');
        });

        $routes->get('tipos-declaracion', function () {
            return view('Modules\Seleccion\Views\tipos_declaracion\index');
        });
    });
});

// Endpoint API para la cabecera e info pública
$routes->group('api/seleccion', ['namespace' => 'Modules\Seleccion\Controllers\Api'], static function ($routes) {
    $routes->get('convocatorias', 'ConvocatoriaApiController::index');
    $routes->get('convocatorias/(:num)', 'ConvocatoriaApiController::show/$1');
    $routes->post('convocatorias', 'ConvocatoriaApiController::create');
    $routes->put('convocatorias/(:num)', 'ConvocatoriaApiController::update/$1');
    $routes->post('convocatorias/(:num)/publicar', 'ConvocatoriaApiController::publish/$1');
});






###################### 
