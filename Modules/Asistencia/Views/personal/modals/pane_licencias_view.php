<!-- Header del Pane con Filtros y Botón Nuevo -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3 pb-2 border-bottom">
    <div>
        <h6 class="fw-bold mb-0 text-dark d-flex align-items-center">
            <iconify-icon icon="solar:document-text-bold" class="text-primary me-2 fs-5"></iconify-icon>
            Licencias, Papeletas e Incidencias
        </h6>
        <small class="text-muted">Historial de permisos y justificaciones registradas.</small>
    </div>

    <div class="d-flex align-items-center gap-2">
        <!-- Filtro Mes -->
        <select id="filtroMesLicencia" class="form-select form-select-sm" style="width: 130px;"
            onchange="cargarListadoLicencias()">
            <option value="">-- Todo el año --</option>
            <?php foreach ($meses as $mes): ?>
                <?php
                // Formateamos el número a 2 dígitos (ej: 1 -> "01") para mantener tu lógica de value
                $valMes = str_pad($mes['numero'], 2, '0', STR_PAD_LEFT);
                $selected = ($valMes == date('m')) ? 'selected' : '';
                ?>
                <option value="<?= $valMes ?>" <?= $selected ?>>
                    <?= esc($mes['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select id="filtroAnioLicencia" class="form-select form-select-sm" style="width: 100px;"
            onchange="cargarListadoLicencias()">
            <?php foreach ($anios as $anio): ?>
                <?php $selected = ($anio['numero'] == date('Y')) ? 'selected' : ''; ?>
                <option value="<?= $anio['numero'] ?>" <?= $selected ?>>
                    <?= esc($anio['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Botón Registrar -->
        <button class="btn btn-sm btn-primary d-flex align-items-center text-nowrap" onclick="abrirModalFormLicencia()">
            <iconify-icon icon="solar:add-circle-bold" class="me-1fs-6"></iconify-icon> Nueva Licencia
        </button>
    </div>
</div>

<!-- Tabla de Registros -->
<div class="table-responsive">
    <table class="table table-sm table-hover table-bordered align-middle mb-0" id="tblLicencias">
        <thead class="table-light text-secondary small">
            <tr>
                <th style="width: 50px;" class="text-center">#</th>
                <th>Tipo de Licencia</th>
                <th>Periodo (Inicio - Fin)</th>
                <th>Doc. Sustento</th>
                <th>Motivo / Justificación</th>
                <th class="text-center">Estado</th>
                <th style="width: 100px;" class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody id="tbodyLicencias">
            <tr>
                <td colspan="7" class="text-center py-4 text-muted">
                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                    Cargando listado de licencias...
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- ========================================== -->
<!-- MODAL ANIDADO: Registrar / Editar Licencia -->
<!-- ========================================== -->
<div class="modal fade" id="modalFormLicencia" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white py-2">
                <h6 class="modal-title fw-bold" id="titleModalLicencia">
                    <iconify-icon icon="solar:document-add-bold" class="me-1"></iconify-icon> Registrar Licencia /
                    Papeleta
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form id="formLicencia" onsubmit="guardarLicencia(event)">
                <div class="modal-body row g-3">
                    <input type="hidden" id="rl_ide" name="rl_ide">
                    <input type="hidden" id="rl_perl_ide" name="rl_perl_ide" value="<?= $perl_ide ?>">

                    <!-- Tipo de Licencia (Catalogo) -->
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Tipo de Licencia <span
                                class="text-danger">*</span></label>
                        <select class="form-select form-select-sm" id="rl_lic_ide" name="rl_lic_ide" required>
                            <option value="">-- Seleccione Tipo --</option>
                            <!-- Se puebla vía AJAX desde casis_licencia -->
                        </select>
                    </div>

                    <!-- Rango de Fechas -->
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Fecha Inicio <span
                                class="text-danger">*</span></label>
                        <input type="date" class="form-control form-control-sm" id="rl_fecha_inicio"
                            name="rl_fecha_inicio" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Fecha Fin <span class="text-danger">*</span></label>
                        <input type="date" class="form-control form-control-sm" id="rl_fecha_fin" name="rl_fecha_fin"
                            required>
                    </div>

                    <!-- Documento de Sustento -->
                    <div class="col-6">
                        <label class="form-label small fw-semibold">N° Doc. Sustento</label>
                        <input type="text" class="form-control form-control-sm" id="rl_numero_documento"
                            name="rl_numero_documento" placeholder="Ej: Ficha N° 102-2026">
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Fecha Documento</label>
                        <input type="date" class="form-control form-control-sm" id="rl_fecha_documento"
                            name="rl_fecha_documento">
                    </div>

                    <!-- Motivo -->
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Motivo / Observación</label>
                        <textarea class="form-control form-control-sm" id="rl_motivo" name="rl_motivo" rows="2"
                            placeholder="Detalle la razón de la solicitud..."></textarea>
                    </div>
                </div>
                <div class="modal-footer py-2 bg-light">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-sm btn-primary" id="btnGuardarLicencia">
                        <iconify-icon icon="solar:diskette-bold" class="me-1"></iconify-icon> Guardar Registro
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script Lógico de la Pestaña -->
<script>
    (function () {
        const URL_BASE_LICENCIA = "<?= base_url('asistencia/licencia') ?>";
        const PERL_IDE = <?= $perl_ide ?>;

        // 1. Cargar tipos de licencia al combo del modal
        function cargarTiposLicencia() {
            $.get(`${URL_BASE_LICENCIA}/api/tipos-activos`, function (res) {
                if (res.status === 200) {
                    let options = '<option value="">-- Seleccione Tipo --</option>';
                    res.data.forEach(item => {
                        options += `<option value="${item.lic_ide}">${item.lic_nombre} (${item.lic_remunerado == 1 ? 'Goce' : 'Sin Goce'})</option>`;
                    });
                    $('#rl_lic_ide').html(options);
                }
            });
        }

        // 2. Listar licencias aplicando los filtros de mes y año
        window.cargarListadoLicencias = function () {
            const mes = $('#filtroMesLicencia').val();
            const anio = $('#filtroAnioLicencia').val();
            const $tbody = $('#tbodyLicencias');

            $tbody.html(`
            <tr>
                <td colspan="7" class="text-center py-4 text-muted">
                    <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                    Consultando registros...
                </td>
            </tr>
        `);

            $.ajax({
                url: `${URL_BASE_LICENCIA}/api/personal/${PERL_IDE}`,
                type: 'GET',
                data: { mes: mes, anio: anio },
                dataType: 'json',
                success: function (res) {
                    if (res.data && res.data.length > 0) {
                        let html = '';
                        res.data.forEach((item, index) => {
                            const esRemunerado = item.lic_remunerado == 1
                                ? '<span class="badge bg-light-success text-success border border-success ms-1">Con Goce</span>'
                                : '<span class="badge bg-light-danger text-danger border border-danger ms-1">Sin Goce</span>';

                            html += `
                            <tr>
                                <td class="text-center text-muted small">${index + 1}</td>
                                <td>
                                    <span class="fw-semibold text-dark">${item.lic_nombre}</span>
                                    ${esRemunerado}
                                </td>
                                <td class="small text-nowrap">
                                    <iconify-icon icon="solar:calendar-bold" class="text-primary me-1"></iconify-icon>
                                    ${item.rl_fecha_inicio} al ${item.rl_fecha_fin}
                                </td>
                                <td class="small">${item.rl_numero_documento ?? '<span class="text-muted">-</span>'}</td>
                                <td class="small text-truncate" style="max-width: 200px;" title="${item.rl_motivo ?? ''}">
                                    ${item.rl_motivo ?? '-'}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light-primary text-primary">Registrado</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-danger btn-sm" title="Eliminar" onclick="eliminarLicencia(${item.rl_ide})">
                                            <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                        });
                        $tbody.html(html);
                    } else {
                        $tbody.html(`
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <iconify-icon icon="solar:document-medicine-bold-duotone" class="fs-2 d-block mb-1 text-secondary"></iconify-icon>
                                No se encontraron papeletas ni licencias registradas en este periodo.
                            </td>
                        </tr>
                    `);
                    }
                },
                error: function () {
                    $tbody.html('<tr><td colspan="7" class="text-center text-danger py-3">Error al conectar con la API de Licencias.</td></tr>');
                }
            });
        };

        // 3. Abrir Modal de Formulario
        window.abrirModalFormLicencia = function () {
            $('#formLicencia')[0].reset();
            $('#rl_ide').val('');
            const modal = new bootstrap.Modal(document.getElementById('modalFormLicencia'));
            modal.show();
        };

        // 4. Guardar Licencia (Creación)
        window.guardarLicencia = function (e) {
            e.preventDefault();
            const formData = $('#formLicencia').serialize();
            const $btn = $('#btnGuardarLicencia');

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Guardando...');

            $.ajax({
                url: `${URL_BASE_LICENCIA}/api/licencias/guardar`,
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function (res) {
                    bootstrap.Modal.getInstance(document.getElementById('modalFormLicencia')).hide();
                    cargarListadoLicencias(); // Recargar tabla
                },
                error: function (xhr) {
                    alert(xhr.responseJSON?.message || 'Ocurrió un error al guardar el registro.');
                },
                complete: function () {
                    $btn.prop('disabled', false).html('<iconify-icon icon="solar:diskette-bold" class="me-1"></iconify-icon> Guardar Registro');
                }
            });
        };

        // 5. Eliminar Licencia con Auditoría
        window.eliminarLicencia = function (rlIde) {
            const motivo = prompt('Ingrese el motivo o razón de la eliminación para la auditoría:');
            if (motivo === null) return; // Cancelado
            if (motivo.trim() === '') {
                alert('Debe especificar un motivo para poder eliminar el registro.');
                return;
            }

            $.ajax({
                url: `${URL_BASE_LICENCIA}/api/licencias/eliminar/${rlIde}`,
                type: 'POST',
                data: { motivo_cambio: motivo },
                dataType: 'json',
                success: function () {
                    cargarListadoLicencias();
                },
                error: function () {
                    alert('No se pudo eliminar el registro.');
                }
            });
        };

        // Inicialización al cargar el pane
        cargarTiposLicencia();
        cargarListadoLicencias();
    })();
</script>