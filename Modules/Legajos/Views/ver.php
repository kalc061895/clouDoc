<?= $this->extend('layouts/layoutMain') ?>
<?= $this->section('title') ?><?= esc($titulo) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid py-3">

    <!-- Barra Superior / Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('legajos') ?>" class="text-decoration-none">Legajos</a></li>
                <li class="breadcrumb-item active" aria-current="page">Expediente Personal</li>
            </ol>
        </nav>
        <a href="<?= base_url('legajos') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <iconify-icon icon="solar:arrow-left-bold" class="me-1"></iconify-icon> Volver al Listado
        </a>
    </div>

    <!-- TARJETA PRINCIPAL DEL SERVIDOR PÚBLICO -->
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4 overflow-hidden">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-auto text-center mb-3 mb-md-0">
                    <div class="position-relative d-inline-block">
                        <?php $foto = !empty($servidor['ser_foto']) ? base_url($servidor['ser_foto']) : base_url('assets/images/profile/user-1.jpg'); ?>
                        <img src="<?= $foto ?>" alt="Foto" class="rounded-circle border border-3 border-light shadow-sm object-fit-cover" width="110" height="110">
                        <button type="button" class="btn btn-primary btn-sm position-absolute bottom-0 end-0 rounded-circle p-1" style="width: 32px; height: 32px;" onclick="editarServidorActual()" title="Actualizar Datos / Foto">
                            <iconify-icon icon="solar:camera-bold" class="fs-6"></iconify-icon>
                        </button>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                        <h4 class="fw-bold mb-0 text-dark">
                            <?= esc($servidor['ser_apellido_paterno'] . ' ' . ($servidor['ser_apellido_materno'] ?? '') . ', ' . $servidor['ser_nombres']) ?>
                        </h4>
                        <?php
                            $badgeEstado = 'bg-success';
                            if ($servidor['ser_estado'] === 'CESADO') $badgeEstado = 'bg-danger';
                            if ($servidor['ser_estado'] === 'LICENCIA') $badgeEstado = 'bg-warning text-dark';
                            if ($servidor['ser_estado'] === 'SUSPENDIDO') $badgeEstado = 'bg-secondary';
                        ?>
                        <span class="badge <?= $badgeEstado ?> rounded-pill px-3"><?= esc($servidor['ser_estado']) ?></span>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill"><?= esc($servidor['ser_regimen_laboral']) ?></span>
                    </div>

                    <p class="text-primary fw-semibold mb-2">
                        <iconify-icon icon="solar:case-bold" class="me-1"></iconify-icon> <?= esc($servidor['ser_cargo']) ?> &bull; <span class="text-muted fw-normal"><?= esc($servidor['ser_dependencia']) ?></span>
                    </p>

                    <div class="row g-2 text-muted small">
                        <div class="col-sm-6 col-lg-3">
                            <span class="fw-semibold text-dark"><?= esc($servidor['ser_tipo_documento']) ?>:</span> <?= esc($servidor['ser_numero_documento']) ?>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <span class="fw-semibold text-dark">N° Legajo:</span> <?= esc($servidor['ser_numero_legajo'] ?: 'S/N') ?>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <span class="fw-semibold text-dark">Ingreso:</span> <?= esc($servidor['ser_fecha_ingreso'] ? date('d/m/Y', strtotime($servidor['ser_fecha_ingreso'])) : 'No reg.') ?>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <span class="fw-semibold text-dark">Condición:</span> <?= esc($servidor['ser_condicion_laboral'] ?: 'Contratado') ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 text-md-end border-start-md mt-3 mt-md-0 ps-md-4">
                    <div class="d-flex flex-column gap-1 text-muted small">
                        <div><iconify-icon icon="solar:phone-bold" class="me-1 text-primary"></iconify-icon> <?= esc($servidor['ser_celular'] ?: 'Sin celular') ?></div>
                        <div><iconify-icon icon="solar:letter-bold" class="me-1 text-primary"></iconify-icon> <?= esc($servidor['ser_email_institucional'] ?: 'Sin email inst.') ?></div>
                        <div><iconify-icon icon="solar:map-point-bold" class="me-1 text-primary"></iconify-icon> <?= esc($servidor['ser_direccion'] ?: 'Sin dirección') ?></div>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 mt-3 w-100" onclick="editarServidorActual()">
                        <iconify-icon icon="solar:pen-bold" class="me-1"></iconify-icon> Modificar Ficha Personal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- PESTAÑAS NORMATIVAS DEL LEGAJO (SERVIR) -->
    <div class="card border-0 shadow-sm rounded-4 bg-white p-3">
        <!-- Navegación de Pestañas -->
        <ul class="nav nav-pills nav-justified mb-3 p-2 bg-light rounded-4 gap-1 flex-nowrap overflow-auto" id="legajoTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-3 py-2 px-3 fw-semibold small text-nowrap d-flex align-items-center justify-content-center gap-2" id="tab-sec1" data-bs-toggle="pill" data-bs-target="#sec1" type="button" role="tab">
                    <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="fs-5"></iconify-icon>
                    <span>1. Filiación y Familiares</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-3 py-2 px-3 fw-semibold small text-nowrap d-flex align-items-center justify-content-center gap-2" id="tab-sec2" data-bs-toggle="pill" data-bs-target="#sec2" type="button" role="tab">
                    <iconify-icon icon="solar:diploma-verified-bold-duotone" class="fs-5"></iconify-icon>
                    <span>2. Formación Académica</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-3 py-2 px-3 fw-semibold small text-nowrap d-flex align-items-center justify-content-center gap-2" id="tab-sec3" data-bs-toggle="pill" data-bs-target="#sec3" type="button" role="tab">
                    <iconify-icon icon="solar:case-round-minimalistic-bold-duotone" class="fs-5"></iconify-icon>
                    <span>3. Experiencia Laboral</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-3 py-2 px-3 fw-semibold small text-nowrap d-flex align-items-center justify-content-center gap-2" id="tab-sec4" data-bs-toggle="pill" data-bs-target="#sec4" type="button" role="tab">
                    <iconify-icon icon="solar:transfer-vertical-bold-duotone" class="fs-5"></iconify-icon>
                    <span>4. Movimientos Personal</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-3 py-2 px-3 fw-semibold small text-nowrap d-flex align-items-center justify-content-center gap-2" id="tab-sec5" data-bs-toggle="pill" data-bs-target="#sec5" type="button" role="tab">
                    <iconify-icon icon="solar:document-medicine-bold-duotone" class="fs-5"></iconify-icon>
                    <span>5. Eval. y Capacitación</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-3 py-2 px-3 fw-semibold small text-nowrap d-flex align-items-center justify-content-center gap-2" id="tab-sec6" data-bs-toggle="pill" data-bs-target="#sec6" type="button" role="tab">
                    <iconify-icon icon="solar:medal-ribbon-star-bold-duotone" class="fs-5"></iconify-icon>
                    <span>6. Méritos y PAD</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-3 py-2 px-3 fw-semibold small text-nowrap d-flex align-items-center justify-content-center gap-2" id="tab-sec7" data-bs-toggle="pill" data-bs-target="#sec7" type="button" role="tab">
                    <iconify-icon icon="solar:folder-with-files-bold-duotone" class="fs-5"></iconify-icon>
                    <span>7. Expediente Digital</span>
                </button>
            </li>
        </ul>

        <!-- Contenido de las Pestañas -->
        <div class="tab-content p-3" id="legajoTabsContent">

            <!-- ================================================================= -->
            <!-- PESTAÑA 1: DATOS FILIATORIOS Y FAMILIARES (DERECHOHABIENTES) -->
            <!-- ================================================================= -->
            <div class="tab-pane fade show active" id="sec1" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Sección 1: Datos Filiatorios y Familiares</h5>
                        <p class="text-muted small mb-0">Derechohabientes (EsSalud/EPS), cónyuge, hijos y contactos de emergencia</p>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" onclick="abrirModalFamiliar()">
                        <iconify-icon icon="solar:user-plus-bold" class="me-1"></iconify-icon> Agregar Familiar
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle w-100">
                        <thead class="table-light">
                            <tr class="small text-muted text-uppercase">
                                <th>Parentesco</th>
                                <th>Nombres y Apellidos</th>
                                <th>Documento</th>
                                <th>Fecha Nacimiento</th>
                                <th>Derechohabiente</th>
                                <th>Contacto Emergencia</th>
                                <th>Sustento (PDF)</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($familiares)): ?>
                                <tr><td colspan="8" class="text-center text-muted py-4">No hay familiares ni derechohabientes registrados.</td></tr>
                            <?php else: ?>
                                <?php foreach ($familiares as $f): ?>
                                    <tr>
                                        <td><span class="badge bg-primary-subtle text-primary"><?= esc($f['fam_parentesco']) ?></span></td>
                                        <td class="fw-bold text-dark"><?= esc($f['fam_apellido_paterno'] . ' ' . ($f['fam_apellido_materno'] ?? '') . ', ' . $f['fam_nombres']) ?></td>
                                        <td><?= esc($f['fam_tipo_documento']) ?>: <?= esc($f['fam_numero_documento'] ?: 'S/N') ?></td>
                                        <td><?= esc($f['fam_fecha_nacimiento'] ? date('d/m/Y', strtotime($f['fam_fecha_nacimiento'])) : '-') ?></td>
                                        <td><?= $f['fam_es_derechohabiente'] ? '<span class="badge bg-success-subtle text-success">Sí (EsSalud)</span>' : '<span class="badge bg-light text-muted">No</span>' ?></td>
                                        <td><?= $f['fam_es_contacto_emergencia'] ? '<span class="badge bg-warning-subtle text-warning">Contacto Emergencia (' . esc($f['fam_telefono']) . ')</span>' : '-' ?></td>
                                        <td>
                                            <?php if (!empty($f['fam_adjunto_sustento'])): ?>
                                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="verPdf('<?= base64_encode($f['fam_adjunto_sustento']) ?>')">
                                                    <iconify-icon icon="solar:file-text-bold" class="me-1"></iconify-icon> Ver Sustento
                                                </button>
                                            <?php else: ?>
                                                <span class="text-muted small">Sin adjunto</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle p-1" onclick="eliminarFamiliar(<?= $f['fam_ide'] ?>)" title="Eliminar">
                                                <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ================================================================= -->
            <!-- PESTAÑA 2: FORMACIÓN ACADÉMICA Y COLEGIATURA -->
            <!-- ================================================================= -->
            <div class="tab-pane fade" id="sec2" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Sección 2: Formación Académica, Grados y Colegiatura</h5>
                        <p class="text-muted small mb-0">Títulos, maestrías, doctorados, colegiaturas profesionales y habilitación</p>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" onclick="abrirModalFormacion()">
                        <iconify-icon icon="solar:add-circle-bold" class="me-1"></iconify-icon> Agregar Formación
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle w-100">
                        <thead class="table-light">
                            <tr class="small text-muted text-uppercase">
                                <th>Nivel</th>
                                <th>Carrera / Grado</th>
                                <th>Institución Educativa</th>
                                <th>Colegiatura / SUNEDU</th>
                                <th>Fecha Expedición</th>
                                <th>Habilitado</th>
                                <th>Diploma / Sustento</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($formaciones)): ?>
                                <tr><td colspan="8" class="text-center text-muted py-4">No hay registros de formación académica.</td></tr>
                            <?php else: ?>
                                <?php foreach ($formaciones as $fo): ?>
                                    <tr>
                                        <td><span class="badge bg-info-subtle text-info fw-semibold"><?= esc($fo['for_nivel_educativo']) ?></span></td>
                                        <td>
                                            <strong class="text-dark d-block"><?= esc($fo['for_carrera_especialidad']) ?></strong>
                                            <small class="text-muted"><?= esc($fo['for_grado_obtenido'] ?: '') ?></small>
                                        </td>
                                        <td><?= esc($fo['for_institucion']) ?></td>
                                        <td>
                                            <?php if ($fo['for_colegio_profesional']): ?>
                                                <small class="d-block text-dark fw-medium"><?= esc($fo['for_colegio_profesional']) ?> (<?= esc($fo['for_numero_colegiatura']) ?>)</small>
                                            <?php endif; ?>
                                            <?php if ($fo['for_registro_sunedu']): ?>
                                                <small class="text-muted">SUNEDU: <?= esc($fo['for_registro_sunedu']) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($fo['for_fecha_expedicion'] ? date('d/m/Y', strtotime($fo['for_fecha_expedicion'])) : '-') ?></td>
                                        <td><?= $fo['for_es_habilitado'] ? '<span class="badge bg-success-subtle text-success">Habilitado</span>' : '<span class="badge bg-danger-subtle text-danger">No Habilitado</span>' ?></td>
                                        <td>
                                            <?php if (!empty($fo['for_adjunto_sustento'])): ?>
                                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="verPdf('<?= base64_encode($fo['for_adjunto_sustento']) ?>')">
                                                    <iconify-icon icon="solar:file-text-bold" class="me-1"></iconify-icon> Ver Título
                                                </button>
                                            <?php else: ?>
                                                <span class="text-muted small">Sin adjunto</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle p-1" onclick="eliminarFormacion(<?= $fo['for_ide'] ?>)" title="Eliminar">
                                                <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ================================================================= -->
            <!-- PESTAÑA 3: EXPERIENCIA LABORAL PREVIA -->
            <!-- ================================================================= -->
            <div class="tab-pane fade" id="sec3" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Sección 3: Experiencia Laboral Previa</h5>
                        <p class="text-muted small mb-0">Récord de servicios en entidades públicas y privadas anteriores</p>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" onclick="abrirModalExperiencia()">
                        <iconify-icon icon="solar:add-circle-bold" class="me-1"></iconify-icon> Agregar Experiencia
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle w-100">
                        <thead class="table-light">
                            <tr class="small text-muted text-uppercase">
                                <th>Sector</th>
                                <th>Entidad / Empresa</th>
                                <th>Cargo Desempeñado</th>
                                <th>Periodo</th>
                                <th>Tiempo Computado</th>
                                <th>Constancia (PDF)</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($experiencias)): ?>
                                <tr><td colspan="7" class="text-center text-muted py-4">No hay registros de experiencia laboral previa.</td></tr>
                            <?php else: ?>
                                <?php foreach ($experiencias as $ex): ?>
                                    <tr>
                                        <td><span class="badge <?= $ex['exp_tipo_entidad'] === 'PUBLICA' ? 'bg-primary-subtle text-primary' : 'bg-secondary-subtle text-secondary' ?>"><?= esc($ex['exp_tipo_entidad']) ?></span></td>
                                        <td class="fw-bold text-dark"><?= esc($ex['exp_entidad_empresa']) ?></td>
                                        <td><?= esc($ex['exp_cargo_desempenado']) ?></td>
                                        <td>
                                            <?= esc(date('d/m/Y', strtotime($ex['exp_fecha_inicio']))) ?> al 
                                            <?= esc($ex['exp_fecha_fin'] ? date('d/m/Y', strtotime($ex['exp_fecha_fin'])) : 'Actual') ?>
                                        </td>
                                        <td><?= esc($ex['exp_tiempo_anios']) ?>a, <?= esc($ex['exp_tiempo_meses']) ?>m, <?= esc($ex['exp_tiempo_dias']) ?>d</td>
                                        <td>
                                            <?php if (!empty($ex['exp_adjunto_sustento'])): ?>
                                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="verPdf('<?= base64_encode($ex['exp_adjunto_sustento']) ?>')">
                                                    <iconify-icon icon="solar:file-text-bold" class="me-1"></iconify-icon> Ver Constancia
                                                </button>
                                            <?php else: ?>
                                                <span class="text-muted small">Sin adjunto</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle p-1" onclick="eliminarExperiencia(<?= $ex['exp_ide'] ?>)" title="Eliminar">
                                                <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ================================================================= -->
            <!-- PESTAÑA 4: MOVIMIENTOS DE PERSONAL Y DESPLAZAMIENTOS -->
            <!-- ================================================================= -->
            <div class="tab-pane fade" id="sec4" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Sección 4: Movimientos de Personal y Desplazamientos</h5>
                        <p class="text-muted small mb-0">Rotaciones, reasignaciones, destaques, encargaturas, licencias y vacaciones</p>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" onclick="abrirModalMovimiento()">
                        <iconify-icon icon="solar:add-circle-bold" class="me-1"></iconify-icon> Registrar Movimiento
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle w-100">
                        <thead class="table-light">
                            <tr class="small text-muted text-uppercase">
                                <th>Tipo de Movimiento</th>
                                <th>Documento Sustento</th>
                                <th>Origen / Destino</th>
                                <th>Vigencia / Periodo</th>
                                <th>Motivo / Detalle</th>
                                <th>Resolución (PDF)</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($movimientos)): ?>
                                <tr><td colspan="7" class="text-center text-muted py-4">No hay movimientos de personal registrados.</td></tr>
                            <?php else: ?>
                                <?php foreach ($movimientos as $mo): ?>
                                    <tr>
                                        <td><span class="badge bg-purple-subtle text-purple fw-semibold"><?= esc($mo['mov_tipo']) ?></span></td>
                                        <td>
                                            <strong class="text-dark d-block"><?= esc($mo['mov_numero_documento']) ?></strong>
                                            <small class="text-muted"><?= esc($mo['mov_tipo_documento_sustento']) ?> &bull; <?= esc($mo['mov_fecha_documento'] ? date('d/m/Y', strtotime($mo['mov_fecha_documento'])) : '') ?></small>
                                        </td>
                                        <td>
                                            <small class="d-block text-muted">De: <?= esc($mo['mov_dependencia_origen'] ?: '-') ?></small>
                                            <small class="d-block text-dark fw-medium">A: <?= esc($mo['mov_dependencia_destino'] ?: '-') ?></small>
                                        </td>
                                        <td>
                                            <?= esc(date('d/m/Y', strtotime($mo['mov_fecha_inicio']))) ?>
                                            <?= esc($mo['mov_fecha_fin'] ? ' al ' . date('d/m/Y', strtotime($mo['mov_fecha_fin'])) : '') ?>
                                        </td>
                                        <td><small class="text-muted"><?= esc($mo['mov_motivo_detalle'] ?: '-') ?></small></td>
                                        <td>
                                            <?php if (!empty($mo['mov_adjunto_sustento'])): ?>
                                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="verPdf('<?= base64_encode($mo['mov_adjunto_sustento']) ?>')">
                                                    <iconify-icon icon="solar:file-text-bold" class="me-1"></iconify-icon> Ver Resolución
                                                </button>
                                            <?php else: ?>
                                                <span class="text-muted small">Sin adjunto</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle p-1" onclick="eliminarMovimiento(<?= $mo['mov_ide'] ?>)" title="Eliminar">
                                                <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ================================================================= -->
            <!-- PESTAÑA 5: EVALUACIONES Y CAPACITACIONES -->
            <!-- ================================================================= -->
            <div class="tab-pane fade" id="sec5" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Sección 5: Evaluaciones del Desempeño y Capacitaciones</h5>
                        <p class="text-muted small mb-0">Evaluaciones de rendimiento GDR, diplomados, cursos, talleres y horas académicas</p>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" onclick="abrirModalCapacitacion()">
                        <iconify-icon icon="solar:add-circle-bold" class="me-1"></iconify-icon> Registrar Capacitación/Eval
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle w-100">
                        <thead class="table-light">
                            <tr class="small text-muted text-uppercase">
                                <th>Tipo</th>
                                <th>Título de Evento / Evaluación</th>
                                <th>Entidad Organizadora</th>
                                <th>Horas / Créditos</th>
                                <th>Calificación</th>
                                <th>PDP Entidad</th>
                                <th>Certificado (PDF)</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($capacitaciones)): ?>
                                <tr><td colspan="8" class="text-center text-muted py-4">No hay capacitaciones ni evaluaciones registradas.</td></tr>
                            <?php else: ?>
                                <?php foreach ($capacitaciones as $c): ?>
                                    <tr>
                                        <td><span class="badge bg-success-subtle text-success"><?= esc($c['evc_tipo']) ?></span></td>
                                        <td>
                                            <strong class="text-dark d-block"><?= esc($c['evc_titulo']) ?></strong>
                                            <small class="text-muted"><?= esc($c['evc_tipo_evento']) ?> &bull; <?= esc(date('d/m/Y', strtotime($c['evc_fecha_inicio']))) ?></small>
                                        </td>
                                        <td><?= esc($c['evc_institucion_organizadora']) ?></td>
                                        <td><?= esc($c['evc_horas_academicas']) ?> hrs (<?= esc($c['evc_creditos']) ?> cred)</td>
                                        <td><span class="badge bg-light text-dark border"><?= esc($c['evc_calificacion_obtenida'] ?: 'Aprobado') ?></span></td>
                                        <td><?= $c['evc_es_financiado_entidad'] ? '<span class="badge bg-primary-subtle text-primary">Financiado PDP</span>' : '<span class="badge bg-light text-muted">Personal</span>' ?></td>
                                        <td>
                                            <?php if (!empty($c['evc_adjunto_sustento'])): ?>
                                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="verPdf('<?= base64_encode($c['evc_adjunto_sustento']) ?>')">
                                                    <iconify-icon icon="solar:file-text-bold" class="me-1"></iconify-icon> Ver Certificado
                                                </button>
                                            <?php else: ?>
                                                <span class="text-muted small">Sin adjunto</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle p-1" onclick="eliminarCapacitacion(<?= $c['evc_ide'] ?>)" title="Eliminar">
                                                <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ================================================================= -->
            <!-- PESTAÑA 6: MÉRITOS, RECONOCIMIENTOS Y SANCIONES (PAD) -->
            <!-- ================================================================= -->
            <div class="tab-pane fade" id="sec6" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Sección 6: Méritos, Reconocimientos y Sanciones (PAD)</h5>
                        <p class="text-muted small mb-0">Felicitaciones institucionales y Procedimientos Administrativos Disciplinarios</p>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" onclick="abrirModalSancionMerito()">
                        <iconify-icon icon="solar:add-circle-bold" class="me-1"></iconify-icon> Registrar Mérito / Sanción
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle w-100">
                        <thead class="table-light">
                            <tr class="small text-muted text-uppercase">
                                <th>Naturaleza</th>
                                <th>Subtipo</th>
                                <th>Acto Resolutivo</th>
                                <th>Entidad Emisora</th>
                                <th>Fecha Acto</th>
                                <th>Expediente PAD</th>
                                <th>Sustento (PDF)</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($meritosSanciones)): ?>
                                <tr><td colspan="8" class="text-center text-muted py-4">No hay méritos ni sanciones registradas en el legajo.</td></tr>
                            <?php else: ?>
                                <?php foreach ($meritosSanciones as $ms): ?>
                                    <tr>
                                        <td>
                                            <?php if ($ms['msa_tipo'] === 'MERITO'): ?>
                                                <span class="badge bg-success-subtle text-success"><iconify-icon icon="solar:medal-ribbon-star-bold" class="me-1"></iconify-icon> MÉRITO</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-subtle text-danger"><iconify-icon icon="solar:shield-warning-bold" class="me-1"></iconify-icon> SANCIÓN (PAD)</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="fw-semibold"><?= esc($ms['msa_subtipo']) ?></td>
                                        <td>
                                            <strong class="text-dark d-block"><?= esc($ms['msa_acto_resolutivo']) ?></strong>
                                            <small class="text-muted"><?= esc($ms['msa_descripcion_motivo'] ?: '') ?></small>
                                        </td>
                                        <td><?= esc($ms['msa_entidad_emisora']) ?></td>
                                        <td><?= esc(date('d/m/Y', strtotime($ms['msa_fecha_acto']))) ?></td>
                                        <td><?= esc($ms['msa_numero_expediente_pad'] ?: '-') ?></td>
                                        <td>
                                            <?php if (!empty($ms['msa_adjunto_sustento'])): ?>
                                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="verPdf('<?= base64_encode($ms['msa_adjunto_sustento']) ?>')">
                                                    <iconify-icon icon="solar:file-text-bold" class="me-1"></iconify-icon> Ver Resolución
                                                </button>
                                            <?php else: ?>
                                                <span class="text-muted small">Sin adjunto</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle p-1" onclick="eliminarSancionMerito(<?= $ms['msa_ide'] ?>)" title="Eliminar">
                                                <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ================================================================= -->
            <!-- PESTAÑA 7: EXPEDIENTE DIGITAL Y REPOSITORIO DE FOLIOS -->
            <!-- ================================================================= -->
            <div class="tab-pane fade" id="sec7" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Expediente Digital del Legajo</h5>
                        <p class="text-muted small mb-0">Repositorio centralizado de documentos digitalizados y folios del servidor</p>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" onclick="abrirModalDocumento()">
                        <iconify-icon icon="solar:cloud-upload-bold" class="me-1"></iconify-icon> Incorporar Folio Digital
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle w-100">
                        <thead class="table-light">
                            <tr class="small text-muted text-uppercase">
                                <th>N° Folio</th>
                                <th>Sección Normativa</th>
                                <th>Título del Documento</th>
                                <th>Fecha Emisión</th>
                                <th>Tamaño</th>
                                <th>Visor</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($documentos)): ?>
                                <tr><td colspan="7" class="text-center text-muted py-4">No se han incorporado documentos digitalizados al expediente.</td></tr>
                            <?php else: ?>
                                <?php foreach ($documentos as $d): ?>
                                    <tr>
                                        <td><span class="badge bg-secondary-subtle text-secondary fw-mono">Folio <?= esc($d['doc_numero_folio'] ?: 'S/N') ?></span></td>
                                        <td><span class="badge bg-primary-subtle text-primary">Sec. <?= esc($d['sec_numero'] ?? 'Gral') ?>: <?= esc($d['sec_nombre'] ?? 'General') ?></span></td>
                                        <td>
                                            <strong class="text-dark d-block"><?= esc($d['doc_titulo']) ?></strong>
                                            <small class="text-muted"><?= esc($d['doc_nombre_original']) ?></small>
                                        </td>
                                        <td><?= esc($d['doc_fecha_emision'] ? date('d/m/Y', strtotime($d['doc_fecha_emision'])) : '-') ?></td>
                                        <td><?= round($d['doc_peso_kb'] / 1024, 2) ?> MB</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="verPdf('<?= base64_encode($d['doc_ruta_archivo']) ?>')">
                                                <iconify-icon icon="solar:eye-bold" class="me-1"></iconify-icon> Ver Documento
                                            </button>
                                        </td>
                                        <td class="text-end">
                                            <a href="<?= base_url('legajos/descargar-documento/' . $d['doc_ide']) ?>" class="btn btn-sm btn-outline-secondary rounded-circle p-1 me-1" title="Descargar">
                                                <iconify-icon icon="solar:download-bold"></iconify-icon>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle p-1" onclick="eliminarDocumento(<?= $d['doc_ide'] ?>)" title="Eliminar">
                                                <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODALES PARA CADA SECCIÓN DEL LEGAJO -->
