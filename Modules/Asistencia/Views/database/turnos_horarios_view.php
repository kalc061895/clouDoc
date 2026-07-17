<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?>
Horarios por Turno
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:calendar-date-bold-duotone" class="me-2 text-primary" style="font-size: 1.5rem;"></iconify-icon>
                Horarios por Turno
            </h4>
            <p class="text-muted mb-0 small">Configura las horas límites de marcado para el ingreso, salida y los intervalos de almuerzo o refrigerio autorizados.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <select class="form-select form-select-sm rounded-pill px-3" id="filtroEstado" style="width: 150px;" onchange="filtrarHorarios()">
                <option value="" selected>Todos los estados</option>
                <option value="1">Activos</option>
                <option value="0">Inactivos</option>
            </select>

            <button class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="abrirModalCrear()">
                <iconify-icon icon="lucide:plus" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
                Nuevo Horario
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table id="tablaHorarios" class="table table-hover align-middle w-100">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th style="width: 180px;">Turno Padre</th>
                    <th style="width: 120px;">Cod. Horario</th>
                    <th>Nombre de Configuración</th>
                    <th>Ingreso / Salida</th>
                    <th>Tolerancia (I/S)</th>
                    <th>Refrigerio</th>
                    <th style="width: 100px;">Estado</th>
                    <th class="text-end" style="width: 110px;">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- MODAL: CREAR / EDITAR -->
