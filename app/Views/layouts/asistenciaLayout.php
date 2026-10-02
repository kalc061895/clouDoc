<?php
$usuarioAsistencia = auth()->user();
$perfilAsistencia = \App\Libraries\AsistenciaLayoutData::perfil($usuarioAsistencia);
$menuAsistenciaModel = new \App\Models\MenuModel();
$menuAsistencia = $menuAsistenciaModel->getMenuTree();
$opcionesAsistencia = \App\Libraries\AsistenciaLayoutData::opciones($menuAsistencia, $menuAsistenciaModel->getMenusByRole());
$aparienciaAsistencia = \App\Services\UserPreferenceService::DEFAULTS;
$aparienciaDisponible = true;
try {
    $aparienciaAsistencia = (new \App\Services\UserPreferenceService())->obtener((int) $usuarioAsistencia->id);
} catch (\Throwable $e) {
    $aparienciaDisponible = false;
    log_message('error', 'Carga de apariencia: {message}', ['message' => $e->getMessage()]);
}
?>
<!DOCTYPE html>
<html lang="es" dir="<?= esc($aparienciaAsistencia['Direction'], 'attr') ?>" data-bs-theme="<?= esc($aparienciaAsistencia['Theme'], 'attr') ?>" data-color-theme="<?= esc($aparienciaAsistencia['ColorTheme'], 'attr') ?>" data-layout="<?= esc($aparienciaAsistencia['Layout'], 'attr') ?>" data-boxed-layout="<?= $aparienciaAsistencia['BoxedLayout'] ? 'boxed' : 'full' ?>" data-card="<?= $aparienciaAsistencia['cardBorder'] ? 'border' : 'shadow' ?>">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/png') ?>" href="<?= base_url('assets/images/logos/favicon.png') ?>" />

    <!-- Core Css -->
    <link rel="stylesheet" href="<?= base_url('assets/css/styles.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/libs/sweetalert2/dist/sweetalert2.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') ?>">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/libs/select2/dist/css/select2.min.css') ?>">

    <title><?= $this->renderSection('title'); ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/asistencia-layout.css') ?>">
    <?= $this->renderSection('pageStyles'); ?>
</head>

