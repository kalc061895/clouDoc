<div class="card bg-white p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon
                    icon="solar:calendar-mark-bold"
                    class="me-2 text-primary"
                    style="font-size: 1.5rem;">
                </iconify-icon>
                Cronograma y Etapas
            </h4>
            <p class="text-muted mb-0 small">
                Define las fechas, horas y plazos para cada fase del proceso de selección.
            </p>
        </div>
        <button
            type="button"
            class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center"
            onclick="abrirModalEtapa()">
            <iconify-icon
                icon="lucide:plus"
                class="me-1"
                style="font-size: 1.1rem;">
            </iconify-icon>
            Agregar Etapa
        </button>
    </div>
    <div class="row g-3 mb-4">
        <!-- Total etapas -->
        <div class="col-md-4">
            <div class="border rounded p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="me-3 text-primary">
                        <iconify-icon
                            icon="solar:checklist-minimalistic-bold"
                            style="font-size: 1.6rem;">
                        </iconify-icon>
                    </div>
                    <div>
                        <div class="text-muted small">
                            Etapas registradas
                        </div>
                        <div
                            class="fs-5 fw-bold text-dark"
                            id="totalEtapas">
                            0
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inicio -->
        <div class="col-md-4">
            <div class="border rounded p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="me-3 text-info">
                        <iconify-icon
                            icon="solar:calendar-date-bold"
                            style="font-size: 1.6rem;">
                        </iconify-icon>
                    </div>
                    <div>
                        <div class="text-muted small">
                            Inicio del concurso
                        </div>
                        <div
                            class="fs-5 fw-bold text-primary"
                            id="fechaInicioConcurso">
                            -
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="border rounded p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="me-3 text-success">
                        <iconify-icon
                            icon="solar:calendar-date-bold"
                            style="font-size: 1.6rem;">
                        </iconify-icon>
                    </div>
                    <div>
                        <div class="text-muted small">
                            Fin del concurso
                        </div>
                        <div
                            class="fs-5 fw-bold text-success"
                            id="fechaFinConcurso">
                            -
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="table-responsive">
        <table
            id="tablaEtapas"
            class="table table-hover align-middle w-100">
            <thead>
                <tr>
                    <th style="width: 50px;">
                        #
                    </th>
                    <th>
                        Etapa del proceso
                    </th>
                    <th class="text-center">
                        Fecha y hora inicio
                    </th>
                    <th class="text-center">
                        Fecha y hora cierre
                    </th>
                    <th class="text-center">
                        Estado
                    </th>
                    <th
                        class="text-end"
                        style="width: 110px;">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody id="tbodyEtapas">
                <tr>
                    <td
                        colspan="6"
                        class="text-center py-5 text-muted">
                        <div class="mb-2">
                            <div
                                class="spinner-border spinner-border-sm text-primary"
                                role="status">
                            </div>
                        </div>
                        <div class="small">
                            Cargando cronograma...
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div
    class="modal fade"
    id="modalEtapa"
    tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header border-0 pb-0">
                <h5
                    class="modal-title fw-bold"
                    id="modalEtapaTitle">
                    Nueva Etapa
                </h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Cerrar">
                </button>
            </div>

            <form
                id="formEtapa"
                onsubmit="guardarEtapa(event)">
                <div class="modal-body py-3">
                    <input
                        type="hidden"
                        id="cet_ide"
                        name="cet_ide">
                    <input
                        type="hidden"
                        id="cet_con_ide"
                        name="cet_con_ide"
                        value="<?= $convocatoriaId ?>">


                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label
                                class="form-label small fw-bold"
                                for="cet_eta_ide">
                                Etapa
                                <span class="text-danger">*</span>
                            </label>
                            <select
                                class="form-select"
                                id="cet_eta_ide"
                                name="cet_eta_ide"
                                required>
                                <option value="">
                                    Seleccione etapa...
                                </option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label
                                class="form-label small fw-bold"
                                for="cet_estado">
                                Estado
                            </label>
                            <select
                                class="form-select"
                                id="cet_estado"
                                name="cet_estado">
                                <option value="PENDIENTE">
                                    PENDIENTE
                                </option>
                                <option value="EN PROCESO">
                                    EN PROCESO
                                </option>
                                <option value="FINALIZADO">
                                    FINALIZADO
                                </option>
                                <option value="CANCELADO">
                                    CANCELADO
                                </option>
                            </select>
                        </div>
                    </div>


                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label
                                class="form-label small fw-bold"
                                for="cet_fecha_inicio">
                                Fecha de inicio
                                <span class="text-danger">*</span>
                            </label>
                            <input
                                type="date"
                                class="form-control"
                                id="cet_fecha_inicio"
                                name="cet_fecha_inicio"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label
                                class="form-label small fw-bold"
                                for="cet_hora_inicio">
                                Hora de inicio
                            </label>
                            <input
                                type="time"
                                class="form-control"
                                id="cet_hora_inicio"
                                name="cet_hora_inicio"
                                value="08:00">
                        </div>
                    </div>


                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label
                                class="form-label small fw-bold"
                                for="cet_fecha_cierre">
                                Fecha de cierre
                                <span class="text-danger">*</span>
                            </label>
                            <input
                                type="date"
                                class="form-control"
                                id="cet_fecha_cierre"
                                name="cet_fecha_cierre"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label
                                class="form-label small fw-bold"
                                for="cet_hora_cierre">
                                Hora de cierre
                            </label>
                            <input
                                type="time"
                                class="form-control"
                                id="cet_hora_cierre"
                                name="cet_hora_cierre"
                                value="17:00">
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-12">
                            <label
                                class="form-label small fw-bold"
                                for="cet_observacion">
                                Observaciones
                            </label>
                            <textarea
                                class="form-control"
                                id="cet_observacion"
                                name="cet_observacion"
                                rows="2"
                                placeholder="Observaciones o indicaciones adicionales"></textarea>
                        </div>
                    </div>
                </div>


                <div class="modal-footer border-0 pt-0">
                    <button
                        type="button"
                        class="btn btn-light btn-sm"
                        data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary btn-sm px-3"
                        id="btnGuardarEtapa">
                        <i class="bi bi-save me-1"></i>
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {

        const convocatoriaId = <?= json_encode($convocatoriaId) ?>;


        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });


        window.abrirModalEtapa = function() {
            $('#formEtapa')[0].reset();
            $('#cet_ide').val('');
            $('#cet_con_ide').val(convocatoriaId);
            $('#cet_estado').val('PENDIENTE');
            $('#cet_hora_inicio').val('08:00');
            $('#cet_hora_cierre').val('17:00');
            $('#modalEtapaTitle').text(
                'Nueva Etapa'
            );

            if (window.modalEtapaBs) {
                window.modalEtapaBs.show();
            }
        };


        window.editarEtapa = function(etapa) {
            $('#cet_ide').val(
                etapa.cet_ide
            );
            $('#cet_con_ide').val(
                etapa.cet_con_ide
            );
            $('#cet_eta_ide').val(
                etapa.cet_eta_ide
            );
            $('#cet_fecha_inicio').val(
                etapa.cet_fecha_inicio
            );
            $('#cet_hora_inicio').val(
                etapa.cet_hora_inicio
            );
            $('#cet_fecha_cierre').val(
                etapa.cet_fecha_cierre
            );
            $('#cet_hora_cierre').val(
                etapa.cet_hora_cierre
            );
            $('#cet_estado').val(
                etapa.cet_estado || 'PENDIENTE'
            );
            $('#cet_observacion').val(
                etapa.cet_observacion || ''
            );

            $('#modalEtapaTitle').text(
                'Editar Etapa del Cronograma'
            );

            if (window.modalEtapaBs) {
                window.modalEtapaBs.show();
            }
        };


        window.guardarEtapa = function(e) {
            e.preventDefault();

            const $btn = $('#btnGuardarEtapa');

            $btn
                .prop('disabled', true)
                .html(`
                <span
                    class="spinner-border spinner-border-sm me-1"
                    role="status">
                </span>
                Guardando...
            `);

            $.post(
                    '<?= base_url('seleccion/admin/etapas-convocatoria/guardar') ?>',
                    $('#formEtapa').serialize()
                )
                .done(function(r) {
                    if (r.ok || r.status) {
                        if (window.modalEtapaBs) {
                            window.modalEtapaBs.hide();
                        }

                        listarEtapas();

                        Toast.fire({
                            icon: 'success',
                            title: r.message ||
                                'Etapa guardada correctamente'
                        });

                    } else {
                        Swal.fire(
                            'Atención',
                            r.message ||
                            'No se pudo guardar el registro.',
                            'warning'
                        );
                    }
                })
                .fail(function(xhr) {
                    const res =
                        xhr.responseJSON || {};

                    Swal.fire(
                        'Error',
                        res.message ||
                        'Error al procesar la solicitud en el servidor.',
                        'error'
                    );
                })
                .always(function() {
                    $btn
                        .prop('disabled', false)
                        .html(`
                    <i class="bi bi-save me-1"></i>
                    Guardar
                `);
                });
        };


        window.eliminarEtapa = function(id) {
            Swal.fire({
                    title: '¿Eliminar etapa?',
                    text: 'La etapa será retirada del cronograma.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                })
                .then(function(result) {
                    if (!result.isConfirmed) {
                        return;
                    }

                    $.post(
                            '<?= base_url('seleccion/admin/etapas-convocatoria/eliminar/') ?>' + id
                        )
                        .done(function(r) {
                            if (r.ok || r.status) {
                                listarEtapas();

                                Toast.fire({
                                    icon: 'success',
                                    title: r.message ||
                                        'Etapa eliminada correctamente'
                                });

                            } else {
                                Swal.fire(
                                    'Error',
                                    r.message ||
                                    'No se pudo eliminar.',
                                    'error'
                                );
                            }
                        })
                        .fail(function() {
                            Swal.fire(
                                'Error',
                                'No se pudo procesar la eliminación.',
                                'error'
                            );
                        });
                });
        };


        function escapeHtml(text) {
            return $('<div>')
                .text(text || '')
                .html();
        }


        function cargarCatalogoEtapas() {
            $.get(
                    '<?= base_url('seleccion/api/etapas') ?>',
                    function(r) {
                        let options =
                            '<option value="">Seleccione etapa...</option>';

                        const items =
                            r.data || r;

                        if (Array.isArray(items)) {
                            items.forEach(function(e) {
                                options += `
                                <option value="${e.eta_ide}">
                            ${
                                    e.eta_orden
                                        ? e.eta_orden + '. '
                                        : ''
                                }
                                        ${escapeHtml(e.eta_nombre)}
                                </option>
                            `;
                            });
                        }

                        $('#cet_eta_ide')
                            .html(options);
                    }
                )
                .fail(function() {
                    Toast.fire({
                        icon: 'warning',
                        title: 'No se pudo cargar el catálogo de etapas'
                    });
                });
        }


        function getBadgeEstado(estado) {
            switch (estado) {
                case 'EN PROCESO':
                    return `
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                        EN PROCESO
                    </span>
                `;

                case 'FINALIZADO':
                    return `
                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                        FINALIZADO
                    </span>
                `;

                case 'CANCELADO':
                    return `
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                        CANCELADO
                    </span>
                `;

                default:
                    return `
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                        PENDIENTE
                    </span>
                `;
            }
        }


        function listarEtapas() {
            $.get(
                    '<?= base_url('seleccion/admin/etapas-convocatoria/listar/') ?>' + convocatoriaId,
                    function(r) {
                        const data =
                            r.data || r;

                        let rows = '';

                        if (
                            Array.isArray(data) &&
                            data.length > 0
                        ) {

                            $('#fechaInicioConcurso').text(
                                data[0].cet_fecha_inicio || '-'
                            );

                            $('#fechaFinConcurso').text(
                                data[data.length - 1].cet_fecha_cierre || '-'
                            );


                            data.forEach(function(item, index) {
                                const horaIni =
                                    item.cet_hora_inicio ?
                                    ' ' + item.cet_hora_inicio.substring(0, 5) :
                                    '';

                                const horaFin =
                                    item.cet_hora_cierre ?
                                    ' ' + item.cet_hora_cierre.substring(0, 5) :
                                    '';

                                rows += `
                                <tr>
                            <td class="text-muted fw-semibold">
                                ${
                                        item.eta_orden ||
                                        (index + 1)
                                    }
                                        </td>
                                
                                <td>
                                <div class="fw-bold text-dark">
                                    ${
                                            escapeHtml(
                                                item.eta_nombre
                                            )
                                        }
                                            </div>
                                    
                                    ${
                                        item.cet_observacion
                                        ? `
                                                <small class="text-muted">
                                                    ${escapeHtml(
                                                        item.cet_observacion
                                                    )}
                                                </small>
                                              `
                                              : ''
                                    }
                                            </td>
                                
                                <td class="text-center">
                                <div class="small fw-semibold">
                                    ${item.cet_fecha_inicio}
                                        </div>
                                    ${
                                        horaIni
                                            ? `
                                                <small class="text-muted">
                                                    ${horaIni}
                                                </small>
                                              `
                                            : ''
                                    }
                                            </td>
                                
                                <td class="text-center">
                                <div class="small fw-semibold">
                                    ${item.cet_fecha_cierre}
                                        </div>
                                    ${
                                        horaFin
                                            ? `
                                                <small class="text-muted">
                                                    ${horaFin}
                                                </small>
                                              `
                                            : ''
                                    }
                                            </td>
                                
                                <td class="text-center">
                                ${getBadgeEstado(
                                        item.cet_estado
                                    )}
                                    </td>
                                
                                <td class="text-end">
                                <button
                                        type="button"
                                        class="btn btn-outline-warning btn-sm rounded-circle p-1 me-1 d-inline-flex align-items-center justify-content-center"
                                        onclick='editarEtapa(${JSON.stringify(item)})'
                                        title="Editar"
                                        style="width:32px;height:32px;">
                                        <iconify-icon
                                            icon="lucide:edit"
                                            style="font-size:1rem;">
                                        </iconify-icon>
                                        </button>
                                    
                                    <button
                                        type="button"
                                        class="btn btn-outline-danger btn-sm rounded-circle p-1 d-inline-flex align-items-center justify-content-center"
                                        onclick="eliminarEtapa(${item.cet_ide})"
                                        title="Eliminar"
                                        style="width:32px;height:32px;">
                                        <iconify-icon
                                            icon="lucide:trash-2"
                                            style="font-size:1rem;">
                                        </iconify-icon>
                                        </button>
                                    </td>
                                </tr>
                            `;
                            });

                            $('#totalEtapas').text(
                                data.length
                            );

                        } else {
                            rows = `
                            <tr>
                        <td
                                colspan="6"
                                class="text-center py-5 text-muted">
                                <div class="mb-2">
                                <iconify-icon
                                        icon="solar:calendar-minimalistic-broken"
                                        style="font-size:2rem;">
                                    </iconify-icon>
                                    </div>
                                <div class="small">
                                No se han registrado etapas
                                    para esta convocatoria.
                                    </div>
                                </td>
                            </tr>
                        `;

                            $('#totalEtapas').text(0);
                            $('#fechaInicioConcurso').text('-');
                            $('#fechaFinConcurso').text('-');
                        }

                        $('#tbodyEtapas')
                            .html(rows);
                    }
                )
                .fail(function() {
                    $('#tbodyEtapas').html(`
                    <tr>
                <td
                        colspan="6"
                        class="text-center py-5 text-danger">
                        <iconify-icon
                            icon="solar:danger-triangle-bold"
                            class="me-1">
                        </iconify-icon>
                        Error al obtener el cronograma.
                        </td>
                    </tr>
                `);
                });
        }


        const modalEl =
            document.getElementById('modalEtapa');

        if (modalEl) {
            window.modalEtapaBs =
                bootstrap.Modal.getInstance(modalEl) ||
                new bootstrap.Modal(modalEl);
        }


        cargarCatalogoEtapas();
        listarEtapas();
    })();
</script>