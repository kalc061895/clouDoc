<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?>
Catálogo de Segundas Especialidades
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:diploma-verified-bold-duotone" class="me-2 text-primary" style="font-size: 1.5rem;"></iconify-icon>
                Segundas Especialidades Médicas y Asistenciales
            </h4>
            <p class="text-muted mb-0 small">Gestiona el catálogo de especialidades de postgrado del personal asistencial para asignaciones de guardia avanzadas. (Soft Delete Activo).</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <select class="form-select form-select-sm rounded-pill px-3" id="filtroEstado" style="width: 150px;" onchange="filtrarEspecialidades()">
                <option value="" selected>Todos los estados</option>
                <option value="1">Activos</option>
                <option value="0">Inactivos</option>
            </select>

            <button class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="abrirModalCrear()">
                <iconify-icon icon="lucide:plus" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
                Nueva Especialidad
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table id="tablaEspecialidades" class="table table-hover align-middle w-100">
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Especialidad / Título Profesional de Postgrado</th>
                    <th style="width: 180px;">Abreviatura</th>
                    <th style="width: 130px;">Estado</th>
                    <th class="text-end" style="width: 110px;">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- MODAL: CREAR / EDITAR -->
<div class="modal fade" id="modalEspecialidad" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitulo">Nueva Segunda Especialidad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEspecialidad">
                <div class="modal-body py-3">
                    <input type="hidden" id="se_ide">

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Denominación de la Especialidad</label>
                            <input type="text" class="form-control" id="se_nombre" required maxlength="200" placeholder="Ej. MEDICINA INTENSIVA">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Abreviatura (Opcional)</label>
                            <input type="text" class="form-control text-uppercase font-monospace" id="se_abreviatura" maxlength="50" placeholder="Ej. MED-INT">
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="se_estado" value="1" checked>
                                <label class="form-check-label small fw-bold" for="se_estado">Especialidad habilitada para asignación de personal</label>
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
    const urlBaseApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/segundas-especialidades';

    let tabla;
    let modalEspecialidad = new bootstrap.Modal(document.getElementById('modalEspecialidad'));

    $(document).ready(function() {
        tabla = $('#tablaEspecialidades').DataTable({
            "ajax": {
                "url": urlBaseApi,
                "type": "GET",
                "data": function(d) {
                    d.estado = $('#filtroEstado').val();
                },
                "dataSrc": "data"
            },
            "columns": [{
                    "data": "se_ide"
                },
                {
                    "data": "se_nombre",
                    "className": "fw-bold text-dark text-uppercase"
                },
                {
                    "data": "se_abreviatura",
                    "className": "font-monospace text-uppercase fw-semibold text-secondary",
                    "render": function(data) {
                        return data ? `<span class="bg-light border px-2 py-1 rounded small">${data}</span>` : '<span class="text-muted small">-</span>';
                    }
                },
                {
                    "data": "se_estado",
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
                                    onclick="eliminarEspecialidad(${row.se_ide})" title="Archivar" style="width: 32px; height: 32px;">
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

        $('#formEspecialidad').on('submit', function(e) {
            e.preventDefault();
            guardarEspecialidad();
        });
    });

    function filtrarEspecialidades() {
        tabla.ajax.reload(null, false);
    }

    function abrirModalCrear() {
        $('#formEspecialidad')[0].reset();
        $('#se_ide').val('');
        $('#se_estado').prop('checked', true);
        $('#modalTitulo').text('Nueva Segunda Especialidad');
        modalEspecialidad.show();
    }

    function abrirModalEditar(data) {
        $('#formEspecialidad')[0].reset();
        $('#se_ide').val(data.se_ide);
        $('#se_nombre').val(data.se_nombre);
        $('#se_abreviatura').val(data.se_abreviatura || '');
        $('#se_estado').prop('checked', data.se_estado == 1);
        $('#modalTitulo').text('Editar Segunda Especialidad');
        modalEspecialidad.show();
    }

    function guardarEspecialidad() {
        const id = $('#se_ide').val();
        const esEditar = id !== "";
        const url = esEditar ? `${urlBaseApi}/${id}` : urlBaseApi;
        const metodo = esEditar ? 'PUT' : 'POST';

        $('#btnGuardar').prop('disabled', true).text('Guardando...');

        const datos = {
            se_nombre: $('#se_nombre').val(),
            se_abreviatura: $('#se_abreviatura').val(),
            se_estado: $('#se_estado').is(':checked') ? 1 : 0
        };

        $.ajax({
            url: url,
            type: metodo,
            data: datos,
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    modalEspecialidad.hide();
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
                    title: '¡Error de Registro!',
                    html: errorMsg
                });
            },
            complete: function() {
                $('#btnGuardar').prop('disabled', false).text('Guardar');
            }
        });
    }

    function eliminarEspecialidad(id) {
        Swal.fire({
            title: '¿Archivar Especialidad?',
            text: "Se aplicará una baja lógica. Los históricos de personal vinculado no sufrirán alteraciones.",
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