<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?>
Direcciones de Salud (DIRESA)
<?= $this->endSection() ?>



<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:hospital-bold" class="me-2 text-primary" style="font-size: 1.5rem;"></iconify-icon>
                Direcciones de Salud (DIRESA)
            </h4>
            <p class="text-muted mb-0 small">Configuración de las Direcciones Regionales de Salud del sistema</p>
        </div>
        <button class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="abrirModalCrear()">
            <iconify-icon icon="lucide:plus" class="me-1" style="font-size: 1.2rem;"></iconify-icon>
            Nueva Oficina
        </button>
    </div>

    <div class="table-responsive">
        <table id="tablaDiresas" class="table table-hover align-middle w-100 h-100">
            <thead >
                <tr>
                    <th>ID</th>
                    <th>Nombre de DIRESA</th>
                    <th>Director / Encargado</th>
                    <th>Ubicación</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalDiresa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitulo">Nueva DIRESA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formDiresa">
                <div class="modal-body py-3">
                    <input type="hidden" id="dir_ide">

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nombre de la DIRESA</label>
                        <input type="text" class="form-control" id="dir_nombre" required maxlength="150" placeholder="Ej. DIRESA PUNO">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nombre del Director</label>
                        <input type="text" class="form-control" id="dir_director" placeholder="Nombre completo del director">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Ubicación / Dirección</label>
                        <input type="text" class="form-control" id="dir_ubicacion" placeholder="Dirección física">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Teléfono</label>
                            <input type="text" class="form-control" id="dir_telefono" placeholder="Número de contacto">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Email</label>
                            <input type="email" class="form-control" id="dir_email" placeholder="correo@diresa.gob.pe">
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
    let tabla;
    let modalElement = document.getElementById('modalDiresa');
    let modalDiresa = new bootstrap.Modal(modalElement);

    $(document).ready(function() {
        tabla = $('#tablaDiresas').DataTable({
            "ajax": {
                "url": "<?= base_url('asistencia/gestordb/api/diresas') ?>",
                "type": "GET",
                "dataSrc": "data"
            },
            "columns": [{
                    "data": "dir_ide"
                },
                {
                    "data": "dir_nombre"
                },
                {
                    "data": "dir_director",
                    "render": function(data) {
                        return data ? data : '<span class="text-muted">No asignado</span>';
                    }
                },
                {
                    "data": "dir_ubicacion"
                },
                {
                    "data": "dir_telefono"
                },
                {
                    "data": "dir_email",
                    "render": function(data) {
                        return data ? `<a href="mailto:${data}" class="text-decoration-none">${data}</a>` : '';
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
                                    onclick="eliminarDiresa(${row.dir_ide})" title="Eliminar" style="width: 32px; height: 32px;">
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

        $('#formDiresa').on('submit', function(e) {
            e.preventDefault();
            guardarDiresa();
        });
    });

    function abrirModalCrear() {
        $('#formDiresa')[0].reset();
        $('#dir_ide').val('');
        $('#modalTitulo').text('Nueva DIRESA');
        modalDiresa.show();
    }

    function abrirModalEditar(data) {
        $('#dir_ide').val(data.dir_ide);
        $('#dir_nombre').val(data.dir_nombre);
        $('#dir_director').val(data.dir_director);
        $('#dir_ubicacion').val(data.dir_ubicacion);
        $('#dir_telefono').val(data.dir_telefono);
        $('#dir_email').val(data.dir_email);

        $('#modalTitulo').text('Editar DIRESA');
        modalDiresa.show();
    }

    function guardarDiresa() {
        const id = $('#dir_ide').val();
        const esEditar = id !== "";
        const url = esEditar ? "<?= base_url() ?>" + `/asistencia/gestordb/api/diresas/${id}` : "<?= base_url() ?>" + '/asistencia/gestordb/api/diresas';
        const metodo = esEditar ? 'PUT' : 'POST';

        $('#btnGuardar').prop('disabled', true).text('Guardando...');

        const datos = {
            dir_nombre: $('#dir_nombre').val(),
            dir_director: $('#dir_director').val(),
            dir_ubicacion: $('#dir_ubicacion').val(),
            dir_telefono: $('#dir_telefono').val(),
            dir_email: $('#dir_email').val()
        };

        $.ajax({
            url: url,
            type: metodo,
            data: datos,
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    modalDiresa.hide();
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

    function eliminarDiresa(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Este registro se enviará a la papelera (Soft Delete).",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sí, eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url() ?>" + `/asistencia/gestordb/api/diresas/${id}`,
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