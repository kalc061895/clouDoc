<?= $this->extend('layouts/seleccionLayout') ?>

<?= $this->section('title') ?>
Ficha Digital de Inscripción
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card bg-white p-4">
    <!-- CABECERA -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <a href="<?= base_url('seleccion/postulacion') ?>" class="btn btn-outline-secondary btn-sm rounded-pill mb-2">
                <iconify-icon icon="solar:arrow-left-linear" class="align-middle"></iconify-icon>
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
                    <div class="text-muted small">Convocatoria</div>
                    <div class="fw-bold">
                        <?= esc($convocatoriaId) ?>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="text-muted small">Postulante</div>
                    <div class="fw-bold" id="resumen-postulante">
                        <!-- Cargar mediante AJAX o evento -->
                        Por completar
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="text-muted small">Estado de inscripción</div>
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
            <!-- Scroll horizontal en pantallas pequeñas -->
            <ul class="nav nav-tabs nav-fill flex-nowrap overflow-auto" id="tabsPostulacion" role="tablist" style="scrollbar-width: thin;">
                <!-- TAB 1 -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link active text-nowrap" id="tab-plaza" data-bs-toggle="tab" data-bs-target="#panel-plaza" type="button" role="tab">
                        <iconify-icon icon="solar:case-round-bold" class="me-1"></iconify-icon>
                        <span class="d-none d-lg-inline">1. Plaza</span>
                        <span class="d-lg-none">Plaza</span>
                    </button>
                </li>

                <!-- TAB 2 -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-nowrap" id="tab-datos" data-bs-toggle="tab" data-bs-target="#panel-datos" type="button" role="tab">
                        <iconify-icon icon="solar:user-bold" class="me-1"></iconify-icon>
                        <span class="d-none d-lg-inline">2. Datos personales</span>
                        <span class="d-lg-none">Datos</span>
                    </button>
                </li>

                <!-- TAB 3 -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-nowrap" id="tab-profesional" data-bs-toggle="tab" data-bs-target="#panel-profesional" type="button" role="tab">
                        <iconify-icon icon="solar:diploma-bold" class="me-1"></iconify-icon>
                        <span class="d-none d-lg-inline">3. Formación profesional</span>
                        <span class="d-lg-none">Profesional</span>
                    </button>
                </li>

                <!-- TAB 4 -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-nowrap" id="tab-academica" data-bs-toggle="tab" data-bs-target="#panel-academica" type="button" role="tab">
                        <iconify-icon icon="solar:book-2-bold" class="me-1"></iconify-icon>
                        <span class="d-none d-lg-inline">4. Formación académica</span>
                        <span class="d-lg-none">Académica</span>
                    </button>
                </li>

                <!-- TAB 5 -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-nowrap" id="tab-experiencia" data-bs-toggle="tab" data-bs-target="#panel-experiencia" type="button" role="tab">
                        <iconify-icon icon="solar:case-minimalistic-bold" class="me-1"></iconify-icon>
                        <span class="d-none d-lg-inline">5. Experiencia laboral</span>
                        <span class="d-lg-none">Experiencia</span>
                    </button>
                </li>

                <!-- TAB 6 -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-nowrap" id="tab-capacitaciones" data-bs-toggle="tab" data-bs-target="#panel-capacitaciones" type="button" role="tab">
                        <iconify-icon icon="solar:library-bold" class="me-1"></iconify-icon>
                        <span class="d-none d-lg-inline">6. Capacitaciones</span>
                        <span class="d-lg-none">Capacitaciones</span>
                    </button>
                </li>

                <!-- TAB 7 -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-nowrap" id="tab-anexos" data-bs-toggle="tab" data-bs-target="#panel-anexos" type="button" role="tab">
                        <iconify-icon icon="solar:folder-with-files-bold" class="me-1"></iconify-icon>
                        <span class="d-none d-lg-inline">7. Otros anexos</span>
                        <span class="d-lg-none">Anexos</span>
                    </button>
                </li>

                <!-- TAB 8 -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-nowrap" id="tab-dj" data-bs-toggle="tab" data-bs-target="#panel-dj" type="button" role="tab">
                        <iconify-icon icon="solar:shield-check-bold" class="me-1"></iconify-icon>
                        <span class="d-none d-lg-inline">8. Declaraciones juradas</span>
                        <span class="d-lg-none">DD.JJ.</span>
                    </button>
                </li>

                <!-- TAB 9 -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-nowrap" id="tab-confirmacion" data-bs-toggle="tab" data-bs-target="#panel-confirmacion" type="button" role="tab">
                        <iconify-icon icon="solar:check-read-bold" class="me-1"></iconify-icon>
                        <span class="d-none d-lg-inline">9. Confirmación</span>
                        <span class="d-lg-none">Confirmar</span>
                    </button>
                </li>
            </ul>
        </div>

        <!-- CONTENIDO -->
        <div class="card-body p-4">
            <div class="tab-content" id="contenidoTabsPostulacion">
                <div class="tab-pane fade show active" id="panel-plaza" role="tabpanel">
                    <div id="contenido-plaza"></div>
                </div>
                <div class="tab-pane fade" id="panel-datos" role="tabpanel">
                    <div id="contenido-datos"></div>
                </div>
                <div class="tab-pane fade" id="panel-profesional" role="tabpanel">
                    <div id="contenido-profesional"></div>
                </div>
                <div class="tab-pane fade" id="panel-academica" role="tabpanel">
                    <div id="contenido-academica"></div>
                </div>
                <div class="tab-pane fade" id="panel-experiencia" role="tabpanel">
                    <div id="contenido-experiencia"></div>
                </div>
                <div class="tab-pane fade" id="panel-capacitaciones" role="tabpanel">
                    <div id="contenido-capacitaciones"></div>
                </div>
                <div class="tab-pane fade" id="panel-anexos" role="tabpanel">
                    <div id="contenido-anexos"></div>
                </div>
                <div class="tab-pane fade" id="panel-dj" role="tabpanel">
                    <div id="contenido-dj"></div>
                </div>
                <div class="tab-pane fade" id="panel-confirmacion" role="tabpanel">
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

        // Inyección directa desde CodeIgniter 4
        const convocatoriaId = '<?= esc($convocatoriaId) ?>';
        

        const urls = {
            plaza: '<?= base_url('seleccion/postulacion/partial/plaza') ?>',
            datos: '<?= base_url('seleccion/postulacion/postulante/datos-personales') ?>',
            profesional: '<?= base_url('seleccion/postulacion/profesion/formacion-profesional') ?>',
            academica: '<?= base_url('seleccion/postulacion/partial/formacion-academica') ?>',
            experiencia: '<?= base_url('seleccion/postulacion/partial/experiencia-laboral') ?>',
            capacitaciones: '<?= base_url('seleccion/postulacion/partial/capacitaciones') ?>',
            anexos: '<?= base_url('seleccion/postulacion/partial/otros-anexos') ?>',
            dj: '<?= base_url('seleccion/postulacion/partial/declaraciones-juradas') ?>',
            confirmacion: '<?= base_url('seleccion/postulacion/partial/confirmacion') ?>'
        };

        // Cargar primer tab al iniciar
        cargarTab('plaza');

        // Escuchar cambio de pestañas en Bootstrap
        $('#tabsPostulacion button[data-bs-toggle="tab"]').on('shown.bs.tab', function(event) {
            const target = $(event.target).data('bs-target');
            const tab = target.replace('#panel-', '');
            cargarTab(tab);
        });

        function cargarTab(tab) {
            

            const contenedor = $('#contenido-' + tab);
            if (!contenedor.length) return;

            // Feedback visual mientras carga
            contenedor.html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <div class="text-muted small mt-2">Cargando información...</div>
                </div>
            `);

            $.ajax({
                url: urls[tab] + '/' + convocatoriaId,
                type: 'GET',
                cache: false,
                success: function(response) {
                    contenedor.html(response);
                    

                    // Trigger evento global para los JS de las parciales
                    $(document).trigger('postulacion:tab:cargado', [tab]);
                },
                error: function(xhr) {
                    contenedor.html(`
                        <div class="alert alert-danger border-0 d-flex align-items-center">
                            <iconify-icon icon="solar:danger-triangle-bold" class="fs-4 me-2"></iconify-icon>
                            <div>No se pudo cargar esta sección. Por favor, intente de nuevo.</div>
                        </div>
                    `);
                }
            });
        }

        // Helpers globales
        window.recargarTabPostulacion = function(tab) {
            
            cargarTab(tab);
        };

        window.irATabPostulacion = function(tab) {
            const button = $('#tab-' + tab);
            if (button.length) {
                bootstrap.Tab.getOrCreateInstance(button[0]).show();
            }
        };
    });
</script>
<?= $this->endSection() ?>