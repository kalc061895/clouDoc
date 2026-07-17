<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?>
Tipos de Documento de Identidad
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:user-id-bold" class="me-2 text-primary" style="font-size: 1.5rem;"></iconify-icon>
                Tipos de Documento de Identidad
            </h4>
            <p class="text-muted mb-0 small">Configura las longitudes y descripciones de los documentos utilizados para el registro del personal (DNI, CE, etc.)</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <select class="form-select form-select-sm rounded-pill px-3" id="filtroEstado" style="width: 150px;" onchange="filtrarTablas()">
                <option value="" selected>Todos</option>
                <option value="1">Activos</option>
                <option value="0">Inactivos</option>
            </select>

            <button class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="abrirModalCrear()">
                <iconify-icon icon="lucide:plus" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
                Nuevo Documento
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table id="tablaDocumentos" class="table table-hover align-middle w-100">
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th style="width: 120px;">Abreviatura</th>
                    <th>Nombre de Documento</th>
                    <th>Longitud Caracteres</th>
                    <th>Estado</th>
                    <th class="text-end" style="width: 120px;">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- MODAL: CREAR / EDITAR -->
<div class="modal fade" id="modalDocumento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitulo">Nuevo Tipo de Documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formDocumento">
                <div class="modal-body py-3">
                    <input type="hidden" id="tdi_ide">

                    <div class="row g-3">
                        <div class="col-md-9">
                            <label class="form-label small fw-bold">Nombre del Documento</label>
                            <input type="text" class="form-control" id="tdi_nombre" required maxlength="100" placeholder="Ej. Documento Nacional de Identidad">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Abrev.</label>
                            <input type="text" class="form-control font-monospace text-uppercase text-center" id="tdi_abreviatura" maxlength="10" placeholder="DNI">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Longitud de Caracteres</label>
                            <input type="number" class="form-control" id="tdi_longitud" min="1" max="50" placeholder="Ej. 8 (para DNI)">
                            <div class="form-text text-muted" style="font-size: 0.7rem;">Define el número exacto o máximo de dígitos permitidos en los formularios de personal</div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="tdi_estado" value="1" checked>
                                <label class="form-check-label small fw-bold" for="tdi_estado">Habilitado para selección</label>
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
    const urlBaseApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/documentos';

    let tabla;
    let modalDocumento = new bootstrap.Modal(document.getElementById('modalDocumento'));

    $(document).ready(function() {
        tabla = $('#tablaDocumentos').DataTable({
            "ajax": {
                "url": urlBaseApi,
                "type": "GET",
                "data": function(d) {
                    d.estado = $('#filtroEstado').val();
                },
                "dataSrc": "data"
            },
            "columns": [{
                    "data": "tdi_ide"
                },
                {
                    "data": "tdi_abreviatura",
                    "className": "fw-bold font-monospace text-primary text-center",
                    "render": function(data) {
                        return data ? `[ ${data.toUpperCase()} ]` : '-';
                    }
                },
                {
                    "data": "tdi_nombre",
                    "className": "fw-bold text-dark"
                },
                {
                    "data": "tdi_longitud",
                    "className": "text-center",
                    "render": function(data) {
                        return data ?
                            `<span class="badge bg-light text-dark border font-monospace fw-bold">${data} caracteres</span>` :
                            '<span class="text-muted small">Variable</span>';
                    }
                },
                {
                    "data": "tdi_estado",
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
                                    onclick="eliminarDocumento(${row.tdi_ide})" title="Eliminar" style="width: 32px; height: 32px;">
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

        $('#formDocumento').on('submit', function(e) {
            e.preventDefault();
            guardarDocumento();
        });
    });

    function filtrarTablas() {
        tabla.ajax.reload(null, false);
    }

    function abrirModalCrear() {
        $('#formDocumento')[0].reset();
        $('#tdi_ide').val('');
        $('#tdi_estado').prop('checked', true);
        $('#modalTitulo').text('Nuevo Tipo de Documento');
        modalDocumento.show();
    }

    function abrirModalEditar(data) {
        $('#formDocumento')[0].reset();
        $('#tdi_ide').val(data.tdi_ide);
        $('#tdi_nombre').val(data.tdi_nombre);
        $('#tdi_abreviatura').val(data.tdi_abreviatura || '');
        $('#tdi_longitud').val(data.tdi_longitud || '');
        $('#tdi_estado').prop('checked', data.tdi_estado == 1);

        $('#modalTitulo').text('Editar Tipo de Documento');
        modalDocumento.show();
    }

    function guardarDocumento() {
        const id = $('#tdi_ide').val();
        const esEditar = id !== "";
        const url = esEditar ? `${urlBaseApi}/${id}` : urlBaseApi;
        const metodo = esEditar ? 'PUT' : 'POST';

        $('#btnGuardar').prop('disabled', true).text('Guardando...');

        const datos = {
            tdi_nombre: $('#tdi_nombre').val(),
            tdi_abreviatura: $('#tdi_abreviatura').val().toUpperCase(),
            tdi_longitud: $('#tdi_longitud').val(),
            tdi_estado: $('#tdi_estado').is(':checked') ? 1 : 0
        };

        $.ajax({
            url: url,
            type: metodo,
            data: datos,
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    modalDocumento.hide();
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

    function eliminarDocumento(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "El tipo de documento se archivará. No se podrá seleccionar en las fichas del nuevo personal.",
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