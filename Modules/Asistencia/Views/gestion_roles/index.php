<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Gestión de roles<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid py-3">
<h1 class="h3">Gestión de roles</h1>
<p>Seguimiento de los roles de turnos por oficina. El jefe configurado en la oficina puede programar; la aprobación requiere autorización expresa. Horario de referencia: Lima.</p>
<?= view('Modules\Asistencia\Views\gestion_roles\navegacion', ['admin' => $admin]) ?>
<?php foreach (['error'=>'danger','success'=>'success'] as $key=>$color): if (session()->getFlashdata($key)): ?>
<div class="alert alert-<?= $color ?>"><?= esc(session()->getFlashdata($key)) ?></div>
<?php endif; endforeach ?>
<h2 class="h5">Periodos</h2>
<div class="table-responsive"><table class="table table-bordered"><thead><tr><th>Mes / establecimiento</th><th>Apertura</th><th>Aprobación desde</th><th>Cierre</th><th>Etapa</th><th>Reglas fijadas</th></tr></thead><tbody>
<?php foreach ($periodos as $p): ?><tr><td><?= esc($p['mes']) ?> / <?= esc($p['est_nombre']) ?></td><td><?= esc($p['apertura']) ?></td><td><?= esc($p['aprobacion']) ?></td><td><?= esc($p['cierre']) ?></td><td><?= esc($p['etapa']) ?></td><td><details><summary>Ver reglas</summary><ul><?php foreach (json_decode($p['reglas'],true) as $r): ?><li><?= esc($r['nombre']) ?>: <?= esc($r['tipo']) ?> <?= esc($r['limite']??implode(', ',$r['turnos']??[])) ?></li><?php endforeach ?></ul></details></td></tr><?php endforeach ?>
</tbody></table></div>
<h2 class="h5">Roles por oficina</h2>
<p>Se crean al guardar la primera asignación. Presente el rol dentro del plazo de programación. Las observaciones permiten corregirlo mientras ese plazo siga vigente.</p>
<table class="table"><thead><tr><th>Mes</th><th>Oficina</th><th>Estado</th><th>Acción</th></tr></thead><tbody>
<?php foreach ($roles as $r): ?><tr><td><?= esc($r['mes']) ?></td><td><?= esc($r['ofi_nombre']) ?></td><td><?= esc($r['estado']) ?></td><td>
<?php if ($r['estado']!=='APROBADO'): ?>
<form method="post" action="<?= site_url('asistencia/gestion-roles/estado') ?>" class="d-flex gap-2"><?= csrf_field() ?><input type="hidden" name="rol_id" value="<?= (int)$r['id'] ?>"><select name="accion" class="form-select"><option value="PRESENTAR">Presentar</option><option value="OBSERVAR">Observar</option><option value="APROBAR">Aprobar</option></select><input name="motivo" class="form-control" placeholder="Motivo de observación" maxlength="1000"><button class="btn btn-primary">Aplicar</button></form>
<?php endif ?></td></tr><?php endforeach ?></tbody></table>
<?php if ($admin): ?>
<h2 class="h5">Delegación temporal y aprobación</h2><p>El jefe se configura en el CRUD de oficinas. Use esta delegación para reemplazos temporales o para autorizar a un usuario a aprobar.</p>
<form method="post" action="<?= site_url('asistencia/gestion-roles/responsable') ?>" class="card card-body mb-4"><?= csrf_field() ?><div class="row g-3"><label class="col-md-4">Usuario<select name="usuario_id" required class="form-select"><option value="">Seleccione...</option><?php foreach ($usuarios as $u): ?><option value="<?= (int)$u['id'] ?>"><?= esc(trim(($u['nombres'] ?? '') . ' ' . ($u['paterno'] ?? '') . ' ' . ($u['materno'] ?? '')) ?: $u['username']) ?><?= !empty($u['dni']) ? esc(' — DNI: ' . $u['dni']) : '' ?></option><?php endforeach ?></select></label><label class="col-md-4">Oficina y descendientes<select name="oficina_id" class="form-select"><?php foreach ($oficinas as $o): ?><option value="<?= (int)$o['ofi_ide'] ?>"><?= esc($o['ofi_nombre']) ?></option><?php endforeach ?></select></label><label class="col-md-2">Desde<input name="desde" type="date" required class="form-control"></label><label class="col-md-2">Hasta<input name="hasta" type="date" required class="form-control"></label><label class="col-md-2"><input name="aprobar" type="checkbox" value="1"> Puede aprobar</label></div><button class="btn btn-primary mt-3">Asignar responsable</button></form>
<table class="table"><thead><tr><th>Usuario</th><th>Oficina</th><th>Vigencia</th><th>Puede aprobar</th></tr></thead><tbody><?php foreach ($responsables as $r): ?><tr><td><?= (int)$r['usuario_id'] ?></td><td><?= (int)$r['oficina_id'] ?></td><td><?= esc($r['desde'].' al '.$r['hasta']) ?></td><td><?= $r['aprobar']?'Sí':'No' ?></td></tr><?php endforeach ?></tbody></table>
<h2 class="h5">Clasificar turnos</h2><p>La modalidad del trabajador se administra en el catálogo de cargos. Aquí se clasifica únicamente la modalidad de los turnos.</p>
<form method="post" action="<?= site_url('asistencia/gestion-roles/modalidad') ?>" class="d-flex gap-2 mb-4"><?= csrf_field() ?><input type="hidden" name="entidad" value="turno"><input type="number" name="id" min="1" required placeholder="ID del registro" class="form-control"><select name="modalidad" class="form-select"><option>ASISTENCIAL</option><option>ADMINISTRATIVO</option></select><button class="btn btn-primary">Clasificar</button></form>

<?php endif ?>
</div>
<?= $this->endSection() ?>
