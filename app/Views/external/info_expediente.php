<?php if ($expediente) : ?>

    <div class="card-header text-bg-success">
        <h5 class="mb-0 text-white"><?= lang('External.titleSuccess') ?> : <?= $expediente[0]->numero_expediente ?></h5>
    </div>

    <div class="card-body">
        <small class="text-muted"><?= lang('External.emisorSuccess') ?></small>
        <h6><?= $entidad['nombre'] ?></h6>
        <small class="text-muted"><?= lang('External.correoNew') ?></small>
        <h6><?= $entidad['correo_electronico'] ?></h6>
        <small class="text-muted db"><?= lang('External.telefonoNew') ?></small>
        <h6><?= $entidad['telefono'] ?></h6>
        <small class="text-muted db"><?= lang('External.asuntoDocExp') ?></small>
        <h6>
            <?= $expediente[0]->asunto ?> <a href="<?= $adjunto[0]->local_path ?>" target="_blank" class="
                                "> <i class="ti ti-file-text fs-4"></i> Exp_<?= $expediente[0]->numero_expediente ?>.pdf
            </a>
        </h6>
        <small class="text-muted db"><?= lang('External.fecha') ?></small>
        <h6>
            <?= $expediente[0]->recibido ?>
        </h6>
        <?php
        $estado = [
            'ESPERA' => 'warning',
            'RECIBIDO' => 'success',
            'DERIVADO' => 'success',
            'EN PROCESO' => 'primary',
            'FINALIZADO' => 'success',
            'ATENDIDO' => 'primary',
            'OBSERVADO' => 'danger',
            'RECHAZADO' => 'danger',

        ];
        ?>
        <h4 class="card-title"><?= lang('External.seguimientoTitle')?></h4>
        <p class="card-subtitle mb-7">
            <?= lang('External.seguimientoDescription')?>
        </p>
        <div class="comment-widgets position-relative mb-2 h-485" data-simplebar>
            <?php if ($movimiento) : ?>
                <?php foreach ($movimiento as $item) : ?>
                    <div class="d-flex gap-9 comment-row mb-3">
                        <span class="flex-shrink-0">
                            <img src="assets/images/profile/user-3.jpg" alt="user" width="56" height="56" class="rounded-circle" />
                        </span>
                        <div class="comment-text w-100">
                            <div class="hstack justify-content-between gap-6 mb-2">
                                <div class="hstack gap-6">
                                    <h5 class="mb-0"><?= $item->oficina_destino ?></h5>
                                    <p class="mb-0 fs-3 text-muted"><?= $item->fecha_envio ?></p>
                                </div>
                                <span class="badge bg-<?= $estado[$item->estado] ?>-subtle text-<?= $estado[$item->estado] ?> rounded-pill"> <?= $item->estado ?> </span>
                            </div>
                            <p class="comment-details mb-2">
                                <?= $item->observacion ?>
                            </p>
                            <ul class="list-unstyled mb-0 hstack gap-3 lh-1">
                                <li>
                                    <a class="fs-6 text-muted link-primary" href="javascript:void(0)">
                                        <iconify-icon icon="solar:gallery-edit-line-duotone" class="fs-6"></iconify-icon>
                                    </a>
                                </li>
                                <li>
                                    <a class="fs-6 text-muted link-primary" href="javascript:void(0)">
                                        <iconify-icon icon="solar:check-square-line-duotone" class="fs-6"></iconify-icon>
                                    </a>
                                </li>
                                <li>
                                    <a class="fs-6 text-muted link-danger" href="javascript:void(0)">
                                        <iconify-icon icon="solar:heart-angle-line-duotone" class="fs-6"></iconify-icon>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                <?php endforeach ?>
            <?php else : ?>
                <div class="d-flex gap-9 comment-row mb-3">
                    <span class="flex-shrink-0">
                        <img src="assets/images/profile/user-3.jpg" alt="user" width="56" height="56" class="rounded-circle" />
                    </span>
                    <div class="comment-text">
                        <div class="hstack justify-content-between gap-6 mb-2">
                            <div class="hstack gap-6">
                                <h5 class="mb-0">Oficina Destino</h5>
                                <p class="mb-0 fs-3 text-muted">April 14, 2024</p>
                            </div>
                            <span class="badge bg-danger-subtle text-danger rounded-pill">Rejected</span>
                        </div>
                        <p class="comment-details mb-2">
                            Lorem Ipsum is simply dummy text of the printing and type setting
                            industry.
                            Lorem Ipsum has beenorem Ipsum is simply dummy text of the printing
                            and type setting industry.
                        </p>
                        <ul class="list-unstyled mb-0 hstack gap-3 lh-1">
                            <li>
                                <a class="fs-6 text-muted link-primary" href="javascript:void(0)">
                                    <iconify-icon icon="solar:gallery-edit-line-duotone" class="fs-6"></iconify-icon>
                                </a>
                            </li>
                            <li>
                                <a class="fs-6 text-muted link-primary" href="javascript:void(0)">
                                    <iconify-icon icon="solar:check-square-line-duotone" class="fs-6"></iconify-icon>
                                </a>
                            </li>
                            <li>
                                <a class="fs-6 text-muted link-danger" href="javascript:void(0)">
                                    <iconify-icon icon="solar:heart-angle-line-duotone" class="fs-6"></iconify-icon>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            <?php endif ?>

            <!-- Comment Row -->

            <!-- Comment Row -->
            <div class="d-flex gap-9 comment-row mb-3">
                <span class="flex-shrink-0">
                    <img src="assets/images/profile/user-5.jpg" alt="user" width="56" height="56" class="rounded-circle" />
                </span>
                <div class="comment-text w-100">
                    <div class="hstack justify-content-between gap-6 mb-2">
                        <div class="hstack gap-6">
                            <h5 class="mb-0">OFICINA DE RECURSOS HUMANOS</h5>
                            <p class="mb-0 fs-3 text-muted">April 14, 2024</p>
                        </div>
                        <span class="badge bg-success-subtle text-success rounded-pill">DERIVADO</span>
                    </div>
                    <p class="comment-details mb-2">

                    </p>
                    <ul class="list-unstyled mb-0 hstack gap-3 lh-1">
                        <li>
                            <a class="fs-6 text-muted link-primary" href="javascript:void(0)">
                                <iconify-icon icon="solar:gallery-edit-line-duotone" class="fs-6"></iconify-icon>
                            </a>
                        </li>
                        <li>
                            <a class="fs-6 text-muted link-primary" href="javascript:void(0)">
                                <iconify-icon icon="solar:check-square-line-duotone" class="fs-6"></iconify-icon>
                            </a>
                        </li>
                        <li>
                            <a class="fs-6 text-muted link-danger" href="javascript:void(0)">
                                <iconify-icon icon="solar:heart-angle-line-duotone" class="fs-6"></iconify-icon>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Comment Row -->
            <div class="d-flex gap-9 comment-row mb-3">
                <span class="flex-shrink-0">
                    <img src="assets/images/profile/user-3.jpg" alt="user" width="56" height="56" class="rounded-circle" />
                </span>
                <div class="comment-text">
                    <div class="hstack justify-content-between gap-6 mb-2">
                        <div class="hstack gap-6">
                            <h5 class="mb-0">Johnathan Doeting</h5>
                            <p class="mb-0 fs-3 text-muted">April 14, 2024</p>
                        </div>
                        <span class="badge bg-danger-subtle text-danger rounded-pill">Rejected</span>
                    </div>
                    <p class="comment-details mb-2">
                        Lorem Ipsum is simply dummy text of the printing and type setting
                        industry.
                        Lorem Ipsum has beenorem Ipsum is simply dummy text of the printing
                        and type setting industry.
                    </p>
                    <ul class="list-unstyled mb-0 hstack gap-3 lh-1">
                        <li>
                            <a class="fs-6 text-muted link-primary" href="javascript:void(0)">
                                <iconify-icon icon="solar:gallery-edit-line-duotone" class="fs-6"></iconify-icon>
                            </a>
                        </li>
                        <li>
                            <a class="fs-6 text-muted link-primary" href="javascript:void(0)">
                                <iconify-icon icon="solar:check-square-line-duotone" class="fs-6"></iconify-icon>
                            </a>
                        </li>
                        <li>
                            <a class="fs-6 text-muted link-danger" href="javascript:void(0)">
                                <iconify-icon icon="solar:heart-angle-line-duotone" class="fs-6"></iconify-icon>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Comment Row -->
            <div class="d-flex gap-9 comment-row mb-3">
                <span class="flex-shrink-0">
                    <img src="assets/images/profile/user-7.jpg" alt="user" width="56" height="56" class="rounded-circle" />
                </span>
                <div class="comment-text">
                    <div class="hstack justify-content-between gap-6 mb-2">
                        <div class="hstack gap-6">
                            <h5 class="mb-0">James Anderson</h5>
                            <p class="mb-0 fs-3 text-muted">April 14, 2024</p>
                        </div>
                        <span class="badge bg-info-subtle text-info rounded-pill">Pending</span>
                    </div>
                    <p class="comment-details mb-2">
                        Lorem Ipsum is simply dummy text of the printing and type setting
                        industry.
                        Lorem Ipsum has beenorem Ipsum is simply dummy text of the printing
                        and type setting industry.
                    </p>
                    <ul class="list-unstyled mb-0 hstack gap-3 lh-1">
                        <li>
                            <a class="fs-6 text-muted link-primary" href="javascript:void(0)">
                                <iconify-icon icon="solar:gallery-edit-line-duotone" class="fs-6"></iconify-icon>
                            </a>
                        </li>
                        <li>
                            <a class="fs-6 text-muted link-primary" href="javascript:void(0)">
                                <iconify-icon icon="solar:check-square-line-duotone" class="fs-6"></iconify-icon>
                            </a>
                        </li>
                        <li>
                            <a class="fs-6 text-muted link-danger" href="javascript:void(0)">
                                <iconify-icon icon="solar:heart-angle-line-duotone" class="fs-6"></iconify-icon>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Comment Row -->
            <div class="d-flex gap-9 comment-row mb-3">
                <span class="flex-shrink-0">
                    <img src="assets/images/profile/user-8.jpg" alt="user" width="56" height="56" class="rounded-circle" />
                </span>
                <div class="comment-text w-100">
                    <div class="hstack justify-content-between gap-6 mb-2">
                        <div class="hstack gap-6">
                            <h5 class="mb-0">RECURSOS HUMANOS</h5>
                            <p class="mb-0 fs-3 text-muted">02-Julio-2024</p>
                        </div>
                        <span class="badge bg-warning-subtle text-warning rounded-pill">EN ESPERA</span>
                    </div>
                    <p class="comment-details mb-2 w-100">
                        Sin observación.asdasdasdasdasdasdasddddddddddddddddd
                    </p>
                    <ul class="list-unstyled mb-0 hstack gap-3 lh-1">
                        <li>
                            <a class="fs-6 text-muted link-primary" href="javascript:void(0)">
                                <iconify-icon icon="solar:gallery-edit-line-duotone" class="fs-6"></iconify-icon>
                            </a>
                        </li>
                        <li>
                            <a class="fs-6 text-muted link-primary" href="javascript:void(0)">
                                <iconify-icon icon="solar:check-square-line-duotone" class="fs-6"></iconify-icon>
                            </a>
                        </li>
                        <li>
                            <a class="fs-6 text-muted link-danger" href="javascript:void(0)">
                                <iconify-icon icon="solar:heart-angle-line-duotone" class="fs-6"></iconify-icon>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

<?php else : ?>
    <div class="alert customize-alert alert-dismissible text-secondary alert-light-secondary bg-secondary-subtle fade show remove-close-icon" role="alert">
        <span class="side-line bg-secondary"></span>

        <div class="d-flex align-items-center ">
            <i class="ti ti-info-circle fs-5 text-secondary me-2 flex-shrink-0"></i>
            <span class="text-truncate"><?= lang('External.noFound', [$exp_id]) ?></span>
            <div class="ms-auto d-flex justify-content-end">
                <a href="javascript:void(0)" class="px-2 btn" data-bs-dismiss="alert" aria-label="Close">
                    <i class="ti ti-trash fs-5 text-secondary"></i>
                </a>
            </div>
        </div>
    </div>

<?php endif ?>