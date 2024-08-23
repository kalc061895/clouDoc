<?= $this->extend('layouts/external'); ?>

<?= $this->section('title'); ?>
<?= lang('External.titleTupa') ?>

<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="row justify-content-center m-0">

    <div class="col-md-8 col-xl-6 ">
        <div class="card mb-0">
            <div class="text-bg-primary p-4 rounded-3 rounded-bottom-0">
                <div class="text-center text-white display-6">
                    <?= lang('External.titleTupa') ?>
                </div>
            </div>
            <div class="card-body">
                <div class="text-center mb-7">
                    <h3 class="fw-semibold"><?= lang('External.faqTitleTupa') ?></h3>
                    <p class="fw-normal mb-0 fs-4"><?= lang('External.descriptionTupa') ?></p>
                </div>
                <div class="accordion accordion-flush mb-5 card position-relative overflow-hidden" id="accordionFlushExample">
                    <?php if ($tupa != null) : ?>
                        <?php foreach ($tupa as $item) : ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button collapsed fs-4 fw-semibold shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse-<?=$item->id?>" aria-expanded="false" aria-controls="flush-collapse-<?=$item->id?>">
                                        What is an Admin Dashboard?
                                    </button>
                                </h2>
                                <div id="flush-collapse-<?=$item->id?>" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body fw-normal">
                                        Admin Dashboard is the backend interface of a website or an application that helps to manage the
                                        website's overall content and settings. It is widely used by the site owners to keep track of
                                        their website,
                                        make changes to their content, and more.
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    <?php endif ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button collapsed fs-4 fw-semibold shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                What is an Admin Dashboard?
                            </button>
                        </h2>
                        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body fw-normal">
                                Admin Dashboard is the backend interface of a website or an application that helps to manage the
                                website's overall content and settings. It is widely used by the site owners to keep track of
                                their website,
                                make changes to their content, and more.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingTwo">
                            <button class="accordion-button collapsed fs-4 fw-semibold shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                What should an admin dashboard template include?
                            </button>
                        </h2>
                        <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body fw-normal">
                                Admin dashboard template should include user & SEO friendly design with a variety of components
                                and
                                application designs to help create your own web application with ease. This could include
                                customization
                                options, technical support and about 6 months of future updates.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingThree">
                            <button class="accordion-button collapsed fs-4 fw-semibold shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                Why should I buy admin templates from Wrappixel?
                            </button>
                        </h2>
                        <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body fw-normal">
                                Wrappixel offers high-quality templates that are easy to use and fully customizable. With over
                                101,801
                                happy customers & trusted by 10,000+ Companies. AdminMart is recognized as the leading online
                                source
                                for buying admin templates.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingfour">
                            <button class="accordion-button collapsed fs-4 fw-semibold shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsefour" aria-expanded="false" aria-controls="flush-collapsefour">
                                Do Adminmart offers a money back guarantee?
                            </button>
                        </h2>
                        <div id="flush-collapsefour" class="accordion-collapse collapse" aria-labelledby="flush-headingfour" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body fw-normal">
                                There is no money back guarantee in most companies, but if you are unhappy with our product,
                                Adminmart
                                gives you a 100% money back guarantee.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?= $this->endSection(); ?>