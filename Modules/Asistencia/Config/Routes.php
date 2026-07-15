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
        $routes->get('oficina', 'DatabaseController::oficinas');
        $routes->get('licencia', 'DatabaseController::licencias');
        $routes->get('turno', 'DatabaseController::turnos');
        $routes->get('turno_horario', 'DatabaseController::horarios');
        $routes->get('cargo', 'DatabaseController::cargos');
        $routes->get('rol', 'DatabaseController::nivelesAcceso');
        $routes->get('distrito', 'DatabaseController::distritos');
        $routes->get('microred', 'DatabaseController::sectores');
        $routes->get('usuario', 'DatabaseController::usuarios');
        $routes->get('establecimiento', 'DatabaseController::establecimientos');
        $routes->get('asignar_tarea', 'DatabaseController::asignarTareas');
        $routes->get('tipo_contrato', 'DatabaseController::modalidades');
        $routes->get('permiso', 'DatabaseController::permisos');
    });

    // Otros controladores específicos
    $routes->get('tuasalud/reporte_rol_tuasalud', 'TuasaludController::reporte');
});

###################### 
