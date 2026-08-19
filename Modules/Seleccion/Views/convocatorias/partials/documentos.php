<div class="card bg-white p-4 shadow-sm border-0 rounded-3">
    <!-- =========================================================
         CABECERA
    ========================================================== -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:document-text-bold" class="me-2 text-primary" style="font-size: 1.5rem;"></iconify-icon>
                Requisitos y Documentos Solicitados
            </h4>
            <p class="text-muted mb-0 small">
                Configura los requisitos obligatorios y plantillas descargables para la postulación digital.
            </p>
        </div>
        <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center" onclick="abrirModalDocumento()">
            <iconify-icon icon="lucide:plus" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
            Agregar Documento
        </button>
    </div>

    <!-- =========================================================
         RESUMEN
    ========================================================== -->
    <div class="row g-3 mb-4">
        <!-- Total documentos -->
        <div class="col-md-4">
            <div class="border rounded p-3 h-100 bg-light-subtle">
                <div class="d-flex align-items-center">
                    <div class="me-3 text-primary">
                        <iconify-icon icon="solar:documents-bold" style="font-size: 1.8rem;"></iconify-icon>
                    </div>
                    <div>
                        <div class="text-muted small">Documentos registrados</div>
                        <div class="fs-4 fw-bold text-dark" id="totalDocumentos">0</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Obligatorios -->
        <div class="col-md-4">
            <div class="border rounded p-3 h-100 bg-light-subtle">
                <div class="d-flex align-items-center">
                    <div class="me-3 text-danger">
                        <iconify-icon icon="solar:document-add-bold" style="font-size: 1.8rem;"></iconify-icon>
                    </div>
                    <div>
                        <div class="text-muted small">Obligatorios</div>
                        <div class="fs-4 fw-bold text-danger" id="totalObligatorios">0</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Opcionales -->
        <div class="col-md-4">
            <div class="border rounded p-3 h-100 bg-light-subtle">
                <div class="d-flex align-items-center">
                    <div class="me-3 text-success">
                        <iconify-icon icon="solar:document-text-bold" style="font-size: 1.8rem;"></iconify-icon>
                    </div>
                    <div>
                        <div class="text-muted small">Opcionales / Plantillas</div>
                        <div class="fs-4 fw-bold text-success" id="totalOpcionales">0</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- =========================================================
         TABLA DE DOCUMENTOS
    ========================================================== -->
    <div class="table-responsive">
        <table id="tablaDocumentos" class="table table-hover align-middle w-100">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;" class="text-center">#</th>
                    <th>Documento / Requisito</th>
                    <th class="text-center" style="width: 140px;">Tipo</th>
                    <th class="text-center" style="width: 130px;">Carácter</th>
                    <th class="text-center" style="width: 150px;">Plantilla Adjunta</th>
                    <th class="text-end" style="width: 110px;">Acciones</th>
                </tr>
            </thead>
            <tbody id="tbodyDocumentos">
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
                        <div class="small">Cargando requisitos...</div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- =============================================================
     MODAL REGISTRAR / EDITAR DOCUMENTO
