<!doctype html>
<html lang="es" data-bs-theme="light" data-color-theme="Blue_Theme">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->renderSection('title') ?> · clouDoc</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/styles.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/sweetalert2/dist/sweetalert2.min.css') ?>">
</head>

<body class="bg-light">
    <header class="bg-white border-bottom mb-4">
        <nav class="container-fluid px-4 py-3 d-flex flex-wrap align-items-center gap-3" aria-label="Navegación principal">
            <a class="fs-5 fw-bold text-primary" href="<?= base_url('inicio') ?>">clouDoc</a>
            <a class="btn btn-primary" href="<?= base_url('firma') ?>" aria-current="page">Firma de documentos</a>
            <a class="btn btn-outline-secondary" href="<?= base_url('asistencia/roles/historial') ?>">Roles de Asistencia</a>
            <a class="ms-auto" href="<?= base_url('inicio') ?>">Volver al inicio</a>
        </nav>
    </header>
    <main class="container-fluid px-4 pb-4"><?= $this->renderSection('content') ?></main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?= base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/libs/sweetalert2/dist/sweetalert2.min.js') ?>"></script>
    <?= $this->renderSection('pageScripts') ?>
</body>

</html>
