<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('Auth.useMagicLink') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>

<div class="auth-max-width col-sm-8 col-md-6 col-xl-7 px-4">
    <div class="mb-5">
        <h2 class="fw-bolder fs-7 mb-3"><?= lang('Auth.useMagicLink') ?></h2>
        <p class="mb-0 ">

        </p>
    </div>
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
    <form action="<?= url_to('magic-link') ?>" method="post">
        <?= csrf_field() ?>
        <div class="form-floating mb-2">
            <input type="email" class="form-control" id="floatingEmailInput" name="email" autocomplete="email" placeholder="<?= lang('Auth.email') ?>"
                value="<?= old('email', auth()->user()->email ?? null) ?>" required>
            <label for="floatingEmailInput"><?= lang('Auth.email') ?></label>
        </div>
        <button type="submit" class="btn btn-primary w-100 py-8 mb-3"><?= lang('Auth.send') ?></button>
        <a href="<?= base_url('login') ?>" class="btn bg-primary-subtle text-primary w-100 py-8"><?= lang('Auth.backToLogin') ?></a>
    </form>
</div>

<?= $this->endSection() ?>