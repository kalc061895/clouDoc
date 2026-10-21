<nav class="d-flex flex-wrap gap-2 mb-4" aria-label="Gestión de roles">
    <a class="btn btn-outline-primary" href="<?= site_url('asistencia/gestion-roles') ?>">Roles por oficina</a>
    <a class="btn btn-outline-primary" href="<?= site_url('asistencia/programacion') ?>">Programación</a>
    <?php if ($admin): ?>
    <a class="btn btn-outline-primary" href="<?= site_url('asistencia/gestion-roles/periodos') ?>">Períodos de roles</a>
    <a class="btn btn-outline-primary" href="<?= site_url('asistencia/gestion-roles/plantillas') ?>">Plantillas de reglas</a>
    <?php endif ?>
</nav>
