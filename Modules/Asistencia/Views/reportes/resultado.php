<div class="card card-body">
<h4>Periodo <?= esc($reporte['inicio']) ?> al <?= esc($reporte['fin']) ?></h4>
<p><?= esc($reporte['ambito']) ?> · <?= count($reporte['filas']) ?> trabajadores · Consulta: <?= esc($reporte['generado']) ?></p>
<p class="text-muted small"><?= esc($reporte['nota']) ?></p>
<div class="table-responsive"><?= view('Modules\Asistencia\Views\reportes\resumen', ['reporte' => $reporte]) ?></div>
<h4 class="mt-3">Detalle diario por trabajador</h4>
<?php foreach ($reporte['filas'] as $persona): ?><details class="mb-2 border rounded p-2"><summary><?= esc($persona['per_numero_documento'] . ' — ' . $persona['trabajador']) ?></summary><div class="table-responsive mt-2"><?= view('Modules\Asistencia\Views\reportes\diario', ['persona' => $persona]) ?></div></details><?php endforeach ?>
</div>
