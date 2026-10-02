<li class="nav-item dropdown asis-account">
    <button class="nav-link asis-account-toggle d-flex align-items-center gap-2 border-0 bg-transparent" type="button" id="asis-account-<?= esc($orientacion, 'attr') ?>" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Abrir información de <?= esc($perfilAsistencia['nombre'], 'attr') ?>">
        <?= view('partials/asistencia/avatar', ['perfilAsistencia' => $perfilAsistencia, 'avatarClass' => '']) ?>
        <span class="asis-account-text text-start d-none d-md-block"><strong title="<?= esc($perfilAsistencia['nombre'], 'attr') ?>"><?= esc($perfilAsistencia['nombre']) ?></strong><small title="<?= esc($perfilAsistencia['cargo'], 'attr') ?>"><?= esc($perfilAsistencia['cargo']) ?></small></span>
        <i class="ti ti-chevron-down" aria-hidden="true"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end asis-account-menu p-0" aria-labelledby="asis-account-<?= esc($orientacion, 'attr') ?>">
        <div class="asis-account-summary p-4">
            <div class="d-flex align-items-center gap-3 mb-3"><?= view('partials/asistencia/avatar', ['perfilAsistencia' => $perfilAsistencia, 'avatarClass' => 'asis-avatar-lg']) ?><div class="asis-account-details"><strong><?= esc($perfilAsistencia['nombre']) ?></strong><div class="text-muted small"><?= esc($perfilAsistencia['cargo']) ?></div></div></div>
            <?php if ($perfilAsistencia['email']): ?><div class="asis-account-email small"><i class="ti ti-mail me-1" aria-hidden="true"></i><?= esc($perfilAsistencia['email']) ?></div><?php endif ?>
        </div>
        <div class="p-2"><button type="button" class="dropdown-item rounded d-flex align-items-center gap-2 py-2" data-bs-toggle="modal" data-bs-target="#asis-user-modal"><i class="ti ti-user-circle text-primary" aria-hidden="true"></i>Mi información</button><button type="button" class="dropdown-item rounded d-flex align-items-center gap-2 py-2" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="ti ti-search text-primary" aria-hidden="true"></i>Buscar una funcionalidad</button></div>
        <div class="px-2 pb-2"><button type="button" class="dropdown-item rounded d-flex align-items-center gap-2 py-2" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample"><i class="ti ti-palette text-primary" aria-hidden="true"></i>Mi apariencia</button></div>
        <div class="border-top p-2"><a class="dropdown-item rounded d-flex align-items-center gap-2 py-2 text-danger" href="<?= base_url('logout') ?>"><i class="ti ti-logout" aria-hidden="true"></i>Cerrar sesión</a></div>
    </div>
</li>
