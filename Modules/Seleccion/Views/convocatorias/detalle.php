<?= $this->extend('layouts/seleccionLayout') ?>

<?= $this->section('title') ?>
Configuración de Convocatoria
<?= $this->endSection() ?>


<?= $this->section('content') ?>

<div class="card bg-white p-4">

    <!-- =========================================================
         CABECERA DE LA CONVOCATORIA
    ========================================================== -->
    <div id="cabecera" class="mb-4">

        <div class="d-flex align-items-center">

            <div
                class="spinner-border spinner-border-sm text-primary me-2"
                role="status">
            </div>

            <span class="text-muted small">
                Cargando información de la convocatoria...
            </span>

        </div>

    </div>


    <!-- =========================================================
         NAVEGACIÓN DE CONFIGURACIÓN
    ========================================================== -->
    <div class="mb-4">

        <div
            class="nav nav-pills flex-column flex-md-row gap-2"
            id="navTabsConvocatoria">

            <a
                href="#"
                class="nav-link <?= ($seccionActiva === 'resumen') ? 'active' : '' ?>"
                data-seccion="resumen">

                <iconify-icon
                    icon="solar:widget-2-bold"
                    class="me-1">
                </iconify-icon>

                Resumen

            </a>


            <a
                href="#"
                class="nav-link <?= ($seccionActiva === 'cargos') ? 'active' : '' ?>"
                data-seccion="cargos">

                <iconify-icon
                    icon="solar:case-bold"
                    class="me-1">
                </iconify-icon>

                Cargos y plazas

            </a>


            <a
                href="#"
                class="nav-link <?= ($seccionActiva === 'requisitos') ? 'active' : '' ?>"
                data-seccion="requisitos">

                <iconify-icon
                    icon="solar:checklist-minimalistic-bold"
                    class="me-1">
                </iconify-icon>

                Requisitos

            </a>


            <a
                href="#"
                class="nav-link <?= ($seccionActiva === 'cronograma') ? 'active' : '' ?>"
                data-seccion="cronograma">

                <iconify-icon
                    icon="solar:calendar-mark-bold"
                    class="me-1">
                </iconify-icon>

                Cronograma

            </a>


            <a
                href="#"
                class="nav-link <?= ($seccionActiva === 'documentos') ? 'active' : '' ?>"
                data-seccion="documentos">

                <iconify-icon
                    icon="solar:folder-with-files-bold"
                    class="me-1">
                </iconify-icon>

                Documentos

            </a>


            <a
                href="#"
                class="nav-link <?= ($seccionActiva === 'anexos') ? 'active' : '' ?>"
                data-seccion="anexos">

                <iconify-icon
                    icon="solar:paperclip-bold"
                    class="me-1">
                </iconify-icon>

                Anexos

            </a>

        </div>

    </div>


    <!-- =========================================================
         CONTENIDO DINÁMICO
    ========================================================== -->
    <div
        id="contenedorPartial"
        class="border rounded bg-light p-3">

        <div class="text-center py-5">

            <div
                class="spinner-border text-primary"
                role="status">
            </div>

            <p class="text-muted small mt-2 mb-0">
                Cargando sección...
            </p>

        </div>

    </div>

</div>

<?= $this->endSection() ?>


<?= $this->section('pageScripts') ?>

