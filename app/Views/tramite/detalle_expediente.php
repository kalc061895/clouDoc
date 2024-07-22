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
                        <button class="accordion-button collapsed fs-4 fw-semibold px-3 py-6 lh-base border-0 rounded-top" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                            <?= lang('Main.derivar') ?>
                        </button>
                    </h2>
                    <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body px-3 fw-normal">

                            <form class="floating-labels mt-4 pt-2">
                                <div class="form-group mb-4">
                                    <select class="form-control form-select" id="oficinaDestino" name="oficinaDestino" required>
                                        <option></option>
                                        <?php foreach ($oficina as $item) : ?>
                                            <option value="<?= $item['id'] ?>"><?= $item['nombre'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <span class="bar"></span>
                                    <label for="oficinaDestino"><?= lang('Main.derivarOficina') ?></label>
                                </div>
                                <div class="form-group mb-4">
                                    <select class="form-control form-select" id="accionDestino" name="accionDestino" required>
                                        <option></option>
                                        <?php foreach ($accion as $item) : ?>
                                            <option value="<?= $item['id'] ?>"><?= $item['nombre'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <span class="bar"></span>
                                    <label for="accionDestino"><?= lang('Main.accion') ?></label>
                                </div>
                                <div class="form-group mb-4">
                                    <textarea class="form-control" rows="4" id="input7"></textarea>
                                    <span class="bar"></span>
                                    <label for="input7"><?= lang('Main.observacion') ?></label>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="adjuntoDerivar" class="form-label">Adjunto</label>
                                    <input class="form-control form-sm" type="file" id="adjuntoDerivar">
                                </div>
                                <div class="form-group mb-4">
                                    <button type="button" class="justify-content-center w-100 btn mb-1 btn-rounded btn-primary d-flex align-items-center">
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
                        <button class="accordion-button collapsed fs-4 fw-semibold px-3 py-6 lh-base border-0" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                            <?= lang('Main.observar') ?>
                        </button>
                    </h2>
                    <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body px-3 fw-normal">
                            <form class="floating-labels mt-4 pt-2">

                                <div class="form-group mb-4">
                                    <textarea class="form-control" rows="4" id="input7"></textarea>
                                    <span class="bar"></span>
                                    <label for="input7"><?= lang('Main.observacion') ?></label>
                                </div>
                                <div class="form-group mb-4">
                                    <button type="button" class="justify-content-center w-100 btn mb-1 btn-rounded btn-danger d-flex align-items-center">
                                        <i class="ti ti-upload fs-4 me-2"></i>
                                        <?= lang('Main.observar') ?>
                                    </button>

                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                <div class="accordion-item mb-3 shadow-none border rounded-bottom">
                    <h2 class="accordion-header" id="flush-headingThree">
                        <button class="accordion-button collapsed fs-4 fw-semibold px-3 py-6 lh-base border-0 rounded-bottom" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                            Payment Method
                        </button>
                    </h2>
                    <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body px-3 fw-normal">
                            <form class="floating-labels mt-4 pt-2">
                                <div class="form-group mb-4">
                                    <select class="form-control form-select" id="oficinaDestino" name="oficinaDestino" required>
                                        <option></option>
                                        <?php foreach ($oficina as $item) : ?>
                                            <option value="<?= $item['id'] ?>"><?= $item['nombre'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <span class="bar"></span>
                                    <label for="oficinaDestino"><?= lang('Main.derivarOficina') ?></label>
                                </div>
                                <div class="form-group mb-4">
                                    <select class="form-control form-select" id="accionDestino" name="accionDestino" required>
                                        <option></option>
                                        <?php foreach ($accion as $item) : ?>
                                            <option value="<?= $item['id'] ?>"><?= $item['nombre'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <span class="bar"></span>
                                    <label for="accionDestino"><?= lang('Main.accion') ?></label>
                                </div>
                                <div class="form-group mb-4">
                                    <textarea class="form-control" rows="4" id="input7"></textarea>
                                    <span class="bar"></span>
                                    <label for="input7"><?= lang('Main.observacion') ?></label>
                                </div>
                                <div class="form-group mb-4">
                                    <button type="button" class="justify-content-center w-100 btn mb-1 btn-rounded btn-primary d-flex align-items-center">
                                        <i class="ti ti-send fs-4 me-2"></i>
                                        <?= lang('Main.derivar') ?>
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
</script>