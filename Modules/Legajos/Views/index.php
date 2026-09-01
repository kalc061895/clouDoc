<?= $this->extend('layouts/layoutMain') ?>
<?= $this->section('title') ?><?= esc($titulo) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid py-3">

    <!-- Encabezado y Estadísticas Rápidas -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <div class="d-flex align-items-center">
                <div class="bg-primary-subtle text-primary p-3 rounded-circle me-3">
                    <iconify-icon icon="solar:folder-with-files-bold-duotone" width="32" height="32"></iconify-icon>
                </div>
                <div>
                    <h3 class="fw-bold mb-0 text-dark">Legajo Personal Institucional</h3>
                    <p class="text-muted small mb-0">Gestión integral de expedientes personales de servidores públicos conforme a SERVIR</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" onclick="abrirModalServidor()">
                <iconify-icon icon="solar:user-plus-bold-duotone" class="me-1"></iconify-icon> Nuevo Servidor
            </button>
        </div>
    </div>

    <!-- Tarjetas de Métricas -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary text-white p-3 rounded-4 me-3">
                        <iconify-icon icon="solar:users-group-rounded-bold-duotone" width="28" height="28"></iconify-icon>
                    </div>
                    <div>
                        <span class="text-muted text-uppercase fw-semibold small">Total Servidores</span>
                        <h3 class="fw-bold mb-0 text-dark"><?= esc($estadisticas['total'] ?? 0) ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success text-white p-3 rounded-4 me-3">
                        <iconify-icon icon="solar:check-circle-bold-duotone" width="28" height="28"></iconify-icon>
                    </div>
                    <div>
                        <span class="text-muted text-uppercase fw-semibold small">Activos</span>
                        <h3 class="fw-bold mb-0 text-dark"><?= esc($estadisticas['activos'] ?? 0) ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning text-white p-3 rounded-4 me-3">
                        <iconify-icon icon="solar:pause-circle-bold-duotone" width="28" height="28"></iconify-icon>
                    </div>
                    <div>
                        <span class="text-muted text-uppercase fw-semibold small">En Licencia</span>
                        <h3 class="fw-bold mb-0 text-dark"><?= esc($estadisticas['licencia'] ?? 0) ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-secondary text-white p-3 rounded-4 me-3">
                        <iconify-icon icon="solar:user-block-bold-duotone" width="28" height="28"></iconify-icon>
                    </div>
                    <div>
                        <span class="text-muted text-uppercase fw-semibold small">Cesados</span>
                        <h3 class="fw-bold mb-0 text-dark"><?= esc($estadisticas['cesados'] ?? 0) ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla Principal con Filtros -->
    <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
        <!-- Filtros de búsqueda -->
        <div class="row g-3 mb-4 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold text-muted">Régimen Laboral</label>
                <select id="filtro_regimen" class="form-select rounded-3" onchange="recargarTabla()">
                    <option value="">-- Todos los Regímenes --</option>
                    <option value="D.L. 276">D.L. 276 (Carrera Administrativa)</option>
                    <option value="D.L. 728">D.L. 728 (Régimen Privado)</option>
                    <option value="D.L. 1057 (CAS)">D.L. 1057 (CAS)</option>
                    <option value="LEY 30057 (SERVIR)">Ley 30057 (Servicio Civil)</option>
                    <option value="LOCACION DE SERVICIOS">Locación de Servicios</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Estado del Servidor</label>
                <select id="filtro_estado" class="form-select rounded-3" onchange="recargarTabla()">
                    <option value="">-- Todos los Estados --</option>
                    <option value="ACTIVO">ACTIVO</option>
                    <option value="LICENCIA">LICENCIA</option>
                    <option value="SUSPENDIDO">SUSPENDIDO</option>
                    <option value="CESADO">CESADO</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Dependencia / Oficina</label>
                <input type="text" id="filtro_dependencia" class="form-control rounded-3" placeholder="Buscar por oficina..." onkeyup="if(event.key==='Enter') recargarTabla()">
            </div>
            <div class="col-md-2 text-end">
                <button type="button" class="btn btn-outline-secondary w-100 rounded-3" onclick="limpiarFiltros()">
                    <iconify-icon icon="solar:restart-bold" class="me-1"></iconify-icon> Limpiar
                </button>
            </div>
        </div>

        <!-- Tabla DataTables -->
        <div class="table-responsive">
            <table id="tabla_servidores" class="table table-hover align-middle w-100 border-top">
                <thead class="table-light">
                    <tr class="text-muted small text-uppercase">
                        <th>Servidor Público</th>
                        <th>Documento</th>
                        <th>Régimen / Condición</th>
                        <th>Cargo & Dependencia</th>
                        <th>N° Legajo</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL: REGISTRO / EDICIÓN DE SERVIDOR PÚBLICO -->
