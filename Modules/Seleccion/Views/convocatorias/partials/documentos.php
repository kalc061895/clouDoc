<div class="card bg-white p-4">
    <!-- CABECERA -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:document-text-bold" class="me-2 text-primary" style="font-size: 1.5rem;"></iconify-icon>
                Requisitos y Documentos
            </h4>
            <p class="text-muted mb-0 small">
                Define los documentos obligatorios, opcionales y plantillas requeridas para la postulación.
            </p>
        </div>
        <button
            type="button"
            class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center"
            onclick="abrirModalDocumento()">
            <iconify-icon icon="lucide:plus" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
            Agregar Documento
        </button>
    </div>

    <!-- RESUMEN -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="border rounded p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="me-3 text-primary">
                        <iconify-icon icon="solar:documents-bold" style="font-size: 1.6rem;"></iconify-icon>
                    </div>
                    <div>
                        <div class="text-muted small">Documentos registrados</div>
                        <div class="fs-5 fw-bold text-dark" id="totalDocumentos">0</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="border rounded p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="me-3 text-danger">
                        <iconify-icon icon="solar:document-add-bold" style="font-size: 1.6rem;"></iconify-icon>
                    </div>
                    <div>
                        <div class="text-muted small">Documentos obligatorios</div>
                        <div class="fs-5 fw-bold text-danger" id="totalObligatorios">0</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="border rounded p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="me-3 text-success">
                        <iconify-icon icon="solar:document-text-bold" style="font-size: 1.6rem;"></iconify-icon>
                    </div>
                    <div>
                        <div class="text-muted small">Opcionales / Plantillas</div>
                        <div class="fs-5 fw-bold text-success" id="totalOpcionales">0</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLA -->
    <div class="table-responsive">
        <table id="tablaDocumentos" class="table table-hover align-middle w-100">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Documento / Requisito</th>
                    <th class="text-center" style="width: 140px;">Tipo</th>
                    <th class="text-center" style="width: 130px;">Carácter</th>
                    <th class="text-center" style="width: 150px;">Plantilla</th>
                    <th class="text-end" style="width: 110px;">Acciones</th>
                </tr>
            </thead>
            <tbody id="tbodyDocumentos">
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <div class="mb-2">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        </div>
                        <div class="small">Cargando documentos...</div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="modalDocumento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalDocumentoTitle">Nuevo Documento Requerido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <form id="formDocumento" enctype="multipart/form-data" onsubmit="guardarDocumento(event)">
                <div class="modal-body py-3">
                    <input type="hidden" id="cod_ide" name="cod_ide">
                    <input type="hidden" id="cod_con_ide" name="cod_con_ide" value="<?= $convocatoriaId ?>">

                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold" for="cod_nombre">
                                Nombre del requisito / documento
                                <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="cod_nombre"
                                name="cod_nombre"
                                placeholder="Ej. Anexo 01 - Declaración Jurada de Filiación"
                                maxlength="255"
                                required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold" for="cod_tipo">
                                Tipo de registro
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="cod_tipo" name="cod_tipo" required>
                                <option value="BASES">BASES</option>
                                <option value="ANEXO">ANEXO</option>
                                <option value="ACTA">REQUISITO</option>
                                <option value="DECLARACION_JURADA">DECLARACIÓN JURADA</option>
                                <option value="OTRO">OTROS</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            <div class="border rounded p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="small fw-bold text-dark">Carácter de presentación</div>
                                        <div class="small text-muted">
                                            Determina si el postulante está obligado a adjuntar este archivo para enviar su expediente.
                                        </div>
                                    </div>
                                    <div class="form-check form-switch mb-0">
                                        <input type="hidden" name="cod_obligatorio" value="0">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            id="cod_obligatorio"
                                            name="cod_obligatorio"
                                            value="1"
                                            checked>
                                        <label class="form-check-label fw-semibold" for="cod_obligatorio" id="labelObligatorio">
                                            Obligatorio
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold" for="archivo_adjunto">
                                Plantilla o Formato Descargable (PDF / Word)
                            </label>
                            <input
                                type="file"
                                class="form-control"
                                id="archivo_adjunto"
                                name="archivo_adjunto"
                                accept=".pdf,.doc,.docx">
                            <div class="form-text small" id="infoAdjuntoActual">
                                Opcional. Adjunta la plantilla oficial en blanco para que el postulante la descargue y llene.
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <label class="form-label small fw-bold" for="cod_descripcion">
                                Indicaciones / Observaciones para el Postulante
                            </label>
                            <textarea
                                class="form-control"
                                id="cod_descripcion"
                                name="cod_descripcion"
                                rows="2"
                                placeholder="Ej. El documento debe presentarse foliado, firmado con lapicero azul y escaneado en PDF."></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm px-3" id="btnGuardarDoc">
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

        $('#cod_obligatorio').on('change', function() {
            $('#labelObligatorio').text(this.checked ? 'Obligatorio' : 'Opcional');
        });

        window.abrirModalDocumento = function() {
            $('#formDocumento')[0].reset();
            $('#cod_ide').val('');
            $('#cod_con_ide').val(convocatoriaId);
            $('#cod_obligatorio').prop('checked', true).trigger('change');
            $('#infoAdjuntoActual').html(
                'Opcional. Adjunta la plantilla oficial en blanco para que el postulante la descargue.'
            );
            $('#modalDocumentoTitle').text('Nuevo Documento Requerido');

            if (window.modalDocBs) {
                window.modalDocBs.show();
            }
        };

        window.editarDocumento = function(doc) {
            $('#formDocumento')[0].reset();
            $('#cod_ide').val(doc.cod_ide);
            $('#cod_con_ide').val(doc.cod_con_ide);
            $('#cod_nombre').val(doc.cod_nombre);
            $('#cod_tipo').val(doc.cod_tipo);
            $('#cod_descripcion').val(doc.cod_descripcion || '');

            const esOblig = parseInt(doc.cod_obligatorio) === 1;

            $('#cod_obligatorio')
                .prop('checked', esOblig)
                .trigger('change');

            if (doc.cod_ruta) {
                $('#infoAdjuntoActual').html(`
                <span class="text-success fw-bold">
                    <i class="bi bi-file-earmark-check me-1"></i>
                    Archivo actual registrado.
                </span>
                Sube uno nuevo solo si deseas reemplazarlo.
            `);
            } else {
                $('#infoAdjuntoActual').html(
                    'Opcional. Adjunta la plantilla oficial en blanco.'
                );
            }

            $('#modalDocumentoTitle').text('Editar Documento Requerido');

            if (window.modalDocBs) {
                window.modalDocBs.show();
            }
        };

        window.guardarDocumento = function(e) {
            e.preventDefault();

            const $btn = $('#btnGuardarDoc');

            $btn
                .prop('disabled', true)
                .html(`
                <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                Guardando...
            `);

            const formData = new FormData($('#formDocumento')[0]);

            $.ajax({
                url: '<?= base_url('seleccion/admin/documentos-convocatoria/guardar') ?>',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(r) {
                    if (r.ok || r.status) {
                        if (window.modalDocBs) {
                            window.modalDocBs.hide();
                        }

                        listarDocumentos();

                        Toast.fire({
                            icon: 'success',
                            title: r.message || 'Documento guardado correctamente'
                        });
                    } else {
                        Swal.fire(
                            'Atención',
                            r.message || 'No se pudo guardar el documento.',
                            'warning'
                        );
                    }
                },
                error: function(xhr) {
                    const res = xhr.responseJSON || {};

                    Swal.fire(
                        'Error',
                        res.message || 'Ocurrió un error en el servidor.',
                        'error'
                    );
                },
                complete: function() {
                    $btn
                        .prop('disabled', false)
                        .html('<i class="bi bi-save me-1"></i> Guardar');
                }
            });
        };

        window.eliminarDocumento = function(id) {
            Swal.fire({
                title: '¿Eliminar requisito?',
                text: 'El documento ya no figurará en las bases ni se le exigirá al postulante.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(function(result) {
                if (!result.isConfirmed) {
                    return;
                }

                $.post(
                        '<?= base_url('seleccion/admin/documentos-convocatoria/eliminar/') ?>' + id
                    )
                    .done(function(r) {
                        if (r.ok || r.status) {
                            listarDocumentos();

                            Toast.fire({
                                icon: 'success',
                                title: r.message || 'Documento eliminado correctamente'
                            });
                        } else {
                            Swal.fire(
                                'Error',
                                r.message || 'No se pudo eliminar el documento.',
                                'error'
                            );
                        }
                    })
                    .fail(function(xhr) {
                        const res = xhr.responseJSON || {};

                        Swal.fire(
                            'Error',
                            res.message || 'No se pudo procesar la eliminación.',
                            'error'
                        );
                    });
            });
        };

        function escapeHtml(text) {
            return $('<div>').text(text || '').html();
        }

        function getBadgeTipo(tipo) {
            switch (tipo) {
                case 'BASES':
                    return '<span class="badge bg-primary-subtle text-primary border border-primary-subtle">BASES</span>';
                case 'ACTA':
                    return '<span class="badge bg-info-subtle text-info border border-info-subtle">ACTA</span>';
                case 'ANEXO':
                    return '<span class="badge bg-warning-subtle text-warning border border-warning-subtle">ANEXO</span>';
                case 'DECLARACION_JURADA':
                    return '<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">DECLARACIÓN JURADA</span>';
                case 'CARGOS':
                    return '<span class="badge bg-dark-subtle text-dark border border-dark-subtle">CARGOS</span>';
                default:
                    return '<span class="badge bg-light text-dark border">OTROS</span>';
            }
        }

        function getBadgeObligatorio(valor) {
            if (parseInt(valor) === 1) {
                return '<span class="badge bg-danger-subtle text-danger border border-danger-subtle">OBLIGATORIO</span>';
            }

            return '<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">OPCIONAL</span>';
        }

        function listarDocumentos() {
            $.get(
                '<?= base_url('seleccion/admin/documentos-convocatoria/listar/') ?>' + convocatoriaId,
                function(r) {
                    const data = r.data || r;
                    let rows = '';
                    let obligatorios = 0;
                    let opcionales = 0;

                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(function(item, index) {
                            const esOblig = parseInt(item.cod_obligatorio) === 1;

                            if (esOblig) {
                                obligatorios++;
                            } else {
                                opcionales++;
                            }

                            const rutaBase64 = item.cod_ruta ?
                                encodeURIComponent(btoa(item.cod_ruta)) :
                                '';

                            const adjunto = item.cod_ruta ?
                                `
                                <a
                                    href="<?= base_url('seleccion/ver/documento') ?>?path=${rutaBase64}"
                                    target="_blank"
                                    class="btn btn-sm btn-outline-primary py-0 px-2"
                                    title="Ver documento">
                                    <iconify-icon icon="lucide:file-text" class="me-1"></iconify-icon>
                                    Ver
                                </a>
                            ` :
                                '<span class="text-muted small">Sin archivo</span>';

                            rows += `
                            <tr>
                                <td class="text-muted fw-semibold">${index + 1}</td>
                                <td>
                                    <div class="fw-bold text-dark">
                                        ${escapeHtml(item.cod_nombre)}
                                    </div>
                                    ${
                                        item.cod_descripcion
                                            ? `<small class="text-muted">${escapeHtml(item.cod_descripcion)}</small>`
                                            : ''
                                    }
                                </td>
                                <td class="text-center">
                                    ${getBadgeTipo(item.cod_tipo)}
                                </td>
                                <td class="text-center">
                                    ${getBadgeObligatorio(item.cod_obligatorio)}
                                </td>
                                <td class="text-center">
                                    ${adjunto}
                                </td>
                                <td class="text-end">
                                    <button
                                        type="button"
                                        class="btn btn-outline-warning btn-sm rounded-circle p-1 me-1 d-inline-flex align-items-center justify-content-center"
                                        onclick='editarDocumento(${JSON.stringify(item)})'
                                        title="Editar"
                                        style="width:32px;height:32px;">
                                        <iconify-icon icon="lucide:edit" style="font-size:1rem;"></iconify-icon>
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-outline-danger btn-sm rounded-circle p-1 d-inline-flex align-items-center justify-content-center"
                                        onclick="eliminarDocumento(${item.cod_ide})"
                                        title="Eliminar"
                                        style="width:32px;height:32px;">
                                        <iconify-icon icon="lucide:trash-2" style="font-size:1rem;"></iconify-icon>
                                    </button>
                                </td>
                            </tr>
                        `;
                        });

                        $('#totalDocumentos').text(data.length);
                        $('#totalObligatorios').text(obligatorios);
                        $('#totalOpcionales').text(opcionales);
                    } else {
                        rows = `
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="mb-2">
                                    <iconify-icon
                                        icon="solar:document-text-broken"
                                        style="font-size:2rem;">
                                    </iconify-icon>
                                </div>
                                <div class="small">
                                    No se han registrado documentos para esta convocatoria.
                                </div>
                            </td>
                        </tr>
                    `;

                        $('#totalDocumentos').text(0);
                        $('#totalObligatorios').text(0);
                        $('#totalOpcionales').text(0);
                    }

                    $('#tbodyDocumentos').html(rows);
                }
            ).fail(function() {
                $('#tbodyDocumentos').html(`
                <tr>
                    <td colspan="6" class="text-center py-5 text-danger">
                        <iconify-icon icon="solar:danger-triangle-bold" class="me-1"></iconify-icon>
                        Error al obtener los documentos.
                    </td>
                </tr>
            `);
            });
        }

        const modalEl = document.getElementById('modalDocumento');

        if (modalEl) {
            window.modalDocBs =
                bootstrap.Modal.getInstance(modalEl) ||
                new bootstrap.Modal(modalEl);
        }

        listarDocumentos();
    })();
</script>