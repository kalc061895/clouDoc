<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Importar Personal<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid py-3">
  <div class="d-flex justify-content-between align-items-center mb-3"><div><h1 class="h3 mb-1">Importar Personal</h1><p class="text-muted mb-0">Descargue la plantilla, complete los datos como texto y revise las validaciones antes de guardar.</p></div><a class="btn btn-outline-primary" href="<?= site_url('asistencia/personal/importar/plantilla') ?>">Descargar plantilla Excel</a></div>
  <?php $errorImportacion = session()->getFlashdata('error'); ?>
  <?php if ($errorImportacion): ?><div class="alert alert-danger"><?= esc($errorImportacion) ?></div><?php endif ?>
  <?php if (!empty($resultado['completados'])): ?><div class="alert alert-success">Importación completada: <?= (int)$resultado['completados'] ?> registros.</div><?php endif ?>
  <div class="card mb-3"><div class="card-body"><form id="formPrevisualizar" method="post" enctype="multipart/form-data" action="<?= site_url('asistencia/personal/importar/previsualizar') ?>">
    <?= csrf_field() ?><label class="form-label" for="archivo_excel">Archivo Excel (.xlsx o .xls)</label><div class="d-flex gap-2"><input class="form-control" type="file" id="archivo_excel" name="archivo_excel" accept=".xlsx,.xls" required><button id="btnPrevisualizar" class="btn btn-primary" type="submit">Cargar y validar</button></div>
    <div class="form-text mt-2">Las relaciones se identifican por el nombre exacto del catálogo. Las fechas usan AAAA-MM-DD y los códigos/documentos se conservan como texto.</div>
  </form></div></div>
  <?php if (isset($resultado['validos'])): ?>
    <div class="alert alert-info">Filas válidas: <strong><?= count($resultado['validos']) ?></strong> · Filas con errores: <strong><?= count($resultado['errores'] ?? []) ?></strong></div>
    <?php if (!empty($resultado['errores'])): ?><div class="card mb-3"><div class="card-header fw-semibold">Errores por fila</div><div class="table-responsive"><table class="table table-sm table-striped mb-0"><thead><tr><th>Fila Excel</th><th>Detalle</th></tr></thead><tbody><?php foreach ($resultado['errores'] as $error): ?><tr><td><?= (int)$error['fila'] ?></td><td><?= esc(implode('; ', $error['errores'])) ?></td></tr><?php endforeach ?></tbody></table></div></div><?php endif ?>
    <?php if ($resultado['validos']): ?><div class="card mb-3"><div class="card-header fw-semibold">Vista previa (<?= count($resultado['validos']) ?> filas)</div><div class="table-responsive" style="max-height:360px"><table class="table table-sm table-bordered mb-0"><thead class="table-light"><tr><th>Documento</th><th>Apellidos y nombres</th><th>Código</th><th>Establecimiento</th><th>Modalidad</th><th>Profesión</th></tr></thead><tbody><?php foreach (array_slice($resultado['validos'],0,100) as $r): ?><tr><td><?= esc($r[0].' '.$r[1]) ?></td><td><?= esc(trim($r[2].' '.$r[3].', '.$r[4])) ?></td><td><?= esc($r[18]) ?></td><td><?= esc($r[14]) ?></td><td><?= esc($r[17]) ?></td><td><?= esc($r[25]) ?></td></tr><?php endforeach ?></tbody></table></div></div>
    <?php if (empty($resultado['errores'])): ?><form id="formConfirmar" method="post" action="<?= site_url('asistencia/personal/importar/confirmar') ?>"><?= csrf_field() ?><button class="btn btn-success" type="submit">Confirmar importación completa</button></form><?php else: ?><div class="alert alert-warning">La importación está bloqueada mientras existan errores. Corrija el Excel y vuelva a cargarlo.</div><?php endif ?><?php endif ?>
  <?php endif ?>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const error = <?= json_encode($errorImportacion, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
  const completados = <?= (int)($resultado['completados'] ?? 0) ?>;
  if (error && window.toastr) toastr.error(error, 'No se pudo completar');
  if (completados > 0 && window.toastr) toastr.success(`Se importaron ${completados} registros correctamente.`, 'Importación finalizada');

  const formPrevisualizar = document.getElementById('formPrevisualizar');
  formPrevisualizar?.addEventListener('submit', function (event) {
    if (!formPrevisualizar.reportValidity()) return;
    event.preventDefault();
    document.getElementById('btnPrevisualizar').disabled = true;
    Swal.fire({ title: 'Analizando archivo', text: 'Estamos validando el personal y sus datos.', allowOutsideClick: false, allowEscapeKey: false, didOpen: () => Swal.showLoading() });
    formPrevisualizar.submit();
  });

  const formConfirmar = document.getElementById('formConfirmar');
  formConfirmar?.addEventListener('submit', function (event) {
    event.preventDefault();
    Swal.fire({ title: '¿Confirmar importación?', text: 'Se guardarán <?= (int)count($resultado['validos'] ?? []) ?> personas.', icon: 'question', showCancelButton: true, confirmButtonText: 'Sí, importar', cancelButtonText: 'Cancelar' }).then((result) => {
      if (!result.isConfirmed) return;
      formConfirmar.querySelector('button[type="submit"]').disabled = true;
      Swal.fire({ title: 'Importando personal', text: 'Guardando los registros validados.', allowOutsideClick: false, allowEscapeKey: false, didOpen: () => Swal.showLoading() });
      formConfirmar.submit();
    });
  });
});
</script>
<?= $this->endSection() ?>
