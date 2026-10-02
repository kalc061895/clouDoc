<?= $this->extend('layouts/asistenciaLayout') ?>
<?= $this->section('title') ?>Mi perfil y apariencia<?= $this->endSection() ?>
<?= $this->section('content') ?>
<h3 class="mb-2"><i class="ti ti-user-circle me-2 text-primary" aria-hidden="true"></i>Mi perfil y apariencia</h3>
<p class="text-muted mb-4">Información de tu cuenta y preferencias de presentación.</p>
<div class="row g-4">
    <div class="col-lg-6"><section class="card h-100 mb-0"><div class="card-body">
        <div class="d-flex align-items-center gap-3 mb-4"><?= view('partials/asistencia/avatar', ['perfilAsistencia' => $perfilAsistencia, 'avatarClass' => 'asis-avatar-lg']) ?><div><h4 class="mb-1"><?= esc($perfilAsistencia['nombre']) ?></h4><span class="badge bg-success-subtle text-success">Sesión activa</span></div></div>
        <dl class="asis-user-fields mb-0"><dt>Cargo</dt><dd><?= esc($perfilAsistencia['cargo']) ?></dd><dt>Usuario</dt><dd><?= esc($perfilAsistencia['username'] ?: 'No registrado') ?></dd><dt>Correo electrónico</dt><dd><?= esc($perfilAsistencia['email'] ?: 'No registrado') ?></dd></dl>
    </div></section></div>
    <div class="col-lg-6"><section class="card h-100 mb-0"><div class="card-body"><i class="ti ti-palette fs-8 text-primary" aria-hidden="true"></i><h4 class="mt-3">Tu espacio de trabajo</h4><p class="text-muted">Elige el tema claro u oscuro, color, menú, ancho de presentación y estilo de tarjetas. Tus preferencias se guardan automáticamente en tu perfil.</p><button type="button" class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample"><i class="ti ti-settings me-2" aria-hidden="true"></i>Personalizar apariencia</button></div></section></div>
</div>
<?= $this->endSection() ?>
