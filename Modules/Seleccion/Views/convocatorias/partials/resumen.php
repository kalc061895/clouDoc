<div class="row g-3">

    <!-- =========================================================
         INFORMACIÓN GENERAL
    ========================================================== -->
    <div class="col-lg-8">

        <div class="card border-0 shadow-sm mb-3">

            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">

                <div>
                    <h6 class="fw-bold m-0 text-dark d-flex align-items-center">
                        <iconify-icon
                            icon="solar:info-circle-bold"
                            class="me-2 text-primary"
                            style="font-size: 1.25rem;">
                        </iconify-icon>

                        Información General
                    </h6>

                    <small class="text-muted">
                        Información principal de la convocatoria
                    </small>
                </div>

                <span
                    class="badge bg-secondary"
                    id="badgeEstado">
                    Cargando...
                </span>

            </div>


            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">
                            Tipo de convocatoria
                        </small>

                        <span
                            class="fw-semibold text-dark"
                            id="lblTipo">
                            -
                        </span>
                    </div>


                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">
                            Año fiscal
                        </small>

                        <span
                            class="fw-semibold text-dark"
                            id="lblAnio">
                            -
                        </span>
                    </div>


                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">
                            Código interno
                        </small>

                        <span
                            class="fw-semibold text-dark"
                            id="lblCodigo">
                            -
                        </span>
                    </div>


                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">
                            Número oficial
                        </small>

                        <span
                            class="fw-semibold text-dark"
                            id="lblNumero">
                            -
                        </span>
                    </div>


                    <div class="col-12">

                        <small class="text-muted d-block mb-1">
                            Denominación del proceso
                        </small>

                        <div
                            class="fw-bold text-dark"
                            id="lblNombre">
                            -
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- =====================================================
             MÉTRICAS
        ====================================================== -->
        <div class="row g-3">

            <!-- PLAZAS -->
            <div class="col-sm-4">

                <div class="card border-0 shadow-sm bg-white h-100">

                    <div class="card-body p-3">

                        <div class="d-flex align-items-center mb-2">

                            <iconify-icon
                                icon="solar:case-bold"
                                class="text-primary me-2"
                                style="font-size: 1.4rem;">
                            </iconify-icon>

                            <span class="text-muted small">
                                Plazas registradas
                            </span>

                        </div>

                        <div
                            class="fs-3 fw-bold text-dark"
                            id="resumenCargos">
                            0
                        </div>

                        <a
                            href="<?= base_url('seleccion/convocatorias/' . $convocatoriaId . '/cargos') ?>"
                            class="small text-decoration-none">

                            Ver plazas
                            <i class="bi bi-arrow-right ms-1"></i>

                        </a>

                    </div>

                </div>

            </div>


            <!-- CRONOGRAMA -->
            <div class="col-sm-4">

                <div class="card border-0 shadow-sm bg-white h-100">

                    <div class="card-body p-3">

                        <div class="d-flex align-items-center mb-2">

                            <iconify-icon
                                icon="solar:calendar-mark-bold"
                                class="text-info me-2"
                                style="font-size: 1.4rem;">
                            </iconify-icon>

                            <span class="text-muted small">
                                Etapas cronograma
                            </span>

                        </div>

                        <div
                            class="fs-3 fw-bold text-dark"
                            id="resumenEtapas">
                            0
                        </div>

                        <a
                            href="<?= base_url('seleccion/convocatorias/' . $convocatoriaId . '/cronograma') ?>"
                            class="small text-decoration-none">

                            Ver cronograma
                            <i class="bi bi-arrow-right ms-1"></i>

                        </a>

                    </div>

                </div>

            </div>


            <!-- DOCUMENTOS -->
            <div class="col-sm-4">

                <div class="card border-0 shadow-sm bg-white h-100">

                    <div class="card-body p-3">

                        <div class="d-flex align-items-center mb-2">

                            <iconify-icon
                                icon="solar:folder-with-files-bold"
                                class="text-warning me-2"
                                style="font-size: 1.4rem;">
                            </iconify-icon>

                            <span class="text-muted small">
                                Documentos requeridos
                            </span>

                        </div>

                        <div
                            class="fs-3 fw-bold text-dark"
                            id="resumenDocumentos">
                            0
                        </div>

                        <a
                            href="<?= base_url('seleccion/convocatorias/' . $convocatoriaId . '/documentos') ?>"
                            class="small text-decoration-none">

                            Ver documentos
                            <i class="bi bi-arrow-right ms-1"></i>

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =========================================================
         REQUISITOS DE PUBLICACIÓN
    ========================================================== -->
    <div class="col-lg-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-header bg-white border-0 py-3">

                <h6 class="fw-bold m-0 text-dark d-flex align-items-center">

                    <iconify-icon
                        icon="solar:checklist-minimalistic-bold"
                        class="me-2 text-success"
                        style="font-size: 1.25rem;">
                    </iconify-icon>

                    Requisitos de Publicación

                </h6>

                <small class="text-muted">
                    Verificación previa a la publicación
                </small>

            </div>


            <div class="card-body">

                <div
                    class="list-group list-group-flush small"
                    id="checklistPublicacion">


                    <!-- CARGOS -->
                    <div
                        class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">

                        <div class="d-flex align-items-center">

                            <i
                                class="bi bi-circle text-muted me-2"
                                id="iconCargo">
                            </i>

                            <span>
                                Al menos 1 cargo registrado
                            </span>

                        </div>

                        <span
                            class="badge bg-light text-dark"
                            id="chkCargoCount">
                            0
                        </span>

                    </div>


                    <!-- ETAPAS -->
                    <div
                        class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">

                        <div class="d-flex align-items-center">

                            <i
                                class="bi bi-circle text-muted me-2"
                                id="iconEtapa">
                            </i>

                            <span>
                                Cronograma / etapas definidas
                            </span>

                        </div>

                        <span
                            class="badge bg-light text-dark"
                            id="chkEtapasCount">
                            0
                        </span>

                    </div>


                    <!-- DOCUMENTOS -->
                    <div
                        class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">

                        <div class="d-flex align-items-center">

                            <i
                                class="bi bi-circle text-muted me-2"
                                id="iconDocumento">
                            </i>

                            <span>
                                Documentos obligatorios
                            </span>

                        </div>

                        <span
                            class="badge bg-light text-dark"
                            id="chkDocumentosCount">
                            0
                        </span>

                    </div>

                </div>


                <hr class="my-3">


                <div class="d-grid">

                    <button
                        type="button"
                        class="btn btn-success"
                        id="btnPublicar"
                        onclick="publicarConvocatoria()"
                        disabled>

                        <i class="bi bi-send-check me-1"></i>

                        Publicar Convocatoria

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

