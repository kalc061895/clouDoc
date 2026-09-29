<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?>
Registros de Asistencia y Marcaciones Reales
<?= $this->endSection() ?>

<?= $this->section('pageStyles') ?>
<style>
    .badge-turno {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #ffffff;
    }
    .badge-sin-turno {
        background-color: #f1f5f9;
        color: #64748b;
        border: 1px dashed #cbd5e1;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.72rem;
    }
    .table-detalle-asistencia th {
        background-color: #f8fafc;
        font-size: 0.8rem;
        text-transform: uppercase;
        color: #475569;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid px-0">

    <!-- CABECERA -->
    <div class="card bg-white p-4 border-0 shadow-sm rounded-4 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                    <iconify-icon icon="solar:user-check-rounded-bold-duotone" class="me-2 text-primary" style="font-size: 1.8rem;"></iconify-icon>
                    Registros de Asistencia y Marcaciones
                </h4>
                <p class="text-muted mb-0 small">
                    Consulta y audita las marcaciones biométricas y reales del personal, contrastadas con sus turnos programados.
                </p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button type="button" class="btn btn-outline-success btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="exportarExcel()">
                    <iconify-icon icon="solar:file-excel-bold" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
                    Exportar Excel
                </button>
                <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="abrirModalManual()">
                    <iconify-icon icon="lucide:plus-circle" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
                    Marcación Manual
                </button>
            </div>
        </div>

        <hr class="my-3 opacity-10">

        <!-- FILTROS DE BÚSQUEDA -->
        <form id="formFiltros" class="row g-3 align-items-end">
            <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-bold text-muted mb-1">Fecha Inicio</label>
                <input type="date" class="form-control form-control-sm rounded-3" id="filtro_fecha_inicio" value="<?= esc($fechaInicioDef) ?>">
            </div>
            <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-bold text-muted mb-1">Fecha Fin</label>
                <input type="date" class="form-control form-control-sm rounded-3" id="filtro_fecha_fin" value="<?= esc($fechaFinDef) ?>">
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
                <label class="form-label small fw-bold text-muted mb-1">Tipo de Marcación</label>
                <select class="form-select form-select-sm rounded-3" id="filtro_tipo">
                    <option value="">Todos los tipos</option>
                    <option value="ENTRADA">Entrada</option>
                    <option value="SALIDA">Salida</option>
                    <option value="REFRIGERIO_SALIDA">Refrigerio Salida</option>
                    <option value="REFRIGERIO_RETORNO">Refrigerio Retorno</option>
                    <option value="OTRO">Otro / Manual</option>
                </select>
            </div>
            <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-bold text-muted mb-1">Origen / Dispositivo</label>
                <select class="form-select form-select-sm rounded-3" id="filtro_origen">
                    <option value="">Todos los orígenes</option>
                    <option value="IMPORTACION">Importación Biométrico</option>
                    <option value="MANUAL">Registro Manual</option>
                    <option value="API">API / Reloj Virtual</option>
                </select>
            </div>
            <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-bold text-muted mb-1">DNI del Trabajador</label>
                <input type="text" class="form-control form-control-sm rounded-3" id="filtro_dni" placeholder="Buscar por DNI...">
            </div>
            <div class="col-md-6 col-sm-12 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-light btn-sm rounded-pill px-3" onclick="limpiarFiltros()">
                    <iconify-icon icon="solar:restart-bold" class="me-1"></iconify-icon>
                    Limpiar
                </button>
                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4">
                    <iconify-icon icon="solar:magnifer-linear" class="me-1"></iconify-icon>
                    Filtrar Marcaciones
                </button>
            </div>
        </form>
    </div>

    <!-- TABLA PRINCIPAL DE MARCACIONES -->
    <div class="card bg-white p-4 border-0 shadow-sm rounded-4">
        <div class="table-responsive">
            <table id="tablaMarcaciones" class="table table-hover align-middle w-100">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th style="width: 130px;">Fecha y Hora</th>
                        <th style="width: 90px;">DNI</th>
                        <th>Trabajador</th>
                        <th>Establecimiento</th>
                        <th style="width: 120px;">Dispositivo</th>
                        <th style="width: 100px;">Origen</th>
                        <th style="width: 110px;">Tipo</th>
                        <th>Turno Programado</th>
                        <th class="text-end" style="width: 110px;">Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: REGISTRO MANUAL DE MARCACIÓN        -->
<!-- ========================================== -->
<div class="modal fade" id="modalManual" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold d-flex align-items-center text-dark">
                    <iconify-icon icon="solar:pen-new-square-bold-duotone" class="text-primary me-2" style="font-size: 1.4rem;"></iconify-icon>
                    Registro Manual de Marcación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formManual">
                <div class="modal-body py-3">
                    <div class="alert alert-info py-2 px-3 small border-0 rounded-3 mb-3 d-flex align-items-center">
                        <iconify-icon icon="solar:info-circle-bold" class="fs-5 me-2 flex-shrink-0"></iconify-icon>
                        <div>Esta operación queda registrada bajo auditoría institucional con motivo obligatorio.</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold">Trabajador *</label>
                            <select class="form-select select2-modal" id="manual_perl_ide" name="asi_perl_ide" required style="width: 100%;">
                                <option value="">Seleccione trabajador...</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Fecha y Hora *</label>
                            <input type="datetime-local" class="form-control rounded-3" id="manual_fecha_hora" name="asi_fecha_hora" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tipo de Marcación *</label>
                            <select class="form-select rounded-3" id="manual_tipo" name="asi_tipo" required>
                                <option value="ENTRADA">Entrada</option>
                                <option value="SALIDA">Salida</option>
                                <option value="REFRIGERIO_SALIDA">Refrigerio Salida</option>
                                <option value="REFRIGERIO_RETORNO">Refrigerio Retorno</option>
                                <option value="OTRO">Otro / Justificado</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Motivo / Justificación *</label>
                            <textarea class="form-control rounded-3" id="manual_motivo" name="asi_motivo" rows="3" required minlength="5" maxlength="255" placeholder="Indique la justificación formal (ej. Falla de sensor biométrico, comisión de servicio autorizada, etc.)..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4" id="btnGuardarManual">
                        <span class="spinner-border spinner-border-sm me-1 d-none" id="spnGuardarManual"></span>
                        Registrar Marcación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: CORREGIR MARCACIÓN                  -->
<!-- ========================================== -->
<div class="modal fade" id="modalCorregir" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold d-flex align-items-center text-dark">
                    <iconify-icon icon="solar:pen-bold" class="text-warning me-2" style="font-size: 1.4rem;"></iconify-icon>
                    Corregir Marcación de Asistencia
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formCorregir">
                <input type="hidden" id="corregir_asi_ide">
                <div class="modal-body py-3">
                    <div class="mb-2">
                        <span class="text-muted small">Trabajador:</span>
                        <strong class="text-dark small d-block" id="corregir_nombre_trabajador"></strong>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Fecha y Hora *</label>
                            <input type="datetime-local" class="form-control rounded-3" id="corregir_fecha_hora" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tipo de Marcación *</label>
                            <select class="form-select rounded-3" id="corregir_tipo" required>
                                <option value="ENTRADA">Entrada</option>
                                <option value="SALIDA">Salida</option>
                                <option value="REFRIGERIO_SALIDA">Refrigerio Salida</option>
                                <option value="REFRIGERIO_RETORNO">Refrigerio Retorno</option>
                                <option value="OTRO">Otro</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Motivo de Corrección *</label>
                            <textarea class="form-control rounded-3" id="corregir_motivo" rows="3" required minlength="5" maxlength="255" placeholder="Explique la causa de la modificación para el historial de auditoría..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning btn-sm rounded-pill px-4 text-dark fw-bold" id="btnGuardarCorregir">
                        <span class="spinner-border spinner-border-sm me-1 d-none" id="spnGuardarCorregir"></span>
                        Guardar Corrección
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: DETALLE DE TRABAJADOR Y CONTRASTE   -->
<!-- ========================================== -->
<div class="modal fade" id="modalDetalleTrabajador" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center">
                        <iconify-icon icon="solar:calendar-date-bold-duotone" class="text-primary me-2" style="font-size: 1.5rem;"></iconify-icon>
                        Historial y Contraste de Asistencia vs Programación
                    </h5>
                    <p class="text-muted small mb-0" id="detalle_subtitulo"></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <!-- Ficha del trabajador -->
                <div class="card bg-light border-0 rounded-3 p-3 mb-3">
                    <div class="row g-2 small">
                        <div class="col-md-4">
                            <span class="text-muted">Trabajador:</span>
                            <div class="fw-bold text-dark" id="det_trabajador_nombre">-</div>
                        </div>
                        <div class="col-md-2">
                            <span class="text-muted">DNI:</span>
                            <div class="fw-bold text-dark" id="det_trabajador_dni">-</div>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted">Cargo:</span>
                            <div class="fw-bold text-dark" id="det_trabajador_cargo">-</div>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted">Establecimiento:</span>
                            <div class="fw-bold text-dark" id="det_trabajador_est">-</div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Contraste Día por Día -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle table-detalle-asistencia w-100">
                        <thead>
                            <tr>
                                <th style="width: 120px;">Fecha</th>
                                <th style="width: 90px;">Día</th>
                                <th>Turno Programado</th>
                                <th>Horario Oficial</th>
                                <th>Marcaciones Reales</th>
                                <th style="width: 140px;">Estado Contraste</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyDetalleDias"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script>
    let tablaMarcaciones = null;
    let csrfToken = '<?= csrf_token() ?>';
    let csrfHash = '<?= csrf_hash() ?>';

    $(document).ready(function () {
        cargarSelectTrabajadores();
        inicializarDataTable();

        $('#formFiltros').on('submit', function (e) {
            e.preventDefault();
            tablaMarcaciones.ajax.reload();
        });

        // Enviar formulario manual
        $('#formManual').on('submit', function (e) {
            e.preventDefault();
            guardarMarcacionManual();
        });

        // Enviar corrección
        $('#formCorregir').on('submit', function (e) {
            e.preventDefault();
            guardarCorreccion();
        });
    });

    function cargarSelectTrabajadores() {
        $.ajax({
            url: '<?= base_url('asistencia/personal/api/listar') ?>',
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                if (res.status === 'success' && res.data) {
                    let options = '<option value="">Seleccione trabajador...</option>';
                    res.data.forEach(function (t) {
                        let dni = t.per_numero_documento || t.perl_codigo || '';
                        let nombre = (t.per_paterno || '') + ' ' + (t.per_materno || '') + ' ' + (t.per_nombre || '');
                        options += `<option value="${t.perl_ide}">${dni} - ${nombre.trim()}</option>`;
                    });
                    $('#manual_perl_ide').html(options);
                }
            }
        });
    }

    function inicializarDataTable() {
        tablaMarcaciones = $('#tablaMarcaciones').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            pageLength: 25,
            order: [[1, 'desc']], // asi_fecha_hora
            ajax: {
                url: '<?= base_url('asistencia/asistencia/api/listar') ?>',
                type: 'GET',
                data: function (d) {
                    d.fecha_inicio = $('#filtro_fecha_inicio').val();
                    d.fecha_fin    = $('#filtro_fecha_fin').val();
                    d.est_ide      = $('#filtro_est_ide').val();
                    d.tipo         = $('#filtro_tipo').val();
                    d.origen       = $('#filtro_origen').val();
                    d.dni          = $('#filtro_dni').val();
                },
                dataSrc: function (json) {
                    if (json.csrf_hash) {
                        csrfHash = json.csrf_hash;
                    }
                    return json.data;
                }
            },
            columns: [
                { data: 'asi_ide', className: 'text-center text-muted small' },
                {
                    data: 'asi_fecha_hora',
                    render: function (data) {
                        if (!data) return '-';
                        let f = data.split(' ');
                        return `<span class="fw-bold text-dark">${f[0]}</span> <span class="badge bg-light text-dark font-monospace">${f[1]}</span>`;
                    }
                },
                {
                    data: 'per_numero_documento',
                    className: 'text-center font-monospace small',
                    render: function (data, type, row) {
                        return data || row.asi_numero_documento || '-';
                    }
                },
                {
                    data: 'per_paterno',
                    render: function (data, type, row) {
                        let nom = ((row.per_paterno || '') + ' ' + (row.per_materno || '') + ' ' + (row.per_nombre || '')).trim();
                        let cargo = row.car_nombre ? `<div class="text-muted" style="font-size: 0.72rem;">${row.car_nombre}</div>` : '';
                        return `<div class="fw-semibold text-dark">${nom || 'Personal ID #' + row.asi_perl_ide}</div>${cargo}`;
                    }
                },
                {
                    data: 'est_nombre',
                    render: function (data) {
                        return data ? `<span class="small">${data}</span>` : '<span class="text-muted small">-</span>';
                    }
                },
                {
                    data: 'asi_dispositivo',
                    className: 'text-center',
                    render: function (data) {
                        return `<span class="badge bg-light text-secondary border font-monospace" style="font-size: 0.72rem;">${data || 'N/A'}</span>`;
                    }
                },
                {
                    data: 'asi_origen',
                    className: 'text-center',
                    render: function (data) {
                        let badgeClass = data === 'MANUAL' ? 'bg-warning text-dark' : (data === 'API' ? 'bg-info text-white' : 'bg-primary text-white');
                        return `<span class="badge ${badgeClass}" style="font-size: 0.7rem;">${data || 'IMPORTACION'}</span>`;
                    }
                },
                {
                    data: 'asi_tipo',
                    className: 'text-center',
                    render: function (data) {
                        if (!data) return '<span class="text-muted small">-</span>';
                        let color = 'secondary';
                        if (data === 'ENTRADA') color = 'success';
                        if (data === 'SALIDA') color = 'danger';
                        if (data.includes('REFRIGERIO')) color = 'info';
                        return `<span class="badge bg-${color}" style="font-size: 0.72rem;">${data}</span>`;
                    }
                },
                {
                    data: 'turno_programado',
                    render: function (tp) {
                        if (tp && tp.tur_codigo) {
                            let color = tp.tur_color || '#3b82f6';
                            let horario = (tp.th_hora_ingreso || '').substring(0, 5) + ' - ' + (tp.th_hora_salida || '').substring(0, 5);
                            return `<span class="badge-turno" style="background-color: ${color};" title="${tp.tur_nombre}">
                                        <iconify-icon icon="solar:clock-circle-bold"></iconify-icon>
                                        ${tp.tur_codigo} (${horario})
                                    </span>`;
                        }
                        return `<span class="badge-sin-turno">Sin turno prog.</span>`;
                    }
                },
                {
                    data: null,
                    className: 'text-end',
                    orderable: false,
                    render: function (data, type, row) {
                        return `
                            <div class="d-inline-flex gap-1">
                                <button type="button" class="btn btn-sm btn-light-primary text-primary p-1 rounded-2" title="Ver Detalle y Contraste" onclick="verDetalleTrabajador(${row.asi_perl_ide})">
                                    <iconify-icon icon="solar:eye-bold" style="font-size: 1.15rem;"></iconify-icon>
                                </button>
                                <button type="button" class="btn btn-sm btn-light-warning text-warning p-1 rounded-2" title="Corregir Marcación" onclick="abrirModalCorregir(${row.asi_ide}, '${row.asi_fecha_hora}', '${row.asi_tipo || ''}', '${row.per_paterno || ''} ${row.per_nombre || ''}')">
                                    <iconify-icon icon="solar:pen-bold" style="font-size: 1.15rem;"></iconify-icon>
                                </button>
                                <button type="button" class="btn btn-sm btn-light-danger text-danger p-1 rounded-2" title="Anular Marcación" onclick="anularMarcacion(${row.asi_ide})">
                                    <iconify-icon icon="solar:trash-bin-trash-bold" style="font-size: 1.15rem;"></iconify-icon>
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            }
        });
    }

    function limpiarFiltros() {
        $('#filtro_fecha_inicio').val('<?= esc($fechaInicioDef) ?>');
        $('#filtro_fecha_fin').val('<?= esc($fechaFinDef) ?>');
        $('#filtro_est_ide').val('');
        $('#filtro_tipo').val('');
        $('#filtro_origen').val('');
        $('#filtro_dni').val('');
        tablaMarcaciones.ajax.reload();
    }

    function exportarExcel() {
        let params = $.param({
            fecha_inicio: $('#filtro_fecha_inicio').val(),
            fecha_fin:    $('#filtro_fecha_fin').val(),
            est_ide:      $('#filtro_est_ide').val(),
            tipo:         $('#filtro_tipo').val(),
            origen:       $('#filtro_origen').val(),
            dni:          $('#filtro_dni').val()
        });
        window.location.href = '<?= base_url('asistencia/asistencia/exportar-excel') ?>?' + params;
    }

    function abrirModalManual() {
        $('#formManual')[0].reset();
        // Fijar fecha y hora actual en el datetime-local
        let now = new Date();
        let localIso = new Date(now.getTime() - (now.getTimezoneOffset() * 60000)).toISOString().slice(0, 16);
        $('#manual_fecha_hora').val(localIso);
        $('#modalManual').modal('show');
    }

    function guardarMarcacionManual() {
        let btn = $('#btnGuardarManual');
        let spn = $('#spnGuardarManual');

        btn.prop('disabled', true);
        spn.removeClass('d-none');

        let postData = {
            asi_perl_ide:   $('#manual_perl_ide').val(),
            asi_fecha_hora: $('#manual_fecha_hora').val().replace('T', ' ') + ':00',
            asi_tipo:       $('#manual_tipo').val(),
            asi_motivo:     $('#manual_motivo').val(),
            [csrfToken]:    csrfHash
        };

        $.ajax({
            url: '<?= base_url('asistencia/asistencia/api/manual') ?>',
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function (res) {
                btn.prop('disabled', false);
                spn.addClass('d-none');
                if (res.status === 'success') {
                    $('#modalManual').modal('hide');
                    toastr.success(res.message);
                    tablaMarcaciones.ajax.reload(null, false);
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: res.message });
                }
            },
            error: function (xhr) {
                btn.prop('disabled', false);
                spn.addClass('d-none');
                let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error en el servidor al registrar.';
                Swal.fire({ icon: 'error', title: 'Error', text: msg });
            }
        });
    }

    function abrirModalCorregir(asiIde, fechaHora, tipo, nombre) {
        $('#corregir_asi_ide').val(asiIde);
        $('#corregir_nombre_trabajador').text(nombre);
        if (fechaHora) {
            $('#corregir_fecha_hora').val(fechaHora.replace(' ', 'T').substring(0, 16));
        }
        if (tipo) {
            $('#corregir_tipo').val(tipo);
        }
        $('#corregir_motivo').val('');
        $('#modalCorregir').modal('show');
    }

    function guardarCorreccion() {
        let asiIde = $('#corregir_asi_ide').val();
        let btn = $('#btnGuardarCorregir');
        let spn = $('#spnGuardarCorregir');

        btn.prop('disabled', true);
        spn.removeClass('d-none');

        let postData = {
            asi_fecha_hora: $('#corregir_fecha_hora').val().replace('T', ' ') + ':00',
            asi_tipo:       $('#corregir_tipo').val(),
            asi_motivo:     $('#corregir_motivo').val(),
            [csrfToken]:    csrfHash
        };

        $.ajax({
            url: '<?= base_url('asistencia/asistencia/api/corregir') ?>/' + asiIde,
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function (res) {
                btn.prop('disabled', false);
                spn.addClass('d-none');
                if (res.status === 'success') {
                    $('#modalCorregir').modal('hide');
                    toastr.success(res.message);
                    tablaMarcaciones.ajax.reload(null, false);
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: res.message });
                }
            },
            error: function (xhr) {
                btn.prop('disabled', false);
                spn.addClass('d-none');
                let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error al corregir.';
                Swal.fire({ icon: 'error', title: 'Error', text: msg });
            }
        });
    }

    function anularMarcacion(asiIde) {
        Swal.fire({
            title: '¿Anular Marcación?',
            text: 'Debe ingresar obligatoriamente el motivo formal de anulación:',
            input: 'textarea',
            inputPlaceholder: 'Ingrese el motivo aquí (mínimo 5 caracteres)...',
            inputAttributes: {
                minlength: 5,
                maxlength: 255
            },
            showCancelButton: true,
            confirmButtonText: 'Sí, Anular',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#ef4444',
            preConfirm: (motivo) => {
                if (!motivo || motivo.trim().length < 5) {
                    Swal.showValidationMessage('El motivo es obligatorio y debe tener al menos 5 caracteres.');
                    return false;
                }
                return motivo.trim();
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('asistencia/asistencia/api/eliminar') ?>/' + asiIde,
                    type: 'POST',
                    data: {
                        asi_motivo: result.value,
                        [csrfToken]: csrfHash
                    },
                    dataType: 'json',
                    success: function (res) {
                        if (res.status === 'success') {
                            toastr.success(res.message);
                            tablaMarcaciones.ajax.reload(null, false);
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error', text: res.message });
                        }
                    },
                    error: function (xhr) {
                        let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error al anular la marcación.';
                        Swal.fire({ icon: 'error', title: 'Error', text: msg });
                    }
                });
            }
        });
    }

    function verDetalleTrabajador(perlIde) {
        let fInicio = $('#filtro_fecha_inicio').val();
        let fFin    = $('#filtro_fecha_fin').val();

        $('#tbodyDetalleDias').html('<tr><td colspan="6" class="text-center py-4 text-muted"><div class="spinner-border spinner-border-sm me-2"></div>Cargando historial de asistencia y contraste...</td></tr>');
        $('#modalDetalleTrabajador').modal('show');

        $.ajax({
            url: '<?= base_url('asistencia/asistencia/api/detalle') ?>/' + perlIde,
            type: 'GET',
            data: { fecha_inicio: fInicio, fecha_fin: fFin },
            dataType: 'json',
            success: function (res) {
                if (res.status === 'success' && res.data) {
                    let trab = res.data.trabajador;
                    let nombre = (trab.per_paterno || '') + ' ' + (trab.per_materno || '') + ' ' + (trab.per_nombre || '');

                    $('#det_trabajador_nombre').text(nombre.trim());
                    $('#det_trabajador_dni').text(trab.per_numero_documento || trab.perl_codigo || '-');
                    $('#det_trabajador_cargo').text(trab.car_nombre || '-');
                    $('#det_trabajador_est').text(trab.est_nombre || '-');
                    $('#detalle_subtitulo').text(`Periodo analizado: ${res.data.fecha_inicio} al ${res.data.fecha_fin} (Total ${res.data.total_marcas} marcaciones reales)`);

                    let tbody = '';
                    res.data.dias.forEach(function (d) {
                        let turnosHtml = '<span class="text-muted small">Sin Programar</span>';
                        let horariosHtml = '<span class="text-muted small">-</span>';

                        if (d.turnos && d.turnos.length > 0) {
                            turnosHtml = d.turnos.map(t => {
                                let c = t.tur_color || '#3b82f6';
                                return `<span class="badge-turno me-1 mb-1" style="background-color: ${c}">${t.tur_codigo} - ${t.tur_nombre}</span>`;
                            }).join('');

                            horariosHtml = d.turnos.map(t => {
                                return `<div class="small font-monospace">${t.th_hora_ingreso.substring(0, 5)} - ${t.th_hora_salida.substring(0, 5)}</div>`;
                            }).join('');
                        }

                        let marcasHtml = '<span class="text-muted small">Sin marcas</span>';
                        if (d.marcaciones && d.marcaciones.length > 0) {
                            marcasHtml = d.marcaciones.map(m => {
                                let hora = m.asi_fecha_hora.split(' ')[1] || '';
                                let tipo = m.asi_tipo ? `(${m.asi_tipo})` : '';
                                return `<span class="badge bg-light text-dark border font-monospace me-1 mb-1">${hora} ${tipo}</span>`;
                            }).join('');
                        }

                        let estadoBadge = '';
                        if (d.tiene_turno && d.tiene_marca) {
                            estadoBadge = '<span class="badge bg-success" style="font-size: 0.72rem;">Turno y Marcación</span>';
                        } else if (d.tiene_turno && !d.tiene_marca) {
                            estadoBadge = '<span class="badge bg-danger" style="font-size: 0.72rem;">Sin Marcación</span>';
                        } else if (!d.tiene_turno && d.tiene_marca) {
                            estadoBadge = '<span class="badge bg-warning text-dark" style="font-size: 0.72rem;">Marca no Programada</span>';
                        } else {
                            estadoBadge = '<span class="badge bg-light text-muted" style="font-size: 0.72rem;">Descanso / Libre</span>';
                        }

                        tbody += `
                            <tr>
                                <td class="fw-bold font-monospace small">${d.fecha}</td>
                                <td class="small text-muted">${d.dia_nombre}</td>
                                <td>${turnosHtml}</td>
                                <td>${horariosHtml}</td>
                                <td>${marcasHtml}</td>
                                <td>${estadoBadge}</td>
                            </tr>
                        `;
                    });

                    $('#tbodyDetalleDias').html(tbody);
                } else {
                    $('#tbodyDetalleDias').html('<tr><td colspan="6" class="text-center py-4 text-danger">No se pudo cargar el detalle del trabajador.</td></tr>');
                }
            },
            error: function () {
                $('#tbodyDetalleDias').html('<tr><td colspan="6" class="text-center py-4 text-danger">Error de comunicación con el servidor.</td></tr>');
            }
        });
    }
</script>
<?= $this->endSection() ?>