<div class="modal fade" id="modalServidor" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="modalServidorTitulo">
                    <iconify-icon icon="solar:user-id-bold-duotone" class="me-2"></iconify-icon> Registrar Servidor Público
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formServidor" enctype="multipart/form-data" onsubmit="guardarServidor(event)">
                <div class="modal-body p-4">
                    <input type="hidden" id="ser_ide" name="ser_ide">

                    <!-- SECCIÓN 1: DATOS PERSONALES -->
                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">
                        <iconify-icon icon="solar:user-bold" class="me-1"></iconify-icon> 1. Datos Personales
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Tipo Documento *</label>
                            <select class="form-select" id="ser_tipo_documento" name="ser_tipo_documento" required>
                                <option value="DNI">DNI</option>
                                <option value="CARNET EXT.">Carné de Extranjería</option>
                                <option value="PASAPORTE">Pasaporte</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">N° Documento *</label>
                            <input type="text" class="form-control" id="ser_numero_documento" name="ser_numero_documento" maxlength="20" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">RUC (4ta/5ta)</label>
                            <input type="text" class="form-control" id="ser_ruc" name="ser_ruc" maxlength="11">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Sexo *</label>
                            <select class="form-select" id="ser_sexo" name="ser_sexo" required>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Nombres *</label>
                            <input type="text" class="form-control" id="ser_nombres" name="ser_nombres" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Apellido Paterno *</label>
                            <input type="text" class="form-control" id="ser_apellido_paterno" name="ser_apellido_paterno" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Apellido Materno</label>
                            <input type="text" class="form-control" id="ser_apellido_materno" name="ser_apellido_materno">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Fecha Nacimiento</label>
                            <input type="date" class="form-control" id="ser_fecha_nacimiento" name="ser_fecha_nacimiento">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Estado Civil</label>
                            <select class="form-select" id="ser_estado_civil" name="ser_estado_civil">
                                <option value="SOLTERO">Soltero(a)</option>
                                <option value="CASADO">Casado(a)</option>
                                <option value="CONVIVIENTE">Conviviente</option>
                                <option value="DIVORCIADO">Divorciado(a)</option>
                                <option value="VIUDO">Viudo(a)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Grupo Sanguíneo</label>
                            <input type="text" class="form-control" id="ser_grupo_sanguineo" name="ser_grupo_sanguineo" placeholder="Ej: O+, A+">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Fotografía</label>
                            <input type="file" class="form-control" id="ser_foto_archivo" name="ser_foto_archivo" accept="image/*">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Celular</label>
                            <input type="text" class="form-control" id="ser_celular" name="ser_celular">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Email Institucional</label>
                            <input type="email" class="form-control" id="ser_email_institucional" name="ser_email_institucional">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Email Personal</label>
                            <input type="email" class="form-control" id="ser_email_personal" name="ser_email_personal">
                        </div>

                        <div class="col-md-8">
                            <label class="form-label small fw-semibold">Dirección Domiciliaria</label>
                            <input type="text" class="form-control" id="ser_direccion" name="ser_direccion">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Ubigeo (Distrito)</label>
                            <input type="text" class="form-control" id="ser_ubigeo" name="ser_ubigeo" placeholder="Ej: 150101">
                        </div>
                    </div>

                    <!-- SECCIÓN 2: DATOS LABORALES -->
                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">
                        <iconify-icon icon="solar:case-bold" class="me-1"></iconify-icon> 2. Vinculación y Datos Laborales
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Régimen Laboral *</label>
                            <select class="form-select" id="ser_regimen_laboral" name="ser_regimen_laboral" required>
                                <option value="D.L. 276">D.L. 276 (Carrera Administrativa)</option>
                                <option value="D.L. 728">D.L. 728 (Régimen Laboral Privado)</option>
                                <option value="D.L. 1057 (CAS)" selected>D.L. 1057 (CAS)</option>
                                <option value="LEY 30057 (SERVIR)">Ley 30057 (Servicio Civil)</option>
                                <option value="LOCACION DE SERVICIOS">Locación de Servicios</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Condición Laboral *</label>
                            <select class="form-select" id="ser_condicion_laboral" name="ser_condicion_laboral" required>
                                <option value="NOMBRADO">Nombrado</option>
                                <option value="CONTRATADO PLAZO INDETERMINADO">Contratado a Plazo Indeterminado</option>
                                <option value="CONTRATADO PLAZO DETERMINADO" selected>Contratado a Plazo Determinado</option>
                                <option value="DESIGNADO">Designado</option>
                                <option value="CONFIANZA">Funcionario de Confianza</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Estado *</label>
                            <select class="form-select" id="ser_estado" name="ser_estado" required>
                                <option value="ACTIVO" selected>ACTIVO</option>
                                <option value="LICENCIA">LICENCIA</option>
                                <option value="SUSPENDIDO">SUSPENDIDO</option>
                                <option value="CESADO">CESADO</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Cargo Institucional *</label>
                            <input type="text" class="form-control" id="ser_cargo" name="ser_cargo" placeholder="Ej: Especialista Legal I" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Dependencia / Unidad Orgánica *</label>
                            <input type="text" class="form-control" id="ser_dependencia" name="ser_dependencia" placeholder="Ej: Oficina General de Asesoría Jurídica" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Fecha de Ingreso</label>
                            <input type="date" class="form-control" id="ser_fecha_ingreso" name="ser_fecha_ingreso">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Fecha de Cese (si aplica)</label>
                            <input type="date" class="form-control" id="ser_fecha_cese" name="ser_fecha_cese">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">N° de Legajo Físico</label>
                            <input type="text" class="form-control" id="ser_numero_legajo" name="ser_numero_legajo" placeholder="Ej: LEG-2026-0012">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">Observaciones / Notas adicionales</label>
                            <textarea class="form-control" id="ser_observaciones" name="ser_observaciones" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 py-3">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm" id="btnGuardarServidor">
                        <iconify-icon icon="solar:diskette-bold" class="me-1"></iconify-icon> Guardar Registro
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script>
let tablaServidores;

