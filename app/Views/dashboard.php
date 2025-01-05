<?= $this->extend('layouts/main'); ?>

<?= $this->section('title'); ?>
<?= lang('Main.appTitle') ?>
<?= $this->endSection(); ?>

<?php
$auth = service('auth');
$user = $auth->user();

// Obtener que menu es el que esta activo
$menuActive = 2;

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

<?php foreach ($menu as $item) : ?>

    <?php if ($item['type'] == 'primary') : ?>
        <!-- MENU DE TODO---------------------------------- -->
        <li class="nav-small-cap">
            <iconify-icon icon="solar:menu-dots-linear" class="mini-icon"></iconify-icon>
            <span class="hide-menu"><?= $item['name'] ?></span>
        </li>
    <?php else : ?>
        <li class="sidebar-item">
            <a class="sidebar-link open-here <?= ($item['id'] == $menuActive) ? ' active ' : '' ?>" href="<?= base_url('/' . $item['url']) ?>" aria-expanded="false" accesskey="<?= $item['abbr']?>">
                <iconify-icon icon="solar:<?= $item['icon'] ?>"></iconify-icon>
                <span class="hide-menu"><?= $item['name'] ?></span>
            </a>
        </li>
    <?php endif ?>



<?php endforeach ?>

<?= $this->endSection(); ?>

<!-- Menu Horizontal -->
<?= $this->section('menuHorizontal'); ?>

<?= $this->endSection(); ?>


<?= $this->section('content'); ?>

<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title fs-4 fw-normal mb-3">
                    <?= lang('Main.totalExpedientes') ?>
                </h4>
                <h2 class="mb-3 fs-9 fw-light" id="totalSemanal">$56,908</h2>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="fas fa-caret-down text-warning fs-5"></i>
                    <span class="text-muted fs-3 fw-medium" id="diferenciaSemanal">$45,987</span>
                </div>
                <div class="progress bg-warning-subtle" style="height: 4px">
                    <div class="progress-bar w-70 text-bg-warning" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title fs-4 fw-normal mb-3">
                    <?= lang('Main.totalExpedientesHoy') ?>
                </h4>
                <h2 class="mb-3 fs-9 fw-light" id="totalHoy">
                    10.578 <span class="fs-5 fw-medium"></span>
                </h2>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="fas fa-caret-up text-indigo fs-5"></i>
                    <span class="text-muted fs-3 fw-medium" id="diferenciaHoy">567 Kwh</span>
                </div>
                <div class="progress bg-indigo-subtle" style="height: 4px">
                    <div class="progress-bar w-70 text-bg-secondary" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title fs-4 fw-normal mb-3">
                    <?= lang('Main.almacenamientoDrive') ?>
                </h4>
                <h2 class="mb-3 fs-9 fw-light">
                    <span class="fs-9 fw-light" id="totalAlmacenamiento">14158</span>
                    <span class="fs-5 fw-medium">Gb</span>
                </h2>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="fas fa-caret-down text-danger fs-5"></i>
                    <span class="text-muted fs-3 fw-medium" id="diferenciaAlmacenamiento">467 Gb</span>
                </div>
                <div class="progress bg-danger-subtle" style="height: 4px">
                    <div class="progress-bar w-70 text-bg-danger" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title fs-4 fw-normal mb-3"><?= lang('Main.totalAtendidos') ?></h4>
                <h2 class="mb-3 fs-9 fw-light" id="totalAtendidos">$995,204</h2>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="fas fa-caret-up text-success fs-5"></i>
                    <span class="text-muted fs-3 fw-medium" id="diferenciaAtendidos">$45,987</span>
                </div>
                <div class="progress bg-success-subtle" style="height: 4px">
                    <div class="progress-bar w-70 text-bg-success" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex align-items-center flex-wrap">
                            <div>
                                <h4 class="card-title"><?= lang('Main.chartSemanalTitle') ?></h3>
                                    <p class="card-subtitle"><?= date('F Y') ?></p>
                            </div>
                            <div class="ms-auto">

                                <ul class="list-unstyled mb-0 hstack gap-3">
                                    <li>
                                        <h6 class="text-muted mb-0 hstack gap-2 fw-medium">
                                            <span class="text-bg-success round-10 rounded-circle"></span><?= lang('Main.chartFisicos') ?>
                                        </h6>
                                    </li>
                                    <li>
                                        <h6 class="text-muted mb-0 hstack gap-2 fw-medium">
                                            <span class="text-bg-info round-10 rounded-circle"></span><?= lang('Main.chartVirtuales') ?>
                                        </h6>
                                    </li>
                                    <li>
                                        <h6 class="text-muted mb-0 hstack gap-2 fw-medium">
                                            <span class="text-bg-info round-10 rounded-circle"></span><?= lang('Main.chartAtendidos') ?>
                                        </h6>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div id="revenue-statistics" class="mx-n3"></div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4 mt-3 text-center">
                        <h2 class="fw-medium" id="hoyFisicos">$54,578</h2>
                        <p class="text-muted mb-0 fs-3"><?= lang('Main.hoyFisicos') ?></p>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4 mt-3 text-center">
                        <h2 class="fw-medium" id="hoyVirtual">$43,451</h2>
                        <p class="text-muted mb-0 fs-3"><?= lang('Main.hoyVirtual') ?></p>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4 mt-3 text-center">
                        <h2 class="fw-medium " id="hoyAtendidos">$44,578</h2>
                        <p class="text-muted mb-0 fs-3"><?= lang('Main.hoyAtendidos') ?></p>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4 mt-3 text-center">
                        <h2 class="fw-medium" id="hoyEmitidos">$12,578</h2>
                        <p class="text-muted mb-0 fs-3"><?= lang('Main.hoyEmitidos') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection(); ?>

