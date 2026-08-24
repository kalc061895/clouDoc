<div class="d-flex justify-content-between">
    <div>
        <h5 class="mb-1">Revisión y confirmación</h5>
        <p class="text-muted small">Ejecute las validaciones antes de presentar el expediente.</p>
    </div><?php if (!empty($postulacion)): ?><span
            class="badge bg-primary align-self-start"><?= esc($postulacion['pto_codigo']) ?></span><?php endif ?>
</div>
<?php if (empty($postulacion)): ?>
    <div class="alert alert-warning">Primero seleccione una plaza.</div><?php else: ?>
    <div id="lista-validaciones" class="mb-3"><?php foreach ($validaciones as $v): ?>
            <div class="d-flex gap-2 py-2 border-bottom"><span
                    class="<?= $v['vpo_resultado'] ? 'text-success' : 'text-danger' ?>"><?= $v['vpo_resultado'] ? '✓' : '✕' ?></span>
                <div><strong><?= esc($v['vpo_nombre']) ?></strong><?php if (!$v['vpo_resultado']): ?>
                        <div class="small text-muted"><?= esc($v['vpo_observacion']) ?></div><?php endif ?>
                </div>
            </div><?php endforeach ?>
    </div><?php if ($editable): ?>
        <div class="form-check mb-2"><input class="form-check-input confirm-check" type="checkbox" id="c1"><label
                class="form-check-label" for="c1">He revisado la información registrada.</label></div>
        <div class="form-check mb-2"><input class="form-check-input confirm-check" type="checkbox" id="c2"><label
                class="form-check-label" for="c2">Declaro que la información proporcionada es verdadera.</label></div>
        <div class="form-check mb-3"><input class="form-check-input confirm-check" type="checkbox" id="c3"><label
                class="form-check-label" for="c3">Acepto las condiciones de la convocatoria.</label></div><button
            class="btn btn-outline-primary me-2" id="validar-inscripcion">Actualizar validaciones</button><button
            class="btn btn-success" id="confirmar-inscripcion" disabled>Confirmar inscripción definitiva</button><?php else: ?>
        <div class="alert alert-success">Expediente presentado. El contenido ha sido bloqueado.</div>
    <?php endif ?><?php endif ?>
<script>$('#validar-inscripcion').on('click', () => recargarTabPostulacion('confirmacion')); $('.confirm-check').on('change', () => $('#confirmar-inscripcion').prop('disabled', $('.confirm-check:checked').length !== 3)); $('#confirmar-inscripcion').on('click', () => Swal.fire({ title: '¿Confirmar inscripción?', text: 'Después no podrá modificar el expediente.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Confirmar' }).then(x => { if (x.isConfirmed) $.post('<?= base_url('seleccion/postulacion/inscripcion/confirmar/' . $convocatoriaId) ?>').done(r => { r.status ? (Swal.fire('Inscripción presentada', r.message, 'success'), recargarTabPostulacion('confirmacion')) : toastr.error(r.message) }).fail(y => toastr.error(y.responseJSON?.message || 'No se pudo confirmar')); }));</script>