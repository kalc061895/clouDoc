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
        <select id="filtroMesLicencia" class="form-select form-select-sm" style="width: 130px;" onchange="cargarListadoLicencias()">
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

        <select id="filtroAnioLicencia" class="form-select form-select-sm" style="width: 100px;" onchange="cargarListadoLicencias()">
            <?php foreach ($anios as $anio): ?>
                <?php $selected = ($anio['numero'] == date('Y')) ? 'selected' : ''; ?>
                <option value="<?= $anio['numero'] ?>" <?= $selected ?>>
                    <?= esc($anio['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Botón Registrar -->
        <button class="btn btn-sm btn-primary d-flex align-items-center text-nowrap" onclick="abrirModalFormLicencia()">
            <iconify-icon icon="solar:add-circle-bold" class="me-1 fs-6"></iconify-icon> Nueva Licencia
        </button>
    </div>
</div>

<!-- Tabla de Registros -->
<div class="table-responsive">
    <table class="table table-sm table-hover table-bordered align-middle mb-0" id="tblLicencias">
        <thead class=" text-secondary small">
            <tr>
                <th style="width: 40px;" class="text-center">#</th>
                <th>Tipo de Licencia</th>
                <th>Periodo (Inicio - Fin)</th>
                <th>Doc. Sustento</th>
                <th>Anexos</th>
                <th>Motivo / Justificación</th>
                <th class="text-center">Estado</th>
                <th style="width: 90px;" class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody id="tbodyLicencias">
            <tr>
                <td colspan="8" class="text-center py-4 text-muted">
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
                <h6 class="modal-title  text-light fw-bold" id="titleModalLicencia">
                    <iconify-icon icon="solar:document-add-bold" class="me-1"></iconify-icon> Registrar Licencia / Papeleta
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- enctype multipart/form-data obligatorio para enviar archivos -->
            <form id="formLicencia" onsubmit="guardarLicencia(event)" enctype="multipart/form-data">
                <div class="modal-body row g-3">
                    <input type="hidden" id="rl_ide" name="rl_ide">
                    <input type="hidden" id="rl_perl_ide" name="rl_perl_ide" value="<?= $perl_ide ?>">

                    <!-- Tipo de Licencia -->
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Tipo de Licencia <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm" id="rl_lic_ide" name="rl_lic_ide" required>
                            <option value="">-- Seleccione Tipo --</option>
                        </select>
                    </div>

                    <!-- Rango de Fechas -->
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Fecha Inicio <span class="text-danger">*</span></label>
                        <input type="date" class="form-control form-control-sm" id="rl_fecha_inicio" name="rl_fecha_inicio" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Fecha Fin <span class="text-danger">*</span></label>
                        <input type="date" class="form-control form-control-sm" id="rl_fecha_fin" name="rl_fecha_fin" required>
                    </div>

                    <!-- NUEVO: Badge informativo de total de días -->
                    <div class="col-12 mt-1">
                        <div id="cntDias" class="text-end small fw-semibold text-muted" style="font-size: 0.8rem;">
                            <!-- Se llena dinámicamente: Ej. "Total: 5 día(s)" -->
                        </div>
                    </div>

                    <!-- Documento de Sustento -->
                    <div class="col-6">
                        <label class="form-label small fw-semibold">N° Doc. Sustento</label>
                        <input type="text" class="form-control form-control-sm" id="rl_numero_documento" name="rl_numero_documento" placeholder="Ej: Ficha N° 102-2026">
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Fecha Documento</label>
                        <input type="date" class="form-control form-control-sm" id="rl_fecha_documento" name="rl_fecha_documento">
                    </div>

                    <!-- NUEVO: Campo de Carga de Múltiples Anexos/Adjuntos -->
                    <div class="col-12">
                        <label class="form-label small fw-semibold d-flex justify-content-between align-items-center">
                            <span>Adjuntar Anexos / Sustentos (Opcional)</span>
                            <span class="badge bg-light-secondary text-muted">Formatos: PDF, JPG, PNG</span>
                        </label>
                        <input type="file" class="form-control form-control-sm" id="anexos" name="anexos[]" multiple accept=".pdf,.png,.jpg,.jpeg">
                        <div class="form-text text-muted small" style="font-size: 0.75rem;">
                            Puedes seleccionar uno o varios archivos manteniendo presionada la tecla Ctrl.
                        </div>
                    </div>

                    <!-- Motivo -->
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Motivo / Observación</label>
                        <textarea class="form-control form-control-sm" id="rl_motivo" name="rl_motivo" rows="2" placeholder="Detalle la razón de la solicitud..."></textarea>
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

<!-- ========================================== -->
<!-- MODAL SECUNDARIO: Ver Anexos Adjuntos      -->
<!-- ========================================== -->
<div class="modal fade" id="modalVerAnexos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content shadow border-0">
            <div class="modal-header py-2 bg-light">
                <h6 class="modal-title fw-bold text-dark small">
                    <iconify-icon icon="solar:paperclip-bold" class="me-1 text-primary"></iconify-icon> Documentos Anexos
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-2" id="bodyAnexosList">
                <!-- Se llena dinámicamente -->
            </div>
        </div>
    </div>
</div>

<!-- Script Lógico -->
<script>
    (function() {
        const URL_BASE_LICENCIA = "<?= base_url('asistencia/licencia') ?>";
        const URL_BASE_SISTEMA = "<?= base_url() ?>";
        const PERL_IDE = <?= $perl_ide ?>;

        // Cargar tipos de licencia al combo del modal
        function cargarTiposLicencia() {
            $.get(`${URL_BASE_LICENCIA}/api/tipos-activos`, function(res) {
                if (res.status === 200) {
                    let options = '<option value="">-- Seleccione Tipo --</option>';
                    res.data.forEach(item => {
                        options += `<option value="${item.lic_ide}">${item.lic_nombre} (${item.lic_remunerado == 1 ? 'Goce' : 'Sin Goce'})</option>`;
                    });
                    $('#rl_lic_ide').html(options);
                }
            });
        }

        // Listar licencias aplicando los filtros
        window.cargarListadoLicencias = function() {
            const mes = $('#filtroMesLicencia').val();
            const anio = $('#filtroAnioLicencia').val();
            const $tbody = $('#tbodyLicencias');

            $tbody.html(`
            <tr>
                <td colspan="8" class="text-center py-4 text-muted">
                    <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                    Consultando registros...
                </td>
            </tr>
            `);

            $.ajax({
                url: `${URL_BASE_LICENCIA}/api/personal/${PERL_IDE}`,
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
                            const esRemunerado = item.lic_remunerado == 1 ?
                                '<span class="badge bg-light-success text-success border border-success ms-1">Con Goce</span>' :
                                '<span class="badge bg-light-danger text-danger border border-danger ms-1">Sin Goce</span>';

                            // Renderizado del botón de anexos si existen
                            let anexosHtml = '<span class="text-muted small">-</span>';
                            // Renderizado en la tabla/listado
                            if (item.adjuntos && item.adjuntos.length > 0) {
                                const jsonAnexos = esc(JSON.stringify(item.adjuntos));
                                anexosHtml = `
                                    <button class="btn btn-xs btn-outline-info d-inline-flex align-items-center gap-1 py-0 px-2" 
                                            onclick='verAnexos(${jsonAnexos})' title="Ver anexos">
                                        <iconify-icon icon="solar:paperclip-linear"></iconify-icon>
                                        <span class="fw-bold">${item.adjuntos.length}</span>
                                    </button>
                                `;
                            }

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
                                <td class="text-center">${anexosHtml}</td>
                                <td class="small text-truncate" style="max-width: 180px;" title="${item.rl_motivo ?? ''}">
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
                            <td colspan="8" class="text-center py-4 text-muted">
                                <iconify-icon icon="solar:document-medicine-bold-duotone" class="fs-2 d-block mb-1 text-secondary"></iconify-icon>
                                No se encontraron papeletas ni licencias registradas en este periodo.
                            </td>
                        </tr>
                    `);
                    }
                },
                error: function() {
                    $tbody.html('<tr><td colspan="8" class="text-center text-danger py-3">Error al conectar con la API de Licencias.</td></tr>');
                }
            });
        };

        // Modal para visualizar la lista de anexos
        window.verAnexos = function(listaAdjuntos) {
            let listHtml = '<div class="list-group list-group-flush small">';

            listaAdjuntos.forEach(adj => {
                // 1. URL corregida apuntando al controlador seguro pasando el adj_ide
                const urlArchivo = `${URL_BASE_SISTEMA}/asistencia/adjuntos/ver/${adj.adj_ide}`;

                // 2. Icono dinámico según el MIME Type
                let icono = 'solar:file-text-bold';
                if (adj.adj_mime_type) {
                    if (adj.adj_mime_type.includes('pdf')) {
                        icono = 'solar:file-pdf-bold';
                    } else if (adj.adj_mime_type.includes('image')) {
                        icono = 'solar:gallery-wide-bold';
                    } else if (adj.adj_mime_type.includes('word') || adj.adj_mime_type.includes('document')) {
                        icono = 'solar:document-bold';
                    }
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

            // Insertar en el modal y mostrar
            $('#bodyAnexosList').html(listHtml);
            const modalElement = document.getElementById('modalVerAnexos');
            const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
            modal.show();
        };

        // Limpiar el contador cuando se abre el modal
        window.abrirModalFormLicencia = function() {
            $('#formLicencia')[0].reset();
            $('#rl_ide').val('');
            $('#cntDias').empty(); // <-- Limpia la etiqueta de días
            const modal = new bootstrap.Modal(document.getElementById('modalFormLicencia'));
            modal.show();
        };

        // Guardar Licencia (Soporta envío de binarios mediante FormData)
        window.guardarLicencia = function(e) {
            e.preventDefault();

            const fechaInicio = $('#rl_fecha_inicio').val();
            const fechaFin = $('#rl_fecha_fin').val();

            // 1. Validar que ambas fechas estén ingresadas
            if (!fechaInicio || !fechaFin) {
                Swal.fire('Atención', 'Debe seleccionar ambas fechas (Inicio y Fin).', 'warning');
                return false;
            }

            // 2. Comparar fechas (YYYY-MM-DD permite comparación directa de strings o por objeto Date)
            if (new Date(fechaFin) < new Date(fechaInicio)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Rango de fechas inválido',
                    text: 'La fecha fin debe ser mayor o igual a la fecha de inicio.'
                });

                $('#rl_fecha_fin').focus();
                return false; // Interrumpe el envío del formulario
            }

            // Reemplazamos .serialize() por FormData para procesar los archivos
            const formElement = document.getElementById('formLicencia');
            const formData = new FormData(formElement);
            const $btn = $('#btnGuardarLicencia');

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Guardando...');

            $.ajax({
                url: `${URL_BASE_LICENCIA}/api/guardar`,
                type: 'POST',
                data: formData,
                processData: false, // Requerido para envío de archivos con AJAX
                contentType: false, // Requerido para envío de archivos con AJAX
                dataType: 'json',
                success: function(res) {
                    bootstrap.Modal.getInstance(document.getElementById('modalFormLicencia')).hide();
                    cargarListadoLicencias();

                    // Notificación Toastr de éxito
                    toastr.success(
                        res.message || 'Licencia guardada correctamente.',
                        '¡Éxito!', {
                            positionClass: 'toast-top-right',
                            timeOut: 3000
                        }
                    );
                },
                error: function(xhr) {
                    let errorMsg = 'Ocurrió un error inesperado al guardar.';

                    // Capturar errores de validación de CodeIgniter 4 ($this->failValidationErrors)
                    if (xhr.responseJSON?.messages) {
                        if (typeof xhr.responseJSON.messages === 'object') {
                            errorMsg = Object.values(xhr.responseJSON.messages).join('<br>');
                        } else {
                            errorMsg = xhr.responseJSON.messages;
                        }
                    } else if (xhr.responseJSON?.message) {
                        errorMsg = xhr.responseJSON.message;
                    }

                    // Notificación Toastr de error
                    toastr.error(
                        errorMsg,
                        'Error al guardar', {
                            positionClass: 'toast-top-right',
                            timeOut: 4000
                        }
                    );
                },
                complete: function() {
                    $btn.prop('disabled', false).html('<iconify-icon icon="solar:diskette-bold" class="me-1"></iconify-icon> Guardar Registro');
                }
            });
        };

        // Eliminar Licencia con Auditoría (Solución definitiva para Focus-Trap de Bootstrap)
        window.eliminarLicencia = function(rlIde) {
            // Si hay un modal de Bootstrap abierto en la página, usamos ese modal como target
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
                target: targetElement, // Renderiza SweetAlert DENTRO del contenedor activo para evitar bloqueo de foco
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
                    // Quitar foco al elemento anterior y forzar foco en el textarea
                    if (document.activeElement) {
                        document.activeElement.blur();
                    }
                    setTimeout(() => {
                        const textarea = Swal.getInput();
                        if (textarea) {
                            textarea.focus();
                        }
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
                        url: `${URL_BASE_LICENCIA}/api/eliminar/${rlIde}`,
                        type: 'POST',
                        data: {
                            motivo_cambio: motivo
                        },
                        dataType: 'json'
                    }).fail((xhr) => {

                        // 1. Obtener el cuerpo de la respuesta JSON enviada por CodeIgniter
                        const res = xhr.responseJSON;

                        // 2. Buscar el mensaje probando las posibles rutas de la API
                        const errorMsg = res?.messages?.error ||
                            res?.message ||
                            (typeof res?.messages === 'string' ? res.messages : null) ||
                            'No se pudo eliminar el registro.';

                        // 3. Mostrar el mensaje en SweetAlert2
                        Swal.showValidationMessage(`Error: ${errorMsg}`);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Eliminado!',
                        text: 'El registro ha sido eliminado correctamente.',
                        timer: 1500,
                        showConfirmButton: false,
                        target: targetElement,
                        heightAuto: false
                    });
                    cargarListadoLicencias();
                }
            });
        };

        // Helper para escapar comillas simples/dobles en JSON en linea
        function esc(str) {
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
        }

        // Inicialización al cargar el pane
        cargarTiposLicencia();
        cargarListadoLicencias();
    })();

    // Función para calcular la diferencia de días entre fechas
    function calcularDiasLicencia() {
        const fInicio = $('#rl_fecha_inicio').val();
        const fFin = $('#rl_fecha_fin').val();

        if (fInicio && fFin) {
            const fecha1 = new Date(fInicio + 'T00:00:00');
            const fecha2 = new Date(fFin + 'T00:00:00');

            if (fecha2 >= fecha1) {
                // Diferencia en milisegundos convertida a días (incluyendo el día de inicio)
                const diffTime = Math.abs(fecha2 - fecha1);
                const totalDias = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

                $('#cntDias').html(`
                <span class="badge bg-light-primary text-primary border border-primary-subtle">
                    <iconify-icon icon="solar:calendar-mark-bold" class="me-1"></iconify-icon>
                    Total: ${totalDias} día(s)
                </span>
            `);
                return;
            } else {
                $('#cntDias').html(`
                <span class="badge bg-light-danger text-danger border border-danger-subtle">
                    La fecha fin debe ser mayor o igual a la inicio
                </span>
            `);
                return;
            }
        }

        $('#cntDias').empty();
    }

    // Escuchar cambios en los inputs de fecha
    $('#rl_fecha_inicio, #rl_fecha_fin').on('change input', calcularDiasLicencia);
</script>