<!-- ========================================================================= -->

<!-- MODAL 1: FAMILIAR -->
<div class="modal fade" id="modalFamiliar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><iconify-icon icon="solar:user-plus-bold" class="me-2"></iconify-icon> Registrar Familiar / Derechohabiente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formFamiliar" onsubmit="guardarSeccion(event, 'familiares/guardar', 'formFamiliar', '#modalFamiliar')">
                <input type="hidden" name="fam_ser_ide" value="<?= $servidor['ser_ide'] ?>">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Parentesco *</label>
                            <select class="form-select" name="fam_parentesco" required>
                                <option value="CONYUGE">Cónyuge</option>
                                <option value="CONVIVIENTE">Conviviente</option>
                                <option value="HIJO(A)" selected>Hijo(a)</option>
                                <option value="PADRE">Padre</option>
                                <option value="MADRE">Madre</option>
                                <option value="HERMANO(A)">Hermano(a)</option>
                                <option value="OTRO">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Tipo Documento</label>
                            <select class="form-select" name="fam_tipo_documento">
                                <option value="DNI">DNI</option>
                                <option value="CARNET EXT.">Carné Extranjería</option>
                                <option value="PARTIDA NAC.">Partida Nacimiento</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">N° Documento</label>
                            <input type="text" class="form-control" name="fam_numero_documento">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Nombres *</label>
                            <input type="text" class="form-control" name="fam_nombres" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Apellido Paterno *</label>
                            <input type="text" class="form-control" name="fam_apellido_paterno" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Apellido Materno</label>
                            <input type="text" class="form-control" name="fam_apellido_materno">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Fecha Nacimiento</label>
                            <input type="date" class="form-control" name="fam_fecha_nacimiento">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Sexo</label>
                            <select class="form-select" name="fam_sexo">
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Teléfono / Celular</label>
                            <input type="text" class="form-control" name="fam_telefono">
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" name="fam_es_derechohabiente" id="fam_es_derechohabiente" value="1" checked>
                                <label class="form-check-label small fw-semibold" for="fam_es_derechohabiente">Es Derechohabiente (EsSalud / EPS)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" name="fam_es_contacto_emergencia" id="fam_es_contacto_emergencia" value="1">
                                <label class="form-check-label small fw-semibold" for="fam_es_contacto_emergencia">Contacto de Emergencia Institucional</label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">Acta de Nacimiento / Matrimonio (PDF o Imagen)</label>
                            <input type="file" class="form-control" name="fam_adjunto" accept=".pdf,image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Guardar Registro</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL 2: FORMACIÓN ACADÉMICA -->
