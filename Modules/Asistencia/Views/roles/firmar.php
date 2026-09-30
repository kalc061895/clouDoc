<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Firmar roles<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="card card-body"><h3>Firmar roles</h3><p>La firma de roles estará disponible en una siguiente etapa. Por ahora puede generar los documentos y consultar su historial.</p><div><a class="btn btn-primary" href="<?= base_url('asistencia/roles/historial') ?>">Ver historial de roles</a></div></div>
<?= $this->endSection() ?>
