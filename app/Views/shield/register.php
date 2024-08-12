<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('Auth.register') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>

<div class="position-relative overflow-hidden radial-gradient min-vh-100 w-100">
    <div class="position-relative z-index-5">
        <div class="row">
            <div class="col-xl-5 col-xxl-4">
                <div class="authentication-login min-vh-100 bg-body row justify-content-center">
                    <div class="col-12">
                        <a href="<?=base_url('/')?>" class="text-nowrap logo-img d-block px-4 py-9 w-100">
                            <img src="assets/images/logos/dark-logo.png" class="dark-logo" alt="Logo-Dark" />
                            <img src="assets/images/logos/light-logo.png" class="light-logo" alt="Logo-light" />
                        </a>
                    </div>

                    <div class="auth-max-width col-sm-8 col-md-6 col-xl-7 px-4">
                        <h2 class="mb-1 fs-7 fw-bolder"><?= lang('Auth.title') ?></h2>
                        <p class="mb-7"><?= lang('Auth.register') ?></p>

                        <?php if (session('error') !== null) : ?>
                            <div class="alert alert-danger" role="alert"><?= session('error') ?></div>
                        <?php elseif (session('errors') !== null) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?php if (is_array(session('errors'))) : ?>
                                    <?php foreach (session('errors') as $error) : ?>
                                        <?= $error ?>
                                        <br>
                                    <?php endforeach ?>
                                <?php else : ?>
                                    <?= session('errors') ?>
                                <?php endif ?>
                            </div>
                        <?php endif ?>

                        <form action="<?= url_to('register') ?>" method="post">
                            <?= csrf_field() ?>

                            <!-- Email -->
                            <div class="form-floating mb-2">
                                <input type="email" class="form-control" id="floatingEmailInput" name="email" inputmode="email" autocomplete="email" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email') ?>" required>
                                <label for="floatingEmailInput"><?= lang('Auth.email') ?></label>
                            </div>

                            <!-- Username -->
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="floatingUsernameInput" name="username" inputmode="text" autocomplete="username" placeholder="<?= lang('Auth.username') ?>" value="<?= old('username') ?>" required>
                                <label for="floatingUsernameInput"><?= lang('Auth.username') ?></label>
                            </div>

                            <!-- Password -->
                            <div class="form-floating mb-2">
                                <input type="password" class="form-control" id="floatingPasswordInput" name="password" inputmode="text" autocomplete="new-password" placeholder="<?= lang('Auth.password') ?>" required>
                                <label for="floatingPasswordInput"><?= lang('Auth.password') ?></label>
                            </div>

                            <!-- Password (Again) -->
                            <div class="form-floating mb-5">
                                <input type="password" class="form-control" id="floatingPasswordConfirmInput" name="password_confirm" inputmode="text" autocomplete="new-password" placeholder="<?= lang('Auth.passwordConfirm') ?>" required>
                                <label for="floatingPasswordConfirmInput"><?= lang('Auth.passwordConfirm') ?></label>
                            </div>

                            <div class="d-grid col-12 col-md-8 mx-auto m-3">
                                <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.register') ?></button>
                            </div>

                            <p class="text-center"><?= lang('Auth.haveAccount') ?> <a href="<?= url_to('login') ?>"><?= lang('Auth.login') ?></a></p>

                        </form>


                    </div>

                </div>
            </div>
            <div class="col-xl-7 col-xxl-8 d-none d-xl-block">
                <div class="d-none d-xl-flex align-items-center justify-content-center h-100">
                    <img src="assets/images/backgrounds/user-login.png" alt="" class="img-fluid">
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