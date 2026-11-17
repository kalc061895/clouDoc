<?php $portal = $portal ?? config('Portal'); ?>
<!doctype html>
<html lang="es" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= $this->renderSection('title') ?> | <?= esc($portal->nombre) ?></title>
<link rel="icon" href="<?= base_url('assets/images/logos/favicon.png') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/styles.css') ?>">
<?= $this->renderSection('pageStyles') ?>
</head>
<body>
<a class="visually-hidden-focusable btn btn-primary" href="#contenido">Saltar al contenido</a>
<div class="landingpage"><div class="main-wrapper">
<header class="topheader py-3" id="top"><div class="container">
<nav class="navbar navbar-expand-md navbar-light ps-0" aria-label="Principal">
<a class="navbar-brand fw-bold fs-7 text-dark" href="<?= site_url('/') ?>"><?= esc($portal->nombre) ?></a>
<button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#portal-menu" aria-controls="portal-menu" aria-label="Abrir menu"><i class="ti ti-menu-2 fs-7" aria-hidden="true"></i></button>
<div class="collapse navbar-collapse"><ul class="navbar-nav ms-auto stylish-nav align-items-md-center gap-3">
<li class="nav-item"><a class="nav-link" href="<?= site_url('/') ?>#modulos">M&oacute;dulos</a></li>
<li class="nav-item"><a class="nav-link" href="<?= site_url('/') ?>#recursos">Manuales y videos</a></li>
<li class="nav-item"><a class="nav-link" href="<?= site_url('/') ?>#ayuda">Ayuda</a></li>
<li class="nav-item"><a class="btn btn-primary" href="<?= site_url('login') ?>">Iniciar sesi&oacute;n</a></li>
</ul></div></nav></div></header>
<div class="offcanvas offcanvas-start" tabindex="-1" id="portal-menu" aria-labelledby="portal-menu-title">
<div class="offcanvas-header"><h5 class="offcanvas-title" id="portal-menu-title"><?= esc($portal->nombre) ?></h5><button class="btn-close" type="button" data-bs-dismiss="offcanvas" aria-label="Cerrar menu"></button></div>
<div class="offcanvas-body"><nav class="nav flex-column gap-3" aria-label="Menu movil">
<a class="nav-link" href="<?= site_url('/') ?>#modulos">M&oacute;dulos</a><a class="nav-link" href="<?= site_url('/') ?>#recursos">Manuales y videos</a><a class="nav-link" href="<?= site_url('/') ?>#ayuda">Ayuda</a><a class="btn btn-primary" href="<?= site_url('login') ?>">Iniciar sesi&oacute;n</a>
</nav></div></div>
<main id="contenido"><?= $this->renderSection('content') ?></main>
<footer class="text-center py-5 border-top"><div class="container"><a class="fw-bold fs-6" href="<?= site_url('/') ?>"><?= esc($portal->nombre) ?></a><p class="mt-2"><?= esc($portal->entidad) ?> &middot; Gesti&oacute;n institucional</p><div class="d-flex justify-content-center flex-wrap gap-4"><a href="<?= site_url('portal/modulos/tramite-documentario') ?>">Mesa de partes y tr&aacute;mites</a><a href="<?= site_url('/') ?>#recursos">Centro de aprendizaje</a><a href="<?= site_url('login') ?>">Iniciar sesi&oacute;n</a></div></div></footer>
</div></div>
<script src="<?= base_url('assets/js/vendor.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
<?= $this->renderSection('pageScripts') ?>
</body></html>
