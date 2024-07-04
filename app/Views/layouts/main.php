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
</head>

<body>
    <!-- Toast -->
    <div class="toast toast-onload align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body hstack align-items-start gap-6">
            <i class="ti ti-alert-circle fs-6"></i>
            <div>
                <h5 class="text-white fs-3 mb-1"><?= lang('Main.welcomeMessage'); ?></h5>
                <h6 class="text-white fs-2 mb-0">
                    <?= lang('welcomeDecription'); ?>
                </h6>
            </div>
            <button type="button" class="btn-close btn-close-white fs-2 m-0 ms-auto shadow-none" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    <!-- Preloader -->
    <div class="preloader">
        <img src="../assets/images/logos/favicon.png" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <div id="main-wrapper">

        <!-- Sidebar Start -->
        <aside class="left-sidebar with-vertical">
            <!-- ---------------------------------- -->
            <!-- Start Vertical Layout Sidebar -->
            <!-- ---------------------------------- -->

            <div>
                <div class="brand-logo d-flex align-items-center">
                    <a href="index.html" class="text-nowrap logo-img">
                        <img src="../assets/images/logos/dark-logo.svg" alt="Logo" class="dark-logo" />
                        <img src="../assets/images/logos/light-logo.svg" alt="Logo" class="light-logo" />
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
                            <div class="user-profile text-center position-relative pt-4 mt-1">
                                <!-- User profile image -->
                                <div class="profile-img m-auto">
                                    <img src="../assets/images/profile/user-1.jpg" alt="user" class="w-100 rounded-circle" />
                                </div>

                                <!-- User profile text-->
                                <div class="profile-text py-2 dropdown-center hide-menu">
                                    <a href="javascript:void(0)" class="dropdown-toggle link u-dropdown" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"> <?= $this->renderSection('username'); ?><span class="caret"></span>
                                    </a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item d-flex align-items-center gap-2" href="javascript:void(0)">
                                            <iconify-icon icon="solar:user-linear" class="fs-5 text-primary"></iconify-icon>
                                            <?= lang('Main.myProfile') ?>
                                        </a>
                                        <a class="dropdown-item d-flex align-items-center gap-2" href="javascript:void(0)">
                                            <iconify-icon icon="solar:inbox-linear" class="fs-5 text-primary"></iconify-icon>
                                            <?= lang('Main.myImbox') ?>
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item d-flex align-items-center gap-2" href="javascript:void(0)">
                                            <iconify-icon icon="solar:settings-linear" class="fs-5 text-primary"></iconify-icon>
                                            <?= lang('Main.profileSettings') ?>
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item d-flex align-items-center gap-2" href="javascript:void(0)">
                                            <iconify-icon icon="solar:login-2-linear" class="fs-5 text-primary"></iconify-icon>
                                            <?= lang('Main.logout') ?>
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <div class="p-2">
                                            <button type="button" class="btn d-block w-100 btn-info">
                                                <?= lang('Main.myProfile') ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End User profile text-->
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="<?= base_url('/') ?>#" aria-expanded="false">
                                <iconify-icon icon="solar:home-linear"></iconify-icon>
                                <span class="hide-menu"><?= lang('Main.dashboard') ?></span>
                            </a>
                        </li>
                        <!-- Menu Vertical -->
                        <?= $this->renderSection('menuVertical'); ?>
                        <?= $this->renderSection('menuHorizontal'); ?>

                        <!-- User Profile-->
                        <!-- ---------------------------------- -->
                        <!-- Home -->
                        <!-- ---------------------------------- -->
                        <li class="nav-small-cap">
                            <iconify-icon icon="solar:menu-dots-linear" class="mini-icon"></iconify-icon>
                            <span class="hide-menu">Personal</span>
                        </li>
                        <!-- ---------------------------------- -->
                        <!-- Dashboard -->
                        <!-- ---------------------------------- -->
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="#" id="get-url" aria-expanded="false">
                                <iconify-icon icon="solar:screencast-2-linear"></iconify-icon>
                                <span class="hide-menu">Modern</span>
                            </a>
                        </li>


                        <li class="sidebar-item">
                            <a class="sidebar-link" href="index2.html" aria-expanded="false">
                                <iconify-icon icon="solar:atom-linear"></iconify-icon>
                                <span class="hide-menu">Awesome</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="index3.html" aria-expanded="false">
                                <iconify-icon icon="solar:box-minimalistic-linear"></iconify-icon>
                                <span class="hide-menu">Classy</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="index4.html" aria-expanded="false">
                                <iconify-icon icon="solar:buildings-2-linear"></iconify-icon>
                                <span class="hide-menu">Analytical</span>
                            </a>
                        </li>

                    </ul>
                </nav>

                <div class="sidebar-footer hide-menu">
                    <!-- item-->
                    <a href="page-account-settings.html" class="link" data-bs-toggle="tooltip" data-bs-placement="top" title="Settings"><iconify-icon icon="solar:settings-linear"></iconify-icon></a>
                    <!-- item-->
                    <a href="app-email.html" class="link" data-bs-toggle="tooltip" data-bs-placement="top" title="Email"><iconify-icon icon="solar:inbox-linear"></iconify-icon></a>
                    <!-- item-->
                    <a href="<?= base_url('logout') ?>" class="link" data-bs-toggle="tooltip" data-bs-placement="top" title="Logout"><iconify-icon icon="solar:power-bold"></iconify-icon></a>
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
                                <a class="nav-link position-relative" href="javascript:void(0)" id="drop2" aria-expanded="false">
                                    <iconify-icon icon="solar:bell-bing-line-duotone" class="fs-6"></iconify-icon>
                                    <div class="notify">
                                        <span class="heartbit"></span>
                                        <span class="point"></span>
                                    </div>
                                </a>
                                <div class="dropdown-menu content-dd dropdown-menu-animate-up" aria-labelledby="drop2">
                                    <div class="py-3 px-4 border-bottom">
                                        <h5 class="mb-0 fs-4 fw-normal">Notifications</h5>
                                    </div>
                                    <div class="message-body" data-simplebar>
                                        <a href="javascript:void(0)" class="p-3 pe-0 border-bottom d-flex align-items-center dropdown-item gap-3">
                                            <span class="flex-shrink-0 bg-danger-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-danger">
                                                <iconify-icon icon="solar:widget-3-line-duotone"></iconify-icon>
                                            </span>
                                            <div class="w-75 d-inline-block ">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 fw-semibold">Launch Admin</h6>
                                                    <span class="d-block fs-2 text-body-color">9:30 AM</span>
                                                </div>
                                                <span class="d-block text-truncate text-truncate fs-11 text-body-color">Just see the my new
                                                    admin!</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)" class="p-3 pe-0 border-bottom d-flex align-items-center dropdown-item gap-3">
                                            <span class="flex-shrink-0 bg-primary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-primary">
                                                <iconify-icon icon="solar:calendar-line-duotone"></iconify-icon>
                                            </span>
                                            <div class="w-75 d-inline-block ">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 fw-semibold">Event today</h6>
                                                    <span class="d-block fs-2 text-body-color">9:15 AM</span>
                                                </div>
                                                <span class="d-block text-truncate text-truncate fs-11 text-body-color">Just a reminder that you
                                                    have event</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)" class="p-3 pe-0 border-bottom d-flex align-items-center dropdown-item gap-3">
                                            <span class="flex-shrink-0 bg-secondary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-secondary">
                                                <iconify-icon icon="solar:settings-line-duotone"></iconify-icon>
                                            </span>
                                            <div class="w-75 d-inline-block ">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 fw-semibold">Settings</h6>
                                                    <span class="d-block fs-2 text-body-color">4:36 PM</span>
                                                </div>
                                                <span class="d-block text-truncate text-truncate fs-11 text-body-color">You can customize this
                                                    template as you want</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)" class="p-3 pe-0 border-bottom d-flex align-items-center dropdown-item gap-3">
                                            <span class="flex-shrink-0 bg-warning-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-warning">
                                                <iconify-icon icon="solar:widget-4-line-duotone"></iconify-icon>
                                            </span>
                                            <div class="w-75 d-inline-block ">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 fw-semibold">Launch Admin</h6>
                                                    <span class="d-block fs-2 text-body-color">9:30 AM</span>
                                                </div>
                                                <span class="d-block text-truncate text-truncate fs-11 text-body-color">Just see the my new
                                                    admin!</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)" class="p-3 pe-0 border-bottom d-flex align-items-center dropdown-item gap-3">
                                            <span class="flex-shrink-0 bg-primary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-primary">
                                                <iconify-icon icon="solar:calendar-line-duotone"></iconify-icon>
                                            </span>
                                            <div class="w-75 d-inline-block ">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 fw-semibold">Event today</h6>
                                                    <span class="d-block fs-2 text-body-color">9:15 AM</span>
                                                </div>
                                                <span class="d-block text-truncate text-truncate fs-11 text-body-color">Just a reminder that you
                                                    have event</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)" class="p-3 pe-0 border-bottom d-flex align-items-center dropdown-item gap-3">
                                            <span class="flex-shrink-0 bg-secondary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-secondary">
                                                <iconify-icon icon="solar:settings-line-duotone"></iconify-icon>
                                            </span>
                                            <div class="w-75 d-inline-block ">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 fw-semibold">Settings</h6>
                                                    <span class="d-block fs-2 text-body-color">4:36 PM</span>
                                                </div>
                                                <span class="d-block text-truncate text-truncate fs-11 text-body-color">You can customize this
                                                    template as you want</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div>
                                        <a class="d-flex align-items-center pt-3 pb-2 justify-content-center link-primary text-dark" href="javascript:void(0);">
                                            <span class="fw-semibold">Check all notifications</span>
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
                                <a class="nav-link position-relative" href="javascript:void(0)" id="drop2" aria-expanded="false">
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
                                        <a href="javascript:void(0)" class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                            <span class="user-img position-relative d-inline-block">
                                                <img src="../assets/images/profile/user-5.jpg" alt="user" class="rounded-circle w-100 round-40" />
                                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-warning border border-light rounded-circle">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                            </span>
                                            <div class="w-75 d-inline-block">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 lh-base">Mathew Anderson</h6>
                                                    <span class="fs-2 text-nowrap d-block text-body-color">9:30 AM</span>
                                                </div>
                                                <span class="fs-2 d-block text-truncate text-truncate text-body-color">Just see the my new
                                                    admin!</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)" class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                            <span class="user-img position-relative d-inline-block">
                                                <img src="../assets/images/profile/user-3.jpg" alt="user" class="rounded-circle w-100 round-40" />
                                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                            </span>
                                            <div class="w-75 d-inline-block">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 lh-base">Bianca Anderson</h6>
                                                    <span class="fs-2 text-nowrap d-block text-body-color">9:10 AM</span>
                                                </div>

                                                <span class="fs-2 d-block text-truncate text-truncate text-body-color">Just a reminder that you
                                                    have event</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)" class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                            <span class="user-img position-relative d-inline-block">
                                                <img src="../assets/images/profile/user-6.jpg" alt="user" class="rounded-circle w-100 round-40" />
                                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                            </span>
                                            <div class="w-75 d-inline-block">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 lh-base">Andrew Johnson</h6>
                                                    <span class="fs-2 text-nowrap d-block text-body-color">9:08 AM</span>
                                                </div>
                                                <span class="fs-2 d-block text-truncate text-truncate text-body-color">You can customize this
                                                    template as you want</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)" class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                            <span class="user-img position-relative d-inline-block">
                                                <img src="../assets/images/profile/user-7.jpg" alt="user" class="rounded-circle w-100 round-40" />
                                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                                </button>
                                            </span>
                                            <div class="w-75 d-inline-block">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 lh-base">Mark Strokes</h6>
                                                    <span class="fs-2 text-nowrap d-block text-body-color">9:30 AM</span>
                                                </div>
                                                <span class="fs-2 d-block text-truncate text-truncate text-body-color">Just see the my new
                                                    admin!</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)" class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                            <span class="user-img position-relative d-inline-block">
                                                <img src="../assets/images/profile/user-8.jpg" alt="user" class="rounded-circle w-100 round-40" />
                                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                            </span>
                                            <div class="w-75 d-inline-block">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 lh-base">Mark, Stoinus & Rishvi..</h6>
                                                    <span class="fs-2 text-nowrap d-block text-body-color">9:10 AM</span>
                                                </div>
                                                <span class="fs-2 d-block text-truncate text-truncate text-body-color">Just a reminder that you
                                                    have event</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)" class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                            <span class="user-img position-relative d-inline-block">
                                                <img src="../assets/images/profile/user-9.jpg" alt="user" class="rounded-circle w-100 round-40" />
                                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-warning border border-light rounded-circle">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                            </span>
                                            <div class="w-75 d-inline-block">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 lh-base">Settings</h6>
                                                    <span class="fs-2 text-nowrap d-block text-body-color">9:08 AM</span>
                                                </div>
                                                <span class="fs-2 d-block text-truncate text-truncate text-body-color">You can customize this
                                                    template as you want</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div>
                                        <a class="d-flex align-items-center pt-3 pb-2 justify-content-center link-primary text-dark" href="javascript:void(0);">
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
                                    <a class="nav-link" id="drop2" href="javascript:void(0)" aria-haspopup="true" aria-expanded="false">
                                        <iconify-icon icon="solar:widget-3-line-duotone" class="fs-6"></iconify-icon>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-nav dropdown-menu-animate-up py-0 overflow-hidden" aria-labelledby="drop2">
                                        <div class="position-relative">
                                            <div class="row">
                                                <div class="col-8">
                                                    <div class="p-4 pb-3">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <div class="position-relative">
                                                                    <a href="app-chat.html" class="d-flex align-items-center pb-9 position-relative">
                                                                        <div class="bg-primary-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon icon="solar:chat-line-linear" class="text-primary fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Chat Application</h6>
                                                                            <span class="fs-11 d-block text-muted">New messages arrived</span>
                                                                        </div>
                                                                    </a>
                                                                    <a href="app-invoice.html" class="d-flex align-items-center pb-9 position-relative">
                                                                        <div class="bg-secondary-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon icon="solar:bill-list-linear" class="text-secondary fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Invoice App</h6>
                                                                            <span class="fs-11 d-block text-muted">Get latest invoice</span>
                                                                        </div>
                                                                    </a>
                                                                    <a href="app-contact2.html" class="d-flex align-items-center pb-9 position-relative">
                                                                        <div class="bg-warning-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon icon="solar:bedside-table-2-linear" class="text-warning fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Contact Application</h6>
                                                                            <span class="fs-11 d-block text-muted">2 Unsaved Contacts</span>
                                                                        </div>
                                                                    </a>
                                                                    <a href="app-email.html" class="d-flex align-items-center position-relative">
                                                                        <div class="bg-danger-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon icon="solar:letter-unread-linear" class="text-danger fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Email App</h6>
                                                                            <span class="fs-11 d-block text-muted">Get new emails</span>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="position-relative">
                                                                    <a href="page-user-profile.html" class="d-flex align-items-center pb-9 position-relative">
                                                                        <div class="bg-success-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon icon="solar:cart-large-2-linear" class="text-success fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">User Profile</h6>
                                                                            <span class="fs-11 d-block text-muted">learn more information</span>
                                                                        </div>
                                                                    </a>
                                                                    <a href="app-calendar.html" class="d-flex align-items-center pb-9 position-relative">
                                                                        <div class="bg-primary-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon icon="solar:calendar-linear" class="text-primary fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Calendar App</h6>
                                                                            <span class="fs-11 d-block text-muted">Get dates</span>
                                                                        </div>
                                                                    </a>
                                                                    <a href="app-contact.html" class="d-flex align-items-center pb-9 position-relative">
                                                                        <div class="bg-secondary-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon icon="solar:bedside-table-linear" class="text-secondary fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Contact List Table</h6>
                                                                            <span class="fs-11 d-block text-muted">Add new contact</span>
                                                                        </div>
                                                                    </a>
                                                                    <a href="app-notes.html" class="d-flex align-items-center position-relative">
                                                                        <div class="bg-warning-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon icon="solar:palette-linear" class="text-warning fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Notes Application</h6>
                                                                            <span class="fs-11 d-block text-muted">To-do and Daily tasks</span>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row align-items-center border-top">
                                                        <div class="col-8">
                                                            <div class="ps-3 py-3">
                                                                <a class="text-dark d-flex align-items-center lh-1 fs-3" href="javascript:void(0)">
                                                                    <i class="ti ti-help fs-5 me-2"></i>Frequently Asked Questions
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
                                                                <a class="fs-3" href="page-pricing.html">Pricing Page</a>
                                                            </li>
                                                            <li class="mb-3">
                                                                <a class="fs-3" href="authentication-login.html">Authentication Design</a>
                                                            </li>
                                                            <li class="mb-3">
                                                                <a class="fs-3" href="authentication-register.html">Register Now</a>
                                                            </li>
                                                            <li class="mb-3">
                                                                <a class="fs-3" href="authentication-error.html">404 Error Page</a>
                                                            </li>
                                                            <li class="mb-3">
                                                                <a class="fs-3" href="app-notes.html">Notes App</a>
                                                            </li>
                                                            <li class="mb-3">
                                                                <a class="fs-3" href="page-user-profile.html">User Application</a>
                                                            </li>
                                                            <li class="mb-3">
                                                                <a class="fs-3" href="page-account-settings.html">Account Settings</a>
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
                            <img src="../assets/images/logos/light-logo.svg" alt="Logo" />
                        </div>
                        <ul class="navbar-nav navbar-toggler p-0 border-0">
                            <li class="nav-item nav-icon-hover-bg dark rounded-circle d-flex">
                                <a class="nav-link rounded-circle" href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                                        <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" aria-expanded="false">
                                            <iconify-icon icon="solar:bell-linear" class="fs-6"></iconify-icon>
                                            <div class="notify">
                                                <span class="heartbit"></span>
                                                <span class="point"></span>
                                            </div>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-start content-dd dropdown-menu-animate-up mailbox" aria-labelledby="drop2">
                                            <div class="py-3 px-4 border-bottom">
                                                <h5 class="mb-0 fs-4 fw-normal">Notifications</h5>
                                            </div>
                                            <div class="message-body" data-simplebar>
                                                <a href="javascript:void(0)" class="p-3 pe-0 border-bottom d-flex align-items-center dropdown-item gap-3">
                                                    <span class="flex-shrink-0 bg-danger-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-danger">
                                                        <iconify-icon icon="solar:widget-3-line-duotone"></iconify-icon>
                                                    </span>
                                                    <div class="w-75 d-inline-block ">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-1 fw-semibold">Launch Admin</h6>
                                                            <span class="d-block fs-2 text-body-color">9:30 AM</span>
                                                        </div>
                                                        <span class="d-block text-truncate text-truncate fs-11 text-body-color">Just see the my new
                                                            admin!</span>
                                                    </div>
                                                </a>
                                                <a href="javascript:void(0)" class="p-3 pe-0 border-bottom d-flex align-items-center dropdown-item gap-3">
                                                    <span class="flex-shrink-0 bg-primary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-primary">
                                                        <iconify-icon icon="solar:calendar-line-duotone"></iconify-icon>
                                                    </span>
                                                    <div class="w-75 d-inline-block ">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-1 fw-semibold">Event today</h6>
                                                            <span class="d-block fs-2 text-body-color">9:15 AM</span>
                                                        </div>
                                                        <span class="d-block text-truncate text-truncate fs-11 text-body-color">Just a reminder that
                                                            you have event</span>
                                                    </div>
                                                </a>
                                                <a href="javascript:void(0)" class="p-3 pe-0 border-bottom d-flex align-items-center dropdown-item gap-3">
                                                    <span class="flex-shrink-0 bg-secondary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-secondary">
                                                        <iconify-icon icon="solar:settings-line-duotone"></iconify-icon>
                                                    </span>
                                                    <div class="w-75 d-inline-block ">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-1 fw-semibold">Settings</h6>
                                                            <span class="d-block fs-2 text-body-color">4:36 PM</span>
                                                        </div>
                                                        <span class="d-block text-truncate text-truncate fs-11 text-body-color">You can customize
                                                            this template as you want</span>
                                                    </div>
                                                </a>
                                                <a href="javascript:void(0)" class="p-3 pe-0 border-bottom d-flex align-items-center dropdown-item gap-3">
                                                    <span class="flex-shrink-0 bg-warning-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-warning">
                                                        <iconify-icon icon="solar:widget-4-line-duotone"></iconify-icon>
                                                    </span>
                                                    <div class="w-75 d-inline-block ">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-1 fw-semibold">Launch Admin</h6>
                                                            <span class="d-block fs-2 text-body-color">9:30 AM</span>
                                                        </div>
                                                        <span class="d-block text-truncate text-truncate fs-11 text-body-color">Just see the my new
                                                            admin!</span>
                                                    </div>
                                                </a>
                                                <a href="javascript:void(0)" class="p-3 pe-0 border-bottom d-flex align-items-center dropdown-item gap-3">
                                                    <span class="flex-shrink-0 bg-primary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-primary">
                                                        <iconify-icon icon="solar:calendar-line-duotone"></iconify-icon>
                                                    </span>
                                                    <div class="w-75 d-inline-block ">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-1 fw-semibold">Event today</h6>
                                                            <span class="d-block fs-2 text-body-color">9:15 AM</span>
                                                        </div>
                                                        <span class="d-block text-truncate text-truncate fs-11 text-body-color">Just a reminder that
                                                            you have event</span>
                                                    </div>
                                                </a>
                                                <a href="javascript:void(0)" class="p-3 pe-0 border-bottom d-flex align-items-center dropdown-item gap-3">
                                                    <span class="flex-shrink-0 bg-secondary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-secondary">
                                                        <iconify-icon icon="solar:settings-line-duotone"></iconify-icon>
                                                    </span>
                                                    <div class="w-75 d-inline-block ">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-1 fw-semibold">Settings</h6>
                                                            <span class="d-block fs-2 text-body-color">4:36 PM</span>
                                                        </div>
                                                        <span class="d-block text-truncate text-truncate fs-11 text-body-color">You can customize
                                                            this template as you want</span>
                                                    </div>
                                                </a>
                                            </div>
                                            <div>
                                                <a class="d-flex align-items-center pt-3 pb-2 justify-content-center link-primary text-dark" href="javascript:void(0);">
                                                    <span class="fw-semibold">Check all notifications</span>
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
                                        <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" aria-expanded="false">
                                            <iconify-icon icon="solar:inbox-linear" class="fs-6"></iconify-icon>
                                            <div class="notify">
                                                <span class="heartbit"></span>
                                                <span class="point"></span>
                                            </div>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-start content-dd dropdown-menu-animate-up mailbox" aria-labelledby="drop2">
                                            <div class="py-3 px-4 border-bottom">
                                                <h5 class="mb-0 fs-4 fw-normal">You have 4 new messages</h5>
                                            </div>
                                            <div class="message-body" data-simplebar>
                                                <a href="javascript:void(0)" class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                                    <span class="user-img position-relative d-inline-block">
                                                        <img src="../assets/images/profile/user-5.jpg" alt="user" class="rounded-circle w-100 round-40" />
                                                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-warning border border-light rounded-circle">
                                                            <span class="visually-hidden">New alerts</span>
                                                        </span>
                                                    </span>
                                                    <div class="w-75 d-inline-block">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-1 lh-base">Mathew Anderson</h6>
                                                            <span class="fs-2 text-nowrap d-block text-body-color">9:30 AM</span>
                                                        </div>
                                                        <span class="fs-2 d-block text-truncate text-truncate text-body-color">Just see the my new
                                                            admin!</span>
                                                    </div>
                                                </a>
                                                <a href="javascript:void(0)" class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                                    <span class="user-img position-relative d-inline-block">
                                                        <img src="../assets/images/profile/user-3.jpg" alt="user" class="rounded-circle w-100 round-40" />
                                                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                                                            <span class="visually-hidden">New alerts</span>
                                                        </span>
                                                    </span>
                                                    <div class="w-75 d-inline-block">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-1 lh-base">Bianca Anderson</h6>
                                                            <span class="fs-2 text-nowrap d-block text-body-color">9:10 AM</span>
                                                        </div>

                                                        <span class="fs-2 d-block text-truncate text-truncate text-body-color">Just a reminder that
                                                            you have event</span>
                                                    </div>
                                                </a>
                                                <a href="javascript:void(0)" class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                                    <span class="user-img position-relative d-inline-block">
                                                        <img src="../assets/images/profile/user-6.jpg" alt="user" class="rounded-circle w-100 round-40" />
                                                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                                            <span class="visually-hidden">New alerts</span>
                                                        </span>
                                                    </span>
                                                    <div class="w-75 d-inline-block">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-1 lh-base">Andrew Johnson</h6>
                                                            <span class="fs-2 text-nowrap d-block text-body-color">9:08 AM</span>
                                                        </div>
                                                        <span class="fs-2 d-block text-truncate text-truncate text-body-color">You can customize
                                                            this template as you want</span>
                                                    </div>
                                                </a>
                                                <a href="javascript:void(0)" class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                                    <span class="user-img position-relative d-inline-block">
                                                        <img src="../assets/images/profile/user-7.jpg" alt="user" class="rounded-circle w-100 round-40" />
                                                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                                                            <span class="visually-hidden">New alerts</span>
                                                        </span>
                                                        </button>
                                                    </span>
                                                    <div class="w-75 d-inline-block">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-1 lh-base">Mark Strokes</h6>
                                                            <span class="fs-2 text-nowrap d-block text-body-color">9:30 AM</span>
                                                        </div>
                                                        <span class="fs-2 d-block text-truncate text-truncate text-body-color">Just see the my new
                                                            admin!</span>
                                                    </div>
                                                </a>
                                                <a href="javascript:void(0)" class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                                    <span class="user-img position-relative d-inline-block">
                                                        <img src="../assets/images/profile/user-8.jpg" alt="user" class="rounded-circle w-100 round-40" />
                                                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                                                            <span class="visually-hidden">New alerts</span>
                                                        </span>
                                                    </span>
                                                    <div class="w-75 d-inline-block">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-1 lh-base">Mark, Stoinus & Rishvi..</h6>
                                                            <span class="fs-2 text-nowrap d-block text-body-color">9:10 AM</span>
                                                        </div>
                                                        <span class="fs-2 d-block text-truncate text-truncate text-body-color">Just a reminder that
                                                            you have event</span>
                                                    </div>
                                                </a>
                                                <a href="javascript:void(0)" class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                                    <span class="user-img position-relative d-inline-block">
                                                        <img src="../assets/images/profile/user-9.jpg" alt="user" class="rounded-circle w-100 round-40" />
                                                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-warning border border-light rounded-circle">
                                                            <span class="visually-hidden">New alerts</span>
                                                        </span>
                                                    </span>
                                                    <div class="w-75 d-inline-block">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="mb-1 lh-base">Settings</h6>
                                                            <span class="fs-2 text-nowrap d-block text-body-color">9:08 AM</span>
                                                        </div>
                                                        <span class="fs-2 d-block text-truncate text-truncate text-body-color">You can customize
                                                            this template as you want</span>
                                                    </div>
                                                </a>
                                            </div>
                                            <div>
                                                <a class="d-flex align-items-center pt-3 pb-2 justify-content-center link-primary text-dark" href="javascript:void(0);">
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
                                        <a href="javascript:void(0)" class="nav-link nav-icon-hover-bg dark rounded-circle d-flex d-lg-none align-items-center justify-content-center" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilenavbar" aria-controls="offcanvasWithBothOptions">
                                            <iconify-icon icon="solar:widget-linear" class="fs-6"></iconify-icon>
                                        </a>
                                    </li>
                                    <!-- ------------------------------- -->
                                    <!-- end mega-dropdown Dropdown -->
                                    <!-- ------------------------------- -->
                                </ul>
                                <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">

                                    <li class="nav-item search-box d-none d-xl-flex align-items-center">
                                        <div class="nav-link">
                                            <form class="app-search position-relative">
                                                <input type="text" class="form-control rounded-pill border-0 shadow-none" placeholder="Search for..." />
                                                <a href="javascript:void(0)" class="srh-btn">
                                                    <iconify-icon icon="solar:magnifer-linear" class="position-absolute top-50 end-0 translate-middle-y me-2 fs-5"></iconify-icon>
                                                </a>
                                            </form>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link moon dark-layout nav-icon-hover-bg dark rounded-circle" href="javascript:void(0)">
                                            <iconify-icon icon="solar:moon-line-duotone" class="moon fs-6"></iconify-icon>
                                        </a>
                                        <a class="nav-link sun light-layout nav-icon-hover-bg dark rounded-circle" href="javascript:void(0)" style="display: none">
                                            <iconify-icon icon="solar:sun-2-line-duotone" class="sun fs-6"></iconify-icon>
                                        </a>
                                    </li>
                                    <!-- ------------------------------- -->
                                    <!-- start language Dropdown -->
                                    <!-- ------------------------------- -->
                                    <li class="nav-item dropdown nav-icon-hover-bg dark rounded-circle">
                                        <a class="nav-link" href="javascript:void(0)" id="drop2" aria-expanded="false">
                                            <img src="../assets/images/flag/icon-flag-pe.svg" alt="monster-img" width="20px" height="20px" class="rounded-circle object-fit-cover round-20" />
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up overflow-hidden" aria-labelledby="drop2">
                                            <div class="message-body">
                                                <a href="javascript:void(0)" class="d-flex align-items-center gap-2 py-3 px-4 dropdown-item">
                                                    <div class="position-relative">
                                                        <img src="../assets/images/flag/icon-flag-pe.svg" alt="monster-img" width="20px" height="20px" class="rounded-circle object-fit-cover round-20" />
                                                    </div>
                                                    <p class="mb-0 fs-3"><?= lang('Main.languageES')?></p>
                                                </a>
                                                <a href="javascript:void(0)" class="d-flex align-items-center gap-2 py-3 px-4 dropdown-item">
                                                    <div class="position-relative">
                                                        <img src="../assets/images/flag/icon-flag-us.svg" alt="monster-img" width="20px" height="20px" class="rounded-circle object-fit-cover round-20" />
                                                    </div>
                                                    <p class="mb-0 fs-3"><?= lang('Main.languageEN')?></p>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                    <!-- ------------------------------- -->
                                    <!-- end language Dropdown -->
                                    <!-- ------------------------------- -->

                                    <!-- ------------------------------- -->
                                    <!-- start profile Dropdown -->
                                    <!-- ------------------------------- -->
                                    <li class="nav-item dropdown">
                                        <a class="nav-link" href="javascript:void(0)" id="drop1" aria-expanded="false">
                                            <div class="d-flex align-items-center lh-base">
                                                <img src="../assets/images/profile/user-1.jpg" class="rounded-circle" width="35" height="35" alt="monster-img" />
                                            </div>
                                        </a>
                                        <div class="dropdown-menu content-dd dropdown-menu-end animated flipInY" aria-labelledby="drop1">
                                            <div class="profile-dropdown position-relative" data-simplebar>
                                                <div class="py-3 px-7 pb-0">
                                                    <h5 class="mb-0 fs-5"><?= lang('Main.userProfile');
                                                                            ?></h5>
                                                </div>
                                                <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                                                    <?= $this->renderSection('profile'); ?>
                                                </div>
                                                <div class="message-body">
                                                    <a href="page-user-profile.html" class="py-8 px-7 mt-8 d-flex align-items-center">
                                                        <span class="d-flex align-items-center justify-content-center bg-info-subtle rounded p-6 fs-7 text-info">
                                                            <iconify-icon icon="solar:user-circle-line-duotone"></iconify-icon>
                                                        </span>
                                                        <div class="w-75 d-inline-block v-middle ps-3">
                                                            <h6 class="mb-1 fs-3 lh-base"><?= lang('Main.myProfile'); ?></h6>
                                                            <span class="fs-2 d-block text-body-secondary"><?= lang('Main.profileSettings'); ?></span>
                                                        </div>
                                                    </a>
                                                    <a href="app-email.html" class="py-8 px-7 d-flex align-items-center">
                                                        <span class="d-flex align-items-center justify-content-center bg-info-subtle rounded p-6 fs-7 text-info">
                                                            <iconify-icon icon="solar:inbox-line-line-duotone"></iconify-icon>
                                                        </span>
                                                        <div class="w-75 d-inline-block v-middle ps-3">
                                                            <h6 class="mb-1 fs-3 lh-base"><?= lang('Main.myImbox') ?></h6>
                                                            <span class="fs-2 d-block text-body-secondary"><?= lang('Main.imboxDecription') ?></span>
                                                        </div>
                                                    </a>
                                                    <a href="app-kanban.html" class="py-8 px-7 d-flex align-items-center">
                                                        <span class="d-flex align-items-center justify-content-center bg-info-subtle rounded p-6 fs-7 text-info">
                                                            <iconify-icon icon="solar:checklist-minimalistic-line-duotone"></iconify-icon>
                                                        </span>
                                                        <div class="w-75 d-inline-block v-middle ps-3">
                                                            <h6 class="mb-1 fs-3 lh-base"><?= lang('Main.myTask'); ?></h6>
                                                            <span class="fs-2 d-block text-body-secondary"><?= lang('Main.taskList') ?></span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="d-grid py-4 px-7 pt-8">
                                                    <a href="<?= base_url('logout') ?>" class="btn btn-info"><?= lang('Main.logout') ?></a>
                                                </div>
                                            </div>

                                        </div>
                                    </li>
                                    <!-- ------------------------------- -->
                                    <!-- end profile Dropdown -->
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
                    <div class="offcanvas offcanvas-start pt-0" data-bs-scroll="true" tabindex="-1" id="mobilenavbar" aria-labelledby="offcanvasWithBothOptionsLabel">
                        <nav class="sidebar-nav scroll-sidebar">
                            <div class="offcanvas-header justify-content-between ps-0 pt-0">
                                <div class="brand-logo d-flex align-items-center">
                                    <a href="index.html" class="text-nowrap logo-img">
                                        <img src="../assets/images/logos/dark-logo.svg" alt="Logo" class="dark-logo" />
                                        <img src="../assets/images/logos/light-logo.svg" alt="Logo" class="light-logo" />
                                    </a>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body pt-0" data-simplebar style="height: calc(100vh - 80px)">
                                <ul id="sidebarnav">
                                    <li class="sidebar-item">
                                        <a class="sidebar-link has-arrow ms-0 rounded" href="javascript:void(0)" aria-expanded="false">
                                            <span>
                                                <iconify-icon icon="solar:slider-vertical-line-duotone" class="fs-7"></iconify-icon>
                                            </span>
                                            <span class="hide-menu">Apps</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse first-level my-3 ps-3">
                                            <li class="sidebar-item py-2">
                                                <a href="app-chat.html" class="d-flex align-items-center">
                                                    <div class="bg-primary-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                        <iconify-icon icon="solar:chat-line-linear" class="text-primary fs-5"></iconify-icon>
                                                    </div>
                                                    <div class="d-inline-block">
                                                        <h6 class="mb-0">Chat Application</h6>
                                                        <span class="fs-11 d-block text-muted">New messages arrived</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="sidebar-item py-2">
                                                <a href="app-invoice.html" class="d-flex align-items-center">
                                                    <div class="bg-secondary-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                        <iconify-icon icon="solar:bill-list-linear" class="text-secondary fs-5"></iconify-icon>
                                                    </div>
                                                    <div class="d-inline-block">
                                                        <h6 class="mb-0">Invoice App</h6>
                                                        <span class="fs-11 d-block text-muted">Get latest invoice</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="sidebar-item py-2">
                                                <a href="app-contact2.html" class="d-flex align-items-center">
                                                    <div class="bg-warning-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                        <iconify-icon icon="solar:bedside-table-2-linear" class="text-warning fs-5"></iconify-icon>
                                                    </div>
                                                    <div class="d-inline-block">
                                                        <h6 class="mb-0">Contact Application</h6>
                                                        <span class="fs-11 d-block text-muted">2 Unsaved Contacts</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="sidebar-item py-2">
                                                <a href="app-email.html" class="d-flex align-items-center">
                                                    <div class="bg-danger-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                        <iconify-icon icon="solar:letter-unread-linear" class="text-danger fs-5"></iconify-icon>
                                                    </div>
                                                    <div class="d-inline-block">
                                                        <h6 class="mb-0">Email App</h6>
                                                        <span class="fs-11 d-block text-muted">Get new emails</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="sidebar-item py-2">
                                                <a href="page-user-profile.html" class="d-flex align-items-center">
                                                    <div class="bg-success-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                        <iconify-icon icon="solar:cart-large-2-linear" class="text-success fs-5"></iconify-icon>
                                                    </div>
                                                    <div class="d-inline-block">
                                                        <h6 class="mb-0"><?= lang('Main.userProfile') ?> </h6>
                                                        <span class="fs-11 d-block text-muted">learn more information</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="sidebar-item py-2">
                                                <a href="app-calendar.html" class="d-flex align-items-center">
                                                    <div class="bg-primary-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                        <iconify-icon icon="solar:calendar-linear" class="text-primary fs-5"></iconify-icon>
                                                    </div>
                                                    <div class="d-inline-block">
                                                        <h6 class="mb-0">Calendar App</h6>
                                                        <span class="fs-11 d-block text-muted">Get dates</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="sidebar-item py-2">
                                                <a href="app-contact.html" class="d-flex align-items-center">
                                                    <div class="bg-secondary-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                        <iconify-icon icon="solar:bedside-table-linear" class="text-secondary fs-5"></iconify-icon>
                                                    </div>
                                                    <div class="d-inline-block">
                                                        <h6 class="mb-0">Contact List Table</h6>
                                                        <span class="fs-11 d-block text-muted">Add new contact</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="sidebar-item py-2">
                                                <a href="app-notes.html" class="d-flex align-items-center">
                                                    <div class="bg-warning-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                        <iconify-icon icon="solar:palette-linear" class="text-warning fs-5"></iconify-icon>
                                                    </div>
                                                    <div class="d-inline-block">
                                                        <h6 class="mb-0">Notes Application</h6>
                                                        <span class="fs-11 d-block text-muted">To-do and Daily tasks</span>
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
                                <a class="nav-link sidebartoggler nav-icon-hover-bg rounded-circle" id="sidebarCollapse" href="javascript:void(0)">
                                    <iconify-icon icon="solar:hamburger-menu-line-duotone" class="fs-7"></iconify-icon>
                                </a>
                            </li>
                            <li class="nav-item d-none d-xl-flex align-items-center">
                                <a href="../horizontal/index.html" class="text-nowrap nav-link">

                                    <img src="../assets/images/logos/light-logo.svg" alt="Logo" />
                                </a>
                            </li>
                            <!-- ------------------------------- -->
                            <!-- start notification Dropdown -->
                            <!-- ------------------------------- -->
                            <li class="nav-item dropdown nav-icon-hover-bg dark rounded-circle d-none d-xl-flex">
                                <a class="nav-link position-relative" href="javascript:void(0)" id="drop2" aria-expanded="false">
                                    <iconify-icon icon="solar:bell-bing-line-duotone" class="fs-6"></iconify-icon>
                                    <div class="notify">
                                        <span class="heartbit"></span>
                                        <span class="point"></span>
                                    </div>
                                </a>
                                <div class="dropdown-menu content-dd dropdown-menu-animate-up" aria-labelledby="drop2">
                                    <div class="py-3 px-4 border-bottom">
                                        <h5 class="mb-0 fs-4 fw-normal">Notifications</h5>
                                    </div>
                                    <div class="message-body" data-simplebar>
                                        <a href="javascript:void(0)" class="p-3 pe-0 border-bottom d-flex align-items-center dropdown-item gap-3">
                                            <span class="flex-shrink-0 bg-danger-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-danger">
                                                <iconify-icon icon="solar:widget-3-line-duotone"></iconify-icon>
                                            </span>
                                            <div class="w-75 d-inline-block ">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 fw-semibold">Launch Admin</h6>
                                                    <span class="d-block fs-2 text-body-color">9:30 AM</span>
                                                </div>
                                                <span class="d-block text-truncate text-truncate fs-11 text-body-color">Just see the my new
                                                    admin!</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)" class="p-3 pe-0 border-bottom d-flex align-items-center dropdown-item gap-3">
                                            <span class="flex-shrink-0 bg-primary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-primary">
                                                <iconify-icon icon="solar:calendar-line-duotone"></iconify-icon>
                                            </span>
                                            <div class="w-75 d-inline-block ">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 fw-semibold">Event today</h6>
                                                    <span class="d-block fs-2 text-body-color">9:15 AM</span>
                                                </div>
                                                <span class="d-block text-truncate text-truncate fs-11 text-body-color">Just a reminder that you
                                                    have event</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)" class="p-3 pe-0 border-bottom d-flex align-items-center dropdown-item gap-3">
                                            <span class="flex-shrink-0 bg-secondary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-secondary">
                                                <iconify-icon icon="solar:settings-line-duotone"></iconify-icon>
                                            </span>
                                            <div class="w-75 d-inline-block ">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 fw-semibold">Settings</h6>
                                                    <span class="d-block fs-2 text-body-color">4:36 PM</span>
                                                </div>
                                                <span class="d-block text-truncate text-truncate fs-11 text-body-color">You can customize this
                                                    template as you want</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)" class="p-3 pe-0 border-bottom d-flex align-items-center dropdown-item gap-3">
                                            <span class="flex-shrink-0 bg-warning-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-warning">
                                                <iconify-icon icon="solar:widget-4-line-duotone"></iconify-icon>
                                            </span>
                                            <div class="w-75 d-inline-block ">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 fw-semibold">Launch Admin</h6>
                                                    <span class="d-block fs-2 text-body-color">9:30 AM</span>
                                                </div>
                                                <span class="d-block text-truncate text-truncate fs-11 text-body-color">Just see the my new
                                                    admin!</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)" class="p-3 pe-0 border-bottom d-flex align-items-center dropdown-item gap-3">
                                            <span class="flex-shrink-0 bg-primary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-primary">
                                                <iconify-icon icon="solar:calendar-line-duotone"></iconify-icon>
                                            </span>
                                            <div class="w-75 d-inline-block ">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 fw-semibold">Event today</h6>
                                                    <span class="d-block fs-2 text-body-color">9:15 AM</span>
                                                </div>
                                                <span class="d-block text-truncate text-truncate fs-11 text-body-color">Just a reminder that you
                                                    have event</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)" class="p-3 pe-0 border-bottom d-flex align-items-center dropdown-item gap-3">
                                            <span class="flex-shrink-0 bg-secondary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-secondary">
                                                <iconify-icon icon="solar:settings-line-duotone"></iconify-icon>
                                            </span>
                                            <div class="w-75 d-inline-block ">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 fw-semibold">Settings</h6>
                                                    <span class="d-block fs-2 text-body-color">4:36 PM</span>
                                                </div>
                                                <span class="d-block text-truncate text-truncate fs-11 text-body-color">You can customize this
                                                    template as you want</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div>
                                        <a class="d-flex align-items-center pt-3 pb-2 justify-content-center link-primary text-dark" href="javascript:void(0);">
                                            <span class="fw-semibold">Check all notifications</span>
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
                                <a class="nav-link position-relative" href="javascript:void(0)" id="drop2" aria-expanded="false">
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
                                        <a href="javascript:void(0)" class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                            <span class="user-img position-relative d-inline-block">
                                                <img src="../assets/images/profile/user-5.jpg" alt="user" class="rounded-circle w-100 round-40" />
                                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-warning border border-light rounded-circle">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                            </span>
                                            <div class="w-75 d-inline-block">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 lh-base">Mathew Anderson</h6>
                                                    <span class="fs-2 text-nowrap d-block text-body-color">9:30 AM</span>
                                                </div>
                                                <span class="fs-2 d-block text-truncate text-truncate text-body-color">Just see the my new
                                                    admin!</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)" class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                            <span class="user-img position-relative d-inline-block">
                                                <img src="../assets/images/profile/user-3.jpg" alt="user" class="rounded-circle w-100 round-40" />
                                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                            </span>
                                            <div class="w-75 d-inline-block">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 lh-base">Bianca Anderson</h6>
                                                    <span class="fs-2 text-nowrap d-block text-body-color">9:10 AM</span>
                                                </div>

                                                <span class="fs-2 d-block text-truncate text-truncate text-body-color">Just a reminder that you
                                                    have event</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)" class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                            <span class="user-img position-relative d-inline-block">
                                                <img src="../assets/images/profile/user-6.jpg" alt="user" class="rounded-circle w-100 round-40" />
                                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                            </span>
                                            <div class="w-75 d-inline-block">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 lh-base">Andrew Johnson</h6>
                                                    <span class="fs-2 text-nowrap d-block text-body-color">9:08 AM</span>
                                                </div>
                                                <span class="fs-2 d-block text-truncate text-truncate text-body-color">You can customize this
                                                    template as you want</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)" class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                            <span class="user-img position-relative d-inline-block">
                                                <img src="../assets/images/profile/user-7.jpg" alt="user" class="rounded-circle w-100 round-40" />
                                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                                </button>
                                            </span>
                                            <div class="w-75 d-inline-block">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 lh-base">Mark Strokes</h6>
                                                    <span class="fs-2 text-nowrap d-block text-body-color">9:30 AM</span>
                                                </div>
                                                <span class="fs-2 d-block text-truncate text-truncate text-body-color">Just see the my new
                                                    admin!</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)" class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                            <span class="user-img position-relative d-inline-block">
                                                <img src="../assets/images/profile/user-8.jpg" alt="user" class="rounded-circle w-100 round-40" />
                                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                            </span>
                                            <div class="w-75 d-inline-block">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 lh-base">Mark, Stoinus & Rishvi..</h6>
                                                    <span class="fs-2 text-nowrap d-block text-body-color">9:10 AM</span>
                                                </div>
                                                <span class="fs-2 d-block text-truncate text-truncate text-body-color">Just a reminder that you
                                                    have event</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)" class="p-3 pe-0 d-flex align-items-center dropdown-item gap-3 border-bottom">
                                            <span class="user-img position-relative d-inline-block">
                                                <img src="../assets/images/profile/user-9.jpg" alt="user" class="rounded-circle w-100 round-40" />
                                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-warning border border-light rounded-circle">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                            </span>
                                            <div class="w-75 d-inline-block">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 lh-base">Settings</h6>
                                                    <span class="fs-2 text-nowrap d-block text-body-color">9:08 AM</span>
                                                </div>
                                                <span class="fs-2 d-block text-truncate text-truncate text-body-color">You can customize this
                                                    template as you want</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div>
                                        <a class="d-flex align-items-center pt-3 pb-2 justify-content-center link-primary text-dark" href="javascript:void(0);">
                                            <span class="fw-semibold">See all e-Mails</span>
                                            <iconify-icon icon="solar:alt-arrow-right-linear"></iconify-icon>
                                        </a>
                                    </div>

                                </div>
                            </li>
                            <!-- ------------------------------- -->
                            <!-- end messages Dropdown -->
                            <!-- ------------------------------- -->
                            <li class="nav-item d-none d-lg-flex dropdown nav-icon-hover-bg dark rounded-circle d-none d-xl-flex">
                                <div class="hover-dd">
                                    <a class="nav-link" id="drop2" href="javascript:void(0)" aria-haspopup="true" aria-expanded="false">
                                        <iconify-icon icon="solar:widget-3-line-duotone" class="fs-6"></iconify-icon>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-nav dropdown-menu-animate-up py-0 overflow-hidden" aria-labelledby="drop2">
                                        <div class="position-relative">
                                            <div class="row">
                                                <div class="col-8">
                                                    <div class="p-4 pb-3">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <div class="position-relative">
                                                                    <a href="app-chat.html" class="d-flex align-items-center pb-9 position-relative">
                                                                        <div class="bg-primary-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon icon="solar:chat-line-linear" class="text-primary fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Chat Application</h6>
                                                                            <span class="fs-11 d-block text-muted">New messages arrived</span>
                                                                        </div>
                                                                    </a>
                                                                    <a href="app-invoice.html" class="d-flex align-items-center pb-9 position-relative">
                                                                        <div class="bg-secondary-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon icon="solar:bill-list-linear" class="text-secondary fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Invoice App</h6>
                                                                            <span class="fs-11 d-block text-muted">Get latest invoice</span>
                                                                        </div>
                                                                    </a>
                                                                    <a href="app-contact2.html" class="d-flex align-items-center pb-9 position-relative">
                                                                        <div class="bg-warning-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon icon="solar:bedside-table-2-linear" class="text-warning fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Contact Application</h6>
                                                                            <span class="fs-11 d-block text-muted">2 Unsaved Contacts</span>
                                                                        </div>
                                                                    </a>
                                                                    <a href="app-email.html" class="d-flex align-items-center position-relative">
                                                                        <div class="bg-danger-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon icon="solar:letter-unread-linear" class="text-danger fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Email App</h6>
                                                                            <span class="fs-11 d-block text-muted">Get new emails</span>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="position-relative">
                                                                    <a href="page-user-profile.html" class="d-flex align-items-center pb-9 position-relative">
                                                                        <div class="bg-success-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon icon="solar:cart-large-2-linear" class="text-success fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">User Profile</h6>
                                                                            <span class="fs-11 d-block text-muted">learn more information</span>
                                                                        </div>
                                                                    </a>
                                                                    <a href="app-calendar.html" class="d-flex align-items-center pb-9 position-relative">
                                                                        <div class="bg-primary-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon icon="solar:calendar-linear" class="text-primary fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Calendar App</h6>
                                                                            <span class="fs-11 d-block text-muted">Get dates</span>
                                                                        </div>
                                                                    </a>
                                                                    <a href="app-contact.html" class="d-flex align-items-center pb-9 position-relative">
                                                                        <div class="bg-secondary-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon icon="solar:bedside-table-linear" class="text-secondary fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Contact List Table</h6>
                                                                            <span class="fs-11 d-block text-muted">Add new contact</span>
                                                                        </div>
                                                                    </a>
                                                                    <a href="app-notes.html" class="d-flex align-items-center position-relative">
                                                                        <div class="bg-warning-subtle rounded-circle round me-3 d-flex align-items-center justify-content-center">
                                                                            <iconify-icon icon="solar:palette-linear" class="text-warning fs-5"></iconify-icon>
                                                                        </div>
                                                                        <div class="d-inline-block">
                                                                            <h6 class="mb-0">Notes Application</h6>
                                                                            <span class="fs-11 d-block text-muted">To-do and Daily tasks</span>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row align-items-center border-top">
                                                        <div class="col-8">
                                                            <div class="ps-3 py-3">
                                                                <a class="text-dark d-flex align-items-center lh-1 fs-3" href="javascript:void(0)">
                                                                    <i class="ti ti-help fs-5 me-2"></i>Frequently Asked Questions
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
                                                                <a class="fs-3" href="page-pricing.html">Pricing Page</a>
                                                            </li>
                                                            <li class="mb-3">
                                                                <a class="fs-3" href="authentication-login.html">Authentication Design</a>
                                                            </li>
                                                            <li class="mb-3">
                                                                <a class="fs-3" href="authentication-register.html">Register Now</a>
                                                            </li>
                                                            <li class="mb-3">
                                                                <a class="fs-3" href="authentication-error.html">404 Error Page</a>
                                                            </li>
                                                            <li class="mb-3">
                                                                <a class="fs-3" href="app-notes.html">Notes App</a>
                                                            </li>
                                                            <li class="mb-3">
                                                                <a class="fs-3" href="page-user-profile.html">User Application</a>
                                                            </li>
                                                            <li class="mb-3">
                                                                <a class="fs-3" href="page-account-settings.html">Account Settings</a>
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
                            <a href="index.html" class="text-nowrap nav-link">
                                <img src="../assets/images/logos/light-logo.svg" alt="Logo" />
                            </a>
                        </div>
                        <ul class="navbar-nav navbar-toggler p-0 border-0">
                            <li class="nav-item nav-icon-hover-bg dark rounded-circle d-flex">
                                <a class="nav-link rounded-circle" href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                                    <iconify-icon icon="solar:menu-dots-bold-duotone" class="fs-6"></iconify-icon>
                                </a>
                            </li>
                        </ul>
                        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                            <div class="d-flex align-items-center justify-content-between px-0 px-xl-8">
                                <ul class="navbar-nav flex-row mx-auto ms-lg-auto align-items-center justify-content-center">
                                    <li class="nav-item dropdown">
                                        <a href="javascript:void(0)" class="nav-link nav-icon-hover-bg rounded-circle d-flex d-lg-none align-items-center justify-content-center" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilenavbar" aria-controls="offcanvasWithBothOptions">
                                            <iconify-icon icon="solar:sort-line-duotone" class="fs-6"></iconify-icon>
                                        </a>
                                    </li>
                                    <li class="nav-item search-box d-none d-xl-flex align-items-center">
                                        <div class="nav-link">
                                            <form class="app-search position-relative">
                                                <input type="text" class="form-control rounded-pill border-0 shadow-none" placeholder="Search for..." />
                                                <a href="javascript:void(0)" class="srh-btn">
                                                    <iconify-icon icon="solar:magnifer-linear" class="position-absolute top-50 end-0 translate-middle-y me-2 fs-5"></iconify-icon>
                                                </a>
                                            </form>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link nav-icon-hover-bg rounded-circle moon dark-layout" href="javascript:void(0)">
                                            <iconify-icon icon="solar:moon-line-duotone" class="moon fs-6"></iconify-icon>
                                        </a>
                                        <a class="nav-link nav-icon-hover-bg rounded-circle sun light-layout" href="javascript:void(0)" style="display: none">
                                            <iconify-icon icon="solar:sun-2-line-duotone" class="sun fs-6"></iconify-icon>
                                        </a>
                                    </li>
                                    <li class="nav-item d-block d-xl-none">
                                        <a class="nav-link nav-icon-hover-bg rounded-circle" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            <iconify-icon icon="solar:magnifer-line-duotone" class="fs-6"></iconify-icon>
                                        </a>
                                    </li>

                                    <!-- ------------------------------- -->
                                    <!-- start language Dropdown -->
                                    <!-- ------------------------------- -->
                                    <li class="nav-item dropdown nav-icon-hover-bg rounded-circle">
                                        <a class="nav-link" href="javascript:void(0)" id="drop2" aria-expanded="false">
                                            <img src="../assets/images/flag/icon-flag-en.svg" alt="monster-img" width="20px" height="20px" class="rounded-circle object-fit-cover round-20" />
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up overflow-hidden" aria-labelledby="drop2">
                                            <div class="message-body">
                                                <a href="javascript:void(0)" class="d-flex align-items-center gap-2 py-3 px-4 dropdown-item">
                                                    <div class="position-relative">
                                                        <img src="../assets/images/flag/icon-flag-en.svg" alt="monster-img" width="20px" height="20px" class="rounded-circle object-fit-cover round-20" />
                                                    </div>
                                                    <p class="mb-0 fs-3">English (UK)</p>
                                                </a>
                                                <a href="javascript:void(0)" class="d-flex align-items-center gap-2 py-3 px-4 dropdown-item">
                                                    <div class="position-relative">
                                                        <img src="../assets/images/flag/icon-flag-cn.svg" alt="monster-img" width="20px" height="20px" class="rounded-circle object-fit-cover round-20" />
                                                    </div>
                                                    <p class="mb-0 fs-3">中国人 (Chinese)</p>
                                                </a>
                                                <a href="javascript:void(0)" class="d-flex align-items-center gap-2 py-3 px-4 dropdown-item">
                                                    <div class="position-relative">
                                                        <img src="../assets/images/flag/icon-flag-fr.svg" alt="monster-img" width="20px" height="20px" class="rounded-circle object-fit-cover round-20" />
                                                    </div>
                                                    <p class="mb-0 fs-3">français (French)</p>
                                                </a>
                                                <a href="javascript:void(0)" class="d-flex align-items-center gap-2 py-3 px-4 dropdown-item">
                                                    <div class="position-relative">
                                                        <img src="../assets/images/flag/icon-flag-sa.svg" alt="monster-img" width="20px" height="20px" class="rounded-circle object-fit-cover round-20" />
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
                                    <!-- start profile Dropdown -->
                                    <!-- ------------------------------- -->
                                    <li class="nav-item dropdown">
                                        <a class="nav-link" href="javascript:void(0)" id="drop1" aria-expanded="false">
                                            <div class="d-flex align-items-center lh-base">
                                                <img src="../assets/images/profile/user-1.jpg" class="rounded-circle" width="35" height="35" alt="monster-img" />
                                            </div>
                                        </a>
                                        <div class="dropdown-menu content-dd dropdown-menu-end animated flipInY" aria-labelledby="drop1">
                                            <div class="profile-dropdown position-relative" data-simplebar>
                                                <div class="py-3 px-7 pb-0">
                                                    <h5 class="mb-0 fs-5">User Profile</h5>
                                                </div>
                                                <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                                                    <img src="../assets/images/profile/user-1.jpg" class="rounded-circle" width="80" height="80" alt="" />
                                                    <div class="ms-3">
                                                        <h5 class="mb-1 fs-4">Markarn Doe</h5>
                                                        <span class="mb-1 d-block">Designer</span>
                                                        <p class="mb-0 d-flex align-items-center gap-2">
                                                            <i class="ti ti-mail fs-4"></i> info@monster.com
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="message-body">
                                                    <a href="page-user-profile.html" class="py-8 px-7 mt-8 d-flex align-items-center">
                                                        <span class="d-flex align-items-center justify-content-center bg-info-subtle rounded p-6 fs-7 text-info">
                                                            <iconify-icon icon="solar:user-circle-line-duotone"></iconify-icon>
                                                        </span>
                                                        <div class="w-75 d-inline-block v-middle ps-3">
                                                            <h6 class="mb-1 fs-3 lh-base">My Profile</h6>
                                                            <span class="fs-2 d-block text-body-secondary">Account Settings</span>
                                                        </div>
                                                    </a>
                                                    <a href="app-email.html" class="py-8 px-7 d-flex align-items-center">
                                                        <span class="d-flex align-items-center justify-content-center bg-info-subtle rounded p-6 fs-7 text-info">
                                                            <iconify-icon icon="solar:inbox-line-line-duotone"></iconify-icon>
                                                        </span>
                                                        <div class="w-75 d-inline-block v-middle ps-3">
                                                            <h6 class="mb-1 fs-3 lh-base">My Inbox</h6>
                                                            <span class="fs-2 d-block text-body-secondary">Messages & Emails</span>
                                                        </div>
                                                    </a>
                                                    <a href="app-kanban.html" class="py-8 px-7 d-flex align-items-center">
                                                        <span class="d-flex align-items-center justify-content-center bg-info-subtle rounded p-6 fs-7 text-info">
                                                            <iconify-icon icon="solar:checklist-minimalistic-line-duotone"></iconify-icon>
                                                        </span>
                                                        <div class="w-75 d-inline-block v-middle ps-3">
                                                            <h6 class="mb-1 fs-3 lh-base">My Task</h6>
                                                            <span class="fs-2 d-block text-body-secondary">To-do and Daily Tasks</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="d-grid py-4 px-7 pt-8">
                                                    <a href="authentication-login.html" class="btn btn-info">Log Out</a>
                                                </div>
                                            </div>

                                        </div>
                                    </li>
                                    <!-- ------------------------------- -->
                                    <!-- end profile Dropdown -->
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
                            <!-- =================== -->
                            <!-- Dashboard -->
                            <!-- =================== -->
                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                    <span>
                                        <iconify-icon icon="solar:layers-line-duotone" class="ti"></iconify-icon>
                                    </span>
                                    <span class="hide-menu">Dashboard</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a href="index.html" class="sidebar-link">
                                            <i class="ti ti-aperture"></i>
                                            <span class="hide-menu">Dashboard 1</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="index2.html" class="sidebar-link">
                                            <i class="ti ti-shopping-cart"></i>
                                            <span class="hide-menu">Dashboard 2</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- ============================= -->
                            <!-- Apps -->
                            <!-- ============================= -->
                            <li class="nav-small-cap">
                                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                                <span class="hide-menu">Apps</span>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link two-column has-arrow" href="javascript:void(0)" aria-expanded="false">
                                    <span>
                                        <iconify-icon icon="solar:widget-line-duotone" class="ti"></iconify-icon>
                                    </span>
                                    <span class="hide-menu">Apps</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a href="app-calendar.html" class="sidebar-link">
                                            <i class="ti ti-calendar"></i>
                                            <span class="hide-menu">Calendar</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="apps-kanban.html" class="sidebar-link">
                                            <i class="ti ti-layout-kanban"></i>
                                            <span class="hide-menu">Kanban</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="app-chat.html" class="sidebar-link">
                                            <i class="ti ti-message-dots"></i>
                                            <span class="hide-menu">Chat</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="app-email.html" aria-expanded="false">
                                            <i class="ti ti-mail"></i>
                                            <span class="hide-menu">Email</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="app-contact.html" class="sidebar-link">
                                            <i class="ti ti-phone"></i>
                                            <span class="hide-menu">Contact Table</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="app-contact2.html" class="sidebar-link">
                                            <i class="ti ti-list-details"></i>
                                            <span class="hide-menu">Contact List</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="app-notes.html" class="sidebar-link">
                                            <i class="ti ti-notes"></i>
                                            <span class="hide-menu">Notes</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="app-invoice.html" class="sidebar-link">
                                            <i class="ti ti-file-text"></i>
                                            <span class="hide-menu">Invoice</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="page-user-profile.html" class="sidebar-link">
                                            <i class="ti ti-user-circle"></i>
                                            <span class="hide-menu">User Profile</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="blog-posts.html" class="sidebar-link">
                                            <i class="ti ti-article"></i>
                                            <span class="hide-menu">Posts</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="blog-detail.html" class="sidebar-link">
                                            <i class="ti ti-details"></i>
                                            <span class="hide-menu">Detail</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="eco-shop.html" class="sidebar-link">
                                            <i class="ti ti-shopping-cart"></i>
                                            <span class="hide-menu">Shop</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="eco-shop-detail.html" class="sidebar-link">
                                            <i class="ti ti-basket"></i>
                                            <span class="hide-menu">Shop Detail</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="eco-product-list.html" class="sidebar-link">
                                            <i class="ti ti-list-check"></i>
                                            <span class="hide-menu">List</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="eco-checkout.html" class="sidebar-link">
                                            <i class="ti ti-brand-shopee"></i>
                                            <span class="hide-menu">Checkout</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="eco-add-product.html" class="sidebar-link">
                                            <i class="ti ti-file-plus"></i>
                                            <span class="hide-menu">Add Product</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="eco-edit-product.html" class="sidebar-link">
                                            <i class="ti ti-file-pencil"></i>
                                            <span class="hide-menu">Edit Product</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- ============================= -->
                            <!-- PAGES -->
                            <!-- ============================= -->
                            <li class="nav-small-cap">
                                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                                <span class="hide-menu">PAGES</span>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                    <span>
                                        <iconify-icon icon="solar:notes-line-duotone" class="ti"></iconify-icon>
                                    </span>
                                    <span class="hide-menu">Pages</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a href="page-faq.html" class="sidebar-link">
                                            <i class="ti ti-help"></i>
                                            <span class="hide-menu">FAQ</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="page-account-settings.html" class="sidebar-link">
                                            <i class="ti ti-user-circle"></i>
                                            <span class="hide-menu">Account Setting</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="page-pricing.html" class="sidebar-link">
                                            <i class="ti ti-currency-dollar"></i>
                                            <span class="hide-menu">Pricing</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="widgets-cards.html" class="sidebar-link">
                                            <i class="ti ti-cards"></i>
                                            <span class="hide-menu">Card</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="widgets-banners.html" class="sidebar-link">
                                            <i class="ti ti-ad"></i>
                                            <span class="hide-menu">Banner</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="widgets-charts.html" class="sidebar-link">
                                            <i class="ti ti-chart-bar"></i>
                                            <span class="hide-menu">Charts</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="starter.html" class="sidebar-link">
                                            <i class="ti ti-file"></i>
                                            <span class="hide-menu">Starter</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../landingpage/index.html" class="sidebar-link">
                                            <i class="ti ti-app-window"></i>
                                            <span class="hide-menu">Landing Page</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </nav>
                    <!-- End Sidebar navigation -->
                </div>
                <!-- End Sidebar scroll-->
            </aside>

            <div class="body-wrapper">
                <div class="container-fluid">
                    <div class="d-md-flex align-items-center justify-content-between mb-7">
                        <div class="mb-4 mb-md-0">
                            <h4 class="fs-6 mb-0">Dashboard</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item">
                                        <a class="text-muted text-decoration-none" href="index.html">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="d-flex align-items-center justify-content-between gap-6">
                            <select class="form-select border fs-3" aria-label="Default select example">
                                <option selected>November 2024</option>
                                <option value="1">February 2024</option>
                                <option value="2">March 2024</option>
                                <option value="3">April 2024</option>
                            </select>
                            <button class="btn btn-success d-flex align-items-center gap-1 fs-3 py-2 px-9">
                                <i class="ti ti-plus fs-4"></i>
                                Create
                            </button>
                        </div>
                    </div>

                    <?= $this->renderSection('content'); ?>

                </div>
            </div>
            <button class="btn btn-primary p-3 rounded-circle d-flex align-items-center justify-content-center customizer-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                <i class="icon ti ti-settings fs-7"></i>
            </button>

            <div class="offcanvas customizer offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
                <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                    <h4 class="offcanvas-title fw-semibold" id="offcanvasExampleLabel">
                        Settings
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body" data-simplebar style="height: calc(100vh - 80px)">
                    <h6 class="fw-semibold fs-4 mb-2">Theme</h6>

                    <div class="d-flex flex-row gap-3 customizer-box" role="group">
                        <input type="radio" class="btn-check light-layout" name="theme-layout" id="light-layout" autocomplete="off" />
                        <label class="btn p-9 btn-outline-primary rounded-2" for="light-layout">
                            <i class="icon ti ti-brightness-up fs-7 me-2"></i>Light
                        </label>

                        <input type="radio" class="btn-check dark-layout" name="theme-layout" id="dark-layout" autocomplete="off" />
                        <label class="btn p-9 btn-outline-primary rounded-2" for="dark-layout">
                            <i class="icon ti ti-moon fs-7 me-2"></i>Dark
                        </label>
                    </div>

                    <h6 class="mt-5 fw-semibold fs-4 mb-2">Theme Direction</h6>
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

                    <h6 class="mt-5 fw-semibold fs-4 mb-2">Theme Colors</h6>

                    <div class="d-flex flex-row flex-wrap gap-3 customizer-box color-pallete" role="group">
                        <input type="radio" class="btn-check" name="color-theme-layout" id="Blue_Theme" autocomplete="off" />
                        <label class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center" onclick="handleColorTheme('Blue_Theme')" for="Blue_Theme" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="BLUE_THEME">
                            <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-1">
                                <i class="ti ti-check text-white d-flex icon fs-5"></i>
                            </div>
                        </label>

                        <input type="radio" class="btn-check" name="color-theme-layout" id="Aqua_Theme" autocomplete="off" />
                        <label class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center" onclick="handleColorTheme('Aqua_Theme')" for="Aqua_Theme" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="AQUA_THEME">
                            <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-2">
                                <i class="ti ti-check text-white d-flex icon fs-5"></i>
                            </div>
                        </label>

                        <input type="radio" class="btn-check" name="color-theme-layout" id="Purple_Theme" autocomplete="off" />
                        <label class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center" onclick="handleColorTheme('Purple_Theme')" for="Purple_Theme" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="PURPLE_THEME">
                            <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-3">
                                <i class="ti ti-check text-white d-flex icon fs-5"></i>
                            </div>
                        </label>

                        <input type="radio" class="btn-check" name="color-theme-layout" id="green-theme-layout" autocomplete="off" />
                        <label class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center" onclick="handleColorTheme('Green_Theme')" for="green-theme-layout" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="GREEN_THEME">
                            <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-4">
                                <i class="ti ti-check text-white d-flex icon fs-5"></i>
                            </div>
                        </label>

                        <input type="radio" class="btn-check" name="color-theme-layout" id="cyan-theme-layout" autocomplete="off" />
                        <label class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center" onclick="handleColorTheme('Cyan_Theme')" for="cyan-theme-layout" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="CYAN_THEME">
                            <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-5">
                                <i class="ti ti-check text-white d-flex icon fs-5"></i>
                            </div>
                        </label>

                        <input type="radio" class="btn-check" name="color-theme-layout" id="orange-theme-layout" autocomplete="off" />
                        <label class="btn p-9 btn-outline-primary rounded-2 d-flex align-items-center justify-content-center" onclick="handleColorTheme('Orange_Theme')" for="orange-theme-layout" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="ORANGE_THEME">
                            <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-6">
                                <i class="ti ti-check text-white d-flex icon fs-5"></i>
                            </div>
                        </label>
                    </div>

                    <h6 class="mt-5 fw-semibold fs-4 mb-2">Layout Type</h6>
                    <div class="d-flex flex-row gap-3 customizer-box" role="group">
                        <div>
                            <input type="radio" class="btn-check" name="page-layout" id="vertical-layout" autocomplete="off" />
                            <label class="btn p-9 btn-outline-primary rounded-2" for="vertical-layout">
                                <i class="icon ti ti-layout-sidebar-right fs-7 me-2"></i>Vertical
                            </label>
                        </div>
                        <div>
                            <input type="radio" class="btn-check" name="page-layout" id="horizontal-layout" autocomplete="off" />
                            <label class="btn p-9 btn-outline-primary rounded-2" for="horizontal-layout">
                                <i class="icon ti ti-layout-navbar fs-7 me-2"></i>Horizontal
                            </label>
                        </div>
                    </div>

                    <h6 class="mt-5 fw-semibold fs-4 mb-2">Container Option</h6>

                    <div class="d-flex flex-row gap-3 customizer-box" role="group">
                        <input type="radio" class="btn-check" name="layout" id="boxed-layout" autocomplete="off" />
                        <label class="btn p-9 btn-outline-primary rounded-2" for="boxed-layout">
                            <i class="icon ti ti-layout-distribute-vertical fs-7 me-2"></i>Boxed
                        </label>

                        <input type="radio" class="btn-check" name="layout" id="full-layout" autocomplete="off" />
                        <label class="btn p-9 btn-outline-primary rounded-2" for="full-layout">
                            <i class="icon ti ti-layout-distribute-horizontal fs-7 me-2"></i>Full
                        </label>
                    </div>

                    <h6 class="fw-semibold fs-4 mb-2 mt-5">Sidebar Type</h6>
                    <div class="d-flex flex-row gap-3 customizer-box" role="group">
                        <a href="javascript:void(0)" class="fullsidebar">
                            <input type="radio" class="btn-check" name="sidebar-type" id="full-sidebar" autocomplete="off" />
                            <label class="btn p-9 btn-outline-primary rounded-2" for="full-sidebar">
                                <i class="icon ti ti-layout-sidebar-right fs-7 me-2"></i>Full
                            </label>
                        </a>
                        <div>
                            <input type="radio" class="btn-check" name="sidebar-type" id="mini-sidebar" autocomplete="off" />
                            <label class="btn p-9 btn-outline-primary rounded-2" for="mini-sidebar">
                                <i class="icon ti ti-layout-sidebar fs-7 me-2"></i>Collapse
                            </label>
                        </div>
                    </div>

                    <h6 class="mt-5 fw-semibold fs-4 mb-2">Card With</h6>

                    <div class="d-flex flex-row gap-3 customizer-box" role="group">
                        <input type="radio" class="btn-check" name="card-layout" id="card-with-border" autocomplete="off" />
                        <label class="btn p-9 btn-outline-primary rounded-2" for="card-with-border">
                            <i class="icon ti ti-border-outer fs-7 me-2"></i>Border
                        </label>

                        <input type="radio" class="btn-check" name="card-layout" id="card-without-border" autocomplete="off" />
                        <label class="btn p-9 btn-outline-primary rounded-2" for="card-without-border">
                            <i class="icon ti ti-border-none fs-7 me-2"></i>Shadow
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

        <!--  Search Bar -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-bottom">
                        <input type="search" class="form-control" placeholder="Search here" id="search" />
                        <a href="javascript:void(0)" data-bs-dismiss="modal" class="lh-1">
                            <i class="ti ti-x fs-5 ms-3"></i>
                        </a>
                    </div>
                    <div class="modal-body message-body" data-simplebar="">
                        <h5 class="mb-0 fs-5 p-1">Quick Page Links</h5>
                        <ul class="list mb-0 py-2">
                            <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
                                <a href="javascript:void(0)">
                                    <span class="text-dark fw-semibold d-block">Analytics</span>
                                    <span class="fs-2 d-block text-body-secondary">/dashboards/dashboard1</span>
                                </a>
                            </li>
                            <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
                                <a href="javascript:void(0)">
                                    <span class="text-dark fw-semibold d-block">eCommerce</span>
                                    <span class="fs-2 d-block text-body-secondary">/dashboards/dashboard2</span>
                                </a>
                            </li>
                            <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
                                <a href="javascript:void(0)">
                                    <span class="text-dark fw-semibold d-block">CRM</span>
                                    <span class="fs-2 d-block text-body-secondary">/dashboards/dashboard3</span>
                                </a>
                            </li>
                            <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
                                <a href="javascript:void(0)">
                                    <span class="text-dark fw-semibold d-block">Contacts</span>
                                    <span class="fs-2 d-block text-body-secondary">/apps/contacts</span>
                                </a>
                            </li>
                            <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
                                <a href="javascript:void(0)">
                                    <span class="text-dark fw-semibold d-block">Posts</span>
                                    <span class="fs-2 d-block text-body-secondary">/apps/blog/posts</span>
                                </a>
                            </li>
                            <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
                                <a href="javascript:void(0)">
                                    <span class="text-dark fw-semibold d-block">Detail</span>
                                    <span class="fs-2 d-block text-body-secondary">/apps/blog/detail/streaming-video-way-before-it-was-cool-go-dark-tomorrow</span>
                                </a>
                            </li>
                            <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
                                <a href="javascript:void(0)">
                                    <span class="text-dark fw-semibold d-block">Shop</span>
                                    <span class="fs-2 d-block text-body-secondary">/apps/ecommerce/shop</span>
                                </a>
                            </li>
                            <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
                                <a href="javascript:void(0)">
                                    <span class="text-dark fw-semibold d-block">Modern</span>
                                    <span class="fs-2 d-block text-body-secondary">/dashboards/dashboard1</span>
                                </a>
                            </li>
                            <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
                                <a href="javascript:void(0)">
                                    <span class="text-dark fw-semibold d-block">Dashboard</span>
                                    <span class="fs-2 d-block text-body-secondary">/dashboards/dashboard2</span>
                                </a>
                            </li>
                            <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
                                <a href="javascript:void(0)">
                                    <span class="text-dark fw-semibold d-block">Contacts</span>
                                    <span class="fs-2 d-block text-body-secondary">/apps/contacts</span>
                                </a>
                            </li>
                            <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
                                <a href="javascript:void(0)">
                                    <span class="text-dark fw-semibold d-block">Posts</span>
                                    <span class="fs-2 d-block text-body-secondary">/apps/blog/posts</span>
                                </a>
                            </li>
                            <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
                                <a href="javascript:void(0)">
                                    <span class="text-dark fw-semibold d-block">Detail</span>
                                    <span class="fs-2 d-block text-body-secondary">/apps/blog/detail/streaming-video-way-before-it-was-cool-go-dark-tomorrow</span>
                                </a>
                            </li>
                            <li class="p-1 mb-1 bg-hover-light-black rounded px-2">
                                <a href="javascript:void(0)">
                                    <span class="text-dark fw-semibold d-block">Shop</span>
                                    <span class="fs-2 d-block text-body-secondary">/apps/ecommerce/shop</span>
                                </a>
                            </li>
                        </ul>
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
    <script src="../assets/js/theme/sidebarmenu.js"></script>

    <!-- solar icons -->
    <script src="../assets/js/iconify-icon%401.0.8/dist/iconify-icon.min.js"></script>
    <script src="../assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="../assets/js/dashboards/dashboard.js"></script>
</body>

</html>