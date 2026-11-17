<?php

/** Static by default. DataTableCell supplies dynamic=true and data-ui-table. */
helper('ui');
$columns = ui_columns($columns ?? []);
$rows = $rows ?? [];
$attributes = $attributes ?? [];
if (isset($id)) {
    $attributes['id'] = $id;
}
$attributes['class'] = trim('table table-striped table-bordered align-middle mb-0 w-100 ' . ($class ?? '') . ' ' . ($attributes['class'] ?? ''));
?>
<div class="table-responsive">
    <table<?= ui_attributes($attributes) ?>>
        <?php if (! empty($caption)): ?><caption class="caption-top"><?= ui_escape($caption) ?></caption><?php endif ?>
        <thead>
            <tr><?php foreach ($columns as $column): ?><th scope="col" class="<?= ui_escape($column['class'] ?? '') ?>"><?= ui_escape($column['label']) ?></th><?php endforeach ?></tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
                <tr><?php foreach ($columns as $column): ?><td class="<?= ui_escape($column['class'] ?? '') ?>"><?= ui_table_value($row, $column) ?></td><?php endforeach ?></tr>
            <?php endforeach ?>
            <?php // DataTables cannot parse a colspan row; let its emptyTable message handle it. 
            ?>
            <?php if (empty($rows) && empty($dynamic)): ?><tr>
                    <td colspan="<?= count($columns) ?>" class="text-center text-body-secondary py-4"><?= ui_escape($emptyMessage ?? 'No hay registros disponibles.') ?></td>
                </tr><?php endif ?>
        </tbody>
        <?php if (! empty($columnFilters)): ?>
            <tfoot>
                <tr><?php foreach ($columns as $index => $column): ?><th>
                            <?php if ($column['searchable'] ?? true): ?>
                                <input class="form-control form-control-sm" type="search" data-ui-column="<?= $index ?>" aria-label="<?= ui_escape('Filtrar ' . $column['label']) ?>" placeholder="<?= ui_escape($column['label']) ?>">
                            <?php endif ?>
                        </th><?php endforeach ?></tr>
            </tfoot>
        <?php endif ?>
        </table>
</div>