<div class="modal fade" id="modalFormacion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><iconify-icon icon="solar:diploma-verified-bold" class="me-2"></iconify-icon> Registrar Formación Académica</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formFormacion" onsubmit="guardarSeccion(event, 'formacion/guardar', 'formFormacion', '#modalFormacion')">
                <input type="hidden" name="for_ser_ide" value="<?= $servidor['ser_ide'] ?>">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Nivel Educativo *</label>
                            <select class="form-select" name="for_nivel_educativo" required>
                                <option value="SECUNDARIA">Secundaria Completa</option>
                                <option value="TECNICO">Técnico Superior</option>
                                <option value="BACHILLER">Bachiller</option>
                                <option value="TITULADO" selected>Titulado Profesional</option>
                                <option value="MAESTRIA">Maestría</option>
                                <option value="DOCTORADO">Doctorado</option>
                                <option value="SEGUNDA ESPECIALIDAD">Segunda Especialidad</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold">Carrera / Especialidad *</label>
                            <input type="text" class="form-control" name="for_carrera_especialidad" placeholder="Ej: Derecho, Contabilidad, Ingeniería de Sistemas" required>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label small fw-semibold">Universidad / Instituto *</label>
                            <input type="text" class="form-control" name="for_institucion" placeholder="Ej: Universidad Nacional Mayor de San Marcos" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Grado / Título Obtenido</label>
                            <input type="text" class="form-control" name="for_grado_obtenido" placeholder="Ej: Licenciado en Administración">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Fecha de Expedición</label>
                            <input type="date" class="form-control" name="for_fecha_expedicion">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Colegio Profesional (si aplica)</label>
                            <input type="text" class="form-control" name="for_colegio_profesional" placeholder="Ej: Colegio de Abogados de Lima">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">N° de Colegiatura</label>
                            <input type="text" class="form-control" name="for_numero_colegiatura" placeholder="Ej: CAL-54128">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Código / Registro SUNEDU</label>
                            <input type="text" class="form-control" name="for_registro_sunedu" placeholder="Ej: 2021-UNMSM-124">
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" name="for_es_habilitado" id="for_es_habilitado" value="1" checked>
                                <label class="form-check-label small fw-semibold" for="for_es_habilitado">Habilitado Profesional Vigente</label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">Diploma / Título / Constancia SUNEDU (PDF)</label>
                            <input type="file" class="form-control" name="for_adjunto" accept=".pdf,image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Guardar Formación</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL 3: EXPERIENCIA LABORAL -->
