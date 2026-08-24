<?= $this->extend('layouts/seleccionLayout') ?>
<?= $this->section('title') ?>
Configuración de Convocatoria
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <div id="cabecera" class="mb-4">
        <div class="d-flex align-items-center">
            <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
            </div>
            <span class="text-muted small">
                Cargando información de la convocatoria...
            </span>
        </div>
    </div>
    <div class="mb-4">
        <div class="nav nav-pills flex-column flex-md-row gap-2" id="navTabsConvocatoria">
            <!-- Resumen -->
            <a href="#" class="nav-link <?= ($seccionActiva === 'resumen') ? 'active' : '' ?>" data-seccion="resumen">
                <iconify-icon icon="solar:widget-2-bold" class="me-1"></iconify-icon>
                Resumen
            </a>

            <!-- Cargos y plazas -->
            <a href="#" class="nav-link <?= ($seccionActiva === 'cargos') ? 'active' : '' ?>" data-seccion="cargos">
                <iconify-icon icon="solar:case-bold" class="me-1"></iconify-icon>
                Cargos y plazas
            </a>

            <!-- Cronograma -->
            <a href="#" class="nav-link <?= ($seccionActiva === 'cronograma') ? 'active' : '' ?>"
                data-seccion="cronograma">
                <iconify-icon icon="solar:calendar-mark-bold" class="me-1"></iconify-icon>
                Cronograma
            </a>

            <!-- Documentos -->
            <a href="#" class="nav-link <?= ($seccionActiva === 'documentos') ? 'active' : '' ?>"
                data-seccion="documentos">
                <iconify-icon icon="solar:folder-with-files-bold" class="me-1"></iconify-icon>
                Documentos
            </a>

            <!-- Requisitos -->
            <a href="#" class="nav-link <?= ($seccionActiva === 'requisitos') ? 'active' : '' ?>"
                data-seccion="requisitos">
                <iconify-icon icon="solar:clipboard-check-bold" class="me-1"></iconify-icon>
                Requisitos
            </a>

            <!-- Comisión -->
            <a href="#" class="nav-link <?= ($seccionActiva === 'comision') ? 'active' : '' ?>" data-seccion="comision">
                <iconify-icon icon="solar:users-group-two-rounded-bold" class="me-1"></iconify-icon>
                Comisión
            </a>

            <!-- Evaluación -->
            <a href="#" class="nav-link <?= ($seccionActiva === 'evaluacion') ? 'active' : '' ?>"
                data-seccion="evaluacion">
                <iconify-icon icon="solar:notes-bold" class="me-1"></iconify-icon>
                Evaluación
            </a>

            <!-- Actas -->
            <a href="#" class="nav-link <?= ($seccionActiva === 'actas') ? 'active' : '' ?>" data-seccion="actas">
                <iconify-icon icon="solar:document-text-bold" class="me-1"></iconify-icon>
                Actas
            </a>
            <!-- anexos -->
            <a href="#" class="nav-link <?= ($seccionActiva === 'anexos') ? 'active' : '' ?>" data-seccion="anexos">
                <iconify-icon icon="solar:document-text-bold" class="me-1"></iconify-icon>
                Anexos
            </a>
        </div>
    </div>
    <div id="contenedorPartial" class="border rounded bg-light p-3">
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
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
    (function () {
        const convocatoriaId = <?= json_encode($convocatoriaId) ?>;
        let seccionActual = <?= json_encode($seccionActiva) ?>;
        let requestPartial = null;
        const urls = {
            convocatoria: '<?= base_url('api/seleccion/convocatorias') ?>',
            partial: '<?= base_url('seleccion/convocatorias/partial') ?>',
            configuracion: '<?= base_url('seleccion/convocatorias') ?>'

        };

        $(document).ready(function () {
            cargarCabecera();
            cargarPartial(seccionActual);

            $('#navTabsConvocatoria').on('click', '.nav-link', function (e) {
                e.preventDefault();
                const nuevaSeccion = $(this).data('seccion');

                // No hacer una nueva petición si ya estamos en esa sección.
                if (nuevaSeccion === seccionActual && $('#contenedorPartial').children().length) {
                    return;
                }
                cambiarSeccion(nuevaSeccion);
            });

            // ELIMINADO: Ya no necesitamos escuchar el evento 'popstate' 
            // porque no estamos alterando el historial del navegador.
        });

        function cambiarSeccion(nuevaSeccion) { // Quitamos el parámetro actualizarUrl
            if (!nuevaSeccion) {
                nuevaSeccion = 'resumen';
            }
            seccionActual = nuevaSeccion;

            $('#navTabsConvocatoria .nav-link').removeClass('active');
            $('#navTabsConvocatoria .nav-link[data-seccion="' + nuevaSeccion + '"]').addClass('active');

            // ELIMINADO: history.pushState ya no se ejecuta aquí, 
            // por lo que la URL del navegador se mantendrá intacta.

            cargarPartial(nuevaSeccion);
        }

        function cargarCabecera() {
            mostrarCabeceraLoading();
            $.get(urls.convocatoria + '/' + convocatoriaId)
                .done(function (r) {
                    const data = r.data || r;
                    if (!data || !data.con_codigo) {
                        mostrarCabeceraError('Convocatoria no encontrada.');
                        return;
                    }
                    const codigo = escapeHtml(data.con_codigo);
                    const nombre = escapeHtml(data.con_nombre || 'Sin denominación');
                    const numero = escapeHtml(data.con_numero || '');
                    const estado = data.eco_nombre || 'BORRADOR';
                    const estadoCodigo = data.eco_codigo || '';
                    let estadoClass = 'bg-secondary';

                    if (estadoCodigo === 'PUBLICADA') {
                        estadoClass = 'bg-success';
                    } else if (estadoCodigo === 'CERRADA') {
                        estadoClass = 'bg-dark';
                    } else if (estadoCodigo === 'ANULADA') {
                        estadoClass = 'bg-danger';
                    }

                    $('#cabecera').html(`
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                            <div>
                                <div class="d-flex align-items-center mb-1">
                                    <iconify-icon icon="solar:document-text-bold" class="text-primary me-2" style="font-size:1.5rem;"></iconify-icon>
                                    <h4 class="fw-bold text-dark mb-0">${codigo}</h4>
                                </div>
                                <div class="fw-semibold text-dark">${nombre}</div>
                                ${numero ? `<small class="text-muted">${numero}</small>` : ''}
                            </div>
                            <div>
                                <span class="badge ${estadoClass} px-3 py-2">${escapeHtml(estado)}</span>
                            </div>
                        </div>
                    `);
                })
                .fail(function () {
                    mostrarCabeceraError('Error al obtener los datos de la convocatoria.');
                });
        }

        function mostrarCabeceraLoading() {
            $('#cabecera').html(`
                <div class="d-flex align-items-center">
                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                    <span class="text-muted small">Cargando información de la convocatoria...</span>
                </div>
            `);
        }

        function mostrarCabeceraError(mensaje) {
            $('#cabecera').html(`
                <div class="alert alert-danger border-0 mb-0 py-2">
                    <iconify-icon icon="solar:danger-triangle-bold" class="me-1"></iconify-icon>
                    ${escapeHtml(mensaje)}
                </div>
            `);
        }

        function cargarPartial(seccion) {
            mostrarPartialLoading();
            if (requestPartial) {
                requestPartial.abort();
            }
            requestPartial = $.ajax({
                url: urls.partial + '/' + convocatoriaId + '/' + encodeURIComponent(seccion),
                type: 'GET',
                cache: false
            })
                .done(function (html) {
                    $('#contenedorPartial').html(html);
                })
                .fail(function (xhr, status) {
                    if (status === 'abort') {
                        return;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo cargar el contenido de la sección seleccionada.'
                    });
                    $('#contenedorPartial').html(`
                    <div class="alert alert-danger border-0 mb-0">
                        <iconify-icon icon="solar:danger-triangle-bold" class="me-1"></iconify-icon>
                        Error al cargar la sección.
                    </div>
                `);
                })
                .always(function () {
                    requestPartial = null;
                });
        }

        function mostrarPartialLoading() {
            $('#contenedorPartial').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="text-muted small mt-2 mb-0">Cargando sección...</p>
                </div>
            `);
        }

        function escapeHtml(text) {
            return $('<div>').text(text || '').html();
        }
    })();
</script>
<?= $this->endSection() ?>