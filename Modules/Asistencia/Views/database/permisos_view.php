<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?>
Tipos de Permisos por Horas
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:clock-square-bold" class="me-2 text-primary" style="font-size: 1.5rem;"></iconify-icon>
                Tipos de Permisos por Horas
            </h4>
            <p class="text-muted mb-0 small">Gestión de papeletas y permisos por horas (médicos, particulares, lactancia) y topes de duración</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <!-- Filtro seguro por remuneración -->
            <select class="form-select form-select-sm rounded-pill px-3" id="filtroRemunerado" style="width: 170px;" onchange="filtrarTablas()">
                <option value="" selected>Todos los permisos</option>
                <option value="1">Con Goce de Haber</option>
                <option value="0">Sin Goce de Haber</option>
            </select>

            <button class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="abrirModalCrear()">
                <iconify-icon icon="lucide:plus" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
                Nuevo Permiso
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table id="tablaPermisos" class="table table-hover align-middle w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Abrv.</th>
                    <th>Concepto de Permiso</th>
                    <th>Régimen Remunerativo</th>
                    <th>Tope Mensual / Horas</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- MODAL: CREAR / EDITAR -->
<div class="modal fade" id="modalPermiso" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitulo">Nuevo Permiso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formPermiso">
                <div class="modal-body py-3">
                    <input type="hidden" id="pero_ide">

                    <div class="row g-3">
                        <div class="col-md-9">
                            <label class="form-label small fw-bold">Nombre del Permiso / Concepto</label>
                            <input type="text" class="form-control" id="pero_nombre" required maxlength="100" placeholder="Ej. Lactancia, Cita Médica EsSalud, Personal">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Abreviatura</label>
                            <input type="text" class="form-control font-monospace text-uppercase text-center" id="pero_abreviatura" maxlength="10" placeholder="Ej. LAC">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Descripción del Permiso</label>
                            <textarea class="form-control" id="pero_descripcion" rows="2" placeholder="Detalles de uso o condiciones de las papeletas"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Máximo de Horas Mensuales</label>
                            <input type="number" class="form-control" id="pero_horas_maximas" min="1" placeholder="Ilimitado si se deja vacío">
                            <div class="form-text text-muted" style="font-size: 0.7rem;">Vacío si no tiene límite mensual</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Remuneración</label>
                            <select class="form-select" id="pero_remunerado" required>
                                <option value="1">Con Goce de Haber (Pagado)</option>
                                <option value="0">Sin Goce de Haber (Descontable)</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="pero_estado" value="1" checked>
                                <label class="form-check-label small fw-bold" for="pero_estado">Permiso Activo</label>
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
    const urlBaseApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/permisos';

    let tabla;
    let modalPermiso = new bootstrap.Modal(document.getElementById('modalPermiso'));

    $(document).ready(function() {
        tabla = $('#tablaPermisos').DataTable({
            "ajax": {
                "url": urlBaseApi,
                "type": "GET",
                "data": function(d) {
                    d.remunerado = $('#filtroRemunerado').val();
                },
                "dataSrc": "data"
            },
            "columns": [{
                    "data": "pero_ide"
                },
                {
                    "data": "pero_abreviatura",
                    "className": "fw-bold font-monospace text-primary text-center",
                    "render": function(data) {
                        return data ? `[ ${data.toUpperCase()} ]` : '-';
                    }
                },
                {
                    "data": "pero_nombre",
                    "className": "fw-bold text-dark",
                    "render": function(data, type, row) {
                        return `
                            <div>
                                <span>${data}</span>
                                ${row.pero_descripcion ? `<div class="text-muted fw-normal small text-truncate" style="max-width: 380px;">${row.pero_descripcion}</div>` : ''}
                            </div>
                        `;
                    }
                },
                {
                    "data": "pero_remunerado",
                    "render": function(data) {
                        return data == 1 ?
                            '<span class="badge bg-success-subtle text-success border border-success-subtle"><iconify-icon icon="solar:dollar-minimalistic-bold" class="me-1"></iconify-icon>Con Goce</span>' :
                            '<span class="badge bg-warning-subtle text-warning border border-warning-subtle"><iconify-icon icon="solar:block-bold" class="me-1"></iconify-icon>Sin Goce</span>';
                    }
                },
                {
                    "data": "pero_horas_maximas",
                    "className": "text-center",
                    "render": function(data) {
                        return data ?
                            `<span class="badge bg-light text-dark border font-monospace fw-bold">${data} Horas</span>` :
                            '<span class="text-muted small">Ilimitado</span>';
                    }
                },
                {
                    "data": "pero_estado",
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
                                    onclick="eliminarPermiso(${row.pero_ide})" title="Eliminar" style="width: 32px; height: 32px;">
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

        $('#formPermiso').on('submit', function(e) {
            e.preventDefault();
            guardarPermiso();
        });
    });

    function filtrarTablas() {
        tabla.ajax.reload(null, false);
    }

    function abrirModalCrear() {
        $('#formPermiso')[0].reset();
        $('#pero_ide').val('');
        $('#pero_estado').prop('checked', true);
        $('#modalTitulo').text('Nuevo Tipo de Permiso');
        modalPermiso.show();
    }

    function abrirModalEditar(data) {
        $('#formPermiso')[0].reset();
        $('#pero_ide').val(data.pero_ide);
        $('#pero_nombre').val(data.pero_nombre);
        $('#pero_abreviatura').val(data.pero_abreviatura || '');
        $('#pero_descripcion').val(data.pero_descripcion || '');
        $('#pero_horas_maximas').val(data.pero_horas_maximas || '');
        $('#pero_remunerado').val(data.pero_remunerado);
        $('#pero_estado').prop('checked', data.pero_estado == 1);

        $('#modalTitulo').text('Editar Tipo de Permiso');
        modalPermiso.show();
    }

    function guardarPermiso() {
        const id = $('#pero_ide').val();
        const esEditar = id !== "";
        const url = esEditar ? `${urlBaseApi}/${id}` : urlBaseApi;
        const metodo = esEditar ? 'PUT' : 'POST';

        $('#btnGuardar').prop('disabled', true).text('Guardando...');

        const datos = {
            pero_nombre: $('#pero_nombre').val(),
            pero_abreviatura: $('#pero_abreviatura').val().toUpperCase(),
            pero_descripcion: $('#pero_descripcion').val(),
            pero_horas_maximas: $('#pero_horas_maximas').val(),
            pero_remunerado: $('#pero_remunerado').val(),
            pero_estado: $('#pero_estado').is(':checked') ? 1 : 0
        };

        $.ajax({
            url: url,
            type: metodo,
            data: datos,
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    modalPermiso.hide();
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

    function eliminarPermiso(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "El tipo de permiso se enviará a la papelera. No se podrá seleccionar en las nuevas papeletas de salida.",
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