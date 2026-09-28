<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?>
Colegiaturas Profesionales
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:bookmark-square-minimalistic-bold" class="me-2 text-primary" style="font-size: 1.5rem;"></iconify-icon>
                Colegiaturas Profesionales
            </h4>
            <p class="text-muted mb-0 small">Asociación de colegios oficiales y siglas de colegiaturas según el perfil o profesión</p>
        </div>
        <button class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="abrirModalCrear()">
            <iconify-icon icon="lucide:plus" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
            Nueva Colegiatura
        </button>
    </div>

    <div class="table-responsive">
        <table id="tablaColegiaturas" class="table table-hover align-middle w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Profesión Asociada</th>
                    <th>Colegiatura / Entidad</th>
                    <th>Sigla / Abreviatura</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- MODAL: CREAR / EDITAR -->
<div class="modal fade" id="modalColegiatura" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitulo">Nueva Colegiatura</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formColegiatura">
                <div class="modal-body py-3">
                    <input type="hidden" id="col_ide">

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Profesión Relacionada</label>
                            <select class="form-select" id="col_pro_ide" required>
                                <option value="">Seleccione una profesión...</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Nombre del Colegio Profesional</label>
                            <input type="text" class="form-control" id="col_nombre" required maxlength="150" placeholder="Ej. Colegio Médico del Perú">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Abreviatura / Siglas</label>
                            <input type="text" class="form-control" id="col_abreviatura" placeholder="Ej. CMP / CEP">
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" role="switch" id="col_estado" checked>
                                <label class="form-check-label small fw-bold" for="col_estado">Activo</label>
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
    const urlBaseApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/colegiaturas';
    const urlProfesionesLookup = '<?= base_url() ?>' + '/asistencia/gestordb/api/profesiones-lookup';

    let tabla;
    let modalColegiatura = new bootstrap.Modal(document.getElementById('modalColegiatura'));

    $(document).ready(function() {
        // Cargar Catálogo de Profesiones en el selector
        cargarProfesiones();

        tabla = $('#tablaColegiaturas').DataTable({
            "ajax": {
                "url": urlBaseApi,
                "type": "GET",
                "dataSrc": "data"
            },
            "columns": [{
                    "data": "col_ide"
                },
                {
                    "data": "profesion_nombre",
                    "className": "text-secondary small fw-semibold",
                    "render": function(data) {
                        return data ? data : '<span class="text-danger">No asignada</span>';
                    }
                },
                {
                    "data": "col_nombre",
                    "className": "fw-bold text-dark"
                },
                {
                    "data": "col_abreviatura",
                    "render": function(data) {
                        return data ? `<span class="badge bg-light text-primary border border-primary-subtle fw-bold">${data}</span>` : '<span class="text-muted">-</span>';
                    }
                },
                {
                    "data": "col_estado",
                    "render": function(data) {
                        return data == 1 ?
                            '<span class="badge bg-success-subtle text-success border border-success-subtle px-2.5">Activo</span>' :
                            '<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5">Inactivo</span>';
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
                                    onclick="eliminarColegiatura(${row.col_ide})" title="Eliminar" style="width: 32px; height: 32px;">
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

        $('#formColegiatura').on('submit', function(e) {
            e.preventDefault();
            guardarColegiatura();
        });
    });

    function cargarProfesiones() {
        $.ajax({
            url: urlProfesionesLookup,
            type: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    let options = '<option value="">Seleccione una profesión...</option>';
                    response.data.forEach(function(item) {
                        options += `<option value="${item.id}">${item.nombre}</option>`;
                    });
                    $('#col_pro_ide').html(options);
                }
            }
        });
    }

    function abrirModalCrear() {
        $('#formColegiatura')[0].reset();
        $('#col_ide').val('');
        $('#col_estado').prop('checked', true);
        $('#modalTitulo').text('Nueva Colegiatura');
        modalColegiatura.show();
    }

    function abrirModalEditar(data) {
        $('#formColegiatura')[0].reset();
        $('#col_ide').val(data.col_ide);
        $('#col_pro_ide').val(data.col_pro_ide);
        $('#col_nombre').val(data.col_nombre);
        $('#col_abreviatura').val(data.col_abreviatura);
        $('#col_estado').prop('checked', data.col_estado == 1);

        $('#modalTitulo').text('Editar Colegiatura');
        modalColegiatura.show();
    }

    function guardarColegiatura() {
        const id = $('#col_ide').val();
        const esEditar = id !== "";
        const url = esEditar ? `${urlBaseApi}/${id}` : urlBaseApi;
        const metodo = esEditar ? 'PUT' : 'POST';

        $('#btnGuardar').prop('disabled', true).text('Guardando...');

        const datos = {
            col_pro_ide: $('#col_pro_ide').val(),
            col_nombre: $('#col_nombre').val(),
            col_abreviatura: $('#col_abreviatura').val(),
            col_estado: $('#col_estado').is(':checked') ? 1 : 0
        };

        $.ajax({
            url: url,
            type: metodo,
            data: datos,
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    modalColegiatura.hide();
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

    function eliminarColegiatura(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "La colegiatura se enviará a la papelera.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sí, eliminar'
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