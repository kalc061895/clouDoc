<?= $this->extend('layouts/seleccionLayout') ?>

<?= $this->section('title') ?>
Cargos
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:briefcase-bold" class="me-2 text-primary" style="font-size: 1.5rem;"></iconify-icon>
                Gestión de Cargos
            </h4>
            <p class="text-muted mb-0 small">Administración de cargos para los procesos de selección</p>
        </div>
        <button class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="abrirModalCrear()">
            <iconify-icon icon="lucide:plus" class="me-1"></iconify-icon>
            Nuevo cargo
        </button>
    </div>
    <div class="table-responsive">
        <table id="tablaCargos" class="table table-hover align-middle w-100">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Denominación</th>
                    <th>Especialidad</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalCargo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitulo">Nuevo Cargo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formCargo">
                <div class="modal-body py-3">
                    <input type="hidden" id="car_ide">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Código <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="car_codigo" required maxlength="50">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Denominación <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="car_denominacion" required maxlength="150">
                        </div>

                        <!-- Selects para Relaciones / FKs -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tipo de Cargo</label>
                            <select class="form-select" id="car_tca_ide">
                                <option value="">-- Seleccione Tipo --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Grupo Ocupacional</label>
                            <select class="form-select" id="car_gru_ide">
                                <option value="">-- Seleccione Grupo --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nivel</label>
                            <select class="form-select" id="car_niv_ide">
                                <option value="">-- Seleccione Nivel --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Profesión</label>
                            <select class="form-select" id="car_pro_ide">
                                <option value="">-- Seleccione Profesión --</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Especialidad</label>
                            <input type="text" class="form-control" id="car_especialidad" maxlength="150">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Estado <span class="text-danger">*</span></label>
                            <select class="form-select" id="car_estado" required>
                                <option value="ACTIVO">Activo</option>
                                <option value="INACTIVO">Inactivo</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Descripción</label>
                            <textarea class="form-control" id="car_descripcion" rows="3" maxlength="255"></textarea>
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
    const urlBaseApi = '<?= base_url('seleccion/api/cargo') ?>';
    const tableId = '#tablaCargos';
    const formId = '#formCargo';
    const modal = new bootstrap.Modal(document.getElementById('modalCargo'));
    let tabla;
    const registros = new Map();

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, character => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        } [character]));
    }

    function mensajeError(xhr) {
        const body = xhr.responseJSON || {};
        const errors = body.errors || body.messages || {};
        return Object.keys(errors).length ?
            Object.values(errors).map(escapeHtml).join('<br>') :
            escapeHtml(body.message || 'No se pudo procesar la solicitud.');
    }

    // Carga de Lookups/Catalogos para Selects
    function cargarCatalogos() {
        const endpoints = [{
                url: '<?= base_url('seleccion/api/tipos-cargo') ?>',
                select: '#car_tca_ide',
                idKey: 'tca_ide',
                textKey: 'tca_nombre'
            },
            {
                url: '<?= base_url('seleccion/api/grupos-ocupacionales') ?>',
                select: '#car_gru_ide',
                idKey: 'gru_ide',
                textKey: 'gru_nombre'
            },
            {
                url: '<?= base_url('seleccion/api/niveles') ?>',
                select: '#car_niv_ide',
                idKey: 'niv_ide',
                textKey: 'niv_nombre'
            },
            {
                url: '<?= base_url('seleccion/api/profesiones') ?>',
                select: '#car_pro_ide',
                idKey: 'pro_ide',
                textKey: 'pro_nombre'
            }
        ];

        return Promise.all(endpoints.map(ep =>
            $.ajax({
                url: ep.url,
                type: 'GET'
            }).then(res => {
                const select = $(ep.select);
                const firstOption = select.find('option:first');
                select.empty().append(firstOption);

                if (res.status && Array.isArray(res.data)) {
                    res.data.forEach(item => {
                        const id = item[ep.idKey] ?? item.id;
                        const text = item[ep.textKey] ?? item.nombre ?? item.denominacion;
                        select.append(new Option(text, id));
                    });
                }
            }).catch(err => console.warn('Error cargando catálogo:', ep.url, err))
        ));
    }

    $(document).ready(function() {
        cargarCatalogos();

        tabla = $(tableId).DataTable({
            ajax: {
                url: urlBaseApi,
                type: 'GET',
                dataSrc: function(response) {
                    if (!response.status) {
                        toastr.error(response.message || 'No se pudo cargar la información.');
                        return [];
                    }
                    registros.clear();
                    (response.data || []).forEach(row => registros.set(String(row.car_ide), row));
                    return response.data || [];
                }
            },
            columns: [{
                    data: 'car_codigo',
                    render: value => escapeHtml(value)
                },
                {
                    data: 'car_denominacion',
                    render: value => escapeHtml(value)
                },
                {
                    data: 'car_especialidad',
                    render: value => value ? escapeHtml(value) : '<span class="text-muted small">N/A</span>'
                },
                {
                    data: 'car_estado',
                    render: function(value) {
                        const isActivo = value === 'ACTIVO';
                        const badgeClass = isActivo ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger';
                        return `<span class="badge ${badgeClass} fw-semibold">${escapeHtml(value)}</span>`;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    className: 'text-end',
                    render: function(_, __, row) {
                        return '<button class="btn btn-outline-warning btn-sm me-1 rounded-circle p-1 editar" data-id="' + row.car_ide + '" title="Editar" style="width:32px;height:32px"><iconify-icon icon="lucide:edit"></iconify-icon></button>' +
                            '<button class="btn btn-outline-danger btn-sm rounded-circle p-1 eliminar" data-id="' + row.car_ide + '" title="Eliminar" style="width:32px;height:32px"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>';
                    }
                }
            ],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
        });

        $(formId).on('submit', function(event) {
            event.preventDefault();
            guardar();
        });

        $(tableId + ' tbody').on('click', '.editar', function() {
            abrirModalEditar(registros.get(String($(this).data('id'))));
        });

        $(tableId + ' tbody').on('click', '.eliminar', function() {
            eliminar($(this).data('id'));
        });
    });

    function abrirModalCrear() {
        $(formId)[0].reset();
        $('#car_ide').val('');
        $('#car_estado').val('ACTIVO');
        $('#car_tca_ide, #car_gru_ide, #car_niv_ide, #car_pro_ide').val('');
        $('#modalTitulo').text('Nuevo Cargo');
        modal.show();
    }

    function abrirModalEditar(data) {
        if (!data) return;
        $(formId)[0].reset();
        $('#car_ide').val(data.car_ide);
        $('#car_codigo').val(data.car_codigo ?? '');
        $('#car_denominacion').val(data.car_denominacion ?? '');
        $('#car_tca_ide').val(data.car_tca_ide ?? '');
        $('#car_gru_ide').val(data.car_gru_ide ?? '');
        $('#car_niv_ide').val(data.car_niv_ide ?? '');
        $('#car_pro_ide').val(data.car_pro_ide ?? '');
        $('#car_especialidad').val(data.car_especialidad ?? '');
        $('#car_descripcion').val(data.car_descripcion ?? '');
        $('#car_estado').val(data.car_estado ?? 'ACTIVO');
        $('#modalTitulo').text('Editar Cargo');
        modal.show();
    }

    function guardar() {
        const id = $('#car_ide').val();
        const editar = id !== '';
        const datos = {
            car_codigo: $('#car_codigo').val(),
            car_denominacion: $('#car_denominacion').val(),
            car_tca_ide: $('#car_tca_ide').val() || null,
            car_gru_ide: $('#car_gru_ide').val() || null,
            car_niv_ide: $('#car_niv_ide').val() || null,
            car_pro_ide: $('#car_pro_ide').val() || null,
            car_especialidad: $('#car_especialidad').val(),
            car_descripcion: $('#car_descripcion').val(),
            car_estado: $('#car_estado').val()
        };

        $('#btnGuardar').prop('disabled', true).text('Guardando...');

        $.ajax({
            url: editar ? urlBaseApi + '/' + id : urlBaseApi,
            type: editar ? 'PUT' : 'POST',
            contentType: 'application/json',
            data: JSON.stringify(datos),
            success: function(response) {
                if (response.status) {
                    toastr.success(response.message);
                    modal.hide();
                    tabla.ajax.reload(null, false);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error!',
                        html: mensajeError({
                            responseJSON: response
                        })
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    html: mensajeError(xhr)
                });
            },
            complete: function() {
                $('#btnGuardar').prop('disabled', false).text('Guardar');
            }
        });
    }

    function eliminar(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'El cargo será eliminado.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(result => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: urlBaseApi + '/' + id,
                type: 'DELETE',
                success: function(response) {
                    if (response.status) {
                        toastr.success(response.message);
                        tabla.ajax.reload(null, false);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error!',
                            html: mensajeError({
                                responseJSON: response
                            })
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error!',
                        html: mensajeError(xhr)
                    });
                }
            });
        });
    }
</script>
<?= $this->endSection() ?>