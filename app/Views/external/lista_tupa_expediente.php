<?= $this->extend('layouts/external'); ?>

<?= $this->section('title'); ?>
<?= lang('External.titleTupa') ?>

<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="row justify-content-center m-0">

    <div class="col-md-8 col-xl-6 ">
        <div class="card mb-0">
            <div class="text-bg-primary p-4 rounded-3 rounded-bottom-0">
                <div class="text-center text-white display-6">
                    <?= lang('External.titleTupa') ?>
                </div>
            </div>
            <div class="card-body">
                <div class="text-center mb-7">
                    <h3 class="fw-semibold"><?= lang('External.faqTitleTupa') ?></h3>
                    <p class="fw-normal mb-0 fs-4"><?= lang('External.descriptionTupa') ?></p>
                </div>
                <div class="accordion accordion-flush mb-5 card position-relative overflow-hidden" id="accordionFlushParent">
                    <?php if ($tupa != null) : ?>
                        <?php
                        $uniqueTupa = [];
                        $denominacionSet = [];

                        foreach ($tupa as $item_tupa) {
                            if (!in_array($item_tupa->denominacion_procedimiento, $denominacionSet)) {
                                $uniqueTupa[] = (object)[
                                    'id' => $item_tupa->t_id,
                                    'denominacion_procedimiento' => $item_tupa->denominacion_procedimiento,
                                ];
                                $denominacionSet[] = $item_tupa->denominacion_procedimiento;
                            }
                        }
                        ?>
                        <?php foreach ($uniqueTupa as $uniqueItem) : ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-heading<?= $uniqueItem->id ?>">
                                    <button class="accordion-button collapsed fs-6 fw-semibold shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse-<?= $uniqueItem->id ?>" aria-expanded="false" aria-controls="flush-collapse-<?= $uniqueItem->id ?>">
                                        <?= $uniqueItem->denominacion_procedimiento ?>
                                    </button>
                                </h2>
                                <div id="flush-collapse-<?= $uniqueItem->id ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?= $uniqueItem->id ?>" data-bs-parent="#accordionFlushParent">
                                    <div class="accordion-body fs-5">
                                        <ol class="list-group list-group-numbered list-group-flush">
                                            <?php foreach ($tupa as $item) : ?>
                                                <?php if ($item->tupa_id == $uniqueItem->id) : ?>
                                                    <!-- TRUE -->
                                                    <li class="list-group-item d-flex ">
                                                        
                                                        <?= $item->denominacion ?>

                                                        <?php if ($item->formato != '') : ?>
                                                            <span class="badge ms-auto">
                                                                <a class="btn btn-sm bg-primary text-white" href="<?= base_url('/' . $item->formato) ?>" target="_blank" rel="noopener noreferrer"> Descargar Formato</a>
                                                        <?php endif ?>
                                                    </li>
                                                <?php endif ?>
                                            <?php endforeach ?>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    <?php endif ?>
                    <pre>
                    </pre>
                </div>
            </div>
        </div>

    </div>
</div>
<?= $this->endSection(); ?>