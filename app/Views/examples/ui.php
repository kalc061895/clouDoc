<?php
/** Usage gallery: return view('examples/ui', ['rows' => $authorizedRows]) from a protected controller. */
helper(['ui', 'url']);
$rows = $rows ?? [];
$table = (new \App\Cells\DataTableCell())->fill([
    'id' => 'ui-example-users',
    'caption' => 'Usuarios autorizados',
    'rows' => $rows,
    'columnFilters' => true,
    'columns' => [
        ['key' => 'nombre', 'label' => 'Nombre'],
        ['key' => 'email', 'label' => 'Correo'],
        ['key' => 'activo', 'label' => 'Estado', 'html' => true,
            'format' => static fn ($value) => ui_badge($value ? 'Activo' : 'Inactivo', $value ? 'success' : 'secondary')],
    ],
])->render();
?>
<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Componentes UI<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?= view('components/breadcrumbs', ['items' => [
    ['label' => 'Inicio', 'url' => site_url('inicio')], ['label' => 'Componentes UI'],
]], ['saveData' => false]) ?>
<?= view('components/alert', ['variant' => 'info', 'message' => 'Esta galería reutiliza los recursos de la plantilla.', 'dismissible' => true], ['saveData' => false]) ?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <h1 class="fs-7 mb-0">Componentes reutilizables</h1>
    <?= ui_button('Abrir modal', ['icon' => 'ti ti-plus', 'attrs' => ['data-bs-toggle' => 'modal', 'data-bs-target' => '#ui-example-modal']]) ?>
</div>
<?= view('components/card', [
    'title' => 'Tabla dinámica',
    'bodyHtml' => $table,
    'actionsHtml' => ui_button('Ayuda', ['href' => site_url('/') . '#ayuda', 'variant' => 'secondary', 'size' => 'sm']),
], ['saveData' => false]) ?>
<?= view('components/card', [
    'title' => 'Estado y preferencias',
    'bodyHtml' => ui_badge('Disponible', 'success', ['pill' => true])
        . ui_switch('notificaciones', true, ['id' => 'ui-example-notifications', 'label' => 'Recibir notificaciones']),
], ['saveData' => false]) ?>
<?= view('components/card', [
    'title' => 'Campos y acciones',
    'bodyHtml' => view('components/form_field', ['name' => 'ejemplo-correo', 'type' => 'email', 'label' => 'Correo', 'helpText' => 'Escriba su correo institucional.'], ['saveData' => false])
        . view('components/form_field', ['name' => 'ejemplo-estado', 'type' => 'select', 'label' => 'Estado', 'value' => 1, 'options' => [1 => 'Activo', 0 => 'Inactivo']], ['saveData' => false])
        . view('components/dropdown', ['id' => 'ejemplo-acciones', 'items' => [
            ['label' => 'Abrir modal', 'attrs' => ['data-bs-toggle' => 'modal', 'data-bs-target' => '#ui-example-modal']],
            ['label' => 'Ir al portal', 'href' => site_url('/')],
        ]], ['saveData' => false]),
], ['saveData' => false]) ?>
<?= view('components/card', ['title' => 'Sin resultados', 'bodyHtml' => view('components/empty_state', [
    'title' => 'No hay materiales publicados.', 'description' => 'Los materiales aparecerán aquí cuando estén disponibles.',
], ['saveData' => false])], ['saveData' => false]) ?>
<?= $this->endSection() ?>
<?= $this->section('modals') ?>
<?= view('components/modal', [
    'id' => 'ui-example-modal',
    'title' => 'Modal de ejemplo',
    'size' => 'lg',
    'bodyHtml' => '<p>Los formularios reales deben renderizarse desde una vista con su campo CSRF.</p>',
], ['saveData' => false]) ?>
<?= $this->endSection() ?>