<div class="modal fade" id="modalExperiencia" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><iconify-icon icon="solar:case-bold" class="me-2"></iconify-icon> Registrar Experiencia Laboral Previa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formExperiencia" onsubmit="guardarSeccion(event, 'experiencia/guardar', 'formExperiencia', '#modalExperiencia')">
                <input type="hidden" name="exp_ser_ide" value="<?= $servidor['ser_ide'] ?>">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Sector de la Entidad *</label>
                            <select class="form-select" name="exp_tipo_entidad" required>
                                <option value="PUBLICA" selected>Sector Público</option>
                                <option value="PRIVADA">Sector Privado</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold">Entidad / Empresa *</label>
                            <input type="text" class="form-control" name="exp_entidad_empresa" required placeholder="Ej: Ministerio de Economía y Finanzas">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Cargo Desempeñado *</label>
                            <input type="text" class="form-control" name="exp_cargo_desempenado" required placeholder="Ej: Especialista de Presupuesto">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Unidad Orgánica / Área</label>
                            <input type="text" class="form-control" name="exp_unidad_organica" placeholder="Ej: Dirección General de Presupuesto Público">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Fecha Inicio *</label>
                            <input type="date" class="form-control" name="exp_fecha_inicio" id="exp_fecha_inicio" required onchange="calcularTiempoExp()">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Fecha Fin</label>
                            <input type="date" class="form-control" name="exp_fecha_fin" id="exp_fecha_fin" onchange="calcularTiempoExp()">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Años</label>
                            <input type="number" class="form-control" name="exp_tiempo_anios" id="exp_anios" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Meses</label>
                            <input type="number" class="form-control" name="exp_tiempo_meses" id="exp_meses" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Días</label>
                            <input type="number" class="form-control" name="exp_tiempo_dias" id="exp_dias" value="0">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">Funciones Principales / Logros</label>
                            <textarea class="form-control" name="exp_funciones_principales" rows="2"></textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">Certificado o Constancia de Trabajo (PDF)</label>
                            <input type="file" class="form-control" name="exp_adjunto" accept=".pdf,image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Guardar Experiencia</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL 4: MOVIMIENTO DE PERSONAL -->
