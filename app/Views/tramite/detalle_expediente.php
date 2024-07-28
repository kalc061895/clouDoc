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
            <div class="accordion accordion-flush position-relative overflow-hidden" id="accordionFlushExample">
                <div class="accordion-item mb-3 shadow-none border rounded-top">
                    <h2 class="accordion-header" id="flush-headingOne">
                        <button class="accordion-button collapsed fs-4 fw-semibold px-3 py-6 lh-base border-0 rounded-top bg-primary-subtle " type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                            <?= lang('Main.derivar') ?>
                        </button>
                    </h2>
                    <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body px-3 fw-normal">

                            <form class="floating-labels mt-4 pt-2" action="<?= base_url('/mesa_de_partes/derivar') ?>" method="POST" id="formDerivar">
                                <input type="text" name="id" value="<?= $expediente[0]->id?>" hidden>
                                <div class="form-group mb-4">
                                    <select class="form-control form-select" id="oficinaDerivar" name="oficinaDerivar" required>
                                        <option></option>
                                        <?php foreach ($oficina as $item) : ?>
                                            <option value="<?= $item['id'] ?>"><?= $item['nombre'] ?></option>
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
                                    <input class="form-control form-sm" type="file" id="adjuntoDerivar" name="adjuntoDerivar">
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
                <div class="accordion-item mb-3 shadow-none border">
                    <h2 class="accordion-header" id="flush-headingTwo">
                        <button class="accordion-button collapsed fs-4 fw-semibold px-3 py-6 lh-base border-0  bg-danger-subtle" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                            <?= lang('Main.observar') ?>
                        </button>
                    </h2>
                    <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
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
        <iframe src="<?= $expediente[0]->local_path ?>" class="w-100 h-100" frameborder="0"></iframe>
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