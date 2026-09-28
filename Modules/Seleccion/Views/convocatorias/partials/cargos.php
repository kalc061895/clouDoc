
<div class="card bg-white p-4">

    <!-- =========================================================
         CABECERA
    ========================================================== -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon
                    icon="solar:case-minimalistic-bold"
                    class="me-2 text-primary"
                    style="font-size: 1.5rem;">
                </iconify-icon>
                Cargos de la Convocatoria
            </h4>

            <p class="text-muted mb-0 small">
                Gestión de plazas, vacantes, ubicación y remuneración.
            </p>
        </div>

        <button
            type="button"
            class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center"
            onclick="abrirModalCargo()">

            <iconify-icon
                icon="lucide:plus"
                class="me-1"
                style="font-size: 1.1rem;">
            </iconify-icon>

            Nueva Plaza
        </button>
    </div>


    <!-- =========================================================
         RESUMEN
    ========================================================== -->
    <div class="row g-3 mb-4">

        <!-- Total puestos -->
        <div class="col-md-4">
            <div class="border rounded p-3 h-100">
                <div class="d-flex align-items-center">

                    <div class="me-3 text-primary">
                        <iconify-icon
                            icon="solar:case-bold"
                            style="font-size: 1.6rem;">
                        </iconify-icon>
                    </div>

                    <div>
                        <div class="text-muted small">
                            Puestos registrados
                        </div>

                        <div
                            class="fs-5 fw-bold text-dark"
                            id="totalCargos">
                            0
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <!-- Total plazas -->
        <div class="col-md-4">
            <div class="border rounded p-3 h-100">
                <div class="d-flex align-items-center">

                    <div class="me-3 text-info">
                        <iconify-icon
                            icon="solar:users-group-rounded-bold"
                            style="font-size: 1.6rem;">
                        </iconify-icon>
                    </div>

                    <div>
                        <div class="text-muted small">
                            Total de plazas
                        </div>

                        <div
                            class="fs-5 fw-bold text-dark"
                            id="totalVacantes">
                            0
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <!-- Remuneración máxima -->
        <div class="col-md-4">
            <div class="border rounded p-3 h-100">
                <div class="d-flex align-items-center">

                    <div class="me-3 text-success">
                        <iconify-icon
                            icon="solar:wallet-money-bold"
                            style="font-size: 1.6rem;">
                        </iconify-icon>
                    </div>

                    <div>
                        <div class="text-muted small">
                            Remuneración máxima
                        </div>

                        <div
                            class="fs-5 fw-bold text-success"
                            id="maxRemuneracion">
                            S/ 0.00
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>


    <!-- =========================================================
         TABLA DE CARGOS
    ========================================================== -->
    <div class="table-responsive">

        <table
            id="tablaCargos"
            class="table table-hover align-middle w-100">

            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Cargo</th>
                    <th>Grupo ocupacional</th>
                    <th>Ubicación / Adscripción</th>
                    <th class="text-center">Plazas</th>
                    <th class="text-end">Remuneración</th>
                    <th class="text-end" style="width: 110px;">Acciones</th>
                </tr>
            </thead>

            <tbody id="tbodyCargos"></tbody>

        </table>

    </div>

</div>


<!-- =============================================================
     MODAL REGISTRAR / EDITAR PLAZA
