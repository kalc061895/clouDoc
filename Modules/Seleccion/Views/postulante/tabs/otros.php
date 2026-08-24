<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-1 fw-bold">Otros documentos / Declaraciones</h5>
        <small class="text-muted">Puede adjuntar otros documentos o sustentos requeridos. Formatos admitidos: PDF, JPG, PNG (máx. 10 MB).</small>
    </div>
    <?php if ($editable): ?>
        <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modal-otros">
            <iconify-icon icon="solar:add-circle-bold" class="fs-5"></iconify-icon>
            Agregar Documento
        </button>
    <?php endif; ?>
</div>

<!-- TABLA DE REGISTROS -->
<div class="table-responsive">
    <table class="table table-hover table-sm align-middle">
        <thead class="table-light">
            <tr>
                <th>Tipo / Nivel</th>
                <th>Descripción / Titulo</th>
                <th class="text-center">Documento</th>
                <th class="text-end">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registros as $r): ?>
                <?php
                // Obtención y codificación de la ruta del documento
                $rutaArchivo = $r['otr_ruta'] ?? $r['exd_ruta'] ?? $r['path_documento'] ?? null;
                $rutaBase64 = !empty($rutaArchivo) ? urlencode(base64_encode($rutaArchivo)) : '';
                ?>
                <tr>
                    <td><?= esc($r['otr_tipo'] ?? '-') ?></td>
                    <td><?= esc($r['otr_descripcion'] ?? '-') ?></td>

                    <!-- Columna Documento Sustentatorio -->
                    <td class="text-center">
                        <?php if (!empty($rutaBase64)): ?>
                            <a href="<?= base_url('seleccion/ver/documento?path=' . $rutaBase64) ?>" target="_blank"
                                class="btn btn-outline-info btn-xs d-inline-flex align-items-center gap-1"
                                title="<?= esc($r['exd_nombre_original'] ?? 'Ver Documento') ?>">
                                <iconify-icon icon="solar:file-text-bold"></iconify-icon>
                                Ver
                            </a>
                        <?php else: ?>
                            <span class="badge bg-light text-secondary border">Sin adjunto</span>
                        <?php endif; ?>
                    </td>

                    <!-- Columna Acciones -->
                    <td class="text-end">
                        <?php if ($editable): ?>
                            <button type="button"
                                class="btn btn-outline-danger btn-sm btn-eliminar-otros d-inline-flex align-items-center gap-1"
                                data-id="<?= esc($r['otr_ide']) ?>">
                                <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon>
                                Eliminar
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($registros)): ?>
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        <iconify-icon icon="solar:inbox-line" class="fs-1 d-block mb-1 text-secondary opacity-50"></iconify-icon>
                        Aún no ha registrado información en este apartado.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- MODAL DE REGISTRO -->
<?php if ($editable): ?>
    <div class="modal fade" id="modal-otros" tabindex="-1" aria-labelledby="modalLabel-otros" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalLabel-otros">Agregar Otro Documento / Sustento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-otros" class="form-registro" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="otr_ide">

                        <div class="row g-3">
                            <!-- Tipo / Nivel (Select) -->
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Tipo / Categoria <span class="text-danger">*</span></label>
                                <select class="form-select" name="otr_tipo" required>
                                    <option value="">-- Seleccione Opción --</option>
                                    <option value="SERUMS">SERUMS</option>
                                    <option value="COLEGIATURA">COLEGIATURA</option>
                                    <option value="CONADIS">CONADIS</option>
                                    <option value="FF.AA.">FF.AA.</option>
                                    <option value="LICENCIA">LICENCIA</option>
                                    <option value="RESOLUCION">RESOLUCION</option>
                                    <option value="OTROS">OTROS</option>

                                </select>
                            </div>

                            <!-- Descripción / Título -->
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Descripción / Nombre del documento <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="otr_descripcion" placeholder="Ej. Colegiatura, Habilidad Profesional, Bonificación..." required>
                            </div>

                            <!-- Documento Sustentatorio -->
                            <div class="col-md-12">
                                <label class="form-label small fw-semibold">Documento Sustentatorio <span class="text-danger">*</span></label>
                                <input class="form-control" name="documento" type="file" accept=".pdf" required>
                                <div class="form-text small">Max. 10 MB (.pdf)</div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success d-flex align-items-center gap-1" id="btnGuardar-otros">
                            <iconify-icon icon="solar:diskette-bold"></iconify-icon>
                            Guardar Información
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- SCRIPT DE INTERACCIÓN AJAX -->
<script>
    (function() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        // Guardar / Registrar vía AJAX
        $('#form-otros').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            const $btn = $('#btnGuardar-otros');
            const originalBtnHtml = $btn.html();

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Guardando...');

            $.ajax({
                    url: '<?= base_url('seleccion/postulacion/inscripcion/registro/' . $convocatoriaId . '/otros') ?>',
                    type: 'POST',
                    data: new FormData(form),
                    processData: false,
                    contentType: false
                })
                .done(r => {
                    if (r.status) {
                        Toast.fire({
                            icon: 'success',
                            title: r.message || 'Registro guardado con éxito'
                        });

                        const modalEl = document.getElementById('modal-otros');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();
                        form.reset();

                        if (typeof recargarTabPostulacion === 'function') {
                            recargarTabPostulacion('otros');
                        } else {
                            location.reload();
                        }
                    } else {
                        Swal.fire('Atención', r.message || 'Ocurrió un inconveniente al guardar.', 'warning');
                    }
                })
                .fail(x => {
                    const msg = x.responseJSON?.message || 'Ocurrió un error al procesar el envío.';
                    Swal.fire('Error', msg, 'error');
                })
                .always(() => {
                    $btn.prop('disabled', false).html(originalBtnHtml);
                });
        });

        // Eliminar registro
        $('.btn-eliminar-otros').on('click', function() {
            const id = $(this).data('id');

            Swal.fire({
                title: '¿Eliminar registro?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                            url: '<?= base_url('seleccion/postulacion/inscripcion/registro/' . $convocatoriaId . '/otros') ?>/' + id,
                            type: 'DELETE'
                        })
                        .done(r => {
                            if (r.status) {
                                Toast.fire({
                                    icon: 'success',
                                    title: r.message || 'Registro eliminado correctamente'
                                });
                                if (typeof recargarTabPostulacion === 'function') {
                                    recargarTabPostulacion('otros');
                                } else {
                                    location.reload();
                                }
                            } else {
                                Swal.fire('Atención', r.message || 'No se pudo eliminar el registro', 'warning');
                            }
                        })
                        .fail(x => {
                            const msg = x.responseJSON?.message || 'Error al intentar eliminar el registro';
                            Swal.fire('Error', msg, 'error');
                        });
                }
            });
        });
    })();
</script>