<div class="modal fade" id="modalMovimiento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><iconify-icon icon="solar:transfer-vertical-bold" class="me-2"></iconify-icon> Registrar Movimiento de Personal</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formMovimiento" onsubmit="guardarSeccion(event, 'movimientos/guardar', 'formMovimiento', '#modalMovimiento')">
                <input type="hidden" name="mov_ser_ide" value="<?= $servidor['ser_ide'] ?>">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Tipo de Movimiento *</label>
                            <select class="form-select" name="mov_tipo" required>
                                <option value="ROTACION">Rotación Interna</option>
                                <option value="REASIGNACION">Reasignación</option>
                                <option value="DESTAQUE">Destaque</option>
                                <option value="PERMUTA">Permuta</option>
                                <option value="ENCARGATURA">Encargatura de Puesto/Funciones</option>
                                <option value="COMISION_SERVICIO">Comisión de Servicio</option>
                                <option value="LICENCIA_CON_GOCE">Licencia con Goce de Haber</option>
                                <option value="LICENCIA_SIN_GOCE">Licencia sin Goce de Haber</option>
                                <option value="VACACIONES">Descanso Vacacional</option>
                                <option value="SUSPENSION">Suspensión Temporal</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Tipo de Documento Sustento *</label>
                            <select class="form-select" name="mov_tipo_documento_sustento">
                                <option value="RESOLUCION DIRECTORAL">Resolución Directoral</option>
                                <option value="RESOLUCION PRESIDENCIAL">Resolución Presidencial</option>
                                <option value="RESOLUCION MINISTERIAL">Resolución Ministerial</option>
                                <option value="MEMORANDO">Memorando</option>
                                <option value="OFICIO">Oficio</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">N° de Documento / Resolución *</label>
                            <input type="text" class="form-control" name="mov_numero_documento" placeholder="Ej: RD N° 124-2026-MINSA" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Fecha del Documento</label>
                            <input type="date" class="form-control" name="mov_fecha_documento">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Dependencia de Origen</label>
                            <input type="text" class="form-control" name="mov_dependencia_origen" value="<?= esc($servidor['ser_dependencia']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Dependencia de Destino</label>
                            <input type="text" class="form-control" name="mov_dependencia_destino" placeholder="Ej: Oficina de Abastecimiento">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Fecha de Inicio *</label>
                            <input type="date" class="form-control" name="mov_fecha_inicio" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Fecha de Fin (si aplica)</label>
                            <input type="date" class="form-control" name="mov_fecha_fin">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Días Computados</label>
                            <input type="number" class="form-control" name="mov_dias_computados" value="0">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">Motivo y Detalle del Acto</label>
                            <textarea class="form-control" name="mov_motivo_detalle" rows="2"></textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">Resolución / Memorando Escaneado (PDF)</label>
                            <input type="file" class="form-control" name="mov_adjunto" accept=".pdf">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Guardar Movimiento</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL 5: CAPACITACIÓN / EVALUACIÓN -->