============================================================== -->
<div
    class="modal fade"
    id="modalCargo"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header border-0 pb-0">

                <h5
                    class="modal-title fw-bold"
                    id="modalCargoTitle">

                    Nueva Plaza

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Cerrar">
                </button>

            </div>


            <form
                id="formCargo"
                onsubmit="guardarCargo(event)">

                <div class="modal-body py-3">

                    <input
                        type="hidden"
                        id="cco_ide"
                        name="cco_ide">

                    <input
                        type="hidden"
                        id="cco_con_ide"
                        name="cco_con_ide"
                        value="<?= $convocatoriaId ?>">


                    <!-- =================================================
                         CARGO
                    ================================================== -->
                    <div class="row g-3 mb-3">

                        <div class="col-md-8">

                            <label
                                class="form-label small fw-bold"
                                for="cco_car_ide">

                                Cargo
                                <span class="text-danger">*</span>

                            </label>

                            <select
                                class="form-select"
                                id="cco_car_ide"
                                name="cco_car_ide"
                                required>

                                <option value="">
                                    Seleccione cargo...
                                </option>

                            </select>

                        </div>


                        <div class="col-md-4">

                            <label
                                class="form-label small fw-bold"
                                for="cco_numero_plazas">

                                Número de plazas
                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="number"
                                class="form-control"
                                id="cco_numero_plazas"
                                name="cco_numero_plazas"
                                min="1"
                                value="1"
                                required>

                        </div>

                    </div>


                    <!-- =================================================
                         ADSCRIPCIÓN
                    ================================================== -->
                    <div class="row g-3 mb-3">

                        <div class="col-md-6">

                            <label
                                class="form-label small fw-bold"
                                for="cco_dependencia">

                                Dependencia

                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="cco_dependencia"
                                name="cco_dependencia"
                                placeholder="Ej. Red de Salud San Román"
                                maxlength="200">

                        </div>


                        <div class="col-md-6">

                            <label
                                class="form-label small fw-bold"
                                for="cco_area">

                                Área / Unidad orgánica

                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="cco_area"
                                name="cco_area"
                                placeholder="Ej. Unidad de Estadística e Informática"
                                maxlength="200">

                        </div>


                        <div class="col-md-6">

                            <label
                                class="form-label small fw-bold"
                                for="cco_establecimiento">

                                Establecimiento

                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="cco_establecimiento"
                                name="cco_establecimiento"
                                placeholder="Ej. Hospital Carlos Monge Medrano"
                                maxlength="200">

                        </div>


                        <div class="col-md-6">

                            <label
                                class="form-label small fw-bold"
                                for="cco_remuneracion">

                                Remuneración (S/)
                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                class="form-control"
                                id="cco_remuneracion"
                                name="cco_remuneracion"
                                placeholder="0.00"
                                required>

                        </div>

                    </div>


                    <!-- =================================================
                         OBSERVACIONES
                    ================================================== -->
                    <div class="row">

                        <div class="col-12">

                            <label
                                class="form-label small fw-bold"
                                for="cco_observacion">

                                Observaciones

                            </label>

                            <textarea
                                class="form-control"
                                id="cco_observacion"
                                name="cco_observacion"
                                rows="2"
                                placeholder="Observaciones o precisiones de la plaza"></textarea>

                        </div>

                    </div>

                </div>


                <!-- Footer -->
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
                        id="btnGuardarCargo">

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

        // =========================================================
        // CONFIGURACIÓN
        // =========================================================

        const convocatoriaId = <?= json_encode($convocatoriaId) ?>;


        // =========================================================
        // TOAST
        // =========================================================

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });


        // =========================================================
        // ABRIR MODAL - NUEVO
        // =========================================================

        window.abrirModalCargo = function() {

            $('#formCargo')[0].reset();

            $('#cco_ide').val('');
            $('#cco_con_ide').val(convocatoriaId);

            $('#modalCargoTitle').text('Nueva Plaza');

            if (window.modalCargoBs) {
                window.modalCargoBs.show();
            }
        };


        // =========================================================
        // EDITAR
        // =========================================================

        window.editarCargo = function(cargo) {

            $('#cco_ide').val(cargo.cco_ide);
            $('#cco_con_ide').val(cargo.cco_con_ide);

            $('#cco_car_ide').val(cargo.cco_car_ide);
            $('#cco_numero_plazas').val(
                cargo.cco_numero_plazas || 1
            );

            $('#cco_dependencia').val(
                cargo.cco_dependencia || ''
            );

            $('#cco_area').val(
                cargo.cco_area || ''
            );

            $('#cco_establecimiento').val(
                cargo.cco_establecimiento || ''
            );

            $('#cco_remuneracion').val(
                cargo.cco_remuneracion || ''
            );

            $('#cco_observacion').val(
                cargo.cco_observacion || ''
            );

            $('#modalCargoTitle').text(
                'Editar Plaza de Convocatoria'
            );

            if (window.modalCargoBs) {
                window.modalCargoBs.show();
            }
        };


        // =========================================================
        // GUARDAR
        // =========================================================

        window.guardarCargo = function(e) {

            e.preventDefault();

            const $btn = $('#btnGuardarCargo');

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
                    '<?= base_url('seleccion/admin/cargos-convocatoria/guardar') ?>',
                    $('#formCargo').serialize()
                )

                .done(function(r) {

                    if (r.ok || r.status) {

                        if (window.modalCargoBs) {
                            window.modalCargoBs.hide();
                        }

                        listarCargos();

                        Toast.fire({
                            icon: 'success',
                            title: r.message ||
                                'Plaza guardada correctamente'
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

                    const res = xhr.responseJSON || {};

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


        // =========================================================
        // ELIMINAR
        // =========================================================

        window.eliminarCargo = function(id) {

            Swal.fire({

                    title: '¿Eliminar plaza?',

                    text: 'La plaza será retirada de esta convocatoria.',

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
                            '<?= base_url('seleccion/admin/cargos-convocatoria/eliminar/') ?>' + id
                        )

                        .done(function(r) {

                            if (r.ok || r.status) {

                                listarCargos();

                                Toast.fire({
                                    icon: 'success',
                                    title: r.message ||
                                        'Plaza eliminada correctamente'
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


        // =========================================================
        // ESCAPE HTML
        // =========================================================

        function escapeHtml(text) {

            return $('<div>')
                .text(text || '')
                .html();
        }


        // =========================================================
        // CATÁLOGO DE CARGOS
        // =========================================================

        function cargarCatalogoCargos() {

            $.get(
                    '<?= base_url('seleccion/api/cargo') ?>',
                    function(r) {

                        let options =
                            '<option value="">Seleccione cargo...</option>';

                        const items = r.data || r;

                        if (Array.isArray(items)) {

                            items.forEach(function(c) {

                                options += `
                            <option value="${c.car_ide}">
                                ${escapeHtml(c.car_codigo)}
                                -
                                ${escapeHtml(c.car_denominacion)}
                            </option>
                        `;
                            });
                        }

                        $('#cco_car_ide').html(options);

                    }
                )

                .fail(function() {

                    Toast.fire({
                        icon: 'warning',
                        title: 'No se pudo cargar el catálogo de cargos'
                    });

                });
        }


        // =========================================================
        // LISTAR CARGOS
        // =========================================================

        function listarCargos() {

            $.get(
                    '<?= base_url('seleccion/admin/cargos-convocatoria/listar/') ?>' + convocatoriaId,
                    function(r) {

                        const data = r.data || r;

                        let rows = '';

                        let totalVacantes = 0;

                        let maxRem = 0;


                        if (Array.isArray(data) && data.length > 0) {

                            data.forEach(function(item, index) {

                                const plazas =
                                    parseInt(
                                        item.cco_numero_plazas || 1
                                    );

                                totalVacantes += plazas;


                                const rem =
                                    parseFloat(
                                        item.cco_remuneracion || 0
                                    );

                                if (rem > maxRem) {
                                    maxRem = rem;
                                }


                                rows += `

                            <tr>

                                <td class="text-muted">
                                    ${index + 1}
                                </td>


                                <td>

                                    <div class="fw-bold text-dark">
                                        ${escapeHtml(
                                            item.car_denominacion
                                        )}
                                    </div>

                                    <small class="text-muted">
                                        ${item.car_codigo
                                            ? escapeHtml(item.car_codigo)
                                            : 'Sin código'}
                                    </small>

                                </td>


                                <td>

                                    ${
                                        item.grupo_ocupacional
                                            ? escapeHtml(
                                                item.grupo_ocupacional
                                            )
                                            : '<span class="text-muted">-</span>'
                                    }

                                </td>


                                <td>

                                    <div class="small fw-semibold text-dark">
                                        ${
                                            item.cco_area
                                                ? escapeHtml(item.cco_area)
                                                : 'Sin área'
                                        }
                                    </div>

                                    <small class="text-muted">
                                        ${
                                            item.cco_establecimiento
                                                ? escapeHtml(
                                                    item.cco_establecimiento
                                                )
                                                : 'Sin establecimiento'
                                        }
                                    </small>

                                </td>


                                <td class="text-center">

                                    <span
                                        class="badge bg-info-subtle text-info border border-info-subtle">

                                        ${plazas}

                                    </span>

                                </td>


                                <td class="text-end fw-semibold text-success">

                                    S/ ${rem.toFixed(2)}

                                </td>


                                <td class="text-end">

                                    <button
                                        type="button"
                                        class="btn btn-outline-warning btn-sm rounded-circle p-1 me-1 d-inline-flex align-items-center justify-content-center"
                                        onclick='editarCargo(${JSON.stringify(item)})'
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
                                        onclick="eliminarCargo(${item.cco_ide})"
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


                            $('#totalCargos').text(data.length);

                        } else {

                            rows = `

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5 text-muted">

                                <div class="mb-2">

                                    <iconify-icon
                                        icon="solar:case-minimalistic-broken"
                                        style="font-size:2rem;">
                                    </iconify-icon>

                                </div>

                                <div class="small">
                                    No se han registrado plazas
                                    para esta convocatoria.
                                </div>

                            </td>

                        </tr>

                    `;

                            $('#totalCargos').text(0);
                        }


                        $('#tbodyCargos').html(rows);

                        $('#totalVacantes').text(
                            totalVacantes
                        );

                        $('#maxRemuneracion').text(
                            'S/ ' + maxRem.toFixed(2)
                        );

                    }
                )

                .fail(function() {

                    $('#tbodyCargos').html(`

                <tr>

                    <td
                        colspan="7"
                        class="text-center py-5 text-danger">

                        <iconify-icon
                            icon="solar:danger-triangle-bold"
                            class="me-1">
                        </iconify-icon>

                        Error al obtener la lista de cargos.

                    </td>

                </tr>

            `);

                });
        }


        // =========================================================
        // MODAL BOOTSTRAP
        // =========================================================

        const modalEl =
            document.getElementById('modalCargo');

        if (modalEl) {

            window.modalCargoBs =
                bootstrap.Modal.getInstance(modalEl) ||
                new bootstrap.Modal(modalEl);
        }


        // =========================================================
        // INICIALIZACIÓN
        // =========================================================

        cargarCatalogoCargos();

        listarCargos();

    })();
</script>