<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?>
Redes de Salud
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:globus-bold" class="me-2 text-primary" style="font-size: 1.5rem;"></iconify-icon>
                Redes de Salud
            </h4>
            <p class="text-muted mb-0 small">Mantenimiento de Redes de Salud y vinculación regional con DIRESA</p>
        </div>
        <button class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="abrirModalCrear()">
            <iconify-icon icon="lucide:plus" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
            Nueva Red
        </button>
    </div>

    <div class="table-responsive">
        <table id="tablaRedes" class="table table-hover align-middle w-100">
            <thead >
                <tr>
                    <th>ID</th>
                    <th>DIRESA</th>
                    <th>Código</th>
                    <th>Nombre de Red</th>
                    <th>Director</th>
                    <th>Ubicación</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalRed" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitulo">Nueva Red de Salud</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formRed">
                <div class="modal-body py-3">
                    <input type="hidden" id="red_ide">

                    <div class="mb-3">
                        <label class="form-label small fw-bold">DIRESA de Dependencia</label>
                        <select class="form-select" id="red_dir_ide" required>
                            <option value="">Cargando DIRESAs...</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold">Código Red</label>
                            <input type="text" class="form-control" id="red_codigo" placeholder="Ej. R-01">
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label small fw-bold">Nombre de la Red</label>
                            <input type="text" class="form-control" id="red_nombre" required maxlength="150" placeholder="Ej. RED SAN ROMÁN">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Director / Encargado</label>
                        <input type="text" class="form-control" id="red_director" placeholder="Nombre completo del director">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Dirección / Ubicación</label>
                        <input type="text" class="form-control" id="red_ubicacion" placeholder="Dirección física">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Teléfono</label>
                            <input type="text" class="form-control" id="red_telefono" placeholder="Número de contacto">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Email</label>
                            <input type="email" class="form-control" id="red_email" placeholder="correo@red.gob.pe">
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
    const urlBaseApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/redes';
    const urlDiresaApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/diresas';

    let tabla;
    let modalElement = document.getElementById('modalRed');
    let modalRed = new bootstrap.Modal(modalElement);

    $(document).ready(function() {
        // Inicializar tabla usando el helper de la URL base
        tabla = $('#tablaRedes').DataTable({
            "ajax": {
                "url": urlBaseApi,
                "type": "GET",
                "dataSrc": "data"
            },
            "columns": [{
                    "data": "red_ide"
                },
                {
                    "data": "diresa_nombre",
                    "render": function(data) {
                        return data ? `<span class="badge bg-light text-dark border border-secondary-subtle">${data}</span>` : '<span class="text-danger">Sin DIRESA</span>';
                    }
                },
                {
                    "data": "red_codigo"
                },
                {
                    "data": "red_nombre"
                },
                {
                    "data": "red_director",
                    "render": function(data) {
                        return data ? data : '<span class="text-muted">No asignado</span>';
                    }
                },
                {
                    "data": "red_ubicacion"
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
                                    onclick="eliminarRed(${row.red_ide})" title="Eliminar" style="width: 32px; height: 32px;">
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

        // Carga de DIRESAs al iniciar
        cargarDiresas();

        $('#formRed').on('submit', function(e) {
            e.preventDefault();
            guardarRed();
        });
    });

    // Cargar selectores dinámicos usando la ruta base de DIRESA configurada
    function cargarDiresas() {
        $.ajax({
            url: urlDiresaApi,
            type: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    let select = $('#red_dir_ide');
                    select.empty().append('<option value="">Seleccione DIRESA...</option>');
                    response.data.forEach(function(item) {
                        select.append(`<option value="${item.dir_ide}">${item.dir_nombre}</option>`);
                    });
                }
            },
            error: function() {
                toastr.error('No se pudieron cargar las DIRESAs.');
            }
        });
    }

    function abrirModalCrear() {
        $('#formRed')[0].reset();
        $('#red_ide').val('');
        $('#modalTitulo').text('Nueva Red de Salud');
        modalRed.show();
    }

    function abrirModalEditar(data) {
        $('#red_ide').val(data.red_ide);
        $('#red_dir_ide').val(data.red_dir_ide);
        $('#red_codigo').val(data.red_codigo);
        $('#red_nombre').val(data.red_nombre);
        $('#red_director').val(data.red_director);
        $('#red_ubicacion').val(data.red_ubicacion);
        $('#red_telefono').val(data.red_telefono);
        $('#red_email').val(data.red_email);

        $('#modalTitulo').text('Editar Red de Salud');
        modalRed.show();
    }

    function guardarRed() {
        const id = $('#red_ide').val();
        const esEditar = id !== "";
        const url = esEditar ? `${urlBaseApi}/${id}` : urlBaseApi;
        const metodo = esEditar ? 'PUT' : 'POST';

        $('#btnGuardar').prop('disabled', true).text('Guardando...');

        const datos = {
            red_dir_ide: $('#red_dir_ide').val(),
            red_codigo: $('#red_codigo').val(),
            red_nombre: $('#red_nombre').val(),
            red_director: $('#red_director').val(),
            red_ubicacion: $('#red_ubicacion').val(),
            red_telefono: $('#red_telefono').val(),
            red_email: $('#red_email').val()
        };

        $.ajax({
            url: url,
            type: metodo,
            data: datos,
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    modalRed.hide();
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

    function eliminarRed(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta red de salud se enviará a la papelera.",
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