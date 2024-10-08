<!DOCTYPE html>
<html lang="es" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/png" href="<?= base_url('/')?>assets/images/logos/favicon.png" />

    <!-- Core Css -->
    <link rel="stylesheet" href="<?= base_url('/')?>assets/css/styles.css" />

    <title><?= lang('Main.appTitle') ?></title>
</head>

<body>
    <!-- Preloader -->
    <div class="preloader">
        <img src="<?= base_url('/')?>assets/images/logos/favicon.png" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <div id="main-wrapper" class="auth-customizer-none">
        <div class="position-relative overflow-hidden min-vh-100 w-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-lg-4">
                        <div class="text-center">
                            <img src="<?= base_url('/')?>assets/images/backgrounds/errorimg.svg" alt="" class="img-fluid" width="500">
                            <h1 class="fw-semibold mb-7 fs-9"><?= lang('Main.errorTitle404') ?></h1>
                            
                            <?php if (ENVIRONMENT !== 'production') : ?>
                                <?= nl2br(esc($message)) ?>
                                <?php else : ?>
                                    <h4 class="fw-semibold mb-7"><?= lang('Main.errorBody404') ?></h4>
                            <?php endif; ?>
                            <a class="btn btn-primary" href="<?= base_url('/')?><?= base_url('/') ?>" role="button"><iconify-icon icon="solar:home-linear"></iconify-icon> <?= lang('Main.errorAction404') ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dark-transparent sidebartoggler"></div>
    <!-- Import Js Files -->
    <script src="<?= base_url('/')?>assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('/')?>assets/libs/simplebar/dist/simplebar.min.js"></script>
    <script src="<?= base_url('/')?>assets/js/theme/app.init.js"></script>
    <script src="<?= base_url('/')?>assets/js/theme/theme.js"></script>
    <script src="<?= base_url('/')?>assets/js/theme/app.min.js"></script>

    <!-- solar icons -->
    <script src="<?= base_url('/')?>assets/js/iconify-icon%401.0.8/dist/iconify-icon.min.js"></script>
</body>


</html>