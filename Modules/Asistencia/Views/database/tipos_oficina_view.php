<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?>
Tipos de Oficina
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:folder-list-bold" class="me-2 text-primary" style="font-size: 1.5rem;"></iconify-icon>
                Tipos de Oficina / Áreas
            </h4>
            <p class="text-muted mb-0 small">Configuración de clasificaciones internas para las oficinas del sector</p>
        </div>
        <button class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="abrirModalCrear()">
            <iconify-icon icon="lucide:plus" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
            Nuevo Tipo
        </button>
    </div>

    <div class="table-responsive">
        <table id="tablaTiposOficina" class="table table-striped table-hover align-middle w-100">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Nombre Clasificación</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalTipoOficina" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitulo">Nuevo Tipo de Oficina</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTipoOficina">
                <div class="modal-body py-3">
                    <input type="hidden" id="tofi_ide">
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Código</label>
                            <input type="text" class="form-control" id="tofi_codigo" placeholder="Ej. ADM, ASIS">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Nombre del Tipo</label>
                            <input type="text" class="form-control" id="tofi_nombre" required maxlength="100" placeholder="Ej. Administrativo">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Descripción / Notas</label>
                            <textarea class="form-control" id="tofi_descripcion" rows="3" placeholder="Detalles sobre este tipo de área..."></textarea>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="tofi_estado" checked>
                                <label class="form-check-label small fw-bold" for="tofi_estado">Clasificación Activa</label>
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
    const urlBaseApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/tipos-oficina';
    
    let tabla;
    let modalTipoOficina = new bootstrap.Modal(document.getElementById('modalTipoOficina'));

    $(document).ready(function() {
        tabla = $('#tablaTiposOficina').DataTable({
            "ajax": {
                "url": urlBaseApi,
                "type": "GET",
                "dataSrc": "data"
            },
            "columns": [
                { "data": "tofi_ide" },
                { 
                    "data": "tofi_codigo",
                    "render": function(data) { 
                        return data ? `<span class="badge bg-light text-secondary border fw-bold">${data}</span>` : '<span class="text-muted">-</span>'; 
                    }
                },
                { "data": "tofi_nombre", "className": "fw-bold text-dark" },
                { 
                    "data": "tofi_descripcion",
                    "render": function(data) { return data ? data : '<span class="text-muted small">Sin descripción</span>'; }
                },
                { 
                    "data": "tofi_estado",
                    "render": function(data) {
                        return data == 1 
                            ? '<span class="badge bg-success-subtle text-success border border-success-subtle px-2.5">Activo</span>' 
                            : '<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5">Inactivo</span>';
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
                                    onclick="eliminarTipoOficina(${row.tofi_ide})" title="Eliminar" style="width: 32px; height: 32px;">
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

        $('#formTipoOficina').on('submit', function(e) {
            e.preventDefault();
            guardarTipoOficina();
        });
    });

    function abrirModalCrear() {
        $('#formTipoOficina')[0].reset();
        $('#tofi_ide').val('');
        $('#tofi_estado').prop('checked', true);
        $('#modalTitulo').text('Nuevo Tipo de Oficina');
        modalTipoOficina.show();
    }

    function abrirModalEditar(data) {
        $('#formTipoOficina')[0].reset();
        $('#tofi_ide').val(data.tofi_ide);
        $('#tofi_codigo').val(data.tofi_codigo);
        $('#tofi_nombre').val(data.tofi_nombre);
        $('#tofi_descripcion').val(data.tofi_descripcion);
        $('#tofi_estado').prop('checked', data.tofi_estado == 1);

        $('#modalTitulo').text('Editar Tipo de Oficina');
        modalTipoOficina.show();
    }

    function guardarTipoOficina() {
        const id = $('#tofi_ide').val();
        const esEditar = id !== "";
        const url = esEditar ? `${urlBaseApi}/${id}` : urlBaseApi;
        const metodo = esEditar ? 'PUT' : 'POST';

        $('#btnGuardar').prop('disabled', true).text('Guardando...');

        const datos = {
            tofi_codigo: $('#tofi_codigo').val(),
            tofi_nombre: $('#tofi_nombre').val(),
            tofi_descripcion: $('#tofi_descripcion').val(),
            tofi_estado: $('#tofi_estado').is(':checked') ? 1 : 0
        };

        $.ajax({
            url: url,
            type: metodo,
            data: datos,
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    modalTipoOficina.hide();
                    tabla.ajax.reload(null, false);
                }
            },
            error: function(xhr) {
                let errorMsg = 'Error en el servidor.';
                if (xhr.responseJSON && xhr.responseJSON.messages) {
                    errorMsg = Object.values(xhr.responseJSON.messages).join('<br>');
                }
                Swal.fire({ icon: 'error', title: '¡Error!', html: errorMsg });
            },
            complete: function() {
                $('#btnGuardar').prop('disabled', false).text('Guardar');
            }
        });
    }

    function eliminarTipoOficina(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "El registro se enviará a la papelera.",
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