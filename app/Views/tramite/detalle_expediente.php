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
        <?php if (!$observacion) : ?>
            <div class="collapsible-section mb-2">
                <div class="accordion accordion-flush position-relative overflow-hidden" id="accordionFlushParent">
                    <div class="accordion-item mb-3 shadow-none border rounded-top">
                        <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button  fs-4 fw-semibold px-3 py-6 lh-base border-0 rounded-top bg-primary-subtle " type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                <?= lang('Main.derivar') ?>
                            </button>
                        </h2>
                        <div id="flush-collapseOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushParent">
                            <div class="accordion-body px-3 fw-normal">


                                <form class=" mt-4 pt-2" action="<?= base_url('/mesa_de_partes/derivar') ?>" method="POST" id="formDerivar">

                                    <input type="text" name="idDerivar" value="<?= $expediente[0]->id ?>" hidden>

                                    <div class="form-group mb-2">

                                        <label for="oficinaDerivar"><?= lang('Main.derivarOficina') ?></label>

                                        <select class="select2 form-control form-control-sm " id="oficinaDerivar" name="oficinaDerivar" required>
                                            
                                            <option value="6">LOGISTICA</option>
                                            
                                            <?php foreach ($oficina as $item) : ?>
                                                <option value="<?= $item['id'] ?>"><?= $item['nombre'] ?></option>            
                                            <?php endforeach ?>

                                        </select>
                                    </div>

                                    <div class="form-group mb-2">
                                        <label for="accionDerivar"><?= lang('Main.accion') ?></label>
                                        <select class="select2 form-control form-control-sm" id="accionDerivar" name="accionDerivar" required>
                                            <option value="2">ATENCION</option>
                                            <?php foreach ($accion as $item) : ?>
                                                <option value="<?= $item['id'] ?>"><?= $item['nombre'] ?></option>
                                            <?php endforeach ?>
                                        </select>

                                    </div>

                                    <div class="form-group mb-2">
                                        <label for="observacionDerivar"><?= lang('Main.observacion') ?></label>
                                        <textarea class="form-control form-control-sm" rows="4" id="observacionDerivar" name="observacionDerivar"></textarea>
                                    </div>

                                    <div class="form-group mb-2">
                                        <label for="adjuntoDerivar" class="form-label">Adjunto</label>
                                        <input class="form-control " type="file" id="adjuntoDerivar" name="adjuntoDerivar[]" multiple>
                                    </div>
                                    <div class="form-group mb-2">

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

                                <form class=" mt-4 pt-2" action="<?= base_url('/mesa_de_partes/atender') ?>" method="POST" id="formAtender">
                                    <input type="text" name="idAtender" value="<?= $expediente[0]->id ?>" hidden>

                                    <div class="form-group mb-2">
                                        <label for="accionAtender"><?= lang('Main.accion') ?></label>
                                        <select class="form-control form-control-sm select2" id="accionAtender" name="accionAtender" required>
                                            <?php foreach ($accion as $item) : ?>
                                                <option value="<?= $item['id'] ?>"><?= $item['nombre'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="observacionAtender"><?= lang('Main.observacion') ?></label>
                                        <textarea class="form-control form-control-sm" rows="4" id="observacionAtender" name="observacionAtender"></textarea>

                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="adjuntoAtender" class="form-label">Adjunto</label>
                                        <input class="form-control form-control-sm form-sm" type="file" id="adjuntoAtender" name="adjuntoAtender[]" multiple>
                                    </div>
                                    <div class="form-group mb-2">

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

                                <form class=" mt-4 pt-2" action="<?= base_url('/mesa_de_partes/observar') ?>" method="POST" id="formObservar">
                                    <input type="text" name="idObservar" value="<?= $expediente[0]->id ?>" hidden>

                                    <div class="form-group mb-2">
                                        <label for="accionObservar"><?= lang('Main.accion') ?></label>
                                        <select class="form-control form-control-sm select2" id="accionObservar" name="accionObservar" required>
                                            <?php foreach ($accion as $item) : ?>
                                                <option value="<?= $item['id'] ?>"><?= $item['nombre'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="observacionObservar"><?= lang('Main.observacion') ?></label>
                                        <textarea class="form-control form-control-sm" rows="4" id="observacionObservar" name="observacionObservar"></textarea>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="adjuntoObservar" class="form-label">Adjunto</label>
                                        <input class="form-control form-control-sm form-sm" type="file" id="adjuntoObservar" name="adjuntoObservar[]" multiple>
                                    </div>
                                    <div class="form-group mb-2">

                                        <button type="submit" class="justify-content-center w-100 btn mb-1 btn-rounded bg-primary-subtle text-primary  d-flex align-items-center">
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
        <?php endif ?>
    </div>

    <div class="col-md-8">

        <div class="accordion accordion-flush" id="accordionFlushMovimiento">
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMovimientoOne" aria-expanded="<?= $observacion ? 'true' : 'false' ?>" aria-controls="collapseMovimientoOne">
                        <strong><?= lang('Main.movimientosTitle') ?>
                        </strong>
                    </button>
                </h2>

                <div id="collapseMovimientoOne" class="accordion-collapse collapse <?= $observacion ? 'show' : '' ?>" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushMovimiento">
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
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMovimientoTwo" aria-expanded="<?= $observacion ? 'false' : 'true' ?>" aria-controls="collapseMovimientoTwo">
                        <strong><?= lang('Main.expedientePreview') ?></strong>
                    </button>
                </h2>
                <div id="collapseMovimientoTwo" class="accordion-collapse collapse <?= $observacion ? '' : 'show' ?>" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushMovimiento">

                    <div class="button-group">
                        <a href="<?= base_url('/' . $expediente[0]->local_path) ?>" class="btn btn-sm btn-primary" target="_blank">
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
    // funcion para derivar
    $('#formDerivar').on('submit', function(event) {
        event.preventDefault(); // Prevenir la acción predeterminada del formulario

        var formData = new FormData(this);
        $('#detalleExpediente').modal('hide');
        Swal.fire({
            type: "info",
            html: "<div class='d-flex justify-content-center'>" +
                "<div class='spinner-border text-primary' role='status'>" +
                "<span class='visually-hidden'>Loading...</span>" +
                "</div>" +
                "</div>",
            showConfirmButton: false,
            showCloseButton: false,
            showCancelButton: false,
        });
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: formData,
            processData: false, // Evitar que jQuery procese los datos
            contentType: false, // Evitar que jQuery establezca el tipo de contenido
            success: function(response) {
                // Manejar la respuesta del servidor aquí
                //response = JSON.parse(response);
                

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
                Swal.fire(
                        "Alerta",
                        "Hubo un Error, Vuelva a intentarlo nuevamente.",
                        "error"
                    ); // Muestra el error en la consola
            }
        });
    });

    // funcion para ATENDER
    $('#formAtender').on('submit', function(event) {
        event.preventDefault(); // Prevenir la acción predeterminada del formulario

        var formData = new FormData(this);
        $('#detalleExpediente').modal('hide');
        Swal.fire({
            type: "info",
            html: "<div class='d-flex justify-content-center'>" +
                "<div class='spinner-border text-primary' role='status'>" +
                "<span class='visually-hidden'>Loading...</span>" +
                "</div>" +
                "</div>",
            showConfirmButton: false,
            showCloseButton: false,
            showCancelButton: false,
        });
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: formData,
            processData: false, // Evitar que jQuery procese los datos
            contentType: false, // Evitar que jQuery establezca el tipo de contenido
            success: function(response) {
                // Manejar la respuesta del servidor aquí
                // response = JSON.parse(response);
                

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
                Swal.fire(
                        "Alerta",
                        "Hubo un Error, Vuelva a intentarlo nuevamente.",
                        "error"
                    ); // Muestra el error en la consola
            }
        });
    });
    // funcion para OBSERVAR
    $('#formObservar').on('submit', function(event) {
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
                    "<?= lang('Main.confirmarObservar') ?>",
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
                Swal.fire(
                        "Alerta",
                        "Hubo un Error, Vuelva a intentarlo nuevamente.",
                        "error"
                    ); // Muestra el error en la consola
            }
        });
    });
    $('form').on('submit', function(event) {
        var isValid = true;
        $(this).find('input[type="file"]').each(function() {
            var files = this.files;
            for (var i = 0; i < files.length; i++) {
                if (files[i].size > 20 * 1024 * 1024) { // 20 MB
                    isValid = false;
                    break;
                }
            }
        });
        if (!isValid) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Uno o más archivos superan el tamaño máximo permitido de 20 MB.',
            });
        }
    });
</script>