<?= $this->extend('layouts/asistenciaLayout') ?>

<?= $this->section('title') ?>Servicios por UPSS<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Servicios por UPSS</h4>
            <p class="text-muted mb-0 small">Administre los servicios y la UPSS a la que pertenecen.</p>
        </div>
        <button type="button" id="nuevoServicio" class="btn btn-primary btn-sm">Nuevo servicio</button>
    </div>

    <div class="table-responsive">
        <table id="tablaServicios" class="table table-hover align-middle w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Abreviatura</th>
                    <th>Servicio</th>
                    <th>UPSS</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalServicio" tabindex="-1" aria-labelledby="modalTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formServicio">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitulo">Nuevo servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="uss_ide">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label for="uss_codigo" class="form-label">Código</label>
                            <input type="text" id="uss_codigo" class="form-control" maxlength="20">
                        </div>
                        <div class="col-md-7">
                            <label for="uss_abreviatura" class="form-label">Abreviatura</label>
                            <input type="text" id="uss_abreviatura" class="form-control text-uppercase" maxlength="20">
                        </div>
                        <div class="col-12">
                            <label for="uss_nombre" class="form-label">Nombre del servicio</label>
                            <input type="text" id="uss_nombre" class="form-control" maxlength="200" required>
                        </div>
                        <div class="col-12">
                            <label for="uss_ups_ide" class="form-label">UPSS activa</label>
                            <select id="uss_ups_ide" class="form-select" required>
                                <option value="">Cargando UPSS...</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="uss_estado" class="form-label">Estado del servicio</label>
                            <select id="uss_estado" class="form-select" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btnGuardar" class="btn btn-primary btn-sm">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script>
    (() => {
        const apiServicios = <?= json_encode(base_url('asistencia/gestordb/api/upsservicios')) ?>;
        const apiUpss = <?= json_encode(base_url('asistencia/gestordb/api/upss')) ?>;
        const csrfName = <?= json_encode(csrf_token()) ?>;
        let csrfHash = <?= json_encode(csrf_hash()) ?>;
        const modal = new bootstrap.Modal(document.getElementById('modalServicio'));
        const escapeHtml = $.fn.dataTable.render.text().display;
        const safe = value => escapeHtml(value == null ? '' : String(value));

        function refrescarCsrf(response) {
            if (response && response.csrfHash) csrfHash = response.csrfHash;
        }

        function mostrarError(xhr) {
            const response = xhr.responseJSON || {};
            refrescarCsrf(response);
            const messages = response.messages;
            let mensaje = response.message || 'No se pudo completar la operación.';
            if (messages && typeof messages === 'object') {
                mensaje = Object.values(messages).join('\n');
            } else if (typeof messages === 'string') {
                mensaje = messages;
            }
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: mensaje
            });
        }

        // El endpoint /api/upss debe aplicar el filtro estado=1 en el servidor.
        function cargarUpss() {
            const select = $('#uss_ups_ide');
            select.prop('disabled', true).empty().append(new Option('Cargando UPSS...', ''));
            return $.getJSON(apiUpss, {
                    estado: 1
                })
                .done(response => {
                    select.empty().append(new Option('Seleccione una UPSS', ''));
                    if (response.status !== 'success' || !Array.isArray(response.data)) {
                        select.empty().append(new Option('Respuesta UPSS incorrecta', ''));
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'La API de UPSS no devolvió una lista válida.'
                        });
                        return;
                    }
                    response.data.forEach(item => {
                        // Defensa adicional si la API envía el estado explícitamente.
                        if (item.ups_estado !== undefined && Number(item.ups_estado) !== 1) return;
                        select.append(new Option(`${item.ups_nombre} [${item.ups_codigo || ''}]`, item.ups_ide));
                    });
                    select.prop('disabled', false);
                })
                .fail(xhr => {
                    select.empty().append(new Option('No se pudieron cargar las UPSS', ''));
                    mostrarError(xhr);
                });
        }

        const tabla = $('#tablaServicios').DataTable({
            ajax: {
                url: apiServicios,
                dataSrc: 'data',
                error: mostrarError
            },
            columns: [{
                    data: 'uss_ide'
                },
                {
                    data: 'uss_codigo',
                    render: (d, type) => type === 'display' ? safe(d || '-') : d
                },
                {
                    data: 'uss_abreviatura',
                    render: (d, type) => type === 'display' ? safe(d || '-') : d
                },
                {
                    data: 'uss_nombre',
                    render: (d, type) => type === 'display' ? safe(d) : d
                },
                {
                    data: 'ups_nombre',
                    render: (d, type, row) => type === 'display' ? safe(`${d || '-'} [${row.ups_codigo || ''}]`) : d
                },
                {
                    data: 'uss_estado',
                    render: (d, type) => type === 'display' ?
                        (Number(d) === 1 ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>') :
                        d
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    className: 'text-end',
                    render: (_d, type) => type === 'display' ?
                        '<button type="button" class="btn btn-outline-primary btn-sm js-editar me-1">Editar</button>' +
                        '<button type="button" class="btn btn-outline-danger btn-sm js-eliminar">Eliminar</button>' :
                        ''
                }
            ],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
        });

        $('#nuevoServicio').on('click', () => {
            $('#formServicio')[0].reset();
            $('#uss_ide').val('');
            $('#uss_estado').val('1');
            $('#modalTitulo').text('Nuevo servicio');
            cargarUpss();
            modal.show();
        });

        $('#tablaServicios').on('click', '.js-editar', function() {
            const row = tabla.row($(this).closest('tr')).data();
            if (!row) return;
            $('#formServicio')[0].reset();
            $('#uss_ide').val(row.uss_ide);
            $('#uss_codigo').val(row.uss_codigo || '');
            $('#uss_nombre').val(row.uss_nombre || '');
            $('#uss_abreviatura').val(row.uss_abreviatura || '');
            $('#uss_estado').val(String(row.uss_estado));
            $('#modalTitulo').text('Editar servicio');
            cargarUpss().done(() => $('#uss_ups_ide').val(String(row.uss_ups_ide)));
            modal.show();
        });

        $('#formServicio').on('submit', function(event) {
            event.preventDefault();
            if (!this.reportValidity()) return;
            const id = $('#uss_ide').val();
            const datos = {
                uss_ups_ide: $('#uss_ups_ide').val(),
                uss_codigo: $('#uss_codigo').val().trim(),
                uss_nombre: $('#uss_nombre').val().trim(),
                uss_abreviatura: $('#uss_abreviatura').val().trim().toUpperCase(),
                uss_estado: $('#uss_estado').val()
            };
            datos[csrfName] = csrfHash;
            const boton = $('#btnGuardar').prop('disabled', true).text('Guardando...');
            $.ajax({
                    url: id ? `${apiServicios}/${id}` : apiServicios,
                    method: id ? 'PUT' : 'POST',
                    data: datos
                })
                .done(response => {
                    refrescarCsrf(response);
                    if (response.status !== 'success') return mostrarError({
                        responseJSON: response
                    });
                    toastr.success(response.message);
                    modal.hide();
                    tabla.ajax.reload(null, false);
                })
                .fail(mostrarError)
                .always(() => boton.prop('disabled', false).text('Guardar'));
        });

        $('#tablaServicios').on('click', '.js-eliminar', function() {
            const row = tabla.row($(this).closest('tr')).data();
            if (!row) return;

            Swal.fire({
                icon: 'warning',
                title: '¿Eliminar el servicio?',
                text: `Se eliminará ${row.uss_nombre} de forma permanente.`,
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc3545',
                showLoaderOnConfirm: true,
                allowOutsideClick: () => !Swal.isLoading(),
                allowEscapeKey: () => !Swal.isLoading(),

                preConfirm: async () => {
                    try {
                        const datos = {
                            [csrfName]: csrfHash
                        };

                        const response = await $.ajax({
                            url: `${apiServicios}/${row.uss_ide}`,
                            method: 'DELETE',
                            data: datos
                        });

                        refrescarCsrf(response);
                        return response;
                    } catch (xhr) {
                        const response = xhr.responseJSON || {};
                        refrescarCsrf(response);

                        const mensaje = response.message ||
                            (response.messages && Object.values(response.messages).join(', ')) ||
                            'No se pudo eliminar el servicio.';

                        Swal.showValidationMessage(mensaje);
                        return false;
                    }
                }
            }).then(result => {
                if (!result.isConfirmed || !result.value) return;

                toastr.success(result.value.message || 'Servicio eliminado correctamente.');
                tabla.ajax.reload(null, false);
            });
        });
    })();
</script>
<?= $this->endSection() ?>