<script>
    (function() {

        // =========================================================
        // CONFIGURACIÓN
        // =========================================================

        const convocatoriaId =
            <?= json_encode($convocatoriaId) ?>;

        let seccionActual =
            <?= json_encode($seccionActiva) ?>;

        let requestPartial = null;


        // =========================================================
        // URLs
        // =========================================================

        const urls = {

            convocatoria: '<?= base_url('api/seleccion/convocatorias') ?>',

            partial: '<?= base_url('seleccion/convocatorias/partial') ?>',

            configuracion: '<?= base_url('seleccion/convocatorias') ?>'

        };


        // =========================================================
        // INICIALIZACIÓN
        // =========================================================

        $(document).ready(function() {

            cargarCabecera();

            cargarPartial(seccionActual);


            // =====================================================
            // CAMBIO DE SECCIÓN
            // =====================================================

            $('#navTabsConvocatoria')
                .on('click', '.nav-link', function(e) {

                    e.preventDefault();

                    const nuevaSeccion =
                        $(this).data('seccion');


                    // No hacer una nueva petición
                    // si ya estamos en esa sección.
                    if (
                        nuevaSeccion === seccionActual &&
                        $('#contenedorPartial').children().length
                    ) {
                        return;
                    }


                    cambiarSeccion(nuevaSeccion);

                });


            // =====================================================
            // NAVEGACIÓN DEL NAVEGADOR
            // =====================================================

            window.addEventListener(
                'popstate',
                function() {

                    const seccion =
                        obtenerSeccionDesdeUrl();

                    if (seccion) {

                        cambiarSeccion(
                            seccion,
                            false
                        );

                    }

                }
            );

        });


        // =========================================================
        // CAMBIAR SECCIÓN
        // =========================================================

        function cambiarSeccion(
            nuevaSeccion,
            actualizarUrl = true
        ) {

            if (!nuevaSeccion) {
                nuevaSeccion = 'resumen';
            }


            seccionActual =
                nuevaSeccion;


            // -----------------------------------------------------
            // Actualizar pestaña activa
            // -----------------------------------------------------

            $('#navTabsConvocatoria .nav-link')
                .removeClass('active');

            $(
                    '#navTabsConvocatoria .nav-link[data-seccion="' +
                    nuevaSeccion +
                    '"]'
                )
                .addClass('active');


            // -----------------------------------------------------
            // Actualizar URL
            // -----------------------------------------------------

            if (actualizarUrl) {

                const nuevaUrl =
                    urls.configuracion +
                    '/' +
                    convocatoriaId +
                    '/' +
                    nuevaSeccion;


                history.pushState({
                        seccion: nuevaSeccion
                    },
                    '',
                    nuevaUrl
                );

            }


            // -----------------------------------------------------
            // Cargar contenido
            // -----------------------------------------------------

            cargarPartial(nuevaSeccion);

        }


        // =========================================================
        // CARGAR CABECERA
        // =========================================================

        function cargarCabecera() {

            mostrarCabeceraLoading();


            $.get(
                    urls.convocatoria +
                    '/' +
                    convocatoriaId
                )

                .done(function(r) {

                    const data =
                        r.data || r;


                    if (
                        !data ||
                        !data.con_codigo
                    ) {

                        mostrarCabeceraError(
                            'Convocatoria no encontrada.'
                        );

                        return;
                    }


                    const codigo =
                        escapeHtml(
                            data.con_codigo
                        );


                    const nombre =
                        escapeHtml(
                            data.con_nombre ||
                            'Sin denominación'
                        );


                    const numero =
                        escapeHtml(
                            data.con_numero ||
                            ''
                        );


                    const estado =
                        data.eco_nombre ||
                        'BORRADOR';


                    const estadoCodigo =
                        data.eco_codigo ||
                        '';


                    let estadoClass =
                        'bg-secondary';


                    if (
                        estadoCodigo === 'PUBLICADA'
                    ) {

                        estadoClass =
                            'bg-success';

                    } else if (
                        estadoCodigo === 'CERRADA'
                    ) {

                        estadoClass =
                            'bg-dark';

                    } else if (
                        estadoCodigo === 'ANULADA'
                    ) {

                        estadoClass =
                            'bg-danger';

                    }


                    $('#cabecera').html(`

                <div
                    class="d-flex flex-column flex-md-row
                           justify-content-between
                           align-items-md-center
                           gap-3">

                    <div>

                        <div
                            class="d-flex align-items-center
                                   mb-1">

                            <iconify-icon
                                icon="solar:document-text-bold"
                                class="text-primary me-2"
                                style="font-size:1.5rem;">
                            </iconify-icon>

                            <h4
                                class="fw-bold text-dark mb-0">

                                ${codigo}

                            </h4>

                        </div>


                        <div
                            class="fw-semibold text-dark">

                            ${nombre}

                        </div>


                        ${
                            numero
                                ? `
                                    <small
                                        class="text-muted">

                                        ${numero}

                                    </small>
                                  `
                                : ''
                        }

                    </div>


                    <div>

                        <span
                            class="badge ${estadoClass}
                                   px-3 py-2">

                            ${escapeHtml(estado)}

                        </span>

                    </div>

                </div>

            `);

                })

                .fail(function() {

                    mostrarCabeceraError(
                        'Error al obtener los datos de la convocatoria.'
                    );

                });

        }


        // =========================================================
        // CABECERA - LOADING
        // =========================================================

        function mostrarCabeceraLoading() {

            $('#cabecera').html(`

            <div class="d-flex align-items-center">

                <div
                    class="spinner-border spinner-border-sm
                           text-primary me-2"
                    role="status">
                </div>

                <span class="text-muted small">

                    Cargando información
                    de la convocatoria...

                </span>

            </div>

        `);

        }


        // =========================================================
        // CABECERA - ERROR
        // =========================================================

        function mostrarCabeceraError(mensaje) {

            $('#cabecera').html(`

            <div
                class="alert alert-danger
                       border-0 mb-0 py-2">

                <iconify-icon
                    icon="solar:danger-triangle-bold"
                    class="me-1">
                </iconify-icon>

                ${escapeHtml(mensaje)}

            </div>

        `);

        }


        // =========================================================
        // CARGAR PARTIAL
        // =========================================================

        function cargarPartial(seccion) {

            mostrarPartialLoading();


            // -----------------------------------------------------
            // Cancelar petición anterior si todavía está activa
            // -----------------------------------------------------

            if (requestPartial) {

                requestPartial.abort();

            }


            requestPartial =
                $.ajax({

                    url: urls.partial +
                        '/' +
                        convocatoriaId +
                        '/' +
                        encodeURIComponent(seccion),

                    type: 'GET',

                    cache: false

                })

                .done(function(html) {

                    $('#contenedorPartial')
                        .html(html);

                })

                .fail(function(xhr, status) {

                    // No mostrar error cuando nosotros
                    // cancelamos la petición anterior.
                    if (
                        status === 'abort'
                    ) {
                        return;
                    }


                    Swal.fire({

                        icon: 'error',

                        title: 'Error',

                        text: 'No se pudo cargar el contenido ' +
                            'de la sección seleccionada.'

                    });


                    $('#contenedorPartial').html(`

                    <div
                        class="alert alert-danger
                               border-0 mb-0">

                        <iconify-icon
                            icon="solar:danger-triangle-bold"
                            class="me-1">
                        </iconify-icon>

                        Error al cargar la sección.

                    </div>

                `);

                })

                .always(function() {

                    requestPartial = null;

                });

        }


        // =========================================================
        // PARTIAL - LOADING
        // =========================================================

        function mostrarPartialLoading() {

            $('#contenedorPartial').html(`

            <div class="text-center py-5">

                <div
                    class="spinner-border text-primary"
                    role="status">
                </div>

                <p
                    class="text-muted small mt-2 mb-0">

                    Cargando sección...

                </p>

            </div>

        `);

        }


        // =========================================================
        // OBTENER SECCIÓN DESDE URL
        // =========================================================

        function obtenerSeccionDesdeUrl() {

            const partes =
                window.location.pathname
                .split('/')
                .filter(Boolean);


            const posiblesSecciones = [
                'resumen',
                'cargos',
                'requisitos',
                'cronograma',
                'documentos',
                'anexos'
            ];


            const ultimaParte =
                partes[partes.length - 1];


            return posiblesSecciones.includes(
                    ultimaParte
                ) ?
                ultimaParte :
                'resumen';

        }


        // =========================================================
        // ESCAPE HTML
        // =========================================================

        function escapeHtml(text) {

            return $('<div>')
                .text(text || '')
                .html();

        }

    })();
</script>

<?= $this->endSection() ?>