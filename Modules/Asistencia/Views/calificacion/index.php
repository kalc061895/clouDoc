<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Calificación mensual<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid py-3">
<h1 class="h3">Calificación mensual</h1>
<p>Calcule el periodo cerrado, revise sus observaciones y cierre la calificación. Cada cálculo conserva una versión independiente. El ámbito usa la ubicación actual del personal y exige autorización sobre todas sus oficinas.</p>
<?php foreach (['error'=>'danger','success'=>'success'] as $key=>$color): if (session()->getFlashdata($key)): ?><div role="alert" class="alert alert-<?= $color ?>"><?= esc(session()->getFlashdata($key)) ?></div><?php endif; endforeach ?>
<nav class="nav gap-3 mb-3"><a href="#generar">Generar calificación</a><a href="#historial">Historial</a><a href="#criterios">Criterios</a></nav>
<h2 id="generar" class="h5">Nueva calificación</h2>
<?php if (!$periodos): ?><div class="alert alert-info">Todavía no hay periodos cerrados. Cierre primero el periodo de registro de papeletas en <a href="<?= site_url('asistencia/gestordb/periodos') ?>">Periodos</a>.</div><?php endif ?>
<form method="post" action="<?= site_url('asistencia/calificacion/generar') ?>" class="card card-body mb-4">
<?= csrf_field() ?><div class="row g-3">
<label class="col-md-4">Periodo cerrado<select name="periodo_id" required class="form-select"><option value="">Seleccione...</option><?php foreach ($periodos as $p): ?><option value="<?= (int)$p['per_ide'] ?>"><?= esc($p['per_nombre'].' / '.$p['per_fecha_inicio'].' al '.$p['per_fecha_fin']) ?></option><?php endforeach ?></select></label>
<label class="col-md-3">Nivel<select name="ambito_tipo" id="cal-nivel" class="form-select"><option value="establecimiento">Establecimiento / hospital</option><option value="microred">Microred</option><option value="red">Red</option></select></label>
<label class="col-md-5">Dependencia<select name="ambito_id" id="cal-ambito" required class="form-select"><option value="">Seleccione...</option><?php foreach (['establecimiento'=>['est_ide','est_nombre'],'microred'=>['mic_ide','mic_nombre'],'red'=>['red_ide','red_nombre']] as $tipo=>[$key,$name]): foreach ($ambitos[$key] as $a): ?><option data-nivel="<?= $tipo ?>" value="<?= (int)$a[$key] ?>" <?= $tipo!=='establecimiento'?'hidden disabled':'' ?>><?= esc($a[$name]) ?></option><?php endforeach; endforeach ?></select></label>
<label class="col-md-4">ID de trabajador (opcional)<input type="number" min="1" name="trabajador_id" class="form-control"><small>Vacío: todo el personal del ámbito. Consulte el ID en Personal.</small></label>
</div><button class="btn btn-primary mt-3">Calificar automáticamente</button>
</form>
<h2 id="historial" class="h5">Historial de calificaciones y PDF</h2>
<div class="table-responsive"><table class="table"><thead><tr><th>Versión</th><th>Periodo</th><th>Ámbito</th><th>Estado</th><th>Generada</th><th>Detalle y PDF</th></tr></thead><tbody><?php foreach ($historial as $c): ?><tr><td>#<?= (int)$c['id'] ?></td><td><?= esc(json_decode($c['fuentes'],true)['inicio'].' al '.json_decode($c['fuentes'],true)['fin']) ?></td><td><?= esc($c['ambito_tipo'].' #'.$c['ambito_id']) ?></td><td><?= esc($c['estado']) ?></td><td><?= esc($c['created_at']) ?></td><td><a href="<?= site_url('asistencia/calificacion/'.$c['id']) ?>">Revisar / ver PDF</a></td></tr><?php endforeach ?></tbody></table></div>
<h2 id="criterios" class="h5">Criterios generales</h2><p>Se copian al generar cada versión. No modifican cálculos anteriores. Las guardias se clasifican por la fecha de inicio del turno; los feriados provienen del catálogo.</p>
<form method="post" action="<?= site_url('asistencia/calificacion/criterios') ?>" class="card card-body"><?= csrf_field() ?>
<?php foreach (['tolerancia_minutos'=>'Tolerancia de ingreso (minutos)','tardanzas_excesivas'=>'Cantidad de tardanzas para observar','ventana_minutos'=>'Margen para asociar marcaciones (minutos)','codigos_guardia'=>'Códigos de guardia, separados por comas'] as $k=>$label): ?><label class="mb-2"><?= esc($label) ?><input class="form-control" name="<?= $k ?>" value="<?= esc($criterios[$k],'attr') ?>" <?= !$admin?'disabled':'' ?> required></label><?php endforeach ?>
<?php if ($admin): ?><button class="btn btn-secondary">Guardar criterios</button><?php endif ?></form>
</div>
<script>document.getElementById('cal-nivel').addEventListener('change',function(){const s=document.getElementById('cal-ambito');s.value='';s.querySelectorAll('[data-nivel]').forEach(o=>{o.hidden=o.dataset.nivel!==this.value;o.disabled=o.hidden;});});</script>
<?= $this->endSection() ?>
