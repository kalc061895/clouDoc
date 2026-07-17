<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?>
Profesiones / Especialidades
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:diploma-bold" class="me-2 text-primary" style="font-size: 1.5rem;"></iconify-icon>
                Profesiones y Especialidades
            </h4>
            <p class="text-muted mb-0 small">Mantenimiento de títulos, profesiones o especialidades del personal asistencial y administrativo</p>
        </div>
        <button class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="abrirModalCrear()">
            <iconify-icon icon="lucide:plus" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
            Nueva Profesión
        </button>
    </div>

    <div class="table-responsive">
        <!-- Removido 'table-striped' de la clase de la tabla -->
        <table id="tablaProfesiones" class="table table-hover align-middle w-100">
            <!-- Removido 'table-light' de thead -->
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Abreviatura</th>
                    <th>Nombre de la Profesión</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- MODAL: CREAR / EDITAR -->
<div class="modal fade" id="modalProfesion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitulo">Nueva Profesión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formProfesion">
                <div class="modal-body py-3">
                    <input type="hidden" id="pro_ide">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Código</label>
                            <input type="text" class="form-control" id="pro_codigo" placeholder="Ej. PROF-ENF">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Abreviatura</label>
                            <input type="text" class="form-control" id="pro_abreviatura" placeholder="Ej. Lic. / Dr.">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Nombre de la Profesión</label>
                            <input type="text" class="form-control" id="pro_nombre" required maxlength="200" placeholder="Ej. Licenciado en Enfermería">
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="pro_estado" checked>
                                <label class="form-check-label small fw-bold" for="pro_estado">Profesión Activa</label>
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
    const urlBaseApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/profesiones';

    let tabla;
    let modalProfesion = new bootstrap.Modal(document.getElementById('modalProfesion'));

    $(document).ready(function() {
        tabla = $('#tablaProfesiones').DataTable({
            "ajax": {
                "url": urlBaseApi,
                "type": "GET",
                "dataSrc": "data"
            },
            "columns": [{
                    "data": "pro_ide"
                },
                {
                    "data": "pro_codigo",
                    "render": function(data) {
                        return data ? `<span class="badge bg-light text-secondary border fw-bold">${data}</span>` : '<span class="text-muted">-</span>';
                    }
                },
                {
                    "data": "pro_abreviatura",
                    "render": function(data) {
                        return data ? `<span class="text-dark fw-semibold">${data}</span>` : '<span class="text-muted">-</span>';
                    }
                },
                {
                    "data": "pro_nombre",
                    "className": "fw-bold text-dark"
                },
                {
                    "data": "pro_estado",
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
                                    onclick="eliminarProfesion(${row.pro_ide})" title="Eliminar" style="width: 32px; height: 32px;">
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

        $('#formProfesion').on('submit', function(e) {
            e.preventDefault();
            guardarProfesion();
        });
    });

    function abrirModalCrear() {
        $('#formProfesion')[0].reset();
        $('#pro_ide').val('');
        $('#pro_estado').prop('checked', true);
        $('#modalTitulo').text('Nueva Profesión');
        modalProfesion.show();
    }

    function abrirModalEditar(data) {
        $('#formProfesion')[0].reset();
        $('#pro_ide').val(data.pro_ide);
        $('#pro_codigo').val(data.pro_codigo);
        $('#pro_abreviatura').val(data.pro_abreviatura);
        $('#pro_nombre').val(data.pro_nombre);
        $('#pro_estado').prop('checked', data.pro_estado == 1);

        $('#modalTitulo').text('Editar Profesión');
        modalProfesion.show();
    }

    function guardarProfesion() {
        const id = $('#pro_ide').val();
        const esEditar = id !== "";
        const url = esEditar ? `${urlBaseApi}/${id}` : urlBaseApi;
        const metodo = esEditar ? 'PUT' : 'POST';

        $('#btnGuardar').prop('disabled', true).text('Guardando...');

        const datos = {
            pro_codigo: $('#pro_codigo').val(),
            pro_abreviatura: $('#pro_abreviatura').val(),
            pro_nombre: $('#pro_nombre').val(),
            pro_estado: $('#pro_estado').is(':checked') ? 1 : 0
        };

        $.ajax({
            url: url,
            type: metodo,
            data: datos,
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    modalProfesion.hide();
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

    function eliminarProfesion(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "La profesión se enviará a la papelera.",
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