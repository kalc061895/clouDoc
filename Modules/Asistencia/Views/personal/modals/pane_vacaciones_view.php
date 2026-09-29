<?php $personalId = (int) $personal['perl_ide']; ?>
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
    <h6 class="fw-bold">Registrar uso de vacaciones</h6>
    <form class="form-uso-vacaciones row g-3 mb-4" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="col-md-6"><label class="form-label" for="vac-periodo">Periodo anual</label>
            <select id="vac-periodo" name="periodo" class="form-select" required>
                <option value="">Seleccione un periodo con saldo</option>
                <?php foreach ($periodos as $p): ?>
                    <?php if ($p['habilitado'] && $p['saldo'] > 0): ?>
                        <option value="<?= esc($p['inicio']) ?>" data-desde="<?= esc($p['disponible_desde']) ?>" data-saldo="<?= (int) $p['saldo'] ?>"><?= esc($p['inicio'] . ' al ' . $p['fin']) ?> · Saldo: <?= (int) $p['saldo'] ?> días</option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3"><label class="form-label" for="vac-inicio">Inicio del uso</label><input type="date" id="vac-inicio" name="fecha_inicio" class="form-control" required></div>
        <div class="col-md-3"><label class="form-label" for="vac-fin">Fin del uso</label><input type="date" id="vac-fin" name="fecha_fin" class="form-control" required></div>
        <div class="col-md-4"><label class="form-label" for="vac-documento">Documento de autorización</label><input id="vac-documento" name="documento" class="form-control" maxlength="100"></div>
        <div class="col-md-8"><label class="form-label" for="vac-observacion">Observación</label><textarea id="vac-observacion" name="observacion" class="form-control" rows="2" maxlength="2000"></textarea></div>
        <div class="col-12"><label for="vac-sustento" class="form-label">Archivo de sustento</label><input type="file" id="vac-sustento" name="sustento" class="form-control" accept=".pdf,.jpg,.jpeg,.png"><small class="text-muted">PDF, JPG o PNG. Hasta 10 MB.</small></div>
        <div class="col-12"><p class="vac-resumen small text-muted" aria-live="polite">Se cuentan días calendario, incluyendo las fechas de inicio y fin.</p><div class="vac-error alert alert-danger d-none" role="alert"></div><button type="submit" class="btn btn-primary">Registrar uso</button></div>
    </form>
    <h6 class="fw-bold">Historial de usos</h6>
    <div class="table-responsive"><table class="table table-sm table-bordered align-middle">
        <thead class="table-light"><tr><th>Periodo anual</th><th>Inicio</th><th>Fin</th><th>Días</th><th>Documento</th><th>Observación</th><th>Estado</th><th>Adjuntos</th><th>Acciones</th></tr></thead>
        <tbody>
            <?php foreach ($usos as $u): ?>
                <?php $etiqueta = 'Periodo #' . $u['rv_vac_ide']; foreach ($periodos as $p) { if ($p['vac_ide'] === (int) $u['rv_vac_ide']) $etiqueta = $p['inicio'] . ' al ' . $p['fin']; } ?>
                <tr><td><?= esc($etiqueta) ?></td><td><?= esc($u['rv_fecha_inicio']) ?></td><td><?= esc($u['rv_fecha_fin']) ?></td><td><?= (int) $u['rv_dias'] ?></td><td><?= esc($u['rv_numero_documento'] ?: '—') ?></td><td class="text-break"><?= esc($u['rv_observacion'] ?? '') ?></td><td><?= (int) $u['rv_estado'] !== 1 ? 'Anulado' : ($u['rv_fecha_inicio'] > date('Y-m-d') ? 'Programado' : ($u['rv_fecha_fin'] < date('Y-m-d') ? 'Gozado' : 'En curso')) ?></td>
                    <td class="text-center">
                        <?php foreach ($u['adjuntos'] ?? [] as $adj): ?>
                            <a class="btn btn-sm btn-outline-info" target="_blank" rel="noopener" title="<?= esc($adj['adj_nombre_original'], 'attr') ?>" aria-label="Abrir archivo adjunto" href="<?= base_url('asistencia/adjuntos/ver/' . (int) $adj['adj_ide']) ?>"><iconify-icon icon="solar:paperclip-linear"></iconify-icon></a>
                        <?php endforeach; ?>
                        <?= empty($u['adjuntos']) ? '-' : '' ?>
                    </td>
                    <td><?php if ((int) $u['rv_estado'] === 1): ?><button type="button" class="btn btn-sm btn-outline-danger vac-eliminar" data-uso="<?= (int) $u['rv_ide'] ?>" title="Eliminar uso y devolver saldo" aria-label="Eliminar uso y devolver saldo"><iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon></button><?php endif; ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$usos): ?><tr><td colspan="9" class="text-center text-muted">Sin usos registrados.</td></tr><?php endif; ?>
        </tbody>
    </table></div>
</section>
