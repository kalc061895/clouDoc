<?= $this->extend('layouts/external'); ?>

<?= $this->section('title'); ?>
<?= lang('Main.appTitle') ?>
<?= $this->endSection(); ?>


<?= $this->section('content'); ?>


<!-- start banner -->
<section class="hero-section position-relative overflow-hidden mb-0 mb-lg-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-8">
                <div class="hero-content my-5 my-xl-0 ">
                    <h6 class="d-flex align-items-center gap-2 fs-4 fw-semibold mb-3" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000">
                        <i class="ti ti-rocket fs-6"></i><?= lang('External.appTitle') ?>
                    </h6>
                    <h2 class="fw-mediumer mb-7 fs-14 justify-content-center" data-aos="fade-up" data-aos-delay="400" data-aos-duration="1000">
                        <?= lang('External.nameEntity') ?>
                        <span class="text-primary"><?= lang('External.descriptionHomepage') ?></span>
                    </h2>

                </div>
            </div>
            <div class="col-xl-4">

            </div>
            <div class="row text-center justify-content-around " data-aos="fade-up" data-aos-delay="600" data-aos-duration="1000">
                <h4 class="fs-5 mt-5 mb-3"><?= lang('External.opciones') ?></h4>
                <?=view('components/cardbg', ['bgColor' => 'primary', 'icon' => 'pen-new-square-line-duotone', 'title' => lang('External.nuevoTramite'), 'amount' => '$900.00k', 'url'=>base_url('/nuevoexpediente')]) ?>
                <?=view('components/cardbg', ['bgColor' => 'danger', 'icon' => 'card-search-line-duotone', 'title' => lang('External.buscarTramite'), 'amount' => '$284.50k', 'url'=>base_url('/buscarexpediente')]) ?>
                <?=view('components/cardbg', ['bgColor' => 'info', 'icon' => 'clipboard-list-line-duotone', 'title' => lang('External.tupaTramite'), 'amount' => '$650.80k','url'=>base_url('/tupaexpediente')]) ?>
                

                <p class="fs-5 mb-5 text-dark fw-normal w-85">
                    <?= lang('External.hourEntity') ?>
                </p>
            </div>
        </div>
    </div>
</section>
<!-- end banner -->
<?= $this->endSection(); ?>