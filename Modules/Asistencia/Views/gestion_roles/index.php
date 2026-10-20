<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Gestión de roles<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid py-3">
<h1 class="h3">Gestión de roles</h1>
<p>Periodos, responsables y reglas de programación. Horario de referencia: Lima. El inicio de aprobación bloquea la edición; el cierre deja el periodo en consulta.</p>
<a class="btn btn-outline-primary mb-3" href="<?= site_url('asistencia/programacion') ?>">Programar turnos</a>
<?php foreach (['error'=>'danger','success'=>'success'] as $key=>$color): if (session()->getFlashdata($key)): ?>
<div class="alert alert-<?= $color ?>"><?= esc(session()->getFlashdata($key)) ?></div>
<?php endif; endforeach ?>
<h2 class="h5">Periodos</h2>
<div class="table-responsive"><table class="table table-bordered"><thead><tr><th>Mes / establecimiento</th><th>Apertura</th><th>Aprobación desde</th><th>Cierre</th><th>Etapa</th><th>Reglas fijadas</th></tr></thead><tbody>
<?php foreach ($periodos as $p): ?><tr><td><?= esc($p['mes']) ?> / <?= esc($p['est_id']) ?></td><td><?= esc($p['apertura']) ?></td><td><?= esc($p['aprobacion']) ?></td><td><?= esc($p['cierre']) ?></td><td><?= esc($p['etapa']) ?></td><td><details><summary>Ver reglas</summary><ul><?php foreach (json_decode($p['reglas'],true) as $r): ?><li><?= esc($r['nombre']) ?>: <?= esc($r['tipo']) ?> <?= esc($r['limite']??implode(', ',$r['turnos']??[])) ?></li><?php endforeach ?></ul></details></td></tr><?php endforeach ?>
</tbody></table></div>
<h2 class="h5">Roles por oficina</h2>
<p>Se crean al guardar la primera asignación. Presente el rol dentro del plazo de programación. Las observaciones permiten corregirlo mientras ese plazo siga vigente.</p>
<table class="table"><thead><tr><th>Mes</th><th>Oficina</th><th>Estado</th><th>Acción</th></tr></thead><tbody>
<?php foreach ($roles as $r): ?><tr><td><?= esc($r['mes']) ?></td><td><?= esc($r['ofi_nombre']) ?></td><td><?= esc($r['estado']) ?></td><td>
<?php if ($r['estado']!=='APROBADO'): ?>
<form method="post" action="<?= site_url('asistencia/gestion-roles/estado') ?>" class="d-flex gap-2"><?= csrf_field() ?><input type="hidden" name="rol_id" value="<?= (int)$r['id'] ?>"><select name="accion" class="form-select"><option value="PRESENTAR">Presentar</option><option value="OBSERVAR">Observar</option><option value="APROBAR">Aprobar</option></select><input name="motivo" class="form-control" placeholder="Motivo de observación" maxlength="1000"><button class="btn btn-primary">Aplicar</button></form>
<?php endif ?></td></tr><?php endforeach ?></tbody></table>
<?php if ($admin): ?>
<hr><h2 class="h5">Crear periodo</h2>
<p>Las reglas quedan fijadas al crear el periodo. El límite de aprobación es exclusivo: desde esa hora ya no se permiten cambios.</p>
<form method="post" action="<?= site_url('asistencia/gestion-roles/periodo') ?>" id="periodo-form" class="card card-body mb-4">
<?= csrf_field() ?>
<div class="row g-3"><label class="col-md-3">Establecimiento<select name="est_id" required class="form-select"><?php foreach ($establecimientos as $e): ?><option value="<?= (int)$e['est_ide'] ?>"><?= esc($e['est_nombre']) ?></option><?php endforeach ?></select></label><label class="col-md-2">Mes trabajado<input type="month" name="mes" required class="form-control"></label>
<?php foreach (['apertura'=>'Apertura','aprobacion'=>'Aprobación desde','cierre'=>'Cierre'] as $k=>$label): ?><label class="col-md-2"><?= $label ?><input type="datetime-local" name="<?= $k ?>" required class="form-control"></label><?php endforeach ?></div>
<p class="mt-3">Reglas de bloqueo. Los ámbitos vacíos aplican a todo el personal. Oficina y régimen, cuando se indican juntos, deben coincidir ambos.</p>
<div id="reglas"></div><button type="button" class="btn btn-outline-secondary my-2" id="agregar-regla">Agregar regla</button><input type="hidden" name="reglas" id="reglas-json"><button class="btn btn-primary">Crear periodo y fijar reglas</button>
</form>
<template id="regla-template"><div class="regla border rounded p-2 mb-2 row g-2">
<label class="col-md-3">Nombre<input data-key="nombre" required class="form-control"></label>
<label class="col-md-3">Validación<select data-key="tipo" class="form-select"><option value="MAX_TURNOS">Máximo turnos por mes</option><option value="TURNOS_PERMITIDOS">Turnos permitidos</option><option value="MAX_HORAS_MES">Máximo horas por mes</option><option value="MAX_CONTINUAS">Máximo horas continuas</option></select></label>
<label class="col-md-2">Límite<input data-key="limite" type="number" min="0" step="0.5" value="4" class="form-control"></label><label class="col-md-3">Códigos separados por coma<input data-key="turnos" placeholder="GD, GN" class="form-control"></label>
<label class="col-md-4">Oficina<select data-key="oficina_id" class="form-select"><option value="">Todas</option><?php foreach ($oficinas as $o): ?><option value="<?= (int)$o['ofi_ide'] ?>"><?= esc($o['ofi_nombre']) ?></option><?php endforeach ?></select></label>
<label class="col-md-2">ID régimen contractual<input data-key="regimen_id" type="number" min="1" class="form-control"></label>
<label class="col-md-3">Modalidad<select data-key="modalidad" class="form-select"><option value="">Todas</option><option>ASISTENCIAL</option><option>ADMINISTRATIVO</option></select></label><div class="col-md-2"><button type="button" class="btn btn-outline-danger quitar">Quitar regla</button></div></div></template>
<h2 class="h5">Asignar responsable</h2>
<form method="post" action="<?= site_url('asistencia/gestion-roles/responsable') ?>" class="card card-body mb-4"><?= csrf_field() ?><div class="row g-3"><label class="col-md-2">ID usuario<input name="usuario_id" type="number" min="1" required class="form-control"></label><label class="col-md-4">Oficina y descendientes<select name="oficina_id" class="form-select"><?php foreach ($oficinas as $o): ?><option value="<?= (int)$o['ofi_ide'] ?>"><?= esc($o['ofi_nombre']) ?></option><?php endforeach ?></select></label><label class="col-md-2">Desde<input name="desde" type="date" required class="form-control"></label><label class="col-md-2">Hasta<input name="hasta" type="date" required class="form-control"></label><label class="col-md-2"><input name="aprobar" type="checkbox" value="1"> Puede aprobar</label></div><button class="btn btn-primary mt-3">Asignar responsable</button></form>
<table class="table"><thead><tr><th>Usuario</th><th>Oficina</th><th>Vigencia</th><th>Puede aprobar</th></tr></thead><tbody><?php foreach ($responsables as $r): ?><tr><td><?= (int)$r['usuario_id'] ?></td><td><?= (int)$r['oficina_id'] ?></td><td><?= esc($r['desde'].' al '.$r['hasta']) ?></td><td><?= $r['aprobar']?'Sí':'No' ?></td></tr><?php endforeach ?></tbody></table>
<h2 class="h5">Clasificar turnos</h2><p>La modalidad del trabajador se administra en el catálogo de cargos. Aquí se clasifica únicamente la modalidad de los turnos.</p>
<form method="post" action="<?= site_url('asistencia/gestion-roles/modalidad') ?>" class="d-flex gap-2 mb-4"><?= csrf_field() ?><input type="hidden" name="entidad" value="turno"><input type="number" name="id" min="1" required placeholder="ID del registro" class="form-control"><select name="modalidad" class="form-select"><option>ASISTENCIAL</option><option>ADMINISTRATIVO</option></select><button class="btn btn-primary">Clasificar</button></form>
<script>
(() => {
const box=document.getElementById('reglas');
document.getElementById('agregar-regla').addEventListener('click',()=>box.append(document.getElementById('regla-template').content.cloneNode(true)));
box.addEventListener('click',e=>{if(e.target.classList.contains('quitar'))e.target.closest('.regla').remove();});
document.getElementById('periodo-form').addEventListener('submit',()=>{
const reglas=[...box.querySelectorAll('.regla')].map(row=>{const r={}; row.querySelectorAll('[data-key]').forEach(el=>{if(el.value!=='')r[el.dataset.key]=el.value;});r.turnos=(r.turnos||'').split(',').map(x=>x.trim().toUpperCase()).filter(Boolean);return r;});
document.getElementById('reglas-json').value=JSON.stringify(reglas);
});
})();
</script>
<?php endif ?>
</div>
<?= $this->endSection() ?>
