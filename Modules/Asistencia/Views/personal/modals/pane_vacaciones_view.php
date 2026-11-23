<?php
$personalId = (int) $personal['perl_ide'];
$periodosRegistrados = array_column(array_filter($periodos, static fn(array $p): bool => $p['vac_ide'] !== null), null, 'vac_ide');
$haySaldo = (bool) array_filter($periodos, static fn(array $p): bool => $p['habilitado'] && $p['saldo'] > 0);
$visibles = 0;
?>
<?php if (!empty($paginaCompleta)): ?>
    <?= $this->extend('layouts/asistenciaLayout') ?>
    <?= $this->section('title') ?>Vacaciones<?= $this->endSection() ?>
    <?= $this->section('content') ?>
    <div class="container-fluid py-3">
        <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
            <h1 class="h3 mb-0">Vacaciones</h1>
            <a class="btn btn-outline-secondary" href="<?= site_url('asistencia/personal/gestorpersonal') ?>">Volver al Gestor de personal</a>
        </div>
        <div class="card card-body">
<?php endif ?>
<section class="vacaciones-panel" data-personal="<?= $personalId ?>" data-url="<?= base_url('asistencia/personal/vacaciones/' . $personalId . '/usos') ?>">
    <h6 class="fw-bold">Periodos anuales de vacaciones</h6>
    <p class="small text-muted">Ingreso: <strong><?= esc($personal['perl_fecha_inicio'] ?: 'No registrado') ?></strong>. Los días se habilitan al cumplir 12 meses de servicio. Cada uso se descuenta del periodo seleccionado, incluidos los usos programados a futuro.</p>
    <?php if ($aviso): ?><div class="alert alert-warning"><?= esc($aviso) ?></div><?php endif; ?>
    <div class="table-responsive mb-3">
        <table class="table table-sm table-bordered align-middle">
            <thead class="table-light"><tr><th>Periodo de servicio</th><th>Disponible desde</th><th>Ganados</th><th>Asignados a usos</th><th>Saldo</th><th>Estado</th></tr></thead>
            <tbody>
                <?php foreach ($periodos as $p): ?>
                    <tr>
                        <td><?= esc($p['inicio']) ?> al <?= esc($p['fin']) ?></td>
                        <td><?= esc($p['disponible_desde'] ?? 'Por revisar') ?></td>
                        <td><?= (int) $p['ganados'] ?></td><td><?= (int) $p['asignados'] ?></td><td class="fw-bold"><?= (int) $p['saldo'] ?></td>
                        <td><?= !empty($p['revisar']) ? 'Revisar: no coincide con el ingreso' : (!$p['cumplido'] ? 'En acumulación' : (!$p['habilitado'] ? 'Inactivo' : ($p['saldo'] > 0 ? 'Disponible' : 'Sin saldo'))) ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$periodos): ?><tr><td colspan="6" class="text-center text-muted">No hay periodos calculables con la fecha de ingreso registrada.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="d-flex flex-wrap align-items-end justify-content-between gap-2 mb-3">
        <h6 class="fw-bold mb-0">Historial de usos</h6>
        <div class="d-flex flex-wrap align-items-end gap-2">
            <label class="small">Mes del uso<input type="month" class="form-control form-control-sm vac-filtro" value="<?= esc($mes, 'attr') ?>"></label>
            <button type="button" class="btn btn-sm btn-outline-secondary vac-limpiar">Todos los meses</button>
            <?php if ($puedeCrear): ?>
                <button type="button" class="btn btn-sm btn-primary vac-nuevo" <?= $haySaldo ? '' : 'disabled' ?>>Nuevo uso</button>
            <?php endif ?>
        </div>
    </div>
    <?php if ($puedeCrear && !$haySaldo): ?><p class="small text-muted">No hay periodos habilitados con saldo para registrar otro uso.</p><?php endif ?>
    <?= csrf_field() ?>
    <?php if ($puedeCrear || $puedeEditar): ?>
    <div class="modal fade vac-modal" tabindex="-1" aria-labelledby="vac-modal-titulo" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="vac-modal-titulo">Registrar uso de vacaciones</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form class="form-uso-vacaciones" enctype="multipart/form-data">
                    <div class="modal-body row g-3">
        <div class="col-md-6"><label class="form-label" for="vac-periodo">Periodo anual</label>
            <select id="vac-periodo" name="periodo" class="form-select" required>
                <option value="">Seleccione un periodo con saldo</option>
                <?php foreach ($periodos as $p): ?>
                    <?php if ($p['habilitado']): ?>
                        <option value="<?= esc($p['inicio'], 'attr') ?>" data-desde="<?= esc($p['disponible_desde'], 'attr') ?>" data-saldo="<?= (int) $p['saldo'] ?>"><?= esc($p['inicio'] . ' al ' . $p['fin']) ?> · Saldo: <?= (int) $p['saldo'] ?> días</option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3"><label class="form-label" for="vac-inicio">Inicio del uso</label><input type="date" id="vac-inicio" name="fecha_inicio" class="form-control" required></div>
        <div class="col-md-3"><label class="form-label" for="vac-fin">Fin del uso</label><input type="date" id="vac-fin" name="fecha_fin" class="form-control" required></div>
        <div class="col-md-4"><label class="form-label" for="vac-documento">Documento de autorización</label><input id="vac-documento" name="documento" class="form-control" maxlength="100"></div>
        <div class="col-md-8"><label class="form-label" for="vac-observacion">Observación</label><textarea id="vac-observacion" name="observacion" class="form-control" rows="2" maxlength="2000"></textarea></div>
        <div class="col-12"><label for="vac-sustento" class="form-label">Archivo de sustento</label><input type="file" id="vac-sustento" name="sustento" class="form-control" accept=".pdf,.jpg,.jpeg,.png"><small class="text-muted">PDF, JPG o PNG. Hasta 10 MB.</small></div>
        <div class="col-12">
            <p class="vac-resumen small text-muted mb-0" aria-live="polite"></p>
            <div class="vac-error alert alert-danger d-none mt-2 mb-0" role="alert"></div>
        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
                            <span class="vac-guardar-texto">Guardar uso</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif ?>
    <div class="table-responsive"><table class="table table-sm table-bordered align-middle">
        <thead class="table-light"><tr><th>Periodo anual</th><th>Inicio</th><th>Fin</th><th>Días</th><th>Documento</th><th>Observación</th><th>Estado</th><th>Adjuntos</th><th>Acciones</th></tr></thead>
        <tbody>
            <?php foreach ($usos as $u): ?>
                <?php
                $periodoUso = $periodosRegistrados[$u['rv_vac_ide']] ?? null;
                $editable = (int) $u['rv_estado'] === 1;
                $etiqueta = $periodoUso ? $periodoUso['inicio'] . ' al ' . $periodoUso['fin'] : 'Periodo #' . $u['rv_vac_ide'];
                $visible = !$mes || (substr($u['rv_fecha_inicio'], 0, 7) <= $mes && substr($u['rv_fecha_fin'], 0, 7) >= $mes);
                if ($visible) $visibles++;
                ?>
                <tr class="vac-uso" <?= $visible ? '' : 'hidden' ?> data-inicio="<?= esc($u['rv_fecha_inicio'], 'attr') ?>" data-fin="<?= esc($u['rv_fecha_fin'], 'attr') ?>"><td><?= esc($etiqueta) ?></td><td><?= esc($u['rv_fecha_inicio']) ?></td><td><?= esc($u['rv_fecha_fin']) ?></td><td><?= (int) $u['rv_dias'] ?></td><td><?= esc($u['rv_numero_documento'] ?: '—') ?></td><td class="text-break"><?= esc($u['rv_observacion'] ?? '') ?></td><td><?= (int) $u['rv_estado'] !== 1 ? 'Anulado' : ($u['rv_fecha_inicio'] > date('Y-m-d') ? 'Programado' : ($u['rv_fecha_fin'] < date('Y-m-d') ? 'Gozado' : 'En curso')) ?></td>
                    <td class="text-center">
                        <?php foreach ($u['adjuntos'] ?? [] as $adj): ?>
                            <a class="btn btn-sm btn-outline-info" target="_blank" rel="noopener" title="<?= esc($adj['adj_nombre_original'], 'attr') ?>" aria-label="Abrir archivo adjunto" href="<?= base_url('asistencia/adjuntos/ver/' . (int) $adj['adj_ide']) ?>"><iconify-icon icon="solar:paperclip-linear"></iconify-icon></a>
                        <?php endforeach; ?>
                        <?= empty($u['adjuntos']) ? '-' : '' ?>
                    </td>
                    <td class="text-nowrap">
                        <?php if ($editable && $puedeEditar): ?>
                            <button type="button" class="btn btn-sm btn-outline-primary vac-editar" data-uso="<?= (int) $u['rv_ide'] ?>"
                                data-periodo="<?= esc($periodoUso['inicio'] ?? '', 'attr') ?>" data-dias="<?= (int) $u['rv_dias'] ?>"
                                data-documento="<?= esc($u['rv_numero_documento'] ?? '', 'attr') ?>" data-observacion="<?= esc($u['rv_observacion'] ?? '', 'attr') ?>"
                                title="Editar uso" aria-label="Editar uso"><iconify-icon icon="solar:pen-bold"></iconify-icon></button>
                        <?php endif ?>
                        <?php if ($editable && $puedeEliminar): ?>
                            <button type="button" class="btn btn-sm btn-outline-danger vac-eliminar" data-uso="<?= (int) $u['rv_ide'] ?>" title="Eliminar uso y devolver saldo" aria-label="Eliminar uso y devolver saldo"><iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon></button>
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr class="vac-sin-usos <?= $visibles ? 'd-none' : '' ?>"><td colspan="9" class="text-center text-muted">Sin usos registrados para el filtro seleccionado.</td></tr>
        </tbody>
    </table></div>
</section>
<?php if (!empty($paginaCompleta)): ?>
        </div>
    </div>
    <?= $this->endSection() ?>
    <?= $this->section('pageScripts') ?>
    <script src="<?= base_url('assets/js/asistencia-vacaciones.js') . '?v=' . filemtime(FCPATH . 'assets/js/asistencia-vacaciones.js') ?>"></script>
    <?= $this->endSection() ?>
<?php endif ?>
