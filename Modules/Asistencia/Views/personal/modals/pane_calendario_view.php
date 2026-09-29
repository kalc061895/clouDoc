<?php
$periodo = new \DateTimeImmutable($fechaInicio);
$mesSeleccionado = (int) $periodo->format('n');
$anioSeleccionado = (int) $periodo->format('Y');
$meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
$filas = $tipo === 'asistencia' ? ['turnos' => 'Turnos', 'marcaciones' => 'Marcaciones', 'licencias' => 'Licencias', 'permisos' => 'Permisos'] : ['turnos' => 'Turnos', 'licencias' => 'Licencias', 'permisos' => 'Permisos'];
?>
<div class="calendario-personal" data-personal="<?= (int) $personalId ?>" data-tipo="<?= esc($tipo) ?>">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h6 class="fw-bold mb-1"><?= $tipo === 'asistencia' ? 'Asistencia' : 'Programación de turnos' ?> — <?= esc($meses[$mesSeleccionado - 1]) ?> <?= $anioSeleccionado ?></h6>
            <small class="text-muted"><?= esc($resumen) ?>. Periodo: <?= esc($periodo->format('m/Y')) ?></small>
        </div>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm calendario-mes" aria-label="Mes de consulta">
                <?php foreach ($meses as $i => $nombre): ?>
                    <option value="<?= sprintf('%02d', $i + 1) ?>" <?= $i + 1 === $mesSeleccionado ? 'selected' : '' ?>><?= esc($nombre) ?></option>
                <?php endforeach; ?>
            </select>
            <select class="form-select form-select-sm calendario-anio" aria-label="Año de consulta">
                <?php for ($anio = max((int) date('Y') + 1, $anioSeleccionado); $anio >= min((int) date('Y') - 3, $anioSeleccionado); $anio--): ?>
                    <option value="<?= $anio ?>" <?= $anio === $anioSeleccionado ? 'selected' : '' ?>><?= $anio ?></option>
                <?php endfor; ?>
            </select>
        </div>
    </div>
    <div class="table-responsive" tabindex="0" aria-label="Calendario mensual; desplácese horizontalmente para ver todos los días">
        <table class="table table-sm table-bordered align-middle text-center mb-0" style="width: max-content; min-width: 100%;">
            <thead class="table-light">
                <tr>
                    <th class="bg-light" style="position: sticky; left: 0; z-index: 1; min-width: 110px;">Registro</th>
                    <?php foreach ($dias as $dia): ?>
                        <th class="<?= $dia['es_finde'] ? 'table-warning' : '' ?>" style="min-width: 115px;">
                            <?= (int) substr($dia['fecha'], 8, 2) ?><br><small><?= esc($dia['dia_nombre']) ?></small>
                        </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody class="small">
                <?php foreach ($filas as $campo => $titulo): ?>
                    <tr>
                        <th class="bg-light" scope="row" style="position: sticky; left: 0; z-index: 1;"><?= esc($titulo) ?></th>
                        <?php foreach ($dias as $dia): ?>
                            <td class="<?= $dia['es_finde'] ? 'table-warning' : '' ?>">
                                <?php foreach ($dia[$campo] ?? [] as $item): ?>
                                    <div class="mb-1">
                                        <?php if ($campo === 'turnos'): ?>
                                            <span class="badge bg-primary" title="<?= esc($item['tur_nombre'], 'attr') ?>"><?= esc($item['tur_codigo']) ?></span>
                                            <div class="text-nowrap font-monospace"><?= esc(substr($item['th_hora_ingreso'], 0, 5)) ?>–<?= esc(substr($item['th_hora_salida'], 0, 5)) ?></div>
                                            <small><?= esc($item['estado'] ?? $item['prog_estado'] ?? '') ?></small>
                                        <?php elseif ($campo === 'marcaciones'): ?>
                                            <span class="badge bg-light text-dark border"><?= esc(date('H:i', strtotime($item['asi_fecha_hora']))) ?> <?= esc($item['asi_tipo'] ?? '') ?></span>
                                        <?php elseif ($campo === 'licencias'): ?>
                                            <span class="badge bg-info text-dark" title="<?= esc($item['lic_nombre'], 'attr') ?>"><?= esc($item['lic_abreviatura'] ?: $item['lic_nombre']) ?></span>
                                            <?php if ((string) $item['rl_estado'] !== '1'): ?><small class="d-block">Inactiva</small><?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark" title="<?= esc($item['pero_nombre'], 'attr') ?>"><?= esc($item['pero_abreviatura'] ?: $item['pero_nombre']) ?></span>
                                            <div class="text-nowrap"><?= esc(substr($item['rp_hora_salida'] ?? '', 0, 5)) ?>–<?= esc(substr($item['rp_hora_retorno'] ?? '', 0, 5)) ?></div>
                                            <small><?= esc((string) $item['rp_estado'] === '1' ? 'Activo' : ((string) $item['rp_estado'] === '0' ? 'Inactivo' : $item['rp_estado'])) ?></small>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                                <?php if (empty($dia[$campo])): ?><span class="text-muted">—</span><?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <small class="text-muted d-block mt-2">Deslice horizontalmente para consultar todos los días. Amarillo: fin de semana. Pase el cursor sobre un código para ver su nombre.</small>
</div>