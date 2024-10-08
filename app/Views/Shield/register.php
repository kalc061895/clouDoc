<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('Auth.register') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>


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


<?= $this->endSection() ?>