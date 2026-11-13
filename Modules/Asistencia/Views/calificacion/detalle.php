<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Revisión de calificación<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid py-3"><a href="<?= site_url('asistencia/calificacion') ?>">Volver a calificaciones</a>
    <h1 class="h3">Calificación #<?= (int)$c['id'] ?> - <?= esc($c['estado']) ?></h1>
    <?php foreach (['error' => 'danger', 'success' => 'success'] as $key => $color): if (session()->getFlashdata($key)): ?><div role="alert" class="alert alert-<?= $color ?>"><?= esc(session()->getFlashdata($key)) ?></div><?php endif;
                                                                                                                                                                                                                endforeach ?>
    <p>Las horas asistidas se limitan al horario programado. Las papeletas APROBADAS justifican sus intervalos. Use la clasificación manual para resolver tipos ambiguos o excluir marcaciones y recalcular. Revisar acepta los resultados con sustento.</p>
    <div class="table-responsive"><?= view('Modules\Asistencia\Views\calificacion\tabla', ['c' => $c]) ?></div>
    <h2 class="h5">Observaciones y detalle diario</h2>
    <p>La identificación inicial de ingresos y salidas se aplica al cálculo cuando la secuencia es coherente y no hay conflictos. Los casos ambiguos permanecen para revisión. Puede corregir los tipos o excluir marcas en «Modificar identificación de marcaciones» y recalcular. Las pausas sin horario definido no se consideran refrigerio confirmado.</p>
    <p>El tipo indica ENTRADA o SALIDA. El motivo original muestra el sustento registrado en la marcación. Las clasificaciones confirmadas se conservan en esta calificación con su usuario y sustento.</p>
    <?php $fuentesRevision = json_decode($c['fuentes'], true);
    $editable = !in_array($c['estado'], ['CERRADA', 'ANULADA'], true); ?>
    <?php foreach (json_decode($c['resultado'], true) as $r): ?>
        <details class="card card-body mb-2">
            <summary><?= esc($r['personal']['per_numero_documento'] . ' - ' . $r['personal']['per_paterno'] . ' ' . ($r['personal']['per_nombre'] ?? '')) ?> · <?= count($r['observaciones']) ?> observaciones</summary>
            <ul><?php foreach ($r['observaciones'] as $o): ?><li><?= esc($o['fecha'] . ' / ' . str_replace('_', ' ', $o['codigo'])) ?></li><?php endforeach ?></ul>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Turno</th>
                            <th>Presencia entre marcas (min)</th>
                            <th>Minutos asistidos dentro del turno</th>
                            <th>Tardanza</th>
                            <th>Justificado</th>
                        </tr>
                    </thead>
                    <tbody><?php foreach ($r['detalle'] as $d): ?><tr>
                                <td><?= esc($d['fecha']) ?></td>
                                <td><?= esc($d['turno']) ?></td>
                                <td><?= isset($d['minutos_presencia']) ? (int)$d['minutos_presencia'] : 'Sin determinar' ?></td>
                                <td><?= (int)$d['minutos_asistidos'] ?></td>
                                <td><?= (int)$d['minutos_tardanza'] ?></td>
                                <td><?= $d['justificado'] ? 'Sí' : 'No' ?></td>
                            </tr><?php endforeach ?></tbody>
                </table>
            </div>
            <?php $hayPropuestas = false; ?>
            <?php foreach ($r['detalle'] as $d): ?>
                <?php if (!isset($d['clasificacion'])): ?>
                    <p>Genere una nueva versión para obtener propuestas de clasificación para este turno.</p>
                    <?php continue; ?>
                <?php endif ?>
                <?php $clasificacion = $d['clasificacion'];
                $hayPropuestas = $hayPropuestas || $clasificacion['proponible']; ?>
                <?php if (!$clasificacion['marcaciones']) continue; ?>
                <h3 class="h6 mt-3"><?= esc($d['turno'] . ' · ' . $d['horario']) ?></h3>
                <?php if (!empty($clasificacion['automatica'])): ?><p class="text-success">Identificación aplicada automáticamente. Puede modificarla y recalcular.</p><?php endif ?>
                <?php if (!empty($clasificacion['razon_automatica'])): ?><p class="text-warning"><?= esc($clasificacion['razon_automatica']) ?></p><?php endif ?>
                <?php if (!empty($clasificacion['marcaciones_agrupadas'])): ?>
                    <details class="mb-2">
                        <summary><?= count($clasificacion['marcaciones_agrupadas']) ?> marcaciones adicionales agrupadas (conservadas)</summary>
                        <p>Para este turno ordinario sin refrigerio se usa el primer ingreso y la última salida. Las siguientes marcas no forman intervalos adicionales.</p>
                        <ul><?php foreach ($clasificacion['marcaciones_agrupadas'] as $agrupada): ?>
                                <li><?= esc($agrupada['asi_fecha_hora'] . ' · Grupo ' . $agrupada['grupo'] . ' · Marca utilizada: ' . $agrupada['hora_elegida']) ?></li>
                            <?php endforeach ?>
                        </ul>
                    </details>
                <?php endif ?>
                <?php if ($clasificacion['razon']): ?><p class="text-warning"><?= esc($clasificacion['razon']) ?> Revise las marcaciones de origen.</p><?php endif ?>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Fecha y hora</th>
                                <th>Tipo original</th>
                                <th>Motivo original</th>
                                <th>Tipo propuesto / confirmado</th>
                                <th>Evento posible</th>
                                <th>Revisión</th>
                            </tr>
                        </thead>
                        <tbody><?php foreach ($clasificacion['marcaciones'] as $marca): ?>
                                <tr>
                                    <td><?= esc($marca['fecha_hora']) ?></td>
                                    <td><?= esc($marca['tipo_original'] ?: 'Sin identificar') ?></td>
                                    <td><?= esc($marca['motivo_original'] ?: 'Sin motivo registrado') ?></td>
                                    <td><?= esc($marca['confirmada']['tipo'] ?? $marca['automatica']['tipo'] ?? $marca['tipo_propuesto'] ?? 'Por resolver') ?></td>
                                    <td><?= esc($marca['confirmada']['evento'] ?? $marca['automatica']['evento'] ?? $marca['evento']) ?></td>
                                    <td><?php if ($marca['confirmada']): ?>
                                            Confirmada por usuario <?= (int)$marca['confirmada']['usuario_id'] ?> · <?= esc($marca['confirmada']['fecha']) ?><br><?= esc($marca['confirmada']['sustento']) ?>
                                            <?php elseif (!empty($marca['automatica'])): ?>Identificación automática
                                        <?php elseif ($marca['pendiente']): ?>
                                            <?= $clasificacion['proponible'] ? 'Propuesta pendiente' : 'Revisión manual' ?>
                                            <?php else: ?>Tipo registrado<?php endif ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach ?>
            <?php if ($hayPropuestas && !in_array($c['estado'], ['CERRADA', 'ANULADA'], true)): ?>
                <form method="post" action="<?= site_url('asistencia/calificacion/' . $c['id'] . '/clasificar') ?>" class="my-3">
                    <?= csrf_field() ?>
                    <input type="hidden" name="personal_id" value="<?= (int)$r['personal']['perl_ide'] ?>">
                    <label class="d-block">Sustento de clasificación<textarea name="motivo" required minlength="10" maxlength="2000" class="form-control"></textarea></label>
                    <button class="btn btn-outline-primary mt-2">Confirmar propuestas de este trabajador y recalcular</button>
                </form>
            <?php endif ?>
            <?php
            $automaticas = [];
            foreach ($r['detalle'] as $diaIdentificado) {
                foreach ($diaIdentificado['clasificacion']['marcaciones'] ?? [] as $marcaIdentificada) {
                    if (!empty($marcaIdentificada['automatica'])) $automaticas[$marcaIdentificada['asi_ide']] = $marcaIdentificada['automatica'];
                }
            }
            $personalId = (int)$r['personal']['perl_ide'];
            $marcasRevision = array_values(array_filter($fuentesRevision['marcas'], static fn($m) => (int)$m['asi_perl_ide'] === $personalId));
            usort($marcasRevision, static fn($a, $b) => strcmp($a['asi_fecha_hora'], $b['asi_fecha_hora']));
            $turnosRevision = array_filter($fuentesRevision['turnos'], static fn($t) => (int)$t['prog_perl_ide'] === $personalId);
            ?>
            <?php if ($marcasRevision): ?>
                <details class="mt-3">
                    <summary>Modificar identificación de marcaciones</summary>
                    <p>Seleccione ingreso, salida o no tomar en cuenta. Cuando una marca coincida con varios turnos, seleccione también el turno correspondiente. Guarde las selecciones con un sustento para recalcular.</p>
                    <form method="post" action="<?= site_url('asistencia/calificacion/' . $c['id'] . '/clasificar-manual') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="personal_id" value="<?= $personalId ?>">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Fecha y hora</th>
                                        <th>Tipo original</th>
                                        <th>Clasificación aplicada</th><?php if ($editable): ?><th>Selección manual</th>
                                            <th>Turno</th><?php endif ?>
                                    </tr>
                                </thead>
                                <tbody><?php foreach ($marcasRevision as $marca): ?>
                                        <?php $marcaId = (int)$marca['asi_ide'];
                                            $confirmada = $r['clasificaciones_confirmadas'][$marcaId] ?? null; ?>
                                        <tr>
                                            <td><?= esc($marca['asi_fecha_hora']) ?></td>
                                            <td><?= esc(($marca['asi_tipo'] ?? '') ?: 'Sin identificar') ?></td>
                                            <td><?= esc($confirmada['tipo'] ?? $automaticas[$marcaId]['tipo'] ?? (($marca['asi_tipo'] ?? '') ?: 'Por determinar')) ?>
                                                <?php if ($confirmada): ?><br><small><?= esc($confirmada['sustento']) ?> · Usuario <?= (int)$confirmada['usuario_id'] ?> · <?= esc($confirmada['fecha']) ?></small><?php endif ?>
                                            </td>
                                            <?php if ($editable): ?>
                                                <td><select name="marcaciones[<?= $marcaId ?>][tipo]" class="form-select" aria-label="Clasificar marcación <?= $marcaId ?>">
                                                        <option value="">Conservar clasificación actual</option>
                                                        <option value="ENTRADA">Ingreso</option>
                                                        <option value="SALIDA">Salida</option>
                                                        <option value="IGNORAR">No tomar en cuenta</option>
                                                    </select></td>
                                                <td><select name="marcaciones[<?= $marcaId ?>][turno_id]" class="form-select" aria-label="Turno de marcación <?= $marcaId ?>">
                                                        <option value="0">Según horario programado</option>
                                                        <?php foreach ($turnosRevision as $turno): ?>
                                                            <?php if (empty($turno['prog_ide'])) continue; ?>
                                                            <option value="<?= (int)$turno['prog_ide'] ?>" <?= (int)($confirmada['turno_id'] ?? 0) === (int)$turno['prog_ide'] ? 'selected' : '' ?>><?= esc($turno['prog_fecha'] . ' · ' . $turno['tur_codigo'] . ' · ' . $turno['th_hora_ingreso'] . ' — ' . $turno['th_hora_salida']) ?></option>
                                                        <?php endforeach ?>
                                                    </select></td>
                                            <?php endif ?>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if ($editable): ?>
                            <label class="d-block">Sustento de la revisión manual<textarea name="motivo" required minlength="10" maxlength="2000" class="form-control"></textarea></label>
                            <button class="btn btn-primary mt-2">Guardar clasificación manual y recalcular</button>
                        <?php endif ?>
                    </form>
                </details>
            <?php endif ?>
        </details>
    <?php endforeach ?>
    <?php if (!in_array($c['estado'], ['CERRADA', 'ANULADA'], true)): ?>
        <form method="post" action="<?= site_url('asistencia/calificacion/' . $c['id'] . '/revisar') ?>" class="card card-body my-3"><?= csrf_field() ?><label>Sustento de revisión de todas las observaciones<textarea name="motivo" required minlength="10" maxlength="2000" class="form-control"></textarea></label><button class="btn btn-secondary mt-2">Confirmar revisión de los resultados</button></form>
        <form method="post" action="<?= site_url('asistencia/calificacion/' . $c['id'] . '/cerrar') ?>" class="card card-body my-3"><?= csrf_field() ?><label>Sustento de cierre<input name="motivo" required minlength="10" maxlength="2000" class="form-control"></label><button class="btn btn-primary mt-2" <?= $c['estado'] === 'OBSERVADA' ? 'disabled' : '' ?>>Cerrar definitivamente</button></form>
        <form method="post" action="<?= site_url('asistencia/calificacion/' . $c['id'] . '/anular') ?>" class="d-flex gap-2 mb-3"><?= csrf_field() ?><input name="motivo" required minlength="10" maxlength="2000" class="form-control" aria-label="Motivo de anulación" placeholder="Motivo de anulación"><button class="btn btn-outline-danger">Anular borrador</button></form>
    <?php endif ?>
    <h2 class="h5">Historial de PDF</h2>
    <?php if ($c['estado'] === 'CERRADA'): ?><form method="post" action="<?= site_url('asistencia/calificacion/' . $c['id'] . '/pdf') ?>"><?= csrf_field() ?><button class="btn btn-primary">Generar y conservar PDF</button></form><?php endif ?>
    <ul><?php foreach ($pdfs as $p): ?><li><a target="_blank" rel="noopener" href="<?= site_url('asistencia/calificacion/pdf/' . $p['id']) ?>">PDF #<?= (int)$p['id'] ?> - <?= esc($p['created_at']) ?></a> · SHA-256 <small><?= esc($p['sha256']) ?></small></li><?php endforeach ?></ul>
    <h2 class="h5">Historial de estados</h2>
    <ul><?php foreach ($eventos as $e): ?><li><?= esc($e['created_at'] . ' / usuario ' . $e['created_by'] . ' / ' . $e['accion'] . ' / ' . $e['detalle']) ?></li><?php endforeach ?></ul>
    <details>
        <summary>Criterios usados</summary>
        <pre><?= esc(json_encode(json_decode($c['reglas'], true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></pre>
    </details>
</div><?= $this->endSection() ?>