<?= $this->section('pageScripts'); ?>

<script>
    // -----------------------------------------------------------------------
    // Revenue Statistics
    // -----------------------------------------------------------------------

    function loadDashboardData() {
        $.ajax({
            url: '<?= base_url('reportes/semanal') ?>',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                // Asume que el response es un objeto con arrays para ventas, pedidos y entregas
                var salesData = response.sales;
                var ordersData = response.orders;
                var deliveriesData = response.deliveries;
                var categories = response.categories; // Fechas de la última semana
                var params = response.params; // Fechas de la última semana

                // Llama a la función para renderizar el gráfico
                renderChart(salesData, ordersData, deliveriesData, categories);
                renderData(salesData, ordersData, deliveriesData, categories, params);
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud AJAX:', error);
            }
        });
    }
    var Sales_Statistics;
    var chart_area_spline;

    function renderChart(salesData, ordersData, deliveriesData, categories) {
        Sales_Statistics = {
            series: [{
                    name: "Físicos",
                    data: salesData
                },
                {
                    name: "Virtuales",
                    data: ordersData
                },
                {
                    name: "Atendidos",
                    data: deliveriesData
                }
            ],
            chart: {
                fontFamily: "inherit",
                type: "area",
                height: 350,
                toolbar: {
                    show: false
                },
                animations: {
                    enabled: true,
                    easing: 'linear',
                    dynamicAnimation: {
                        speed: 5000
                    }
                },
            },
            plotOptions: {},
            legend: {
                show: true
            },
            dataLabels: {
                enabled: false
            },
            fill: {
                type: "solid",
                opacity: 0.07,
                colors: ["#04B440", "#009EFB", "#FF5733"]
            },
            stroke: {
                curve: "smooth",
                show: true,
                width: 2,
                colors: ["#04B440", "#009EFB", "#FF5733"]
            },
            xaxis: {
                categories: categories,
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                tickAmount: 6,
                labels: {
                    rotate: 0,
                    rotateAlways: true,
                    style: {
                        fontSize: "12px",
                        colors: "#a1aab2"
                    }
                },
                crosshairs: {
                    position: "front",
                    stroke: {
                        color: ["var(--bs-success)", "var(--bs-info)"],
                        width: 1,
                        dashArray: 3
                    }
                },
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: "12px",
                        colors: "#a1aab2"
                    }
                }
            },
            states: {
                normal: {
                    filter: {
                        type: "none",
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: "none",
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: "none",
                        value: 0
                    }
                }
            },
            tooltip: {
                theme: "dark"
            },
            colors: ["var(--bs-success)", "var(--bs-info)", "var(--bs-warning)"],
            grid: {
                borderColor: "var(--bs-border-color)",
                strokeDashArray: 4,
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            markers: {
                strokeColor: ["var(--bs-success)", "var(--bs-info)", "var(--bs-warning)"],
                strokeWidth: 3
            },
        };

        chart_area_spline = new ApexCharts(
            document.querySelector("#revenue-statistics"),
            Sales_Statistics
        );
        chart_area_spline.render();
    }


    function renderData(salesData, ordersData, deliveriesData, categories, params) {
        chart_area_spline.updateSeries([
            {
                data: salesData,
            },
            {
                data: ordersData,
            },
            {
                data: deliveriesData,
            }
        ]);
        $('#totalSemanal').html(params.totalSemanal);
        $('#diferenciaSemanal').html(params.diferenciaSemanal);
        $('#totalHoy').html(params.totalHoy);
        $('#diferenciaHoy').html(params.diferenciaHoy);
        $('#totalAlmacenamiento').html(params.totalAlmacenamiento);
        $('#diferenciaAlmacenamiento').html(params.diferenciaAlmacenamiento);
        $('#totalAtendidos').html(params.totalAtendidos);
        $('#diferenciaAtendidos').html(params.diferenciaAtendidos);

        $('#hoyFisicos').html(params.hoyFisicos);
        $('#hoyVirtual').html(params.hoyVirtual);
        $('#hoyAtendidos').html(params.hoyAtendidos);
        $('#hoyEmitidos').html(params.hoyEmitidos);

    }
    loadDashboardData();

    chartInterval = setInterval(loadDashboardData, 5000);
</script>

<?= $this->endSection(); ?>