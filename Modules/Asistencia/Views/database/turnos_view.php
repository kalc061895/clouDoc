<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?>
Gestión de Turnos Laborales
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:clock-square-bold-duotone" class="me-2 text-primary" style="font-size: 1.5rem;"></iconify-icon>
                Turnos Laborales
            </h4>
            <p class="text-muted mb-0 small">Asigna códigos de marcación y colores identificadores para estructurar los roles de guardia o jornadas ordinarias.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <select class="form-select form-select-sm rounded-pill px-3" id="filtroEstado" style="width: 150px;" onchange="filtrarTurnos()">
                <option value="" selected>Todos los estados</option>
                <option value="1">Activos</option>
                <option value="0">Inactivos</option>
            </select>

            <button class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="abrirModalCrear()">
                <iconify-icon icon="lucide:plus" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
                Nuevo Turno
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table id="tablaTurnos" class="table table-hover align-middle w-100">
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th style="width: 130px;">Código</th>
                    <th>Descripción de Turno</th>
                    <th style="width: 180px;">Color Representativo</th>
                    <th style="width: 130px;">Estado</th>
                    <th class="text-end" style="width: 120px;">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- MODAL: CREAR / EDITAR -->
<div class="modal fade" id="modalTurno" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitulo">Nuevo Turno Laboral</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTurno">
                <div class="modal-body py-3">
                    <input type="hidden" id="tur_ide">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Código Turno</label>
                            <input type="text" class="form-control font-monospace text-uppercase text-center" id="tur_codigo" required maxlength="10" placeholder="Ej. T-MA">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Nombre o Etiqueta</label>
                            <input type="text" class="form-control" id="tur_nombre" required maxlength="100" placeholder="Ej. Turno Mañana (Asistencial)">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold d-block">Color del Turno</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="color" class="form-control form-control-color border-0 rounded-circle shadow-sm" id="tur_color" value="#3b82f6" title="Selecciona un identificador visual" style="width: 48px; height: 48px; padding: 0; cursor: pointer;">
                                <div>
                                    <span class="font-monospace fw-bold text-muted small" id="colorHexText">#3B82F6</span>
                                    <div class="form-text text-muted" style="font-size: 0.7rem; margin-top: 2px;">Este color identificará visualmente al turno dentro de los calendarios y roles mensuales de asistencia.</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="tur_estado" value="1" checked>
                                <label class="form-check-label small fw-bold" for="tur_estado">Turno operativo y disponible</label>
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
    const urlBaseApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/turnos';

    let tabla;
    let modalTurno = new bootstrap.Modal(document.getElementById('modalTurno'));

    $(document).ready(function() {
        // Inicializar tabla asíncrona
        tabla = $('#tablaTurnos').DataTable({
            "ajax": {
                "url": urlBaseApi,
                "type": "GET",
                "data": function(d) {
                    d.estado = $('#filtroEstado').val();
                },
                "dataSrc": "data"
            },
            "columns": [{
                    "data": "tur_ide"
                },
                {
                    "data": "tur_codigo",
                    "className": "fw-bold font-monospace text-center",
                    "render": function(data) {
                        return `<span class="bg-light border px-2 py-1 rounded text-uppercase text-secondary" style="font-size: 0.85rem;">${data}</span>`;
                    }
                },
                {
                    "data": "tur_nombre",
                    "className": "fw-bold text-dark"
                },
                {
                    "data": "tur_color",
                    "className": "align-middle",
                    "render": function(data) {
                        const colorVal = data || '#3b82f6';
                        return `
                            <div class="d-inline-flex align-items-center gap-2">
                                <span class="d-inline-block rounded-circle shadow-sm" style="background-color: ${colorVal}; width: 18px; height: 18px; border: 2px solid #fff; outline: 1px solid #dee2e6;"></span>
                                <span class="font-monospace text-muted small" style="font-size: 0.8rem;">${colorVal.toUpperCase()}</span>
                            </div>
                        `;
                    }
                },
                {
                    "data": "tur_estado",
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
                                    onclick="eliminarTurno(${row.tur_ide})" title="Eliminar" style="width: 32px; height: 32px;">
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

        // Evento para cambiar el texto HEX dinámicamente en el modal
        $('#tur_color').on('input', function() {
            $('#colorHexText').text($(this).val().toUpperCase());
        });

        $('#formTurno').on('submit', function(e) {
            e.preventDefault();
            guardarTurno();
        });
    });

    function filtrarTurnos() {
        tabla.ajax.reload(null, false);
    }

    function abrirModalCrear() {
        $('#formTurno')[0].reset();
        $('#tur_ide').val('');
        $('#tur_color').val('#3b82f6');
        $('#colorHexText').text('#3B82F6');
        $('#tur_estado').prop('checked', true);
        $('#modalTitulo').text('Nuevo Turno Laboral');
        modalTurno.show();
    }

    function abrirModalEditar(data) {
        $('#formTurno')[0].reset();
        $('#tur_ide').val(data.tur_ide);
        $('#tur_codigo').val(data.tur_codigo);
        $('#tur_nombre').val(data.tur_nombre);

        const color = data.tur_color || '#3b82f6';
        $('#tur_color').val(color);
        $('#colorHexText').text(color.toUpperCase());

        $('#tur_estado').prop('checked', data.tur_estado == 1);
        $('#modalTitulo').text('Editar Turno Laboral');
        modalTurno.show();
    }

    function guardarTurno() {
        const id = $('#tur_ide').val();
        const esEditar = id !== "";
        const url = esEditar ? `${urlBaseApi}/${id}` : urlBaseApi;
        const metodo = esEditar ? 'PUT' : 'POST';

        $('#btnGuardar').prop('disabled', true).text('Guardando...');

        const datos = {
            tur_codigo: $('#tur_codigo').val().toUpperCase(),
            tur_nombre: $('#tur_nombre').val(),
            tur_color: $('#tur_color').val(),
            tur_estado: $('#tur_estado').is(':checked') ? 1 : 0
        };

        $.ajax({
            url: url,
            type: metodo,
            data: datos,
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    modalTurno.hide();
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

    function eliminarTurno(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "El turno se archivará. Al archivarse no podrá ser asignado a nuevos cronogramas.",
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