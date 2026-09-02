<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?>
Modalidades de Contratación
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:document-text-bold" class="me-2 text-primary" style="font-size: 1.5rem;"></iconify-icon>
                Regímenes y Modalidades de Contrato
            </h4>
            <p class="text-muted mb-0 small">Administración de modalidades laborales (CAS, 276, 728) y marcos normativos de asistencia</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <select class="form-select form-select-sm rounded-pill px-3" id="filtroEstado" style="width: 140px;" onchange="filtrarPorEstado()">
                <option value="1" selected>Solo Activos</option>
                <option value="0">Solo Inactivos</option>
                <option value="">Todos</option>
            </select>

            <button class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="abrirModalCrear()">
                <iconify-icon icon="lucide:plus" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
                Nueva Modalidad
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table id="tablaModalidades" class="table table-hover align-middle w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Régimen / Modalidad</th>
                    <th>Base Legal</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- MODAL: CREAR / EDITAR -->
<div class="modal fade" id="modalModalidad" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitulo">Nueva Modalidad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formModalidad">
                <div class="modal-body py-3">
                    <input type="hidden" id="mco_ide">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Código Interno</label>
                            <input type="text" class="form-control" id="mco_codigo" required placeholder="Ej. CAS, 276, 728">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Nombre del Régimen / Modalidad</label>
                            <input type="text" class="form-control" id="mco_nombre" required maxlength="100" placeholder="Ej. Contratación Administrativa de Servicios">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Descripción del Régimen</label>
                            <textarea class="form-control" id="mco_descripcion" rows="2" placeholder="Detalles de cobertura u obligaciones del régimen"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Base Legal</label>
                            <input type="text" class="form-control" id="mco_base_legal" placeholder="Ej. D.L. N° 1057 / Ley N° 31131">
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="mco_estado" value="1" checked>
                                <label class="form-check-label small fw-bold" for="mco_estado">Modalidad Activa</label>
                                <div class="form-text text-muted" style="font-size: 0.75rem;">
                                    Las modalidades inactivas no se listarán en la asignación de nuevos contratos.
                                </div>
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
    const urlBaseApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/modalidades';

    let tabla;
    let modalModalidad = new bootstrap.Modal(document.getElementById('modalModalidad'));

    $(document).ready(function() {
        tabla = $('#tablaModalidades').DataTable({
            "ajax": {
                "url": urlBaseApi,
                "type": "GET",
                "data": function(d) {
                    d.estado = $('#filtroEstado').val();
                },
                "dataSrc": "data"
            },
            "columns": [{
                    "data": "mco_ide"
                },
                {
                    "data": "mco_codigo",
                    "className": "fw-bold text-dark font-monospace"
                },
                {
                    "data": "mco_nombre",
                    "className": "fw-bold text-dark",
                    "render": function(data, type, row) {
                        return `
                            <div>
                                <span>${data}</span>
                                ${row.mco_descripcion ? `<div class="text-muted fw-normal small text-truncate" style="max-width: 350px;">${row.mco_descripcion}</div>` : ''}
                            </div>
                        `;
                    }
                },
                {
                    "data": "mco_base_legal",
                    "render": function(data) {
                        return data ? `<span class="small text-muted font-monospace"><iconify-icon icon="lucide:gavel" class="me-1"></iconify-icon>${data}</span>` : '<span class="text-muted small">-</span>';
                    }
                },
                {
                    "data": "mco_estado",
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
                                    onclick="eliminarModalidad(${row.mco_ide})" title="Eliminar" style="width: 32px; height: 32px;">
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

        $('#formModalidad').on('submit', function(e) {
            e.preventDefault();
            guardarModalidad();
        });
    });

    function filtrarPorEstado() {
        tabla.ajax.reload(null, false);
    }

    function abrirModalCrear() {
        $('#formModalidad')[0].reset();
        $('#mco_ide').val('');
        $('#mco_estado').prop('checked', true);
        $('#modalTitulo').text('Nueva Modalidad Contractual');
        modalModalidad.show();
    }

    function abrirModalEditar(data) {
        $('#formModalidad')[0].reset();
        $('#mco_ide').val(data.mco_ide);
        $('#mco_codigo').val(data.mco_codigo);
        $('#mco_nombre').val(data.mco_nombre);
        $('#mco_descripcion').val(data.mco_descripcion || '');
        $('#mco_base_legal').val(data.mco_base_legal || '');
        $('#mco_estado').prop('checked', data.mco_estado == 1);

        $('#modalTitulo').text('Editar Modalidad Contractual');
        modalModalidad.show();
    }

    function guardarModalidad() {
        const id = $('#mco_ide').val();
        const esEditar = id !== "";
        const url = esEditar ? `${urlBaseApi}/${id}` : urlBaseApi;
        const metodo = esEditar ? 'PUT' : 'POST';

        $('#btnGuardar').prop('disabled', true).text('Guardando...');

        const datos = {
            mco_codigo: $('#mco_codigo').val(),
            mco_nombre: $('#mco_nombre').val(),
            mco_descripcion: $('#mco_descripcion').val(),
            mco_base_legal: $('#mco_base_legal').val(),
            mco_estado: $('#mco_estado').is(':checked') ? 1 : 0
        };

        $.ajax({
            url: url,
            type: metodo,
            data: datos,
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    modalModalidad.hide();
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

    function eliminarModalidad(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "La modalidad se enviará a la papelera. Se mantendrán los contratos históricos asignados, pero no estará disponible para nuevos ingresos.",
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