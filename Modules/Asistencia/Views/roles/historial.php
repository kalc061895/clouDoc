<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Historial de roles<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div id="roles-app" data-modo="historial" data-base="<?= base_url('asistencia/roles') ?>">
    <div class="d-flex justify-content-between align-items-center mb-4"><div><h3>Historial de roles</h3><p class="text-muted mb-0">Cada documento conserva los turnos y los datos de su fecha de generación.</p></div><a class="btn btn-primary" href="<?= base_url('asistencia/roles/generacion') ?>">Generar rol</a></div>
    <form id="roles-filtros" class="card card-body"><?= view('Modules\Asistencia\Views\roles\filtros', ['catalogos' => $catalogos, 'historial' => true]) ?><div class="mt-3"><button class="btn btn-primary" type="submit">Buscar</button></div></form>
    <div id="roles-mensaje" role="status" aria-live="polite"></div>
    <div class="table-responsive"><table class="table table-bordered bg-white align-middle"><thead><tr><th>Documento / fecha</th><th>Mes / año</th><th>Establecimiento / ámbito</th><th>Personal</th><th>Horas</th><th>Estado</th><th>Acciones</th></tr></thead><tbody id="roles-historial"></tbody></table></div>
    <div class="d-flex gap-3 align-items-center"><button id="roles-anterior" class="btn btn-outline-secondary" type="button">Anterior</button><span id="roles-pagina"></span><button id="roles-siguiente" class="btn btn-outline-secondary" type="button">Siguiente</button></div>
    <p class="text-muted mt-3">Anular conserva el PDF original y registra el motivo. Los documentos generados aún no cuentan con firma digital.</p>
</div>
<script id="roles-config" type="application/json"><?= json_encode(['csrf' => ['name' => csrf_token(), 'hash' => csrf_hash()]], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>
<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?><script src="<?= base_url('assets/js/asistencia/roles.js') ?>"></script><?= $this->endSection() ?>