============================================================== -->
<div class="modal fade" id="modalDocumento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <!-- Header -->
            <div class="modal-header border-bottom pb-3">
                <h5 class="modal-title fw-bold" id="modalDocumentoTitle">Nuevo Documento Requerido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <form id="formDocumento" enctype="multipart/form-data" onsubmit="guardarDocumento(event)">
                <div class="modal-body py-3">
                    <!-- IDs Ocultos -->
                    <input type="hidden" id="cod_ide" name="cod_ide">
                    <input type="hidden" id="cod_con_ide" name="cod_con_ide" value="<?= $convocatoriaId ?>">

                    <div class="row g-3">
                        <!-- Nombre del documento -->
                        <div class="col-md-8">
                            <label class="form-label small fw-bold" for="cod_nombre">
                                Nombre del requisito / documento <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="cod_nombre" name="cod_nombre" placeholder="Ej. Anexo 01 - Declaración Jurada de Filiación" maxlength="255" required>
                        </div>

                        <!-- Tipo de documento -->
                        <div class="col-md-4">
                            <label class="form-label small fw-bold" for="cod_tipo">
                                Tipo de registro <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="cod_tipo" name="cod_tipo" required>
                                <option value="ANEXO">ANEXO</option>
                                <option value="DECLARACION_JURADA">DECLARACIÓN JURADA</option>
                                <option value="REQUISITO">REQUISITO GENERAL</option>
                                <option value="BASES">BASES DEL CONCURSO</option>
                                <option value="OTRO">OTRO</option>
                            </select>
                        </div>

                        <!-- Carácter obligatorio (Switch) -->
                        <div class="col-12">
                            <div class="border rounded p-3 bg-light-subtle">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="small fw-bold text-dark">Carácter de presentación</div>
                                        <div class="small text-muted">Determina si el postulante está obligado a adjuntar este archivo para enviar su expediente.</div>
                                    </div>
                                    <div class="form-check form-switch mb-0">
                                        <input type="hidden" name="cod_obligatorio" value="0">
                                        <input class="form-check-input" type="checkbox" id="cod_obligatorio" name="cod_obligatorio" value="1" checked>
                                        <label class="form-check-label fw-semibold" for="cod_obligatorio" id="labelObligatorio">Obligatorio</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Plantilla / Formato adjunto opcional -->
                        <div class="col-12">
                            <label class="form-label small fw-bold" for="archivo_adjunto">
                                Plantilla o Formato Descargable (PDF / Word)
                            </label>
                            <input type="file" class="form-control" id="archivo_adjunto" name="archivo_adjunto" accept=".pdf,.doc,.docx">
                            <div class="form-text small" id="infoAdjuntoActual">Opcional. Adjunta la plantilla oficial en blanco para que el postulante la descargue y llene.</div>
                        </div>

                        <!-- Indicaciones / Descripción -->
                        <div class="col-12">
                            <label class="form-label small fw-bold" for="cod_descripcion">
                                Indicaciones / Observaciones para el Postulante
                            </label>
                            <textarea class="form-control" id="cod_descripcion" name="cod_descripcion" rows="3" placeholder="Ej. El documento debe presentarse foliado, firmado con lapicero azul y escaneado en PDF."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer border-top pt-2">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm px-3" id="btnGuardarDoc">
                        <i class="bi bi-save me-1"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- =============================================================
     SCRIPT DE INTERACCIÓN AJAX Y DATA