<body class="asis-layout" data-sidebartype="<?= esc($aparienciaAsistencia['SidebarType'], 'attr') ?>">

    <!-- Toast -->

    <!-- Preloader -->
    <div class="preloader">
        <img src="<?= base_url('assets/images/logos/favicon.png') ?>" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <div id="main-wrapper">

        <!-- Sidebar Start -->
        <aside class="left-sidebar with-vertical">
            <!-- ---------------------------------- -->
            <!-- Start Vertical Layout Sidebar -->
            <!-- ---------------------------------- -->

            <div>
                <div class="brand-logo d-flex align-items-center">
                    <a href="<?= base_url('/inicio') ?>" class="text-nowrap logo-img">
                        <img src="<?= base_url('assets/images/logos/dark-logo.png') ?>" alt="Logo" class="dark-logo" />
                        <img src="<?= base_url('assets/images/logos/light-logo.png') ?>" alt="Logo"
                            class="light-logo" />
                    </a>
                </div>

                <!-- ---------------------------------- -->
                <!-- Dashboard -->
                <!-- ---------------------------------- -->
                <nav class="sidebar-nav scroll-sidebar" data-simplebar>
                    <ul class="sidebar-menu" id="sidebarnav">
                        <!-- User Profile-->
                        <li>
                            <!-- User profile -->
                            <div class="asis-sidebar-user text-center pt-4 pb-3">
                                <button type="button" class="border-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#asis-user-modal" aria-label="Ver mi información">
                                    <?= view('partials/asistencia/avatar', ['perfilAsistencia' => $perfilAsistencia, 'avatarClass' => 'asis-avatar-lg']) ?>
                                </button>
                                <div class="hide-menu mt-2 px-3"><strong class="d-block"><?= esc($perfilAsistencia['nombre']) ?></strong><span class="text-muted small d-block mt-1"><?= esc($perfilAsistencia['cargo']) ?></span></div>
                            </div>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="<?= base_url('/inicio') ?>" aria-expanded="false"
                                id="get-url">
                                <iconify-icon icon="solar:home-linear"></iconify-icon>
                                <span class="hide-menu"><?= lang('Main.dashboard') ?></span>
                            </a>
                        </li>
                        <!-- Menu Vertical -->
                        <?= view('partials/menuVerticalLayout', ['menu' => $menuAsistencia]) ?>

                    </ul>
                </nav>

                <div class="sidebar-footer hide-menu">
                    <button type="button" class="btn link" data-bs-toggle="modal" data-bs-target="#asis-user-modal" aria-label="Mi información"><i class="ti ti-user-circle" aria-hidden="true"></i></button>
                    <button type="button" class="btn link" data-bs-toggle="modal" data-bs-target="#exampleModal" aria-label="Buscar funcionalidad"><i class="ti ti-search" aria-hidden="true"></i></button>
                    <a href="<?= base_url('logout') ?>" class="link" title="Cerrar sesión" aria-label="Cerrar sesión"><i class="ti ti-logout" aria-hidden="true"></i></a>
                </div>
            </div>
        </aside>

        <!--  Sidebar End -->
        <div class="page-wrapper">
            <!--  Header Start -->
            <header class="topbar">
                <div class="with-vertical"><!-- ---------------------------------- -->
                    <!-- Start Vertical Layout Header -->
                    <!-- ---------------------------------- -->
                    <nav class="navbar navbar-expand-lg p-0">
                        <ul class="navbar-nav">
                            <li class="nav-item nav-icon-hover-bg dark rounded-circle d-flex">
                                <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                                    <iconify-icon icon="solar:hamburger-menu-line-duotone" class="fs-6"></iconify-icon>
                                </a>
                            </li>

                            <!-- ------------------------------- -->
                            <!-- start notification Dropdown -->
                            <!-- ------------------------------- -->
                            <li class="nav-item dropdown nav-icon-hover-bg dark rounded-circle d-none d-xl-flex">
                                <a class="nav-link position-relative" href="javascript:void(0)" id="drop2"
                                    aria-expanded="false">
                                    <iconify-icon icon="solar:bell-bing-line-duotone" class="fs-6"></iconify-icon>
                                    <div class="notify">
                                        <span class="heartbit"></span>
                                        <span class="point"></span>
                                    </div>
                                </a>
                                <div class="dropdown-menu content-dd dropdown-menu-animate-up" aria-labelledby="drop2">
                                    <div class="py-3 px-4 border-bottom">
                                        <h5 class="mb-0 fs-4 fw-normal"><?= lang('Notification.notificacion') ?></h5>
                                    </div>
                                    <div class="message-body" data-simplebar>
                                        <div class="contenedorNotificaciones">
                                            <p class="text-center"><?= lang('Notification.no_new_notifications') ?></p>
                                        </div>

                                    </div>
                                    <div>
                                        <a class="d-flex align-items-center pt-3 pb-2 justify-content-center link-primary text-dark"
                                            href="javascript:void(0);">
                                            <span class="fw-semibold"><?= lang('Notification.check_all') ?></span>
                                            <iconify-icon icon="solar:alt-arrow-right-linear"></iconify-icon>
                                        </a>
                                    </div>

                                </div>
                            </li>
                            <!-- ------------------------------- -->
                            <!-- end notification Dropdown -->
                            <!-- ------------------------------- -->

                            <!-- ------------------------------- -->
                            <!-- start messages Dropdown -->
                            <!-- ------------------------------- -->
                            <li class="nav-item dropdown nav-icon-hover-bg dark rounded-circle d-none d-xl-flex">
                                <a class="nav-link position-relative" href="javascript:void(0)" id="drop2"
                                    aria-expanded="false">
                                    <iconify-icon icon="solar:inbox-line-duotone" class="fs-6"></iconify-icon>
                                    <div class="notify">
                                        <span class="heartbit"></span>
                                        <span class="point"></span>
                                    </div>
                                </a>
                                <div class="dropdown-menu content-dd dropdown-menu-animate-up" aria-labelledby="drop2">
                                    <div class="py-3 px-4 border-bottom">
                                        <h5 class="mb-0 fs-4 fw-normal">You have 4 new messages</h5>
                                    </div>
                                    <div class="message-body" data-simplebar>
                                        <a href="javascript:void(0)"
                                            class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                            <span class="user-img position-relative d-inline-block">
                                                <img src="<?= base_url('assets/images/profile/user-5.jpg') ?>"
                                                    alt="user" class="rounded-circle w-100 round-40" />
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle p-1 bg-warning border border-light rounded-circle">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                            </span>
                                            <div class="w-75 d-inline-block">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 lh-base">Mathew Anderson</h6>
                                                    <span class="fs-2 text-nowrap d-block text-body-color">9:30
                                                        AM</span>
                                                </div>
                                                <span
                                                    class="fs-2 d-block text-truncate text-truncate text-body-color">Just
                                                    see the my new
                                                    admin!</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)"
                                            class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                            <span class="user-img position-relative d-inline-block">
                                                <img src="<?= base_url('assets/images/profile/user-3.jpg') ?>"
                                                    alt="user" class="rounded-circle w-100 round-40" />
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                            </span>
                                            <div class="w-75 d-inline-block">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 lh-base">Bianca Anderson</h6>
                                                    <span class="fs-2 text-nowrap d-block text-body-color">9:10
                                                        AM</span>
                                                </div>

                                                <span
                                                    class="fs-2 d-block text-truncate text-truncate text-body-color">Just
                                                    a reminder that you
                                                    have event</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)"
                                            class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                            <span class="user-img position-relative d-inline-block">
                                                <img src="<?= base_url('assets/images/profile/user-6.jpg') ?>"
                                                    alt="user" class="rounded-circle w-100 round-40" />
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                            </span>
                                            <div class="w-75 d-inline-block">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 lh-base">Andrew Johnson</h6>
                                                    <span class="fs-2 text-nowrap d-block text-body-color">9:08
                                                        AM</span>
                                                </div>
                                                <span
                                                    class="fs-2 d-block text-truncate text-truncate text-body-color">You
                                                    can customize this
                                                    template as you want</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)"
                                            class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                            <span class="user-img position-relative d-inline-block">
                                                <img src="<?= base_url('assets/images/profile/user-7.jpg') ?>"
                                                    alt="user" class="rounded-circle w-100 round-40" />
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                                </button>
                                            </span>
                                            <div class="w-75 d-inline-block">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 lh-base">Mark Strokes</h6>
                                                    <span class="fs-2 text-nowrap d-block text-body-color">9:30
                                                        AM</span>
                                                </div>
                                                <span
                                                    class="fs-2 d-block text-truncate text-truncate text-body-color">Just
                                                    see the my new
                                                    admin!</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)"
                                            class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                            <span class="user-img position-relative d-inline-block">
                                                <img src="<?= base_url('assets/images/profile/user-8.jpg') ?>"
                                                    alt="user" class="rounded-circle w-100 round-40" />
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                            </span>
                                            <div class="w-75 d-inline-block">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 lh-base">Mark, Stoinus & Rishvi..</h6>
                                                    <span class="fs-2 text-nowrap d-block text-body-color">9:10
                                                        AM</span>
                                                </div>
                                                <span
                                                    class="fs-2 d-block text-truncate text-truncate text-body-color">Just
                                                    a reminder that you
                                                    have event</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)"
                                            class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                            <span class="user-img position-relative d-inline-block">
                                                <img src="<?= base_url('assets/images/profile/user-9.jpg') ?>"
                                                    alt="user" class="rounded-circle w-100 round-40" />
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle p-1 bg-warning border border-light rounded-circle">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                            </span>
                                            <div class="w-75 d-inline-block">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 lh-base">Settings</h6>
                                                    <span class="fs-2 text-nowrap d-block text-body-color">9:08
                                                        AM</span>
                                                </div>
                                                <span
                                                    class="fs-2 d-block text-truncate text-truncate text-body-color">You
                                                    can customize this
                                                    template as you want</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div>
                                        <a class="d-flex align-items-center pt-3 pb-2 justify-content-center link-primary text-dark"
                                            href="javascript:void(0);">
                                            <span class="fw-semibold">See all e-Mails</span>
                                            <iconify-icon icon="solar:alt-arrow-right-linear"></iconify-icon>
                                        </a>
                                    </div>

                                </div>
                            </li>
                            <!-- ------------------------------- -->
                            <!-- end messages Dropdown -->
                            <!-- ------------------------------- -->

                            <li class="nav-item dropdown nav-icon-hover-bg dark rounded-circle d-none d-xl-flex">
                                <div class="hover-dd">
                                    <a class="nav-link" id="drop2" href="javascript:void(0)" aria-haspopup="true"
                                        aria-expanded="false">
                                        <iconify-icon icon="solar:widget-3-line-duotone" class="fs-6"></iconify-icon>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-nav dropdown-menu-animate-up py-0 overflow-hidden"
                                        aria-labelledby="drop2">
                                        <div class="position-relative">
                                            <div class="row">
                                                <div class="col-8">
                                                    <div class="p-4 pb-3">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <div class="position-relative">
                                                                    <a href="app-chat.html"
                                                                        class="d-flex align-items-center pb-9 position-relative">
                                                                        <div
                                                                            class="bg-primary-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon icon="solar:chat-line-linear"
                                                                                class="text-primary fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Chat Application</h6>
                                                                            <span class="fs-11 d-block text-muted">New
                                                                                messages arrived</span>
                                                                        </div>
                                                                    </a>
                                                                    <a href="app-invoice.html"
                                                                        class="d-flex align-items-center pb-9 position-relative">
                                                                        <div
                                                                            class="bg-secondary-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon icon="solar:bill-list-linear"
                                                                                class="text-secondary fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Invoice App</h6>
                                                                            <span class="fs-11 d-block text-muted">Get
                                                                                latest invoice</span>
                                                                        </div>
                                                                    </a>
                                                                    <a href="app-contact2.html"
                                                                        class="d-flex align-items-center pb-9 position-relative">
                                                                        <div
                                                                            class="bg-warning-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon
                                                                                icon="solar:bedside-table-2-linear"
                                                                                class="text-warning fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Contact Application</h6>
                                                                            <span class="fs-11 d-block text-muted">2
                                                                                Unsaved Contacts</span>
                                                                        </div>
                                                                    </a>
                                                                    <a href="app-email.html"
                                                                        class="d-flex align-items-center position-relative">
                                                                        <div
                                                                            class="bg-danger-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon
                                                                                icon="solar:letter-unread-linear"
                                                                                class="text-danger fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Email App</h6>
                                                                            <span class="fs-11 d-block text-muted">Get
                                                                                new emails</span>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="position-relative">
                                                                    <a href="page-user-profile.html"
                                                                        class="d-flex align-items-center pb-9 position-relative">
                                                                        <div
                                                                            class="bg-success-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon
                                                                                icon="solar:cart-large-2-linear"
                                                                                class="text-success fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">User Profile</h6>
                                                                            <span class="fs-11 d-block text-muted">learn
                                                                                more information</span>
                                                                        </div>
                                                                    </a>
                                                                    <a href="app-calendar.html"
                                                                        class="d-flex align-items-center pb-9 position-relative">
                                                                        <div
                                                                            class="bg-primary-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon icon="solar:calendar-linear"
                                                                                class="text-primary fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Calendar App</h6>
                                                                            <span class="fs-11 d-block text-muted">Get
                                                                                dates</span>
                                                                        </div>
                                                                    </a>
                                                                    <a href="app-contact.html"
                                                                        class="d-flex align-items-center pb-9 position-relative">
                                                                        <div
                                                                            class="bg-secondary-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon
                                                                                icon="solar:bedside-table-linear"
                                                                                class="text-secondary fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Contact List Table</h6>
                                                                            <span class="fs-11 d-block text-muted">Add
                                                                                new contact</span>
                                                                        </div>
                                                                    </a>
                                                                    <a href="app-notes.html"
                                                                        class="d-flex align-items-center position-relative">
                                                                        <div
                                                                            class="bg-warning-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon icon="solar:palette-linear"
                                                                                class="text-warning fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Notes Application</h6>
                                                                            <span class="fs-11 d-block text-muted">To-do
                                                                                and Daily tasks</span>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row align-items-center border-top">
                                                        <div class="col-8">
                                                            <div class="ps-3 py-3">
                                                                <a class="text-dark d-flex align-items-center lh-1 fs-3"
                                                                    href="javascript:void(0)">
                                                                    <i class="ti ti-help fs-5 me-2"></i>Frequently Asked
                                                                    Questions
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="d-flex justify-content-end pe-2 py-3">
                                                                <button class="btn btn-primary">Check</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4 ms-n3">
                                                    <div class="position-relative p-3 border-start h-100">
                                                        <h5 class="fs-5 mb-9 fw-semibold">Quick Links</h5>
                                                        <ul>
                                                            <li class="mb-3">
                                                                <a class="fs-3" href="page-pricing.html">Pricing
                                                                    Page</a>
                                                            </li>
                                                            <li class="mb-3">
                                                                <a class="fs-3"
                                                                    href="authentication-login.html">Authentication
                                                                    Design</a>
                                                            </li>
                                                            <li class="mb-3">
                                                                <a class="fs-3"
                                                                    href="authentication-register.html">Register Now</a>
                                                            </li>
                                                            <li class="mb-3">
                                                                <a class="fs-3" href="authentication-error.html">404
                                                                    Error Page</a>
                                                            </li>
                                                            <li class="mb-3">
                                                                <a class="fs-3" href="app-notes.html">Notes App</a>
                                                            </li>
                                                            <li class="mb-3">
                                                                <a class="fs-3" href="page-user-profile.html">User
                                                                    Application</a>
                                                            </li>
                                                            <li class="mb-3">
                                                                <a class="fs-3"
                                                                    href="page-account-settings.html">Account
                                                                    Settings</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>

                        <div class="d-block d-lg-none py-4 py-xl-0">
                            <img src="<?= base_url('assets/images/logos/light-logo.png') ?>" alt="Logo" />
                        </div>
                        <ul class="navbar-nav navbar-toggler p-0 border-0">
                            <li class="nav-item nav-icon-hover-bg dark rounded-circle d-flex">
                                <a class="nav-link rounded-circle" href="javascript:void(0)" data-bs-toggle="collapse"
                                    data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                                    aria-label="Toggle navigation">
                                    <iconify-icon icon="solar:menu-dots-bold-duotone" class="fs-6"></iconify-icon>
                                </a>
                            </li>
                        </ul>

                        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                            <div class="d-flex align-items-center justify-content-between">
                                <ul class="navbar-nav d-flex d-xl-none flex-row">
                                    <!-- ------------------------------- -->
                                    <!-- start notification Dropdown -->
                                    <!-- ------------------------------- -->
                                    <li class="nav-item hover-dd dropdown">
                                        <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2"
                                            aria-expanded="false">
                                            <iconify-icon icon="solar:bell-linear" class="fs-6"></iconify-icon>
                                            <div class="notify">
                                                <span class="heartbit"></span>
                                                <span class="point"></span>
                                            </div>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-start content-dd dropdown-menu-animate-up mailbox"
                                            aria-labelledby="drop2">
                                            <div class="py-3 px-4 border-bottom">
                                                <h5 class="mb-0 fs-4 fw-normal">
                                                    <?php echo lang('Notification.notificacion'); ?>
                                                </h5>
                                            </div>
                                            <div class="message-body" data-simplebar>
                                                <div class="contenedorNotificaciones">
                                                    <p class="text-center">
                                                        <?= lang('Notification.no_new_notifications') ?>
                                                    </p>
                                                </div>

                                            </div>
                                            <div>
                                                <a class="d-flex align-items-center pt-3 pb-2 justify-content-center link-primary text-dark"
                                                    href="javascript:void(0);">
                                                    <span
                                                        class="fw-semibold"><?php echo lang('Notification.check_all'); ?></span>
                                                    <iconify-icon icon="solar:alt-arrow-right-linear"></iconify-icon>
                                                </a>
                                            </div>

                                        </div>
                                    </li>
                                    <!-- ------------------------------- -->
                                    <!-- end notification Dropdown -->
                                    <!-- ------------------------------- -->

                                    <!-- ------------------------------- -->
                                    <!-- start mailbox Dropdown -->
                                    <!-- ------------------------------- -->
                                    <li class="nav-item hover-dd dropdown">
                                        <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2"
                                            aria-expanded="false">
                                            <iconify-icon icon="solar:inbox-linear" class="fs-6"></iconify-icon>
                                            <div class="notify">
                                                <span class="heartbit"></span>
                                                <span class="point"></span>
                                            </div>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-start content-dd dropdown-menu-animate-up mailbox"
                                            aria-labelledby="drop2">
                                            <div class="py-3 px-4 border-bottom">
                                                <h5 class="mb-0 fs-4 fw-normal">You have 4 new messages</h5>
                                            </div>
                                            <div class="message-body" data-simplebar>
                                                <a href="javascript:void(0)"
                                                    class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                                    <span class="user-img position-relative d-inline-block">
                                                        <img src="<?= base_url('assets/images/profile/user-5.jpg') ?>"
                                                            alt="user" class="rounded-circle w-100 round-40" />
                                                        <span
                                                            class="position-absolute top-0 start-100 translate-middle p-1 bg-warning border border-light rounded-circle">
                                                            <span class="visually-hidden">New alerts</span>
                                                        </span>
                                                    </span>
                                                    <div class="w-75 d-inline-block">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-1 lh-base">Mathew Anderson</h6>
                                                            <span class="fs-2 text-nowrap d-block text-body-color">9:30
                                                                AM</span>
                                                        </div>
                                                        <span
                                                            class="fs-2 d-block text-truncate text-truncate text-body-color">Just
                                                            see the my new
                                                            admin!</span>
                                                    </div>
                                                </a>
                                                <a href="javascript:void(0)"
                                                    class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                                    <span class="user-img position-relative d-inline-block">
                                                        <img src="<?= base_url('assets/images/profile/user-3.jpg') ?>"
                                                            alt="user" class="rounded-circle w-100 round-40" />
                                                        <span
                                                            class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                                                            <span class="visually-hidden">New alerts</span>
                                                        </span>
                                                    </span>
                                                    <div class="w-75 d-inline-block">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-1 lh-base">Bianca Anderson</h6>
                                                            <span class="fs-2 text-nowrap d-block text-body-color">9:10
                                                                AM</span>
                                                        </div>

                                                        <span
                                                            class="fs-2 d-block text-truncate text-truncate text-body-color">Just
                                                            a reminder that
                                                            you have event</span>
                                                    </div>
                                                </a>
                                                <a href="javascript:void(0)"
                                                    class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                                    <span class="user-img position-relative d-inline-block">
                                                        <img src="<?= base_url('assets/images/profile/user-6.jpg') ?>"
                                                            alt="user" class="rounded-circle w-100 round-40" />
                                                        <span
                                                            class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                                            <span class="visually-hidden">New alerts</span>
                                                        </span>
                                                    </span>
                                                    <div class="w-75 d-inline-block">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-1 lh-base">Andrew Johnson</h6>
                                                            <span class="fs-2 text-nowrap d-block text-body-color">9:08
                                                                AM</span>
                                                        </div>
                                                        <span
                                                            class="fs-2 d-block text-truncate text-truncate text-body-color">You
                                                            can customize
                                                            this template as you want</span>
                                                    </div>
                                                </a>
                                                <a href="javascript:void(0)"
                                                    class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                                    <span class="user-img position-relative d-inline-block">
                                                        <img src="<?= base_url('assets/images/profile/user-7.jpg') ?>"
                                                            alt="user" class="rounded-circle w-100 round-40" />
                                                        <span
                                                            class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                                                            <span class="visually-hidden">New alerts</span>
                                                        </span>
                                                        </button>
                                                    </span>
                                                    <div class="w-75 d-inline-block">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-1 lh-base">Mark Strokes</h6>
                                                            <span class="fs-2 text-nowrap d-block text-body-color">9:30
                                                                AM</span>
                                                        </div>
                                                        <span
                                                            class="fs-2 d-block text-truncate text-truncate text-body-color">Just
                                                            see the my new
                                                            admin!</span>
                                                    </div>
                                                </a>
                                                <a href="javascript:void(0)"
                                                    class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                                    <span class="user-img position-relative d-inline-block">
                                                        <img src="<?= base_url('assets/images/profile/user-8.jpg') ?>"
                                                            alt="user" class="rounded-circle w-100 round-40" />
                                                        <span
                                                            class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                                                            <span class="visually-hidden">New alerts</span>
                                                        </span>
                                                    </span>
                                                    <div class="w-75 d-inline-block">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-1 lh-base">Mark, Stoinus & Rishvi..</h6>
                                                            <span class="fs-2 text-nowrap d-block text-body-color">9:10
                                                                AM</span>
                                                        </div>
                                                        <span
                                                            class="fs-2 d-block text-truncate text-truncate text-body-color">Just
                                                            a reminder that
                                                            you have event</span>
                                                    </div>
                                                </a>
                                                <a href="javascript:void(0)"
                                                    class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                                    <span class="user-img position-relative d-inline-block">
                                                        <img src="<?= base_url('assets/images/profile/user-9.jpg') ?>"
                                                            alt="user" class="rounded-circle w-100 round-40" />
                                                        <span
                                                            class="position-absolute top-0 start-100 translate-middle p-1 bg-warning border border-light rounded-circle">
                                                            <span class="visually-hidden">New alerts</span>
                                                        </span>
                                                    </span>
                                                    <div class="w-75 d-inline-block">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-1 lh-base">Settings</h6>
                                                            <span class="fs-2 text-nowrap d-block text-body-color">9:08
                                                                AM</span>
                                                        </div>
                                                        <span
                                                            class="fs-2 d-block text-truncate text-truncate text-body-color">You
                                                            can customize
                                                            this template as you want</span>
                                                    </div>
                                                </a>
                                            </div>
                                            <div>
                                                <a class="d-flex align-items-center pt-3 pb-2 justify-content-center link-primary text-dark"
                                                    href="javascript:void(0);">
                                                    <span class="fw-semibold">See all e-Mails</span>
                                                    <iconify-icon icon="solar:alt-arrow-right-linear"></iconify-icon>
                                                </a>
                                            </div>

                                        </div>
                                    </li>
                                    <!-- ------------------------------- -->
                                    <!-- end mailbox Dropdown -->
                                    <!-- ------------------------------- -->

                                    <!-- ------------------------------- -->
                                    <!-- start mega-dropdown Dropdown -->
                                    <!-- ------------------------------- -->
                                    <li class="nav-item dropdown mega-dropdown">
                                        <a href="javascript:void(0)"
                                            class="nav-link nav-icon-hover-bg dark rounded-circle d-flex d-lg-none align-items-center justify-content-center"
                                            type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilenavbar"
                                            aria-controls="offcanvasWithBothOptions">
                                            <iconify-icon icon="solar:widget-linear" class="fs-6"></iconify-icon>
                                        </a>
                                    </li>
                                    <!-- ------------------------------- -->
                                    <!-- end mega-dropdown Dropdown -->
                                    <!-- ------------------------------- -->
                                </ul>
                                <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">

                                    <?= view('partials/asistencia/searchTrigger') ?>
                                    <li class="nav-item">
                                        <a class="nav-link moon dark-layout nav-icon-hover-bg dark rounded-circle"
                                            href="javascript:void(0)">
                                            <iconify-icon icon="solar:moon-line-duotone"
                                                class="moon fs-6"></iconify-icon>
                                        </a>
                                        <a class="nav-link sun light-layout nav-icon-hover-bg dark rounded-circle"
                                            href="javascript:void(0)" style="display: none">
                                            <iconify-icon icon="solar:sun-2-line-duotone"
                                                class="sun fs-6"></iconify-icon>
                                        </a>
                                    </li>
                                    <!-- ------------------------------- -->
                                    <!-- start language Dropdown -->
                                    <!-- ------------------------------- -->
                                    <li class="nav-item dropdown nav-icon-hover-bg dark rounded-circle">
                                        <a class="nav-link" href="javascript:void(0)" id="drop2" aria-expanded="false">
                                            <img src="<?= base_url('assets/images/flag/icon-flag-pe.svg') ?>"
                                                alt="monster-img" width="20px" height="20px"
                                                class="rounded-circle object-fit-cover round-20" />
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up overflow-hidden"
                                            aria-labelledby="drop2">
                                            <div class="message-body">
                                                <a href="javascript:void(0)"
                                                    class="d-flex align-items-center gap-2 py-3 px-4 dropdown-item">
                                                    <div class="position-relative">
                                                        <img src="<?= base_url('assets/images/flag/icon-flag-pe.svg') ?>"
                                                            alt="monster-img" width="20px" height="20px"
                                                            class="rounded-circle object-fit-cover round-20" />
                                                    </div>
                                                    <p class="mb-0 fs-3"><?= lang('Main.languageES') ?></p>
                                                </a>
                                                <a href="javascript:void(0)"
                                                    class="d-flex align-items-center gap-2 py-3 px-4 dropdown-item">
                                                    <div class="position-relative">
                                                        <img src="<?= base_url('assets/images/flag/icon-flag-us.svg') ?>"
                                                            alt="monster-img" width="20px" height="20px"
                                                            class="rounded-circle object-fit-cover round-20" />
                                                    </div>
                                                    <p class="mb-0 fs-3"><?= lang('Main.languageEN') ?></p>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                    <!-- ------------------------------- -->
                                    <!-- end language Dropdown -->
                                    <!-- ------------------------------- -->

                                    <!-- ------------------------------- -->
                                    <?= view('partials/asistencia/userDropdown', ['perfilAsistencia' => $perfilAsistencia, 'orientacion' => 'vertical']) ?>
                                    <!-- ------------------------------- -->
                                </ul>
                            </div>
                        </div>
                    </nav>
                    <!-- ---------------------------------- -->
                    <!-- End Vertical Layout Header -->
                    <!-- ---------------------------------- -->

                    <!-- ------------------------------- -->
                    <!-- apps Dropdown in Small screen -->
                    <!-- ------------------------------- -->
                    <!--  Mobilenavbar -->
                    <div class="offcanvas offcanvas-start pt-0" data-bs-scroll="true" tabindex="-1" id="mobilenavbar"
                        aria-labelledby="offcanvasWithBothOptionsLabel">
                        <nav class="sidebar-nav scroll-sidebar">
                            <div class="offcanvas-header justify-content-between ps-0 pt-0">
                                <div class="brand-logo d-flex align-items-center">
                                    <a href="<?= base_url('/') ?>" class="text-nowrap logo-img">
                                        <img src="<?= base_url('assets/images/logos/dark-logo.png') ?>" alt="Logo"
                                            class="dark-logo" />
                                        <img src="<?= base_url('assets/images/logos/light-logo.png') ?>" alt="Logo"
                                            class="light-logo" />
                                    </a>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                    aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body pt-0" data-simplebar style="height: calc(100vh - 80px)">
                                <ul id="sidebarnav">
                                    <li class="sidebar-item">
                                        <a class="sidebar-link has-arrow ms-0 rounded" href="javascript:void(0)"
                                            aria-expanded="false">
                                            <span>
                                                <iconify-icon icon="solar:slider-vertical-line-duotone"
                                                    class="fs-7"></iconify-icon>
                                            </span>
                                            <span class="hide-menu">Apps</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse first-level my-3 ps-3">
                                            <li class="sidebar-item py-2">
                                                <a href="app-chat.html" class="d-flex align-items-center">
                                                    <div
                                                        class="bg-primary-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                        <iconify-icon icon="solar:chat-line-linear"
                                                            class="text-primary fs-5"></iconify-icon>
                                                    </div>
                                                    <div class="d-inline-block">
                                                        <h6 class="mb-0">Chat Application</h6>
                                                        <span class="fs-11 d-block text-muted">New messages
                                                            arrived</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="sidebar-item py-2">
                                                <a href="app-invoice.html" class="d-flex align-items-center">
                                                    <div
                                                        class="bg-secondary-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                        <iconify-icon icon="solar:bill-list-linear"
                                                            class="text-secondary fs-5"></iconify-icon>
                                                    </div>
                                                    <div class="d-inline-block">
                                                        <h6 class="mb-0">Invoice App</h6>
                                                        <span class="fs-11 d-block text-muted">Get latest invoice</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="sidebar-item py-2">
                                                <a href="app-contact2.html" class="d-flex align-items-center">
                                                    <div
                                                        class="bg-warning-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                        <iconify-icon icon="solar:bedside-table-2-linear"
                                                            class="text-warning fs-5"></iconify-icon>
                                                    </div>
                                                    <div class="d-inline-block">
                                                        <h6 class="mb-0">Contact Application</h6>
                                                        <span class="fs-11 d-block text-muted">2 Unsaved Contacts</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="sidebar-item py-2">
                                                <a href="app-email.html" class="d-flex align-items-center">
                                                    <div
                                                        class="bg-danger-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                        <iconify-icon icon="solar:letter-unread-linear"
                                                            class="text-danger fs-5"></iconify-icon>
                                                    </div>
                                                    <div class="d-inline-block">
                                                        <h6 class="mb-0">Email App</h6>
                                                        <span class="fs-11 d-block text-muted">Get new emails</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="sidebar-item py-2">
                                                <a href="page-user-profile.html" class="d-flex align-items-center">
                                                    <div
                                                        class="bg-success-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                        <iconify-icon icon="solar:cart-large-2-linear"
                                                            class="text-success fs-5"></iconify-icon>
                                                    </div>
                                                    <div class="d-inline-block">
                                                        <h6 class="mb-0"><?= lang('Main.userProfile') ?> </h6>
                                                        <span class="fs-11 d-block text-muted">learn more
                                                            information</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="sidebar-item py-2">
                                                <a href="app-calendar.html" class="d-flex align-items-center">
                                                    <div
                                                        class="bg-primary-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                        <iconify-icon icon="solar:calendar-linear"
                                                            class="text-primary fs-5"></iconify-icon>
                                                    </div>
                                                    <div class="d-inline-block">
                                                        <h6 class="mb-0">Calendar App</h6>
                                                        <span class="fs-11 d-block text-muted">Get dates</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="sidebar-item py-2">
                                                <a href="app-contact.html" class="d-flex align-items-center">
                                                    <div
                                                        class="bg-secondary-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                        <iconify-icon icon="solar:bedside-table-linear"
                                                            class="text-secondary fs-5"></iconify-icon>
                                                    </div>
                                                    <div class="d-inline-block">
                                                        <h6 class="mb-0">Contact List Table</h6>
                                                        <span class="fs-11 d-block text-muted">Add new contact</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="sidebar-item py-2">
                                                <a href="app-notes.html" class="d-flex align-items-center">
                                                    <div
                                                        class="bg-warning-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                        <iconify-icon icon="solar:palette-linear"
                                                            class="text-warning fs-5"></iconify-icon>
                                                    </div>
                                                    <div class="d-inline-block">
                                                        <h6 class="mb-0">Notes Application</h6>
                                                        <span class="fs-11 d-block text-muted">To-do and Daily
                                                            tasks</span>
                                                    </div>
                                                </a>
                                            </li>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
                <div class="app-header with-horizontal">
                    <nav class="navbar navbar-expand-xl container-fluid p-0">
                        <ul class="navbar-nav align-items-center">
                            <li class="nav-item d-flex d-xl-none">
                                <a class="nav-link sidebartoggler nav-icon-hover-bg rounded-circle" id="sidebarCollapse"
                                    href="javascript:void(0)">
                                    <iconify-icon icon="solar:hamburger-menu-line-duotone" class="fs-7"></iconify-icon>
                                </a>
                            </li>
                            <li class="nav-item d-none d-xl-flex align-items-center">
                                <a href="../horizontal/<?= base_url('/') ?>" class="text-nowrap nav-link">

                                    <img src="<?= base_url('assets/images/logos/light-logo.png') ?>" alt="Logo" />
                                </a>
                            </li>
                            <!-- ------------------------------- -->
                            <!-- start notification Dropdown -->
                            <!-- ------------------------------- -->
                            <li class="nav-item dropdown nav-icon-hover-bg dark rounded-circle d-none d-xl-flex">
                                <a class="nav-link position-relative" href="javascript:void(0)" id="drop2"
                                    aria-expanded="false">
                                    <iconify-icon icon="solar:bell-bing-line-duotone" class="fs-6"></iconify-icon>
                                    <div class="notify">
                                        <span class="heartbit"></span>
                                        <span class="point"></span>
                                    </div>
                                </a>
                                <div class="dropdown-menu content-dd dropdown-menu-animate-up" aria-labelledby="drop2">
                                    <div class="py-3 px-4 border-bottom">
                                        <h5 class="mb-0 fs-4 fw-normal"><?= lang('Notification.notificaciones') ?></h5>
                                    </div>
                                    <div class="message-body" data-simplebar>
                                        <div class="contenedorNotificaciones">
                                            <p class="text-center"><?= lang('Notification.no_new_notifications') ?></p>
                                        </div>

                                    </div>
                                    <div>
                                        <a class="d-flex align-items-center pt-3 pb-2 justify-content-center link-primary text-dark"
                                            href="javascript:void(0);">
                                            <span class="fw-semibold"><?= lang('Notification.check_all') ?></span>
                                            <iconify-icon icon="solar:alt-arrow-right-linear"></iconify-icon>
                                        </a>
                                    </div>

                                </div>
                            </li>
                            <!-- ------------------------------- -->
                            <!-- end notification Dropdown -->
                            <!-- ------------------------------- -->

                            <!-- ------------------------------- -->
                            <!-- start messages Dropdown -->
                            <!-- ------------------------------- -->
                            <li class="nav-item dropdown nav-icon-hover-bg dark rounded-circle d-none d-xl-flex">
                                <a class="nav-link position-relative" href="javascript:void(0)" id="drop2"
                                    aria-expanded="false">
                                    <iconify-icon icon="solar:inbox-line-duotone" class="fs-6"></iconify-icon>
                                    <div class="notify">
                                        <span class="heartbit"></span>
                                        <span class="point"></span>
                                    </div>
                                </a>
                                <div class="dropdown-menu content-dd dropdown-menu-animate-up" aria-labelledby="drop2">
                                    <div class="py-3 px-4 border-bottom">
                                        <h5 class="mb-0 fs-4 fw-normal">You have 4 new messages</h5>
                                    </div>
                                    <div class="message-body" data-simplebar>
                                        <a href="javascript:void(0)"
                                            class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                            <span class="user-img position-relative d-inline-block">
                                                <img src="<?= base_url('assets/images/profile/user-5.jpg') ?>"
                                                    alt="user" class="rounded-circle w-100 round-40" />
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle p-1 bg-warning border border-light rounded-circle">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                            </span>
                                            <div class="w-75 d-inline-block">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 lh-base">Mathew Anderson</h6>
                                                    <span class="fs-2 text-nowrap d-block text-body-color">9:30
                                                        AM</span>
                                                </div>
                                                <span
                                                    class="fs-2 d-block text-truncate text-truncate text-body-color">Just
                                                    see the my new
                                                    admin!</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)"
                                            class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                            <span class="user-img position-relative d-inline-block">
                                                <img src="<?= base_url('assets/images/profile/user-3.jpg') ?>"
                                                    alt="user" class="rounded-circle w-100 round-40" />
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                            </span>
                                            <div class="w-75 d-inline-block">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 lh-base">Bianca Anderson</h6>
                                                    <span class="fs-2 text-nowrap d-block text-body-color">9:10
                                                        AM</span>
                                                </div>

                                                <span
                                                    class="fs-2 d-block text-truncate text-truncate text-body-color">Just
                                                    a reminder that you
                                                    have event</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)"
                                            class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                            <span class="user-img position-relative d-inline-block">
                                                <img src="<?= base_url('assets/images/profile/user-6.jpg') ?>"
                                                    alt="user" class="rounded-circle w-100 round-40" />
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                            </span>
                                            <div class="w-75 d-inline-block">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 lh-base">Andrew Johnson</h6>
                                                    <span class="fs-2 text-nowrap d-block text-body-color">9:08
                                                        AM</span>
                                                </div>
                                                <span
                                                    class="fs-2 d-block text-truncate text-truncate text-body-color">You
                                                    can customize this
                                                    template as you want</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)"
                                            class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                            <span class="user-img position-relative d-inline-block">
                                                <img src="<?= base_url('assets/images/profile/user-7.jpg') ?>"
                                                    alt="user" class="rounded-circle w-100 round-40" />
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                                </button>
                                            </span>
                                            <div class="w-75 d-inline-block">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 lh-base">Mark Strokes</h6>
                                                    <span class="fs-2 text-nowrap d-block text-body-color">9:30
                                                        AM</span>
                                                </div>
                                                <span
                                                    class="fs-2 d-block text-truncate text-truncate text-body-color">Just
                                                    see the my new
                                                    admin!</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)"
                                            class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                            <span class="user-img position-relative d-inline-block">
                                                <img src="<?= base_url('assets/images/profile/user-8.jpg') ?>"
                                                    alt="user" class="rounded-circle w-100 round-40" />
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                            </span>
                                            <div class="w-75 d-inline-block">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 lh-base">Mark, Stoinus & Rishvi..</h6>
                                                    <span class="fs-2 text-nowrap d-block text-body-color">9:10
                                                        AM</span>
                                                </div>
                                                <span
                                                    class="fs-2 d-block text-truncate text-truncate text-body-color">Just
                                                    a reminder that you
                                                    have event</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)"
                                            class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                            <span class="user-img position-relative d-inline-block">
                                                <img src="<?= base_url('assets/images/profile/user-9.jpg') ?>"
                                                    alt="user" class="rounded-circle w-100 round-40" />
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle p-1 bg-warning border border-light rounded-circle">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                            </span>
                                            <div class="w-75 d-inline-block">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 lh-base">Settings</h6>
                                                    <span class="fs-2 text-nowrap d-block text-body-color">9:08
                                                        AM</span>
                                                </div>
                                                <span
                                                    class="fs-2 d-block text-truncate text-truncate text-body-color">You
                                                    can customize this
                                                    template as you want</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div>
                                        <a class="d-flex align-items-center pt-3 pb-2 justify-content-center link-primary text-dark"
                                            href="javascript:void(0);">
                                            <span class="fw-semibold">See all e-Mails</span>
                                            <iconify-icon icon="solar:alt-arrow-right-linear"></iconify-icon>
                                        </a>
                                    </div>

                                </div>
                            </li>
                            <!-- ------------------------------- -->
                            <!-- end messages Dropdown -->
                            <!-- ------------------------------- -->
                            <li
                                class="nav-item d-none d-lg-flex dropdown nav-icon-hover-bg dark rounded-circle d-none d-xl-flex">
                                <div class="hover-dd">
                                    <a class="nav-link" id="drop2" href="javascript:void(0)" aria-haspopup="true"
                                        aria-expanded="false">
                                        <iconify-icon icon="solar:widget-3-line-duotone" class="fs-6"></iconify-icon>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-nav dropdown-menu-animate-up py-0 overflow-hidden"
                                        aria-labelledby="drop2">
                                        <div class="position-relative">
                                            <div class="row">
                                                <div class="col-8">
                                                    <div class="p-4 pb-3">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <div class="position-relative">
                                                                    <a href="app-chat.html"
                                                                        class="d-flex align-items-center pb-9 position-relative">
                                                                        <div
                                                                            class="bg-primary-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon icon="solar:chat-line-linear"
                                                                                class="text-primary fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Chat Application</h6>
                                                                            <span class="fs-11 d-block text-muted">New
                                                                                messages arrived</span>
                                                                        </div>
                                                                    </a>
                                                                    <a href="app-invoice.html"
                                                                        class="d-flex align-items-center pb-9 position-relative">
                                                                        <div
                                                                            class="bg-secondary-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon icon="solar:bill-list-linear"
                                                                                class="text-secondary fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Invoice App</h6>
                                                                            <span class="fs-11 d-block text-muted">Get
                                                                                latest invoice</span>
                                                                        </div>
                                                                    </a>
                                                                    <a href="app-contact2.html"
                                                                        class="d-flex align-items-center pb-9 position-relative">
                                                                        <div
                                                                            class="bg-warning-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon
                                                                                icon="solar:bedside-table-2-linear"
                                                                                class="text-warning fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Contact Application</h6>
                                                                            <span class="fs-11 d-block text-muted">2
                                                                                Unsaved Contacts</span>
                                                                        </div>
                                                                    </a>
                                                                    <a href="app-email.html"
                                                                        class="d-flex align-items-center position-relative">
                                                                        <div
                                                                            class="bg-danger-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon
                                                                                icon="solar:letter-unread-linear"
                                                                                class="text-danger fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Email App</h6>
                                                                            <span class="fs-11 d-block text-muted">Get
                                                                                new emails</span>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="position-relative">
                                                                    <a href="page-user-profile.html"
                                                                        class="d-flex align-items-center pb-9 position-relative">
                                                                        <div
                                                                            class="bg-success-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon
                                                                                icon="solar:cart-large-2-linear"
                                                                                class="text-success fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">User Profile</h6>
                                                                            <span class="fs-11 d-block text-muted">learn
                                                                                more information</span>
                                                                        </div>
                                                                    </a>
                                                                    <a href="app-calendar.html"
                                                                        class="d-flex align-items-center pb-9 position-relative">
                                                                        <div
                                                                            class="bg-primary-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon icon="solar:calendar-linear"
                                                                                class="text-primary fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Calendar App</h6>
                                                                            <span class="fs-11 d-block text-muted">Get
                                                                                dates</span>
                                                                        </div>
                                                                    </a>
                                                                    <a href="app-contact.html"
                                                                        class="d-flex align-items-center pb-9 position-relative">
                                                                        <div
                                                                            class="bg-secondary-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon
                                                                                icon="solar:bedside-table-linear"
                                                                                class="text-secondary fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Contact List Table</h6>
                                                                            <span class="fs-11 d-block text-muted">Add
                                                                                new contact</span>
                                                                        </div>
                                                                    </a>
                                                                    <a href="app-notes.html"
                                                                        class="d-flex align-items-center position-relative">
                                                                        <div
                                                                            class="bg-warning-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon icon="solar:palette-linear"
                                                                                class="text-warning fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Notes Application</h6>
                                                                            <span class="fs-11 d-block text-muted">To-do
                                                                                and Daily tasks</span>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row align-items-center border-top">
                                                        <div class="col-8">
                                                            <div class="ps-3 py-3">
                                                                <a class="text-dark d-flex align-items-center lh-1 fs-3"
                                                                    href="javascript:void(0)">
                                                                    <i class="ti ti-help fs-5 me-2"></i>Frequently Asked
                                                                    Questions
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="d-flex justify-content-end pe-2 py-3">
                                                                <button class="btn btn-primary">Check</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4 ms-n3">
                                                    <div class="position-relative p-3 border-start h-100">
                                                        <h5 class="fs-5 mb-9 fw-semibold">Quick Links</h5>
                                                        <ul>
                                                            <li class="mb-3">
                                                                <a class="fs-3" href="page-pricing.html">Pricing
                                                                    Page</a>
                                                            </li>
                                                            <li class="mb-3">
                                                                <a class="fs-3"
                                                                    href="authentication-login.html">Authentication
                                                                    Design</a>
                                                            </li>
                                                            <li class="mb-3">
                                                                <a class="fs-3"
                                                                    href="authentication-register.html">Register Now</a>
                                                            </li>
                                                            <li class="mb-3">
                                                                <a class="fs-3" href="authentication-error.html">404
                                                                    Error Page</a>
                                                            </li>
                                                            <li class="mb-3">
                                                                <a class="fs-3" href="app-notes.html">Notes App</a>
                                                            </li>
                                                            <li class="mb-3">
                                                                <a class="fs-3" href="page-user-profile.html">User
                                                                    Application</a>
                                                            </li>
                                                            <li class="mb-3">
                                                                <a class="fs-3"
                                                                    href="page-account-settings.html">Account
                                                                    Settings</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="d-block d-xl-none">
                            <a href="<?= base_url('/') ?>" class="text-nowrap nav-link">
                                <img src="<?= base_url('assets/images/logos/light-logo.png') ?>" alt="Logo" />
                            </a>
                        </div>
                        <ul class="navbar-nav navbar-toggler p-0 border-0">
                            <li class="nav-item nav-icon-hover-bg dark rounded-circle d-flex">
                                <a class="nav-link rounded-circle" href="javascript:void(0)" data-bs-toggle="collapse"
                                    data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                                    aria-label="Toggle navigation">
                                    <iconify-icon icon="solar:menu-dots-bold-duotone" class="fs-6"></iconify-icon>
                                </a>
                            </li>
                        </ul>
                        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                            <div class="d-flex align-items-center justify-content-between px-0 px-xl-8">
                                <ul
                                    class="navbar-nav flex-row mx-auto ms-lg-auto align-items-center justify-content-center">
                                    <li class="nav-item dropdown">
                                        <a href="javascript:void(0)"
                                            class="nav-link nav-icon-hover-bg rounded-circle d-flex d-lg-none align-items-center justify-content-center"
                                            type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilenavbar"
                                            aria-controls="offcanvasWithBothOptions">
                                            <iconify-icon icon="solar:sort-line-duotone" class="fs-6"></iconify-icon>
                                        </a>
                                    </li>
                                    <?= view('partials/asistencia/searchTrigger') ?>
                                    <li class="nav-item">
                                        <a class="nav-link nav-icon-hover-bg rounded-circle moon dark-layout"
                                            href="javascript:void(0)">
                                            <iconify-icon icon="solar:moon-line-duotone"
                                                class="moon fs-6"></iconify-icon>
                                        </a>
                                        <a class="nav-link nav-icon-hover-bg rounded-circle sun light-layout"
                                            href="javascript:void(0)" style="display: none">
                                            <iconify-icon icon="solar:sun-2-line-duotone"
                                                class="sun fs-6"></iconify-icon>
                                        </a>
                                    </li>
                                    <li class="nav-item d-block d-xl-none">
                                        <a class="nav-link nav-icon-hover-bg rounded-circle" href="javascript:void(0)"
                                            data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            <iconify-icon icon="solar:magnifer-line-duotone"
                                                class="fs-6"></iconify-icon>
                                        </a>
                                    </li>

                                    <!-- ------------------------------- -->
                                    <!-- start language Dropdown -->
                                    <!-- ------------------------------- -->
                                    <li class="nav-item dropdown nav-icon-hover-bg rounded-circle">
                                        <a class="nav-link" href="javascript:void(0)" id="drop2" aria-expanded="false">
                                            <img src="<?= base_url('assets/images/flag/icon-flag-en.svg') ?>"
                                                alt="monster-img" width="20px" height="20px"
                                                class="rounded-circle object-fit-cover round-20" />
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up overflow-hidden"
                                            aria-labelledby="drop2">
                                            <div class="message-body">
                                                <a href="javascript:void(0)"
                                                    class="d-flex align-items-center gap-2 py-3 px-4 dropdown-item">
                                                    <div class="position-relative">
                                                        <img src="<?= base_url('assets/images/flag/icon-flag-en.svg') ?>"
                                                            alt="monster-img" width="20px" height="20px"
                                                            class="rounded-circle object-fit-cover round-20" />
                                                    </div>
                                                    <p class="mb-0 fs-3">English (UK)</p>
                                                </a>
                                                <a href="javascript:void(0)"
                                                    class="d-flex align-items-center gap-2 py-3 px-4 dropdown-item">
                                                    <div class="position-relative">
                                                        <img src="<?= base_url('assets/images/flag/icon-flag-cn.svg') ?>"
                                                            alt="monster-img" width="20px" height="20px"
                                                            class="rounded-circle object-fit-cover round-20" />
                                                    </div>
                                                    <p class="mb-0 fs-3">中国人 (Chinese)</p>
                                                </a>
                                                <a href="javascript:void(0)"
                                                    class="d-flex align-items-center gap-2 py-3 px-4 dropdown-item">
                                                    <div class="position-relative">
                                                        <img src="<?= base_url('assets/images/flag/icon-flag-fr.svg') ?>"
                                                            alt="monster-img" width="20px" height="20px"
                                                            class="rounded-circle object-fit-cover round-20" />
                                                    </div>
                                                    <p class="mb-0 fs-3">français (French)</p>
                                                </a>
                                                <a href="javascript:void(0)"
                                                    class="d-flex align-items-center gap-2 py-3 px-4 dropdown-item">
                                                    <div class="position-relative">
                                                        <img src="<?= base_url('assets/images/flag/icon-flag-sa.svg') ?>"
                                                            alt="monster-img" width="20px" height="20px"
                                                            class="rounded-circle object-fit-cover round-20" />
                                                    </div>
                                                    <p class="mb-0 fs-3">عربي (Arabic)</p>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                    <!-- ------------------------------- -->
                                    <!-- end language Dropdown -->
                                    <!-- ------------------------------- -->

                                    <!-- ------------------------------- -->
                                    <?= view('partials/asistencia/userDropdown', ['perfilAsistencia' => $perfilAsistencia, 'orientacion' => 'horizontal']) ?>
                                    <!-- ------------------------------- -->
                                </ul>
                            </div>
                        </div>
                    </nav>

                </div>
            </header>
            <!--  Header End -->

            <aside class="left-sidebar with-horizontal">
                <!-- Sidebar scroll-->
                <div>
                    <!-- Sidebar navigation-->
                    <nav id="sidebarnavh" class="sidebar-nav scroll-sidebar container-fluid">
                        <ul id="sidebarnav">
                            <!-- ============================= -->
                            <!-- Home -->
                            <!-- ============================= -->
                            <li class="nav-small-cap">
                                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                                <span class="hide-menu">Home</span>
                            </li>
                            <?= view('partials/menuHorizontalLayout', ['menu' => $menuAsistencia]) ?>


                        </ul>
                    </nav>
                    <!-- End Sidebar navigation -->
                </div>
                <!-- End Sidebar scroll-->
            </aside>

            <div class="body-wrapper">
                <div class="container-fluid content-main mw-100">
                    <?= $this->renderSection('content'); ?>
                </div>
            </div>
            <button
                class="btn btn-primary p-3 rounded-circle d-flex align-items-center justify-content-center customizer-btn"
                type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample"
                aria-controls="offcanvasExample">
                <i class="icon ti ti-settings fs-7"></i>
            </button>

            <div class="offcanvas customizer offcanvas-end" tabindex="-1" id="offcanvasExample"
                aria-labelledby="offcanvasExampleLabel">
                <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                    <h4 class="offcanvas-title fw-semibold" id="offcanvasExampleLabel">
                        Configuración
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body" data-simplebar style="height: calc(100vh - 80px)">
                    <div class="rounded border p-3 mb-4">
                        <p class="small mb-2">Tu apariencia se guarda automáticamente en tu perfil.</p>
                        <p id="appearance-save-status" class="small text-muted mb-2" role="status" aria-live="polite"><?= $aparienciaDisponible ? 'Preferencias cargadas.' : 'No se pudo cargar tu apariencia. Intenta guardar nuevamente.' ?></p>
                        <button type="button" id="appearance-retry" class="btn btn-sm btn-outline-primary" <?= $aparienciaDisponible ? 'hidden' : '' ?>>Reintentar guardado</button>
                        <button type="button" id="appearance-reset" class="btn btn-sm btn-outline-secondary">Restablecer apariencia</button>
                    </div>
                    <h6 class="fw-semibold fs-4 mb-2">Tema</h6>

                    <div class="d-flex flex-row gap-3 customizer-box" role="group">
                        <input type="radio" class="btn-check light-layout" name="theme-layout" id="light-layout"
                            autocomplete="off" />
                        <label class="btn p-9 btn-outline-primary rounded-2" for="light-layout">
                            <i class="icon ti ti-brightness-up fs-7 me-2"></i>Claro
                        </label>

                        <input type="radio" class="btn-check dark-layout" name="theme-layout" id="dark-layout"
                            autocomplete="off" />
                        <label class="btn p-9 btn-outline-primary rounded-2" for="dark-layout">
                            <i class="icon ti ti-moon fs-7 me-2"></i>Oscuro
                        </label>
                    </div>

                    <h6 class="mt-5 fw-semibold fs-4 mb-2">Orientacion del Tema</h6>
                    <div class="d-flex flex-row gap-3 customizer-box" role="group">
                        <input type="radio" class="btn-check" name="direction-l" id="ltr-layout" autocomplete="off" />
                        <label class="btn p-9 btn-outline-primary rounded-2" for="ltr-layout">
                            <i class="icon ti ti-text-direction-ltr fs-7 me-2"></i>LTR
                        </label>

                        <input type="radio" class="btn-check" name="direction-l" id="rtl-layout" autocomplete="off" />
                        <label class="btn p-9 btn-outline-primary rounded-2" for="rtl-layout">
                            <i class="icon ti ti-text-direction-rtl fs-7 me-2"></i>RTL
                        </label>
                    </div>

                    <h6 class="mt-5 fw-semibold fs-4 mb-2">Color de Tema</h6>

                    <div class="d-flex flex-row flex-wrap gap-3 customizer-box color-pallete" role="group">
                        <input type="radio" class="btn-check" name="color-theme-layout" id="Blue_Theme"
                            autocomplete="off" />
                        <label
                            class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center"
                            onclick="handleColorTheme('Blue_Theme')" for="Blue_Theme" data-bs-toggle="tooltip"
                            data-bs-placement="top" data-bs-title="BLUE_THEME">
                            <div
                                class="color-box rounded-circle d-flex align-items-center justify-content-center skin-1">
                                <i class="ti ti-check text-white d-flex icon fs-5"></i>
                            </div>
                        </label>

                        <input type="radio" class="btn-check" name="color-theme-layout" id="Aqua_Theme"
                            autocomplete="off" />
                        <label
                            class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center"
                            onclick="handleColorTheme('Aqua_Theme')" for="Aqua_Theme" data-bs-toggle="tooltip"
                            data-bs-placement="top" data-bs-title="AQUA_THEME">
                            <div
                                class="color-box rounded-circle d-flex align-items-center justify-content-center skin-2">
                                <i class="ti ti-check text-white d-flex icon fs-5"></i>
                            </div>
                        </label>

                        <input type="radio" class="btn-check" name="color-theme-layout" id="Purple_Theme"
                            autocomplete="off" />
                        <label
                            class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center"
                            onclick="handleColorTheme('Purple_Theme')" for="Purple_Theme" data-bs-toggle="tooltip"
                            data-bs-placement="top" data-bs-title="PURPLE_THEME">
                            <div
                                class="color-box rounded-circle d-flex align-items-center justify-content-center skin-3">
                                <i class="ti ti-check text-white d-flex icon fs-5"></i>
                            </div>
                        </label>

                        <input type="radio" class="btn-check" name="color-theme-layout" id="Green_Theme"
                            autocomplete="off" />
                        <label
                            class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center"
                            onclick="handleColorTheme('Green_Theme')" for="Green_Theme" data-bs-toggle="tooltip"
                            data-bs-placement="top" data-bs-title="GREEN_THEME">
                            <div
                                class="color-box rounded-circle d-flex align-items-center justify-content-center skin-4">
                                <i class="ti ti-check text-white d-flex icon fs-5"></i>
                            </div>
                        </label>

                        <input type="radio" class="btn-check" name="color-theme-layout" id="Cyan_Theme"
                            autocomplete="off" />
                        <label
                            class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center"
                            onclick="handleColorTheme('Cyan_Theme')" for="Cyan_Theme" data-bs-toggle="tooltip"
                            data-bs-placement="top" data-bs-title="CYAN_THEME">
                            <div
                                class="color-box rounded-circle d-flex align-items-center justify-content-center skin-5">
                                <i class="ti ti-check text-white d-flex icon fs-5"></i>
                            </div>
                        </label>

                        <input type="radio" class="btn-check" name="color-theme-layout" id="Orange_Theme"
                            autocomplete="off" />
                        <label
                            class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center"
                            onclick="handleColorTheme('Orange_Theme')" for="Orange_Theme"
                            data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="ORANGE_THEME">
                            <div
                                class="color-box rounded-circle d-flex align-items-center justify-content-center skin-6">
                                <i class="ti ti-check text-white d-flex icon fs-5"></i>
                            </div>
                        </label>
                    </div>

                    <h6 class="mt-5 fw-semibold fs-4 mb-2">Tipo de Menu</h6>
                    <div class="d-flex flex-row gap-3 customizer-box" role="group">
                        <div>
                            <input type="radio" class="btn-check" name="page-layout" id="vertical-layout"
                                autocomplete="off" />
                            <label class="btn p-9 btn-outline-primary rounded-2" for="vertical-layout">
                                <i class="icon ti ti-layout-sidebar-right fs-7 me-2"></i>Vertical
                            </label>
                        </div>
                        <div>
                            <input type="radio" class="btn-check" name="page-layout" id="horizontal-layout"
                                autocomplete="off" />
                            <label class="btn p-9 btn-outline-primary rounded-2" for="horizontal-layout">
                                <i class="icon ti ti-layout-navbar fs-7 me-2"></i>Horizontal
                            </label>
                        </div>
                    </div>

                    <h6 class="mt-5 fw-semibold fs-4 mb-2">Modo de Presentación</h6>

                    <div class="d-flex flex-row gap-3 customizer-box" role="group">
                        <input type="radio" class="btn-check" name="layout" id="boxed-layout" autocomplete="off" />
                        <label class="btn p-9 btn-outline-primary rounded-2" for="boxed-layout">
                            <i class="icon ti ti-layout-distribute-vertical fs-7 me-2"></i>Ajustado
                        </label>

                        <input type="radio" class="btn-check" name="layout" id="full-layout" autocomplete="off" />
                        <label class="btn p-9 btn-outline-primary rounded-2" for="full-layout">
                            <i class="icon ti ti-layout-distribute-horizontal fs-7 me-2"></i>Completo
                        </label>
                    </div>

                    <h6 class="fw-semibold fs-4 mb-2 mt-5">Barra Lateral</h6>
                    <div class="d-flex flex-row gap-3 customizer-box" role="group">
                        <a href="javascript:void(0)" class="fullsidebar">
                            <input type="radio" class="btn-check" name="sidebar-type" id="full-sidebar"
                                autocomplete="off" />
                            <label class="btn p-9 btn-outline-primary rounded-2" for="full-sidebar">
                                <i class="icon ti ti-layout-sidebar-right fs-7 me-2"></i>Abierto
                            </label>
                        </a>
                        <div>
                            <input type="radio" class="btn-check" name="sidebar-type" id="mini-sidebar"
                                autocomplete="off" />
                            <label class="btn p-9 btn-outline-primary rounded-2" for="mini-sidebar">
                                <i class="icon ti ti-layout-sidebar fs-7 me-2"></i>Cerrado
                            </label>
                        </div>
                    </div>

                    <h6 class="mt-5 fw-semibold fs-4 mb-2">Cards con</h6>

                    <div class="d-flex flex-row gap-3 customizer-box" role="group">
                        <input type="radio" class="btn-check" name="card-layout" id="card-with-border"
                            autocomplete="off" />
                        <label class="btn p-9 btn-outline-primary rounded-2" for="card-with-border">
                            <i class="icon ti ti-border-outer fs-7 me-2"></i>Borde
                        </label>

                        <input type="radio" class="btn-check" name="card-layout" id="card-without-border"
                            autocomplete="off" />
                        <label class="btn p-9 btn-outline-primary rounded-2" for="card-without-border">
                            <i class="icon ti ti-border-none fs-7 me-2"></i>Sombra
                        </label>
                    </div>
                </div>
            </div>

            <script>
                function handleColorTheme(e) {
                    document.documentElement.setAttribute("data-color-theme", e);
                }
            </script>
        </div>

        <?= view('partials/asistencia/sessionModals', ['perfilAsistencia' => $perfilAsistencia, 'opcionesAsistencia' => $opcionesAsistencia]) ?>
    </div>

    <div class="dark-transparent sidebartoggler"></div>
    <!-- Import Js Files -->
    <script src="<?= base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/libs/simplebar/dist/simplebar.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/theme/app.init.js') ?>"></script>
    <script>
        userSettings = <?= json_encode($aparienciaAsistencia, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    </script>
    <script src="<?= base_url('assets/js/theme/theme.js') ?>"></script>
    <script src="<?= base_url('assets/js/theme/app.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/theme/sidebarmenu.js') ?>"></script>

    <!-- solar icons -->
    <script src="<?= base_url('assets/js/iconify-icon%401.0.8/dist/iconify-icon.min.js') ?>"></script>
    <script src="<?= base_url('assets/libs/apexcharts/dist/apexcharts.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/dashboards/dashboard.js') ?>"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script src="<?= base_url('assets/libs/sweetalert2/dist/sweetalert2.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/forms/sweet-alert.init.js') ?>"></script>
    <script src="<?= base_url('assets/libs/datatables.net/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/plugins/toastr-init.js') ?>"></script>
    <!-- DataTables Buttons Extension -->
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

    <!-- pdfMake -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="<?= base_url('assets/libs/select2/dist/js/select2.full.min.js') ?>"></script>
    <script src="<?= base_url('assets/libs/select2/dist/js/select2.min.js') ?>"></script>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.20/index.global.min.js'></script>
    <script src="<?= base_url('assets/js/notification.js') ?>"></script>

    <script src="<?= base_url('assets/libs/jquery-steps/build/jquery.steps.min.js') ?>"></script>
    <script src="<?= base_url('assets/libs/jquery-validation/dist/jquery.validate.min.js') ?>"></script>

    <script>
        let URLNotification = "<?= base_url('') ?>";
    </script>
    <script>
        let connectionCheckInterval;
        let isOffline = false;

        function checkInternetConnection() {
            if (!navigator.onLine) {
                if (!isOffline) {
                    isOffline = true;
                    Swal.fire({
                        icon: 'warning',
                        title: '<?= lang('Main.titleInternetError') ?>',
                        text: '<?= lang('Main.bodyInternetError') ?>',
                        //confirmButtonText: 'OK'
                    });
                    clearInterval(connectionCheckInterval);
                    connectionCheckInterval = setInterval(checkInternetConnection, 10000); // Revisar cada 20 segundos
                }
            } else {
                if (isOffline) {
                    isOffline = false;
                    Swal.fire({
                        icon: 'success',
                        title: '<?= lang('Main.titleInternetOk') ?>',
                        text: '<?= lang('Main.titleInternetOk') ?>',
                        //confirmButtonText: 'OK'
                    });
                    clearInterval(connectionCheckInterval);
                    connectionCheckInterval = setInterval(checkInternetConnection, 30000); // Volver a revisar cada minuto
                }
            }
        }

        $(document).ready(function() {
            connectionCheckInterval = setInterval(checkInternetConnection, 30000); // Revisar cada minuto

            // Ajuste automático de z-index para modales anidados
            $(document).on('show.bs.modal', '.modal', function() {
                const zIndex = 1040 + (10 * $('.modal:visible').length);
                $(this).css('z-index', zIndex);
                setTimeout(function() {
                    $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
                }, 0);
            });

            // Restaurar scroll al cerrar el modal superior
            $(document).on('hidden.bs.modal', '.modal', function() {
                if ($('.modal:visible').length > 0) {
                    setTimeout(function() {
                        $(document.body).addClass('modal-open');
                    }, 0);
                }
            });
        });

        toastr.info('<?= lang('Main.welcomeMessage'); ?>', '<?= lang('Main.welcomeDecription'); ?>');
    </script>
    <script type="application/json" id="user-appearance-config">
        <?= json_encode(['settings' => $aparienciaAsistencia, 'available' => $aparienciaDisponible, 'url' => base_url('perfil/preferencias'), 'csrf' => ['header' => csrf_header(), 'name' => csrf_token(), 'hash' => csrf_hash()]], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>
    </script>
    <script src="<?= base_url('assets/js/asistencia/preferences.js') ?>"></script>
    <script src="<?= base_url('assets/js/asistencia/layout.js') ?>"></script>
    <?= $this->renderSection('pageScripts'); ?>
</body>

</html>