<?php
$estado = [
    'ESPERA' => 'warning',
    'RECIBIDO' => 'success',
    'DERIVADO' => 'primary',
    'EN PROCESO' => 'primary',
    'FINALIZADO' => 'success',
    'ATENDIDO' => 'primary',
    'OBSERVADO' => 'danger',
    'RECHAZADO' => 'danger',

];

?>
<div class="row">
    <div class="col-md-4">

        <!-- Información del Remitente    -->
        <ul class="list-group list-group-flush mb-2">
            <li class="list-group-item">
                <i class="ti ti-inbox fs-4 me-2"></i>
                <strong><?= lang('Main.asunto') ?></strong>: <br>
                <?= $expediente[0]->asunto ?>
            </li>
            <li class="list-group-item">
                <i class="ti ti-star fs-4 me-2"></i>
                <strong><?= lang('Main.remitente') ?></strong>: <br>
                <?= $expediente[0]->remitente ?>
            </li>
            <li class="list-group-item">
                <i class="ti ti-send fs-4 me-2"></i>
                <strong><?= lang('Main.TipoExpediente') . ' / ' . lang('Main.folios') ?></strong>: <br>
                <?= $expediente[0]->tipoexpediente . " / " . $expediente[0]->folios ?>
            </li>
        </ul>

        <div class="collapsible-section mb-4">
            <div class="accordion accordion-flush position-relative overflow-hidden" id="accordionFlushParent">
                <div class="accordion-item mb-3 shadow-none border rounded-top">
                    <h2 class="accordion-header" id="flush-headingOne">
                        <button class="accordion-button  fs-4 fw-semibold px-3 py-6 lh-base border-0 rounded-top bg-primary-subtle " type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                            <?= lang('Main.derivar') ?>
                        </button>
                    </h2>
                    <div id="flush-collapseOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushParent">
                        <div class="accordion-body px-3 fw-normal">

                            <form class="floating-labels mt-4 pt-2" action="<?= base_url('/mesa_de_partes/derivar') ?>" method="POST" id="formDerivar">
                                <input type="text" name="idDerivar" value="<?= $expediente[0]->id ?>" hidden>
                                <div class="form-group mb-4">
                                    <select class="form-control form-select" id="oficinaDerivar" name="oficinaDerivar" required>
                                        <option></option>
                                        <?php foreach ($oficina as $item) : ?>
                                            <?php
                                            // Contar cuántos hijos tiene esta oficina
                                            $children = array_filter($oficina, function ($ofi) use ($item) {
                                                return $ofi['oficina_padre_id'] == $item['id'];
                                            });
                                            ?>
                                            <?php if (count($children) > 0) : ?>
                                                <optgroup label="<?= $item['nombre'] ?>">
                                                    <?php foreach ($children as $ofi) : ?>
                                                        <option value="<?= $ofi['id'] ?>"><?= $ofi['nombre'] ?></option>
                                                    <?php endforeach ?>
                                                </optgroup>
                                            <?php endif ?>
                                        <?php endforeach ?>

                                    </select>
                                    <span class="bar"></span>
                                    <label for="oficinaDerivar"><?= lang('Main.derivarOficina') ?></label>
                                </div>

                                <div class="form-group mb-4">
                                    <select class="form-control form-select" id="accionDerivar" name="accionDerivar" required>
                                        <option></option>
                                        <?php foreach ($accion as $item) : ?>
                                            <option value="<?= $item['id'] ?>"><?= $item['nombre'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <span class="bar"></span>
                                    <label for="accionDerivar"><?= lang('Main.accion') ?></label>
                                </div>
                                <div class="form-group mb-4">
                                    <textarea class="form-control" rows="4" id="observacionDerivar" name="observacionDerivar"></textarea>
                                    <span class="bar"></span>
                                    <label for="observacionDerivar"><?= lang('Main.observacion') ?></label>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="adjuntoDerivar" class="form-label">Adjunto</label>
                                    <input class="form-control form-sm" type="file" id="adjuntoDerivar" name="adjuntoDerivar[]" multiple>
                                </div>
                                <div class="form-group mb-4">

                                    <button type="submit" class="justify-content-center w-100 btn mb-1 btn-rounded bg-primary-subtle text-primary  d-flex align-items-center">
                                        <i class="ti ti-send fs-4 me-2"></i>
                                        <?= lang('Main.derivar') ?>
                                    </button>

                                </div>

                            </form>

                        </div>
                    </div>
                </div>
                <div class="accordion-item mb-3 shadow-none border rounded-top">
                    <h2 class="accordion-header" id="flush-headingTwo">
                        <button class="accordion-button collapsed fs-4 fw-semibold px-3 py-6 lh-base border-0 rounded-top bg-success-subtle " type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                            <?= lang('Main.atender') ?>
                        </button>
                    </h2>
                    <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushParent">
                        <div class="accordion-body px-3 fw-normal">

                            <form class="floating-labels mt-4 pt-2" action="<?= base_url('/mesa_de_partes/atender') ?>" method="POST" id="formAtender">
                                <input type="text" name="idAtender" value="<?= $expediente[0]->id ?>" hidden>

                                <div class="form-group mb-4">
                                    <select class="form-control form-select" id="accionAtender" name="accionAtender" required>
                                        <option></option>
                                        <?php foreach ($accion as $item) : ?>
                                            <option value="<?= $item['id'] ?>"><?= $item['nombre'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <span class="bar"></span>
                                    <label for="accionAtender"><?= lang('Main.accion') ?></label>
                                </div>
                                <div class="form-group mb-4">
                                    <textarea class="form-control" rows="4" id="observacionAtender" name="observacionAtender"></textarea>
                                    <span class="bar"></span>
                                    <label for="observacionAtender"><?= lang('Main.observacion') ?></label>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="adjuntoAtender" class="form-label">Adjunto</label>
                                    <input class="form-control form-sm" type="file" id="adjuntoAtender" name="adjuntoAtender[]" multiple>
                                </div>
                                <div class="form-group mb-4">

                                    <button type="submit" class="justify-content-center w-100 btn mb-1 btn-rounded bg-primary-subtle text-primary  d-flex align-items-center">
                                        <i class="ti ti-send fs-4 me-2"></i>
                                        <?= lang('Main.atender') ?>
                                    </button>

                                </div>

                            </form>

                        </div>
                    </div>
                </div>
                <div class="accordion-item mb-3 shadow-none border rounded-top">
                    <h2 class="accordion-header" id="flush-headingThree">
                        <button class="accordion-button collapsed fs-4 fw-semibold px-3 py-6 lh-base border-0  bg-danger-subtle" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                            <?= lang('Main.observar') ?>
                        </button>
                    </h2>
                    <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushParent">
                        <div class="accordion-body px-3 fw-normal">
                            <form class="floating-labels mt-4 pt-2" action="<?= base_url('/mesa_de_partes/observar') ?>">

                                <div class="form-group mb-4">
                                    <textarea class="form-control" rows="4" id="observacionObservar" required></textarea>
                                    <span class="bar"></span>
                                    <label for="observacionObservar"><?= lang('Main.observacion') ?></label>
                                </div>
                                <div class="form-group mb-4">
                                    <button type="submit" class="justify-content-center w-100 btn mb-1 btn-rounded bg-danger-subtle text-danger  d-flex align-items-center">
                                        <i class="ti ti-send fs-4 me-2"></i>
                                        <?= lang('Main.observar') ?>
                                    </button>


                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">

        <div class="accordion accordion-flush" id="accordionFlushMovimiento">
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMovimientoOne" aria-expanded="false" aria-controls="collapseMovimientoOne">
                        <strong><?= lang('Main.movimientosTitle') ?>
                        </strong>
                    </button>
                </h2>
                <div id="collapseMovimientoOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushMovimiento">
                    <div class="comment-widgets position-relative mb-2 " data-simplebar>
                        <?php if ($movimiento) : ?>
                            <?php foreach ($movimiento as $item) : ?>
                                <div class="d-flex gap-9 comment-row mb-3">
                                    <span class="flex-shrink-0">
                                        <img src="assets/images/profile/user-<?= $item->oficina_procedencia_id ?>.jpg" alt="user" width="56" height="56" class="rounded-circle" />
                                    </span>
                                    <div class="comment-text w-100">
                                        <div class="hstack justify-content-between gap-6 mb-0">
                                            <div class="hstack gap-6">
                                                <h5 class="mb-0"><?= $item->oficina_procedencia ?></h5>
                                                <p class="mb-0 fs-3 text-muted"><?= $item->fecha_envio ?></p>
                                            </div>
                                            <span class="badge bg-<?= $estado[$item->estado] ?>-subtle text-<?= $estado[$item->estado] ?> rounded-pill"> <?= $item->estado ?> </span>
                                        </div>
                                        <p class="comment-details mb-2">
                                            <?= $item->observacion ?>
                                        </p>
                                        <span class="text-muted mb-2">
                                            Se Derivo a: <?= $item->oficina_destino ?>
                                        </span>
                                        <ul class="list-unstyled mb-0 hstack gap-3 lh-1">
                                            <?php if ($adjunto) : ?>
                                                <?php foreach ($adjunto as $adj) : ?>
                                                    <?php if ($adj->movimiento_id == $item->id) : ?>
                                                        <!-- TRUE -->
                                                        <li>
                                                            <a class="text-primary link-primary" href="<?= $adj->local_path ?>" target="_blank">
                                                                <iconify-icon icon="solar:gallery-edit-line-duotone" class=""></iconify-icon> <?= lang('External.adjunto') ?> <?= $adj->orden ?>
                                                            </a>
                                                        </li>
                                                    <?php endif ?>
                                                <?php endforeach ?>
                                            <?php endif ?>

                                        </ul>

                                    </div>
                                </div>
                            <?php endforeach ?>
                        <?php else : ?>
                            <div class="d-flex gap-9 comment-row mb-3">
                                <span class="flex-shrink-0">
                                    <img src="assets/images/profile/user-24.jpg" alt="user" width="56" height="56" class="rounded-circle" />
                                </span>
                                <div class="comment-text">
                                    <div class="hstack justify-content-between gap-6 mb-2">
                                        <div class="hstack gap-6">
                                            <h5 class="mb-0">Oficina de Tramite Documentario</h5>
                                            <p class="mb-0 fs-3 text-muted"> <?= $expediente[0]->recibido ?> </p>
                                        </div>
                                        <span class="badge bg-danger-subtle text-danger rounded-pill">EN ESPERA</span>
                                    </div>
                                    <p class="comment-details mb-2">
                                        Su expediente aun no ha sido revisado para su derivación.
                                    </p>
                                </div>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMovimientoTwo" aria-expanded="true" aria-controls="collapseMovimientoTwo">
                        <strong><?= lang('Main.expedientePreview') ?></strong>
                    </button>
                </h2>
                <div id="collapseMovimientoTwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushMovimiento">

                    <div class="button-group">
                        <a href="<?= base_url('/'.$expediente[0]->local_path) ?>" class="btn btn-sm btn-primary" target="_blank">
                            Abrir en una nueva Pestaña
                        </a>
                    </div>
                    <iframe src="<?= $expediente[0]->local_path ?>" class="w-100 " frameborder="0" style="height: 720px;"></iframe>

                </div>
            </div>
        </div>
    </div>

</div>

<script>
    $('.floating-labels .form-control').on('focus blur', function(e) {
        $(this).parents('.form-group').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));
    }).trigger('blur');

    // funcion para derivar
    $('#formDerivar').on('submit', function(event) {
        event.preventDefault(); // Prevenir la acción predeterminada del formulario

        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: formData,
            processData: false, // Evitar que jQuery procese los datos
            contentType: false, // Evitar que jQuery establezca el tipo de contenido
            success: function(response) {
                // Manejar la respuesta del servidor aquí
                //response = JSON.parse(response);
                $('#detalleExpediente').modal('hide');

                mensaje_derivar(response.idexpediente);
                Swal.fire(
                    "<?= lang('Main.confirmarDerivacion') ?>",
                    '',
                    "success"
                );
                console.log(response);
                // Puedes mostrar un mensaje de éxito o redirigir a otra página, etc.
            },
            error: function(xhr, status, error) {
                // Manejar errores aquí
                console.error(error);
                // Puedes mostrar un mensaje de error, etc.
            }
        });
    });

    // funcion para ATENDER
    $('#formAtender').on('submit', function(event) {
        event.preventDefault(); // Prevenir la acción predeterminada del formulario

        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: formData,
            processData: false, // Evitar que jQuery procese los datos
            contentType: false, // Evitar que jQuery establezca el tipo de contenido
            success: function(response) {
                // Manejar la respuesta del servidor aquí
                // response = JSON.parse(response);
                $('#detalleExpediente').modal('hide');

                mensaje_derivar(response.idexpediente);
                Swal.fire(
                    "<?= lang('Main.confirmarAtencion') ?>",
                    '',
                    "success"
                );
                console.log(response);
                // Puedes mostrar un mensaje de éxito o redirigir a otra página, etc.
            },
            error: function(xhr, status, error) {
                // Manejar errores aquí
                console.error(error);
                // Puedes mostrar un mensaje de error, etc.
            }
        });
    });
</script>