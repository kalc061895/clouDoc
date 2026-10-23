<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Revisión de calificación<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid py-3"><a href="<?= site_url('asistencia/calificacion') ?>">Volver a calificaciones</a>
<h1 class="h3">Calificación #<?= (int)$c['id'] ?> - <?= esc($c['estado']) ?></h1>
<?php foreach (['error'=>'danger','success'=>'success'] as $key=>$color): if (session()->getFlashdata($key)): ?><div role="alert" class="alert alert-<?= $color ?>"><?= esc(session()->getFlashdata($key)) ?></div><?php endif; endforeach ?>
<p>Las horas asistidas se limitan al horario programado. Las licencias y vacaciones no se suman como presencia física. Las papeletas APROBADAS justifican sus intervalos; los demás estados requieren revisión. Los casos ambiguos no reciben horas automáticas. Revisar acepta los resultados con sustento; para corregir cifras, corrija el registro de origen y genere otra versión.</p>
<div class="table-responsive"><?= view('Modules\Asistencia\Views\calificacion\tabla',['c'=>$c]) ?></div>
<h2 class="h5">Observaciones y detalle diario</h2>
<?php foreach (json_decode($c['resultado'],true) as $r): ?><details class="card card-body mb-2"><summary><?= esc($r['personal']['per_numero_documento'].' - '.$r['personal']['per_paterno']) ?>: <?= count($r['observaciones']) ?> observaciones</summary><ul><?php foreach ($r['observaciones'] as $o): ?><li><?= esc($o['fecha'].' / '.str_replace('_',' ',$o['codigo'])) ?></li><?php endforeach ?></ul><table class="table"><thead><tr><th>Fecha</th><th>Turno</th><th>Minutos asistidos</th><th>Tardanza</th><th>Justificado</th></tr></thead><tbody><?php foreach ($r['detalle'] as $d): ?><tr><td><?= esc($d['fecha']) ?></td><td><?= esc($d['turno']) ?></td><td><?= (int)$d['minutos_asistidos'] ?></td><td><?= (int)$d['minutos_tardanza'] ?></td><td><?= $d['justificado']?'Sí':'No' ?></td></tr><?php endforeach ?></tbody></table></details><?php endforeach ?>
<?php if (!in_array($c['estado'],['CERRADA','ANULADA'],true)): ?>
<form method="post" action="<?= site_url('asistencia/calificacion/'.$c['id'].'/revisar') ?>" class="card card-body my-3"><?= csrf_field() ?><label>Sustento de revisión de todas las observaciones<textarea name="motivo" required minlength="10" maxlength="2000" class="form-control"></textarea></label><button class="btn btn-secondary mt-2">Confirmar revisión de los resultados</button></form>
<form method="post" action="<?= site_url('asistencia/calificacion/'.$c['id'].'/cerrar') ?>" class="card card-body my-3"><?= csrf_field() ?><label>Sustento de cierre<input name="motivo" required minlength="10" maxlength="2000" class="form-control"></label><button class="btn btn-primary mt-2" <?= $c['estado']==='OBSERVADA'?'disabled':'' ?>>Cerrar definitivamente</button></form>
<form method="post" action="<?= site_url('asistencia/calificacion/'.$c['id'].'/anular') ?>" class="d-flex gap-2 mb-3"><?= csrf_field() ?><input name="motivo" required minlength="10" maxlength="2000" class="form-control" aria-label="Motivo de anulación" placeholder="Motivo de anulación"><button class="btn btn-outline-danger">Anular borrador</button></form>
<?php endif ?>
<h2 class="h5">Historial de PDF</h2>
<?php if ($c['estado']==='CERRADA'): ?><form method="post" action="<?= site_url('asistencia/calificacion/'.$c['id'].'/pdf') ?>"><?= csrf_field() ?><button class="btn btn-primary">Generar y conservar PDF</button></form><?php endif ?>
<ul><?php foreach ($pdfs as $p): ?><li><a target="_blank" rel="noopener" href="<?= site_url('asistencia/calificacion/pdf/'.$p['id']) ?>">PDF #<?= (int)$p['id'] ?> - <?= esc($p['created_at']) ?></a> · SHA-256 <small><?= esc($p['sha256']) ?></small></li><?php endforeach ?></ul>
<h2 class="h5">Historial de estados</h2><ul><?php foreach ($eventos as $e): ?><li><?= esc($e['created_at'].' / usuario '.$e['created_by'].' / '.$e['accion'].' / '.$e['detalle']) ?></li><?php endforeach ?></ul>
<details><summary>Criterios usados</summary><pre><?= esc(json_encode(json_decode($c['reglas'],true),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)) ?></pre></details>
</div><?= $this->endSection() ?>
