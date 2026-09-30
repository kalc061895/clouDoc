# Navegación de Asistencia

Revisión estática de Routes.php, métodos públicos y vistas de entrada. No certifica el funcionamiento de los flujos ni la base de datos.

## Ejecución

```powershell
php spark db:seed 'Modules\Asistencia\Database\Seeds\NavigationSeeder'
```

Crea el grupo `asistencia` (1000), 3 agrupadores y 31 enlaces, y vincula las 34 entradas al grupo 1000. MasterSeeder también llama a NavigationSeeder. Las relaciones usan su ID autoincremental; los IDs fijos corresponden al grupo y los menús. No asigna usuarios al grupo. No configura permisos por DIRESA/red/microred ni autorización de endpoints.

Se puede repetir sin duplicar los IDs ni las relaciones. Ante IDs ocupados por otras opciones, falla. Si el grupo asistencia ya tiene otro ID, requiere migrar previamente sus usuarios. No elimina los menús antiguos ni sus relaciones: si ya ejecutó el ejemplo, requieren una migración separada.

## Pantallas incluidas

Firmar roles muestra un aviso de función pendiente; todavía no firma documentos.

| ID | Menú | Ruta | Destino |
| --- | --- | --- | --- |
| 1041 | Generación de roles | asistencia/roles/generacion | RolDocumentoController::index |
| 1042 | Historial de roles | asistencia/roles/historial | RolDocumentoController::historial |
| 1043 | Firmar roles | asistencia/roles/firmar | RolDocumentoController::firmar |
| 1005 | Marcaciones | asistencia/marcaciones | AsistenciaController::index |
| 1004 | Programación de turnos | asistencia/programacion | ProgramacionController::index |
| 1011 | Tipos de oficina | asistencia/gestordb/tipo-oficina | DatabaseController::tiposOficina |
| 1012 | Oficinas | asistencia/gestordb/oficina | DatabaseController::oficinas |
| 1013 | Diresas | asistencia/gestordb/diresas | DatabaseController::diresas |
| 1014 | Redes | asistencia/gestordb/redes | DatabaseController::redes |
| 1015 | Microredes | asistencia/gestordb/microredes | DatabaseController::microredes |
| 1016 | Establecimientos | asistencia/gestordb/establecimiento | DatabaseController::establecimientos |
| 1017 | Licencias | asistencia/gestordb/licencia | DatabaseController::licencias |
| 1018 | Turnos | asistencia/gestordb/turno | DatabaseController::turnos |
| 1021 | Permisos | asistencia/gestordb/permiso | DatabaseController::permisos |
| 1022 | Cargos | asistencia/gestordb/cargo | DatabaseController::cargos |
| 1023 | Profesiones | asistencia/gestordb/profesion | DatabaseController::profesiones |
| 1024 | Colegiaturas | asistencia/gestordb/colegiatura | DatabaseController::colegiaturas |
| 1025 | Feriados | asistencia/gestordb/feriado | DatabaseController::feriados |
| 1020 | Modalidades de contrato | asistencia/gestordb/modalidad | DatabaseController::modalidades |
| 1026 | Tipos de documento | asistencia/gestordb/tipodocumento | DatabaseController::tiposDocumentos |
| 1019 | Horarios de turnos | asistencia/gestordb/turnohorario | DatabaseController::turnosHorarios |
| 1027 | UPSS | asistencia/gestordb/upss | DatabaseController::upss |
| 1028 | Servicios UPSS | asistencia/gestordb/upss-servicio | DatabaseController::upssServicios |
| 1029 | Personas | asistencia/gestordb/persona | DatabaseController::personas |
| 1030 | Segundas especialidades | asistencia/gestordb/segunda-especialidad | DatabaseController::segundasEspecialidades |
| 1031 | Profesión y especialidades | asistencia/gestordb/profesion-especialidad | DatabaseController::profesionEspecialidades |
| 1032 | Periodos | asistencia/gestordb/periodos | PeriodoController::periodos |
| 1033 | Grupos de corte | asistencia/gestordb/grupo-corte | PeriodoController::gruposCorte |
| 1001 | Personal | asistencia/personal | PersonalController::index |
| 1002 | Nuevo personal | asistencia/personal/nuevo | PersonalController::nuevo |
| 1003 | Gestor de personal | asistencia/personal/gestorpersonal | PersonalController::gestorPersonal |

## Pantallas pendientes (no se insertan menús rotos)

