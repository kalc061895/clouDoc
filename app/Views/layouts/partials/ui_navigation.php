<?php

/** The authenticated controller supplies its authorized menu via the existing sections. */
helper('ui');
// CI4 renderSection() echoes instead of returning its content.
ob_start();
$this->renderSection('username');
$usernameSection = trim(ob_get_clean());
$usernameText = $uiUser->username ?? 'Usuario';
ob_start();
$this->renderSection('sidebar');
$sidebar = trim(ob_get_clean());
if ($sidebar === '') {
    ob_start();
    $this->renderSection('menuVertical');
    $sidebar = trim(ob_get_clean());
}
if ($sidebar === '') {
    $sidebar = view_cell('App\\Cells\\MenuCell::render', ['tipo' => 'vertical']);
}
?>
<aside class="left-sidebar with-vertical">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="<?= site_url('inicio') ?>" class="text-nowrap logo-img">
                <img src="<?= base_url('assets/images/logos/dark-logo.png') ?>" alt="ClouDoc" class="dark-logo">
                <img src="<?= base_url('assets/images/logos/light-logo.png') ?>" alt="ClouDoc" class="light-logo">
            </a>
            <button class="btn btn-light d-xl-none" type="button" data-ui-sidebar-toggle aria-label="Cerrar menú"><i class="ti ti-x" aria-hidden="true"></i></button>
        </div>
        <nav class="sidebar-nav scroll-sidebar" data-simplebar aria-label="Menú principal">
            <ul class="sidebar-menu" id="sidebarnav">
                <li class="sidebar-item"><a class="sidebar-link" id="get-url" data-ui-home="<?= site_url('inicio') ?>" href="<?= site_url('inicio') ?>"><i class="ti ti-home" aria-hidden="true"></i><span class="hide-menu">Inicio</span></a></li>
                <?= $sidebar ?>
            </ul>
        </nav>
    </div>
</aside>
<div class="page-wrapper">
    <header class="topbar">
        <nav class="navbar navbar-expand-lg p-0" aria-label="Barra de usuario">
            <ul class="navbar-nav">
                <li class="nav-item"><button class="nav-link sidebartoggler nav-icon-hover-bg rounded-circle" type="button" data-ui-sidebar-toggle aria-label="Alternar menú"><i class="ti ti-menu-2 fs-7" aria-hidden="true"></i></button></li>
            </ul>
            <div class="ms-auto d-flex align-items-center gap-3">
                <?= $this->renderSection('navbar') ?>
                <span class="fw-semibold"><?= $usernameSection !== '' ? $usernameSection : ui_escape($usernameText) ?></span>
                <a class="btn btn-outline-primary btn-sm" href="<?= site_url('logout') ?>">Cerrar sesión</a>
            </div>
        </nav>
    </header>
    <div class="body-wrapper">
        <main id="contenido" class="container-fluid content-main mw-100" tabindex="-1">
            <?= $this->renderSection('content') ?>
        </main>
    </div>
</div>