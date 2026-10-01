<!doctype html><html lang="es"><head><meta charset="utf-8"><title>Reporte mensual de asistencia</title>
<style>
@page { size: A4 landscape; margin: 28px 25px 35px; }
body { font-family: 'DejaVu Sans', sans-serif; font-size: 9px; color: #172b42; }
h1 { font-size: 17px; } h2 { font-size: 12px; } p { line-height: 1.4; }
table { border-collapse: collapse; width: 100%; table-layout: fixed; }
th, td { border: 1px solid #b7c4d2; padding: 4px; vertical-align: top; overflow-wrap: break-word; word-wrap: break-word; }
th { background: #24466b; color: white; font-size: 8px; } thead { display: table-header-group; } tr { page-break-inside: avoid; }
.resumen th:first-child { width: 17%; } .resumen th:nth-child(2) { width: 19%; } .resumen th:nth-child(3) { width: 10%; }
.numero { text-align: right; } .detalle { page-break-before: always; } .diario th:first-child { width: 9%; } .diario th:nth-child(2), .diario th:nth-child(3) { width: 24%; }
.nota { color: #526173; font-size: 8px; } @media print { .acciones { display: none; } }
</style></head><body>
<?php if (! $pdf): ?><div class="acciones"><button onclick="window.print()">Imprimir / guardar PDF</button></div><?php endif ?>
<h1>Reporte mensual de asistencia</h1>
<p><strong><?= esc($reporte['inicio']) ?> al <?= esc($reporte['fin']) ?></strong> · <?= count($reporte['filas']) ?> trabajadores<br><?= esc($reporte['ambito']) ?><br>Generado: <?= esc($reporte['generado']) ?></p>
<p class="nota"><?= esc($reporte['nota']) ?></p>
<?= view('Modules\Asistencia\Views\reportes\resumen', ['reporte' => $reporte]) ?>
<?php if ($reporte['filtros']['detalle']): foreach ($reporte['filas'] as $persona): ?><section class="detalle"><h2><?= esc($persona['per_numero_documento'] . ' — ' . $persona['trabajador']) ?></h2><p><?= esc($persona['est_nombre'] . ' / ' . ($persona['ofi_nombre'] ?? 'Sin oficina')) ?></p><?= view('Modules\Asistencia\Views\reportes\diario', ['persona' => $persona]) ?></section><?php endforeach; endif ?>
</body></html>
