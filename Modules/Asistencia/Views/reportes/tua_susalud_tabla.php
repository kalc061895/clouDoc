<?php $personal = $reporte['personal']; ?>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="tablaTua" class="table table-sm table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th colspan="2" class="text-center">IPRESS</th>
                        <th colspan="9" class="text-center">PROFESIONAL EN SALUD</th><?php for ($d = 1; $d <= 31; $d++): ?><th colspan="2" class="text-center">Día <?= $d ?></th><?php endfor ?>
                    </tr>
                    <tr><?php foreach (['Código Único', 'Nombre', 'RED', 'Tipo Documento', 'Número Documento', 'Profesión', 'Número de Colegiatura', 'Apellidos', 'Nombres', 'Especialidad', 'Servicio'] as $h): ?><th><?= esc($h) ?></th><?php endforeach ?><?php for ($d = 1; $d <= 31; $d++): ?><th>Ingreso</th>
                            <th>Salida</th><?php endfor ?>
                    </tr>
                </thead>
                <tbody><?php foreach ($personal as $p): ?><tr><?php foreach ([$p['est_ipress'] ?? '', $p['est_nombre'] ?? '', $p['red_nombre'] ?? '', $p['tdi_abreviatura'] ?? '', $p['per_numero_documento'] ?? '', $p['profesion'] ?? '', $p['colegiatura'], $p['apellidos'], $p['per_nombre'] ?? '', $p['especialidad'], $p['servicio']] as $v): ?><td><?= esc($v) ?></td><?php endforeach ?><?php for ($d = 1; $d <= 31; $d++): ?><td class="text-nowrap"><?= esc(implode(' / ', $p['dias'][$d]['in'] ?? [])) ?></td>
                                <td class="text-nowrap"><?= esc(implode(' / ', $p['dias'][$d]['out'] ?? [])) ?></td><?php endfor ?>
                        </tr><?php endforeach ?></tbody>
            </table>
        </div>
        <div class="text-muted small mt-2">Personal asistencial: <?= count($personal) ?> · Período: <?= esc($reporte['mes']) ?> · Alcance: <?= esc($reporte['periodo']['nivel']) ?></div>
    </div>
</div>