<?= $this->extend('layouts/seleccionLayout') ?>

<?= $this->section('title') ?>
Gestión de Convocatorias
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:file-text-bold" class="me-2 text-primary" style="font-size: 1.5rem;"></iconify-icon>
                Gestión de Convocatorias
            </h4>
            <p class="text-muted mb-0 small">Administración y seguimiento de procesos de selección de personal</p>
        </div>
        <button class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="abrirModalCrear()">
            <iconify-icon icon="lucide:plus" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
            Nueva Convocatoria
        </button>
    </div>

    <div class="table-responsive">
        <table id="tablaConvocatorias" class="table table-hover align-middle w-100">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Número</th>
                    <th>Nombre / Denominación</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Año</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- MODAL: CREAR / EDITAR -->
<div class="modal fade" id="modalConvocatoria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitulo">Nueva Convocatoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formConvocatoria">
                <div class="modal-body py-3">
                    <input type="hidden" id="con_ide">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Código <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="con_codigo" required placeholder="Ej. CAS-001">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Número <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="con_numero" required placeholder="Ej. 001-2026">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Año <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="con_anio" value="<?= date('Y') ?>" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Nombre / Denominación <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="con_nombre" required placeholder="Ej. Convocatoria CAS para Personal de Salud">
                        </div>

                        <!-- Carga Dinámica de Catálogos -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tipo de Convocatoria <span class="text-danger">*</span></label>
                            <select class="form-select" id="con_tco_ide" required>
                                <option value="">Cargando tipos...</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Estado de Convocatoria <span class="text-danger">*</span></label>
                            <select class="form-select" id="con_eco_ide" required>
                                <option value="">Cargando estados...</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Fecha Publicación</label>
                            <input type="date" class="form-control" id="con_fecha_publicacion">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Fecha Inicio</label>
                            <input type="date" class="form-control" id="con_fecha_inicio">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Fecha Cierre</label>
                            <input type="date" class="form-control" id="con_fecha_cierre">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Observaciones</label>
                            <textarea class="form-control" id="con_observacion" rows="2" placeholder="Detalles adicionales opcionales..."></textarea>
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
    const urlBaseApi = '<?= base_url() ?>/asistencia/gestordb/api/convocatorias';
    const urlTiposLookup = '<?= base_url() ?>/asistencia/gestordb/api/tipos-convocatoria-lookup';
    const urlEstadosLookup = '<?= base_url() ?>/asistencia/gestordb/api/estados-convocatoria-lookup';

    let tabla;
    let modalConvocatoria = new bootstrap.Modal(document.getElementById('modalConvocatoria'));

    $(document).ready(function() {
        // Cargar Catálogos dinámicos
        cargarTiposConvocatoria();
        cargarEstadosConvocatoria();

        // DataTable
        tabla = $('#tablaConvocatorias').DataTable({
            "ajax": {
                "url": urlBaseApi,
                "type": "GET",
                "dataSrc": "data"
            },
            "columns": [{
                    "data": "con_codigo",
                    "className": "fw-bold text-dark"
                },
                {
                    "data": "con_numero"
                },
                {
                    "data": "con_nombre"
                },
                {
                    "data": "tipo_nombre",
                    "className": "small fw-semibold",
                    "render": function(data) {
                        return data ? data : '<span class="text-muted">-</span>';
                    }
                },
                {
                    "data": "estado_nombre",
                    "render": function(data) {
                        return data ? `<span class="badge bg-info-subtle text-info border border-info-subtle px-2.5">${data}</span>` : '<span class="text-muted">-</span>';
                    }
                },
                {
                    "data": "con_anio"
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
                                    onclick="eliminarConvocatoria(${row.con_ide})" title="Eliminar" style="width: 32px; height: 32px;">
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

        $('#formConvocatoria').on('submit', function(e) {
            e.preventDefault();
            guardarConvocatoria();
        });
    });

    // 1. Funciones para cargar catálogos
    function cargarTiposConvocatoria() {
        $.ajax({
            url: urlTiposLookup,
            type: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    let options = '<option value="">Seleccione tipo...</option>';
                    response.data.forEach(function(item) {
                        options += `<option value="${item.id}">${item.nombre}</option>`;
                    });
                    $('#con_tco_ide').html(options);
                }
            }
        });
    }

    function cargarEstadosConvocatoria() {
        $.ajax({
            url: urlEstadosLookup,
            type: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    let options = '<option value="">Seleccione estado...</option>';
                    response.data.forEach(function(item) {
                        options += `<option value="${item.id}">${item.nombre}</option>`;
                    });
                    $('#con_eco_ide').html(options);
                }
            }
        });
    }

    // 2. Modales
    function abrirModalCrear() {
        $('#formConvocatoria')[0].reset();
        $('#con_ide').val('');
        $('#con_anio').val(new Date().getFullYear());
        $('#modalTitulo').text('Nueva Convocatoria');
        modalConvocatoria.show();
    }

    function abrirModalEditar(data) {
        $('#formConvocatoria')[0].reset();
        $('#con_ide').val(data.con_ide);
        $('#con_codigo').val(data.con_codigo);
        $('#con_numero').val(data.con_numero);
        $('#con_anio').val(data.con_anio);
        $('#con_nombre').val(data.con_nombre);
        $('#con_tco_ide').val(data.con_tco_ide);
        $('#con_eco_ide').val(data.con_eco_ide);
        $('#con_fecha_publicacion').val(data.con_fecha_publicacion);
        $('#con_fecha_inicio').val(data.con_fecha_inicio);
        $('#con_fecha_cierre').val(data.con_fecha_cierre);
        $('#con_observacion').val(data.con_observacion);

        $('#modalTitulo').text('Editar Convocatoria');
        modalConvocatoria.show();
    }

    // 3. Persistencia
    function guardarConvocatoria() {
        const id = $('#con_ide').val();
        const esEditar = id !== "";
        const url = esEditar ? `${urlBaseApi}/${id}` : urlBaseApi;
        const metodo = esEditar ? 'PUT' : 'POST';

        $('#btnGuardar').prop('disabled', true).text('Guardando...');

        const datos = {
            con_codigo: $('#con_codigo').val(),
            con_numero: $('#con_numero').val(),
            con_anio: $('#con_anio').val(),
            con_nombre: $('#con_nombre').val(),
            con_tco_ide: $('#con_tco_ide').val(),
            con_eco_ide: $('#con_eco_ide').val(),
            con_fecha_publicacion: $('#con_fecha_publicacion').val(),
            con_fecha_inicio: $('#con_fecha_inicio').val(),
            con_fecha_cierre: $('#con_fecha_cierre').val(),
            con_observacion: $('#con_observacion').val()
        };

        $.ajax({
            url: url,
            type: metodo,
            data: datos,
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    modalConvocatoria.hide();
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

    // 4. Eliminación
    function eliminarConvocatoria(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "La convocatoria se enviará a la papelera.",
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