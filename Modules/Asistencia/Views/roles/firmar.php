<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Firmar roles<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid py-3"><div class="card border-0 shadow-sm"><div class="card-body p-4"><div class="d-flex align-items-center gap-2 mb-2"><iconify-icon icon="solar:pen-new-square-bold-duotone" class="text-primary" style="font-size:1.8rem"></iconify-icon><h1 class="h3 mb-0">Firma de roles</h1></div><p class="text-muted">Los documentos de rol disponibles para firma y sus versiones se administran en el módulo de Firma.</p><div class="d-flex flex-wrap gap-2"><a class="btn btn-primary" href="<?= base_url('firma') ?>"><iconify-icon icon="lucide:pen-line" class="me-1"></iconify-icon>Ir a Firma</a><a class="btn btn-outline-secondary" href="<?= base_url('asistencia/roles/historial') ?>"><iconify-icon icon="lucide:history" class="me-1"></iconify-icon>Volver al historial</a></div></div></div></div>
<?= $this->endSection() ?>