============================================================== -->
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

        // Control dinámico del label del switch
        $('#cod_obligatorio').on('change', function() {
            $('#labelObligatorio').text(this.checked ? 'Obligatorio' : 'Opcional');
        });

        window.abrirModalDocumento = function() {
            $('#formDocumento')[0].reset();
            $('#cod_ide').val('');
            $('#cod_con_ide').val(convocatoriaId);
            $('#cod_obligatorio').prop('checked', true).trigger('change');
            $('#infoAdjuntoActual').html('Opcional. Adjunta la plantilla oficial en blanco para que el postulante la descargue.');
            $('#modalDocumentoTitle').text('Nuevo Documento Requerido');
            if (window.modalDocBs) window.modalDocBs.show();
        };

        window.editarDocumento = function(doc) {
            $('#formDocumento')[0].reset();
            $('#cod_ide').val(doc.cod_ide);
            $('#cod_con_ide').val(doc.cod_con_ide);
            $('#cod_nombre').val(doc.cod_nombre);
            $('#cod_tipo').val(doc.cod_tipo);
            $('#cod_descripcion').val(doc.cod_descripcion);

            const esOblig = parseInt(doc.cod_obligatorio) === 1;
            $('#cod_obligatorio').prop('checked', esOblig).trigger('change');

            if (doc.cod_ruta) {
                $('#infoAdjuntoActual').html(`<span class="text-success fw-bold"><i class="bi bi-file-earmark-check"></i> Archivo actual registrado.</span> Sube uno nuevo solo si deseas reemplazarlo.`);
            } else {
                $('#infoAdjuntoActual').html('Opcional. Adjunta la plantilla oficial en blanco.');
            }

            $('#modalDocumentoTitle').text('Editar Documento Requerido');
            if (window.modalDocBs) window.modalDocBs.show();
        };

        window.guardarDocumento = function(e) {
            e.preventDefault();
            const $btn = $('#btnGuardarDoc').prop('disabled', true);
            const formData = new FormData($('#formDocumento')[0]);

            $.ajax({
                url: '<?= base_url('seleccion/admin/documentos-convocatoria/guardar') ?>',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(r) {
                    if (r.ok || r.status) {
                        if (window.modalDocBs) window.modalDocBs.hide();
                        listarDocumentos();
                        Toast.fire({
                            icon: 'success',
                            title: r.message || 'Documento guardado'
                        });
                    } else {
                        Swal.fire('Atención', r.message || 'No se pudo guardar.', 'warning');
                    }
                },
                error: function(xhr) {
                    const res = xhr.responseJSON || {};
                    Swal.fire('Error', res.message || 'Ocurrió un error en el servidor.', 'error');
                },
                complete: function() {
                    $btn.prop('disabled', false);
                }
            });
        };

        window.eliminarDocumento = function(id) {
            Swal.fire({
                title: '¿Eliminar requisito?',
                text: "El documento ya no figurará en las bases ni se le exigirá al postulante.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('<?= base_url('seleccion/admin/documentos-convocatoria/eliminar/') ?>' + id)
                        .done(r => {
                            if (r.ok || r.status) {
                                listarDocumentos();
                                Toast.fire({
                                    icon: 'success',
                                    title: r.message || 'Eliminado correctamente'
                                });
                            }
                        });
                }
            });
        };

        function escapeHtml(text) {
            return $('<div>').text(text || '').html();
        }

        function listarDocumentos() {
            $.get('<?= base_url('seleccion/admin/documentos-convocatoria/listar/') ?>' + convocatoriaId, r => {
                const data = r.data || r;
                let rows = '';
                let obligatorios = 0;
                let opcionales = 0;

                if (Array.isArray(data) && data.length > 0) {
                    data.forEach((item, index) => {
                        const esOblig = parseInt(item.cod_obligatorio) === 1;
                        if (esOblig) obligatorios++;
                        else opcionales++;

                        const badgeOblig = esOblig ?
                            '<span class="badge bg-danger-subtle text-danger border border-danger-subtle">OBLIGATORIO</span>' :
                            '<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">OPCIONAL</span>';

                        // Se codifica 'item.cod_ruta' a Base64 en el cliente
                        const rutaBase64 = item.cod_ruta ? encodeURIComponent(btoa(item.cod_ruta)) : '';

                        const adjunto = item.cod_ruta ?
                            `<a href="<?= base_url('seleccion/ver/documento') ?>?path=${rutaBase64}" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2" title="Ver Documento">
                                <i class="bi bi-file-earmark-text"></i> Ver
                            </a>` :
                            '<span class="text-muted small">Sin archivo</span>';

                        rows += `
                        <tr>
                            <td class="fw-bold text-center">${index + 1}</td>
                            <td>
                                <div class="fw-bold text-dark">${escapeHtml(item.cod_nombre)}</div>
                                ${item.cod_descripcion ? `<small class="text-muted d-block">${escapeHtml(item.cod_descripcion)}</small>` : ''}
                            </td>
                            <td class="text-center"><span class="badge bg-light text-dark border">${item.cod_tipo}</span></td>
                            <td class="text-center">${badgeOblig}</td>
                            <td class="text-center">${adjunto}</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-light text-primary me-1" onclick='editarDocumento(${JSON.stringify(item)})' title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-light text-danger" onclick="eliminarDocumento(${item.cod_ide})" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    });
                    $('#totalDocumentos').text(data.length);
                    $('#totalObligatorios').text(obligatorios);
                    $('#totalOpcionales').text(opcionales);
                } else {
                    rows = '<tr><td colspan="6" class="text-center py-4 text-muted">No se han configurado documentos ni requisitos para esta convocatoria.</td></tr>';
                    $('#totalDocumentos').text(0);
                    $('#totalObligatorios').text(0);
                    $('#totalOpcionales').text(0);
                }

                $('#tbodyDocumentos').html(rows);
            }).fail(() => {
                $('#tbodyDocumentos').html('<tr><td colspan="6" class="text-center py-4 text-danger">Error al cargar la lista de documentos.</td></tr>');
            });
        }

        const modalEl = document.getElementById('modalDocumento');
        if (modalEl) {
            window.modalDocBs = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        }

        listarDocumentos();
    })();
</script>