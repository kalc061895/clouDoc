<?php

/** @var array $columns @var array $options All assets are owned by the layout. */
echo view('components/table', [
    'id' => $id,
    'columns' => $columns,
    'rows' => $rows,
    'caption' => $caption,
    'dynamic' => true,
    'columnFilters' => $columnFilters && $searching,
    'attributes' => ['data-ui-table' => json_encode($options, JSON_THROW_ON_ERROR)],
], ['saveData' => false]);
