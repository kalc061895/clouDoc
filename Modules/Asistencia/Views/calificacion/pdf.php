<!doctype html><html lang="es"><head><meta charset="utf-8"><style>@page{margin:30px}body{font-family:"DejaVu Sans",sans-serif;font-size:9px;color:#172b4d}h1{font-size:18px}table{border-collapse:collapse;width:100%;margin:15px 0}th,td{border:1px solid #c8d1df;padding:6px;text-align:left}th{background:#eaf0f7}thead{display:table-header-group}tr{page-break-inside:avoid}</style></head><body>
<h1>Calificación mensual #<?= (int)$c['id'] ?></h1>
<?php $f=json_decode($c['fuentes'],true); ?>
<p>Periodo: <?= esc($f['inicio'].' al '.$f['fin']) ?> | Estado: <?= esc($c['estado']) ?> | Ámbito: <?= esc($c['ambito_tipo'].' #'.$c['ambito_id']) ?></p>
<?= view('Modules\Asistencia\Views\calificacion\tabla',['c'=>$c]) ?>
<p>Horas de presencia dentro del turno programado. Licencias y vacaciones se muestran por separado. Guardias clasificadas por fecha de inicio. No constituye cálculo de descuentos ni remuneraciones. Documento sin firma digital.</p>
<h2>Observaciones revisadas</h2><?php foreach (json_decode($c['resultado'],true) as $r): if (!$r['observaciones']) continue; ?><p><strong><?= esc($r['personal']['per_numero_documento']) ?></strong>: <?php foreach ($r['observaciones'] as $o): ?><?= esc($o['fecha'].' '.str_replace('_',' ',$o['codigo'])) ?>; <?php endforeach ?></p><?php endforeach ?>
</body></html>
