<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Detalle de carga<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php $equipo=json_decode($carga['imp_dispositivo_snapshot'],true)??[]; $preview=json_decode($carga['imp_observacion']??'{}',true)??[]; $errores=json_decode($carga['imp_errores']??'[]',true)??[]; ?>
<div class="container-fluid py-3"><h1 class="h3">Carga <?= esc($carga['imp_codigo']) ?></h1>
<?php foreach (['error'=>'danger','success'=>'success'] as $k=>$color): if(session()->getFlashdata($k)): ?><div class="alert alert-<?= $color ?>"><?= esc(session()->getFlashdata($k)) ?></div><?php endif; endforeach ?>
<div class="card card-body mb-3"><dl class="row mb-0">
<?php foreach(['Archivo original'=>$carga['imp_nombre_archivo'],'Equipo'=>($equipo['codigo']??'').' / '.($equipo['nombre']??''),'Formato detectado'=>$carga['imp_origen']??'No reconocido','Usuario de carga'=>$carga['username']??('Usuario #'.$carga['imp_user_ide']),'Fecha de carga'=>$carga['created_at'],'Estado'=>$carga['imp_estado'],'Fecha de importación'=>$carga['imp_fecha_proceso']??'Pendiente','Confirmado por'=>$carga['imp_confirmado_por']?'Usuario #'.$carga['imp_confirmado_por']:'Pendiente'] as $label=>$value): ?><dt class="col-sm-3"><?= esc($label) ?></dt><dd class="col-sm-9"><?= esc($value) ?></dd><?php endforeach ?>
</dl><p>Leídas: <?= (int)$carga['imp_total_registros'] ?> · Importadas: <?= (int)$carga['imp_total_importados'] ?> · Duplicadas: <?= (int)$carga['imp_total_duplicados'] ?> · Errores: <?= (int)$carga['imp_total_error'] ?></p>
<a href="<?= site_url('asistencia/dispositivos/cargas/'.(int)$carga['imp_ide'].'/archivo') ?>">Descargar original archivado</a></div>
<?php if($errores): ?><div class="alert alert-danger"><strong>No se importó este archivo. Corrija los errores o complete los datos del personal y vuelva a revisar.</strong><ul><?php foreach($errores as $error): ?><li><?= esc($error) ?></li><?php endforeach ?></ul><small>Se muestran hasta 100 errores.</small></div><?php endif ?>
<?php if($carga['imp_estado']!=='PROCESADO'): ?>
<p>Nuevas marcaciones listas: <?= (int)($preview['nuevos']??0) ?>. Se validarán de nuevo al confirmar. No se restaura ninguna marcación eliminada previamente.</p>
<div class="d-flex gap-2 mb-3"><form method="post" action="<?= site_url('asistencia/dispositivos/cargas/'.(int)$carga['imp_ide'].'/revisar') ?>"><?= csrf_field() ?><button class="btn btn-outline-secondary">Volver a revisar</button></form>
<?php if($carga['imp_estado']==='VALIDADO'): ?><form method="post" action="<?= site_url('asistencia/dispositivos/cargas/'.(int)$carga['imp_ide'].'/confirmar') ?>"><?= csrf_field() ?><button class="btn btn-primary">Confirmar importación</button></form><?php endif ?></div>
<?php endif ?>
<?php if(!empty($preview['muestra'])): ?><h2 class="h5">Vista previa (hasta 30 marcaciones nuevas al revisar)</h2><table class="table"><thead><tr><th>Documento</th><th>Fecha y hora</th><th>Origen en archivo</th></tr></thead><tbody><?php foreach($preview['muestra'] as $marca): ?><tr><td><?= esc($marca['documento']) ?></td><td><?= esc($marca['fecha']) ?></td><td><?= esc($marca['ubicacion']) ?></td></tr><?php endforeach ?></tbody></table><?php endif ?>
<a class="btn btn-light" href="<?= site_url('asistencia/dispositivos/archivos') ?>">Volver a las cargas</a></div>
<?= $this->endSection() ?>
