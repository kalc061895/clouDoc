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
                <div class="hero-content my-5 my-xl-0">
                    <h6 class="d-flex align-items-center gap-2 fs-4 fw-semibold mb-3" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000">
                        <i class="ti ti-rocket fs-6"></i><?= lang('External.appTitle') ?>
                    </h6>
                    <h1 class="fw-mediumer mb-7 fs-14" data-aos="fade-up" data-aos-delay="400" data-aos-duration="1000">
                        <?= lang('External.nameEntity') ?>
                        <span class="text-primary"><?= lang('External.descriptionHomepage') ?></span>
                    </h1>

                </div>
            </div>
            <div class="col-xl-4">

            </div>
            <div class="row text-center justify-content-around " data-aos="fade-up" data-aos-delay="600" data-aos-duration="1000">
                <h4 class="fs-5 mt-5 mb-3"><?= lang('External.opciones') ?></h4>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card text-bg-primary text-white card-hover">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <a href="JavaScript: void(0);">
                                    <i class="ti ti-currency-dogecoin display-6 text-white" title="LTC"></i>
                                </a>
                                <div class="ms-3">
                                    <h4 class="card-title mb-0 text-white">
                                        Dash
                                    </h4>
                                    <p class="text-white fs-4 mb-0 opacity-75">$900.00k</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card text-bg-danger text-white card-hover">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <a href="JavaScript: void(0);">
                                    <i class="ti ti-currency-bitcoin display-6 text-white" title="BTC"></i>
                                </a>
                                <div class="ms-3">
                                    <h4 class="card-title mb-0 text-white">
                                        Bitcoin
                                    </h4>
                                    <p class="text-white fs-4 mb-0 opacity-75">$284.50k</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card text-bg-info text-white card-hover">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <a href="JavaScript: void(0);">
                                    <i class="ti ti-currency-ripple display-6 text-white" title="AMP"></i>
                                </a>
                                <div class="ms-3">
                                    <h4 class="card-title mb-0 text-white">
                                        Ripple
                                    </h4>
                                    <p class="text-white fs-4 mb-0 opacity-75">$650.80k</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="fs-5 mb-5 text-dark fw-normal w-85" >
                    <?= lang('External.hourEntity') ?>  
                </p>
            </div>
        </div>
    </div>
</section>
<!-- end banner -->
<?= $this->endSection(); ?>