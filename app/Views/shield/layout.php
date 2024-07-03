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

    <title><?= $this->renderSection('title') ?></title>

    <?= $this->renderSection('pageStyles') ?>
</head>

<body class="bg-light">
    <!-- Preloader -->
    <div class="preloader">
        <img src="../assets/images/logos/favicon.png" alt="loader" class="lds-ripple img-fluid" />
    </div>

    <main role="main" id="main-wrapper" class="auth-customizer-none">
        <?= $this->renderSection('main') ?>
    </main>

    <?= $this->renderSection('pageScripts') ?>
</body>
<script>
    function handleColorTheme(e) {
        document.documentElement.setAttribute("data-color-theme", e);
    }
</script>
<div class="dark-transparent sidebartoggler"></div>
<!-- Import Js Files -->
<script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/libs/simplebar/dist/simplebar.min.js"></script>
<script src="../assets/js/theme/app.init.js"></script>
<script src="../assets/js/theme/theme.js"></script>
<script src="../assets/js/theme/app.min.js"></script>

<!-- solar icons -->
<script src="../assets/js/iconify-icon%401.0.8/dist/iconify-icon.min.js"></script>

</html>