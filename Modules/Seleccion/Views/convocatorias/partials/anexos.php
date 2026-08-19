<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="fw-bold mb-0">Bases, Anexos y Comunicados Oficiales</h5>
        <p class="text-muted small mb-0">Adjunta los archivos oficiales (PDF, DOCX) que los postulantes podrán descargar.</p>
    </div>
    <button class="btn btn-primary btn-sm" onclick="abrirModalAnexo()">
        <i class="bi bi-cloud-upload me-1"></i> Subir Archivo
    </button>
</div>

<!-- Listado de Archivos y Anexos -->
<div class="table-responsive bg-white rounded shadow-sm border">
    <table class="table table-hover align-middle mb-0" id="tablaAnexos">
        <thead class="table-light">
            <tr>
                <th style="width: 50px;">#</th>
                <th>Tipo</th>
                <th>Descripción del Documento</th>
                <th style="width: 140px;">Fecha Carga</th>
                <th class="text-center" style="width: 100px;">Archivo</th>
                <th class="text-center" style="width: 120px;">Acciones</th>
            </tr>
        </thead>
        <tbody id="tbodyAnexos">
            <tr>
                <td colspan="6" class="text-center py-4 text-muted">
                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div> Cargando anexos...
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Modal para Subir/Editar Anexo -->
<div class="modal fade" id="modalAnexo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formAnexo" enctype="multipart/form-data" onsubmit="guardarAnexo(event)">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalAnexoTitle">Subir Documento / Anexo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="can_ide" name="can_ide">
                    <input type="hidden" name="can_con_ide" value="<?= $convocatoriaId ?>">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tipo de Publicación <span class="text-danger">*</span></label>
                        <select class="form-select" id="can_tan_ide" name="can_tan_ide" required>
                            <option value="">Seleccione el tipo...</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Descripción / Título del Archivo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="can_nombre" name="can_nombre" placeholder="Ej. Bases del Concurso CAS 001-2026" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Seleccionar Archivo <span class="text-danger" id="reqAdjunto">*</span></label>
                        <input type="file" class="form-control" id="can_archivo" name="can_archivo" accept=".pdf,.doc,.docx,.zip,.rar">
                        <div class="form-text">Formatos permitidos: PDF, Word, ZIP. Tamaño máx. 10MB.</div>
                    </div>

                    <div id="divArchivoActual" class="alert alert-light border d-none">
                        <small class="text-muted d-block">Archivo cargado actualmente:</small>
                        <a href="#" id="linkArchivoActual" target="_blank" class="fw-semibold text-primary text-break"></a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarAnexo">
                        <i class="bi bi-upload me-1"></i> Subir Documento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const convIdAnexos = <?= $convocatoriaId ?>;
    let modalAnexoBs = null;

    $(document).ready(function() {
        modalAnexoBs = new bootstrap.Modal(document.getElementById('modalAnexo'));
        cargarTiposAnexo();
        listarAnexos();
    });

    function cargarTiposAnexo() {
        $.get('<?= base_url('seleccion/api/tipos-anexos') ?>', r => {
            let options = '<option value="">Seleccione el tipo...</option>';
            const items = r.data || r;
            if (Array.isArray(items)) {
                items.forEach(t => {
                    options += `<option value="${t.tan_ide}">${t.tan_nombre}</option>`;
                });
            }
            $('#can_tan_ide').html(options);
        });
    }

    function listarAnexos() {
        $.get('<?= base_url('seleccion/admin/anexos/listar/') ?>' + convIdAnexos, r => {
            const data = r.data || r;
            let rows = '';

            if (Array.isArray(data) && data.length > 0) {
                data.forEach((item, index) => {
                    const ext = item.can_ruta ? item.can_ruta.split('.').pop().toLowerCase() : '';
                    let iconClass = 'bi-file-earmark-text text-secondary';
                    if (ext === 'pdf') iconClass = 'bi-file-earmark-pdf-fill text-danger';
                    if (ext === 'doc' || ext === 'docx') iconClass = 'bi-file-earmark-word-fill text-primary';
                    if (ext === 'zip' || ext === 'rar') iconClass = 'bi-file-earmark-zip-fill text-warning';

                    const urlFile = '<?= base_url() ?>/' + item.can_ruta;

                    rows += `
                        <tr>
                            <td>${index + 1}</td>
                            <td><span class="badge bg-light text-dark border">${item.tan_nombre || 'General'}</span></td>
                            <td>
                                <div class="fw-bold text-dark">${$('<div>').text(item.can_nombre).html()}</div>
                            </td>
                            <td><small class="text-muted">${item.created_at || '-'}</small></td>
                            <td class="text-center">
                                <a href="${urlFile}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Descargar / Ver">
                                    <i class="bi ${iconClass} fs-6"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-light text-primary me-1" onclick='editarAnexo(${JSON.stringify(item)})' title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-light text-danger" onclick="eliminarAnexo(${item.can_ide})" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            } else {
                rows = '<tr><td colspan="6" class="text-center py-4 text-muted">No se han subido anexos o bases para esta convocatoria.</td></tr>';
            }

            $('#tbodyAnexos').html(rows);
        }).fail(() => {
            $('#tbodyAnexos').html('<tr><td colspan="6" class="text-center py-4 text-danger">Error al cargar los documentos anexos.</td></tr>');
        });
    }

    function abrirModalAnexo() {
        $('#formAnexo')[0].reset();
        $('#can_ide').val('');
        $('#reqAdjunto').removeClass('d-none');
        $('#can_archivo').prop('required', true);
        $('#divArchivoActual').addClass('d-none');
        $('#modalAnexoTitle').text('Subir Documento / Anexo');
        modalAnexoBs.show();
    }

    function editarAnexo(item) {
        $('#can_ide').val(item.can_ide);
        $('#can_tan_ide').val(item.can_tan_ide);
        $('#can_nombre').val(item.can_nombre);

        // El archivo pasa a ser opcional en edición
        $('#reqAdjunto').addClass('d-none');
        $('#can_archivo').prop('required', false);

        if (item.can_ruta) {
            $('#linkArchivoActual').attr('href', '<?= base_url() ?>/' + item.can_ruta).text(item.can_ruta.split('/').pop());
            $('#divArchivoActual').removeClass('d-none');
        } else {
            $('#divArchivoActual').addClass('d-none');
        }

        $('#modalAnexoTitle').text('Editar Registro de Anexo');
        modalAnexoBs.show();
    }

    function guardarAnexo(e) {
        e.preventDefault();
        const $btn = $('#btnGuardarAnexo').prop('disabled', true);
        const formData = new FormData(document.getElementById('formAnexo'));

        $.ajax({
            url: '<?= base_url('seleccion/admin/anexos/guardar') ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(r) {
                if (r.ok || r.status) {
                    modalAnexoBs.hide();
                    listarAnexos();
                } else {
                    alert(r.message || 'Error al subir el archivo');
                }
            },
            error: function() {
                alert('Error en la carga del servidor');
            },
            complete: function() {
                $btn.prop('disabled', false);
            }
        });
    }

    function eliminarAnexo(id) {
        if (!confirm('¿Esta seguro de eliminar este archivo adjunto?')) return;

        $.post('<?= base_url('seleccion/admin/anexos/eliminar/') ?>' + id)
            .done(r => {
                listarAnexos();
            })
            .fail(() => alert('Error al eliminar el archivo'));
    }
</script>