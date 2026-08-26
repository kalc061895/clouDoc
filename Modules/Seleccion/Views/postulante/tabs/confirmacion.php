<div class="d-flex justify-content-between">
    <div>
        <h5 class="mb-1">Revisión y confirmación</h5>
        <p class="text-muted small">Ejecute las validaciones antes de presentar el expediente.</p>
    </div>
    <?php if (!empty($postulacion)): ?>
        <span class="badge bg-primary align-self-start">
            <?= esc($postulacion['pto_codigo']) ?>
        </span>
    <?php endif ?>
</div>
<?php if (empty($postulacion)): ?>
    <div class="alert alert-warning">Primero seleccione una plaza.</div>
<?php elseif ($postulacion['pto_epo_ide'] === 8): ?>

    <div class="card border-success shadow-sm">
        <div class="card-body text-center py-5">

            <div class="mb-3">
                <div class="rounded-circle bg-success-subtle text-success d-inline-flex align-items-center justify-content-center"
                    style="width:80px;height:80px;">
                    <iconify-icon icon="solar:shield-check-bold" width="4em" height="4em"></iconify-icon>
                </div>
            </div>

            <h4 class="text-success fw-bold">
                ¡Inscripción realizada correctamente!
            </h4>

            <p class="text-muted mb-4">
                Su inscripción a la convocatoria ha sido registrada satisfactoriamente.
            </p>

            <div class="alert alert-info text-start">
                <i class="bi bi-info-circle me-1"></i>
                Puede descargar e imprimir su
                <strong>Constancia de Inscripción</strong>
                como comprobante de su registro.
            </div>

            <a href="<?= base_url('seleccion/postulacion/generar-constancia-inscripcion/' . $convocatoriaId) ?>"
                class="btn btn-success" target="_blank">

                Descargar Constancia de Inscripción
            </a>

        </div>
    </div>

<?php else: ?>
    <div id="lista-validaciones" class="mb-3">
        <?php foreach ($validaciones as $v): ?>
            <div class="d-flex gap-2 py-2 border-bottom">
                <span class="<?= $v['vpo_resultado'] ? 'text-success' : 'text-danger' ?>">
                    <?= $v['vpo_resultado'] ? '✓' : '✕' ?>
                </span>
                <div>
                    <strong>
                        <?= esc($v['vpo_nombre']) ?>
                    </strong>
                    <?php if (!$v['vpo_resultado']): ?>
                        <div class="small text-muted">
                            <?= esc($v['vpo_observacion']) ?>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        <?php endforeach ?>
    </div>
    <?php if ($editable): ?>
        <div class="form-check mb-2">
            <input class="form-check-input confirm-check" type="checkbox" id="c1">
            <label class="form-check-label" for="c1">He revisado la información registrada.</label>
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input confirm-check" type="checkbox" id="c2">
            <label class="form-check-label" for="c2">Declaro que la información proporcionada es verdadera.</label>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input confirm-check" type="checkbox" id="c3">
            <label class="form-check-label" for="c3">Acepto las condiciones de la convocatoria.</label>
        </div>
        <button class="btn btn-outline-primary me-2" id="validar-inscripcion">Actualizar validaciones</button>
        <button class="btn btn-success" id="confirmar-inscripcion" disabled>Confirmar inscripción definitiva</button>
    <?php else: ?>
        <div class="alert alert-success">Expediente presentado. El contenido ha sido bloqueado.</div>

        <div class="card border-success shadow-sm">
            <div class="card-body text-center py-5">

                <div class="mb-3">
                    <div class="rounded-circle bg-success-subtle text-success d-inline-flex align-items-center justify-content-center"
                        style="width:80px;height:80px;">
                        <iconify-icon icon="solar:shield-check-bold" width="4em" height="4em"></iconify-icon>
                    </div>
                </div>

                <h4 class="text-success fw-bold">
                    ¡Inscripción realizada correctamente!
                </h4>

                <p class="text-muted mb-4">
                    Su inscripción a la convocatoria ha sido registrada satisfactoriamente.
                </p>

                <div class="alert alert-info text-start">
                    <i class="bi bi-info-circle me-1"></i>
                    Puede descargar e imprimir su
                    <strong>Constancia de Inscripción</strong>
                    como comprobante de su registro.
                </div>

                <a href="<?= base_url('seleccion/postulacion/generar-constancia-inscripcion/' . $convocatoriaId) ?>"
                    class="btn btn-success" target="_blank">
                    <iconify-icon icon="solar:file-download-linear" width="1em" height="1em"></iconify-icon>
                    Descargar Constancia de Inscripción
                </a>

            </div>
        </div>
    <?php endif ?>
<?php endif ?>
<script>
    $('#validar-inscripcion').on('click', () => recargarTabPostulacion('confirmacion'));
    $('.confirm-check').on('change', () => $('#confirmar-inscripcion').prop('disabled', $('.confirm-check:checked').length !== 3));
    $('#confirmar-inscripcion').on('click', () => Swal.fire({
        title: '¿Confirmar inscripción?',
        text: 'Después no podrá modificar el expediente.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Confirmar'
    }).then(x => {
        if (x.isConfirmed) $.post('<?= base_url('seleccion/postulacion/inscripcion/confirmar/' . $convocatoriaId) ?>').done(r => {
            r.status ? (Swal.fire('Inscripción presentada', r.message, 'success'), recargarTabPostulacion('confirmacion')) : toastr.error(r.message)
        }).fail(y => toastr.error(y.responseJSON?.message || 'No se pudo confirmar'));
    }));
</script>