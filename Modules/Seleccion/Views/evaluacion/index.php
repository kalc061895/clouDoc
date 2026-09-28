<?= $this->extend('layouts/seleccionLayout') ?>

<?= $this->section('title') ?>
Evaluación de Postulantes
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card bg-white p-4 shadow-sm rounded-3">
    <!-- CABECERA -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                <iconify-icon icon="solar:clipboard-check-bold-duotone" class="align-middle me-1 text-primary"></iconify-icon>
                Evaluación de Postulantes
            </h4>
            <div class="text-muted small">
                Seleccione la convocatoria para listar y calificar a los candidatos en comisión.
            </div>
        </div>

        <div class="col-md-4">
            <label class="form-label fw-bold text-secondary small mb-1">Convocatoria Activa:</label>
            <select class="form-select border-primary" id="selectConvocatoria">
                <option value="">-- Seleccione Convocatoria --</option>
                <?php foreach ($convocatorias as $conv): ?>
                    <option value="<?= $conv['con_ide'] ?>"><?= esc($conv['con_nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- TABLA DATATABLES -->
    <div class="table-responsive">
        <table id="tablaPostulantes" class="table table-hover align-middle w-100 border">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Postulante</th>
                    <th>DNI</th>
                    <th>Cargo</th>
                    <th>Fecha Postulación</th>
                    <th>Estado</th>
                    <th class="text-end">Acción</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- MODAL SPLIT EVALUACIÓN (PREVISUALIZACIÓN + FORMULARIO) -->
<!-- MODAL DE EVALUACIÓN A 3 COLUMNAS -->
<div class="modal fade" id="modalEvaluacion" tabindex="-1" aria-labelledby="modalEvaluacionLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-fullscreen modal-xl style-modal-xl">
        <div class="modal-content">

            <!-- ENCABEZADO DEL MODAL -->
            <div class="modal-header bg-primary text-white py-2">
                <div>
                    <h5 class="modal-title fs-6 fw-bold text-white mb-0" id="modalEvaluacionLabel">
                        <iconify-icon icon="solar:clipboard-check-bold" class="me-1 align-middle"></iconify-icon>
                        Ficha de Evaluación Curricular
                    </h5>
                    <small id="lblPostulanteNombre" class="text-white-50 small">Cargando postulante...</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- CUERPO DEL MODAL (3 COLUMNAS) -->
            <div class="modal-body p-2 bg-light">
                <form id="formEvaluacion">
                    <div class="row g-2">

                        <!-- COLUMNA 1: CRITERIOS DE EVALUACIÓN -->
                        <div class="col-md-4 d-flex flex-column">
                            <div class="card border-0 shadow-sm flex-fill">
                                <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center border-bottom">
                                    <span class="fw-bold small text-dark">
                                        <iconify-icon icon="solar:checklist-minimalistic-bold" class="text-primary me-1 align-middle"></iconify-icon>
                                        1. Criterios a Evaluar
                                    </span>
                                    <span class="badge bg-primary-subtle text-primary" id="lblTotalCriterios">0</span>
                                </div>
                                <div class="card-body p-2" id="contenedorCriterios" style="max-height: 72vh; overflow-y: auto;">
                                    <!-- Se renderiza dinámicamente vía JavaScript -->
                                </div>
                            </div>
                        </div>

                        <!-- COLUMNA 2: DETALLES, CONTADORES Y DOCUMENTOS -->
                        <div class="col-md-4 d-flex flex-column">
                            <div class="card border-0 shadow-sm flex-fill">
                                <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center border-bottom">
                                    <span class="fw-bold small text-dark">
                                        <iconify-icon icon="solar:folder-open-bold" class="text-warning me-1 align-middle"></iconify-icon>
                                        2. Hoja de Vida / Resumen
                                    </span>
                                    <span class="badge bg-secondary" id="lblTotalDocumentos">0 docs</span>
                                </div>
                                <div class="card-body p-2" id="contenedorDocumentos" style="max-height: 72vh; overflow-y: auto;">
                                    <!-- Se renderizan los widgets de experiencia/capacitaciones y los documentos -->
                                </div>
                            </div>
                        </div>

                        <!-- COLUMNA 3: VISOR PDF DE DOCUMENTOS -->
                        <div class="col-md-4 d-flex flex-column">
                            <div class="card border-0 shadow-sm flex-fill">
                                <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center border-bottom">
                                    <span class="fw-bold small text-dark text-truncate" id="lblDocumentoActual">
                                        <iconify-icon icon="solar:document-text-bold" class="text-danger me-1 align-middle"></iconify-icon>
                                        3. Vista Previa
                                    </span>
                                    <a href="#" id="btnAbrirDocumento" target="_blank" class="btn btn-xs btn-outline-secondary d-none" title="Abrir en pestaña nueva">
                                        <iconify-icon icon="solar:export-bold"></iconify-icon>
                                    </a>
                                </div>
                                <div class="card-body p-0 d-flex justify-content-center align-items-center bg-dark-subtle position-relative" style="min-height: 450px; max-height: 72vh;">
                                    <!-- Mensaje cuando no hay selección -->
                                    <div id="visorVacio" class="text-center text-muted p-4">
                                        <iconify-icon icon="solar:file-remove-bold-duotone" width="48" height="48" class="mb-2 text-secondary"></iconify-icon>
                                        <p class="mb-0 small fw-semibold">Selecciona un documento de la Columna 2 para previsualizarlo.</p>
                                    </div>
                                    <!-- Iframe del PDF -->
                                    <iframe id="pdfPreview" class="w-100 h-100 border-0 d-none" style="min-height: 68vh;"></iframe>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>

            <!-- PIE DEL MODAL (RESULTADOS Y ACCIONES) -->
            <div class="modal-footer bg-white py-2 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div>
                        <span class="text-muted small d-block" style="font-size:0.75rem;">PUNTAJE TOTAL:</span>
                        <span class="fs-5 fw-bolder text-primary" id="lblPuntajeTotal">0.00</span>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success btn-sm rounded-pill px-3" id="btnGuardarEvaluacion">
                        <iconify-icon icon="solar:diskette-bold" class="me-1 align-middle"></iconify-icon> Guardar Dictamen
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script>
    $(document).ready(function() {

        let tablePostulantes = null;
        let datosCargados = null; // Guardará el JSON completo del postulante

        const nombresTipo = {
            FORMACION: 'Formación Profesional',
            EXPERIENCIA: 'Experiencia Laboral',
            CAPACITACION: 'Capacitaciones',
            IDENTIFICACION: 'Identificación / Otros',
            OTROS: 'Otros'
        };

        const iconosTipo = {
            FORMACION: 'solar:diploma-bold-duotone',
            EXPERIENCIA: 'solar:case-round-bold-duotone',
            CAPACITACION: 'solar:notebook-bold-duotone',
            IDENTIFICACION: 'solar:document-text-bold-duotone',
            OTROS: 'solar:folder-bold-duotone'
        };

        // 1. DATATABLE DE POSTULANTES
        tablePostulantes = $('#tablaPostulantes').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            responsive: true,
            columns: [{
                    data: 'pto_ide'
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        const pos = row.postulante || row;
                        return `${pos.pos_apellido_paterno || ''} ${pos.pos_apellido_materno || ''} ${pos.pos_nombres || ''}`;
                    }
                },
                {
                    data: 'pos_documento',
                    defaultContent: '-'
                },
                {
                    data: 'pto_fecha_presentacion',
                    defaultContent: '-'
                },
                {
                    data: 'eva_estado',
                    render: function(data) {
                        return data === 'EVALUADO' ?
                            '<span class="badge bg-success">EVALUADO</span>' :
                            '<span class="badge bg-warning text-dark">PENDIENTE</span>';
                    }
                },
                {
                    data: 'eva_puntaje_total',
                    render: function(data) {
                        return data ? parseFloat(data).toFixed(2) : '0.00';
                    }
                },
                {
                    data: null,
                    className: 'text-end',
                    render: function(data, type, row) {
                        return `
                        <button type="button" class="btn btn-sm btn-primary rounded-pill btn-evaluar" data-id="${row.pto_ide || row.postulacion.pto_ide}">
                            <iconify-icon icon="solar:pen-bold" class="me-1"></iconify-icon> Evaluar
                        </button>
                    `;
                    }
                }
            ]
        });

        // 2. SELECCIÓN DE CONVOCATORIA
        $('#selectConvocatoria').on('change', function() {
            const conIde = $(this).val();
            if (!conIde) {
                tablePostulantes.clear().draw();
                return;
            }
            tablePostulantes.ajax.url('<?= base_url('seleccion/comision/evaluacion/postulantes') ?>/' + conIde).load();
        });

        // 3. ABRIR MODAL Y CARGAR DATOS
        $(document).on('click', '.btn-evaluar', function() {
            const ptoIde = $(this).data('id');

            $.ajax({
                url: '<?= base_url('seleccion/comision/evaluacion/formulario') ?>/' + ptoIde,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    datosCargados = res;

                    // Encabezado
                    const pos = res.convocatoria || res.postulante || {};
                    const nombreCompleto = `${pos.pos_nombres || ''} ${pos.pos_apellido_paterno || ''} ${pos.pos_apellido_materno || ''}`;
                    $('#lblPostulanteNombre').text(`${nombreCompleto} - DNI: ${pos.pos_documento || 'S/D'}`);

                    // Renderizar las 3 columnas
                    renderizarCriterios(res.criterios || []);
                    renderizarDocumentosYResumen(res.postulante || {});

                    // Reiniciar Visor PDF
                    $('#pdfPreview').addClass('d-none').attr('src', 'about:blank');
                    $('#visorVacio').removeClass('d-none');
                    $('#btnAbrirDocumento').addClass('d-none');

                    calcularPuntajeTotal();
                    $('#modalEvaluacion').modal('show');
                },
                error: function() {
                    toastr.error('Ocurrió un error al cargar la ficha del postulante.');
                }
            });
        });

        // 4. RENDERIZAR COLUMNA 1: CRITERIOS DE EVALUACIÓN
        function renderizarCriterios(criterios) {
            $('#lblTotalCriterios').text(criterios.length);

            if (!criterios.length) {
                $('#contenedorCriterios').html('<div class="text-center text-muted py-4">No hay criterios configurados.</div>');
                return;
            }

            let html = '';
            criterios.forEach(function(cri) {
                const maxPuntaje = parseFloat(cri.cri_puntaje_maximo || 0).toFixed(2);

                html += `
                <div class="card border mb-3 shadow-sm criterio-card" id="criterio-card-${cri.cri_ide}">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <span class="badge bg-light text-dark border me-1">${cri.cri_codigo}</span>
                            <span class="badge bg-primary-subtle text-primary fw-bold">Máx: ${maxPuntaje} pts</span>
                        </div>
                        <div class="fw-bold text-dark mb-1">${cri.cri_nombre}</div>
                        <div class="text-muted extra-small mb-2">${cri.cri_tipo}</div>

                        <div class="row g-2 align-items-center mt-2">
                            <div class="col-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input chk-cumple" 
                                           type="checkbox" 
                                           id="switch_${cri.cri_ide}"
                                           data-id="${cri.cri_ide}"
                                           data-max="${maxPuntaje}"
                                           name="criterios[${cri.cri_ide}][cumple]" value="1">
                                    <label class="form-check-label small fw-semibold" for="switch_${cri.cri_ide}">Cumple</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <input type="number" 
                                       step="0.01" 
                                       min="0" 
                                       max="${maxPuntaje}" 
                                       class="form-control form-control-sm input-puntaje text-end fw-bold" 
                                       id="puntaje_${cri.cri_ide}"
                                       name="criterios[${cri.cri_ide}][puntaje]" 
                                       value="0.00" placeholder="Puntaje">
                            </div>
                        </div>
                        <input type="text" class="form-control form-control-sm mt-2" name="criterios[${cri.cri_ide}][observacion]" placeholder="Observación del criterio...">
                    </div>
                </div>
            `;
            });

            $('#contenedorCriterios').html(html);
        }

        // 5. RENDERIZAR COLUMNA 2: RESUMEN CON CONTADORES Y DOCUMENTOS
        function renderizarDocumentosYResumen(postulante) {
            let html = '';
            let totalDocs = 0;

            // A. CÁLCULO DE REGLAS DE NEGOCIO (EXPERIENCIA Y CAPACITACIÓN)
            let totalDiasExp = 0;
            (postulante.experiencia || []).forEach(exp => {
                totalDiasExp += parseInt(exp.pex_dias_declarados || 0);
            });

            const aniosExp = Math.floor(totalDiasExp / 365);
            const mesesExp = Math.floor((totalDiasExp % 365) / 30);
            const diasExp = (totalDiasExp % 365) % 30;

            let totalHorasCap = 0;
            let cantCap = (postulante.capacitaciones || []).length;
            (postulante.capacitaciones || []).forEach(cap => {
                totalHorasCap += parseFloat(cap.pca_horas || 0);
            });

            // B. WIDGETS DE RESUMEN (REGLAS Y CONTADORES)
            html += `
            <div class="card bg-light border-0 mb-3 shadow-sm">
                <div class="card-body p-2">
                    <div class="row text-center g-1">
                        <div class="col-6">
                            <div class="bg-white p-2 rounded border">
                                <small class="text-muted d-block fw-bold" style="font-size:0.75rem;">EXP. TOTAL</small>
                                <span class="fw-bold text-success small">${aniosExp}a ${mesesExp}m ${diasExp}d</span>
                                <small class="d-block text-muted" style="font-size:0.68rem;">(${totalDiasExp} días)</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-white p-2 rounded border">
                                <small class="text-muted d-block fw-bold" style="font-size:0.75rem;">CAPACITACIONES</small>
                                <span class="fw-bold text-info small">${cantCap} Cursos</span>
                                <small class="d-block text-muted" style="font-size:0.68rem;">(${totalHorasCap} hrs tot.)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

            // C. ACORDEÓN / LISTA DE DOCUMENTOS ADJUNTOS
            const secciones = [{
                    titulo: 'Profesión / Título',
                    items: postulante.profesion || [],
                    badge: 'bg-primary'
                },
                {
                    titulo: 'Formación Académica',
                    items: postulante.formacion || [],
                    badge: 'bg-info'
                },
                {
                    titulo: 'Capacitaciones',
                    items: postulante.capacitaciones || [],
                    badge: 'bg-warning text-dark'
                },
                {
                    titulo: 'Experiencia Laboral',
                    items: postulante.experiencia || [],
                    badge: 'bg-success'
                },
                {
                    titulo: 'Identificación / Otros',
                    items: postulante.identificacion || [],
                    badge: 'bg-secondary'
                }
            ];

            secciones.forEach(sec => {
                if (sec.items.length > 0) {
                    html += `<div class="fw-bold text-secondary small mb-2 mt-3">${sec.titulo} (${sec.items.length})</div>`;

                    sec.items.forEach(item => {
                        if (item.exd_ruta) {
                            totalDocs++;
                            let detalleTxt = item.ppr_titulo || item.pfo_carrera || item.pca_nombre || item.pex_cargo || item.otr_descripcion || 'Documento adjunto';
                            let subTxt = item.pca_horas ? `${item.pca_horas} Horas` : (item.pex_dias_declarados ? `${item.pex_dias_declarados} Días` : '');

                            html += `
                            <div class="card border mb-2 shadow-sm item-documento" style="cursor:pointer;" data-ruta="${item.exd_ruta}" data-nombre="${item.exd_nombre_original}">
                                <div class="card-body p-2 d-flex align-items-center justify-content-between">
                                    <div class="text-truncate me-2">
                                        <div class="fw-semibold small text-dark text-truncate">${detalleTxt}</div>
                                        <small class="text-muted d-block" style="font-size:0.72rem;">${item.exd_nombre_original}</small>
                                    </div>
                                    ${subTxt ? `<span class="badge ${sec.badge} small">${subTxt}</span>` : ''}
                                </div>
                            </div>
                        `;
                        }
                    });
                }
            });

            $('#lblTotalDocumentos').text(totalDocs);
            $('#contenedorDocumentos').html(html);
        }

        // 6. EVENTO SWITCH: AUTOCOMPLETAR PUNTAJE AUTOMÁTICAMENTE
        $(document).on('change', '.chk-cumple', function() {
            const criId = $(this).data('id');
            const maxPuntaje = $(this).data('max');
            const inputPuntaje = $(`#puntaje_${criId}`);

            if ($(this).is(':checked')) {
                inputPuntaje.val(maxPuntaje);
            } else {
                inputPuntaje.val('0.00');
            }
            calcularPuntajeTotal();
        });

        // 7. EVENTO SELECCIÓN DE DOCUMENTO (VISUALIZAR EN COLUMNA 3)
        $(document).on('click', '.item-documento', function() {
            $('.item-documento').removeClass('border-primary bg-primary-subtle');
            $(this).addClass('border-primary bg-primary-subtle');

            const ruta = $(this).data('ruta'); // ej: "uploads/expedientes/2026/08/archivo.pdf"
            const nombre = $(this).data('nombre');

            // 1. Convertir la ruta a Base64
            const rutaBase64 = btoa(ruta);

            // 2. Construir la nueva URL con el parámetro path
            const fullUrl = '<?= base_url('seleccion/ver/documento') ?>?path=' + rutaBase64;

            // 3. Actualizar los elementos de la vista
            $('#lblDocumentoActual').text(nombre);
            $('#btnAbrirDocumento').attr('href', fullUrl).removeClass('d-none');
            $('#visorVacio').addClass('d-none');
            $('#pdfPreview').attr('src', fullUrl).removeClass('d-none');
        });

        // 8. CÁLCULO EN TIEMPO REAL DEL PUNTAJE TOTAL
        $(document).on('input', '.input-puntaje', function() {
            calcularPuntajeTotal();
        });

        function calcularPuntajeTotal() {
            let total = 0;
            $('.input-puntaje').each(function() {
                const val = parseFloat($(this).val());
                if (!isNaN(val)) total += val;
            });
            $('#lblPuntajeTotal').text(total.toFixed(2));
        }

        // 9. GUARDAR EVALUACIÓN CON ALERTA DE CONFIRMACIÓN
        $('#btnGuardarEvaluacion').on('click', function() {

            // Armar el payload dinámicamente desde la vista
            let formData = $('#formEvaluacion').serializeArray();

            Swal.fire({
                title: '¿Guardar Evaluación?',
                text: "Se registrará el dictamen final del postulante.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, Guardar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('seleccion/comision/evaluacion/guardar') ?>',
                        type: 'POST',
                        data: $('#formEvaluacion').serialize() + '&puntaje_total=' + $('#lblPuntajeTotal').text(),
                        dataType: 'json',
                        success: function(res) {
                            if (res.success) {
                                toastr.success(res.message);
                                $('#modalEvaluacion').modal('hide');
                                if (typeof tablePostulantes !== 'undefined') {
                                    tablePostulantes.ajax.reload(null, false);
                                }
                            } else {
                                toastr.error('Por favor revise los campos requeridos.');
                            }
                        },
                        error: function(err) {
                            toastr.error('Ocurrió un error al guardar la evaluación.');
                        }
                    });
                }
            });
        });

    });
</script>
<?= $this->endSection() ?>