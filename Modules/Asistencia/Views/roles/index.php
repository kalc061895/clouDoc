<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Generación de roles<?= $this->endSection() ?>
<?= $this->section('pageStyles') ?>
<style>
.rol-preview { overflow:auto; max-height:650px; }
.rol-tabla { border-collapse:collapse; width:100%; min-width:1550px; table-layout:fixed; font-size:11px; }
.rol-tabla td,.rol-tabla th { border:1px solid #bcc8d3; padding:5px 2px; text-align:center; vertical-align:middle; overflow-wrap:break-word; }
.rol-tabla th { background:#e2eaf2; }.rol-tabla .nombre { text-align:left; }.rol-tabla .fin-semana { background:#f2f4f6; }
.rol-tabla .turno { padding:5px 1px; font-weight:bold; margin:1px 0; }.rol-tabla .horas { font-weight:bold; }
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div id="roles-app" data-modo="generacion" data-base="<?= base_url('asistencia/roles') ?>">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3"><div><h1 class="h3 mb-1"><iconify-icon icon="solar:document-add-bold-duotone" class="text-primary me-2"></iconify-icon>Generación de roles</h1><p class="text-muted mb-0">Consulte la programación mensual y genere el documento PDF.</p></div><div class="d-flex gap-2"><a class="btn btn-outline-primary" href="<?= base_url('asistencia/roles/historial') ?>"><iconify-icon icon="lucide:history" class="me-1"></iconify-icon>Historial</a><a class="btn btn-outline-secondary" href="<?= base_url('asistencia/roles/firmar') ?>"><iconify-icon icon="lucide:pen-line" class="me-1"></iconify-icon>Firmar roles</a></div></div>
    <form id="roles-filtros" class="card border-0 shadow-sm mb-3"><div class="card-body">
        <?= view('Modules\Asistencia\Views\roles\filtros', ['catalogos' => $catalogos]) ?>
        <div class="d-flex flex-wrap gap-2 mt-4"><button class="btn btn-primary" type="submit"><iconify-icon icon="lucide:search" class="me-1"></iconify-icon>Consultar roles</button><button id="rol-generar" class="btn btn-success" type="button" disabled><iconify-icon icon="lucide:file-down" class="me-1"></iconify-icon>Generar y guardar PDF</button><a id="rol-pdf" class="btn btn-outline-primary" target="_blank" rel="noopener" hidden><iconify-icon icon="lucide:file-text" class="me-1"></iconify-icon>Ver PDF guardado</a></div>
        <small class="text-muted mt-2">Se incluirá el personal activo con turnos programados en el ámbito elegido. El PDF se guardará como documento sin firma digital.</small>
    </div></form>
    <div id="roles-mensaje" role="status" aria-live="polite" class="visually-hidden"></div>
    <div id="roles-resumen" class="alert alert-light border mb-3" hidden></div>
    <div id="roles-preview-card" class="card border-0 shadow-sm" hidden><div class="card-body"><div id="roles-preview" class="rol-preview bg-white rounded"></div></div></div>
</div>
<script id="roles-config" type="application/json"><?= json_encode(['catalogos' => $catalogos, 'csrf' => ['name' => csrf_token(), 'hash' => csrf_hash()]], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>
<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?><script src="<?= base_url('assets/js/asistencia/roles.js') ?>"></script><?= $this->endSection() ?>
