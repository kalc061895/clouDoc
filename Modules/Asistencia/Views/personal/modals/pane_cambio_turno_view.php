<?php
$personalId = (int) $personal['perl_ide'];
$mes = (int) substr($inicio, 5, 2);
$anio = (int) substr($inicio, 0, 4);
$meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
$nombre = trim($personal['per_paterno'] . ' ' . $personal['per_materno'] . ' ' . $personal['per_nombre']);
?>
<div class="calendario-personal cambio-turno-panel" data-personal="<?= $personalId ?>" data-tipo="cambio-turno" data-url="<?= base_url('asistencia/personal/cambio-turno/' . $personalId) ?>">
    <h6 class="fw-bold">Registrar cambio de turno</h6>
    <p class="small text-muted">Seleccione dos turnos ya programados. Cada trabajador asumirá la fecha y el horario del otro. Solo se permiten compañeros del mismo establecimiento y cargo.</p>
    <form class="form-cambio-turno" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Trabajador solicitante</label>
                <input class="form-control" value="<?= esc($nombre, 'attr') ?>" readonly>
                <label class="form-label mt-2" for="ct-fecha-sol">Fecha del turno que entrega</label>
                <input id="ct-fecha-sol" type="date" class="form-control ct-fecha" data-lado="sol" required>
                <label class="form-label mt-2" for="ct-turno-sol">Turno programado del solicitante</label>
                <select id="ct-turno-sol" class="form-select ct-turno" data-lado="sol" name="prog_sol_id" required disabled><option value="">Seleccione una fecha</option></select>
                <input type="hidden" name="version_sol">
            </div>
            <div class="col-md-6">
                <label class="form-label" for="ct-personal">Trabajador que intercambia</label>
                <select id="ct-personal" class="form-select ct-personal" name="otro_personal_id" required>
                    <option value="">Seleccione un compañero</option>
                    <?php foreach ($companeros as $p): ?>
                        <option value="<?= (int) $p['perl_ide'] ?>"><?= esc(trim($p['per_paterno'] . ' ' . $p['per_materno'] . ' ' . $p['per_nombre']) . ' · ' . $p['per_numero_documento']) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (!$companeros): ?><small class="text-danger">No hay otros trabajadores activos con el mismo establecimiento y cargo.</small><?php endif; ?>
                <label class="form-label mt-2" for="ct-fecha-ace">Fecha del turno que entrega el compañero</label>
                <input id="ct-fecha-ace" type="date" class="form-control ct-fecha" data-lado="ace" required>
                <label class="form-label mt-2" for="ct-turno-ace">Turno programado del compañero</label>
                <select id="ct-turno-ace" class="form-select ct-turno" data-lado="ace" name="prog_ace_id" required disabled><option value="">Seleccione compañero y fecha</option></select>
                <input type="hidden" name="version_ace">
            </div>
            <div class="col-12 ct-resumen alert alert-info d-none" aria-live="polite"></div>
            <div class="col-md-7">
                <label class="form-label" for="ct-motivo">Motivo del cambio</label>
                <textarea id="ct-motivo" class="form-control" name="motivo" maxlength="2000" rows="2" required></textarea>
            </div>
            <div class="col-md-5">
                <label class="form-label" for="ct-sustento">Archivo de sustento</label>
                <input id="ct-sustento" class="form-control" name="sustento" type="file" accept=".pdf,.jpg,.jpeg,.png">
                <small class="text-muted">PDF, JPG o PNG. Máximo 10 MB.</small>
            </div>
            <div class="col-12"><div class="ct-error alert alert-danger d-none" role="alert"></div>
                <button class="btn btn-primary ct-guardar" type="submit" disabled>Registrar y aplicar cambio</button>
            </div>
        </div>
    </form>
    <hr>
    <div class="d-flex flex-wrap justify-content-between gap-2 align-items-center mb-3">
        <h6 class="mb-0">Historial de cambios — <?= esc($meses[$mes - 1]) ?> <?= $anio ?></h6>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm calendario-mes" aria-label="Mes del historial">
                <?php foreach ($meses as $i => $n): ?><option value="<?= sprintf('%02d', $i + 1) ?>" <?= $i + 1 === $mes ? 'selected' : '' ?>><?= esc($n) ?></option><?php endforeach; ?>
            </select>
            <select class="form-select form-select-sm calendario-anio" aria-label="Año del historial">
                <?php for ($a = max((int) date('Y') + 1, $anio); $a >= min((int) date('Y') - 3, $anio); $a--): ?><option value="<?= $a ?>" <?= $a === $anio ? 'selected' : '' ?>><?= $a ?></option><?php endfor; ?>
            </select>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-bordered align-middle">
            <thead class="table-light"><tr><th>Registro</th><th>Solicitante / turno entregado</th><th>Compañero / turno entregado</th><th>Motivo</th><th>Estado</th><th>Sustento</th><th>Acciones</th></tr></thead>
            <tbody>
                <?php foreach ($cambios as $c): ?>
                    <tr>
                        <td>#<?= (int) $c['rc_ide'] ?><br><small><?= esc($c['created_at']) ?></small></td>
                        <td><?= esc($c['sol_paterno'] . ' ' . $c['sol_materno'] . ' ' . $c['sol_nombre']) ?><br><strong><?= esc($c['rc_fecha_sol']) ?></strong><br><?= esc($c['sol_codigo']) ?> <?= esc(substr($c['sol_ingreso'] ?? '', 0, 5)) ?>&ndash;<?= esc(substr($c['sol_salida'] ?? '', 0, 5)) ?></td>
                        <td><?= esc($c['ace_paterno'] . ' ' . $c['ace_materno'] . ' ' . $c['ace_nombre']) ?><br><strong><?= esc($c['rc_fecha_ace']) ?></strong><br><?= esc($c['ace_codigo']) ?> <?= esc(substr($c['ace_ingreso'] ?? '', 0, 5)) ?>&ndash;<?= esc(substr($c['ace_salida'] ?? '', 0, 5)) ?></td>
                        <td style="max-width: 240px; overflow-wrap: anywhere;"><?= esc($c['rc_justificacion']) ?></td>
                        <td><span class="badge bg-info text-dark"><?= esc($c['rc_estado']) ?></span></td>
                        <td class="text-center">
                            <?php if (!empty($c['adjuntos'])): ?>
                                <button type="button" class="btn btn-xs btn-outline-info d-inline-flex align-items-center gap-1 py-0 px-2 ct-anexos" title="Ver anexos" aria-label="Ver archivos adjuntos">
                                    <iconify-icon icon="solar:paperclip-linear"></iconify-icon><span class="fw-bold"><?= count($c['adjuntos']) ?></span>
                                </button>
                                <div class="ct-lista-anexos d-none">
                                    <div class="list-group list-group-flush small text-start">
                                        <?php foreach ($c['adjuntos'] as $adj): ?>
                                            <a class="list-group-item list-group-item-action d-flex align-items-center gap-2" target="_blank" rel="noopener" href="<?= base_url('asistencia/adjuntos/ver/' . (int) $adj['adj_ide']) ?>" title="Abrir archivo">
                                                <iconify-icon icon="<?= str_contains($adj['adj_mime_type'] ?? '', 'pdf') ? 'solar:file-text-bold' : 'solar:gallery-bold' ?>" class="text-primary fs-4"></iconify-icon>
                                                <span class="text-break flex-grow-1"><?= esc($adj['adj_nombre_original']) ?></span>
                                                <iconify-icon icon="solar:eye-bold" class="text-primary"></iconify-icon>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php else: ?><span class="text-muted small">—</span><?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($c['rc_estado'] === 'APLICADO'): ?>
                                <button type="button" class="btn btn-outline-danger btn-sm ct-eliminar" data-cambio="<?= (int) $c['rc_ide'] ?>" title="Eliminar cambio y restablecer ambos turnos" aria-label="Eliminar cambio y restablecer ambos turnos">
                                    <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon>
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$cambios): ?><tr><td colspan="7" class="text-center text-muted">No hay cambios de turno registrados en este periodo.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
