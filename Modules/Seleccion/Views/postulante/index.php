<?= $this->extend('layouts/seleccionLayout') ?>
<?= $this->section('title') ?>
Panel de Postulante - Mis Postulaciones y Vacantes
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card bg-white p-4">
    <!-- Header principal -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold d-flex align-items-center">
                <iconify-icon icon="solar:user-speak-bold" class="me-2 text-primary" style="font-size: 1.8rem;"></iconify-icon>
                Mis Postulaciones y Vacantes
            </h4>
            <p class="text-muted small mb-0">
                Gestiona tus postulaciones presentadas y explora los nuevos concursos CAS vigentes.

            </p>
        </div>
    </div>

    <!-- Pestañas de Navegación -->
    <ul class="nav nav-pills mb-4 gap-2" id="postulacionTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill px-4 d-flex align-items-center fw-semibold" id="mis-postulaciones-tab" data-bs-toggle="pill" data-bs-target="#mis-postulaciones" type="button" role="tab">
                <iconify-icon icon="solar:document-text-bold" class="me-2" style="font-size: 1.2rem;"></iconify-icon>
                Mis Postulaciones
                <span class="badge bg-primary ms-2 rounded-circle"><?= count($misPostulaciones) ?></span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4 d-flex align-items-center fw-semibold" id="convocatorias-tab" data-bs-toggle="pill" data-bs-target="#convocatorias" type="button" role="tab">
                <iconify-icon icon="solar:briefcase-bold" class="me-2" style="font-size: 1.2rem;"></iconify-icon>
                Convocatorias Disponibles
                <span class="badge bg-success ms-2 rounded-circle"><?= count($convocatoriasAbiertas) ?></span>
            </button>
        </li>
    </ul>

    <!-- Contenido de Pestañas -->
    <div class="tab-content" id="postulacionTabsContent">

        <!-- PESTAÑA 1: MIS POSTULACIONES -->
        <div class="tab-pane fade show active" id="mis-postulaciones" role="tabpanel">
            <?php if (empty($misPostulaciones)): ?>
                <div class="card border-0 shadow-sm p-5 text-center bg-white rounded-3">
                    <div class="text-muted mb-3">
                        <iconify-icon icon="solar:folder-open-bold-duotone" class="text-secondary" style="font-size: 4rem;"></iconify-icon>
                    </div>
                    <h5 class="fw-bold text-dark">Aún no registras postulaciones</h5>
                    <p class="text-muted small mb-3">Ingresa a la pestaña <strong>"Convocatorias Disponibles"</strong> para presentarte a un concurso público.</p>
                    <div>
                        <button class="btn btn-primary btn-sm rounded-pill px-4" onclick="$('#convocatorias-tab').click()">
                            <iconify-icon icon="solar:magnifier-bold" class="me-1"></iconify-icon> Ver Convocatorias Abiertas
                        </button>
                    </div>
                </div>
            <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($misPostulaciones as $post): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card border-0 shadow-sm h-100 rounded-3 bg-white">
                                <div class="card-body d-flex flex-column p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <span class="badge bg-light text-primary border fw-bold px-2 py-1">
                                            <?= esc($post['con_numero'] ?? $post['con_codigo']) ?>
                                        </span>
                                        <?php if ((int)($post['pto_confirmado'] ?? 0) === 1): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold">PRESENTADO</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle fw-bold">EN BORRADOR</span>
                                        <?php endif; ?>
                                    </div>

                                    <h6 class="fw-bold text-dark mb-2"><?= esc($post['con_nombre']) ?></h6>

                                    <div class="small text-muted mb-3 mt-auto">
                                        <div class="d-flex align-items-center mb-1">
                                            <iconify-icon icon="solar:hashtag-square-bold" class="me-1 text-primary"></iconify-icon>
                                            Cód. Expediente: <strong><?= esc($post['pto_codigo'] ?? 'S/C') ?></strong>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <iconify-icon icon="solar:calendar-bold" class="me-1 text-primary"></iconify-icon>
                                            Fecha: <?= !empty($post['pto_fecha_presentacion']) ? date('d/m/Y h:i A', strtotime($post['pto_fecha_presentacion'])) : date('d/m/Y h:i A', strtotime($post['created_at'])) ?>
                                        </div>
                                    </div>

                                    <div class="d-grid pt-2 border-top">
                                        <a href="<?= base_url('seleccion/postulacion/ver/' . $post['pto_ide']) ?>" class="btn btn-outline-primary btn-sm rounded-pill d-flex align-items-center justify-content-center">
                                            <iconify-icon icon="solar:eye-bold" class="me-1"></iconify-icon> Ver Expediente
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- PESTAÑA 2: CONVOCATORIAS DISPONIBLES -->
        <div class="tab-pane fade" id="convocatorias" role="tabpanel">
            <?php if (empty($convocatoriasAbiertas)): ?>
                <div class="card border-0 shadow-sm p-5 text-center bg-white rounded-3">
                    <div class="text-muted mb-3">
                        <iconify-icon icon="solar:bell-off-bold-duotone" class="text-secondary" style="font-size: 4rem;"></iconify-icon>
                    </div>
                    <h5 class="fw-bold text-dark">No hay procesos con inscripción abierta</h5>
                    <p class="text-muted small mb-0">Revisa más adelante las publicaciones del portal.</p>
                </div>
            <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($convocatoriasAbiertas as $conv): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card border-0 shadow-sm h-100 rounded-3 bg-white border-top border-4 border-success">
                                <div class="card-body d-flex flex-column p-4">
                                    <div class="mb-2">
                                        <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold">POSTULACIÓN ABIERTA</span>
                                    </div>

                                    <h5 class="fw-bold text-dark mb-1"><?= esc($conv['con_nombre']) ?></h5>
                                    <span class="text-muted small mb-3">N° <?= esc($conv['con_numero'] ?? $conv['con_codigo']) ?></span>

                                    <div class="bg-light p-3 rounded-3 mb-3 small text-muted mt-auto">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Regimen:</span>
                                            <strong class="text-dark"><?= esc($conv['tco_nombre'] ?? 'CAS') ?></strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Inscripción:</span>
                                            <strong class="text-danger">
                                                <?= date('d/m/Y', strtotime($conv['cet_fecha_inicio'])) . '-' . date('d/m/Y', strtotime($conv['cet_fecha_cierre'])) ?></strong>
                                        </div>

                                    </div>

                                    <button onclick="confirmarInicioPostulacion(<?= $conv['con_ide'] ?>, '<?= esc($conv['con_nombre'], 'js') ?>')" class="btn btn-primary btn-sm rounded-pill d-flex align-items-center justify-content-center">
                                        <iconify-icon icon="solar:pen-new-square-bold" class="me-1" style="font-size: 1.1rem;"></iconify-icon>
                                        Postular a esta Convocatoria
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
<script>
    function confirmarInicioPostulacion(convocatoriaId, nombreConvocatoria) {
        Swal.fire({
            title: '¿Iniciar Postulación?',
            text: `Vas a iniciar tu inscripción para: "${nombreConvocatoria}".`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-check-circle me-1"></i> Sí, continuar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= base_url('seleccion/postulacion/iniciar/') ?>' + convocatoriaId;
            }
        });
    }
</script>
<?= $this->endSection() ?>