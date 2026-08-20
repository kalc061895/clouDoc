<?= $this->extend('layouts/seleccionLayout') ?>

<?= $this->section('title') ?>
Registro de Expediente Digital
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card border-0 shadow-sm p-4">

    <!-- Cabecera / Info del Puesto al que Postula -->
    <div id="cabeceraPostulacion" class="mb-4 pb-3 border-bottom">
        <div class="d-flex align-items-center">
            <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
            <span class="text-muted small">Cargando datos de la postulación...</span>
        </div>
    </div>

    <!-- Indicador de Pasos (Step Wizard) -->
    <div class="mb-4">
        <div class="nav nav-pills flex-column flex-md-row gap-2 justify-content-between position-relative" id="wizardSteps">

            <a href="#" class="nav-link text-start text-md-center flex-fill <?= ($seccionActiva === 'formacion') ? 'active' : '' ?>" data-seccion="formacion">
                <span class="step-number mb-1">1</span>
                <span class="step-label d-block text-truncate">Formación</span>
            </a>

            <a href="#" class="nav-link text-start text-md-center flex-fill <?= ($seccionActiva === 'profesion') ? 'active' : '' ?>" data-seccion="profesion">
                <span class="step-number mb-1">2</span>
                <span class="step-label d-block text-truncate">Profesión</span>
            </a>

            <a href="#" class="nav-link text-start text-md-center flex-fill <?= ($seccionActiva === 'capacitacion') ? 'active' : '' ?>" data-seccion="capacitacion">
                <span class="step-number mb-1">3</span>
                <span class="step-label d-block text-truncate">Capacitaciones</span>
            </a>

            <a href="#" class="nav-link text-start text-md-center flex-fill <?= ($seccionActiva === 'experiencia') ? 'active' : '' ?>" data-seccion="experiencia">
                <span class="step-number mb-1">4</span>
                <span class="step-label d-block text-truncate">Experiencia</span>
            </a>

            <a href="#" class="nav-link text-start text-md-center flex-fill <?= ($seccionActiva === 'anexos') ? 'active' : '' ?>" data-seccion="anexos">
                <span class="step-number mb-1">5</span>
                <span class="step-label d-block text-truncate">Anexos</span>
            </a>

            <a href="#" class="nav-link text-start text-md-center flex-fill <?= ($seccionActiva === 'declaraciones') ? 'active' : '' ?>" data-seccion="declaraciones">
                <span class="step-number mb-1">6</span>
                <span class="step-label d-block text-truncate">Declaraciones J.</span>
            </a>

        </div>
    </div>

    <!-- Contenedor dinámico donde se cargan los Partials de cada etapa -->
    <div id="contenedorPartial" class="border rounded bg-white p-4">
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="text-muted small mt-2 mb-0">Cargando etapa...</p>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('pageStyles') ?>
<style>
    /* Estilos para transformar los nav-pills en un Wizard numerado */
    #wizardSteps .nav-link {
        background-color: #f8f9fa;
        color: #6c757d;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 10px 12px;
        transition: all 0.2s ease-in-out;
    }

    #wizardSteps .nav-link.active {
        background-color: #0d6efd;
        color: #ffffff;
        border-color: #0d6efd;
    }

    #wizardSteps .step-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background-color: rgba(0, 0, 0, 0.1);
        font-weight: bold;
        font-size: 0.8rem;
    }

    #wizardSteps .nav-link.active .step-number {
        background-color: #ffffff;
        color: #0d6efd;
    }

    #wizardSteps .step-label {
        font-size: 0.85rem;
        font-weight: 600;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script>
    (function() {
        const postulacionId = <?= json_encode($postulacionId) ?>;
        let seccionActual = <?= json_encode($seccionActiva) ?>;
        let requestPartial = null;

        const urls = {
            cabecera: '<?= base_url('api/postulante/postulaciones') ?>',
            partial: '<?= base_url('postulante/expediente/partial') ?>',
            base: '<?= base_url('postulante/expediente') ?>'
        };

        $(document).ready(function() {
            cargarCabecera();
            cargarPartial(seccionActual);

            // Manejo de clic en los pasos
            $('#wizardSteps').on('click', '.nav-link', function(e) {
                e.preventDefault();
                const nuevaSeccion = $(this).data('seccion');

                if (nuevaSeccion === seccionActual && $('#contenedorPartial').children().length) {
                    return;
                }
                cambiarSeccion(nuevaSeccion);
            });

            // Manejo del botón Atrás/Adelante del navegador
            window.addEventListener('popstate', function() {
                const seccion = obtenerSeccionDesdeUrl();
                if (seccion) {
                    cambiarSeccion(seccion, false);
                }
            });
        });

        function cambiarSeccion(nuevaSeccion, actualizarUrl = true) {
            if (!nuevaSeccion) nuevaSeccion = 'formacion';
            seccionActual = nuevaSeccion;

            $('#wizardSteps .nav-link').removeClass('active');
            $('#wizardSteps .nav-link[data-seccion="' + nuevaSeccion + '"]').addClass('active');

            if (actualizarUrl) {
                const nuevaUrl = urls.base + '/' + postulacionId + '/' + nuevaSeccion;
                history.pushState({
                    seccion: nuevaSeccion
                }, '', nuevaUrl);
            }
            cargarPartial(nuevaSeccion);
        }

        function cargarCabecera() {
            $.get(urls.cabecera + '/' + postulacionId)
                .done(function(r) {
                    const data = r.data || r;
                    $('#cabeceraPostulacion').html(`
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-primary mb-1">${escapeHtml(data.convocatoria_codigo || 'CONVOCATORIA')}</span>
                                <h5 class="fw-bold text-dark mb-0">${escapeHtml(data.cargo_nombre || 'Cargo al que postula')}</h5>
                                <small class="text-muted">Postulante: ${escapeHtml(data.postulante_nombre || '')}</small>
                            </div>
                            <div>
                                <span class="badge bg-warning text-dark px-3 py-2">
                                    <iconify-icon icon="solar:clock-circle-bold" class="me-1"></iconify-icon>
                                    ${escapeHtml(data.estado_postulacion || 'EN PROCESO')}
                                </span>
                            </div>
                        </div>
                    `);
                })
                .fail(function() {
                    $('#cabeceraPostulacion').html(`
                        <div class="alert alert-warning border-0 mb-0 py-2">
                            No se pudo obtener la información general de la postulación.
                        </div>
                    `);
                });
        }

        function cargarPartial(seccion) {
            mostrarPartialLoading();
            if (requestPartial) {
                requestPartial.abort();
            }

            requestPartial = $.ajax({
                    url: urls.partial + '/' + postulacionId + '/' + encodeURIComponent(seccion),
                    type: 'GET',
                    cache: false
                })
                .done(function(html) {
                    $('#contenedorPartial').html(html);
                })
                .fail(function(xhr, status) {
                    if (status === 'abort') return;

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo cargar la etapa seleccionada.'
                    });
                    $('#contenedorPartial').html(`
                    <div class="alert alert-danger border-0 mb-0">
                        <iconify-icon icon="solar:danger-triangle-bold" class="me-1"></iconify-icon>
                        Error al cargar el contenido de la etapa.
                    </div>
                `);
                })
                .always(function() {
                    requestPartial = null;
                });
        }

        function mostrarPartialLoading() {
            $('#contenedorPartial').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="text-muted small mt-2 mb-0">Cargando etapa...</p>
                </div>
            `);
        }

        function obtenerSeccionDesdeUrl() {
            const partes = window.location.pathname.split('/').filter(Boolean);
            const etapas = ['formacion', 'profesion', 'capacitacion', 'experiencia', 'anexos', 'declaraciones'];
            const ultimaParte = partes[partes.length - 1];
            return etapas.includes(ultimaParte) ? ultimaParte : 'formacion';
        }

        function escapeHtml(text) {
            return $('<div>').text(text || '').html();
        }
    })();
</script>
<?= $this->endSection() ?>