$(document).ready(function() {
    tablaServidores = $('#tabla_servidores').DataTable({
        ajax: {
            url: '<?= base_url('legajos/listar') ?>',
            data: function(d) {
                d.regimen = $('#filtro_regimen').val();
                d.estado = $('#filtro_estado').val();
                d.dependencia = $('#filtro_dependencia').val();
            }
        },
        columns: [
            {
                data: null,
                render: function(data) {
                    const foto = data.ser_foto ? '<?= base_url() ?>/' + data.ser_foto : '<?= base_url('assets/images/profile/user-1.jpg') ?>';
                    const nombreCompleto = `${data.ser_apellido_paterno} ${data.ser_apellido_materno || ''} ${data.ser_nombres}`;
                    return `
                        <div class="d-flex align-items-center">
                            <img src="${foto}" class="rounded-circle me-3 object-fit-cover shadow-sm" width="42" height="42" alt="Foto">
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">${nombreCompleto}</h6>
                                <span class="text-muted small"><iconify-icon icon="solar:letter-bold" class="me-1"></iconify-icon>${data.ser_email_institucional || 'Sin correo inst.'}</span>
                            </div>
                        </div>
                    `;
                }
            },
            {
                data: null,
                render: function(data) {
                    return `
                        <span class="badge bg-light text-dark border fw-medium">${data.ser_tipo_documento}: ${data.ser_numero_documento}</span>
                        ${data.ser_ruc ? `<br><small class="text-muted">RUC: ${data.ser_ruc}</small>` : ''}
                    `;
                }
            },
            {
                data: null,
                render: function(data) {
                    let badgeClass = 'bg-primary-subtle text-primary';
                    if (data.ser_regimen_laboral.includes('276')) badgeClass = 'bg-info-subtle text-info';
                    if (data.ser_regimen_laboral.includes('728')) badgeClass = 'bg-warning-subtle text-warning';
                    if (data.ser_regimen_laboral.includes('SERVIR')) badgeClass = 'bg-purple-subtle text-purple';

                    return `
                        <span class="badge ${badgeClass} mb-1">${data.ser_regimen_laboral}</span>
                        <br><small class="text-muted">${data.ser_condicion_laboral || ''}</small>
                    `;
                }
            },
            {
                data: null,
                render: function(data) {
                    return `
                        <strong class="text-dark d-block">${data.ser_cargo}</strong>
                        <small class="text-muted"><iconify-icon icon="solar:buildings-bold" class="me-1"></iconify-icon>${data.ser_dependencia}</small>
                    `;
                }
            },
            {
                data: 'ser_numero_legajo',
                render: function(data) {
                    return data ? `<span class="badge bg-secondary-subtle text-secondary fw-mono">${data}</span>` : '<span class="text-muted small">No asignado</span>';
                }
            },
            {
                data: 'ser_estado',
                render: function(data) {
                    let badge = 'bg-success';
                    if (data === 'CESADO') badge = 'bg-danger';
                    if (data === 'LICENCIA') badge = 'bg-warning text-dark';
                    if (data === 'SUSPENDIDO') badge = 'bg-secondary';
                    return `<span class="badge ${badge} rounded-pill px-3">${data}</span>`;
                }
            },
            {
                data: null,
                className: 'text-end',
                orderable: false,
                render: function(data) {
                    return `
                        <div class="btn-group">
                            <a href="<?= base_url('legajos/ver') ?>/${data.ser_ide}" class="btn btn-sm btn-primary rounded-pill px-3 me-1 shadow-sm" title="Ver Legajo">
                                <iconify-icon icon="solar:folder-open-bold" class="me-1"></iconify-icon> Legajo
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2 me-1" onclick="editarServidor(${data.ser_ide})" title="Editar Datos">
                                <iconify-icon icon="solar:pen-bold"></iconify-icon>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2" onclick="eliminarServidor(${data.ser_ide}, '${data.ser_nombres}')" title="Dar de baja">
                                <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        responsive: true,
        order: [[0, 'asc']]
    });
});

function recargarTabla() {
    tablaServidores.ajax.reload();
}

function limpiarFiltros() {
    $('#filtro_regimen').val('');
    $('#filtro_estado').val('');
    $('#filtro_dependencia').val('');
    recargarTabla();
}

function abrirModalServidor() {
    $('#formServidor')[0].reset();
    $('#ser_ide').val('');
    $('#modalServidorTitulo').html('<iconify-icon icon="solar:user-plus-bold-duotone" class="me-2"></iconify-icon> Registrar Servidor Público');
    $('#modalServidor').modal('show');
}

function editarServidor(id) {
    $.getJSON(`<?= base_url('legajos/obtener-servidor') ?>/${id}`, function(res) {
        if (res.status === 'success') {
            const d = res.data;
            $('#ser_ide').val(d.ser_ide);
            $('#ser_tipo_documento').val(d.ser_tipo_documento);
            $('#ser_numero_documento').val(d.ser_numero_documento);
            $('#ser_ruc').val(d.ser_ruc);
            $('#ser_nombres').val(d.ser_nombres);
            $('#ser_apellido_paterno').val(d.ser_apellido_paterno);
            $('#ser_apellido_materno').val(d.ser_apellido_materno);
            $('#ser_sexo').val(d.ser_sexo);
            $('#ser_fecha_nacimiento').val(d.ser_fecha_nacimiento);
            $('#ser_estado_civil').val(d.ser_estado_civil);
            $('#ser_grupo_sanguineo').val(d.ser_grupo_sanguineo);
            $('#ser_celular').val(d.ser_celular);
            $('#ser_telefono_fijo').val(d.ser_telefono_fijo);
            $('#ser_email_institucional').val(d.ser_email_institucional);
            $('#ser_email_personal').val(d.ser_email_personal);
            $('#ser_direccion').val(d.ser_direccion);
            $('#ser_ubigeo').val(d.ser_ubigeo);
            $('#ser_regimen_laboral').val(d.ser_regimen_laboral);
            $('#ser_condicion_laboral').val(d.ser_condicion_laboral);
            $('#ser_cargo').val(d.ser_cargo);
            $('#ser_dependencia').val(d.ser_dependencia);
            $('#ser_fecha_ingreso').val(d.ser_fecha_ingreso);
            $('#ser_fecha_cese').val(d.ser_fecha_cese);
            $('#ser_numero_legajo').val(d.ser_numero_legajo);
            $('#ser_estado').val(d.ser_estado);
            $('#ser_observaciones').val(d.ser_observaciones);

            $('#modalServidorTitulo').html('<iconify-icon icon="solar:pen-bold" class="me-2"></iconify-icon> Editar Servidor Público');
            $('#modalServidor').modal('show');
        } else {
            Swal.fire('Error', res.message, 'error');
        }
    });
}

function guardarServidor(e) {
    e.preventDefault();
    const formData = new FormData(document.getElementById('formServidor'));

    $('#btnGuardarServidor').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Guardando...');

    $.ajax({
        url: '<?= base_url('legajos/guardar-servidor') ?>',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(res) {
            $('#btnGuardarServidor').prop('disabled', false).html('<iconify-icon icon="solar:diskette-bold" class="me-1"></iconify-icon> Guardar Registro');
            if (res.status === 'success') {
                $('#modalServidor').modal('hide');
                Swal.fire('Éxito', res.message, 'success');
                recargarTabla();
            } else {
                Swal.fire('Atención', res.message, 'warning');
            }
        },
        error: function(xhr) {
            $('#btnGuardarServidor').prop('disabled', false).html('<iconify-icon icon="solar:diskette-bold" class="me-1"></iconify-icon> Guardar Registro');
            const err = xhr.responseJSON ? xhr.responseJSON.message : 'Error interno al procesar el registro.';
            Swal.fire('Error', err, 'error');
        }
    });
}

function eliminarServidor(id, nombre) {
    Swal.fire({
        title: '¿Dar de baja al servidor?',
        text: `Se dará de baja el registro de ${nombre} en el sistema.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, dar de baja',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post(`<?= base_url('legajos/eliminar-servidor') ?>/${id}`, function(res) {
                if (res.status === 'success') {
                    Swal.fire('Completado', res.message, 'success');
                    recargarTabla();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
        }
    });
}
</script>
<?= $this->endSection() ?>

