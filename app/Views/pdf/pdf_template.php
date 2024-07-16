<!-- 
 -- MODIFICA AQUI EL FORMATO PARA LOS PDFS
 -- DE REPORTE 
 -->
<!-- CODE HERE -->
<h1>Constancia de Presentación</h1>
<p>N° Expediente: <?= $expediente['numero_expediente'] ?></p>
<p>Nombre: <?= $expediente['entidad_id'] ?></p>
<p>Fecha: <?= $expediente['fecha_recepcion'] ?></p>
<p>Asunto: <?= $expediente['asunto'] ?></p>

<?php
    //$this->extend('layouts/pdf') ;
?>


<?= $this->section('title'); ?>
<?= $expediente['numero_expediente'] ?>
<?= $this->endSection(); ?>

<?= $this->Section('content'); ?>
<div class="card-body" id="formResponse">
    <div class="row justify-content-center ">
        <div class="col-xl-6 col-lg-8 col-md-8 col-sm-12">
            <div class="card w-100">
                <div class="card-header text-bg-success">
                    <h4 class="mb-0 text-white card-title">Información de su Expediente </h4>
                </div>
                <div class="card-body">

                    <div class="card text-center">
                        <div class="card-body">
                            <img src="../assets/images/profile/user-3.jpg" class="rounded img-fluid" width="90">
                            <div class="mt-n2">
                                <span class="badge text-bg-primary">Remitente</span>
                                <h3 class="card-title mt-3">KEVIN ARNOLD LARUTA CALCINA</h3>
                                <p class="card-subtitle">kalc.06.18.95@gmail.com</p>
                            </div>
                            <div class="row mt-3 justify-content-center">
                                <div class="col-md-6">
                                    <div class="py-2 px-3 text-bg-light rounded-1 d-flex align-items-center">
                                        <span class="text-primary">
                                            <i class="ti ti-files fs-8"></i>
                                        </span>
                                        <div class="ms-2 text-start">

                                            <h4 class="mb-0 fs-5">Exp: 015204</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="py-2 px-3 text-bg-light rounded-1 d-flex align-items-center">
                                        <span class="text-primary">
                                            <i class="ti ti-calendar fs-8"></i>
                                        </span>
                                        <div class="ms-2 text-start">

                                            <h4 class="mb-0 fs-5">2024-07-16</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="card-text">
                        Información adicional </p>

                    <div class="vstack gap-3">
                        <div class="hstack gap-6 border-bottom pb-3">
                            <div class="text-primary round-48 rounded-1 hstack justify-content-center">
                                <i class="ti ti-pin fs-7"></i>
                            </div>
                            <div>
                                <h4 class="fs-4 mb-0">ALGUN ASUNTO INTERESANTE</h4>
                                <p class="mb-0">
                                    Asunto </p>
                            </div>
                        </div>

                        <div class="hstack gap-6 border-bottom pb-3">
                            <div class=" text-primary round-48 rounded-1 hstack justify-content-center">
                                <i class="ti ti-files fs-7"></i>
                            </div>
                            <div>
                                <h4 class="fs-4 mb-0">Carta 12 : <a href="C:\wamp64\www\clouDoc\writable\uploads/1721137740_da36ef6539c928ee36b3.pdf" target="_blank" class="
                                "> <i class="ti ti-file-text fs-4"></i> Exp_015204.pdf
                                    </a>
                                </h4>
                                <p class="mb-0">
                                    Tipo de Documento </p>
                            </div>
                        </div>

                        <div class="hstack gap-6 border-bottom pb-3">
                            <div class="text-primary round-48 rounded-1 hstack justify-content-center">
                                <i class="ti ti-tag fs-7"></i>
                            </div>
                            <div>
                                <h4 class="fs-4 mb-0">1</h4>
                                <p class="mb-0">
                                    Nro. de Folios </p>
                            </div>
                        </div>


                    </div>
                    <div class="btn-group me-2 mb-2 w-100 " role="group" aria-label="First group">
                        <a href="http://localhost:8080/cargoexpediente/15646" target="_blank" class="btn bg-primary-subtle btn-lg">
                            <i class="ti ti-printer"></i> Imprimir
                        </a>
                        <a href="http://localhost:8080/nuevoexpediente" class="btn bg-secondary-subtle btn-lg">
                            <i class="ti ti-arrow-right"></i> Nuevo </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>