<div class="modal fade" id="modalCapacitacion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><iconify-icon icon="solar:document-medicine-bold" class="me-2"></iconify-icon> Registrar Capacitación / Evaluación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCapacitacion" onsubmit="guardarSeccion(event, 'capacitaciones/guardar', 'formCapacitacion', '#modalCapacitacion')">
                <input type="hidden" name="evc_ser_ide" value="<?= $servidor['ser_ide'] ?>">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Tipo *</label>
                            <select class="form-select" name="evc_tipo" required>
                                <option value="CAPACITACION" selected>Capacitación / Curso</option>
                                <option value="EVALUACION_DESEMPENO">Evaluación del Rendimiento (GDR)</option>
                                <option value="CERTIFICACION">Certificación de Competencias</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold">Título del Evento / Evaluación *</label>
                            <input type="text" class="form-control" name="evc_titulo" required placeholder="Ej: Gestión de Contrataciones del Estado (OSCE)">
                        </div>

                        <div class="col-md-8">
                            <label class="form-label small fw-semibold">Institución Organizadora *</label>
                            <input type="text" class="form-control" name="evc_institucion_organizadora" required placeholder="Ej: Escuela Nacional de Administración Pública (ENAP/SERVIR)">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Modalidad de Evento</label>
                            <select class="form-select" name="evc_tipo_evento">
                                <option value="CURSO" selected>Curso</option>
                                <option value="TALLER">Taller</option>
                                <option value="DIPLOMADO">Diplomado</option>
                                <option value="SEMINARIO">Seminario / Conferencia</option>
                                <option value="PASANTIA">Pasantía</option>
                                <option value="GDR">Evaluación de Desempeño</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Fecha Inicio *</label>
                            <input type="date" class="form-control" name="evc_fecha_inicio" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Fecha Fin</label>
                            <input type="date" class="form-control" name="evc_fecha_fin">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Horas Académicas</label>
                            <input type="number" class="form-control" name="evc_horas_academicas" value="0">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Calificación / Nota Obtenida</label>
                            <input type="text" class="form-control" name="evc_calificacion_obtenida" placeholder="Ej: 18 - Sobresaliente">
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" name="evc_es_financiado_entidad" id="evc_es_financiado_entidad" value="1">
                                <label class="form-check-label small fw-semibold" for="evc_es_financiado_entidad">Financiado por PDP de la Entidad</label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">Certificado / Informe de Evaluación (PDF)</label>
                            <input type="file" class="form-control" name="evc_adjunto" accept=".pdf,image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Guardar Capacitación</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL 6: MÉRITOS Y SANCIONES (PAD) -->
