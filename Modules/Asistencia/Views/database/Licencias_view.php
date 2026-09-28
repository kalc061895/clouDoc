<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?>
Tipos de Permisos y Licencias
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:folder-with-files-bold" class="me-2 text-primary" style="font-size: 1.5rem;"></iconify-icon>
                Tipos de Licencias y Permisos
            </h4>
            <p class="text-muted mb-0 small">Definición de licencias con o sin goce de haber, límites anuales de días y justificaciones válidas</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <!-- Filtro de remuneración -->
            <select class="form-select form-select-sm rounded-pill px-3" id="filtroRemuneracion" style="width: 160px;" onchange="filtrarTablas()">
                <option value="" selected>Todas las licencias</option>
                <option value="1">Con Goce de Haber</option>
                <option value="0">Sin Goce de Haber</option>
            </select>

            <button class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="abrirModalCrear()">
                <iconify-icon icon="lucide:plus" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
                Nueva Licencia
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table id="tablaLicencias" class="table table-hover align-middle w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Abrv.</th>
                    <th>Denominación de la Licencia</th>
                    <th>Tipo Remuneración</th>
                    <th>Tope de Días</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- MODAL: CREAR / EDITAR -->
<div class="modal fade" id="modalLicencia" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitulo">Nueva Licencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formLicencia">
                <div class="modal-body py-3">
                    <input type="hidden" id="lic_ide">

                    <div class="row g-3">
                        <div class="col-md-9">
                            <label class="form-label small fw-bold">Denominación de la Licencia</label>
                            <input type="text" class="form-control" id="lic_nombre" required maxlength="150" placeholder="Ej. Por Maternidad, Por Fallecimiento">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Abreviatura</label>
                            <input type="text" class="form-control font-monospace text-uppercase text-center" id="lic_abreviatura" maxlength="10" placeholder="Ej. MAT">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Descripción / Regla de Otorgamiento</label>
                            <textarea class="form-control" id="lic_descripcion" rows="2" placeholder="Detallar condiciones particulares o requerimiento de sustento físico"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Límite de Días Máximos</label>
                            <input type="number" class="form-control" id="lic_dias_maximos" min="1" placeholder="Ilimitado si se deja vacío">
                            <div class="form-text text-muted" style="font-size: 0.7rem;">Vacío para licencias sin tope estricto</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Régimen Remunerativo</label>
                            <select class="form-select" id="lic_remunerado" required>
                                <option value="1">Con Goce de Haber (Pagada)</option>
                                <option value="0">Sin Goce de Haber (No Pagada)</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="lic_estado" value="1" checked>
                                <label class="form-check-label small fw-bold" for="lic_estado">Licencia Vigente</label>
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
    const urlBaseApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/licencias';

    let tabla;
    let modalLicencia = new bootstrap.Modal(document.getElementById('modalLicencia'));

    $(document).ready(function() {
        tabla = $('#tablaLicencias').DataTable({
            "ajax": {
                "url": urlBaseApi,
                "type": "GET",
                "data": function(d) {
                    d.remunerado = $('#filtroRemuneracion').val();
                },
                "dataSrc": "data"
            },
            "columns": [{
                    "data": "lic_ide"
                },
                {
                    "data": "lic_abreviatura",
                    "className": "fw-bold font-monospace text-primary text-center",
                    "render": function(data) {
                        return data ? `[ ${data.toUpperCase()} ]` : '-';
                    }
                },
                {
                    "data": "lic_nombre",
                    "className": "fw-bold text-dark",
                    "render": function(data, type, row) {
                        return `
                            <div>
                                <span>${data}</span>
                                ${row.lic_descripcion ? `<div class="text-muted fw-normal small text-truncate" style="max-width: 380px;">${row.lic_descripcion}</div>` : ''}
                            </div>
                        `;
                    }
                },
                {
                    "data": "lic_remunerado",
                    "render": function(data) {
                        return data == 1 ?
                            '<span class="badge bg-success-subtle text-success border border-success-subtle"><iconify-icon icon="solar:dollar-minimalistic-bold" class="me-1"></iconify-icon>Con Goce</span>' :
                            '<span class="badge bg-warning-subtle text-warning border border-warning-subtle"><iconify-icon icon="solar:block-bold" class="me-1"></iconify-icon>Sin Goce</span>';
                    }
                },
                {
                    "data": "lic_dias_maximos",
                    "className": "text-center",
                    "render": function(data) {
                        return data ?
                            `<span class="badge bg-light text-dark border font-monospace fw-bold">${data} Días</span>` :
                            '<span class="text-muted small">No parametrizado</span>';
                    }
                },
                {
                    "data": "lic_estado",
                    "render": function(data) {
                        return data == 1 ?
                            '<span class="badge bg-success-subtle text-success border border-success-subtle">Habilitado</span>' :
                            '<span class="badge bg-danger-subtle text-danger border border-danger-subtle">Deshabilitado</span>';
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
                                    onclick="eliminarLicencia(${row.lic_ide})" title="Eliminar" style="width: 32px; height: 32px;">
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

        $('#formLicencia').on('submit', function(e) {
            e.preventDefault();
            guardarLicencia();
        });
    });

    function filtrarTablas() {
        tabla.ajax.reload(null, false);
    }

    function abrirModalCrear() {
        $('#formLicencia')[0].reset();
        $('#lic_ide').val('');
        $('#lic_estado').prop('checked', true);
        $('#modalTitulo').text('Nuevo Tipo de Licencia / Permiso');
        modalLicencia.show();
    }

    function abrirModalEditar(data) {
        $('#formLicencia')[0].reset();
        $('#lic_ide').val(data.lic_ide);
        $('#lic_nombre').val(data.lic_nombre);
        $('#lic_abreviatura').val(data.lic_abreviatura || '');
        $('#lic_descripcion').val(data.lic_descripcion || '');
        $('#lic_dias_maximos').val(data.lic_dias_maximos || '');
        $('#lic_remunerado').val(data.lic_remunerado);
        $('#lic_estado').prop('checked', data.lic_estado == 1);

        $('#modalTitulo').text('Editar Tipo de Licencia');
        modalLicencia.show();
    }

    function guardarLicencia() {
        const id = $('#lic_ide').val();
        const esEditar = id !== "";
        const url = esEditar ? `${urlBaseApi}/${id}` : urlBaseApi;
        const metodo = esEditar ? 'PUT' : 'POST';

        $('#btnGuardar').prop('disabled', true).text('Guardando...');

        const datos = {
            lic_nombre: $('#lic_nombre').val(),
            lic_abreviatura: $('#lic_abreviatura').val().toUpperCase(),
            lic_descripcion: $('#lic_descripcion').val(),
            lic_dias_maximos: $('#lic_dias_maximos').val(),
            lic_remunerado: $('#lic_remunerado').val(),
            lic_estado: $('#lic_estado').is(':checked') ? 1 : 0
        };

        $.ajax({
            url: url,
            type: metodo,
            data: datos,
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    modalLicencia.hide();
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

    function eliminarLicencia(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "El tipo de licencia se enviará a la papelera. No estará disponible para nuevas solicitudes de los colaboradores.",
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