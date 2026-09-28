<?= $this->extend('layouts/seleccionLayout') ?>
<?= $this->section('title') ?>
Evaluación de Postulantes
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card bg-white p-4 shadow-sm rounded-3">
    <!-- ============================================================
         CABECERA
    ============================================================= -->
    <div class="d-flex flex-column flex-md-row
                align-items-md-center
                justify-content-between
                gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                <iconify-icon
                    icon="solar:clipboard-check-bold-duotone"
                    class="align-middle me-1 text-primary">
                </iconify-icon>
                Evaluación de Postulantes
            </h4>
            <div class="text-muted small">
                Seleccione la convocatoria para listar y
                calificar a los candidatos en comisión.
            </div>
        </div>

        <div class="col-md-4">
            <label class="form-label fw-bold
                          text-secondary small mb-1">
                Convocatoria Activa:
            </label>
            <select
                class="form-select border-primary"
                id="selectConvocatoria">
                <option value="">
                    -- Seleccione Convocatoria --
                </option>
                <?php foreach ($convocatorias as $conv): ?>
                    <option value="<?= $conv['con_ide'] ?>">
                        <?= esc($conv['con_nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- ============================================================
         TABLA POSTULANTES
    ============================================================= -->
    <div class="table-responsive">
        <table
            id="tablaPostulantes"
            class="table table-hover align-middle w-100 border">
            <thead class="table-light">
                <tr>
                <tr>
                    <th>#</th>
                    <th>Postulante</th>
                    <th>DNI</th>
                    <th>Fecha Postulación</th>
                    <th>Estado Evaluado</th>
                    <th>Puntaje Total</th>
                    <th class="text-end">Acción</th>
                </tr>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<!-- ================================================================
     MODAL EVALUACIÓN
================================================================ -->
<div
    class="modal fade"
    id="modalEvaluacion"
    tabindex="-1"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    aria-hidden="true">

    <div class="modal-dialog modal-fullscreen">

        <div class="modal-content">

            <!-- ====================================================
                 HEADER
            ===================================================== -->
            <div class="modal-header
                        bg-dark
                        text-white
                        py-2">

                <div>
                    <h5
                        class="modal-title fs-6 mb-0 text-light"
                        id="modalTitleEvaluacion">
                        <iconify-icon
                            icon="solar:user-speak-rounded-bold-duotone"
                            class="me-2">
                        </iconify-icon>
                        Evaluación de Expediente Digital
                    </h5>

                    <small
                        class="text-white-50"
                        id="lblPostulanteNombre">
                        -
                    </small>
                </div>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                    aria-label="Cerrar">
                </button>
            </div>

            <!-- ====================================================
                 BODY
            ===================================================== -->
            <div class="modal-body p-0">
                <div class="container-fluid h-100">
                    <div class="row h-100 g-0">

                        <!-- ==================================================
                             COLUMNA 1
                             CRITERIOS
                        =================================================== -->
                        <div
                            class="col-lg-4 col-xl-4
                                   border-end
                                   bg-light">

                            <div
                                class="d-flex
                                       justify-content-between
                                       align-items-center
                                       px-3
                                       py-2
                                       border-bottom
                                       bg-white">

                                <div>
                                    <div class="fw-bold text-dark">
                                        <iconify-icon
                                            icon="solar:list-check-bold"
                                            class="me-1 text-primary">
                                        </iconify-icon>
                                        Criterios de Evaluación
                                    </div>

                                    <small class="text-muted">
                                        Requisitos establecidos
                                        para la plaza
                                    </small>
                                </div>

                                <span
                                    class="badge bg-primary"
                                    id="lblTotalCriterios">
                                    0
                                </span>
                            </div>

                            <div
                                id="contenedorCriterios"
                                class="p-3 overflow-auto"
                                style="
                                    height:calc(100vh - 110px);
                                ">
                                <!-- Criterios dinámicos -->
                            </div>
                        </div>

                        <!-- ==================================================
                             COLUMNA 2
                             DOCUMENTOS
                        =================================================== -->
                        <div
                            class="col-lg-4 col-xl-4
                                   border-end
                                   bg-white">

                            <div
                                class="d-flex
                                       justify-content-between
                                       align-items-center
                                       px-3
                                       py-2
                                       border-bottom">

                                <div>
                                    <div class="fw-bold text-dark">
                                        <iconify-icon
                                            icon="solar:folder-with-files-bold"
                                            class="me-1 text-success">
                                        </iconify-icon>
                                        Documentación Presentada
                                    </div>

                                    <small class="text-muted">
                                        Documentos cargados
                                        por el postulante
                                    </small>
                                </div>

                                <span
                                    class="badge bg-success"
                                    id="lblTotalDocumentos">
                                    0
                                </span>
                            </div>

                            <div
                                id="contenedorDocumentos"
                                class="p-3 overflow-auto"
                                style="
                                    height:calc(100vh - 110px);
                                ">
                                <!-- Documentos -->
                            </div>
                        </div>

                        <!-- ==================================================
                             COLUMNA 3
                             VISOR PDF
                        =================================================== -->
                        <div
                            class="col-lg-4 col-xl-4
                                   bg-dark">

                            <div
                                class="d-flex
                                       justify-content-between
                                       align-items-center
                                       px-3
                                       py-2
                                       border-bottom
                                       border-secondary">

                                <div class="text-white">

                                    <div class="fw-bold">
                                        <iconify-icon
                                            icon="solar:document-text-bold"
                                            class="me-1">
                                        </iconify-icon>
                                        Vista previa
                                    </div>

                                    <small
                                        class="text-white-50"
                                        id="lblDocumentoActual">
                                        Seleccione un documento
                                    </small>
                                </div>

                                <a
                                    id="btnAbrirDocumento"
                                    href="#"
                                    target="_blank"
                                    class="btn
                                           btn-sm
                                           btn-outline-light
                                           d-none">

                                    <iconify-icon
                                        icon="solar:arrow-right-up-linear">
                                    </iconify-icon>
                                    Nueva pestaña
                                </a>
                            </div>

                            <div
                                class="p-2"
                                style="
                                    height:calc(100vh - 110px);
                                ">

                                <!-- MENSAJE INICIAL -->
                                <div
                                    id="visorVacio"
                                    class="h-100
                                           d-flex
                                           flex-column
                                           justify-content-center
                                           align-items-center
                                           text-white-50">

                                    <iconify-icon
                                        icon="solar:document-add-bold-duotone"
                                        style="font-size:5rem;">
                                    </iconify-icon>

                                    <div class="mt-3">
                                        Seleccione un documento
                                        para visualizarlo
                                    </div>
                                </div>

                                <!-- IFRAME -->
                                <iframe
                                    id="pdfPreview"
                                    src="about:blank"
                                    class="d-none"
                                    style="
                                        width:100%;
                                        height:100%;
                                        border:0;
                                        background:#525659;
                                    ">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ====================================================
                 FOOTER
            ===================================================== -->
            <div
                class="modal-footer
                       py-2
                       bg-white">

                <div class="me-auto">
                    <span class="text-muted small">
                        Puntaje total:
                    </span>

                    <span
                        class="fw-bold fs-5 text-primary"
                        id="lblPuntajeTotal">
                        0.00
                    </span>
                </div>

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button
                    type="button"
                    class="btn btn-success"
                    id="btnGuardarEvaluacion">
                    <iconify-icon
                        icon="solar:disk-bold"
                        class="me-1">
                    </iconify-icon>
                    Guardar Evaluación
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
<script>
    $(document).ready(function() {

        /* ================================================================
           VARIABLES
        ================================================================ */
        let tablePostulantes = null;

        /* ================================================================
           CONFIGURACIÓN DE TIPOS
        ================================================================ */
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

        /* ================================================================
           DATATABLE POSTULANTES
        ================================================================ */
        tablePostulantes = $('#tablaPostulantes').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            responsive: true,
            columns: [{
                    data: 'pto_ide'
                },

                {
                    data: 'postulante_nombre'
                },

                {
                    data: 'dni'
                },

                {
                    data: 'fecha_postulacion'
                },

                {
                    data: 'eva_estado',
                    render: function(data) {
                        if (data === 'EVALUADO') {
                            return `
                            <span class="badge bg-success">
                                EVALUADO
                            </span>
                        `;
                        }
                        return `
                        <span
                            class="badge
                                   bg-warning
                                   text-dark">
                                   PENDIENTE
                            </span>
                    `;
                    }
                },

                {
                    data: 'eva_puntaje_total',
                    render: function(data) {
                        return data ?
                            parseFloat(data).toFixed(2) :
                            '0.00';
                    }
                },

                {
                    data: null,
                    className: 'text-end',
                    render: function(data, type, row) {
                        return `
                        <button
                            type="button"
                            class="btn
                                   btn-sm
                                   btn-primary
                                   rounded-pill
                                   btn-evaluar"
                            data-id="${row.pto_ide}">
                            <iconify-icon
                                icon="solar:pen-bold"
                                class="me-1">
                            </iconify-icon>
                            Evaluar
                            </button>
                        `;
                    }
                }
            ]
        });

        /* ================================================================
           CAMBIO DE CONVOCATORIA
        ================================================================ */
        $('#selectConvocatoria').on(
            'change',
            function() {
                const conIde = $(this).val();

                if (!conIde) {
                    tablePostulantes
                        .clear()
                        .draw();
                    return;
                }

                tablePostulantes.ajax
                    .url(
                        '<?= base_url('seleccion/comision/evaluacion/postulantes') ?>/' +
                        conIde
                    )
                    .load();
            }
        );

        /* ================================================================
           ESCAPAR HTML
        ================================================================ */
        function escapeHtml(value) {
            return String(value ?? '')
                .replace(
                    /[&<>"']/g,
                    function(character) {
                        return {
                            '&': '&amp;',
                            '<': '&lt;',
                            '>': '&gt;',
                            '"': '&quot;',
                            "'": '&#039;'
                        } [character];
                    }
                );
        }

        /* ================================================================
           URL DEL DOCUMENTO
        ================================================================ */
        function obtenerUrlDocumento(ruta) {
            if (!ruta) {
                return null;
            }

            /*
             * Convertimos:
             *
             * uploads/expedientes/2026/08/archivo.pdf
             *
             * en Base64
             */
            const pathBase64 = btoa(
                unescape(
                    encodeURIComponent(ruta)
                )
            );

            return '<?= base_url('seleccion/ver/documento') ?>' +
                '?path=' +
                encodeURIComponent(pathBase64);
        }

        /* ================================================================
           MOSTRAR DOCUMENTO
        ================================================================ */
        function visualizarDocumento(documento) {
            if (!documento || !documento.ruta) {
                toastr.warning(
                    'El documento no tiene una ruta válida.',
                    'Documento'
                );
                return;
            }

            const url =
                obtenerUrlDocumento(documento.ruta);

            if (!url) {
                return;
            }

            $('#pdfPreview')
                .attr('src', url)
                .removeClass('d-none');

            $('#visorVacio')
                .addClass('d-none');

            $('#btnAbrirDocumento')
                .attr('href', url)
                .removeClass('d-none');

            $('#lblDocumentoActual')
                .text(documento.nombre || 'Documento');

            /*
             * Quitar selección anterior
             */
            $('.documento-postulante')
                .removeClass(
                    'border-primary bg-primary-subtle'
                );

            /*
             * Seleccionar documento actual
             */
            $(
                    '[data-documento-id="' +
                    documento.exd_ide +
                    '"]'
                )
                .addClass(
                    'border-primary bg-primary-subtle'
                );
        }

        /* ================================================================
           OBTENER DOCUMENTOS DEL POSTULANTE
        ================================================================ */
        function obtenerDocumentosPostulante(postulante) {
            let documentos = [];

            const grupos = [{
                    tipo: 'FORMACION',
                    nombre: 'Formación',
                    items: postulante.formacion || []
                },

                {
                    tipo: 'FORMACION',
                    nombre: 'Profesión',
                    items: postulante.profesion || []
                },

                {
                    tipo: 'CAPACITACION',
                    nombre: 'Capacitación',
                    items: postulante.capacitaciones || []
                },

                {
                    tipo: 'EXPERIENCIA',
                    nombre: 'Experiencia',
                    items: postulante.experiencia || []
                },

                {
                    tipo: 'IDENTIFICACION',
                    nombre: 'Identificación / Otros',
                    items: postulante.identificacion || []
                }
            ];

            grupos.forEach(function(grupo) {

                grupo.items.forEach(function(item) {

                    if (!item.exd_ruta) {
                        return;
                    }

                    documentos.push({
                        tipo: grupo.tipo,
                        categoria: grupo.nombre,
                        exd_ide: item.exd_ide,
                        nombre: item.exd_nombre_original,
                        ruta: item.exd_ruta,
                        mime: item.exd_mime,
                        item: item
                    });
                });
            });

            return documentos;
        }

        /* ================================================================
           RENDERIZAR CRITERIOS
        ================================================================ */
        function renderizarCriterios(criterios) {

            const grupos = {};

            criterios.forEach(function(cri) {

                const tipo =
                    cri.cri_tipo || 'OTROS';

                if (!grupos[tipo]) {
                    grupos[tipo] = [];
                }

                grupos[tipo].push(cri);
            });

            let html = '';

            Object.keys(grupos)
                .forEach(function(tipo) {

                    const criteriosGrupo =
                        grupos[tipo];

                    html += `
                    <div class="mb-4">
                    
                        <div
                            class="d-flex
                                   align-items-center
                                   mb-2">
                                   
                            <iconify-icon
                                icon="${iconosTipo[tipo] || iconosTipo.OTROS}"
                                class="text-primary me-2"
                                style="font-size:1.4rem;">
                                </iconify-icon>
                            
                            <div>
                            <div class="fw-bold text-dark">
                                ${escapeHtml(
                                        nombresTipo[tipo]
                                        || tipo
                                    )}
                                    </div>
                                
                                <small class="text-muted">
                                ${criteriosGrupo.length}
                                    criterio(s)
                                    </small>
                                </div>
                            </div>
                        `;

                    criteriosGrupo.forEach(function(cri) {

                        html += `
                        <div
                            class="card
                                   border
                                   mb-2
                                   shadow-sm
                                   criterio-item"
                            data-criterio-id="${cri.cri_ide}">
                            
                            <div
                                class="card-body p-3">
                                
                                <div
                                    class="d-flex
                                           justify-content-between
                                           align-items-start">
                                           
                                    <div
                                        class="fw-semibold
                                               text-dark">
                                               ${escapeHtml(
                                            cri.cri_nombre
                                        )}
                                        </div>
                                    
                                    <span
                                        class="badge
                                               bg-primary-subtle
                                               text-primary
                                               ms-2">
                                               Máx.
                                        ${parseFloat(
                                            cri.cri_puntaje_maximo || 0
                                        ).toFixed(2)}
                                        </span>
                                    </div>
                                
                                ${
                                    cri.cri_descripcion
                                    ?
                                    `
                                        <div
                                            class="text-muted
                                                   small
                                                   mt-1">
                                                   ${escapeHtml(
                                                cri.cri_descripcion
                                            )}
                                            </div>
                                    `
                                    :
                                    ''
                                }
                                    
                                <div
                                    class="row
                                           mt-3
                                           g-2">
                                           
                                    <div class="col-6">
                                                                            <div
                                            class="form-check
                                                   form-switch">
                                                   
                                            <input
                                                class="form-check-input chk-cumple"
                                                type="checkbox"
                                                name="criterios[${cri.cri_ide}][cumple]"
                                                value="1">
                                                
                                            <label
                                                class="form-check-label
                                                       small
                                                       fw-semibold">
                                                                                                       Cumple
                                                                                                   </label>
                                                                                               </div>
                                                                                           </div>
                                                                                           <div class="col-6">
                                                                                               <input
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            max="${cri.cri_puntaje_maximo}"
                                            class="form-control
                                                   form-control-sm
                                                   input-puntaje"
                                            name="criterios[${cri.cri_ide}][puntaje]"
                                            value="0"
                                            placeholder="Puntaje">
                                                                                </div>
                                                                            </div>
                                                                            <input
                                    type="text"
                                    class="form-control
                                           form-control-sm
                                           mt-2"
                                    name="criterios[${cri.cri_ide}][observacion]"
                                    placeholder="Observación del criterio...">
                                                                </div>
                                                            </div>
                                                        `;
                    });
                    html += `</div>`;
                });
            if (!criterios.length) {
                html = `
                                                    <div
                    class="text-center
                           text-muted
                           py-5">
                           
                    <iconify-icon
                        icon="solar:clipboard-remove-bold-duotone"
                        style="font-size:4rem;">
                    </iconify-icon>
                    
                    <div class="mt-2">
                                            No existen criterios
                        configurados.
                                            </div>
                                        </div>
                                    `;
            }
            $('#contenedorCriterios')
                .html(html);

            $('#lblTotalCriterios')
                .text(criterios.length);
        }
        /* ================================================================
           RENDERIZAR DOCUMENTOS
        ================================================================ */
        function renderizarDocumentos(postulante) {

            const documentos =
                obtenerDocumentosPostulante(
                    postulante
                );

            let html = '';

            const grupos = {};
            documentos.forEach(function(doc) {

                if (!grupos[doc.tipo]) {
                    grupos[doc.tipo] = [];

                }

                grupos[doc.tipo].push(doc);
            });
            Object.keys(grupos)
                .forEach(function(tipo) {

                    html += `
                                        <div class="mb-4">
                    
                        <div
                            class="fw-bold
                                   text-secondary
                                   mb-2">
                                   
                            <iconify-icon
                                icon="${iconosTipo[tipo] || iconosTipo.OTROS}"
                                class="me-1 text-success">
                            </iconify-icon>
                            
                            ${escapeHtml(
                                nombresTipo[tipo] || tipo
                            )}
                                                    </div>
                                            `;
                    grupos[tipo]
                        .forEach(function(doc) {

                            html += `
                                                        <div
                                class="card
                                       border
                                       mb-2
                                       shadow-sm
                                       documento-postulante"
                                data-documento-id="${doc.exd_ide}"
                                style="cursor:pointer;">
                                
                                <div
                                    class="card-body p-3">
                                    
                                    <div
                                        class="d-flex
                                               align-items-start">
                                               
                                        <div class="me-3">
                                        
                                            <div
                                                class="
                                                    rounded-circle
                                                    bg-danger-subtle
                                                    text-danger
                                                    d-flex
                                                    align-items-center
                                                    justify-content-center
                                                "
                                                style="
                                                    width:42px;
                                                    height:42px;
                                                ">
                                                
                                                <iconify-icon
                                                    icon="solar:file-text-bold"
                                                    style="font-size:1.3rem;">
                                                </iconify-icon>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div
                                            class="flex-grow-1">
                                            
                                            <div
                                                class="fw-semibold
                                                       text-dark">
                                                       
                                                ${escapeHtml(
                                                    doc.nombre
                                                    || 'Documento'
                                                )}
                                                                                            </div>
                                                                                            <div
                                                class="small
                                                       text-muted
                                                       mt-1">
                                                       
                                                ${escapeHtml(
                                                    doc.categoria
                                                )}
                                                
                                                ${
                                                    doc.mime
                                                    ?
                                                    `
                                                        ·
                                                        ${escapeHtml(
                                                            doc.mime
                                                        )}
                                                    `
                                                    :
                                                    ''
                                                }
                                                                                                </div>
                                                                                                                                        <button
                                                type="button"
                                                class="
                                                    btn
                                                    btn-sm
                                                    btn-outline-primary
                                                    mt-2
                                                    btn-ver-documento
                                                "
                                                data-documento-id="${doc.exd_ide}">
                                                
                                                <iconify-icon
                                                    icon="solar:eye-bold"
                                                    class="me-1">
                                                </iconify-icon>
                                                
                                                Ver documento
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        `;
                        });
                    html += `</div>`;
                });
            if (!documentos.length) {

                html = `
                                <div
                    class="text-center
                           text-muted
                           py-5">
                           
                    <iconify-icon
                        icon="solar:folder-error-bold-duotone"
                        style="font-size:4rem;">
                    </iconify-icon>
                    
                    <div class="mt-2">
                                            El postulante no presentó
                        documentos.
                                            </div>
                                        </div>
                                    `;
            }
            $('#contenedorDocumentos')
                .html(html);

            $('#lblTotalDocumentos')
                .text(documentos.length);

            /*
             * Guardar documentos en el contenedor
             */
            $('#contenedorDocumentos')
                .data(
                    'documentos',
                    documentos
                );
        }
        /* ================================================================
           CLICK DOCUMENTO
        ================================================================ */
        $(document).on(
            'click',
            '.btn-ver-documento, .documento-postulante',
            function(e) {

                e.preventDefault();

                const id =
                    $(this).data('documento-id');

                const documentos =
                    $('#contenedorDocumentos')
                    .data('documentos') || [];

                const documento =
                    documentos.find(
                        function(doc) {
                            return String(
                                doc.exd_ide
                            ) === String(id);
                        }
                    );

                if (!documento) {

                    toastr.error(
                        'No se encontró la información del documento.',
                        'Documento'
                    );

                    return;
                }

                visualizarDocumento(
                    documento
                );
            }
        );
        /* ================================================================
           ABRIR MODAL DE EVALUACIÓN
        ================================================================ */
        $(document).on(
            'click',
            '.btn-evaluar',
            function() {

                const ptoIde =
                    $(this).data('id');

                /*
                 * Mostrar loading
                 */
                Swal.fire({
                    title: 'Cargando expediente',
                    text: 'Obteniendo información del postulante...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: function() {
                        Swal.showLoading();

                    }
                });
                $.ajax({
                    url: '<?= base_url('seleccion/comision/evaluacion/formulario') ?>/' +
                        ptoIde,
                    type: 'GET',
                    dataType: 'json',

                    success: function(res) {

                        Swal.close();

                        /* ==========================================
                           DATOS POSTULANTE
                        ========================================== */
                        $('#eva_pto_ide')
                            .val(
                                res.postulacion ?
                                res.postulacion.pto_ide :
                                ptoIde
                            );

                        $('#eva_fie_ide')
                            .val(
                                res.ficha ?
                                res.ficha.fie_ide :
                                ''
                            );

                        $('#lblPostulanteNombre')
                            .text(
                                (
                                    res.postulacion
                                    ?.postulante_nombre ||
                                    '-'
                                ) +
                                ' (' +
                                (
                                    res.postulacion
                                    ?.per_numero_documento ||
                                    ''
                                ) +
                                ')'
                            );
                        /* ==========================================
                           CRITERIOS
                        ========================================== */
                        renderizarCriterios(
                            res.criterios || []
                        );
                        /* ==========================================
                           DOCUMENTOS
                        ========================================== */
                        renderizarDocumentos(
                            res.postulante || {}
                        );
                        /* ==========================================
                           LIMPIAR VISOR
                        ========================================== */
                        $('#pdfPreview')
                            .attr(
                                'src',
                                'about:blank'
                            )
                            .addClass('d-none');

                        $('#visorVacio')
                            .removeClass('d-none');

                        $('#btnAbrirDocumento')
                            .addClass('d-none')
                            .attr('href', '#');

                        $('#lblDocumentoActual')
                            .text(
                                'Seleccione un documento'
                            );
                        /* ==========================================
                           EVALUACIÓN EXISTENTE
                        ========================================== */
                        $('#eva_observacion')
                            .val(
                                res.evaluacion ?
                                res.evaluacion.eva_observacion :
                                ''
                            );
                        /*
                         * Cargar detalles existentes
                         */
                        if (res.detalles) {
                            Object.keys(res.detalles)
                                .forEach(function(criIde) {

                                    const det =
                                        res.detalles[criIde];

                                    const checkbox =
                                        $(
                                            '[name="criterios[' +
                                            criIde +
                                            '][cumple]"]'
                                        );

                                    const puntaje =
                                        $(
                                            '[name="criterios[' +
                                            criIde +
                                            '][puntaje]"]'
                                        );

                                    const observacion =
                                        $(
                                            '[name="criterios[' +
                                            criIde +
                                            '][observacion]"]'
                                        );

                                    if (checkbox.length) {
                                        checkbox.prop(
                                            'checked',
                                            Number(
                                                det.evd_cumple
                                            ) === 1
                                        );
                                    }

                                    if (puntaje.length) {
                                        puntaje.val(
                                            det.evd_puntaje ??
                                            0
                                        );
                                    }

                                    if (observacion.length) {
                                        observacion.val(
                                            det.evd_observacion ??
                                            ''
                                        );
                                    }
                                });
                        }
                        calcularPuntajeTotal();
                        /* ==========================================
                           MOSTRAR MODAL
                        ========================================== */
                        $('#modalEvaluacion')
                            .modal('show');
                    },

                    error: function(xhr) {

                        Swal.close();

                        const msg =
                            xhr.responseJSON ?
                            (
                                xhr.responseJSON.error ||
                                xhr.responseJSON.message
                            ) :
                            'Ocurrió un error al obtener la ficha.';

                        toastr.error(
                            msg,
                            'Error'
                        );
                    }
                });
            }
        );
        /* ================================================================
           RECALCULAR PUNTAJE
        ================================================================ */
        $(document).on(
            'input',
            '.input-puntaje',
            function() {
                calcularPuntajeTotal();

            }
        );

        function calcularPuntajeTotal() {

            let total = 0;

            $('.input-puntaje')
                .each(
                    function() {

                        const val =
                            parseFloat(
                                $(this).val()
                            );

                        if (!isNaN(val)) {
                            total += val;

                        }
                    }
                );

            $('#lblPuntajeTotal')
                .text(
                    total.toFixed(2)
                );
        }
        /* ================================================================
           GUARDAR EVALUACIÓN
        ================================================================ */
        $('#btnGuardarEvaluacion')
            .on(
                'click',
                function() {

                    Swal.fire({
                            title: '¿Confirmar Calificación?',
                            text: 'Se registrará el puntaje final y las observaciones ingresadas.',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#198754',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Sí, Guardar Dictamen',
                            cancelButtonText: 'Cancelar'

                        })
                        .then(
                            function(result) {

                                if (!result.isConfirmed) {
                                    return;

                                }

                                $.ajax({
                                    url: '<?= base_url('seleccion/comision/evaluacion/guardar') ?>',
                                    type: 'POST',
                                    data: $('#formEvaluacion').length ?
                                        $('#formEvaluacion').serialize() : construirFormularioEvaluacion(),
                                    dataType: 'json',

                                    success: function(res) {

                                        if (
                                            res.status ===
                                            'success'
                                        ) {

                                            toastr.success(
                                                res.message,
                                                'Éxito'
                                            );

                                            $('#modalEvaluacion')
                                                .modal('hide');

                                            tablePostulantes
                                                .ajax
                                                .reload(
                                                    null,
                                                    false
                                                );
                                        } else {

                                            toastr.error(
                                                res.message,
                                                'Error'
                                            );
                                        }
                                    },

                                    error: function(xhr) {

                                        const msg =
                                            xhr.responseJSON ?
                                            (
                                                xhr.responseJSON.message ||
                                                xhr.responseJSON.error
                                            ) :
                                            'Error del servidor al procesar la solicitud.';

                                        toastr.error(
                                            msg,
                                            'Error'
                                        );
                                    }
                                });
                            }
                        );
                }
            );
        /* ================================================================
           CONSTRUIR FORMULARIO
           Como eliminamos el <form> original del modal,
           construimos los datos manualmente.
        ================================================================ */
        function construirFormularioEvaluacion() {

            const data = {
                eva_pto_ide: $('#eva_pto_ide').val(),
                eva_fie_ide: $('#eva_fie_ide').val(),
                eva_observacion: $('#eva_observacion').val(),
                criterios: {}
            };

            $('.criterio-item')
                .each(
                    function() {

                        const criterioId =
                            $(this)
                            .data(
                                'criterio-id'
                            );

                        data.criterios[criterioId] = {

                            cumple: $(this)
                                .find(
                                    '.chk-cumple'
                                )
                                .is(':checked') ?
                                1 : 0,

                            puntaje: $(this)
                                .find(
                                    '.input-puntaje'
                                )
                                .val() ||
                                0,

                            observacion: $(this)
                                .find(
                                    'input[name*="[observacion]"]'
                                )
                                .val() ||
                                ''
                        };
                    }
                );

            return data;
        }
        /* ================================================================
           CREAR CAMPOS OCULTOS NECESARIOS
           Para mantener compatibilidad con tu endpoint actual
        ================================================================ */
        $('#modalEvaluacion')
            .on(
                'show.bs.modal',
                function() {

                    if (!$('#formEvaluacion').length) {

                        const form =
                            $('<form>')
                            .attr(
                                'id',
                                'formEvaluacion'
                            );

                        form.append(
                            $('<input>')
                            .attr({
                                type: 'hidden',
                                id: 'eva_pto_ide',
                                name: 'eva_pto_ide'
                            })
                        );

                        form.append(
                            $('<input>')
                            .attr({
                                type: 'hidden',
                                id: 'eva_fie_ide',
                                name: 'eva_fie_ide'
                            })
                        );

                        form.append(
                            $('<input>')
                            .attr({
                                type: 'hidden',
                                id: 'eva_observacion',
                                name: 'eva_observacion'
                            })
                        );

                        /*
                         * Los inputs dinámicos realmente
                         * están dentro del contenedor de criterios.
                         *
                         * No necesitamos agregarlo físicamente
                         * al formulario porque construiremos
                         * el payload manualmente.
                         */
                        $('body')
                            .append(form);
                    }
                }
            );
        /* ================================================================
           LIMPIAR VISOR AL CERRAR
        ================================================================ */
        $('#modalEvaluacion')
            .on(
                'hidden.bs.modal',
                function() {

                    $('#pdfPreview')
                        .attr(
                            'src',
                            'about:blank'
                        )
                        .addClass('d-none');

                    $('#visorVacio')
                        .removeClass('d-none');

                    $('#btnAbrirDocumento')
                        .addClass('d-none')
                        .attr('href', '#');

                    $('#lblDocumentoActual')
                        .text(
                            'Seleccione un documento'
                        );

                    $('#contenedorCriterios')
                        .empty();

                    $('#contenedorDocumentos')
                        .empty();

                    $('#lblTotalCriterios')
                        .text('0');

                    $('#lblTotalDocumentos')
                        .text('0');

                    $('#lblPuntajeTotal')
                        .text('0.00');
                }
            );
    });
</script>
<?= $this->endSection() ?>