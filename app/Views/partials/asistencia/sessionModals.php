<div class="modal fade" id="asis-user-modal" tabindex="-1" aria-labelledby="asis-user-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
        <div class="modal-header"><h5 id="asis-user-title" class="modal-title">Mi información</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button></div>
        <div class="modal-body p-4"><div class="d-flex align-items-center gap-3 mb-4"><?= view('partials/asistencia/avatar', ['perfilAsistencia' => $perfilAsistencia, 'avatarClass' => 'asis-avatar-lg']) ?><div class="asis-account-details"><h5 class="mb-1"><?= esc($perfilAsistencia['nombre']) ?></h5><span class="badge bg-success-subtle text-success">Sesión activa</span></div></div>
            <dl class="asis-user-fields mb-0"><dt><i class="ti ti-briefcase" aria-hidden="true"></i> Cargo</dt><dd><?= esc($perfilAsistencia['cargo']) ?></dd><dt><i class="ti ti-user" aria-hidden="true"></i> Usuario</dt><dd><?= esc($perfilAsistencia['username'] ?: 'No registrado') ?></dd><dt><i class="ti ti-mail" aria-hidden="true"></i> Correo electrónico</dt><dd><?= esc($perfilAsistencia['email'] ?: 'No registrado') ?></dd></dl>
        </div>
    </div></div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="asis-search-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered"><div class="modal-content">
        <div class="modal-header"><h5 id="asis-search-title" class="modal-title"><i class="ti ti-search me-2 text-primary" aria-hidden="true"></i>Buscar funcionalidad</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button></div>
        <div class="modal-body"><label for="asis-menu-query" class="visually-hidden">Nombre o categoría de la funcionalidad</label><input id="asis-menu-query" type="search" class="form-control form-control-lg" placeholder="Ej. personal, turnos, reportes…" autocomplete="off" maxlength="100" aria-controls="asis-menu-results" aria-describedby="asis-search-status"><p id="asis-search-status" class="text-muted small mt-3 mb-2" role="status" aria-live="polite"></p><ul id="asis-menu-results" class="list-unstyled mb-0"></ul><noscript><p>Activa JavaScript para buscar. También puedes acceder desde el menú lateral.</p></noscript></div>
        <div class="modal-footer justify-content-between text-muted small"><span>Sólo opciones habilitadas para tu usuario</span><span>↑ ↓ navegar · Enter abrir · Esc cerrar</span></div>
    </div></div>
</div>
<script type="application/json" id="asis-menu-options"><?= json_encode($opcionesAsistencia, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?></script>
