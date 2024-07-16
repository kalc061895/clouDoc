<div class="row justify-content-center ">
    <div class="col-xl-6 col-lg-8 col-md-8 col-sm-12">
        <div class="card w-100">
            <div class="card-header text-bg-success">
                <h4 class="mb-0 text-white card-title"><?= lang('External.titleSuccess') ?> </h4>
            </div>
            <div class="card-body">

                <div class="card text-center">
                    <div class="card-body">
                        <img src="../assets/images/profile/user-3.jpg" class="rounded img-fluid" width="90">
                        <div class="mt-n2">
                            <span class="badge text-bg-primary"><?= lang('External.emisorSuccess') ?></span>
                            <h3 class="card-title mt-3"><?= $entidad['nombre'] ?></h3>
                            <p class="card-subtitle"><?= $entidad['correo_electronico'] ?></p>
                        </div>
                        <div class="row mt-3 justify-content-center">
                            <div class="col-md-6">
                                <div class="py-2 px-3 text-bg-light rounded-1 d-flex align-items-center">
                                    <span class="text-primary">
                                        <i class="ti ti-files fs-8"></i>
                                    </span>
                                    <div class="ms-2 text-start">

                                        <h4 class="mb-0 fs-5">Exp: <?= $expediente['numero_expediente'] ?></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="py-2 px-3 text-bg-light rounded-1 d-flex align-items-center">
                                    <span class="text-primary">
                                        <i class="ti ti-calendar fs-8"></i>
                                    </span>
                                    <div class="ms-2 text-start">

                                        <h4 class="mb-0 fs-5"><?= substr($expediente['fecha_recepcion'], 0, 10) ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="card-text">
                    <?= lang('External.infoSuccess') ?>
                </p>

                <div class="vstack gap-3">
                    <div class="hstack gap-6 border-bottom pb-3">
                        <div class="text-primary round-48 rounded-1 hstack justify-content-center">
                            <i class="ti ti-pin fs-7"></i>
                        </div>
                        <div>
                            <h4 class="fs-4 mb-0"><?= $expediente['asunto'] ?></h4>
                            <p class="mb-0">
                                <?= lang('External.asuntoDocExp') ?>
                            </p>
                        </div>
                    </div>

                    <div class="hstack gap-6 border-bottom pb-3">
                        <div class=" text-primary round-48 rounded-1 hstack justify-content-center">
                            <i class="ti ti-files fs-7"></i>
                        </div>
                        <div>
                            <h4 class="fs-4 mb-0"><?= $documento['nombre'] . " " . $expediente['numero_documento'] ?> :  <a href="<?= $adjunto['local_path'] ?>" target="_blank" class="
                                "> <i class="ti ti-file-text fs-4"></i>  Exp_<?= $expediente['numero_expediente'] ?>.pdf
                                </a>
                            </h4>
                            <p class="mb-0">
                                <?= lang('External.docuSuccess') ?>
                            </p>
                        </div>
                    </div>

                    <div class="hstack gap-6 border-bottom pb-3">
                        <div class="text-primary round-48 rounded-1 hstack justify-content-center">
                            <i class="ti ti-tag fs-7"></i>
                        </div>
                        <div>
                            <h4 class="fs-4 mb-0"><?= $expediente['folios'] ?></h4>
                            <p class="mb-0">
                                <?= lang('External.folioDocExp') ?>
                            </p>
                        </div>
                    </div>

                    
                </div>
                <div class="btn-group me-2 mb-2 w-100 " role="group" aria-label="First group">
                    <a href="<?= base_url('/cargoexpediente/'.$expediente['id'])?>" target="_blank" class="btn bg-primary-subtle btn-lg" >
                        <i class="ti ti-printer"></i> Imprimir
                    </a>
                    <a href="<?= base_url('/nuevoexpediente')?>" class="btn bg-secondary-subtle btn-lg" >
                        <i class="ti ti-arrow-right"></i> Nuevo </a>
                </div>
            </div>
        </div>
    </div>
</div>