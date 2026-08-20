<?= $this->extend('layouts/seleccionLayout') ?>

<?= $this->section('title') ?>
Ficha Digital de Inscripción
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card bg-white p-4 p-4">
    <!-- CABECERA -->
    <div class="d-flex flex-column flex-md-row
                align-items-md-center
                justify-content-between
                gap-3 mb-4">

        <div>
            <a href="<?= base_url('seleccion/postulacion') ?>"
                class="btn btn-outline-secondary btn-sm rounded-pill mb-2">

                <iconify-icon
                    icon="solar:arrow-left-linear"
                    class="align-middle">
                </iconify-icon>

                Volver al listado
            </a>

            <h4 class="fw-bold text-dark mb-1">
                Ficha Digital de Inscripción
            </h4>

            <div class="text-muted small">
                Complete y revise la información de su postulación
            </div>
        </div>

        <div>
            <span class="badge bg-primary px-3 py-2 rounded-pill">
                Proceso de Selección
            </span>
        </div>

    </div>
    <!-- RESUMEN DE POSTULACIÓN -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">
                    <div class="text-muted small">
                        Convocatoria
                    </div>

                    <div class="fw-bold">
                        <?= esc($convocatoriaId) ?>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="text-muted small">
                        Postulante
                    </div>

                    <div class="fw-bold">
                        <!-- cargar mediante AJAX -->
                        Por completar
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="text-muted small">
                        Estado de inscripción
                    </div>

                    <span class="badge bg-warning text-dark">
                        EN PROCESO
                    </span>
                </div>

            </div>

        </div>

    </div>
    <!-- TABS -->
    <div class="card border-0 shadow-sm rounded-3">

        <div class="card-header bg-white border-bottom p-0">

            <ul class="nav nav-tabs nav-fill"
                id="tabsPostulacion"
                role="tablist">

                <!-- 1 -->
                <li class="nav-item" role="presentation">

                    <button
                        class="nav-link active"
                        id="tab-plaza"
                        data-bs-toggle="tab"
                        data-bs-target="#panel-plaza"
                        type="button"
                        role="tab">

                        <iconify-icon
                            icon="solar:case-round-bold"
                            class="me-1">
                        </iconify-icon>

                        <span class="d-none d-lg-inline">
                            1. Plaza
                        </span>

                        <span class="d-lg-none">
                            Plaza
                        </span>

                    </button>

                </li>


                <!-- 2 -->
                <li class="nav-item" role="presentation">

                    <button
                        class="nav-link"
                        id="tab-datos"
                        data-bs-toggle="tab"
                        data-bs-target="#panel-datos"
                        type="button"
                        role="tab">

                        <iconify-icon
                            icon="solar:user-bold"
                            class="me-1">
                        </iconify-icon>

                        <span class="d-none d-lg-inline">
                            2. Datos personales
                        </span>

                        <span class="d-lg-none">
                            Datos
                        </span>

                    </button>

                </li>


                <!-- 3 -->
                <li class="nav-item" role="presentation">

                    <button
                        class="nav-link"
                        id="tab-profesional"
                        data-bs-toggle="tab"
                        data-bs-target="#panel-profesional"
                        type="button"
                        role="tab">

                        <iconify-icon
                            icon="solar:diploma-bold"
                            class="me-1">
                        </iconify-icon>

                        <span class="d-none d-lg-inline">
                            3. Formación profesional
                        </span>

                        <span class="d-lg-none">
                            Profesional
                        </span>

                    </button>

                </li>


                <!-- 4 -->
                <li class="nav-item" role="presentation">

                    <button
                        class="nav-link"
                        id="tab-academica"
                        data-bs-toggle="tab"
                        data-bs-target="#panel-academica"
                        type="button"
                        role="tab">

                        <iconify-icon
                            icon="solar:book-2-bold"
                            class="me-1">
                        </iconify-icon>

                        <span class="d-none d-lg-inline">
                            4. Formación académica
                        </span>

                        <span class="d-lg-none">
                            Académica
                        </span>

                    </button>

                </li>


                <!-- 5 -->
                <li class="nav-item" role="presentation">

                    <button
                        class="nav-link"
                        id="tab-experiencia"
                        data-bs-toggle="tab"
                        data-bs-target="#panel-experiencia"
                        type="button"
                        role="tab">

                        <iconify-icon
                            icon="solar:case-minimalistic-bold"
                            class="me-1">
                        </iconify-icon>

                        <span class="d-none d-lg-inline">
                            5. Experiencia laboral
                        </span>

                        <span class="d-lg-none">
                            Experiencia
                        </span>

                    </button>

                </li>


                <!-- 6 -->
                <li class="nav-item" role="presentation">

                    <button
                        class="nav-link"
                        id="tab-capacitaciones"
                        data-bs-toggle="tab"
                        data-bs-target="#panel-capacitaciones"
                        type="button"
                        role="tab">

                        <iconify-icon
                            icon="solar:library-bold"
                            class="me-1">
                        </iconify-icon>

                        <span class="d-none d-lg-inline">
                            6. Capacitaciones
                        </span>

                        <span class="d-lg-none">
                            Capacitaciones
                        </span>

                    </button>

                </li>


                <!-- 7 -->
                <li class="nav-item" role="presentation">

                    <button
                        class="nav-link"
                        id="tab-anexos"
                        data-bs-toggle="tab"
                        data-bs-target="#panel-anexos"
                        type="button"
                        role="tab">

                        <iconify-icon
                            icon="solar:folder-with-files-bold"
                            class="me-1">
                        </iconify-icon>

                        <span class="d-none d-lg-inline">
                            7. Otros anexos
                        </span>

                        <span class="d-lg-none">
                            Anexos
                        </span>

                    </button>

                </li>


                <!-- 8 -->
                <li class="nav-item" role="presentation">

                    <button
                        class="nav-link"
                        id="tab-dj"
                        data-bs-toggle="tab"
                        data-bs-target="#panel-dj"
                        type="button"
                        role="tab">

                        <iconify-icon
                            icon="solar:shield-check-bold"
                            class="me-1">
                        </iconify-icon>

                        <span class="d-none d-lg-inline">
                            8. Declaraciones juradas
                        </span>

                        <span class="d-lg-none">
                            DD.JJ.
                        </span>

                    </button>

                </li>


                <!-- 9 -->
                <li class="nav-item" role="presentation">

                    <button
                        class="nav-link"
                        id="tab-confirmacion"
                        data-bs-toggle="tab"
                        data-bs-target="#panel-confirmacion"
                        type="button"
                        role="tab">

                        <iconify-icon
                            icon="solar:check-read-bold"
                            class="me-1">
                        </iconify-icon>

                        <span class="d-none d-lg-inline">
                            9. Confirmación
                        </span>

                        <span class="d-lg-none">
                            Confirmar
                        </span>

                    </button>

                </li>

            </ul>

        </div>


        <!-- CONTENIDO -->
        <div class="card-body p-4">

            <div class="tab-content"
                id="contenidoTabsPostulacion">


                <!-- TAB 1 -->
                <div class="tab-pane fade show active"
                    id="panel-plaza"
                    role="tabpanel">

                    <div id="contenido-plaza">

                        <div class="text-center py-5">

                            <div class="spinner-border text-primary">
                            </div>

                            <div class="text-muted small mt-2">
                                Cargando selección de plaza...
                            </div>

                        </div>

                    </div>

                </div>


                <!-- TAB 2 -->
                <div class="tab-pane fade"
                    id="panel-datos"
                    role="tabpanel">

                    <div id="contenido-datos">

                        <div class="text-center py-5">

                            <div class="spinner-border text-primary">
                            </div>

                            <div class="text-muted small mt-2">
                                Cargando datos personales...
                            </div>

                        </div>

                    </div>

                </div>


                <!-- TAB 3 -->
                <div class="tab-pane fade"
                    id="panel-profesional"
                    role="tabpanel">

                    <div id="contenido-profesional"></div>

                </div>


                <!-- TAB 4 -->
                <div class="tab-pane fade"
                    id="panel-academica"
                    role="tabpanel">

                    <div id="contenido-academica"></div>

                </div>


                <!-- TAB 5 -->
                <div class="tab-pane fade"
                    id="panel-experiencia"
                    role="tabpanel">

                    <div id="contenido-experiencia"></div>

                </div>


                <!-- TAB 6 -->
                <div class="tab-pane fade"
                    id="panel-capacitaciones"
                    role="tabpanel">

                    <div id="contenido-capacitaciones"></div>

                </div>


                <!-- TAB 7 -->
                <div class="tab-pane fade"
                    id="panel-anexos"
                    role="tabpanel">

                    <div id="contenido-anexos"></div>

                </div>


                <!-- TAB 8 -->
                <div class="tab-pane fade"
                    id="panel-dj"
                    role="tabpanel">

                    <div id="contenido-dj"></div>

                </div>


                <!-- TAB 9 -->
                <div class="tab-pane fade"
                    id="panel-confirmacion"
                    role="tabpanel">

                    <div id="contenido-confirmacion"></div>

                </div>

            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>