<div class="modal fade" id="modalHorario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitulo">Nuevo Horario de Turno</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formHorario">
                <div class="modal-body py-3">
                    <input type="hidden" id="th_ide">

                    <div class="row g-3">
                        <!-- Turno Padre -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Turno General Asociado</label>
                            <select class="form-select" id="th_tur_ide" required>
                                <option value="" disabled selected>Seleccione Turno...</option>
                            </select>
                        </div>
                        <!-- Código del Horario -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Código de Horario</label>
                            <input type="text" class="form-control font-monospace text-uppercase" id="th_codigo" required maxlength="15" placeholder="Ej. MA-ADMIN">
                        </div>
                        <!-- Nombre del Horario -->
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Descripción del Horario</label>
                            <input type="text" class="form-control" id="th_nombre" required maxlength="100" placeholder="Ej. Lunes a Viernes - Administrativo Hospital">
                        </div>

                        <hr class="my-2 text-muted">
                        <div class="col-12 py-0"><span class="badge bg-primary-subtle text-primary fw-bold">Jornada General</span></div>

                        <!-- Hora Ingreso -->
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Hora Ingreso</label>
                            <input type="time" class="form-control font-monospace" id="th_hora_ingreso" required>
                        </div>
                        <!-- Hora Salida -->
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Hora Salida</label>
                            <input type="time" class="form-control font-monospace" id="th_hora_salida" required>
                        </div>
                        <!-- Tolerancia Ingreso -->
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Tolerancia Ingreso (min)</label>
                            <input type="number" class="form-control font-monospace text-center" id="th_tolerancia_ingreso" min="0" max="60" value="0">
                        </div>
                        <!-- Tolerancia Salida -->
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Tolerancia Salida (min)</label>
                            <input type="number" class="form-control font-monospace text-center" id="th_tolerancia_salida" min="0" max="60" value="0">
                        </div>

                        <hr class="my-2 text-muted">
                        <div class="col-12 py-0 d-flex justify-content-between align-items-center">
                            <span class="badge bg-warning-subtle text-warning fw-bold">Refrigerio / Almuerzo (Opcional)</span>
                            <button type="button" class="btn btn-link btn-xs text-danger p-0 text-decoration-none small" onclick="limpiarRefrigerio()">Limpiar Refrigerio</button>
                        </div>

                        <!-- Refrigerio Salida -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Salida a Refrigerio</label>
                            <input type="time" class="form-control font-monospace" id="th_refrigerio_salida">
                        </div>
                        <!-- Refrigerio Retorno -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Retorno de Refrigerio</label>
                            <input type="time" class="form-control font-monospace" id="th_refrigerio_retorno">
                        </div>

                        <div class="col-md-12">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="th_estado" value="1" checked>
                                <label class="form-check-label small fw-bold" for="th_estado">Horario habilitado para marcaciones</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm px-3" id="btnGuardar">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script>
    const urlBaseApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/horarios';
    const urlTurnosApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/turnos';

    let tabla;
    let modalHorario = new bootstrap.Modal(document.getElementById('modalHorario'));

    $(document).ready(function() {
        // Cargar Catálogo de Turnos para el Selector
        cargarSelectTurnos();

        // Inicializar tabla asíncrona con Datatables
        tabla = $('#tablaHorarios').DataTable({
            "ajax": {
                "url": urlBaseApi,
                "type": "GET",
                "data": function(d) {
                    d.estado = $('#filtroEstado').val();
                },
                "dataSrc": "data"
            },
            "columns": [{
                    "data": "th_ide"
                },
                {
                    "data": "tur_nombre",
                    "render": function(data, type, row) {
                        const color = row.tur_color || '#6c757d';
                        return `
                            <div class="d-flex align-items-center gap-2">
                                <span class="rounded-circle" style="background-color: ${color}; width: 12px; height: 12px; border: 1.5px solid #fff; outline: 1px solid #dee2e6;"></span>
                                <span class="fw-bold text-dark small" style="font-size: 0.85rem;">${data}</span>
                            </div>
                        `;
                    }
                },
                {
                    "data": "th_codigo",
                    "className": "fw-bold font-monospace text-center",
                    "render": function(data) {
                        return `<span class="bg-light border px-2 py-1 rounded text-uppercase text-secondary" style="font-size: 0.8rem;">${data}</span>`;
                    }
                },
                {
                    "data": "th_nombre",
                    "className": "text-secondary small fw-medium"
                },
                {
                    "data": null,
                    "className": "font-monospace text-center fw-bold text-dark",
                    "render": function(data, type, row) {
                        // Formatear formato de salida visual a HH:MM hs
                        const ingreso = row.th_hora_ingreso.substring(0, 5);
                        const salida = row.th_hora_salida.substring(0, 5);
                        return `<span class="text-primary">${ingreso}</span> a <span class="text-success">${salida}</span>`;
                    }
                },
                {
                    "data": null,
                    "className": "font-monospace text-center small text-muted",
                    "render": function(data, type, row) {
                        return `I: ${row.th_tolerancia_ingreso}m | S: ${row.th_tolerancia_salida}m`;
                    }
                },
                {
                    "data": null,
                    "className": "font-monospace text-center",
                    "render": function(data, type, row) {
                        if (row.th_refrigerio_salida && row.th_refrigerio_retorno) {
                            return `<span class="badge bg-warning-subtle text-warning border border-warning-subtle small fw-bold">${row.th_refrigerio_salida.substring(0,5)} - ${row.th_refrigerio_retorno.substring(0,5)}</span>`;
                        }
                        return '<span class="text-muted small">Sin ref.</span>';
                    }
                },
                {
                    "data": "th_estado",
                    "render": function(data) {
                        return data == 1 ?
                            '<span class="badge bg-success-subtle text-success border border-success-subtle">Activo</span>' :
                            '<span class="badge bg-danger-subtle text-danger border border-danger-subtle">Inactivo</span>';
                    }
                },
                {
                    "data": null,
                    "orderable": false,
                    "className": "text-end",
                    "render": function(data, type, row) {
                        return `
                            <button class="btn btn-outline-warning btn-sm me-1 rounded-circle p-1 d-inline-flex align-items-center justify-content-center" 
                                    onclick='abrirModalEditar(${JSON.stringify(row)})' title="Editar" style="width: 32px; height: 32px;">
                                <iconify-icon icon="lucide:edit" style="font-size: 1.1rem;"></iconify-icon>
                            </button>
                            <button class="btn btn-outline-danger btn-sm rounded-circle p-1 d-inline-flex align-items-center justify-content-center" 
                                    onclick="eliminarHorario(${row.th_ide})" title="Eliminar" style="width: 32px; height: 32px;">
                                <iconify-icon icon="lucide:trash-2" style="font-size: 1.1rem;"></iconify-icon>
                            </button>
                        `;
                    }
                }
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            }
        });

        $('#formHorario').on('submit', function(e) {
            e.preventDefault();
            guardarHorario();
        });
    });

    function filtrarHorarios() {
        tabla.ajax.reload(null, false);
    }

    function cargarSelectTurnos() {
        $.ajax({
            url: urlTurnosApi,
            type: 'GET',
            data: {
                estado: 1
            },
            success: function(response) {
                if (response.status === 'success') {
                    const select = $('#th_tur_ide');
                    select.find('option:not(:first)').remove();
                    response.data.forEach(function(turno) {
                        select.append(`<option value="${turno.tur_ide}">${turno.tur_nombre} [${turno.tur_codigo}]</option>`);
                    });
                }
            }
        });
    }

    function limpiarRefrigerio() {
        $('#th_refrigerio_salida').val('');
        $('#th_refrigerio_retorno').val('');
    }

    function abrirModalCrear() {
        $('#formHorario')[0].reset();
        $('#th_ide').val('');
        $('#th_estado').prop('checked', true);
        $('#modalTitulo').text('Nuevo Horario de Turno');
        modalHorario.show();
    }

    function abrirModalEditar(data) {
        $('#formHorario')[0].reset();
        $('#th_ide').val(data.th_ide);
        $('#th_tur_ide').val(data.th_tur_ide);
        $('#th_codigo').val(data.th_codigo);
        $('#th_nombre').val(data.th_nombre);
        $('#th_hora_ingreso').val(data.th_hora_ingreso);
        $('#th_hora_salida').val(data.th_hora_salida);
        $('#th_tolerancia_ingreso').val(data.th_tolerancia_ingreso);
        $('#th_tolerancia_salida').val(data.th_tolerancia_salida);

        $('#th_refrigerio_salida').val(data.th_refrigerio_salida || '');
        $('#th_refrigerio_retorno').val(data.th_refrigerio_retorno || '');

        $('#th_estado').prop('checked', data.th_estado == 1);
        $('#modalTitulo').text('Editar Horario de Turno');
        modalHorario.show();
    }

    function guardarHorario() {
        const id = $('#th_ide').val();
        const esEditar = id !== "";
        const url = esEditar ? `${urlBaseApi}/${id}` : urlBaseApi;
        const metodo = esEditar ? 'PUT' : 'POST';

        $('#btnGuardar').prop('disabled', true).text('Guardando...');

        const datos = {
            th_tur_ide: $('#th_tur_ide').val(),
            th_codigo: $('#th_codigo').val().toUpperCase(),
            th_nombre: $('#th_nombre').val(),
            th_hora_ingreso: $('#th_hora_ingreso').val(),
            th_hora_salida: $('#th_hora_salida').val(),
            th_tolerancia_ingreso: $('#th_tolerancia_ingreso').val(),
            th_tolerancia_salida: $('#th_tolerancia_salida').val(),
            th_refrigerio_salida: $('#th_refrigerio_salida').val() || null,
            th_refrigerio_retorno: $('#th_refrigerio_retorno').val() || null,
            th_estado: $('#th_estado').is(':checked') ? 1 : 0
        };

        $.ajax({
            url: url,
            type: metodo,
            data: datos,
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    modalHorario.hide();
                    tabla.ajax.reload(null, false);
                }
            },
            error: function(xhr) {
                let errorMsg = 'Error en el servidor.';
                if (xhr.responseJSON && xhr.responseJSON.messages) {
                    errorMsg = Object.values(xhr.responseJSON.messages).join('<br>');
                }
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    html: errorMsg
                });
            },
            complete: function() {
                $('#btnGuardar').prop('disabled', false).text('Guardar');
            }
        });
    }

    function eliminarHorario(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "El horario se archivará. Esto afectará las evaluaciones del personal con este horario asignado.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sí, archivar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `${urlBaseApi}/${id}`,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            tabla.ajax.reload(null, false);
                        }
                    }
                });
            }
        });
    }
</script>
<?= $this->endSection() ?>