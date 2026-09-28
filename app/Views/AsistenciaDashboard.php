<?= $this->extend('layouts/layoutMain'); ?>

<?= $this->section('title'); ?>
<?= isset($title) ? $title : lang('Main.appTitle'); ?>

<?= $this->endSection(); ?>

<?php
$auth = service('auth');
$user = $auth->user();

?>
<?= $this->section('username'); ?>
<?= $user->username; ?>
<?= $this->endSection(); ?>




<?= $this->section('profile'); ?>
<img src="assets/images/profile/<?= $user->photo ?>" class="rounded-circle" width="80" height="80" alt="" />
<div class="ms-3">
    <h5 class="mb-1 fs-4"><?= $user->username; ?></h5>
    <span class="mb-1 d-block"><?= $user->cargo; ?></span>
    <p class="mb-0 d-flex align-items-center gap-2">
        <i class="ti ti-mail fs-4"></i> <?= $user->email; ?>
    </p>
</div>
<?= $this->endSection(); ?>

<!-- Menu Vertical -->
<?= $this->section('menuVertical'); ?>

<?= view_cell('App\Cells\MenuCell::menuVertical') ?>


<?= $this->endSection(); ?>

<!-- Menu Horizontal -->
<?= $this->section('menuHorizontal'); ?>

<?= view_cell('App\Cells\MenuCell::menuHorizontal') ?>

<?= $this->endSection(); ?>


<?= $this->section('content'); ?>

<?= !empty($vista) ? view($vista) : '<h1>Bienvenido al Dashboard</h1>'; ?>

<?= $this->endSection(); ?>

<?= $this->section('pageScripts'); ?>


<?= $this->endSection(); ?>