<script>
    $(document).ready(function() {

        const convocatoriaId = $('#pto_con_ide').val();

        const tabsCargados = {};

        const urls = {

            plaza: '<?= base_url('seleccion/postulacion/partial/plaza') ?>',

            datos: '<?= base_url('seleccion/postulacion/partial/datos-personales') ?>',

            profesional: '<?= base_url('seleccion/postulacion/partial/formacion-profesional') ?>',

            academica: '<?= base_url('seleccion/postulacion/partial/formacion-academica') ?>',

            experiencia: '<?= base_url('seleccion/postulacion/partial/experiencia-laboral') ?>',

            capacitaciones: '<?= base_url('seleccion/postulacion/partial/capacitaciones') ?>',

            anexos: '<?= base_url('seleccion/postulacion/partial/otros-anexos') ?>',

            dj: '<?= base_url('seleccion/postulacion/partial/declaraciones-juradas') ?>',

            confirmacion: '<?= base_url('seleccion/postulacion/partial/confirmacion') ?>'

        };


        /*
        |--------------------------------------------------------------------------
        | Cargar primer tab
        |--------------------------------------------------------------------------
        */

        cargarTab('plaza');


        /*
        |--------------------------------------------------------------------------
        | Cambio de pestaña
        |--------------------------------------------------------------------------
        */

        $('#tabsPostulacion button[data-bs-toggle="tab"]')
            .on('shown.bs.tab', function(event) {

                const target =
                    $(event.target).data('bs-target');

                const tab =
                    target.replace('#panel-', '');

                cargarTab(tab);

            });


        /*
        |--------------------------------------------------------------------------
        | Cargar contenido AJAX
        |--------------------------------------------------------------------------
        */

        function cargarTab(tab) {

            if (tabsCargados[tab]) {
                return;
            }

            const contenedor =
                $('#contenido-' + tab);

            if (!contenedor.length) {
                return;
            }

            contenedor.html(`
            <div class="text-center py-5">

                <div class="spinner-border text-primary">
                </div>

                <div class="text-muted small mt-2">
                    Cargando información...
                </div>

            </div>
        `);


            $.ajax({

                url: urls[tab] + '/' + convocatoriaId,

                type: 'GET',

                cache: false,

                success: function(response) {

                    contenedor.html(response);

                    tabsCargados[tab] = true;

                    /*
                     * Permitir que cada partial
                     * inicialice sus propios eventos.
                     */

                    $(document).trigger(
                        'postulacion:tab:cargado',
                        [tab]
                    );

                },

                error: function() {

                    contenedor.html(`
                    <div class="alert alert-danger border-0">

                        <iconify-icon
                            icon="solar:danger-triangle-bold"
                            class="me-1">
                        </iconify-icon>

                        No se pudo cargar esta sección.

                    </div>
                `);

                }

            });

        }


        /*
        |--------------------------------------------------------------------------
        | Invalidar caché de un tab
        |--------------------------------------------------------------------------
        */

        window.recargarTabPostulacion =
            function(tab) {

                tabsCargados[tab] = false;

                cargarTab(tab);

            };


        /*
        |--------------------------------------------------------------------------
        | Cambiar a una pestaña
        |--------------------------------------------------------------------------
        */

        window.irATabPostulacion =
            function(tab) {

                const button =
                    $('#tab-' + tab);

                if (button.length) {

                    bootstrap.Tab
                        .getOrCreateInstance(button[0])
                        .show();

                }

            };

    });
</script>

<?= $this->endSection() ?>