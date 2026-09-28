<div class="mb-3">
    <h5 class="mb-1 fw-bold">Seleccione la plaza a la que postula</h5>
    <p class="text-muted small mb-0">Solo podrá confirmar una plaza por convocatoria.</p>
</div>

<?php if (empty($plazas)): ?>
    <div class="alert alert-warning">No hay plazas configuradas para esta convocatoria.</div>
<?php else: ?>
    <?php
    $idPlazaActual = (int) ($postulacion['pto_cco_ide'] ?? 0);
    $plazaSeleccionada = null;
    ?>

    <div class="row g-3">
        <div class="col-12 col-md-8">
            <label for="selectPlaza" class="form-label small text-muted fw-semibold">Plazas disponibles</label>
            <select id="selectPlaza" class="form-select" <?= !$editable ? 'disabled' : '' ?>>
                <option value="">-- Seleccione una plaza --</option>
                <?php foreach ($plazas as $plaza):
                    $esEsta = (int) $plaza['cco_ide'] === $idPlazaActual;
                    if ($esEsta) {
                        $plazaSeleccionada = $plaza;
                    }
                    ?>
                    <option value="<?= $plaza['cco_ide'] ?>" <?= $esEsta ? 'selected' : '' ?>
                        data-plaza='<?= esc(json_encode($plaza), 'attr') ?>'>
                        <?= esc($plaza['car_denominacion']) ?> (<?= esc($plaza['car_codigo']) ?>) - [Vacantes:
                        <?= esc($plaza['cco_numero_plazas']) ?>]
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-12 col-md-4 d-flex align-items-end">
            <button id="btnGuardarPlaza" class="btn btn-primary w-100" data-id="<?= $idPlazaActual ?>" <?= (!$editable || !$idPlazaActual) ? 'disabled' : '' ?>>
                <iconify-icon icon="solar:check-read-bold" class="me-1"></iconify-icon>
                <?= $idPlazaActual ? 'Plaza seleccionada' : 'Confirmar plaza' ?>
            </button>
        </div>
    </div>

    <!-- Contenedor con detalles de la plaza seleccionada -->
    <div id="detallePlazaContenedor" class="mt-3 <?= $plazaSeleccionada ? '' : 'd-none' ?>">
        <div class="card bg-light border">
            <div class="card-body p-3">
                <h6 class="fw-bold text-primary mb-1" id="detCargo">
                    <?= esc($plazaSeleccionada['car_denominacion'] ?? '') ?>
                </h6>
                <div class="small text-muted mb-2" id="detCodigoGrupo">
                    <?= esc($plazaSeleccionada['car_codigo'] ?? '') ?> ·
                    <?= esc($plazaSeleccionada['grupo_ocupacional'] ?? '') ?>
                </div>

                <hr class="my-2">

                <div class="row g-2 small">
                    <div class="col-sm-6 col-md-4">
                        <span class="text-muted d-block">Dependencia:</span>
                        <strong id="detDependencia"><?= esc($plazaSeleccionada['cco_dependencia'] ?? '-') ?></strong>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <span class="text-muted d-block">Área:</span>
                        <strong id="detArea"><?= esc($plazaSeleccionada['cco_area'] ?? '-') ?></strong>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <span class="text-muted d-block">Establecimiento:</span>
                        <strong
                            id="detEstablecimiento"><?= esc($plazaSeleccionada['cco_establecimiento'] ?? '-') ?></strong>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <span class="text-muted d-block">Remuneración:</span>
                        <strong class="text-success" id="detRemuneracion">S/
                            <?= esc($plazaSeleccionada['cco_remuneracion'] ?? '-') ?></strong>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <span class="text-muted d-block">Vacantes:</span>
                        <strong id="detVacantes"><?= esc($plazaSeleccionada['cco_numero_plazas'] ?? '-') ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    (function () {
        const $select = $('#selectPlaza');
        const $btn = $('#btnGuardarPlaza');
        const $detalle = $('#detallePlazaContenedor');
        const esEditable = <?= json_encode((bool) $editable) ?>;

        // Instancia reutilizable de Toast SweetAlert2
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        // Al cambiar la opción del desplegable
        $select.on('change', function () {
            const $opcion = $(this).find(':selected');
            const id = $(this).val();

            if (!id) {
                $detalle.addClass('d-none');
                $btn.prop('disabled', true).data('id', '').html('<iconify-icon icon="solar:check-read-bold" class="me-1"></iconify-icon> Confirmar plaza');
                return;
            }

            // Obtener el objeto plaza almacenado en el data attribute
            const plaza = $opcion.data('plaza');

            if (plaza) {
                // Renderizar los detalles en el panel inferior
                $('#detCargo').text(plaza.car_denominacion || '-');
                $('#detCodigoGrupo').text((plaza.car_codigo || '') + ' · ' + (plaza.grupo_ocupacional || ''));
                $('#detDependencia').text(plaza.cco_dependencia || '-');
                $('#detArea').text(plaza.cco_area || '-');
                $('#detEstablecimiento').text(plaza.cco_establecimiento || '-');
                $('#detRemuneracion').text('S/ ' + (plaza.cco_remuneracion || '-'));
                $('#detVacantes').text(plaza.cco_numero_plazas || '-');

                $detalle.removeClass('d-none');
            }

            // Si la plaza seleccionada en el combo es la misma que ya está guardada
            const idActualGuardado = <?= json_encode($idPlazaActual) ?>;
            if (parseInt(id) === parseInt(idActualGuardado)) {
                $btn.html('<iconify-icon icon="solar:check-read-bold" class="me-1"></iconify-icon> Plaza seleccionada');
            } else {
                $btn.html('<iconify-icon icon="solar:check-read-bold" class="me-1"></iconify-icon> Confirmar plaza');
            }

            // Habilitar botón si la postulación es editable
            if (esEditable) {
                $btn.prop('disabled', false).data('id', id);
            }
        });

        // Evento click al confirmar / guardar con SweetAlert2
        $btn.on('click', function () {
            const plazaId = $(this).data('id');
            if (!plazaId) return;

            const nombreCargo = $('#detCargo').text();

            // Modal de confirmación SweetAlert2
            Swal.fire({
                title: '¿Confirmar selección de plaza?',
                text: `Postularás a: ${nombreCargo}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, seleccionar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const $b = $(this);
                    $b.prop('disabled', true);

                    $.post('<?= base_url('seleccion/postulacion/inscripcion/plaza/' . $convocatoriaId) ?>', { cco_ide: plazaId })
                        .done(r => {
                            if (r.status) {
                                Toast.fire({
                                    icon: 'success',
                                    title: r.message || 'Plaza seleccionada correctamente'
                                });
                                recargarTabPostulacion('plaza');
                                if (r.data && r.data.pto_codigo) {
                                    $('#resumen-postulante').text('Postulación ' + r.data.pto_codigo);
                                }
                            } else {
                                Swal.fire('Atención', r.message || 'No se pudo procesar el registro', 'warning');
                                $b.prop('disabled', false);
                            }
                        })
                        .fail(x => {
                            const msg = x.responseJSON?.message || 'No se pudo seleccionar la plaza';
                            Swal.fire('Error', msg, 'error');
                            $b.prop('disabled', false);
                        });
                }
            });
        });
    })();
</script>