<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?>
Gestión de Servicios Hospitalarios
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:stethoscope-bold-duotone" class="me-2 text-primary" style="font-size: 1.5rem;"></iconify-icon>
                Servicios y Departamentos
            </h4>
            <p class="text-muted mb-0 small">Estructure las sub-unidades operativas e interconecte cada servicio con su respectiva UPSS oficial.</p>
        </div>
        <div>
            <button class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="abrirModalCrear()">
                <iconify-icon icon="lucide:plus" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
                Nuevo Servicio
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table id="tablaServicios" class="table table-hover align-middle w-100">
            <thead>
                <tr>
                    <th style="width: 70px;">ID</th>
                    <th style="width: 140px;">Abreviatura</th>
                    <th>Servicio / Unidad</th>
                    <th>Departamento Eje</th>
                    <th>UPSS Contenedora</th>
                    <th>Descripción Corta</th>
                    <th class="text-end" style="width: 110px;">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- MODAL: CREAR / EDITAR -->
<div class="modal fade" id="modalServicio" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitulo">Nuevo Servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formServicio">
                <div class="modal-body py-3">
                    <input type="hidden" id="ser_ide">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Abreviatura</label>
                            <input type="text" class="form-control font-monospace text-uppercase text-center" id="ser_abreviatura" required maxlength="50" placeholder="Ej. PEDIAT">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Nombre del Servicio</label>
                            <input type="text" class="form-control" id="ser_nombre" required maxlength="100" placeholder="Ej. Pediatría Especializada">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Departamento de Adscripción</label>
                            <input type="text" class="form-control" id="ser_departamento" maxlength="100" placeholder="Ej. Departamento de Medicina">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">UPSS Relacionada</label>
                            <select class="form-select" id="ser_upss">
                                <option value="" selected>Ninguna / No aplica</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Detalle o Descripción</label>
                            <textarea class="form-control" id="ser_descripcion" rows="2" placeholder="Información operativa interna complementaria..."></textarea>
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
    const urlBaseApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/servicios';
    const urlUpssApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/upss';

    let tabla;
    let modalServicio = new bootstrap.Modal(document.getElementById('modalServicio'));

    $(document).ready(function() {
        // Cargar Catálogo de UPSS activas para el selector
        cargarSelectUpss();

        // Inicializar DataTables
        tabla = $('#tablaServicios').DataTable({
            "ajax": {
                "url": urlBaseApi,
                "type": "GET",
                "dataSrc": "data"
            },
            "columns": [{
                    "data": "ser_ide"
                },
                {
                    "data": "ser_abreviatura",
                    "className": "fw-bold font-monospace text-center",
                    "render": function(data) {
                        return `<span class="bg-light border px-2 py-1 rounded text-uppercase text-dark" style="font-size: 0.8rem;">${data}</span>`;
                    }
                },
                {
                    "data": "ser_nombre",
                    "className": "fw-bold text-dark"
                },
                {
                    "data": "ser_departamento",
                    "render": function(data) {
                        return data ? `<span class="text-secondary small">${data}</span>` : '<span class="text-muted small">Sin asignar</span>';
                    }
                },
                {
                    "data": "ups_nombre",
                    "render": function(data, type, row) {
                        return data ?
                            `<div class="small fw-semibold text-primary"><iconify-icon icon="solar:hospital-outline" class="me-1"></iconify-icon>${data} <span class="text-muted font-monospace" style="font-size: 0.75rem;">[${row.ups_codigo}]</span></div>` :
                            '<span class="text-muted small">Sin vínculo UPSS</span>';
                    }
                },
                {
                    "data": "ser_descripcion",
                    "className": "text-muted small",
                    "render": function(data) {
                        return data || '-';
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
                                    onclick="eliminarServicio(${row.ser_ide})" title="Eliminar" style="width: 32px; height: 32px;">
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

        $('#formServicio').on('submit', function(e) {
            e.preventDefault();
            guardarServicio();
        });
    });

    function cargarSelectUpss() {
        $.ajax({
            url: urlUpssApi,
            type: 'GET',
            data: {
                estado: 1
            },
            success: function(response) {
                if (response.status === 'success') {
                    const select = $('#ser_upss');
                    select.find('option:not(:first)').remove();
                    response.data.forEach(function(item) {
                        select.append(`<option value="${item.ups_ide}">${item.ups_nombre} [${item.ups_codigo}]</option>`);
                    });
                }
            }
        });
    }

    function abrirModalCrear() {
        $('#formServicio')[0].reset();
        $('#ser_ide').val('');
        $('#modalTitulo').text('Nuevo Servicio / Unidad');
        modalServicio.show();
    }

    function abrirModalEditar(data) {
        $('#formServicio')[0].reset();
        $('#ser_ide').val(data.ser_ide);
        $('#ser_abreviatura').val(data.ser_abreviatura);
        $('#ser_nombre').val(data.ser_nombre);
        $('#ser_departamento').val(data.ser_departamento);
        $('#ser_upss').val(data.ser_upss || '');
        $('#ser_descripcion').val(data.ser_descripcion);
        $('#modalTitulo').text('Editar Servicio / Unidad');
        modalServicio.show();
    }

    function guardarServicio() {
        const id = $('#ser_ide').val();
        const esEditar = id !== "";
        const url = esEditar ? `${urlBaseApi}/${id}` : urlBaseApi;
        const metodo = esEditar ? 'PUT' : 'POST';

        $('#btnGuardar').prop('disabled', true).text('Guardando...');

        const datos = {
            ser_abreviatura: $('#ser_abreviatura').val().toUpperCase(),
            ser_nombre: $('#ser_nombre').val(),
            ser_departamento: $('#ser_departamento').val(),
            ser_upss: $('#ser_upss').val() || null,
            ser_descripcion: $('#ser_descripcion').val()
        };

        $.ajax({
            url: url,
            type: metodo,
            data: datos,
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    modalServicio.hide();
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

    function eliminarServicio(id) {
        Swal.fire({
            title: '¿Proceder con la eliminación?',
            text: "Esta acción destruirá el registro físico del servicio de forma inmediata.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sí, eliminar permanentemente'
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