<?php // Minimal authentication shell; route access remains the responsibility of Shield. ?>
<!doctype html>
<html lang="es" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->renderSection('title') ?></title>
    <link rel="icon" href="<?= base_url('assets/images/logos/favicon.png') ?>">
    <?= view('layouts/partials/ui_styles', ['authenticated' => false], ['saveData' => false]) ?>
    <?= $this->renderSection('styles') ?>
    <?= $this->renderSection('pageStyles') ?>
</head>
<body class="bg-light">
    <main id="main-wrapper" class="auth-customizer-none">
        <div class="position-relative overflow-hidden min-vh-100 w-100 d-flex align-items-center justify-content-center py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                        <div class="card mb-0"><div class="card-body p-4 p-sm-5">
                            <a href="<?= site_url('/') ?>" class="d-block mb-4 text-center"><img src="<?= base_url('assets/images/logos/dark-logo.png') ?>" alt="ClouDoc" class="img-fluid"></a>
                            <?= $this->renderSection('content') ?>
                            <?= $this->renderSection('main') ?>
                        </div></div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?= $this->renderSection('modals') ?>
    <?= view('components/toast', [], ['saveData' => false]) ?>
    <?= view('layouts/partials/ui_scripts', ['authenticated' => false], ['saveData' => false]) ?>
    <?= $this->renderSection('scripts') ?>
    <?= $this->renderSection('pageScripts') ?>
</body>
</html>
