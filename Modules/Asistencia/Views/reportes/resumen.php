<table class="table table-bordered table-sm resumen">
<thead><tr><th>Documento / trabajador</th><th>DIRESA / establecimiento / oficina</th><th>Turnos por código</th><th>Turnos prog.</th><th>Horas prog.</th><th>Marcaciones</th><th>Días con marca</th><th>Días licencia</th><th>Permisos</th><th>Horas permiso</th><th>Días vacaciones</th></tr></thead>
<tbody><?php foreach ($reporte['filas'] as $p): ?><tr>
<td><?= esc($p['per_numero_documento']) ?><br><?= esc($p['trabajador']) ?></td>
<td><?= esc($p['dir_nombre'] ?? '') ?><br><?= esc($p['est_nombre']) ?><br><?= esc($p['ofi_nombre'] ?? 'Sin oficina') ?></td>
<td><?= esc($p['turnos_resumen'] ?: '—') ?></td>
<?php foreach (['turnos','horas','marcaciones','dias_marcados','dias_licencia','permisos','horas_permiso','dias_vacacion'] as $key): ?><td class="numero"><?= esc((string) $p[$key]) ?></td><?php endforeach ?>
</tr><?php endforeach ?>
<?php if (! $reporte['filas']): ?><tr><td colspan="11">No hay trabajadores para los filtros seleccionados.</td></tr><?php endif ?></tbody>
</table>
