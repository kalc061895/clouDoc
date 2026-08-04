<!-- Header del Pane con Filtros y Botón Nuevo -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3 pb-2 border-bottom">
    <div>
        <h6 class="fw-bold mb-0 text-dark d-flex align-items-center">
            <iconify-icon icon="solar:clock-square-bold" class="text-primary me-2 fs-5"></iconify-icon>
            Permisos y Papeletas por Horas
        </h6>
        <small class="text-muted">Historial de salidas por horas y papeletas de permiso registradas.</small>
    </div>

    <div class="d-flex align-items-center gap-2">
        <!-- Filtro Mes -->
        <select id="filtroMesPermiso" class="form-select form-select-sm" style="width: 130px;" onchange="cargarListadoPermisos()">
            <option value="">-- Todo el año --</option>
            <?php foreach ($meses as $mes): ?>
                <?php
                $valMes = str_pad($mes['numero'], 2, '0', STR_PAD_LEFT);
                $selected = ($valMes == date('m')) ? 'selected' : '';
                ?>
                <option value="<?= $valMes ?>" <?= $selected ?>>
                    <?= esc($mes['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Filtro Año -->
        <select id="filtroAnioPermiso" class="form-select form-select-sm" style="width: 100px;" onchange="cargarListadoPermisos()">
            <?php foreach ($anios as $anio): ?>
                <?php $selected = ($anio['numero'] == date('Y')) ? 'selected' : ''; ?>
                <option value="<?= $anio['numero'] ?>" <?= $selected ?>>
                    <?= esc($anio['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Botón Registrar -->
        <button class="btn btn-sm btn-primary d-flex align-items-center text-nowrap" onclick="abrirModalFormPermiso()">
            <iconify-icon icon="solar:add-circle-bold" class="me-1 fs-6"></iconify-icon> Nuevo Permiso
        </button>
    </div>
</div>

<!-- Tabla de Registros -->
<div class="table-responsive">
    <table class="table table-sm table-hover table-bordered align-middle mb-0" id="tblPermisos">
        <thead class="text-secondary small">
            <tr>
                <th style="width: 40px;" class="text-center">#</th>
                <th>Tipo de Permiso</th>
                <th>Fecha</th>
                <th>Horario (Inicio - Fin)</th>
                <th>Doc. Sustento</th>
                <th>Anexos</th>
                <th>Motivo / Observación</th>
                <th class="text-center">Estado</th>
                <th style="width: 90px;" class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody id="tbodyPermisos">
            <tr>
                <td colspan="9" class="text-center py-4 text-muted">
                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                    Cargando listado de permisos...
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- ========================================== -->
<!-- MODAL ANIDADO: Registrar / Editar Permiso  -->
<!-- ========================================== -->
<div class="modal fade" id="modalFormPermiso" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white py-2">
                <h6 class="modal-title text-light fw-bold" id="titleModalPermiso">
                    <iconify-icon icon="solar:document-add-bold" class="me-1"></iconify-icon> Registrar Permiso / Papeleta
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="formPermiso" onsubmit="guardarPermiso(event)" enctype="multipart/form-data">
                <div class="modal-body row g-3">
                    <input type="hidden" id="rp_ide" name="rp_ide">
                    <input type="hidden" id="rp_perl_ide" name="rp_perl_ide" value="<?= $perl_ide ?>">

                    <!-- Tipo de Permiso -->
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Tipo de Permiso <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm" id="rp_pero_ide" name="rp_pero_ide" required>
                            <option value="">-- Seleccione Tipo --</option>
                        </select>
                    </div>

                    <!-- Fecha del Permiso -->
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Fecha de Permiso <span class="text-danger">*</span></label>
                        <input type="date" class="form-control form-control-sm" id="rp_fecha" name="rp_fecha" required>
                    </div>

                    <!-- Horas Inicio / Fin -->
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Hora Inicio <span class="text-danger">*</span></label>
                        <input type="time" class="form-control form-control-sm" id="rp_hora_salida" name="rp_hora_salida" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Hora Fin <span class="text-danger">*</span></label>
                        <input type="time" class="form-control form-control-sm" id="rp_hora_retorno" name="rp_hora_retorno" required>
                    </div>

                    <!-- Badge informativo de horas calculadas -->
                    <div class="col-12 mt-1">
                        <div id="cntHoras" class="text-end small fw-semibold text-muted" style="font-size: 0.8rem;">
                            <!-- Se llena dinámicamente con JS -->
                        </div>
                    </div>

                    <!-- Documento de Sustento -->
                    <div class="col-6">
                        <label class="form-label small fw-semibold">N° Doc. Sustento</label>
                        <input type="text" class="form-control form-control-sm" id="rp_numero_documento" name="rp_numero_documento" placeholder="Ej: Papeleta N° 045-2026">
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Fecha Documento</label>
                        <input type="date" class="form-control form-control-sm" id="rp_fecha_documento" name="rp_fecha_documento">
                    </div>

                    <!-- Campo de Carga de Múltiples Anexos -->
                    <div class="col-12">
                        <label class="form-label small fw-semibold d-flex justify-content-between align-items-center">
                            <span>Adjuntar Anexos / Sustentos (Opcional)</span>
                            <span class="badge bg-light-secondary text-muted">Formatos: PDF, JPG, PNG</span>
                        </label>
                        <input type="file" class="form-control form-control-sm" id="anexos_permiso" name="anexos[]" multiple accept=".pdf,.png,.jpg,.jpeg">
                        <div class="form-text text-muted small" style="font-size: 0.75rem;">
                            Puedes seleccionar uno o varios archivos manteniendo presionada la tecla Ctrl.
                        </div>
                    </div>

                    <!-- Motivo -->
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Motivo / Observación</label>
                        <textarea class="form-control form-control-sm" id="rp_motivo" name="rp_motivo" rows="2" placeholder="Detalle la razón de la solicitud de permiso..."></textarea>
                    </div>
                </div>
                <div class="modal-footer py-2 bg-light">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-sm btn-primary" id="btnGuardarPermiso">
                        <iconify-icon icon="solar:diskette-bold" class="me-1"></iconify-icon> Guardar Registro
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL SECUNDARIO: Ver Anexos Adjuntos      -->
<!-- ========================================== -->
<div class="modal fade" id="modalVerAnexosPermisos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content shadow border-0">
            <div class="modal-header py-2 bg-light">
                <h6 class="modal-title fw-bold text-dark small">
                    <iconify-icon icon="solar:paperclip-bold" class="me-1 text-primary"></iconify-icon> Documentos Anexos
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-2" id="bodyAnexosPermisosList">
                <!-- Dinámico -->
            </div>
        </div>
    </div>
</div>

<!-- Script Lógico para Permisos -->
<script>
    (function() {
        const URL_BASE_PERMISO = "<?= base_url('asistencia/permiso') ?>";
        const URL_BASE_SISTEMA = "<?= base_url() ?>";
        const PERL_IDE = <?= $perl_ide ?>;

        // Cargar tipos de permiso
        function cargarTiposPermiso() {
            $.get(`${URL_BASE_PERMISO}/api/tipos-activos`, function(res) {
                if (res.status === 200) {
                    let options = '<option value="">-- Seleccione Tipo --</option>';
                    res.data.forEach(item => {
                        const esGoce = item.pero_remunerado == 1 ? 'Con Goce' : 'Sin Goce';
                        options += `<option value="${item.pero_ide}">${item.pero_nombre} (${esGoce})</option>`;
                    });
                    $('#rp_pero_ide').html(options);
                }
            });
        }

        // Listar permisos aplicando filtros
        window.cargarListadoPermisos = function() {
            const mes = $('#filtroMesPermiso').val();
            const anio = $('#filtroAnioPermiso').val();
            const $tbody = $('#tbodyPermisos');

            $tbody.html(`
            <tr>
                <td colspan="9" class="text-center py-4 text-muted">
                    <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                    Consultando registros...
                </td>
            </tr>
            `);

            $.ajax({
                url: `${URL_BASE_PERMISO}/api/personal/${PERL_IDE}`,
                type: 'GET',
                data: {
                    mes: mes,
                    anio: anio
                },
                dataType: 'json',
                success: function(res) {
                    if (res.data && res.data.length > 0) {
                        let html = '';
                        res.data.forEach((item, index) => {
                            const esRemunerado = item.pero_remunerado == 1 ?
                                '<span class="badge bg-light-success text-success border border-success ms-1">Con Goce</span>' :
                                '<span class="badge bg-light-danger text-danger border border-danger ms-1">Sin Goce</span>';

                            let anexosHtml = '<span class="text-muted small">-</span>';
                            if (item.adjuntos && item.adjuntos.length > 0) {
                                const jsonAnexos = esc(JSON.stringify(item.adjuntos));
                                anexosHtml = `
                                    <button class="btn btn-xs btn-outline-info d-inline-flex align-items-center gap-1 py-0 px-2" 
                                            onclick='verAnexosPermiso(${jsonAnexos})' title="Ver anexos">
                                        <iconify-icon icon="solar:paperclip-linear"></iconify-icon>
                                        <span class="fw-bold">${item.adjuntos.length}</span>
                                    </button>
                                `;
                            }

                            html += `
                            <tr>
                                <td class="text-center text-muted small">${index + 1}</td>
                                <td>
                                    <span class="fw-semibold text-dark">${item.pero_nombre}</span>
                                    ${esRemunerado}
                                </td>
                                <td class="small text-nowrap">
                                    <iconify-icon icon="solar:calendar-bold" class="text-primary me-1"></iconify-icon>
                                    ${item.rp_fecha}
                                </td>
                                <td class="small text-nowrap">
                                    <iconify-icon icon="solar:clock-circle-bold" class="text-info me-1"></iconify-icon>
                                    ${item.rp_hora_salida} - ${item.rp_hora_retorno}
                                </td>
                                <td class="small">${item.rp_numero_documento ?? '<span class="text-muted">-</span>'}</td>
                                <td class="text-center">${anexosHtml}</td>
                                <td class="small text-truncate" style="max-width: 180px;" title="${item.rp_motivo ?? ''}">
                                    ${item.rp_motivo ?? '-'}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light-primary text-primary">Registrado</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-danger btn-sm" title="Eliminar" onclick="eliminarPermiso(${item.rp_ide})">
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
                            <td colspan="9" class="text-center py-4 text-muted">
                                <iconify-icon icon="solar:document-medicine-bold-duotone" class="fs-2 d-block mb-1 text-secondary"></iconify-icon>
                                No se encontraron permisos registrados en este periodo.
                            </td>
                        </tr>
                        `);
                    }
                },
                error: function() {
                    $tbody.html('<tr><td colspan="9" class="text-center text-danger py-3">Error al conectar con la API de Permisos.</td></tr>');
                }
            });
        };

        // Modal para visualizar anexos
        window.verAnexosPermiso = function(listaAdjuntos) {
            let listHtml = '<div class="list-group list-group-flush small">';
            listaAdjuntos.forEach(adj => {
                const urlArchivo = `${URL_BASE_SISTEMA}/asistencia/adjuntos/ver/${adj.adj_ide}`;
                let icono = 'solar:file-text-bold';

                if (adj.adj_mime_type) {
                    if (adj.adj_mime_type.includes('pdf')) icono = 'solar:file-pdf-bold';
                    else if (adj.adj_mime_type.includes('image')) icono = 'solar:gallery-wide-bold';
                    else if (adj.adj_mime_type.includes('word') || adj.adj_mime_type.includes('document')) icono = 'solar:document-bold';
                }

                listHtml += `
                    <a href="${urlArchivo}" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between p-2">
                        <div class="text-truncate me-2" style="max-width: 250px;" title="${adj.adj_nombre_original}">
                            <iconify-icon icon="${icono}" class="text-primary me-1 fs-6 align-middle"></iconify-icon>
                            <span class="align-middle">${adj.adj_nombre_original}</span>
                        </div>
                        <iconify-icon icon="solar:square-share-line-bold" class="fs-5 text-secondary" title="Abrir en pestaña nueva"></iconify-icon>
                    </a>
                `;
            });
            listHtml += '</div>';

            $('#bodyAnexosPermisosList').html(listHtml);
            const modalElement = document.getElementById('modalVerAnexosPermisos');
            const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
            modal.show();
        };

        // Modal Abrir
        window.abrirModalFormPermiso = function() {
            $('#formPermiso')[0].reset();
            $('#rp_ide').val('');
            $('#cntHoras').empty();
            const modal = new bootstrap.Modal(document.getElementById('modalFormPermiso'));
            modal.show();
        };

        // Guardar Permiso
        window.guardarPermiso = function(e) {
            e.preventDefault();

            const hInicio = $('#rp_hora_salida').val();
            const hFin = $('#rp_hora_retorno').val();

            if (!hInicio || !hFin) {
                Swal.fire('Atención', 'Debe especificar ambas horas (Inicio y Fin).', 'warning');
                return false;
            }

            if (hFin <= hInicio) {
                Swal.fire({
                    icon: 'error',
                    title: 'Rango de horario inválido',
                    text: 'La hora fin debe ser mayor a la hora de inicio.'
                });
                $('#rp_hora_retorno').focus();
                return false;
            }

            const formElement = document.getElementById('formPermiso');
            const formData = new FormData(formElement);
            const $btn = $('#btnGuardarPermiso');

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Guardando...');

            $.ajax({
                url: `${URL_BASE_PERMISO}/api/guardar`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(res) {
                    bootstrap.Modal.getInstance(document.getElementById('modalFormPermiso')).hide();
                    cargarListadoPermisos();

                    toastr.success(
                        res.message || 'Permiso guardado correctamente.',
                        '¡Éxito!', {
                            positionClass: 'toast-top-right',
                            timeOut: 3000
                        }
                    );
                },
                error: function(xhr) {
                    let errorMsg = 'Ocurrió un error inesperado al guardar.';

                    if (xhr.responseJSON?.messages) {
                        if (typeof xhr.responseJSON.messages === 'object') {
                            errorMsg = Object.values(xhr.responseJSON.messages).join('<br>');
                        } else {
                            errorMsg = xhr.responseJSON.messages;
                        }
                    } else if (xhr.responseJSON?.message) {
                        errorMsg = xhr.responseJSON.message;
                    }

                    toastr.error(errorMsg, 'Error al guardar', {
                        positionClass: 'toast-top-right',
                        timeOut: 4000
                    });
                },
                complete: function() {
                    $btn.prop('disabled', false).html('<iconify-icon icon="solar:diskette-bold" class="me-1"></iconify-icon> Guardar Registro');
                }
            });
        };

        // Eliminar Permiso con Auditoría
        window.eliminarPermiso = function(rpIde) {
            const modalAbierto = document.querySelector('.modal.show');
            const targetElement = modalAbierto ? modalAbierto : 'body';

            Swal.fire({
                title: '¿Eliminar registro?',
                text: 'Escriba el motivo de la eliminación para el registro de auditoría:',
                icon: 'warning',
                input: 'textarea',
                inputPlaceholder: 'Ingrese el motivo aquí...',
                inputAttributes: {
                    'aria-label': 'Motivo de la eliminación',
                    rows: '3'
                },
                target: targetElement,
                heightAuto: false,
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'btn btn-sm btn-danger me-2',
                    cancelButton: 'btn btn-sm btn-secondary'
                },
                buttonsStyling: false,
                didOpen: () => {
                    if (document.activeElement) document.activeElement.blur();
                    setTimeout(() => {
                        const textarea = Swal.getInput();
                        if (textarea) textarea.focus();
                    }, 150);
                },
                inputValidator: (value) => {
                    if (!value || !value.trim()) {
                        return 'Debe especificar un motivo para poder eliminar el registro.';
                    }
                },
                showLoaderOnConfirm: true,
                preConfirm: (motivo) => {
                    return $.ajax({
                        url: `${URL_BASE_PERMISO}/api/eliminar/${rpIde}`,
                        type: 'POST',
                        data: {
                            motivo_cambio: motivo
                        },
                        dataType: 'json'
                    }).fail((xhr) => {
                        const res = xhr.responseJSON;
                        const errorMsg = res?.messages?.error ||
                            res?.message ||
                            (typeof res?.messages === 'string' ? res.messages : null) ||
                            'No se pudo eliminar el registro.';
                        Swal.showValidationMessage(`Error: ${errorMsg}`);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Eliminado!',
                        text: 'El permiso ha sido eliminado correctamente.',
                        timer: 1500,
                        showConfirmButton: false,
                        target: targetElement,
                        heightAuto: false
                    });
                    cargarListadoPermisos();
                }
            });
        };

        // Escapar strings HTML
        function esc(str) {
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
        }

        // Init
        cargarTiposPermiso();
        cargarListadoPermisos();
    })();

    // Cálculo dinámico de diferencia de horas
    function calcularHorasPermiso() {
        const hInicio = $('#rp_hora_salida').val();
        const hFin = $('#rp_hora_retorno').val();

        if (hInicio && hFin) {
            const start = new Date(`1970-01-01T${hInicio}:00`);
            const end = new Date(`1970-01-01T${hFin}:00`);

            if (end > start) {
                const diffMs = end - start;
                const diffMins = Math.floor(diffMs / 60000);
                const hrs = Math.floor(diffMins / 60);
                const mins = diffMins % 60;

                let texto = `${hrs} hora(s)`;
                if (mins > 0) texto += ` con ${mins} min.`;

                $('#cntHoras').html(`
                    <span class="badge bg-light-primary text-primary border border-primary-subtle">
                        <iconify-icon icon="solar:clock-circle-bold" class="me-1"></iconify-icon>
                        Total: ${texto}
                    </span>
                `);
                return;
            } else {
                $('#cntHoras').html(`
                    <span class="badge bg-light-danger text-danger border border-danger-subtle">
                        La hora fin debe ser mayor a la hora de inicio
                    </span>
                `);
                return;
            }
        }
        $('#cntHoras').empty();
    }

    $('#rp_hora_salida, #rp_hora_retorno').on('change input', calcularHorasPermiso);
</script>