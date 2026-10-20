<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Configuración de dispositivos<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid py-3">
<h1 class="h3">Configuración de dispositivos</h1>
<p>Registre los relojes y su establecimiento. La recepción de marcaciones se realiza mediante sus archivos exportados.</p>
<a class="btn btn-outline-primary mb-3" href="<?= site_url('asistencia/dispositivos/archivos') ?>">Asistencia desde archivo</a>
<?php foreach (['error'=>'danger','success'=>'success'] as $k=>$color): if(session()->getFlashdata($k)): ?><div class="alert alert-<?= $color ?>"><?= esc(session()->getFlashdata($k)) ?></div><?php endif; endforeach ?>
<div class="card card-body mb-4"><h2 class="h5"><?= $editar?'Editar dispositivo':'Nuevo dispositivo' ?></h2>
<form method="post" action="<?= site_url('asistencia/dispositivos/guardar') ?>">
<?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)($editar['id']??0) ?>">
<div class="row g-3">
<label class="col-md-3">Código<input class="form-control" name="codigo" required maxlength="40" pattern="[A-Za-z0-9_-]+" value="<?= esc($editar['codigo']??'','attr') ?>"></label>
<label class="col-md-5">Nombre del equipo<input class="form-control" name="nombre" required maxlength="150" value="<?= esc($editar['nombre']??'','attr') ?>"></label>
<label class="col-md-4">Marca<select name="marca" class="form-select" required><?php foreach(['ZKTECO'=>'ZKTeco','HIKVISION'=>'Hikvision'] as $v=>$label): ?><option value="<?= $v ?>" <?= ($editar['marca']??'')===$v?'selected':'' ?>><?= $label ?></option><?php endforeach ?></select></label>
<label class="col-md-6">Establecimiento<select name="est_id" class="form-select" required><option value="">Seleccione</option><?php foreach($establecimientos as $e): ?><option value="<?= (int)$e['est_ide'] ?>" <?= ($editar['est_id']??'')==$e['est_ide']?'selected':'' ?>><?= esc($e['est_nombre']) ?></option><?php endforeach ?></select></label>
<?php foreach(['modelo'=>'Modelo','serie'=>'Número de serie','ip'=>'Dirección IP'] as $key=>$label): ?><label class="col-md-3"><?= $label ?><input class="form-control" name="<?= $key ?>" maxlength="<?= $key==='ip'?45:100 ?>" value="<?= esc($editar[$key]??'','attr') ?>"></label><?php endforeach ?>
<label class="col-md-3">Puerto<input type="number" min="1" max="65535" class="form-control" name="puerto" value="<?= esc($editar['puerto']??'','attr') ?>"></label>
<label class="col-md-3">Estado<select name="activo" class="form-select"><option value="1" <?= ($editar['activo']??1)?'selected':'' ?>>Activo</option><option value="0" <?= isset($editar['activo'])&&!$editar['activo']?'selected':'' ?>>Inactivo</option></select></label>
</div><p class="text-muted mt-3">IP y puerto se conservan como configuración del equipo. La conexión directa al reloj todavía no está habilitada.</p>
<button class="btn btn-primary">Guardar dispositivo</button> <a class="btn btn-light" href="<?= site_url('asistencia/dispositivos') ?>">Limpiar formulario</a>
</form></div>
<div class="table-responsive"><table class="table table-striped"><thead><tr><th>Código</th><th>Nombre</th><th>Marca</th><th>Establecimiento</th><th>IP / puerto</th><th>Estado</th><th></th></tr></thead><tbody>
<?php foreach($equipos as $e): ?><tr><td><?= esc($e['codigo']) ?></td><td><?= esc($e['nombre']) ?></td><td><?= esc($e['marca']) ?></td><td><?= esc($e['est_nombre']) ?></td><td><?= esc(($e['ip']??'').' / '.($e['puerto']??'')) ?></td><td><?= $e['activo']?'Activo':'Inactivo' ?></td><td><a href="<?= site_url('asistencia/dispositivos?editar='.(int)$e['id']) ?>">Editar</a></td></tr><?php endforeach ?>
</tbody></table></div></div>
<?= $this->endSection() ?>
