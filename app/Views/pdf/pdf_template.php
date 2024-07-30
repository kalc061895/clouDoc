<!-- 
 -- MODIFICA AQUI EL FORMATO PARA LOS PDFS
 -- DE REPORTE 
 -->
<!-- CODE HERE -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=}, initial-scale=1.0">
    <title>Resumen de Expediente</title>
    <link rel="stylesheet" href="assets/css/styles.css" />
    <style>
        .card-body {
            flex: 1 1 auto;
            padding: var(--bs-card-spacer-y) var(--bs-card-spacer-x);
            color: var(--bs-card-color);
        }

        *,
        ::after,
        ::before {
            box-sizing: border-box;
        }

        div[Estilo de atributos] {}

        hoja de estilo del usuario-agente div {
            display: block;
            unicode-bidi: isolate;
        }

        .card {
            --bs-card-spacer-y: 24px;
            --bs-card-spacer-x: 24px;
            --bs-card-title-spacer-y: 0.5rem;
            --bs-card-title-color: #3A3F4F;
            --bs-card-subtitle-color: #8392a5;
            --bs-card-border-width: 0px;
            --bs-card-border-color: transparent;
            --bs-card-border-radius: 7px;
            --bs-card-box-shadow: 0px 3px 4px 0px rgba(0, 0, 0, 0.03), 0px 0px 1px 0px rgba(0, 0, 0, 0.1);
            --bs-card-inner-border-radius: 7px;
            --bs-card-cap-padding-y: 12px;
            --bs-card-cap-padding-x: 24px;
            --bs-card-cap-bg: rgba(var(--bs-body-color-rgb), 0.03);
            --bs-card-bg: #fff;
            --bs-card-img-overlay-padding: 1rem;
            --bs-card-group-margin: 15px;
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            height: var(--bs-card-height);
            color: var(--bs-body-color);
            word-wrap: break-word;
            background-color: var(--bs-card-bg);
            background-clip: border-box;
            border: var(--bs-card-border-width) solid var(--bs-card-border-color);
            border-radius: var(--bs-card-border-radius);
            box-shadow: var(--bs-card-box-shadow);
        }

        .row {
            --bs-gutter-x: 30px;
            --bs-gutter-y: 0;
            display: flex;
            flex-wrap: wrap;
            margin-top: calc(-1* var(--bs-gutter-y));
            margin-right: calc(-.5* var(--bs-gutter-x));
            margin-left: calc(-.5* var(--bs-gutter-x));
        }

        body {
            font-family: Inter, sans-serif !important;
        }

        body {
            margin: 0;
            font-family: var(--bs-body-font-family);
            font-size: var(--bs-body-font-size);
            font-weight: var(--bs-body-font-weight);
            line-height: var(--bs-body-line-height);
            color: var(--bs-body-color);
            text-align: var(--bs-body-text-align);
            background-color: var(--bs-body-bg);
            -webkit-text-size-adjust: 100%;
            -webkit-tap-highlight-color: transparent;
        }
    </style>
</head>

<body>
    <div class="row justify-content-center ">
        <div class="col-xl-6 col-lg-8 col-md-8 col-sm-12">
            <div class="card w-100">
                <div class="card-header text-bg-success">
                    <h4 class="mb-0 text-white card-title"><?= lang('External.titleSuccess') ?> </h4>
                </div>
                <div class="card-body">

                    <div class="card text-center">
                        <div class="card-body">
                            <img src="assets/images/profile/user-3.jpg" class="rounded img-fluid" width="90">
                            <div class="mt-n2">
                                <span class="badge text-bg-primary"><?= lang('External.emisorSuccess') ?></span>
                                <h3 class="card-title mt-3"><?= $entidad['nombre'] ?></h3>
                                <p class="card-subtitle"><?= $entidad['correo_electronico'] ?></p>
                            </div>
                            <div class="row mt-3 justify-content-center">
                                <div class="col-md-6">
                                    <div class="py-2 px-3 text-bg-light rounded-1 d-flex align-items-center">
                                        <span class="text-primary">
                                            <i class="ti ti-files fs-8"></i>
                                        </span>
                                        <div class="ms-2 text-start">

                                            <h4 class="mb-0 fs-5">Exp: <?= $expediente['numero_expediente'] ?></h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="py-2 px-3 text-bg-light rounded-1 d-flex align-items-center">
                                        <span class="text-primary">
                                            <i class="ti ti-calendar fs-8"></i>
                                        </span>
                                        <div class="ms-2 text-start">

                                            <h4 class="mb-0 fs-5"><?= substr($expediente['fecha_recepcion'], 0, 16) ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="card-text">
                        <?= lang('External.infoSuccess') ?>
                    </p>

                    <div class="vstack gap-3">
                        <div class="hstack gap-6 border-bottom pb-3">
                            <div class="text-primary round-48 rounded-1 hstack justify-content-center">
                                <i class="ti ti-pin fs-7"></i>
                            </div>
                            <div>
                                <h4 class="fs-4 mb-0"><?= $expediente['asunto'] ?></h4>
                                <p class="mb-0">
                                    <?= lang('External.asuntoDocExp') ?>
                                </p>
                            </div>
                        </div>


                        <div class="hstack gap-6 border-bottom pb-3">
                            <div class="text-primary round-48 rounded-1 hstack justify-content-center">
                                <i class="ti ti-tag fs-7"></i>
                            </div>
                            <div>
                                <h4 class="fs-4 mb-0"><?= $expediente['folios'] ?></h4>
                                <p class="mb-0">
                                    <?= lang('External.folioDocExp') ?>
                                </p>
                            </div>
                        </div>


                    </div>
                    <div class="btn-group me-2 mb-2 w-100 " role="group" aria-label="First group">
                        <a href="<?= base_url('/cargoexpediente/' . $expediente['id']) ?>" target="_blank" class="btn bg-primary-subtle btn-lg">
                            <i class="ti ti-printer"></i> Imprimir
                        </a>
                        <a href="<?= base_url('/nuevoexpediente') ?>" class="btn bg-secondary-subtle btn-lg">
                            <i class="ti ti-arrow-right"></i> Nuevo </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>