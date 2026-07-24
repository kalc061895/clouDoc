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

    // ==========================================
    // RUTA PRINCIPAL / DASHBOARD
    // ==========================================
    $routes->get('dashboard', 'DashboardController::index');

    // ==========================================
    // GRUPO: ADMINISTRADOR (administrador/*)
    // ==========================================
    $routes->group('administrador', function ($routes) {

        // Personal
        $routes->get('personal', 'PersonalController::index');
        $routes->get('nuevo_personal', 'PersonalController::nuevo');
        $routes->post('nuevo_personal/guardar', 'PersonalController::guardar');
        $routes->get('planilla', 'PersonalController::planilla');
        $routes->get('planilla_observacion', 'PersonalController::planillaObservacion');
        $routes->get('planilla_microred', 'PersonalController::planillaMicrored');
        $routes->get('planilla_capacitacion', 'PersonalController::planillaCapacitacion');

        // Roles Mensuales y Turnos
        $routes->get('asignar_plan', 'RolesController::asignarPlan');
        $routes->get('ver_plan', 'RolesController::verPlan');
        $routes->get('cambio_turno_user', 'RolesController::cambioTurnoAdmin');
        $routes->get('load_file_turnos', 'RolesController::cargaMasiva');
        $routes->post('load_file_turnos/procesar', 'RolesController::procesarCargaMasiva');

        // Asistencia
        $routes->get('asistencia', 'AsistenciaController::rectificar');
        $routes->post('asistencia/rectificar', 'AsistenciaController::guardarRectificacion');
        $routes->get('reporte_asistencia', 'AsistenciaController::consultar');

        // Dispositivos Biométricos
        $routes->get('load_file', 'DispositivoController::loadZtkeco');
        $routes->get('load_file_hv', 'DispositivoController::loadHikvision');

        // Reportes (Aquí consumirás tu ReporteService)
        $routes->get('reporte_total', 'ReportesController::totalMensual');
        $routes->get('asistencia_hoy', 'ReportesController::asistenciaHoy');
        $routes->get('faltas_tardanzas', 'ReportesController::tardanzasFaltas');
        $routes->get('reporte_total_rango', 'ReportesController::rangoFechas');
        $routes->get('reporte_total_inasistencia', 'ReportesController::inasistencias');
        $routes->get('reporte_total_especifico', 'ReportesController::reporteEspecifico');
        $routes->get('reporte_rol_turno', 'ReportesController::verRolTurnos');
        $routes->get('reporte_nombrados', 'ReportesController::reporteAntiguo');
        $routes->get('capnominal', 'ReportesController::capNominal');
        $routes->get('calificador', 'ReportesController::calificadorMensual');
        $routes->get('calificador_microred', 'ReportesController::calificadorMicrored');
        $routes->get('listarreportes', 'ReportesController::listarReportes');
        $routes->get('listarreportes_microred', 'ReportesController::listarReportesMicrored');
        $routes->get('reporte_40', 'ReportesController::reporte40');
        $routes->get('reporte_total_kardex', 'ReportesController::kardexTotal');
        $routes->get('mapa_establecimiento', 'EstablecimientosController::mapa');
    });

    // ==========================================
    // GRUPO: USUARIO COMÚN (usuario/*)
    // ==========================================
    $routes->group('usuario', function ($routes) {
        $routes->get('asistencia', 'UsuarioController::miAsistencia');
        $routes->get('cambio_turno', 'UsuarioController::solicitarCambio');
        $routes->get('perfil', 'UsuarioController::misDatos');
        $routes->get('rol', 'UsuarioController::miRol');
        $routes->get('vacaciones', 'UsuarioController::misVacaciones');
        $routes->get('reloj', 'UsuarioController::marcarAsistencia');
        $routes->get('asistencia_est', 'UsuarioController::asistenciaPorEstablecimiento');
    });

    // ==========================================
    // GRUPO: MANTENIMIENTO BASE DE DATOS (gestordb/*)
    // ==========================================

    $routes->group('gestordb', function ($routes) {
        $routes->get('listar_tablas', 'DatabaseController::index');
        $routes->get('tarea', 'DatabaseController::tareas');
        $routes->get('tipo-oficina', 'DatabaseController::tiposOficina');
        $routes->get('oficina', 'DatabaseController::oficinas');
        $routes->get('diresas', 'DatabaseController::diresas');
        $routes->get('redes', 'DatabaseController::redes');
        $routes->get('microredes', 'DatabaseController::microredes');
        $routes->get('establecimiento', 'DatabaseController::establecimientos');
        $routes->get('licencia', 'DatabaseController::licencias');
        $routes->get('turno', 'DatabaseController::turnos');
        $routes->get('turno_horario', 'DatabaseController::horarios');
        $routes->get('rol', 'DatabaseController::nivelesAcceso');
        $routes->get('distrito', 'DatabaseController::distritos');
        $routes->get('microred', 'DatabaseController::sectores');
        $routes->get('usuario', 'DatabaseController::usuarios');
        $routes->get('asignar_tarea', 'DatabaseController::asignarTareas');
        $routes->get('tipo_contrato', 'DatabaseController::modalidades');
        $routes->get('permiso', 'DatabaseController::permisos');
        // Endpoints del API RESTful
        $routes->group('api', function ($routes) {

            // Endpoints de apoyo para selects desplegables (Lookups)
            $routes->get('establecimientos-lookup', 'DatabaseController::apiEstablecimientosLookup');
            $routes->get('oficinas-lookup', 'DatabaseController::apiOficinasLookup');

            $routes->get('diresas', 'DatabaseController::apiListarDiresas');
            $routes->post('diresas', 'DatabaseController::apiCrearDiresa');
            $routes->put('diresas/(:num)', 'DatabaseController::apiActualizarDiresa/$1');
            $routes->delete('diresas/(:num)', 'DatabaseController::apiEliminarDiresa/$1');


            $routes->get('redes', 'DatabaseController::apiListarRedes');
            $routes->post('redes', 'DatabaseController::apiCrearRed');
            $routes->put('redes/(:num)', 'DatabaseController::apiActualizarRed/$1');
            $routes->delete('redes/(:num)', 'DatabaseController::apiEliminarRed/$1');

            // Lookup de Redes
            $routes->get('redes-lookup', 'DatabaseController::apiRedesLookup');

            $routes->get('microredes', 'DatabaseController::apiListarMicroredes');
            $routes->post('microredes', 'DatabaseController::apiCrearMicrored');
            $routes->put('microredes/(:num)', 'DatabaseController::apiActualizarMicrored/$1');
            $routes->delete('microredes/(:num)', 'DatabaseController::apiEliminarMicrored/$1');

            // El lookup que consumirá la vista de establecimientos
            $routes->get('microredes-lookup', 'DatabaseController::apiMicroredesLookup');

            $routes->get('establecimientos', 'DatabaseController::apiListarEstablecimientos');
            $routes->post('establecimientos', 'DatabaseController::apiCrearEstablecimiento');
            $routes->put('establecimientos/(:num)', 'DatabaseController::apiActualizarEstablecimiento/$1');
            $routes->delete('establecimientos/(:num)', 'DatabaseController::apiEliminarEstablecimiento/$1');

            $routes->get('tipos-oficina', 'DatabaseController::apiListarTiposOficina');
            $routes->post('tipos-oficina', 'DatabaseController::apiCrearTipoOficina');
            $routes->put('tipos-oficina/(:num)', 'DatabaseController::apiActualizarTipoOficina/$1');
            $routes->delete('tipos-oficina/(:num)', 'DatabaseController::apiEliminarTipoOficina/$1');
            $routes->get('tipos-oficina-lookup', 'DatabaseController::apiTiposOficinaLookup');

            $routes->get('oficinas', 'DatabaseController::apiListarOficinas');
            $routes->get('oficinas-establecimiento/(:num)', 'DatabaseController::apiOficinasEstablecimiento/$1');
            $routes->post('oficinas', 'DatabaseController::apiCrearOficina');
            $routes->put('oficinas/(:num)', 'DatabaseController::apiActualizarOficina/$1');
            $routes->delete('oficinas/(:num)', 'DatabaseController::apiEliminarOficina/$1');
        });

        $routes->get('cargo', 'DatabaseController::cargos');
        $routes->group('api', function ($routes) {
            $routes->get('cargos', 'DatabaseController::apiListarCargos');
            $routes->post('cargos', 'DatabaseController::apiCrearCargo');
            $routes->put('cargos/(:num)', 'DatabaseController::apiActualizarCargo/$1');
            $routes->delete('cargos/(:num)', 'DatabaseController::apiEliminarCargo/$1');
            $routes->get('cargos-lookup', 'DatabaseController::apiCargosLookup');
        });

        $routes->get('profesion', 'DatabaseController::profesiones');
        $routes->group('api', function ($routes) {
            $routes->get('profesiones', 'DatabaseController::apiListarProfesiones');
            $routes->post('profesiones', 'DatabaseController::apiCrearProfesion');
            $routes->put('profesiones/(:num)', 'DatabaseController::apiActualizarProfesion/$1');
            $routes->delete('profesiones/(:num)', 'DatabaseController::apiEliminarProfesion/$1');
            $routes->get('profesiones-lookup', 'DatabaseController::apiProfesionesLookup');
        });

        $routes->get('colegiatura', 'DatabaseController::colegiaturas');

        $routes->group('api', function ($routes) {
            $routes->get('colegiaturas', 'DatabaseController::apiListarColegiaturas');
            $routes->post('colegiaturas', 'DatabaseController::apiCrearColegiatura');
            $routes->put('colegiaturas/(:num)', 'DatabaseController::apiActualizarColegiatura/$1');
            $routes->delete('colegiaturas/(:num)', 'DatabaseController::apiEliminarColegiatura/$1');
            $routes->get('colegiaturas-por-profesion/(:num)', 'DatabaseController::apiColegiaturasPorProfesion/$1');
        });

        $routes->get('feriado', 'DatabaseController::feriados');
        $routes->group('api', function ($routes) {
            $routes->get('feriados', 'DatabaseController::apiListarFeriados');
            $routes->post('feriados', 'DatabaseController::apiCrearFeriado');
            $routes->put('feriados/(:num)', 'DatabaseController::apiActualizarFeriado/$1');
            $routes->delete('feriados/(:num)', 'DatabaseController::apiEliminarFeriado/$1');
        });

        $routes->get('modalidad', 'DatabaseController::modalidades');
        $routes->group('api', function ($routes) {
            $routes->get('modalidades', 'DatabaseController::apiListarModalidades');
            $routes->post('modalidades', 'DatabaseController::apiCrearModalidad');
            $routes->put('modalidades/(:num)', 'DatabaseController::apiActualizarModalidad/$1');
            $routes->delete('modalidades/(:num)', 'DatabaseController::apiEliminarModalidad/$1');
        });

        $routes->get('licencia', 'DatabaseController::licencias');

        $routes->group('api', function ($routes) {
            $routes->get('licencias', 'DatabaseController::apiListarLicencias');
            $routes->post('licencias', 'DatabaseController::apiCrearLicencia');
            $routes->put('licencias/(:num)', 'DatabaseController::apiActualizarLicencia/$1');
            $routes->delete('licencias/(:num)', 'DatabaseController::apiEliminarLicencia/$1');
        });

        $routes->get('permiso', 'DatabaseController::permisos');

        $routes->group('api', function ($routes) {
            $routes->get('permisos', 'DatabaseController::apiListarPermisos');
            $routes->post('permisos', 'DatabaseController::apiCrearPermiso');
            $routes->put('permisos/(:num)', 'DatabaseController::apiActualizarPermiso/$1');
            $routes->delete('permisos/(:num)', 'DatabaseController::apiEliminarPermiso/$1');
        });

        $routes->get('tipodocumento', 'DatabaseController::tiposDocumentos');

        $routes->group('api', function ($routes) {
            $routes->get('documentos', 'DatabaseController::apiListarDocumentos');
            $routes->post('documentos', 'DatabaseController::apiCrearDocumento');
            $routes->put('documentos/(:num)', 'DatabaseController::apiActualizarDocumento/$1');
            $routes->delete('documentos/(:num)', 'DatabaseController::apiEliminarDocumento/$1');
        });

        $routes->get('turno', 'DatabaseController::turnos');

        $routes->group('api', function ($routes) {
            $routes->get('turnos', 'DatabaseController::apiListarTurnos');
            $routes->post('turnos', 'DatabaseController::apiCrearTurno');
            $routes->put('turnos/(:num)', 'DatabaseController::apiActualizarTurno/$1');
            $routes->delete('turnos/(:num)', 'DatabaseController::apiEliminarTurno/$1');
        });

        $routes->get('turnohorario', 'DatabaseController::turnosHorarios');

        $routes->group('api', function ($routes) {
            $routes->get('horarios', 'DatabaseController::apiListarHorarios');
            $routes->post('horarios', 'DatabaseController::apiCrearHorario');
            $routes->put('horarios/(:num)', 'DatabaseController::apiActualizarHorario/$1');
            $routes->delete('horarios/(:num)', 'DatabaseController::apiEliminarHorario/$1');
        });
        $routes->get('upss', 'DatabaseController::upss');

        $routes->group('api', function ($routes) {
            $routes->get('upss', 'DatabaseController::apiListarUpss');
            $routes->post('upss', 'DatabaseController::apiCrearUpss');
            $routes->put('upss/(:num)', 'DatabaseController::apiActualizarUpss/$1');
            $routes->delete('upss/(:num)', 'DatabaseController::apiEliminarUpss/$1');
        });

        $routes->get('servicio', 'DatabaseController::servicios');

        $routes->group('api', function ($routes) {
            $routes->get('servicios', 'DatabaseController::apiListarServicios');
            $routes->post('servicios', 'DatabaseController::apiCrearServicio');
            $routes->put('servicios/(:num)', 'DatabaseController::apiActualizarServicio/$1');
            $routes->delete('servicios/(:num)', 'DatabaseController::apiEliminarServicio/$1');
        });

        $routes->get('persona', 'DatabaseController::personas');

        $routes->group('api', function ($routes) {
            $routes->get('personas', 'DatabaseController::apiListarPersonas');
            $routes->post('personas', 'DatabaseController::apiCrearPersona');
            $routes->put('personas/(:num)', 'DatabaseController::apiActualizarPersona/$1');
            $routes->delete('personas/(:num)', 'DatabaseController::apiEliminarPersona/$1');
        });
        $routes->get('segunda-especialidad', 'DatabaseController::segundasEspecialidades');

        $routes->group('api', function ($routes) {
            $routes->get('segundas-especialidades', 'DatabaseController::apiListarEspecialidades');
            $routes->post('segundas-especialidades', 'DatabaseController::apiCrearEspecialidad');
            $routes->put('segundas-especialidades/(:num)', 'DatabaseController::apiActualizarEspecialidad/$1');
            $routes->delete('segundas-especialidades/(:num)', 'DatabaseController::apiEliminarEspecialidad/$1');
        });

        $routes->get('profesion-especialidad', 'DatabaseController::profesionEspecialidades');

        $routes->group('api', function ($routes) {
            $routes->get('profesion-especialidades', 'DatabaseController::apiListarRelacionesProfesionEspecialidades');
            $routes->post('profesion-especialidades', 'DatabaseController::apiCrearRelacionProfesionEspecialidades');
            $routes->delete('profesion-especialidades/(:num)', 'DatabaseController::apiEliminarRelacionProfesionEspecialidades/$1');
        });
    });

    $routes->group('personal', ['namespace' => 'Modules\Asistencia\Controllers'], function ($routes) {
        // 1. Vistas Web
        $routes->get('/', 'PersonalController::index');
        $routes->get('nuevo', 'PersonalController::nuevo');
        $routes->get('editar/(:num)', 'PersonalController::editar/$1');
        $routes->get('gestorpersonal', 'PersonalController::gestorPersonal');

        // PARA IMPLEMENTAR LAS VISTAS EN PANEL DE GESTOR DE PERSONAL 
        $routes->get('datos/(:num)', 'PersonalController::getPaneDatos/$1');
        $routes->get('asistencia/(:num)', 'AsistenciaController::getPaneAsistencia/$1');
        $routes->get('turnos/(:num)', 'TurnoController::getPaneTurnos/$1');
        $routes->get('incidencias/(:num)', 'RegistroLicenciaController::getPaneIncidencias/$1');
        $routes->get('vacaciones/(:num)', 'VacacionController::getPaneVacaciones/$1');
        // 2. Operaciones de Guardado / Modificación (JSON)
        $routes->group('api', function ($routes) {
            $routes->get('listar', 'PersonalController::apiListar');
            $routes->post('guardar-wizard', 'PersonalController::apiGuardarWizard');
            $routes->post('actualizar/(:num)', 'PersonalController::apiActualizarPersonal/$1');
            $routes->post('baja/(:num)', 'PersonalController::apiDarDeBaja/$1');

            $routes->get('fetch', 'PersonalController::fetch');
            // Obtener datos individuales de un trabajador por ID (Para cargar los datos en el Modal)
            $routes->get('show/(:num)', 'PersonalController::show/$1');

            // Endpoints consumidos por los Javascripts de las vistas parciales
            $routes->get('asistencia/(:num)', 'AsistenciaController::getByPersonal/$1');
            $routes->get('turnos/(:num)', 'TurnoController::getByPersonal/$1');
            $routes->get('incidencias/(:num)', 'LicenciaController::getByPersonal/$1');
            $routes->get('vacaciones/(:num)', 'VacacionController::getByPersonal/$1');

        });

        // 3. Alimentación de Selectores Dinámicos
        $routes->group('select', function ($routes) {
            $routes->get('tipos-documento', 'PersonalController::apiSelectTiposDocumento');
            $routes->get('establecimientos', 'PersonalController::apiSelectEstablecimientos');
            $routes->get('modalidades-contrato', 'PersonalController::apiSelectModalidadesContrato');
            $routes->get('cargos', 'PersonalController::apiSelectCargos');
            $routes->get('profesiones', 'PersonalController::apiSelectProfesiones');
            $routes->get('colegios', 'PersonalController::apiSelectColegios');
            $routes->get('especialidades', 'PersonalController::apiSelectEspecialidades');

            // Dependientes (Filtros en cascada)
            $routes->get('unidades', 'PersonalController::apiSelectUnidades');
            $routes->get('oficinas', 'PersonalController::apiSelectOficinas');
        });



    });

    $routes->group('licencia', ['namespace' => 'Modules\Asistencia\Controllers'], function ($routes) {

        $routes->group('api', ['namespace' => 'Modules\Asistencia\Controllers'], function ($routes) {
            $routes->get('tipos-activos', 'LicenciaController::tiposActivos');
            $routes->get('personal/(:num)', 'LicenciaController::obtenerPorPersonal/$1');
            $routes->post('guardar', 'LicenciaController::guardar');
            $routes->post('eliminar/(:num)', 'LicenciaController::eliminar/$1');
        });
    });




    // Otros controladores específicos
    $routes->get('tuasalud/reporte_rol_tuasalud', 'TuasaludController::reporte');
});

###################### 
