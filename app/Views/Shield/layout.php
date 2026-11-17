<?= $this->extend('layouts/portal') ?>
<?= $this->section('content') ?>
<section class="hero-section position-relative overflow-hidden py-5"><div class="container"><div class="row align-items-center g-5">
<div class="col-12 col-xl-6"><div class="card border shadow-none rounded"><div class="card-body p-4 p-lg-5 d-flex flex-column align-items-center"><?= $this->renderSection('main') ?></div></div></div>
<div class="col-xl-6 d-none d-xl-block"><aside class="bg-info-subtle p-5 rounded"><p class="text-primary fw-semibold"><?= esc(config('Portal')->entidad) ?></p><h1 class="fw-mediumer fs-10">Las personas y la gesti&oacute;n,<br><span class="text-primary">en un mismo lugar.</span></h1><p class="fs-5 mt-4">Una cuenta para acceder a los m&oacute;dulos habilitados seg&uacute;n su perfil institucional.</p><img src="<?= base_url('assets/images/backgrounds/user-login.png') ?>" alt="" class="img-fluid"></aside></div>
</div></div></section>
<?= $this->endSection() ?>