<script>
    $(document).ready(function() {
        cargarResumenData();
    });

    function cargarResumenData() {
        $.get('<?= base_url('api/seleccion/convocatorias/' . $convocatoriaId) ?>')
            .done(r => {
                const data = r.data || r;
                if (data) {
                    $('#lblTipo').text(data.tco_nombre || 'No asignado');
                    $('#lblAnio').text(data.con_anio || '-');
                    $('#lblCodigo').text(data.con_codigo || '-');
                    $('#lblNumero').text(data.con_numero || '-');
                    $('#lblNombre').text(data.con_nombre || '-');

                    // Estado
                    const estado = data.eco_nombre || 'BORRADOR';
                    const badge = $('#badgeEstado').text(estado);
                    if (data.eco_codigo === 'PUBLICADA') {
                        badge.removeClass('bg-secondary').addClass('bg-success');
                    } else {
                        badge.removeClass('bg-success').addClass('bg-secondary');
                    }
                }
            });

        // Cargar métricas y validar el checklist para publicar
        $.get('<?= base_url('seleccion/admin/cargos-convocatoria/listar/' . $convocatoriaId) ?>', r => {
            const count = Array.isArray(r.data || r) ? (r.data || r).length : 0;
            $('#resumenCargos, #chkCargoCount').text(count);
            validarCheckItem('#iconCargo', count > 0);
            verificarChecklist();
        });
        // Cargar métricas y validar el checklist para publicar
        $.get('<?= base_url('seleccion/admin/etapas-convocatoria/listar/' . $convocatoriaId) ?>', r => {
            const count = Array.isArray(r.data || r) ? (r.data || r).length : 0;
            $('#resumenEtapas, #chkEtapasCount').text(count);
            validarCheckItem('#iconEtapa', count > 0);
            verificarChecklist();
        });
        // Cargar métricas y validar el checklist para publicar
        $.get('<?= base_url('seleccion/admin/documentos-convocatoria/listar/' . $convocatoriaId) ?>', r => {
            const count = Array.isArray(r.data || r) ? (r.data || r).length : 0;
            $('#resumenDocumentos, #chkDocumentosCount').text(count);
            validarCheckItem('#iconDocumento', count > 0);
            verificarChecklist();
        });
    }

    function validarCheckItem(elementId, status) {
        const $el = $(elementId);
        if (status) {
            $el.removeClass('bi-circle text-muted').addClass('bi-check-circle-fill text-success');
        } else {
            $el.removeClass('bi-check-circle-fill text-success').addClass('bi-circle text-muted');
        }
    }

    function verificarChecklist() {
        const cargosOk = $('#iconCargo').hasClass('text-success');
        const etapasOk = $('#iconEtapa').hasClass('text-success');
        const documentosOk = $('#iconDocumento').hasClass('text-success');
        // Si cumple los mínimos, se habilita el botón de publicación
        if (cargosOk > 0 && etapasOk > 0 && documentosOk > 0) {
            $('#btnPublicar').prop('disabled', false);
        }
    }

    function publicarConvocatoria() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        Swal.fire({
            title: '¿Publicar esta convocatoria?',
            text: 'Una vez publicada será visible públicamente para que los postulantes inicien su registro.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-send me-1"></i> Sí, publicar ahora',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (!result.isConfirmed) return;

            const $btn = $('#btnPublicar').prop('disabled', true);

            // Notificación visual durante el proceso AJAX
            Toast.fire({
                icon: 'info',
                title: 'Publicando convocatoria...'
            });

            $.post('<?= base_url('api/seleccion/convocatorias/' . $convocatoriaId . '/publicar') ?>')
                .done(r => {
                    if (r.ok || r.status) {
                        Swal.fire({
                            title: '¡Publicada!',
                            text: r.message || 'La convocatoria ha sido publicada con éxito.',
                            icon: 'success',
                            confirmButtonColor: '#0d6efd',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Atención',
                            text: r.message || 'No se pudo publicar la convocatoria.',
                            icon: 'warning',
                            confirmButtonColor: '#0d6efd'
                        });
                    }
                })
                .fail(err => {
                    const msg = (err.responseJSON && err.responseJSON.message) ?
                        err.responseJSON.message :
                        'Error de servidor al intentar publicar.';

                    Swal.fire({
                        title: 'Error',
                        text: msg,
                        icon: 'error',
                        confirmButtonColor: '#dc3545'
                    });
                })
                .always(() => {
                    $btn.prop('disabled', false);
                });
        });
    }
</script>