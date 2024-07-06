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

  <title><?= $this->renderSection('title'); ?></title>
  <!-- Owl Carousel  -->
  <link rel="stylesheet" href="../assets/libs/aos/dist/aos.css" />
</head>

<body>
  <!-- Preloader -->
  <div class="preloader">
    <img src="../assets/images/logos/favicon.png" alt="loader" class="lds-ripple img-fluid" />
  </div>
  <div class="landingpage">
    <div class="main-wrapper">
      <header class="topheader py-3" id="top">
        <div class="container">
          <nav class="navbar navbar-expand-md navbar-light ps-0">
            <!-- Logo will be here -->
            <a class="navbar-brand" href="<?= base_url('') ?>">
              <img src="../assets/images/logos/dark-logo.svg" alt="img-fluid" />
            </a>

            <!--Toggle button-->
            <button class="navbar-toggler navbar-toggler-right border-0 p-0 fs-8" type="button" data-bs-toggle="offcanvas" href="#right-sidebar">
              <iconify-icon icon="solar:hamburger-menu-line-duotone"></iconify-icon>
            </button>

            <!-- This is the navigation menu -->
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
              <ul class="navbar-nav ms-auto stylish-nav">

                <li class="nav-item">
                  <a class="nav-link " href="<?= base_url('/nuevoexpediente') ?>"><?= lang('External.nuevoT') ?></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link " href="<?= base_url('/buscarexpediente') ?>"><?= lang('External.buscarT') ?></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link " href="<?= base_url('/tupaexpediente') ?>"><?= lang('External.tupaT') ?></a>
                </li>
                <li class="nav-item ms-3 mt-2 mt-md-0">
                  <a class="btn btn-secondary" href="<?= base_url('/login') ?>"><iconify-icon icon='solar:user-linear'></iconify-icon> <?= lang('Auth.login') ?></a>
                </li>
              </ul>
            </div>
          </nav>
        </div>
      </header>

      <!-- mobile sidebar -->
      <div class="offcanvas offcanvas-start" tabindex="-1" id="right-sidebar" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="offcanvasExampleLabel"><?= lang('External.menu') ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
          <ul class="navbar-nav stylish-nav">

            <li class="nav-item">
              <a class="nav-link" href="<?= base_url('/nuevoexpediente') ?>"><?= lang('External.nuevoTramite') ?></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?= base_url('/buscarexpediente') ?>"><?= lang('External.buscarTramite') ?></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?= base_url('/tupaexpediente') ?>"><?= lang('External.tupaTramite') ?></a>
            </li>
            <li class="nav-item mt-4">
              <a class="btn btn-secondary w-100" href="<?= base_url('/login') ?>"><?= lang('Auth.login') ?></a>
            </li>
          </ul>
        </div>
      </div>
      <?= $this->renderSection('content'); ?>

      <!-- start footer -->
      <footer class="text-center py-5">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4">
              <span>
                <img src="../assets/images/logos/favicon.png" alt="logo" />
              </span>
              <div class="mt-2">
                <span><?= lang('External.rights') ?>
                  <a href="#" class="text-primary"><?= lang('External.owner') ?></a>
                </span>
              </div>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>
  <div class="dark-transparent sidebartoggler"></div>
  <script src="../assets/js/vendor.min.js"></script>
  <!-- Import Js Files -->
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/libs/simplebar/dist/simplebar.min.js"></script>
  <script src="../assets/js/theme/app.init.js"></script>
  <script src="../assets/js/theme/theme.js"></script>
  <script src="../assets/js/theme/app.min.js"></script>

  <!-- solar icons -->
  <script src="../assets/js/iconify-icon%401.0.8/dist/iconify-icon.min.js"></script>
  <script src="../assets/libs/aos/dist/aos.js"></script>
  <script src="../assets/js/landingpage/landingpage.js"></script>
</body>



</html>