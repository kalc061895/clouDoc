<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Asistencia desde archivo<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid py-3"><h1 class="h3">Asistencia desde archivo</h1>
<p>Seleccione el reloj y su reporte. El formato se detecta al leer el archivo; las marcaciones se guardan después de revisar y confirmar.</p>
<?php foreach (['error'=>'danger','success'=>'success'] as $k=>$color): if(session()->getFlashdata($k)): ?><div class="alert alert-<?= $color ?>"><?= esc(session()->getFlashdata($k)) ?></div><?php endif; endforeach ?>
<form method="post" action="<?= site_url('asistencia/dispositivos/cargar') ?>" enctype="multipart/form-data" class="card card-body mb-4">
<?= csrf_field() ?><div class="row g-3">
<label class="col-md-6">Dispositivo<select name="dispositivo_id" class="form-select" required><option value="">Seleccione un dispositivo activo</option><?php foreach($equipos as $e): if(!$e['activo'])continue; ?><option value="<?= (int)$e['id'] ?>"><?= esc($e['codigo'].' — '.$e['nombre'].' / '.$e['est_nombre']) ?></option><?php endforeach ?></select></label>
<label class="col-md-6">Archivo del reloj (máximo 10 MB)<input type="file" class="form-control" name="archivo" accept=".dat,.txt,.csv,.log,.xls,.xlsx" required></label>
</div><p class="text-muted mt-3">ZKTeco: documento y fecha/hora separados por tabulación. Hikvision: Excel con la hoja Attendance Record. Se conserva el original, incluso si la validación encuentra errores.</p>
<button class="btn btn-primary align-self-start">Cargar y revisar archivo</button></form>
<h2 class="h5">Historial de cargas</h2><p>El mismo archivo en el mismo establecimiento abre su carga anterior, aunque cambie de nombre. Las horas se muestran en horario de Lima.</p>
<div class="table-responsive"><table class="table table-striped"><thead><tr><th>Código / archivo original</th><th>Establecimiento</th><th>Formato</th><th>Usuario de carga</th><th>Fecha de carga</th><th>Estado</th><th>Nuevas / duplicadas / errores</th><th></th></tr></thead><tbody>
<?php foreach($historial as $c): ?><tr><td><?= esc($c['imp_codigo']) ?><br><small><?= esc($c['imp_nombre_archivo']) ?></small></td><td><?= esc($c['est_nombre']) ?></td><td><?= esc($c['imp_origen']??'Por revisar') ?></td><td><?= esc($c['username']??('Usuario #'.$c['imp_user_ide'])) ?></td><td><?= esc($c['created_at']) ?></td><td><?= esc($c['imp_estado']) ?></td><td><?= (int)$c['imp_total_importados'] ?> / <?= (int)$c['imp_total_duplicados'] ?> / <?= (int)$c['imp_total_error'] ?></td><td><a href="<?= site_url('asistencia/dispositivos/cargas/'.(int)$c['imp_ide']) ?>">Ver detalle</a></td></tr><?php endforeach ?>
</tbody></table></div><nav><?php if($pagina>1): ?><a class="btn btn-outline-secondary" href="<?= site_url('asistencia/dispositivos/archivos?pagina='.($pagina-1)) ?>">Anterior</a><?php endif ?> <span>Página <?= (int)$pagina ?></span> <?php if(count($historial)===25): ?><a class="btn btn-outline-secondary" href="<?= site_url('asistencia/dispositivos/archivos?pagina='.($pagina+1)) ?>">Siguiente</a><?php endif ?></nav>
</div><?= $this->endSection() ?>