| Ruta | Destino faltante |
| --- | --- |
| asistencia/dashboard | DashboardController::index |
| asistencia/administrador/planilla | PersonalController::planilla |
| asistencia/administrador/planilla_observacion | PersonalController::planillaObservacion |
| asistencia/administrador/planilla_microred | PersonalController::planillaMicrored |
| asistencia/administrador/planilla_capacitacion | PersonalController::planillaCapacitacion |
| asistencia/administrador/cambio_turno_user | RolesController::cambioTurnoAdmin |
| asistencia/administrador/load_file | DispositivoController::loadZtkeco |
| asistencia/administrador/load_file_hv | DispositivoController::loadHikvision |
| asistencia/administrador/reporte_total | ReportesController::totalMensual |
| asistencia/administrador/asistencia_hoy | ReportesController::asistenciaHoy |
| asistencia/administrador/faltas_tardanzas | ReportesController::tardanzasFaltas |
| asistencia/administrador/reporte_total_rango | ReportesController::rangoFechas |
| asistencia/administrador/reporte_total_inasistencia | ReportesController::inasistencias |
| asistencia/administrador/reporte_total_especifico | ReportesController::reporteEspecifico |
| asistencia/administrador/reporte_rol_turno | ReportesController::verRolTurnos |
| asistencia/administrador/reporte_nombrados | ReportesController::reporteAntiguo |
| asistencia/administrador/capnominal | ReportesController::capNominal |
| asistencia/administrador/calificador | ReportesController::calificadorMensual |
| asistencia/administrador/calificador_microred | ReportesController::calificadorMicrored |
| asistencia/administrador/listarreportes | ReportesController::listarReportes |
| asistencia/administrador/listarreportes_microred | ReportesController::listarReportesMicrored |
| asistencia/administrador/reporte_40 | ReportesController::reporte40 |
| asistencia/administrador/reporte_total_kardex | ReportesController::kardexTotal |
| asistencia/administrador/mapa_establecimiento | EstablecimientosController::mapa |
| asistencia/usuario/asistencia | UsuarioController::miAsistencia |
| asistencia/usuario/cambio_turno | UsuarioController::solicitarCambio |
| asistencia/usuario/perfil | UsuarioController::misDatos |
| asistencia/usuario/rol | UsuarioController::miRol |
| asistencia/usuario/vacaciones | UsuarioController::misVacaciones |
| asistencia/usuario/reloj | UsuarioController::marcarAsistencia |
| asistencia/usuario/asistencia_est | UsuarioController::asistenciaPorEstablecimiento |
| asistencia/gestordb/listar_tablas | DatabaseController::index |
| asistencia/gestordb/tarea | DatabaseController::tareas |
| asistencia/gestordb/turno_horario | DatabaseController::horarios |
| asistencia/gestordb/rol | DatabaseController::nivelesAcceso |
| asistencia/gestordb/distrito | DatabaseController::distritos |
| asistencia/gestordb/microred | DatabaseController::sectores |
| asistencia/gestordb/usuario | DatabaseController::usuarios |
| asistencia/gestordb/asignar_tarea | DatabaseController::asignarTareas |
| asistencia/tuasalud/reporte_rol_tuasalud | TuasaludController::reporte |

## Alias y descargas sin menú adicional

| Ruta | Destino |
| --- | --- |
| asistencia/roles | RolDocumentoController::index |
| asistencia/roles/consultar | RolDocumentoController::consultar |
| asistencia/roles/listar | RolDocumentoController::listar |
| asistencia/asistencia | AsistenciaController::index |
| asistencia/asistencia/exportar-excel | AsistenciaController::exportarExcel |
| asistencia/programacion/calendario | ProgramacionController::index |
| asistencia/programacion/descargar-plantilla | ProgramacionController::descargarPlantilla |
| asistencia/programacion/descargar-reporte-errores | ProgramacionController::descargarReporteErrores |
| asistencia/programacion/exportar-excel-importable | ProgramacionController::exportarExcelImportable |
| asistencia/programacion/exportar-excel-reporte | ProgramacionController::exportarExcelReporte |
| asistencia/horario | ProgramacionController::index |
| asistencia/administrador/personal | PersonalController::index |
| asistencia/administrador/nuevo_personal | PersonalController::nuevo |
| asistencia/administrador/asignar_plan | ProgramacionController::index |
| asistencia/administrador/ver_plan | ProgramacionController::index |
| asistencia/administrador/load_file_turnos | ProgramacionController::index |
| asistencia/administrador/asistencia | AsistenciaController::index |
| asistencia/administrador/reporte_asistencia | AsistenciaController::index |
| asistencia/gestordb/tipo_contrato | DatabaseController::modalidades |

## Todos los endpoints con controlador o método ausente

