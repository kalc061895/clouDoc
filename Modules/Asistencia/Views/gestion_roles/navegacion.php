<nav class="d-flex flex-wrap gap-2 mb-4" aria-label="Gestión de roles">
    <a class="btn btn-outline-primary" href="<?= site_url('asistencia/gestion-roles') ?>"><iconify-icon icon="lucide:layout-list" class="me-1"></iconify-icon>Roles por oficina</a>
    <a class="btn btn-outline-primary" href="<?= site_url('asistencia/gestion-roles/envios') ?>"><iconify-icon icon="lucide:inbox" class="me-1"></iconify-icon>Envíos y aprobaciones</a>
    <?php if ($admin): ?><a class="btn btn-outline-primary" href="<?= site_url('asistencia/gestion-roles/delegaciones') ?>"><iconify-icon icon="lucide:users-round" class="me-1"></iconify-icon>Delegaciones temporales</a><?php endif ?>
    <?php if ($admin): ?><a class="btn btn-outline-primary" href="<?= site_url('asistencia/gestion-roles/turnos') ?>"><iconify-icon icon="lucide:clock-3" class="me-1"></iconify-icon>Modalidad de turnos</a><?php endif ?>
    <a class="btn btn-outline-primary" href="<?= site_url('asistencia/programacion') ?>"><iconify-icon icon="lucide:calendar-days" class="me-1"></iconify-icon>Programación</a>
    <?php if ($admin): ?>
    <a class="btn btn-outline-primary" href="<?= site_url('asistencia/gestion-roles/periodos') ?>"><iconify-icon icon="lucide:calendar-range" class="me-1"></iconify-icon>Períodos</a>
    <a class="btn btn-outline-primary" href="<?= site_url('asistencia/gestion-roles/plantillas') ?>"><iconify-icon icon="lucide:settings-2" class="me-1"></iconify-icon>Plantillas de reglas</a>
    <?php endif ?>
</nav>