<div class="modal fade" id="modalSancionMerito" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><iconify-icon icon="solar:medal-ribbon-star-bold" class="me-2"></iconify-icon> Registrar Mérito o Sanción (PAD)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formSancionMerito" onsubmit="guardarSeccion(event, 'meritos-sanciones/guardar', 'formSancionMerito', '#modalSancionMerito')">
                <input type="hidden" name="msa_ser_ide" value="<?= $servidor['ser_ide'] ?>">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Naturaleza del Registro *</label>
                            <select class="form-select" name="msa_tipo" id="msa_tipo" required onchange="cambiarTipoMeritoSancion()">
                                <option value="MERITO" selected>MÉRITO / RECONOCIMIENTO</option>
                                <option value="SANCION">SANCIÓN DISCIPLINARIA (PAD)</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold">Subtipo de Acto *</label>
                            <select class="form-select" name="msa_subtipo" id="msa_subtipo" required>
                                <option value="FELICITACION">Resolución de Felicitación</option>
                                <option value="RECONOCIMIENTO">Reconocimiento Institucional</option>
                                <option value="CONDECORACION">Condecoración</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Acto Resolutivo *</label>
                            <input type="text" class="form-control" name="msa_acto_resolutivo" required placeholder="Ej: RM N° 458-2026-MINSA">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Fecha del Acto *</label>
                            <input type="date" class="form-control" name="msa_fecha_acto" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Entidad Emisora *</label>
                            <input type="text" class="form-control" name="msa_entidad_emisora" required placeholder="Ej: Ministerio de Salud">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">N° Expediente PAD (si aplica)</label>
                            <input type="text" class="form-control" name="msa_numero_expediente_pad" placeholder="Ej: EXP-PAD-2026-004">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">Descripción y Motivo</label>
                            <textarea class="form-control" name="msa_descripcion_motivo" rows="2"></textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">Resolución / Documento Oficial (PDF)</label>
                            <input type="file" class="form-control" name="msa_adjunto" accept=".pdf">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Guardar Registro</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL 7: EXPEDIENTE DIGITAL -->
