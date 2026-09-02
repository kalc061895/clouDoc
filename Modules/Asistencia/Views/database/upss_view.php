<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?>
Gestión de UPSS
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:hospital-bold-duotone" class="me-2 text-primary" style="font-size: 1.5rem;"></iconify-icon>
                Unidades Productoras de Servicios de Salud (UPSS)
            </h4>
            <p class="text-muted mb-0 small">Administra las UPSS oficiales del establecimiento de salud para categorizar funcionalmente los roles de guardia y programaciones.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <select class="form-select form-select-sm rounded-pill px-3" id="filtroEstado" style="width: 150px;" onchange="filtrarUpss()">
                <option value="" selected>Todos los estados</option>
                <option value="1">Activos</option>
                <option value="0">Inactivos</option>
            </select>

            <button class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="abrirModalCrear()">
                <iconify-icon icon="lucide:plus" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
                Nueva UPSS
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table id="tablaUpss" class="table table-hover align-middle w-100">
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th style="width: 120px;">Código</th>
                    <th>Unidad Productora (UPSS)</th>
                    <th style="width: 250px;">Agrupación Funcional</th>
                    <th style="width: 120px;">Abreviatura</th>
                    <th style="width: 110px;">Estado</th>
                    <th class="text-end" style="width: 110px;">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- MODAL: CREAR / EDITAR -->
<div class="modal fade" id="modalUpss" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitulo">Nueva UPSS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formUpss">
                <div class="modal-body py-3">
                    <input type="hidden" id="ups_ide">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Código UPSS</label>
                            <input type="text" class="form-control font-monospace text-uppercase text-center" id="ups_codigo" required maxlength="15" placeholder="Ej. 111101">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Nombre de la UPSS</label>
                            <input type="text" class="form-control" id="ups_nombre" required maxlength="200" placeholder="Ej. Consulta Externa">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Agrupación Funcional</label>
                            <input type="text" class="form-control" id="ups_agrupacion" required list="agrupacionesComunes" placeholder="Ej. Servicios de Especialidades Médicas">
                            <datalist id="agrupacionesComunes">
                                <option value="Atención Ambulatoria">
                                <option value="Atención de Emergencia">
                                <option value="Atención de Hospitalización">
                                <option value="Atención Quirúrgica">
                                <option value="Servicios de Apoyo al Diagnóstico y Tratamiento">
                            </datalist>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Abv. Agrupación</label>
                            <input type="text" class="form-control text-uppercase" id="ups_agrupacion_abreviatura" required maxlength="10" placeholder="Ej. AMB">
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="ups_estado" value="1" checked>
                                <label class="form-check-label small fw-bold" for="ups_estado">Unidad Productora activa y operativa</label>
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
    const urlBaseApi = '<?= base_url() ?>' + '/asistencia/gestordb/api/upss';

    let tabla;
    let modalUpss = new bootstrap.Modal(document.getElementById('modalUpss'));

    $(document).ready(function() {
        // Inicializar tabla de UPSS
        tabla = $('#tablaUpss').DataTable({
            "ajax": {
                "url": urlBaseApi,
                "type": "GET",
                "data": function(d) {
                    d.estado = $('#filtroEstado').val();
                },
                "dataSrc": "data"
            },
            "columns": [{
                    "data": "ups_ide"
                },
                {
                    "data": "ups_codigo",
                    "className": "fw-bold font-monospace text-center",
                    "render": function(data) {
                        return data ? `<span class="bg-light border px-2 py-1 rounded text-uppercase text-secondary" style="font-size: 0.85rem;">${data}</span>` : '<span class="text-muted">-</span>';
                    }
                },
                {
                    "data": "ups_nombre",
                    "className": "fw-bold text-dark"
                },
                {
                    "data": "ups_agrupacion",
                    "className": "text-secondary small fw-medium"
                },
                {
                    "data": "ups_agrupacion_abreviatura",
                    "className": "font-monospace text-center text-uppercase text-muted fw-bold"
                },
                {
                    "data": "ups_estado",
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
                                    onclick="eliminarUpss(${row.ups_ide})" title="Eliminar" style="width: 32px; height: 32px;">
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

        $('#formUpss').on('submit', function(e) {
            e.preventDefault();
            guardarUpss();
        });
    });

    function filtrarUpss() {
        tabla.ajax.reload(null, false);
    }

    function abrirModalCrear() {
        $('#formUpss')[0].reset();
        $('#ups_ide').val('');
        $('#ups_estado').prop('checked', true);
        $('#modalTitulo').text('Nueva Unidad Productora (UPSS)');
        modalUpss.show();
    }

    function abrirModalEditar(data) {
        $('#formUpss')[0].reset();
        $('#ups_ide').val(data.ups_ide);
        $('#ups_codigo').val(data.ups_codigo);
        $('#ups_nombre').val(data.ups_nombre);
        $('#ups_agrupacion').val(data.ups_agrupacion);
        $('#ups_agrupacion_abreviatura').val(data.ups_agrupacion_abreviatura);
        $('#ups_estado').prop('checked', data.ups_estado == 1);
        $('#modalTitulo').text('Editar Unidad Productora (UPSS)');
        modalUpss.show();
    }

    function guardarUpss() {
        const id = $('#ups_ide').val();
        const esEditar = id !== "";
        const url = esEditar ? `${urlBaseApi}/${id}` : urlBaseApi;
        const metodo = esEditar ? 'PUT' : 'POST';

        $('#btnGuardar').prop('disabled', true).text('Guardando...');

        const datos = {
            ups_codigo: $('#ups_codigo').val().toUpperCase(),
            ups_nombre: $('#ups_nombre').val(),
            ups_agrupacion: $('#ups_agrupacion').val(),
            ups_agrupacion_abreviatura: $('#ups_agrupacion_abreviatura').val().toUpperCase(),
            ups_estado: $('#ups_estado').is(':checked') ? 1 : 0
        };

        $.ajax({
            url: url,
            type: metodo,
            data: datos,
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    modalUpss.hide();
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

    function eliminarUpss(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "La UPSS se archivará. Esto afectará la organización del personal asociado a esta unidad.",
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