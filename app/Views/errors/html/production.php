<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">

    <title><?= lang('Errors.whoops') ?></title>

    <style>
        <?= preg_replace('#[\r\n\t ]+#', ' ', file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'debug.css')) ?>
    </style>
</head>

<body>

    <div class="container text-center">



    </div>

</body>

</html>

<!DOCTYPE html>
<html lang="es" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">


<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />

    <!-- Core Css -->
    <link rel="stylesheet" href="../assets/css/styles.css" />

    <title><?= lang('Main.appTitle') ?></title>
</head>

<body>
    <!-- Preloader -->
    <div class="preloader">
        <img src="../assets/images/logos/favicon.png" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <div id="main-wrapper" class="auth-customizer-none">
        <div class="position-relative overflow-hidden min-vh-100 w-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-lg-4">
                        <div class="text-center">
                            <img src="../assets/images/backgrounds/maintenance.svg" alt="" class="img-fluid" width="500">
                            <h1 class="fw-semibold my-7 fs-9"><?= lang('Main.maintenanceTitle') ?></h1>
                            <h4 class="fw-semibold mb-7"><?= lang('Main.maintenanceBody') ?></h4>
                            <h1 class="headline"><?= lang('Error.whoops') ?></h1>

                            <p class="lead"><?= lang('Error.weHitASnag') ?></p>
                            <a class="btn btn-primary" href="<?= base_url('/') ?>" role="button"><?= lang('Main.maintenanceAction') ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dark-transparent sidebartoggler"></div>
    <!-- Import Js Files -->
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/libs/simplebar/dist/simplebar.min.js"></script>
    <script src="../assets/js/theme/app.init.js"></script>
    <script src="../assets/js/theme/theme.js"></script>
    <script src="../assets/js/theme/app.min.js"></script>

    <!-- solar icons -->
    <script src="../assets/js/iconify-icon%401.0.8/dist/iconify-icon.min.js"></script>
</body>



</html>