<div class="modal fade" id="modalDocumento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><iconify-icon icon="solar:cloud-upload-bold" class="me-2"></iconify-icon> Incorporar Documento al Expediente Digital</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formDocumento" onsubmit="guardarSeccion(event, 'documentos/guardar', 'formDocumento', '#modalDocumento')">
                <input type="hidden" name="doc_ser_ide" value="<?= $servidor['ser_ide'] ?>">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Sección del Legajo *</label>
                            <select class="form-select" name="doc_sec_ide" required>
                                <?php foreach ($secciones as $sec): ?>
                                    <option value="<?= $sec['sec_ide'] ?>">Sección <?= $sec['sec_numero'] ?>: <?= esc($sec['sec_nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">N° de Folio en Legajo Físico</label>
                            <input type="text" class="form-control" name="doc_numero_folio" placeholder="Ej: 045">
                        </div>

                        <div class="col-md-8">
                            <label class="form-label small fw-semibold">Título / Descripción del Documento *</label>
                            <input type="text" class="form-control" name="doc_titulo" required placeholder="Ej: Declaración Jurada de Bienes y Rentas 2026">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Fecha de Emisión</label>
                            <input type="date" class="form-control" name="doc_fecha_emision" value="<?= date('Y-m-d') ?>">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">Archivo Digitalizado (PDF o Imagen) *</label>
                            <input type="file" class="form-control" name="doc_archivo" accept=".pdf,image/*" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">Observaciones adicionales</label>
                            <textarea class="form-control" name="doc_observacion" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Subir Documento</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL: VISOR DE DOCUMENTOS PDF -->
<div class="modal fade" id="modalVisorPdf" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" style="height: 90vh;">
        <div class="modal-content rounded-4 border-0 shadow h-100">
            <div class="modal-header bg-dark text-white border-0 py-2">
                <h6 class="modal-title fw-bold"><iconify-icon icon="solar:file-text-bold" class="me-2"></iconify-icon> Visor de Documento Digitalizado</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0 h-100 bg-secondary-subtle">
                <iframe id="iframePdf" src="" class="w-100 h-100 border-0" style="min-height: 500px;"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: EDITAR FICHA PRINCIPAL DEL SERVIDOR (REUTILIZA FORMULARIO) -->
<div class="modal fade" id="modalEditarServidorActual" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><iconify-icon icon="solar:pen-bold" class="me-2"></iconify-icon> Actualizar Ficha del Servidor Público</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formServidorActual" enctype="multipart/form-data" onsubmit="actualizarServidorActual(event)">
                <input type="hidden" name="ser_ide" value="<?= $servidor['ser_ide'] ?>">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Tipo Documento</label>
                            <select class="form-select" name="ser_tipo_documento">
                                <option value="DNI" <?= $servidor['ser_tipo_documento'] === 'DNI' ? 'selected' : '' ?>>DNI</option>
                                <option value="CARNET EXT." <?= $servidor['ser_tipo_documento'] === 'CARNET EXT.' ? 'selected' : '' ?>>Carné de Extranjería</option>
                                <option value="PASAPORTE" <?= $servidor['ser_tipo_documento'] === 'PASAPORTE' ? 'selected' : '' ?>>Pasaporte</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">N° Documento *</label>
                            <input type="text" class="form-control" name="ser_numero_documento" value="<?= esc($servidor['ser_numero_documento']) ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">RUC</label>
                            <input type="text" class="form-control" name="ser_ruc" value="<?= esc($servidor['ser_ruc']) ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Sexo</label>
                            <select class="form-select" name="ser_sexo">
                                <option value="M" <?= $servidor['ser_sexo'] === 'M' ? 'selected' : '' ?>>Masculino</option>
                                <option value="F" <?= $servidor['ser_sexo'] === 'F' ? 'selected' : '' ?>>Femenino</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Nombres *</label>
                            <input type="text" class="form-control" name="ser_nombres" value="<?= esc($servidor['ser_nombres']) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Apellido Paterno *</label>
                            <input type="text" class="form-control" name="ser_apellido_paterno" value="<?= esc($servidor['ser_apellido_paterno']) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Apellido Materno</label>
                            <input type="text" class="form-control" name="ser_apellido_materno" value="<?= esc($servidor['ser_apellido_materno']) ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Régimen Laboral</label>
                            <select class="form-select" name="ser_regimen_laboral">
                                <option value="D.L. 276" <?= $servidor['ser_regimen_laboral'] === 'D.L. 276' ? 'selected' : '' ?>>D.L. 276</option>
                                <option value="D.L. 728" <?= $servidor['ser_regimen_laboral'] === 'D.L. 728' ? 'selected' : '' ?>>D.L. 728</option>
                                <option value="D.L. 1057 (CAS)" <?= $servidor['ser_regimen_laboral'] === 'D.L. 1057 (CAS)' ? 'selected' : '' ?>>D.L. 1057 (CAS)</option>
                                <option value="LEY 30057 (SERVIR)" <?= $servidor['ser_regimen_laboral'] === 'LEY 30057 (SERVIR)' ? 'selected' : '' ?>>Ley 30057 (SERVIR)</option>
                                <option value="LOCACION DE SERVICIOS" <?= $servidor['ser_regimen_laboral'] === 'LOCACION DE SERVICIOS' ? 'selected' : '' ?>>Locación de Servicios</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Condición</label>
                            <input type="text" class="form-control" name="ser_condicion_laboral" value="<?= esc($servidor['ser_condicion_laboral']) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Estado</label>
                            <select class="form-select" name="ser_estado">
                                <option value="ACTIVO" <?= $servidor['ser_estado'] === 'ACTIVO' ? 'selected' : '' ?>>ACTIVO</option>
                                <option value="LICENCIA" <?= $servidor['ser_estado'] === 'LICENCIA' ? 'selected' : '' ?>>LICENCIA</option>
                                <option value="SUSPENDIDO" <?= $servidor['ser_estado'] === 'SUSPENDIDO' ? 'selected' : '' ?>>SUSPENDIDO</option>
                                <option value="CESADO" <?= $servidor['ser_estado'] === 'CESADO' ? 'selected' : '' ?>>CESADO</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Cargo Institucional *</label>
                            <input type="text" class="form-control" name="ser_cargo" value="<?= esc($servidor['ser_cargo']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Dependencia / Oficina *</label>
                            <input type="text" class="form-control" name="ser_dependencia" value="<?= esc($servidor['ser_dependencia']) ?>" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Celular</label>
                            <input type="text" class="form-control" name="ser_celular" value="<?= esc($servidor['ser_celular']) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Email Institucional</label>
                            <input type="email" class="form-control" name="ser_email_institucional" value="<?= esc($servidor['ser_email_institucional']) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Actualizar Foto</label>
                            <input type="file" class="form-control" name="ser_foto_archivo" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Actualizar Servidor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script>
function abrirModalFamiliar() {
    $('#formFamiliar')[0].reset();
    $('#modalFamiliar').modal('show');
}

function abrirModalFormacion() {
    $('#formFormacion')[0].reset();
    $('#modalFormacion').modal('show');
}

function abrirModalExperiencia() {
    $('#formExperiencia')[0].reset();
    $('#modalExperiencia').modal('show');
}

function abrirModalMovimiento() {
    $('#formMovimiento')[0].reset();
    $('#modalMovimiento').modal('show');
}

function abrirModalCapacitacion() {
    $('#formCapacitacion')[0].reset();
    $('#modalCapacitacion').modal('show');
}

function abrirModalSancionMerito() {
    $('#formSancionMerito')[0].reset();
    $('#modalSancionMerito').modal('show');
}

function abrirModalDocumento() {
    $('#formDocumento')[0].reset();
    $('#modalDocumento').modal('show');
}

function editarServidorActual() {
    $('#modalEditarServidorActual').modal('show');
}

function verPdf(pathBase64) {
    const url = '<?= base_url('legajos/ver-documento?path=') ?>' + encodeURIComponent(pathBase64);
    $('#iframePdf').attr('src', url);
    $('#modalVisorPdf').modal('show');
}

function calcularTiempoExp() {
    const inicio = $('#exp_fecha_inicio').val();
    const fin = $('#exp_fecha_fin').val() || new Date().toISOString().split('T')[0];

    if (inicio && fin) {
        const d1 = new Date(inicio);
        const d2 = new Date(fin);
        if (d2 >= d1) {
            let years = d2.getFullYear() - d1.getFullYear();
            let months = d2.getMonth() - d1.getMonth();
            let days = d2.getDate() - d1.getDate();

            if (days < 0) {
                months--;
                days += 30;
            }
            if (months < 0) {
                years--;
                months += 12;
            }

            $('#exp_anios').val(years >= 0 ? years : 0);
            $('#exp_meses').val(months >= 0 ? months : 0);
            $('#exp_dias').val(days >= 0 ? days : 0);
        }
    }
}

function cambiarTipoMeritoSancion() {
    const tipo = $('#msa_tipo').val();
    const $subtipo = $('#msa_subtipo');
    $subtipo.empty();

    if (tipo === 'MERITO') {
        $subtipo.append('<option value="FELICITACION">Resolución de Felicitación</option>');
        $subtipo.append('<option value="RECONOCIMIENTO">Reconocimiento Institucional</option>');
        $subtipo.append('<option value="CONDECORACION">Condecoración</option>');
    } else {
        $subtipo.append('<option value="AMONESTACION_VERBAL">Amonestación Verbal</option>');
        $subtipo.append('<option value="AMONESTACION_ESCRITA">Amonestación Escrita</option>');
        $subtipo.append('<option value="SUSPENSION_PAD">Suspensión sin goce (PAD)</option>');
        $subtipo.append('<option value="DESTITUCION_PAD">Destitución (PAD)</option>');
        $subtipo.append('<option value="INHABILITACION">Inhabilitación para el Servicio Civil</option>');
    }
}

function guardarSeccion(e, endpoint, formId, modalId) {
    e.preventDefault();
    const formData = new FormData(document.getElementById(formId));

    Swal.fire({
        title: 'Guardando registro...',
        text: 'Por favor espere mientras se procesa la información.',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    $.ajax({
        url: `<?= base_url('legajos') ?>/${endpoint}`,
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(res) {
            if (res.status === 'success') {
                $(modalId).modal('hide');
                Swal.fire('Éxito', res.message, 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Atención', res.message, 'warning');
            }
        },
        error: function(xhr) {
            const err = xhr.responseJSON ? xhr.responseJSON.message : 'Error al guardar el registro.';
            Swal.fire('Error', err, 'error');
        }
    });
}

function actualizarServidorActual(e) {
    e.preventDefault();
    const formData = new FormData(document.getElementById('formServidorActual'));

    $.ajax({
        url: '<?= base_url('legajos/guardar-servidor') ?>',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(res) {
            if (res.status === 'success') {
                $('#modalEditarServidorActual').modal('hide');
                Swal.fire('Éxito', res.message, 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Atención', res.message, 'warning');
            }
        },
        error: function(xhr) {
            const err = xhr.responseJSON ? xhr.responseJSON.message : 'Error al actualizar el servidor.';
            Swal.fire('Error', err, 'error');
        }
    });
}

function eliminarFamiliar(id) {
    confirmarEliminacion('familiares/eliminar', id, 'familiar');
}
function eliminarFormacion(id) {
    confirmarEliminacion('formacion/eliminar', id, 'registro académico');
}
function eliminarExperiencia(id) {
    confirmarEliminacion('experiencia/eliminar', id, 'experiencia laboral');
}
function eliminarMovimiento(id) {
    confirmarEliminacion('movimientos/eliminar', id, 'movimiento de personal');
}
function eliminarCapacitacion(id) {
    confirmarEliminacion('capacitaciones/eliminar', id, 'capacitación');
}
function eliminarSancionMerito(id) {
    confirmarEliminacion('meritos-sanciones/eliminar', id, 'registro de mérito o sanción');
}
function eliminarDocumento(id) {
    confirmarEliminacion('documentos/eliminar', id, 'documento digitalizado');
}

function confirmarEliminacion(endpoint, id, tipo) {
    Swal.fire({
        title: '¿Confirmar eliminación?',
        text: `Se eliminará el registro de ${tipo} del legajo.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post(`<?= base_url('legajos') ?>/${endpoint}/${id}`, function(res) {
                if (res.status === 'success') {
                    Swal.fire('Eliminado', res.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
        }
    });
}
</script>
<?= $this->endSection() ?>

