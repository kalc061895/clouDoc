<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Firmar roles<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card card-body"><h3>Firmar roles</h3><p>Los roles se firman desde el módulo de Firma de documentos.</p><div><a class="btn btn-primary" href="<?= base_url('firma') ?>">Ir a Firma</a></div></div>
<?= $this->endSection() ?>
