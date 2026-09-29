<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?>
Programación Mensual de Turnos
<?= $this->endSection() ?>

<?= $this->section('pageStyles') ?>
<style>
    /* Matriz de programación */
    .table-matriz-wrapper {
        position: relative;
        max-height: 650px;
        overflow: auto;
    }
    .table-matriz {
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.78rem;
    }
    .table-matriz th, .table-matriz td {
        padding: 4px 6px;
        border: 1px solid #e2e8f0;
        text-align: center;
        vertical-align: middle;
    }
    /* Columnas congeladas (Sticky) */
    .sticky-col-1 {
        position: sticky;
        left: 0;
        background-color: #ffffff;
        z-index: 10;
        width: 45px;
        min-width: 45px;
    }
    .sticky-col-2 {
        position: sticky;
        left: 45px;
        background-color: #ffffff;
        z-index: 10;
        width: 85px;
        min-width: 85px;
    }
    .sticky-col-3 {
        position: sticky;
        left: 130px;
        background-color: #ffffff;
        z-index: 10;
        width: 200px;
        min-width: 200px;
        text-align: left !important;
    }
    .table-matriz thead th.sticky-col-1,
    .table-matriz thead th.sticky-col-2,
    .table-matriz thead th.sticky-col-3 {
        background-color: #1c3254;
        color: #ffffff;
        z-index: 20;
    }
    .table-matriz thead th {
        position: sticky;
        top: 0;
        background-color: #1c3254;
        color: #ffffff;
        z-index: 15;
    }
    .fin-de-semana {
        background-color: #f8fafc !important;
        color: #ef4444;
    }
    .celda-turno {
        min-width: 38px;
        max-width: 46px;
        height: 38px;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
        user-select: none;
    }
    .celda-turno:hover {
        background-color: #e2e8f0 !important;
    }
    .badge-turno-matriz {
        display: inline-block;
        padding: 3px 5px;
        border-radius: 4px;
        color: #ffffff;
        font-weight: 700;
        font-size: 0.72rem;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    .btn-asignar-vacio {
        opacity: 0;
        color: #94a3b8;
        font-size: 0.7rem;
    }
    .celda-turno:hover .btn-asignar-vacio {
        opacity: 1;
    }
    .leyenda-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.75rem;
    }
    .leyenda-color {
        width: 14px;
        height: 14px;
        border-radius: 3px;
        flex-shrink: 0;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid px-0">

    <!-- CABECERA PRINCIPAL -->
    <div class="card bg-white p-4 border-0 shadow-sm rounded-4 mb-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                    <iconify-icon icon="solar:calendar-bold-duotone" class="me-2 text-primary" style="font-size: 1.8rem;"></iconify-icon>
                    Programación Mensual de Turnos
                </h4>
                <p class="text-muted mb-0 small">
                    Asignación, rol mensual de guardias y jornadas laborales del personal con control de horas y cruces.
                </p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="abrirModalImportar()">
                    <iconify-icon icon="solar:upload-track-bold" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
                    Importar Excel
                </button>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-success btn-sm rounded-pill dropdown-toggle px-3 d-inline-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                        <iconify-icon icon="solar:file-excel-bold" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
                        Exportar
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                        <li>
                            <a class="dropdown-item py-2 small d-flex align-items-center" href="javascript:void(0)" onclick="exportarExcelReporte()">
                                <iconify-icon icon="solar:printer-bold" class="text-primary me-2 fs-5"></iconify-icon>
                                Reporte Rol Mensual Legible
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 small d-flex align-items-center" href="javascript:void(0)" onclick="exportarExcelImportable()">
                                <iconify-icon icon="solar:refresh-circle-bold" class="text-success me-2 fs-5"></iconify-icon>
                                Formato para Re-Importación (DNI/Días)
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item py-2 small d-flex align-items-center" href="javascript:void(0)" onclick="descargarPlantillaExcel()">
                                <iconify-icon icon="solar:download-square-bold" class="text-info me-2 fs-5"></iconify-icon>
                                Descargar Plantilla en Blanco
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-secondary btn-sm active px-3" id="btnModoMatriz" onclick="cambiarModoVista('matriz')">
                        <iconify-icon icon="solar:table-bold" class="me-1"></iconify-icon> Matriz
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3" id="btnModoCalendar" onclick="cambiarModoVista('calendar')">
                        <iconify-icon icon="solar:calendar-minimalistic-bold" class="me-1"></iconify-icon> Calendario
                    </button>
                </div>
            </div>
        </div>

        <hr class="my-3 opacity-10">

        <!-- SELECTORES DE PERIODO Y CONTEXTO -->
        <form id="formFiltroProgramacion" class="row g-3 align-items-end">
            <div class="col-md-2 col-sm-6">
                <label class="form-label small fw-bold text-muted mb-1">Año</label>
                <select class="form-select form-select-sm rounded-3 fw-bold" id="filtro_anio">
                    <?php for ($y = $anioActual - 1; $y <= $anioActual + 2; $y++): ?>
                        <option value="<?= $y ?>" <?= $y === $anioActual ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-2 col-sm-6">
                <label class="form-label small fw-bold text-muted mb-1">Mes</label>
                <select class="form-select form-select-sm rounded-3 fw-bold" id="filtro_mes">
                    <?php 
                    $meses = [
                        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                    ];
                    foreach ($meses as $num => $nombre): ?>
                        <option value="<?= $num ?>" <?= $num === $mesActual ? 'selected' : '' ?>><?= $nombre ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-bold text-muted mb-1">Establecimiento</label>
                <select class="form-select form-select-sm rounded-3" id="filtro_est_ide">
                    <option value="">Todos los Establecimientos</option>
                    <?php foreach ($establecimientos as $est): ?>
                        <option value="<?= esc($est['est_ide']) ?>"><?= esc($est['est_nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-bold text-muted mb-1">UPSS / Departamento</label>
                <select class="form-select form-select-sm rounded-3" id="filtro_ups_ide" onchange="cargarServiciosDeUpss(this.value)">
                    <option value="">Todas las UPSS</option>
                    <?php foreach ($upssList as $u): ?>
                        <option value="<?= esc($u['ups_ide']) ?>"><?= esc($u['ups_nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 col-sm-6">
                <label for="filtro_ofi_ide" class="form-label small fw-bold text-muted mb-1">Oficina</label>
                <select id="filtro_ofi_ide" class="form-select form-select-sm rounded-3">
                    <option value="">Todas las oficinas</option>
                    <?php foreach ($oficinas as $oficina): ?>
                        <option value="<?= (int) $oficina['ofi_ide'] ?>" data-est="<?= (int) $oficina['ofi_est_ide'] ?>"><?= esc($oficina['ofi_nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 col-sm-6">
                <label for="filtro_uss_ide" class="form-label small fw-bold text-muted mb-1">Servicio UPSS</label>
                <select id="filtro_uss_ide" class="form-select form-select-sm rounded-3">
                    <option value="">Todos los servicios</option>
                    <?php foreach ($serviciosUpss as $servicio): ?>
                        <option value="<?= (int) $servicio['uss_ide'] ?>" data-ups="<?= (int) $servicio['uss_ups_ide'] ?>"><?= esc($servicio['uss_nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 col-sm-6">
                <label for="filtro_dni" class="form-label small fw-bold text-muted mb-1">DNI / documento del trabajador</label>
                <input id="filtro_dni" class="form-control form-control-sm rounded-3" type="text" maxlength="15" placeholder="Número completo">
            </div>
            <div class="col-md-2 col-sm-12 d-grid">
                <button type="submit" class="btn btn-primary btn-sm rounded-pill" id="btnCargarMatriz">
                    <span class="spinner-border spinner-border-sm me-1 d-none" id="spnCargarMatriz"></span>
                    <iconify-icon icon="solar:magnifer-linear" class="me-1"></iconify-icon>
                    Consultar
                </button>
            </div>
        </form>
    </div>

    <!-- LEYENDA DE TURNOS DISPONIBLES -->
    <div class="card bg-white p-3 border-0 shadow-sm rounded-4 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="small fw-bold text-muted text-uppercase" style="letter-spacing: 0.5px;">
                <iconify-icon icon="solar:palette-bold" class="me-1 text-primary"></iconify-icon>
                Leyenda de Turnos Activos
            </span>
            <span class="text-muted small">Haz clic en cualquier turno para ver su horario y duración</span>
        </div>
        <div class="d-flex flex-wrap gap-2" id="contenedorLeyenda">
            <?php foreach ($catalogoTurnos as $t): ?>
                <?php 
                    $hTxt = !empty($t['horarios'][0]) ? ($t['horarios'][0]['th_hora_ingreso'] . '-' . $t['horarios'][0]['th_hora_salida'] . ' [' . $t['horarios'][0]['duracion_horas'] . 'h]') : 'Sin horario';
                ?>
                <div class="leyenda-item" title="<?= esc($t['tur_nombre']) ?>: <?= esc($hTxt) ?>">
                    <span class="leyenda-color" style="background-color: <?= esc($t['tur_color']) ?>;"></span>
                    <span class="fw-bold font-monospace text-dark"><?= esc($t['tur_codigo']) ?></span>
                    <span class="text-muted"><?= esc($t['tur_nombre']) ?> (<?= esc($hTxt) ?>)</span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- CONTENEDOR VISTA MATRIZ -->
    <div id="vistaMatriz" class="card bg-white p-3 border-0 shadow-sm rounded-4 mb-4">
        <div class="table-matriz-wrapper" id="wrapperMatriz">
            <div class="text-center py-5 text-muted" id="placeholderCarga">
                <div class="spinner-border text-primary mb-3" role="status"></div>
                <div>Cargando matriz de programación mensual...</div>
            </div>
            <table class="table-matriz w-100 d-none" id="tablaMatriz">
                <thead id="matrizThead"></thead>
                <tbody id="matrizTbody"></tbody>
            </table>
        </div>
    </div>

    <!-- CONTENEDOR VISTA FULLCALENDAR -->
    <div id="vistaCalendar" class="card bg-white p-4 border-0 shadow-sm rounded-4 mb-4 d-none">
        <div id="fullCalendarContainer" style="min-height: 650px;"></div>
    </div>

</div>

<!-- ========================================== -->
<!-- MODAL: ASIGNAR / EDITAR TURNO INDIVIDUAL   -->
<!-- ========================================== -->
<div class="modal fade" id="modalTurnoIndividual" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center">
                    <iconify-icon icon="solar:clock-circle-bold-duotone" class="text-primary me-2" style="font-size: 1.4rem;"></iconify-icon>
                    <span id="modalIndividualTitulo">Asignar Turno</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTurnoIndividual">
                <input type="hidden" id="ind_prog_ide" name="prog_ide">
                <input type="hidden" id="ind_perl_ide" name="prog_perl_ide">
                <input type="hidden" id="ind_fecha" name="prog_fecha">

                <div class="modal-body py-3">
                    <div class="card bg-light border-0 rounded-3 p-3 mb-3 small">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Trabajador:</span>
                            <strong class="text-dark" id="ind_trabajador_nombre">-</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Fecha asignada:</span>
                            <strong class="text-primary font-monospace" id="ind_fecha_texto">-</strong>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold">Turno y Horario *</label>
                            <select class="form-select rounded-3" id="ind_th_ide" name="prog_th_ide" required onchange="actualizarInfoTurnoSeleccionado(this.value)">
                                <option value="">Seleccione turno...</option>
                            </select>
                            <div class="form-text small mt-1" id="ind_info_turno"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Estado</label>
                            <select class="form-select rounded-3" id="ind_estado" name="prog_estado">
                                <option value="PROGRAMADO" selected>Programado</option>
                                <option value="CAMBIO TURNO">Cambio turno</option>
                                <option value="CONFIRMADO">Confirmado</option>
                                <option value="CAMBIO">Cambio Solicitado</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Observaciones (Opcional)</label>
                            <textarea class="form-control rounded-3" id="ind_observacion" name="prog_observacion" rows="2" placeholder="Nota o justificación..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 d-flex justify-content-between">
                    <div>
                        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3 d-none" id="btnEliminarIndividual" onclick="eliminarTurnoIndividual()">
                            <iconify-icon icon="solar:trash-bin-trash-bold" class="me-1"></iconify-icon>
                            Eliminar Turno
                        </button>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4" id="btnGuardarIndividual">
                            <span class="spinner-border spinner-border-sm me-1 d-none" id="spnGuardarIndividual"></span>
                            Guardar Asignación
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: IMPORTACIÓN MASIVA DESDE EXCEL      -->
<!-- ========================================== -->
<div class="modal fade" id="modalImportarExcel" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center">
                        <iconify-icon icon="solar:upload-track-bold" class="text-primary me-2" style="font-size: 1.5rem;"></iconify-icon>
                        Importación Masiva de Turnos desde Excel (.xlsx)
                    </h5>
                    <p class="text-muted small mb-0">Carga la programación de todo el personal mediante la plantilla estandarizada.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">

                <!-- PASO 1: SUBIR ARCHIVO -->
                <div id="seccionSubirArchivo" class="card bg-light border-0 rounded-4 p-4 mb-3">
                    <div class="row align-items-center g-3">
                        <div class="col-md-7">
                            <label class="form-label small fw-bold text-dark">Selecciona el archivo Excel (.xlsx)</label>
                            <input type="file" class="form-control rounded-3" id="archivo_excel" accept=".xlsx, .xls">
                            <div class="form-text small">Formato: 1 fila por trabajador (DNI, ANIO, MES, DIA_01 ... DIA_31)</div>
                        </div>
                        <div class="col-md-5 text-md-end">
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" onclick="descargarPlantillaExcel()">
                                <iconify-icon icon="solar:download-minimalistic-bold" class="me-1"></iconify-icon>
                                Descargar Plantilla en Blanco
                            </button>
                        </div>
                        <div class="col-12 mt-2">
                            <button type="button" class="btn btn-primary rounded-pill px-4" id="btnAnalizarExcel" onclick="analizarArchivoExcel()">
                                <span class="spinner-border spinner-border-sm me-1 d-none" id="spnAnalizarExcel"></span>
                                <iconify-icon icon="solar:magnifer-bold" class="me-1"></iconify-icon>
                                Analizar y Previsualizar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- PASO 2: RESULTADOS DE PREVISUALIZACIÓN -->
                <div id="seccionPrevisualizacion" class="d-none">

                    <!-- Tarjetas de Resumen -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-3 col-sm-6">
                            <div class="card bg-light border-0 rounded-3 p-3 text-center">
                                <span class="text-muted small">Trabajadores en Archivo</span>
                                <h4 class="fw-bold mb-0 text-dark" id="prev_total_filas">0</h4>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="card bg-light border-0 rounded-3 p-3 text-center">
                                <span class="text-muted small">Turnos a Cargar</span>
                                <h4 class="fw-bold mb-0 text-success" id="prev_total_turnos">0</h4>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="card bg-light border-0 rounded-3 p-3 text-center">
                                <span class="text-muted small">Ya Existentes en BD</span>
                                <h4 class="fw-bold mb-0 text-warning" id="prev_total_existentes">0</h4>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="card bg-light border-0 rounded-3 p-3 text-center">
                                <span class="text-muted small">Observaciones / Errores</span>
                                <h4 class="fw-bold mb-0 text-danger" id="prev_total_errores">0</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Pestañas de Detalle -->
                    <ul class="nav nav-tabs nav-tabs-custom mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active small fw-bold" data-bs-toggle="tab" href="#tabPrevValidos">
                                <iconify-icon icon="solar:check-circle-bold" class="text-success me-1"></iconify-icon>
                                Registros Válidos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link small fw-bold" data-bs-toggle="tab" href="#tabPrevErrores" id="tabLinkErrores">
                                <iconify-icon icon="solar:danger-triangle-bold" class="text-danger me-1"></iconify-icon>
                                Observaciones / Errores (<span id="prev_badge_errores">0</span>)
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content mb-3">
                        <!-- TAB: VÁLIDOS -->
                        <div class="tab-pane fade show active" id="tabPrevValidos">
                            <div class="table-responsive" style="max-height: 320px; overflow-y: auto;">
                                <table class="table table-sm table-hover align-middle table-bordered small">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th style="width: 50px;">Fila</th>
                                            <th style="width: 100px;">DNI</th>
                                            <th>Trabajador</th>
                                            <th>Turnos a Registrar en el Mes</th>
                                            <th style="width: 80px;" class="text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyPrevValidos"></tbody>
                                </table>
                            </div>
                        </div>

                        <!-- TAB: ERRORES -->
                        <div class="tab-pane fade" id="tabPrevErrores">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-danger small fw-bold">Revise las siguientes incidencias antes de proceder:</span>
                                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="descargarReporteErrores()">
                                    <iconify-icon icon="solar:file-excel-bold" class="me-1"></iconify-icon>
                                    Descargar Reporte de Errores (.xlsx)
                                </button>
                            </div>
                            <div class="table-responsive" style="max-height: 320px; overflow-y: auto;">
                                <table class="table table-sm table-hover align-middle table-bordered small">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th style="width: 50px;">Fila</th>
                                            <th style="width: 90px;">DNI</th>
                                            <th>Trabajador</th>
                                            <th style="width: 100px;">Día / Celda</th>
                                            <th style="width: 90px;">Valor</th>
                                            <th>Descripción de la Observación / Error</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyPrevErrores"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- OPCIÓN DE SOBREESCRITURA -->
                    <div class="card bg-light border-0 rounded-3 p-3">
                        <div class="form-check form-switch mb-1">
                            <input class="form-check-input" type="checkbox" id="check_reemplazar_existentes">
                            <label class="form-check-label fw-bold text-dark small" for="check_reemplazar_existentes">
                                Reemplazar la programación existente en los días que contengan nuevos turnos en el Excel
                            </label>
                        </div>
                        <div class="form-text small text-muted">
                            Si esta opción está desactivada, el sistema conservará los turnos ya existentes y solo llenará los días libres.
                            <strong>Nota:</strong> Las celdas vacías del archivo nunca borrarán información existente.
                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success btn-sm rounded-pill px-4 d-none" id="btnConfirmarImportacion" onclick="aplicarImportacionDefinitiva()">
                    <span class="spinner-border spinner-border-sm me-1 d-none" id="spnConfirmarImportacion"></span>
                    <iconify-icon icon="solar:check-read-bold" class="me-1"></iconify-icon>
                    Confirmar y Aplicar Importación
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script>
    let catalogoTurnos = <?= json_encode($catalogoTurnos) ?>;
    let matrizActualData = null;
    let calendarInstance = null;
    let modoVista = 'matriz';
    let csrfToken = '<?= csrf_token() ?>';
    let csrfHash  = '<?= csrf_hash() ?>';

    $(document).ready(function () {
        cargarMatrizMensual();

        $('#formFiltroProgramacion').on('submit', function (e) {
            e.preventDefault();
            cargarMatrizMensual();
        });

        $('#formTurnoIndividual').on('submit', function (e) {
            e.preventDefault();
            guardarTurnoIndividual();
        });

        poblarSelectTurnos();
    });

    function poblarSelectTurnos() {
        let options = '<option value="">Seleccione turno...</option>';
        catalogoTurnos.forEach(function (t) {
            if (t.horarios && t.horarios.length > 0) {
                t.horarios.forEach(function (h) {
                    options += `<option value="${h.th_ide}" data-tur="${t.tur_codigo}" data-horas="${h.duracion_horas}" data-color="${t.tur_color}">
                        ${t.tur_codigo} - ${t.tur_nombre} (${h.th_hora_ingreso} a ${h.th_hora_salida} - ${h.duracion_horas}h)
                    </option>`;
                });
            }
        });
        $('#ind_th_ide').html(options);
    }

    function actualizarInfoTurnoSeleccionado(thIde) {
        let opt = $('#ind_th_ide option:selected');
        if (thIde && opt.length) {
            let horas = opt.data('horas');
            let color = opt.data('color');
            $('#ind_info_turno').html(`<span class="badge" style="background-color: ${color}">${opt.data('tur')}</span> Duración: <strong>${horas} horas</strong>.`);
        } else {
            $('#ind_info_turno').html('');
        }
    }

    function cargarServiciosDeUpss(upsIde) {
        $('#filtro_uss_ide').val('').find('option[data-ups]').each(function () {
            const visible = !upsIde || String($(this).data('ups')) === String(upsIde);
            $(this).prop('hidden', !visible).prop('disabled', !visible);
        });
    }

    $('#filtro_est_ide').on('change', function () {
        const est = $(this).val();
        $('#filtro_ofi_ide').val('').find('option[data-est]').each(function () {
            const visible = !est || String($(this).data('est')) === String(est);
            $(this).prop('hidden', !visible).prop('disabled', !visible);
        });
    });

    function filtrosProgramacion() {
        return { anio: $('#filtro_anio').val(), mes: $('#filtro_mes').val(),
            est_ide: $('#filtro_est_ide').val(), ofi_ide: $('#filtro_ofi_ide').val(),
            ups_ide: $('#filtro_ups_ide').val(), uss_ide: $('#filtro_uss_ide').val(),
            dni: $('#filtro_dni').val().trim() };
    }

    function cargarMatrizMensual() {
        let anio    = $('#filtro_anio').val();
        let mes     = $('#filtro_mes').val();
        let estIde  = $('#filtro_est_ide').val();
        let upsIde  = $('#filtro_ups_ide').val();

        $('#placeholderCarga').removeClass('d-none');
        $('#tablaMatriz').addClass('d-none');
        $('#spnCargarMatriz').removeClass('d-none');
        $('#btnCargarMatriz').prop('disabled', true);

        $.ajax({
            url: '<?= base_url('asistencia/programacion/api/matriz') ?>',
            type: 'GET',
            data: filtrosProgramacion(),
            dataType: 'json',
            success: function (res) {
                $('#spnCargarMatriz').addClass('d-none');
                $('#btnCargarMatriz').prop('disabled', false);

                if (res.status === 'success' && res.data) {
                    matrizActualData = res.data;
                    renderizarMatriz(res.data);
                    if (modoVista === 'calendar' && calendarInstance) {
                        calendarInstance.refetchEvents();
                    }
                } else {
                    $('#placeholderCarga').html('<div class="text-danger">Error al cargar la programación.</div>');
                }
            },
            error: function () {
                $('#spnCargarMatriz').addClass('d-none');
                $('#btnCargarMatriz').prop('disabled', false);
                $('#placeholderCarga').html('<div class="text-danger">Error de comunicación con el servidor.</div>');
            }
        });
    }

    function renderizarMatriz(data) {
        let dias = data.dias;
        let filas = data.matriz;

        if (!filas || filas.length === 0) {
            $('#placeholderCarga').removeClass('d-none').html('<div class="text-muted py-5"><iconify-icon icon="solar:users-group-rounded-linear" class="fs-1 d-block mb-2 text-secondary"></iconify-icon>No hay personal registrado en este establecimiento o filtro.</div>');
            $('#tablaMatriz').addClass('d-none');
            return;
        }

        // 1. Render Thead
        let theadHtml = '<tr>';
        theadHtml += '<th class="sticky-col-1">N°</th>';
        theadHtml += '<th class="sticky-col-2">DNI</th>';
        theadHtml += '<th class="sticky-col-3">Apellidos y Nombres</th>';

        dias.forEach(function (d) {
            let clsFds = d.es_fin_de_semana ? 'fin-de-semana' : '';
            theadHtml += `<th class="${clsFds}" style="min-width: 38px;">
                <div style="font-size: 0.65rem; opacity: 0.8;">${d.letra}</div>
                <div class="fw-bold">${d.dia}</div>
            </th>`;
        });

        theadHtml += '<th style="min-width: 80px;" class="fw-bold bg-dark text-white">Total Horas</th>';
        theadHtml += '</tr>';
        $('#matrizThead').html(theadHtml);

        // 2. Render Tbody
        let tbodyHtml = '';
        let num = 1;

        filas.forEach(function (f) {
            tbodyHtml += '<tr>';
            tbodyHtml += `<td class="sticky-col-1 text-muted fw-bold">${num}</td>`;
            tbodyHtml += `<td class="sticky-col-2 font-monospace text-center small">${f.dni || '-'}</td>`;
            tbodyHtml += `<td class="sticky-col-3">
                <div class="fw-semibold text-dark text-truncate" style="max-width: 190px;" title="${f.trabajador}">${f.trabajador}</div>
                <div class="text-muted" style="font-size: 0.7rem;">${f.cargo || ''}</div>
            </td>`;

            dias.forEach(function (d) {
                let diaNum = d.dia;
                let turnos = f.dias[diaNum] || [];
                let clsFds = d.es_fin_de_semana ? 'fin-de-semana' : '';
                let fechaIso = `${data.anio}-${String(data.mes).padStart(2, '0')}-${String(diaNum).padStart(2, '0')}`;

                tbodyHtml += `<td class="celda-turno ${clsFds}" onclick="clickCeldaTurno(${f.perl_ide}, '${fechaIso}', '${escapeHtml(f.trabajador)}', ${JSON.stringify(turnos).replace(/"/g, '&quot;')})">`;

                if (turnos.length > 0) {
                    turnos.forEach(function (t) {
                        let c = t.tur_color || '#3b82f6';
                        tbodyHtml += `<span class="badge-turno-matriz" style="background-color: ${c}" title="${t.tur_codigo}: ${t.tur_nombre} (${t.th_hora_ingreso}-${t.th_hora_salida} - ${t.duracion_horas}h)">${t.tur_codigo}</span>`;
                        if (t.prog_estado === 'CAMBIO TURNO') tbodyHtml += '<small class="d-block text-info fw-bold">CAMBIO TURNO</small>';
                    });
                } else {
                    tbodyHtml += `<span class="btn-asignar-vacio"><iconify-icon icon="lucide:plus"></iconify-icon></span>`;
                }

                tbodyHtml += '</td>';
            });

            // Total horas mensual
            tbodyHtml += `<td class="fw-bold font-monospace text-dark text-center bg-light">
                <span class="badge bg-primary text-white" style="font-size: 0.78rem;">${f.total_horas}h</span>
            </td>`;

            tbodyHtml += '</tr>';
            num++;
        });

        $('#matrizTbody').html(tbodyHtml);
        $('#placeholderCarga').addClass('d-none');
        $('#tablaMatriz').removeClass('d-none');
    }

    function clickCeldaTurno(perlIde, fecha, trabajador, turnos) {
        $('#ind_perl_ide').val(perlIde);
        $('#ind_fecha').val(fecha);
        $('#ind_trabajador_nombre').text(trabajador);
        $('#ind_fecha_texto').text(fecha);

        if (turnos && turnos.length > 0) {
            let t = turnos[0]; // Editar turno existente
            $('#modalIndividualTitulo').text('Editar Turno Asignado');
            $('#ind_prog_ide').val(t.prog_ide);
            $('#ind_th_ide').val(t.th_ide);
            $('#ind_estado').val(t.prog_estado || 'PROGRAMADO');
            $('#ind_observacion').val(t.prog_observacion || '');
            $('#btnEliminarIndividual').removeClass('d-none');
            actualizarInfoTurnoSeleccionado(t.th_ide);
        } else {
            // Asignación nueva
            $('#modalIndividualTitulo').text('Asignar Turno');
            $('#ind_prog_ide').val('');
            $('#ind_th_ide').val('');
            $('#ind_estado').val('PROGRAMADO');
            $('#ind_observacion').val('');
            $('#btnEliminarIndividual').addClass('d-none');
            $('#ind_info_turno').html('');
        }

        $('#modalTurnoIndividual').modal('show');
    }

    function guardarTurnoIndividual() {
        let btn = $('#btnGuardarIndividual');
        let spn = $('#spnGuardarIndividual');

        btn.prop('disabled', true);
        spn.removeClass('d-none');

        let postData = {
            prog_ide:      $('#ind_prog_ide').val(),
            prog_perl_ide: $('#ind_perl_ide').val(),
            prog_fecha:    $('#ind_fecha').val(),
            prog_th_ide:   $('#ind_th_ide').val(),
            prog_estado:   $('#ind_estado').val(),
            prog_observacion: $('#ind_observacion').val(),
            est_ide:       $('#filtro_est_ide').val(),
            ups_ide:       $('#filtro_ups_ide').val(),
            [csrfToken]:   csrfHash
        };

        $.ajax({
            url: '<?= base_url('asistencia/programacion/api/guardar') ?>',
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function (res) {
                btn.prop('disabled', false);
                spn.addClass('d-none');

                if (res.status === 'success') {
                    $('#modalTurnoIndividual').modal('hide');
                    toastr.success(res.message);
                    cargarMatrizMensual();
                } else {
                    Swal.fire({ icon: 'error', title: 'Observación de Horario', text: res.message });
                }
            },
            error: function (xhr) {
                btn.prop('disabled', false);
                spn.addClass('d-none');
                let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error al guardar la asignación.';
                Swal.fire({ icon: 'error', title: 'Error', text: msg });
            }
        });
    }

    function eliminarTurnoIndividual() {
        let progIde = $('#ind_prog_ide').val();
        if (!progIde) return;

        Swal.fire({
            title: '¿Eliminar turno?',
            text: 'Se removerá esta asignación de la programación mensual.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, Eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#ef4444'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('asistencia/programacion/api/eliminar') ?>/' + progIde,
                    type: 'POST',
                    data: { [csrfToken]: csrfHash },
                    dataType: 'json',
                    success: function (res) {
                        if (res.status === 'success') {
                            $('#modalTurnoIndividual').modal('hide');
                            toastr.success(res.message);
                            cargarMatrizMensual();
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error', text: res.message });
                        }
                    },
                    error: function (xhr) {
                        let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error al eliminar el turno.';
                        Swal.fire({ icon: 'error', title: 'Error', text: msg });
                    }
                });
            }
        });
    }

    // ==========================================
    // CAMBIO DE VISTA (MATRIZ vs FULLCALENDAR)
    // ==========================================
    function cambiarModoVista(modo) {
        modoVista = modo;
        if (modo === 'matriz') {
            $('#btnModoMatriz').addClass('active');
            $('#btnModoCalendar').removeClass('active');
            $('#vistaMatriz').removeClass('d-none');
            $('#vistaCalendar').addClass('d-none');
        } else {
            $('#btnModoCalendar').addClass('active');
            $('#btnModoMatriz').removeClass('active');
            $('#vistaMatriz').addClass('d-none');
            $('#vistaCalendar').removeClass('d-none');
            inicializarFullCalendar();
        }
    }

    function inicializarFullCalendar() {
        let calendarEl = document.getElementById('fullCalendarContainer');
        if (calendarInstance) {
            calendarInstance.render();
            return;
        }

        let anio = $('#filtro_anio').val();
        let mes  = String($('#filtro_mes').val()).padStart(2, '0');

        calendarInstance = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            initialDate: `${anio}-${mes}-01`,
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listMonth'
            },
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week:  'Semana',
                list:  'Lista'
            },
            events: function (fetchInfo, successCallback, failureCallback) {
                $.ajax({
                    url: '<?= base_url('asistencia/programacion/api/eventos') ?>',
                    type: 'GET',
                    data: filtrosProgramacion(),
                    dataType: 'json',
                    success: function (data) {
                        successCallback(data);
                    },
                    error: function () {
                        failureCallback();
                    }
                });
            },
            eventClick: function (info) {
                let props = info.event.extendedProps;
                let fecha = info.event.startStr.split('T')[0];
                let turnos = [{
                    prog_ide:        props.prog_ide,
                    tur_codigo:      props.tur_codigo,
                    tur_nombre:      props.tur_nombre,
                    duracion_horas:  props.duracion_horas
                }];
                clickCeldaTurno(props.perl_ide, fecha, props.trabajador, turnos);
            }
        });

        calendarInstance.render();
    }

    // ==========================================
    // EXPORTACIONES Y PLANTILLA
    // ==========================================
    function descargarPlantillaExcel() {
        let anio   = $('#filtro_anio').val();
        let mes    = $('#filtro_mes').val();
        let estIde = $('#filtro_est_ide').val();
        window.location.href = `<?= base_url('asistencia/programacion/descargar-plantilla') ?>?anio=${anio}&mes=${mes}&est_ide=${estIde}`;
    }

    function exportarExcelReporte() {
        let anio   = $('#filtro_anio').val();
        let mes    = $('#filtro_mes').val();
        let estIde = $('#filtro_est_ide').val();
        let upsIde = $('#filtro_ups_ide').val();
        window.location.href = `<?= base_url('asistencia/programacion/exportar-excel-reporte') ?>?${new URLSearchParams(filtrosProgramacion()).toString()}`;
    }

    function exportarExcelImportable() {
        let anio   = $('#filtro_anio').val();
        let mes    = $('#filtro_mes').val();
        let estIde = $('#filtro_est_ide').val();
        let upsIde = $('#filtro_ups_ide').val();
        window.location.href = `<?= base_url('asistencia/programacion/exportar-excel-importable') ?>?${new URLSearchParams(filtrosProgramacion()).toString()}`;
    }

    // ==========================================
    // IMPORTACIÓN MASIVA EXCEL
    // ==========================================
    function abrirModalImportar() {
        $('#archivo_excel').val('');
        $('#seccionPrevisualizacion').addClass('d-none');
        $('#btnConfirmarImportacion').addClass('d-none');
        $('#modalImportarExcel').modal('show');
    }

    function analizarArchivoExcel() {
        let fileInput = document.getElementById('archivo_excel');
        if (!fileInput.files.length) {
            Swal.fire({ icon: 'warning', title: 'Atención', text: 'Debe seleccionar un archivo Excel (.xlsx) para continuar.' });
            return;
        }

        let formData = new FormData();
        formData.append('archivo_excel', fileInput.files[0]);
        formData.append('anio', $('#filtro_anio').val());
        formData.append('mes',  $('#filtro_mes').val());
        formData.append('est_ide', $('#filtro_est_ide').val());
        formData.append(csrfToken, csrfHash);

        let btn = $('#btnAnalizarExcel');
        let spn = $('#spnAnalizarExcel');
        btn.prop('disabled', true);
        spn.removeClass('d-none');

        $.ajax({
            url: '<?= base_url('asistencia/programacion/api/previsualizar-excel') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (res) {
                btn.prop('disabled', false);
                spn.addClass('d-none');

                if (res.status === 'success' && res.data) {
                    mostrarPrevisualizacion(res.data);
                } else {
                    Swal.fire({ icon: 'error', title: 'Error en el Archivo', text: res.message });
                }
            },
            error: function (xhr) {
                btn.prop('disabled', false);
                spn.addClass('d-none');
                let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error al procesar el archivo Excel.';
                Swal.fire({ icon: 'error', title: 'Error', text: msg });
            }
        });
    }

    function mostrarPrevisualizacion(data) {
        $('#prev_total_filas').text(data.total_filas);
        $('#prev_total_turnos').text(data.total_turnos);
        $('#prev_total_existentes').text(data.total_existentes);
        $('#prev_total_errores').text(data.total_errores);
        $('#prev_badge_errores').text(data.total_errores);

        // Render Válidos
        let tbodyValidos = '';
        if (data.registros_validos && data.registros_validos.length > 0) {
            data.registros_validos.forEach(function (v) {
                let turnosBadge = '';
                for (const dia in v.turnos) {
                    let t = v.turnos[dia];
                    let c = t.tur_color || '#3b82f6';
                    let clsBorder = t.ya_existe ? 'border border-warning' : '';
                    turnosBadge += `<span class="badge ${clsBorder} me-1 mb-1" style="background-color: ${c}" title="Día ${t.dia}: ${t.tur_codigo} - ${t.tur_nombre} (${t.duracion_horas}h)">d${t.dia}:${t.tur_codigo}</span>`;
                }
                tbodyValidos += `
                    <tr>
                        <td class="text-center text-muted">${v.fila}</td>
                        <td class="font-monospace text-center">${v.dni}</td>
                        <td class="fw-semibold text-dark">${v.trabajador}</td>
                        <td>${turnosBadge}</td>
                        <td class="text-center fw-bold text-success">${v.total_turnos}</td>
                    </tr>
                `;
            });
        } else {
            tbodyValidos = '<tr><td colspan="5" class="text-center py-3 text-muted">No se detectaron asignaciones válidas.</td></tr>';
        }
        $('#tbodyPrevValidos').html(tbodyValidos);

        // Render Errores
        let tbodyErrores = '';
        if (data.errores && data.errores.length > 0) {
            data.errores.forEach(function (err) {
                tbodyErrores += `
                    <tr>
                        <td class="text-center text-danger fw-bold">${err.fila}</td>
                        <td class="font-monospace text-center">${err.dni}</td>
                        <td>${err.trabajador}</td>
                        <td class="text-center fw-bold">${err.dia}</td>
                        <td class="text-center font-monospace">${err.codigo}</td>
                        <td class="text-danger small">${err.error}</td>
                    </tr>
                `;
            });
            $('#tabLinkErrores').addClass('text-danger fw-bold');
        } else {
            tbodyErrores = '<tr><td colspan="6" class="text-center py-3 text-success">¡No se encontraron observaciones ni errores! Todo el archivo es válido.</td></tr>';
            $('#tabLinkErrores').removeClass('text-danger');
        }
        $('#tbodyPrevErrores').html(tbodyErrores);

        $('#seccionPrevisualizacion').removeClass('d-none');

        if (data.registros_validos && data.registros_validos.length > 0) {
            $('#btnConfirmarImportacion').removeClass('d-none');
        } else {
            $('#btnConfirmarImportacion').addClass('d-none');
        }
    }

    function descargarReporteErrores() {
        window.location.href = '<?= base_url('asistencia/programacion/descargar-reporte-errores') ?>';
    }

    function aplicarImportacionDefinitiva() {
        let reemplazar = $('#check_reemplazar_existentes').is(':checked') ? 1 : 0;
        let advertencia = reemplazar
            ? 'Se sobreescribirán los turnos existentes en las fechas especificadas en el archivo.'
            : 'Se conservarán los turnos existentes y solo se insertarán turnos en días libres.';

        Swal.fire({
            title: '¿Confirmar Importación?',
            text: advertencia,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, Aplicar Importación',
            cancelButtonText: 'Revisar más',
            confirmButtonColor: '#10b981'
        }).then((result) => {
            if (result.isConfirmed) {
                let btn = $('#btnConfirmarImportacion');
                let spn = $('#spnConfirmarImportacion');
                btn.prop('disabled', true);
                spn.removeClass('d-none');

                $.ajax({
                    url: '<?= base_url('asistencia/programacion/api/procesar-importacion') ?>',
                    type: 'POST',
                    data: {
                        reemplazar_existentes: reemplazar,
                        est_ide: $('#filtro_est_ide').val(),
                        ups_ide: $('#filtro_ups_ide').val(),
                        [csrfToken]: csrfHash
                    },
                    dataType: 'json',
                    success: function (res) {
                        btn.prop('disabled', false);
                        spn.addClass('d-none');

                        if (res.status === 'success') {
                            $('#modalImportarExcel').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: '¡Importación Exitosa!',
                                text: res.message
                            });
                            cargarMatrizMensual();
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error', text: res.message });
                        }
                    },
                    error: function (xhr) {
                        btn.prop('disabled', false);
                        spn.addClass('d-none');
                        let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error en el servidor al importar.';
                        Swal.fire({ icon: 'error', title: 'Error', text: msg });
                    }
                });
            }
        });
    }

    function escapeHtml(text) {
        if (!text) return '';
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
</script>
<?= $this->endSection() ?>
