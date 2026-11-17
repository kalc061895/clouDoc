<?php

declare(strict_types=1);

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;
use InvalidArgumentException;

/**
 * Client-side DataTables using the already installed jQuery DataTables plugin.
 * Pass the complete, authorized dataset; do not pass an already paginated query.
 * No database access, resource imports or user-specific caching inside this cell.
 */
class DataTableCell extends Cell
{
    public string $id = '';
    public array $columns = [];
    public array $rows = [];
    public string $caption = '';
    public int $pageLength = 10;
    public bool $searching = true;
    public bool $ordering = true;
    public bool $columnFilters = false;
    public array $order = [];

    public function render(): string
    {
        helper('ui');
        if (! preg_match('/^[a-zA-Z][a-zA-Z0-9_-]*$/D', $this->id)) {
            throw new InvalidArgumentException('DataTableCell needs a unique, CSS-safe id.');
        }
        if (! in_array($this->pageLength, [10, 25, 50, 100], true)) {
            throw new InvalidArgumentException('pageLength must be 10, 25, 50 or 100.');
        }
        $columns = ui_columns($this->columns);
        foreach ($this->order as $order) {
            if (
                ! is_array($order) || count($order) !== 2 || ! is_int($order[0] ?? null)
                || $order[0] < 0 || $order[0] >= count($columns)
                || ! in_array($order[1], ['asc', 'desc'], true)
            ) {
                throw new InvalidArgumentException('order must contain [columnIndex, asc|desc] pairs.');
            }
        }
        $options = [
            'pageLength' => $this->pageLength,
            'searching' => $this->searching,
            'ordering' => $this->ordering,
            'order' => $this->order,
            'columnDefs' => array_map(static fn($column, $index) => [
                'targets' => $index,
                'orderable' => (bool) ($column['orderable'] ?? true),
                'searchable' => (bool) ($column['searchable'] ?? true),
            ], $columns, array_keys($columns)),
        ];

        return $this->view('data_table', ['columns' => $columns, 'options' => $options]);
    }
}
