<?php

/** Authenticated shell. Protect routes with the existing Shield session/permission filters. */
$uiUser = $uiUser ?? auth()->user();
?>
<!doctype html>
<html lang="es" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical" data-boxed-layout="full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->renderSection('title') ?></title>
    <link rel="icon" href="<?= base_url('assets/images/logos/favicon.png') ?>">
    <?= view('layouts/partials/ui_styles', ['authenticated' => true], ['saveData' => false]) ?>
    <?= $this->renderSection('styles') ?>
    <?= $this->renderSection('pageStyles') ?>
</head>

<body data-sidebartype="full">
    <a class="visually-hidden-focusable btn btn-primary" href="#contenido">Saltar al contenido</a>
    <div id="main-wrapper">
        <?= view('layouts/partials/ui_navigation', ['uiUser' => $uiUser], ['saveData' => false]) ?>
    </div>
    <div class="dark-transparent" data-ui-sidebar-toggle></div>
    <?= $this->renderSection('modals') ?>
    <?= view('components/toast', [], ['saveData' => false]) ?>
    <?= view('layouts/partials/ui_scripts', ['authenticated' => true], ['saveData' => false]) ?>
    <?= view('layouts/partials/ui_legacy_menu', [], ['saveData' => false]) ?>
    <?= $this->renderSection('scripts') ?>
    <?= $this->renderSection('pageScripts') ?>
</body>

</html>