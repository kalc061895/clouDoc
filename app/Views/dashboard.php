<?= $this->extend('layouts/main'); ?>

<?= $this->section('title'); ?>
<?= lang('Main.appTitle') ?>
<?= $this->endSection(); ?>

<?php
$auth = service('auth');
$user = $auth->user();

// Obtener que menu es el que esta activo
$menuActive = 2;

?>
<?= $this->section('username'); ?>
<?= $user->username; ?>
<?= $this->endSection(); ?>




<?= $this->section('profile'); ?>
<img src="../assets/images/profile/<?= $user->photo ?>" class="rounded-circle" width="80" height="80" alt="" />
<div class="ms-3">
    <h5 class="mb-1 fs-4"><?= $user->username; ?></h5>
    <span class="mb-1 d-block"><?= $user->cargo; ?></span>
    <p class="mb-0 d-flex align-items-center gap-2">
        <i class="ti ti-mail fs-4"></i> <?= $user->email; ?>
    </p>
</div>
<?= $this->endSection(); ?>

<!-- Menu Vertical -->
<?= $this->section('menuVertical'); ?>

<?php foreach ($menu as $item) : ?>

    <?php if ($item['type'] =='primary') : ?>
        <!-- MENU DE TODO---------------------------------- -->
        <li class="nav-small-cap">
            <iconify-icon icon="solar:menu-dots-linear" class="mini-icon"></iconify-icon>
            <span class="hide-menu"><?= $item['name'] ?></span>
        </li>
    <?php else : ?>
        <li class="sidebar-item">
            <a class="sidebar-link open-here <?= ($item['id']==$menuActive)?' active ':''?>" href="<?= $item['url'] ?>"  aria-expanded="false">
                <iconify-icon icon="solar:<?= $item['icon'] ?>"></iconify-icon>
                <span class="hide-menu"><?= $item['name'] ?></span>
            </a>
        </li>
    <?php endif ?>



<?php endforeach ?>

<?= $this->endSection(); ?>

<!-- Menu Horizontal -->
<?= $this->section('menuHorizontal'); ?>

<?= $this->endSection(); ?>


<?= $this->section('content'); ?>

<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title fs-4 fw-normal mb-3">
                    <?=lang('Main.totalExpedientes')?>
                </h4>
                <h2 class="mb-3 fs-9 fw-light">$56,908</h2>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="fas fa-caret-down text-warning fs-5"></i>
                    <span class="text-muted fs-3 fw-medium">$45,987</span>
                </div>
                <div class="progress bg-warning-subtle" style="height: 4px">
                    <div class="progress-bar w-70 text-bg-warning" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title fs-4 fw-normal mb-3">
                    <?=lang('Main.totalExpedientesHoy')?>
                </h4>
                <h2 class="mb-3 fs-9 fw-light">
                    10.578 <span class="fs-5 fw-medium">Kwh</span>
                </h2>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="fas fa-caret-up text-indigo fs-5"></i>
                    <span class="text-muted fs-3 fw-medium">567 Kwh</span>
                </div>
                <div class="progress bg-indigo-subtle" style="height: 4px">
                    <div class="progress-bar w-70 text-bg-secondary" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title fs-4 fw-normal mb-3">
                    <?= lang('Main.almacenamientoDrive')?>
                </h4>
                <h2 class="mb-3 fs-9 fw-light">
                    14.897 <span class="fs-5 fw-medium">Gb</span>
                </h2>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="fas fa-caret-down text-danger fs-5"></i>
                    <span class="text-muted fs-3 fw-medium">467 Gb</span>
                </div>
                <div class="progress bg-danger-subtle" style="height: 4px">
                    <div class="progress-bar w-70 text-bg-danger" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title fs-4 fw-normal mb-3">Total Profit</h4>
                <h2 class="mb-3 fs-9 fw-light">$995,204</h2>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="fas fa-caret-up text-success fs-5"></i>
                    <span class="text-muted fs-3 fw-medium">$45,987</span>
                </div>
                <div class="progress bg-success-subtle" style="height: 4px">
                    <div class="progress-bar w-70 text-bg-success" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex align-items-center flex-wrap">
                            <div>
                                <h4 class="card-title">Revenue Statistics</h3>
                                    <p class="card-subtitle">January 2024</p>
                            </div>
                            <div class="ms-auto">

                                <ul class="list-unstyled mb-0 hstack gap-3">
                                    <li>
                                        <h6 class="text-muted mb-0 hstack gap-2 fw-medium">
                                            <span class="text-bg-success round-10 rounded-circle"></span>Product
                                            A
                                        </h6>
                                    </li>
                                    <li>
                                        <h6 class="text-muted mb-0 hstack gap-2 fw-medium">
                                            <span class="text-bg-info round-10 rounded-circle"></span>Product
                                            B
                                        </h6>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div id="revenue-statistics" class="mx-n3"></div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4 mt-3 text-center">
                        <h2 class="fw-medium">$54,578</h2>
                        <p class="text-muted mb-0 fs-3">Total Revenue</p>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4 mt-3 text-center">
                        <h2 class="fw-medium">$43,451</h2>
                        <p class="text-muted mb-0 fs-3">Online Revenue</p>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4 mt-3 text-center">
                        <h2 class="fw-medium">$44,578</h2>
                        <p class="text-muted mb-0 fs-3">Product A</p>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4 mt-3 text-center">
                        <h2 class="fw-medium">$12,578</h2>
                        <p class="text-muted mb-0 fs-3">Product B</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

<?= $this->endSection(); ?>

<?= $this->section('pageScripts'); ?>


<?= $this->endSection(); ?>