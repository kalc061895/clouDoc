<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('Auth.login') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>

<div class="position-relative overflow-hidden radial-gradient min-vh-100 w-100">
    <div class="position-relative z-index-5">
        <div class="row">
            <div class="col-xl-5 col-xxl-4">
                <div class="authentication-login min-vh-100 bg-body row justify-content-center">
                    <div class="col-12">
                        <a href="index.html" class="text-nowrap logo-img d-block px-4 py-9 w-100">
                            <img src="../assets/images/logos/dark-logo.svg" class="dark-logo" alt="Logo-Dark" />
                            <img src="../assets/images/logos/light-logo.svg" class="light-logo" alt="Logo-light" />
                        </a>
                    </div>
                    <div class="auth-max-width col-sm-8 col-md-6 col-xl-7 px-4">
                        <h2 class="mb-1 fs-7 fw-bolder"><?= lang('Auth.title') ?></h2>
                        <p class="mb-7"><?= lang('Auth.login') ?></p>
                        <!--div class="row">
                            <div class="col-6 mb-2 mb-sm-0">
                                <a class="btn text-dark border fw-normal d-flex align-items-center justify-content-center rounded-2 py-8" href="javascript:void(0)" role="button">
                                    <img src="../assets/images/svgs/google-icon.svg" alt="" class="img-fluid me-2" width="18" height="18">
                                    <span class="flex-shrink-0">with Google</span>
                                </a>
                            </div>
                            <div class="col-6">
                                <a class="btn text-dark border fw-normal d-flex align-items-center justify-content-center rounded-2 py-8" href="javascript:void(0)" role="button">
                                    <img src="../assets/images/svgs/facebook-icon.svg" alt="" class="img-fluid me-2" width="18" height="18">
                                    <span class="flex-shrink-0">with FB</span>
                                </a>
                            </div>
                        </div-->
                        <?php if (session('error') !== null) : ?>
                            <div class="alert customize-alert alert-dismissible alert-light-danger bg-danger-subtle text-danger fade show remove-close-icon" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <div class="d-flex align-items-center  me-3 me-md-0">
                                    <i class="ti ti-info-circle fs-5 me-2 text-danger"></i>
                                    <?= session('error') ?>
                                </div>
                            </div>
                        <?php elseif (session('errors') !== null) : ?>
                            <div class="alert customize-alert alert-dismissible alert-light-danger bg-danger-subtle text-danger fade show remove-close-icon" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <div class="d-flex align-items-center  me-3 me-md-0">
                                    <i class="ti ti-info-circle fs-5 me-2 text-danger"></i>
                                    <?php if (is_array(session('errors'))) : ?>
                                        <?php foreach (session('errors') as $error) : ?>
                                            <?= $error ?>
                                            <br>
                                        <?php endforeach ?>
                                    <?php else : ?>
                                        <?= session('errors') ?>
                                    <?php endif ?>
                                </div>
                            </div>
                        <?php endif ?>
                        <?php if (session('message') !== null) : ?>
                            <div class="alert alert-success" role="alert"></div>
                            <div class="alert customize-alert alert-dismissible text-success alert-light-success bg-success-subtle fade show remove-close-icon" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <div class="d-flex align-items-center  me-3 me-md-0">
                                    <i class="ti ti-info-circle fs-5 me-2 text-success"></i><?= session('message') ?>
                                </div>
                            </div>
                        <?php endif ?>
                        <form action="<?= url_to('login') ?>" method="post">
                            <?= csrf_field() ?>

                            <!-- Email -->
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="floatingEmailInput" name="email" inputmode="email" autocomplete="email" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email') ?>" required>
                                <label for="floatingEmailInput"><?= lang('Auth.email') ?></label>
                            </div>
                            <!-- Password -->
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="floatingPasswordInput" name="password" inputmode="text" autocomplete="current-password" placeholder="<?= lang('Auth.password') ?>" required>
                                <label for="floatingPasswordInput"><?= lang('Auth.password') ?></label>
                            </div>
                            <!-- Remember me -->
                            <?php if (setting('Auth.sessionConfig')['allowRemembering']) : ?>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" name="remember" class="form-check-input" <?php if (old('remember')) : ?> checked<?php endif ?>>
                                        <?= lang('Auth.rememberMe') ?>
                                    </label>
                                </div>
                            <?php endif; ?>
                            <div class="d-flex align-items-center justify-content-between mb-4">

                                <?php if (setting('Auth.allowMagicLinkLogins')) : ?>
                                    <p class="text-center"><?= lang('Auth.forgotPassword') ?> <a href="<?= url_to('magic-link') ?>"><?= lang('Auth.useMagicLink') ?></a></p>
                                <?php endif ?>
                            </div>
                            <div class="d-grid col-12 col-md-8 mx-auto m-3">
                                <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.login') ?></button>
                            </div>
                            <div class="d-flex align-items-center justify-content-center">
                                <?php if (setting('Auth.allowRegistration')) : ?>
                                    <p class="text-center"><?= lang('Auth.needAccount') ?> <a href="<?= url_to('register') ?>"><?= lang('Auth.register') ?></a></p>
                                <?php endif ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl-7 col-xxl-8 d-none d-xl-block">
                <div class="d-none d-xl-flex align-items-center justify-content-center h-100">
                    <img src="../assets/images/backgrounds/user-login.png" alt="" class="img-fluid">
                </div>
            </div>
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
<?= $this->endSection() ?>

<?= $this->section('pageScripts'); ?>

<?= $this->endSection(); ?>