| Verbo | Ruta | Destino | Problema |
| --- | --- | --- | --- |
| GET | asistencia/dashboard | DashboardController::index | Falta controlador |
| POST | asistencia/administrador/nuevo_personal/guardar | PersonalController::guardar | Falta método |
| GET | asistencia/administrador/planilla | PersonalController::planilla | Falta método |
| GET | asistencia/administrador/planilla_observacion | PersonalController::planillaObservacion | Falta método |
| GET | asistencia/administrador/planilla_microred | PersonalController::planillaMicrored | Falta método |
| GET | asistencia/administrador/planilla_capacitacion | PersonalController::planillaCapacitacion | Falta método |
| GET | asistencia/administrador/cambio_turno_user | RolesController::cambioTurnoAdmin | Falta controlador |
| GET | asistencia/administrador/load_file | DispositivoController::loadZtkeco | Falta controlador |
| GET | asistencia/administrador/load_file_hv | DispositivoController::loadHikvision | Falta controlador |
| GET | asistencia/administrador/reporte_total | ReportesController::totalMensual | Falta controlador |
| GET | asistencia/administrador/asistencia_hoy | ReportesController::asistenciaHoy | Falta controlador |
| GET | asistencia/administrador/faltas_tardanzas | ReportesController::tardanzasFaltas | Falta controlador |
| GET | asistencia/administrador/reporte_total_rango | ReportesController::rangoFechas | Falta controlador |
| GET | asistencia/administrador/reporte_total_inasistencia | ReportesController::inasistencias | Falta controlador |
| GET | asistencia/administrador/reporte_total_especifico | ReportesController::reporteEspecifico | Falta controlador |
| GET | asistencia/administrador/reporte_rol_turno | ReportesController::verRolTurnos | Falta controlador |
| GET | asistencia/administrador/reporte_nombrados | ReportesController::reporteAntiguo | Falta controlador |
| GET | asistencia/administrador/capnominal | ReportesController::capNominal | Falta controlador |
| GET | asistencia/administrador/calificador | ReportesController::calificadorMensual | Falta controlador |
| GET | asistencia/administrador/calificador_microred | ReportesController::calificadorMicrored | Falta controlador |
| GET | asistencia/administrador/listarreportes | ReportesController::listarReportes | Falta controlador |
| GET | asistencia/administrador/listarreportes_microred | ReportesController::listarReportesMicrored | Falta controlador |
| GET | asistencia/administrador/reporte_40 | ReportesController::reporte40 | Falta controlador |
| GET | asistencia/administrador/reporte_total_kardex | ReportesController::kardexTotal | Falta controlador |
| GET | asistencia/administrador/mapa_establecimiento | EstablecimientosController::mapa | Falta controlador |
| GET | asistencia/usuario/asistencia | UsuarioController::miAsistencia | Falta controlador |
| GET | asistencia/usuario/cambio_turno | UsuarioController::solicitarCambio | Falta controlador |
| GET | asistencia/usuario/perfil | UsuarioController::misDatos | Falta controlador |
| GET | asistencia/usuario/rol | UsuarioController::miRol | Falta controlador |
| GET | asistencia/usuario/vacaciones | UsuarioController::misVacaciones | Falta controlador |
| GET | asistencia/usuario/reloj | UsuarioController::marcarAsistencia | Falta controlador |
| GET | asistencia/usuario/asistencia_est | UsuarioController::asistenciaPorEstablecimiento | Falta controlador |
| GET | asistencia/gestordb/listar_tablas | DatabaseController::index | Falta método |
| GET | asistencia/gestordb/tarea | DatabaseController::tareas | Falta método |
| GET | asistencia/gestordb/turno_horario | DatabaseController::horarios | Falta método |
| GET | asistencia/gestordb/rol | DatabaseController::nivelesAcceso | Falta método |
| GET | asistencia/gestordb/distrito | DatabaseController::distritos | Falta método |
| GET | asistencia/gestordb/microred | DatabaseController::sectores | Falta método |
| GET | asistencia/gestordb/usuario | DatabaseController::usuarios | Falta método |
| GET | asistencia/gestordb/asignar_tarea | DatabaseController::asignarTareas | Falta método |
| GET | asistencia/gestordb/api/establecimientos-lookup | DatabaseController::apiEstablecimientosLookup | Falta método |
| GET | asistencia/gestordb/api/oficinas-lookup | DatabaseController::apiOficinasLookup | Falta método |
| GET | asistencia/personal/api/incidencias/(:num) | LicenciaController::getByPersonal/$1 | Falta método |
| GET | asistencia/tuasalud/reporte_rol_tuasalud | TuasaludController::reporte | Falta controlador |

## Hallazgos del ejemplo anterior

- Namespace incorrecto en GroupUserSeeder y grupos 100–107; esta versión crea únicamente el grupo solicitado 1000.
- MenuGroupUserSeeder vinculaba IDs 600–663 al grupo 1. Ahora utiliza el catálogo explícito del módulo.
- Enlaces personal/listado_personal*, marcar/prueba2, administrador/periferie y gestordb/servicio no tienen ruta.
- Horarios usa gestordb/turnohorario; gestordb/turno_horario apunta al método inexistente horarios.
- Había catálogos duplicados, agrupadores vacíos y un padre MAPA DE ESTABLECIMIENTOS inexistente.
- Se omiten alias de programación y marcaciones para no repetir la misma pantalla. Los paneles por trabajador se acceden desde el gestor de personal.
- POST administrador/asistencia/rectificar referencia $1 sin capturarlo en la URL. Revisar antes de usarlo.
- Routes.php repite las rutas GET gestordb/licencia, gestordb/permiso y gestordb/turno.

Regenerar y verificar: `php Modules/Asistencia/Database/Seeds/audit_navigation.php`.
