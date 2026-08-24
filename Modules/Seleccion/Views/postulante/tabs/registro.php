<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-1 fw-bold"><?= esc($titulo) ?></h5>
        <small class="text-muted">Puede registrar varios antecedentes. Formatos admitidos: PDF, JPG, PNG (máx. 10 MB).</small>
    </div>
    <?php if ($editable): ?>
        <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modal-<?= esc($tipo) ?>">
            <iconify-icon icon="solar:add-circle-bold" class="fs-5"></iconify-icon>
            Agregar Registro
        </button>
    <?php endif; ?>
</div>

<!-- TABLA DE REGISTROS -->
<div class="table-responsive">
    <table class="table table-hover table-sm align-middle">
        <thead class="table-light">
            <tr>
                <?php foreach ($campos as [, $l]): ?>
                    <th><?= esc($l) ?></th>
                <?php endforeach; ?>
                <th class="text-center">Documento</th>
                <th class="text-end">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registros as $r): ?>
                <?php 
                    $campoDoc = str_replace('_ide', '_documento_ide', $pk);
                    $campoPath = str_replace('_ide', '_ruta', $pk); // O el campo donde guardes la ruta/path del archivo
                    $hasDocument = !empty($r[$campoDoc]) || !empty($r['path_documento'] ?? $r[$campoPath] ?? null);
                    $filePath = $r['path_documento'] ?? $r[$campoPath] ?? ($r[$campoDoc] ?? '');
                ?>
                <tr>
                    <?php foreach ($campos as [$n]): ?>
                        <td><?= esc($r[$n] ?? '-') ?></td>
                    <?php endforeach; ?>

                    <!-- Columna Documento con enlace Base64 -->
                    <td class="text-center">
                        <?php if ($hasDocument && !empty($filePath)): ?>
                            <?php $pathBase64 = urlencode(base64_encode($filePath)); ?>
                            <a href="<?= base_url('ver/documento?path=' . $pathBase64) ?>" 
                               target="_blank" 
                               class="btn btn-outline-info btn-xs d-inline-flex align-items-center gap-1"
                               title="Ver Documento">
                                <iconify-icon icon="solar:file-text-bold"></iconify-icon>
                                Ver 
                            </a>
                        <?php else: ?>
                            <span class="badge bg-light text-secondary border">Sin adjunto</span>
                        <?php endif; ?>
                    </td>

                    <!-- Acciones -->
                    <td class="text-end">
                        <?php if ($editable): ?>
                            <button type="button" 
                                    class="btn btn-outline-danger btn-sm btn-eliminar d-inline-flex align-items-center gap-1" 
                                    data-id="<?= esc($r[$pk]) ?>">
                                <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon>
                                Eliminar
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($registros)): ?>
                <tr>
                    <td colspan="99" class="text-center text-muted py-4">
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
<div class="modal fade" id="modal-<?= esc($tipo) ?>" tabindex="-1" aria-labelledby="modalLabel-<?= esc($tipo) ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalLabel-<?= esc($tipo) ?>">Agregar <?= esc($titulo) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-<?= esc($tipo) ?>" class="form-registro" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="<?= esc($pk) ?>">
                    <div class="row g-3">
                        <?php foreach ($campos as [$n, $l, $t, $r]): ?>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold"><?= esc($l) ?><?= $r ? ' <span class="text-danger">*</span>' : '' ?></label>
                                <input class="form-control" 
                                       type="<?= esc($t) ?>" 
                                       name="<?= esc($n) ?>" 
                                       <?= $r ? 'required' : '' ?>
                                       <?= $n === 'pca_horas' ? 'min="0.01" step="0.01"' : '' ?>>
                            </div>
                        <?php endforeach; ?>
                        
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Documento Sustentatorio</label>
                            <input class="form-control" name="documento" type="file" accept=".pdf,.jpg,.jpeg,.png">
                            <div class="form-text small">Max. 10 MB (.pdf, .jpg, .png)</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success d-flex align-items-center gap-1" id="btnGuardar-<?= esc($tipo) ?>">
                        <iconify-icon icon="solar:diskette-bold"></iconify-icon>
                        Guardar Información
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- SCRIPT DE INTERACCIÓN -->
<script>
(function() {
    // Configuración Toast SweetAlert2
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });

    // Guardar / Registrar vía AJAX
    $('#form-<?= esc($tipo) ?>').on('submit', function (e) {
        e.preventDefault();
        const form = this;
        const $btn = $('#btnGuardar-<?= esc($tipo) ?>');
        const originalBtnHtml = $btn.html();

        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Guardando...');

        $.ajax({
            url: '<?= base_url('seleccion/postulacion/inscripcion/registro/' . $convocatoriaId . '/' . $tipo) ?>',
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

                // Cerrar modal y limpiar formulario
                const modalEl = document.getElementById('modal-<?= esc($tipo) ?>');
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
                form.reset();

                recargarTabPostulacion('<?= $tipo ?>');
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
    $('.btn-eliminar').on('click', function () {
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
                    url: '<?= base_url('seleccion/postulacion/inscripcion/registro/' . $convocatoriaId . '/' . $tipo) ?>/' + id,
                    type: 'DELETE'
                })
                .done(r => {
                    if (r.status) {
                        Toast.fire({
                            icon: 'success',
                            title: r.message || 'Registro eliminado correctamente'
                        });
                        recargarTabPostulacion('<?= $tipo ?>');
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