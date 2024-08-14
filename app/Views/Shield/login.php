<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('Auth.login') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>


<div class="auth-max-width col-sm-8 col-md-6 col-xl-7 px-4">
    <h2 class="mb-1 fs-7 fw-bolder"><?= lang('Auth.title') ?></h2>
    <p class="mb-7"><?= lang('Auth.login') ?></p>
    <!--div class="row">
        <div class="col-6 mb-2 mb-sm-0">
            <a class="btn text-dark border fw-normal d-flex align-items-center justify-content-center rounded-2 py-8" href="javascript:void(0)" role="button">
                <img src="assets/images/svgs/google-icon.svg" alt="" class="img-fluid me-2" width="18" height="18">
                <span class="flex-shrink-0">with Google</span>
            </a>
        </div>
        <div class="col-6">
            <a class="btn text-dark border fw-normal d-flex align-items-center justify-content-center rounded-2 py-8" href="javascript:void(0)" role="button">
                <img src="assets/images/svgs/facebook-icon.svg" alt="" class="img-fluid me-2" width="18" height="18">
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


<?= $this->endSection() ?>

<?= $this->section('pageScripts'); ?>

<?= $this->endSection(); ?>